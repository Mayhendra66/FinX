<?php

namespace App\Http\Controllers;

use App\Models\SavingGoal;
use App\Models\Akun;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SavingGoalController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        $savingGoals = SavingGoal::with(['account', 'user'])
            ->where('user_id', $userId)
            ->get();
        $accounts = Akun::where('user_id', $userId)->get();

        return view('SavingGoal.index', compact('savingGoals', 'accounts'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'target_amount' => 'required|numeric|min:1000',
            'current_amount' => 'nullable|numeric|min:0',
            'category' => 'required|string',
            'deadline' => 'required|date',
            'account_id' => 'required|exists:akun,id',
        ]);

        DB::transaction(function () use ($request) {
            $currentAmount = $request->input('current_amount', 0);

            // Buat data Saving Goal baru
            SavingGoal::create([
                'user_id' => Auth::id(),
                'account_id' => $request->account_id,
                'name' => $request->name,
                'target_amount' => $request->target_amount,
                'current_amount' => $currentAmount,
                'category' => $request->category,
                'deadline' => $request->deadline,
            ]);

            // Jika ada dana awal, potong langsung dari saldo akun terpilih
            if ($currentAmount > 0) {
                $akun = Akun::where('user_id', Auth::id())->findOrFail($request->account_id);
                $akun->decrement('balance', $currentAmount);
            }
        });
    

        return redirect()->route('saving-goals.index')->with('success', 'Komitmen impian berhasil ditambahkan.');
    }

    /**
     * Update resource untuk Setor & Tarik Dana.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'type' => 'required|in:setor,tarik',
            'account_id' => 'required|exists:akun,id',
            'amount' => 'required|numeric|min:1',
        ]);

        return DB::transaction(function () use ($request, $id) {
            $goal = SavingGoal::where('user_id', Auth::id())->findOrFail($id);
            $akun = Akun::where('user_id', Auth::id())->findOrFail($request->account_id);
            $amount = $request->amount;

            if ($request->type === 'setor') {
                if ($akun->balance < $amount) {
                    return response()->json(['success' => false, 'message' => 'Saldo akun tidak mencukupi.'], 422);
                }
                $goal->increment('current_amount', $amount);
                $akun->decrement('balance', $amount);
            } else { // tarik
                if ($goal->current_amount < $amount) {
                    return response()->json(['success' => false, 'message' => 'Dana simpanan tidak mencukupi untuk ditarik.'], 422);
                }
                $goal->decrement('current_amount', $amount);
                $akun->increment('balance', $amount);
            }

            return response()->json(['success' => true, 'message' => 'Transaksi berhasil diperbarui.']);
        });
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        return DB::transaction(function () use ($id) {
            $goal = SavingGoal::where('user_id', Auth::id())->findOrFail($id);

            // Kembalikan seluruh saldo terkumpul ke akun terkait sebelum dihapus
            if ($goal->current_amount > 0) {
                $akun = Akun::where('user_id', Auth::id())->findOrFail($goal->account_id);
                $akun->increment('balance', $goal->current_amount);
            }

            $goal->delete();

            return response()->json(['success' => true, 'message' => 'Target impian berhasil dihapus dan saldo telah dikembalikan ke akun Anda.']);
        });
    }
}
