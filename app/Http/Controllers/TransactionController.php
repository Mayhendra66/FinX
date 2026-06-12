<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Akun;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class TransactionController extends Controller
{
    public function index(Request $request) 
    {
        $query = Transaction::with(['category', 'account'])
            ->where('user_id', Auth::id());

        if ($request->filled('search')) {
            $query->where('note', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('type') && $request->type !== 'all') {
            $query->where('type', $request->type);
        }

        // ✅ Filter category scope user
        if ($request->filled('category_id') && $request->category_id !== 'all') {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('id', $request->category_id)
                  ->where('user_id', Auth::id());
            });
        }

        // ✅ Filter account scope user
        if ($request->filled('account_id') && $request->account_id !== 'all') {
            $query->whereHas('account', function ($q) use ($request) {
                $q->where('id', $request->account_id)
                  ->where('user_id', Auth::id());
            });
        }

        if ($request->filled('start_date')) {
            $query->whereDate('transaction_date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('transaction_date', '<=', $request->end_date);
        }

        $transactions = $query->orderByDesc('transaction_date')->get();

        // ✅ Dropdown hanya milik user login
        $accounts   = Akun::where('user_id', Auth::id())->orderBy('name')->get();
        $categories = Category::where('user_id', Auth::id())->orderBy('name')->get();

        return view('transactions.main.index', compact(
            'transactions',
            'accounts',
            'categories'
        ));
    }

    public function create()
    {
        return redirect()->route('transactions.index');
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'type'             => 'required|in:income,expense',
            // ✅ Validasi account harus milik user
            'account_id'       => [
                'required',
                Rule::exists('akun', 'id')->where('user_id', Auth::id()),
            ],
            // ✅ Validasi category harus milik user
            'category_id'      => [
                'required',
                Rule::exists('categories', 'id')->where('user_id', Auth::id()),
            ],
            'amount'           => 'required|numeric|min:1',
            'transaction_date' => 'required|date',
            'note'             => 'nullable|string|max:500',
        ]);

        $validated['user_id'] = Auth::id();

        $akun = Akun::where('id', $validated['account_id'])
                    ->where('user_id', Auth::id())
                    ->firstOrFail();

        if ($validated['type'] === 'income') {
            $akun->increment('balance', $validated['amount']);
        } else {
            $akun->decrement('balance', $validated['amount']);
        }

        Transaction::create($validated);

        return response()->json([
            'message' => 'Transaksi berhasil ditambahkan.',
        ], 201);
    }

    public function show(string $id)
    {
        return redirect()->route('transactions.index');
    }

    public function edit(string $id)
    {
        return redirect()->route('transactions.index');
    }

    public function update(Request $request, string $id): JsonResponse
    {
        return response()->json([
            'message' => 'Edit langsung tidak diizinkan. Hapus transaksi lama lalu buat baru.',
        ], 403);
    }

    public function destroy(string $id)
    {
        $transaction = Transaction::where('user_id', Auth::id())
                                  ->findOrFail($id);

        $akun = Akun::where('id', $transaction->account_id)
                    ->where('user_id', Auth::id())
                    ->firstOrFail();

        if ($transaction->type === 'income') {
            $akun->decrement('balance', $transaction->amount);
        } else {
            $akun->increment('balance', $transaction->amount);
        }

        $transaction->delete();

        return redirect()->route('transactions.index')
                         ->with('success', 'Transaksi berhasil dihapus.');
    }
}