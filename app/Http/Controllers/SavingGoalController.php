<?php

namespace App\Http\Controllers;

use App\Models\SavingGoal;
use App\Models\MainAccountModel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SavingGoalController extends Controller
{
    // ----------------------------------------------------------------
    // CEK JAM PRODUKTIF 08:00 - 21:00
    // ----------------------------------------------------------------
    private function isJamProduktif(): bool
    {
        $hour = Carbon::now()->hour;
        return $hour >= 8 && $hour < 21;
    }

    // ----------------------------------------------------------------
    // INDEX
    // ----------------------------------------------------------------
    public function index()
    {
        $userId      = Auth::id();
        $savingGoals = SavingGoal::where('user_id', $userId)->get();
        $mainAccount = MainAccountModel::where('user_id', $userId)->first();

        return view('SavingGoal.index', compact('savingGoals', 'mainAccount'));
    }

    // ----------------------------------------------------------------
    // MARK INTRO SEEN
    // ----------------------------------------------------------------
    public function markIntroSeen()
    {
        Auth::user()->update(['saving_goals_intro_seen' => true]);
        return response()->json(['success' => true]);
    }

    // ----------------------------------------------------------------
    // STORE — buat saving goal baru, potong dari main account
    // ----------------------------------------------------------------
    public function store(Request $request)
    {
        // Guard jam produktif
        if (!$this->isJamProduktif()) {
            return back()->withErrors(['jam' => 'Transaksi hanya bisa dilakukan jam 08.00–21.00 WIB.']);
        }

        $request->validate([
            'name'           => 'required|string|max:255',
            'target_amount'  => 'required|numeric|min:1000',
            'current_amount' => 'nullable|numeric|min:0',
            'category'       => 'required|string',
            'deadline'       => 'required|date',
        ]);

        DB::transaction(function () use ($request) {
            $userId        = Auth::id();
            $initialAmount = $request->input('current_amount', 0);

            // Validasi saldo main account jika ada dana awal
            if ($initialAmount > 0) {
                $mainAccount = MainAccountModel::where('user_id', $userId)->firstOrFail();
                if ($mainAccount->balance < $initialAmount) {
                    abort(422, 'Saldo Main Account tidak mencukupi.');
                }
                $mainAccount->decrement('balance', $initialAmount);
            }

            SavingGoal::create([
                'user_id'        => $userId,
                'name'           => $request->name,
                'target_amount'  => $request->target_amount,
                'current_amount' => $initialAmount,
                'category'       => $request->category,
                'deadline'       => $request->deadline,
            ]);
        });

        return redirect()->route('saving-goals.index')->with('success', 'Komitmen impian berhasil ditambahkan.');
    }

    // ----------------------------------------------------------------
    // UPDATE — setor / tarik dana, main account ikut berubah
    // ----------------------------------------------------------------
    public function update(Request $request, string $id)
    {
        // Guard jam produktif
        if (!$this->isJamProduktif()) {
            return response()->json([
                'success' => false,
                'message' => 'Transaksi hanya bisa dilakukan jam 08.00–21.00 WIB.'
            ], 403);
        }

        $request->validate([
            'type'   => 'required|in:setor,tarik',
            'amount' => 'required|numeric|min:1',
        ]);

        return DB::transaction(function () use ($request, $id) {
            $userId      = Auth::id();
            $goal        = SavingGoal::where('user_id', $userId)->findOrFail($id);
            $mainAccount = MainAccountModel::where('user_id', $userId)->firstOrFail();
            $amount      = $request->amount;

            if ($request->type === 'setor') {
                if ($mainAccount->balance < $amount) {
                    return response()->json(['success' => false, 'message' => 'Saldo Main Account tidak mencukupi.'], 422);
                }
                $goal->increment('current_amount', $amount);
                $mainAccount->decrement('balance', $amount);

                return response()->json(['success' => true, 'message' => "Rp " . number_format($amount, 0, ',', '.') . " berhasil disetor ke tabungan."]);

            } else {
                // Tarik
                if ($goal->current_amount < $amount) {
                    return response()->json(['success' => false, 'message' => 'Dana simpanan tidak mencukupi untuk ditarik.'], 422);
                }
                $goal->decrement('current_amount', $amount);
                $mainAccount->increment('balance', $amount);

                return response()->json(['success' => true, 'message' => "Rp " . number_format($amount, 0, ',', '.') . " berhasil ditarik ke Main Account."]);
            }
        });
    }

    // ----------------------------------------------------------------
    // DESTROY — hapus goal, kembalikan saldo ke main account
    // ----------------------------------------------------------------
    public function destroy(string $id)
    {
        if (!$this->isJamProduktif()) {
            return response()->json([
                'success' => false,
                'message' => 'Penghapusan hanya bisa dilakukan jam 08.00–21.00 WIB.'
            ], 403);
        }

        return DB::transaction(function () use ($id) {
            $userId      = Auth::id();
            $goal        = SavingGoal::where('user_id', $userId)->findOrFail($id);
            $mainAccount = MainAccountModel::where('user_id', $userId)->firstOrFail();

            if ($goal->current_amount > 0) {
                $mainAccount->increment('balance', $goal->current_amount);
            }

            $goal->delete();

            return response()->json(['success' => true, 'message' => 'Target impian dihapus dan saldo dikembalikan ke Main Account.']);
        });
    }
}