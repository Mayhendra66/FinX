<?php

namespace App\Http\Controllers;

use App\Models\Akun;
use App\Models\Transaction;
use App\Models\SavingGoal;
use App\Models\Anggaran;
use App\Models\MainAccountModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $userId       = Auth::id();
        $currentMonth = now()->month;
        $currentYear  = now()->year;
        $filter       = $request->get('filter', 'bulan_ini');

        /* --------------------------------------------------
         | 1. TOTAL SALDO (Akun Pemilik)
         | -------------------------------------------------- */
        $totalSaldo = MainAccountModel::where('user_id', $userId)->sum('balance');

        /* --------------------------------------------------
         | 2. PEMASUKAN & PENGELUARAN BULAN INI
         | -------------------------------------------------- */
        $pemasukanBulanIni = Transaction::where('user_id', $userId)
            ->where('type', 'income')
            ->whereMonth('transaction_date', $currentMonth)
            ->whereYear('transaction_date', $currentYear)
            ->sum('amount');

        $pengeluaranBulanIni = Transaction::where('user_id', $userId)
            ->where('type', 'expense')
            ->whereMonth('transaction_date', $currentMonth)
            ->whereYear('transaction_date', $currentYear)
            ->sum('amount');

        /* --------------------------------------------------
         | 3. BULAN LALU — untuk perbandingan persen
         | -------------------------------------------------- */
        $lastMonthObj   = now()->subMonth();
        $lastMonth      = $lastMonthObj->month;
        $lastMonthYear  = $lastMonthObj->year;

        $pengeluaranBulanLalu = Transaction::where('user_id', $userId)
            ->where('type', 'expense')
            ->whereMonth('transaction_date', $lastMonth)
            ->whereYear('transaction_date', $lastMonthYear)
            ->sum('amount');

        /* --------------------------------------------------
         | 4. KALKULASI PERSEN SALDO vs BULAN LALU
         | -------------------------------------------------- */
        $diffPengeluaran  = $pengeluaranBulanIni - $pengeluaranBulanLalu;
        $persenPengeluaran = $pengeluaranBulanLalu > 0
            ? round(($diffPengeluaran / $pengeluaranBulanLalu) * 100)
            : ($pengeluaranBulanIni > 0 ? 100 : 0);

        $netBulanIni   = $pemasukanBulanIni - $pengeluaranBulanIni;
        $saldoBulanLalu = $totalSaldo - $netBulanIni;
        $diffSaldo      = $totalSaldo - $saldoBulanLalu;
        $persenSaldo    = $saldoBulanLalu > 0
            ? round(($diffSaldo / $saldoBulanLalu) * 100)
            : ($totalSaldo > 0 ? 100 : 0);

        /* --------------------------------------------------
         | 5. TABUNGAN / SAVING GOALS
         | -------------------------------------------------- */
        $totalTabungan  = SavingGoal::where('user_id', $userId)->sum('current_amount');
        $targetTabungan = SavingGoal::where('user_id', $userId)->sum('target_amount');
        $persenTabungan = $targetTabungan > 0
            ? round(($totalTabungan / $targetTabungan) * 100)
            : 0;

        /* --------------------------------------------------
         | 6. RINGKASAN ARUS KAS — sesuai filter
         | -------------------------------------------------- */
        $cashFlowQuery = Transaction::where('user_id', $userId);
        $anggaranQuery = Anggaran::where('user_id', $userId);

        if ($filter === '3_bulan') {
            $cashFlowQuery->where('transaction_date', '>=', now()->subMonths(2)->startOfMonth());
            $months = [];
            for ($i = 0; $i < 3; $i++) {
                $d = now()->subMonths($i);
                $months[] = ['month' => $d->month, 'year' => $d->year];
            }
            $anggaranQuery->where(function ($q) use ($months) {
                foreach ($months as $m) {
                    $q->orWhere(fn($s) => $s->where('month', $m['month'])->where('year', $m['year']));
                }
            });
        } elseif ($filter === 'tahun_ini') {
            $cashFlowQuery->whereYear('transaction_date', $currentYear);
            $anggaranQuery->where('year', $currentYear);
        } else {
            $cashFlowQuery
                ->whereMonth('transaction_date', $currentMonth)
                ->whereYear('transaction_date', $currentYear);
            $anggaranQuery
                ->where('month', $currentMonth)
                ->where('year', $currentYear);
        }

        $filteredTransactions = $cashFlowQuery->get();

        $totalPemasukan   = $filteredTransactions->where('type', 'income')->sum('amount');
        $totalPengeluaran = $filteredTransactions->where('type', 'expense')->sum('amount');

        $totalAnggaran      = $anggaranQuery->sum('amount');
        $sisaAnggaran       = $totalAnggaran - $totalPengeluaran;
        $persenAnggaranSisa = $totalAnggaran > 0
            ? round(($sisaAnggaran / $totalAnggaran) * 100)
            : 0;

        /* --------------------------------------------------
         | 7. TRANSAKSI HARI INI
         | -------------------------------------------------- */
        $recentTransactions = Transaction::with('category')
            ->where('user_id', $userId)
            ->whereDate('transaction_date', Carbon::today())
            ->limit(5)
            ->orderByDesc('id')
            ->get();

        /* --------------------------------------------------
         | 8. DAFTAR REKENING ORANG LAIN (untuk transfer)
         | -------------------------------------------------- */
        // Mengubah nama variabel dari $wallets menjadi $akun agar sinkron dengan Blade
        $akun = Akun::where('user_id', $userId)
            ->orderByDesc('created_at')
            ->get();

        /* --------------------------------------------------
         | 9. DATA GRAFIK MINGGUAN
         | -------------------------------------------------- */
        $monthlyTx = Transaction::where('user_id', $userId)
            ->whereMonth('transaction_date', $currentMonth)
            ->whereYear('transaction_date', $currentYear)
            ->get();

        $dateRanges = [
            '1–7'   => [1,  7],
            '8–14'  => [8,  14],
            '15–21' => [15, 21],
            '22–28' => [22, 28],
            '29–30' => [29, 30],
            '31'    => [31, 31],
        ];

        $chartMingguan = [];
        $maxVal        = 0;

        foreach ($dateRanges as $label => $range) {
            $income = $monthlyTx->where('type', 'income')
                ->filter(fn($t) => Carbon::parse($t->transaction_date)->day >= $range[0]
                                && Carbon::parse($t->transaction_date)->day <= $range[1])
                ->sum('amount');

            $expense = $monthlyTx->where('type', 'expense')
                ->filter(fn($t) => Carbon::parse($t->transaction_date)->day >= $range[0]
                                && Carbon::parse($t->transaction_date)->day <= $range[1])
                ->sum('amount');

            $chartMingguan[$label] = compact('income', 'expense');
            $maxVal = max($maxVal, $income, $expense);
        }

        foreach ($chartMingguan as $label => $data) {
            $chartMingguan[$label]['persen_income']  = $maxVal > 0 ? ($data['income']  / $maxVal) * 100 : 0;
            $chartMingguan[$label]['persen_expense'] = $maxVal > 0 ? ($data['expense'] / $maxVal) * 100 : 0;
        }

        $mainAccount = MainAccountModel::where('user_id', $userId)->first();
$totalSaldo  = MainAccountModel::where('user_id', $userId)->sum('balance');
$accountNo   = $mainAccount ? $mainAccount->account_no : '0000000000';

        /* --------------------------------------------------
         | RETURN VIEW
         | -------------------------------------------------- */
        return view('dashboard', compact(
    'totalSaldo',
    'accountNo', // Tambahkan variabel ini
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
    'filter',
    'akun',
));
    }
}