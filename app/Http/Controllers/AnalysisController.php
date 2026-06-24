<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Akun;
use App\Models\Transaction;
use App\Models\SavingGoal;

class AnalysisController extends Controller
{
    public function index(Request $request)
    {
        $userId = auth()->id();

        // Parse date range
        $dateRange = $request->query('date_range');
        $startDate = null;
        $endDate   = null;

        if ($dateRange && str_contains($dateRange, ' s/d ')) {
            [$startDate, $endDate] = explode(' s/d ', $dateRange);
        }

        // Base query helper
        $txQuery = fn($type) => Transaction::where('user_id', $userId)
            ->where('type', $type)
            ->when($startDate && $endDate, fn($q) => $q->whereBetween('transaction_date', [$startDate, $endDate]));

        // KPI
        $totalPengeluaran = (clone $txQuery('expense'))->sum('amount');
        $totalPemasukan   = (clone $txQuery('income'))->sum('amount');
        $totalTabungan    = SavingGoal::where('user_id', $userId)->sum('current_amount');

        // Top 3 hari dengan pengeluaran terbesar
        $topPengeluaran = Transaction::where('user_id', $userId)
            ->where('type', 'expense')
            ->when($startDate && $endDate, fn($q) => $q->whereBetween('transaction_date', [$startDate, $endDate]))
            ->selectRaw('transaction_date, SUM(amount) as total, COUNT(*) as jumlah')
            ->groupBy('transaction_date')
            ->orderByDesc('total')
            ->limit(3)
            ->get();

        // Top 3 hari dengan pemasukan terbesar
        $topPemasukan = Transaction::where('user_id', $userId)
            ->where('type', 'income')
            ->when($startDate && $endDate, fn($q) => $q->whereBetween('transaction_date', [$startDate, $endDate]))
            ->selectRaw('transaction_date, SUM(amount) as total, COUNT(*) as jumlah')
            ->groupBy('transaction_date')
            ->orderByDesc('total')
            ->limit(3)
            ->get();

        return view('analysis.index', compact(
            'totalPengeluaran',
            'totalPemasukan',
            'totalTabungan',
            'topPengeluaran',
            'topPemasukan',
        ));
    }
}