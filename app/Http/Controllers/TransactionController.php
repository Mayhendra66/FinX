<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Akun;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Models\MainAccountModel;

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

    $accounts    = Akun::where('user_id', Auth::id())->orderBy('name')->get();
    $categories  = Category::where('user_id', Auth::id())->orderBy('name')->get();
    $mainAccount = MainAccountModel::where('user_id', Auth::id())->first(); // ✅ tambah ini

    return view('transactions.main.index', compact(
        'transactions',
        'accounts',
        'categories',
        'mainAccount' // ✅ tambah ini
    ));
    }

    

public function topup(Request $request): JsonResponse
{
    $request->validate([
        'payment_method' => 'required|in:bank,retail',
        'amount'         => 'required|string',
        'description'    => 'required|string', // ✅ Validasi input deskripsi
    ]);

    $rawAmount = (int) preg_replace('/\D/', '', $request->amount);

    if ($rawAmount < 10000) {
        return response()->json(['message' => 'Minimal top up adalah Rp 10.000'], 422);
    }

    $accountId = null;

    // ✅ Tambah balance di main-account milik user
    $mainAccount = MainAccountModel::where('user_id', Auth::id())->firstOrFail();
    $mainAccount->increment('balance', $rawAmount);

    // ✅ Catat sebagai transaksi income beserta deskripsinya
    Transaction::create([
        'user_id'          => Auth::id(),
        'account_id'       => null,
        'category_id'      => null,
        'type'             => 'income',
        'amount'           => $rawAmount,
        'transaction_date' => now()->toDateString(),
        'note'             => $request->description, // ✅ Menyimpan deskripsi (TOP UP GERAI / TOP UP VA)
        'receipt_image'    => null,
        'is_installment'   => 0,
        'installment_id'   => null,
        'savings_goal_id'  => null,
    ]);

    return response()->json([
        'message' => 'Top up berhasil, saldo bertambah!',
    ], 201);
}

public function scan(Request $request)
{
    $request->validate([
        'amount'      => 'required|string',
        'description' => 'required|string',
        'catatan'     => 'nullable|string|max:255',
    ]);

    $rawAmount = (int) preg_replace('/\D/', '', $request->amount);

    if ($rawAmount < 1000) {
        return back()
            ->withInput()
            ->withErrors([
                'amount' => 'Minimal pembayaran adalah Rp 1.000'
            ]);
    }

    $mainAccount = MainAccountModel::where(
        'user_id',
        Auth::id()
    )->firstOrFail();

    if ($mainAccount->balance < $rawAmount) {
        return back()
            ->withInput()
            ->withErrors([
                'amount' => 'Saldo utama tidak mencukupi.'
            ]);
    }

    // Potong saldo
    $mainAccount->decrement('balance', $rawAmount);

    // Susun note
    $note = $request->description;

    if ($request->filled('catatan')) {
        $note .= ' - ' . $request->catatan;
    }

    // Simpan transaksi
    Transaction::create([
        'user_id'          => Auth::id(),
        'account_id'       => null,
        'category_id'      => null,
        'type'             => 'expense',
        'amount'           => $rawAmount,
        'transaction_date' => now()->toDateString(),
        'note'             => $note,
        'receipt_image'    => null,
        'is_installment'   => 0,
        'installment_id'   => null,
        'savings_goal_id'  => null,
    ]);

    return redirect()
        ->route('dashboard')
        ->with('success', 'Pembayaran berhasil');
}

public function transfer()
{
    $akun = Akun::where('user_id', Auth::id())
        ->orderBy('name')
        ->get();

    return view('transfer.index', compact('akun'));
}

public function transferCreate($account_id)
{
    $account = Akun::where('user_id', Auth::id())
        ->findOrFail($account_id);

    return view('transfer.create', compact('account'));
}

public function transferStore(Request $request)
{
    $request->validate([
        'account_id' => 'required|exists:akun,id',
        'amount'     => 'required|string',
        'catatan'    => 'nullable|string|max:255',
    ]);

    $rawAmount = (int) preg_replace('/\D/', '', $request->amount);

    if ($rawAmount < 1000) {
        return back()
            ->withInput()
            ->withErrors([
                'amount' => 'Minimal transfer Rp 1.000'
            ]);
    }

    $mainAccount = MainAccountModel::where(
        'user_id',
        Auth::id()
    )->firstOrFail();

    if ($mainAccount->balance < $rawAmount) {
        return back()
            ->withInput()
            ->withErrors([
                'amount' => 'Saldo utama tidak mencukupi.'
            ]);
    }

    $account = Akun::where('user_id', Auth::id())
        ->findOrFail($request->account_id);

    $mainAccount->decrement('balance', $rawAmount);

    Transaction::create([
        'user_id'          => Auth::id(),
        'account_id'       => $account->id,
        'category_id'      => null,
        'type'             => 'expense',
        'amount'           => $rawAmount,
        'transaction_date' => now()->toDateString(),
        'note'             => $request->catatan,
        'receipt_image'    => null,
        'is_installment'   => 0,
        'installment_id'   => null,
        'savings_goal_id'  => null,
    ]);

    return redirect()
        ->route('dashboard')
        ->with('success', 'Transfer berhasil');
}
    
}