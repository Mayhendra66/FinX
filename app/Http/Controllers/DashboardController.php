<?php

namespace App\Http\Controllers;

use App\Models\Akun;
use App\Models\Transaction;
use App\Models\SavingGoal;
use App\Models\Anggaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $userId = Auth::id();
        $currentMonth = now()->month;
        $currentYear = now()->year;
        $filter = $request->get('filter', 'bulan_ini');

        // 1. Total Saldo Saat Ini
        $totalSaldo = Akun::where('user_id', $userId)->sum('balance');

        // Data Realtime Bulan Ini & Bulan Lalu untuk KPI Cards (Atas)
        $pengeluaranBulanIni = Transaction::where('user_id', $userId)
            ->where('type', 'expense')
            ->whereMonth('transaction_date', $currentMonth)
            ->whereYear('transaction_date', $currentYear)
            ->sum('amount');

        $pemasukanBulanIni = Transaction::where('user_id', $userId)
            ->where('type', 'income')
            ->whereMonth('transaction_date', $currentMonth)
            ->whereYear('transaction_date', $currentYear)
            ->sum('amount');

        $lastMonthObj = now()->subMonth();
        $lastMonth = $lastMonthObj->month;
        $lastMonthYear = $lastMonthObj->year;

        $pengeluaranBulanLalu = Transaction::where('user_id', $userId)
            ->where('type', 'expense')
            ->whereMonth('transaction_date', $lastMonth)
            ->whereYear('transaction_date', $lastMonthYear)
            ->sum('amount');

        // Kalkulasi Persentase "vs Bulan Lalu" untuk KPI Cards
        $diffPengeluaran = $pengeluaranBulanIni - $pengeluaranBulanLalu;
        $persenPengeluaran = $pengeluaranBulanLalu > 0 ? round(($diffPengeluaran / $pengeluaranBulanLalu) * 100) : ($pengeluaranBulanIni > 0 ? 100 : 0);

        $netBulanIni = $pemasukanBulanIni - $pengeluaranBulanIni;
        $saldoBulanLalu = $totalSaldo - $netBulanIni;
        $diffSaldo = $totalSaldo - $saldoBulanLalu;
        $persenSaldo = $saldoBulanLalu > 0 ? round(($diffSaldo / $saldoBulanLalu) * 100) : ($totalSaldo > 0 ? 100 : 0);

        // 2. Tabungan (Saving Goals) & Persentase Target
        $totalTabungan = SavingGoal::where('user_id', $userId)->sum('current_amount');
        $targetTabungan = SavingGoal::where('user_id', $userId)->sum('target_amount');
        $persenTabungan = $targetTabungan > 0 ? round(($totalTabungan / $targetTabungan) * 100) : 0;

        // 3. Kalkulasi Ringkasan Arus Kas & Anggaran Sesuai Filter
        $cashFlowQuery = Transaction::where('user_id', $userId);
        $anggaranQuery = Anggaran::where('user_id', $userId);

        if ($filter === '3_bulan') {
            $cashFlowQuery->where('transaction_date', '>=', now()->subMonths(2)->startOfMonth());
            
            $months = [];
            for ($i = 0; $i < 3; $i++) {
                $date = now()->subMonths($i);
                $months[] = ['month' => $date->month, 'year' => $date->year];
            }
            $anggaranQuery->where(function($q) use ($months) {
                foreach ($months as $m) {
                    $q->orWhere(function($sub) use ($m) {
                        $sub->where('month', $m['month'])->where('year', $m['year']);
                    });
                }
            });
        } elseif ($filter === 'tahun_ini') {
            $cashFlowQuery->whereYear('transaction_date', $currentYear);
            $anggaranQuery->where('year', $currentYear);
        } else {
            // Default: Bulan Ini
            $cashFlowQuery->whereMonth('transaction_date', $currentMonth)->whereYear('transaction_date', $currentYear);
            $anggaranQuery->where('month', $currentMonth)->where('year', $currentYear);
        }

        $filteredTransactions = $cashFlowQuery->get();
        
        $totalPemasukan = $filteredTransactions->where('type', 'income')->sum('amount');
        $totalPengeluaran = $filteredTransactions->where('type', 'expense')->sum('amount');
        
        $totalAnggaran = $anggaranQuery->sum('amount');
        $sisaAnggaran = $totalAnggaran - $totalPengeluaran;
        $persenAnggaranSisa = $totalAnggaran > 0 ? round(($sisaAnggaran / $totalAnggaran) * 100) : 0;

        // 4. Transaksi Hari Ini Sahaja (Eager Loading Relasi)
        $recentTransactions = Transaction::with('category')
            ->where('user_id', $userId)
            ->whereDate('transaction_date', Carbon::today())
            ->orderBy('id', 'desc')
            ->get();

        // 5. Konstruksi Data Grafik Mingguan (Berdasarkan Bulan Berjalan)
        $monthlyTransactionsForChart = Transaction::where('user_id', $userId)
            ->whereMonth('transaction_date', $currentMonth)
            ->whereYear('transaction_date', $currentYear)
            ->get();

        $dateRanges = [
            '1–7'   => [1, 7],
            '8–14'  => [8, 14],
            '15–21' => [15, 21],
            '22–28' => [22, 28],
            '29–30' => [29, 30],
            '31'    => [31, 31],
        ];

        $chartMingguan = [];
        $maxVal = 0;

        foreach ($dateRanges as $label => $range) {
            $income = $monthlyTransactionsForChart->where('type', 'income')
                ->filter(function ($t) use ($range) {
                    $day = Carbon::parse($t->transaction_date)->day;
                    return $day >= $range[0] && $day <= $range[1];
                })->sum('amount');

            $expense = $monthlyTransactionsForChart->where('type', 'expense')
                ->filter(function ($t) use ($range) {
                    $day = Carbon::parse($t->transaction_date)->day;
                    return $day >= $range[0] && $day <= $range[1];
                })->sum('amount');

            $chartMingguan[$label] = [
                'income'  => $income,
                'expense' => $expense,
            ];

            if ($income > $maxVal) $maxVal = $income;
            if ($expense > $maxVal) $maxVal = $expense;
        }

        foreach ($chartMingguan as $label => $data) {
            $chartMingguan[$label]['persen_income']  = $maxVal > 0 ? ($data['income'] / $maxVal) * 100 : 0;
            $chartMingguan[$label]['persen_expense'] = $maxVal > 0 ? ($data['expense'] / $maxVal) * 100 : 0;
        }

        return view('dashboard', compact(
            'totalSaldo',
            'pengeluaranBulanIni',
            'persenSaldo',
            'persenPengeluaran',
            'totalTabungan',
            'persenTabungan',
            'totalPemasukan',
            'totalPengeluaran',
            'sisaAnggaran',
            'persenAnggaranSisa',
            'recentTransactions',
            'chartMingguan',
            'filter'
        ));
    }
}