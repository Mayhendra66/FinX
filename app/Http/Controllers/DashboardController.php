<?php

namespace App\Http\Controllers;

use App\Models\Akun;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        // ✅ Total semua balance akun
        $totalSaldo = Akun::where('user_id', $userId)->sum('balance');

        // ✅ Total pengeluaran bulan ini
        $totalPengeluaran = Transaction::where('user_id', $userId)
            ->where('type', 'expense')
            ->whereMonth('transaction_date', now()->month)
            ->whereYear('transaction_date', now()->year)
            ->sum('amount');

        return view('dashboard', compact('totalSaldo', 'totalPengeluaran'));
    }
}