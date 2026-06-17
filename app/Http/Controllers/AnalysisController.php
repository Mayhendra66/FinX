<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Akun;
use App\Models\Transaction; 
use App\Models\SavingGoal;  
use App\Models\Anggaran;    
use App\Models\Category;    

class AnalysisController extends Controller
{
    public function index(Request $request)
    {
        $userId = auth()->id();

        // 1. Ambil parameter date_range dari request
        $dateRange = $request->query('date_range');
        $startDate = null;
        $endDate = null;

        if ($dateRange && str_contains($dateRange, ' s/d ')) {
            $dates = explode(' s/d ', $dateRange);
            $startDate = $dates[0] ?? null;
            $endDate = $dates[1] ?? null;
        }

        // 2. Ambil data Akun milik user
        $akun = Akun::where('user_id', $userId)->get();

        // 3. Hitung KPI: Total Pengeluaran dengan Filter Tanggal (Hanya Tabel Transaction)
        $totalPengeluaranQuery = Transaction::where('user_id', $userId)->where('type', 'expense');
        if ($startDate && $endDate) {
            $totalPengeluaranQuery->whereBetween('transaction_date', [$startDate, $endDate]);
        }
        $totalPengeluaran = $totalPengeluaranQuery->sum('amount');

        // 4. Hitung KPI: Alokasi Tabungan (Semua data / Tanpa Filter Tanggal)
        $totalTabungan = SavingGoal::where('user_id', $userId)->sum('current_amount');

        // 5. Hitung KPI: Sisa Anggaran (Semua data Anggaran / Tanpa Filter Tanggal)
        $totalAnggaran = Anggaran::where('user_id', $userId)->sum('amount');
        $sisaAnggaran = $totalAnggaran - $totalPengeluaran;

        $persenAnggaranSisa = $totalAnggaran > 0 
            ? round(($sisaAnggaran / $totalAnggaran) * 100) 
            : 0;

        // 6. Komposisi Pengeluaran per Kategori dengan Filter Tanggal pada Relasi Sum Transactions
        $expenseCategories = Category::where('user_id', $userId)
            ->where('type', 'expense')
            ->withSum(['transactions' => function ($query) use ($userId, $startDate, $endDate) {
                $query->where('user_id', $userId)->where('type', 'expense');
                if ($startDate && $endDate) {
                    $query->whereBetween('transaction_date', [$startDate, $endDate]);
                }
            }], 'amount')
            ->get()
            ->map(function ($category) use ($totalPengeluaran) {
                $category->total_amount = $category->transactions_sum_amount ?? 0;
                $category->percent = $totalPengeluaran > 0 
                    ? round(($category->total_amount / $totalPengeluaran) * 100) 
                    : 0;
                return $category;
            });

        // 7. Tautan Saving Goal Aktif (Semua data / Tanpa Filter Tanggal)
        $savingGoals = SavingGoal::where('user_id', $userId)->get()
            ->map(function ($goal) {
                $goal->percent = $goal->target_amount > 0 
                    ? round(($goal->current_amount / $goal->target_amount) * 100) 
                    : 0;
                return $goal;
            });

        return view('analysis.index', compact(
            'akun',
            'totalPengeluaran',
            'totalTabungan',
            'sisaAnggaran',
            'persenAnggaranSisa',
            'expenseCategories',
            'savingGoals'
        ));
    }
}