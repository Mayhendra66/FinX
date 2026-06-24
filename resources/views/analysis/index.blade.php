@extends('layouts.app')
@section('content')

<main class="flex-1 md:ml-[240px] p-margin-mobile md:p-margin-desktop bg-grid-pattern min-h-screen flex flex-col gap-6">

    {{-- ================================================================= --}}
    {{-- HEADER                                                             --}}
    {{-- ================================================================= --}}
    <header class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 border-b border-slate-200 dark:border-slate-800/65 pb-6">
        <div>
            <h1 class="text-3xl font-black font-display tracking-tight text-slate-900 dark:text-white flex items-center gap-3">
                <span class="w-11 h-11 flex items-center justify-center rounded-2xl bg-blue-600 text-white shadow-lg shadow-blue-500/20">
                    <i class="fa-solid fa-chart-pie text-xl"></i>
                </span>
                Analisis Portofolio
            </h1>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-2">
                Pantau arus kas, alokasi tabungan, dan hari-hari transaksi terbesar kamu.
            </p>
        </div>

        <form id="form_filter_date" method="GET" action="{{ url()->current() }}" class="flex items-center gap-3 w-full md:w-auto">
            <div class="relative w-full md:w-56">
                <i class="fa-solid fa-calendar-days absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 dark:text-slate-500 text-xs pointer-events-none"></i>
                <input
                    id="filter_date"
                    name="date_range"
                    type="text"
                    value="{{ request('date_range') }}"
                    placeholder="Pilih rentang tanggal"
                    class="w-full bg-white dark:bg-[#121414] border border-slate-250 dark:border-slate-800 rounded-xl pl-9 pr-3.5 py-2.5 text-xs font-bold text-slate-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 cursor-pointer shadow-sm placeholder:text-slate-400 placeholder:font-medium"
                >
            </div>
        </form>
    </header>

    {{-- ================================================================= --}}
    {{-- KPI CARDS — 3 card saja                                           --}}
    {{-- ================================================================= --}}
    <section class="grid grid-cols-1 md:grid-cols-3 gap-5">

        {{-- Total Pengeluaran --}}
        <div class="bg-white dark:bg-[#121415] border border-slate-200/80 dark:border-slate-800/50 p-6 rounded-2xl shadow-sm relative overflow-hidden group">
            <div class="absolute -top-10 -right-10 w-28 h-28 bg-red-500/5 dark:bg-red-500/10 rounded-full blur-2xl group-hover:bg-red-500/10 transition-colors"></div>
            <div class="flex justify-between items-start">
                <div class="space-y-1">
                    <span class="text-xs uppercase tracking-wider text-slate-400 dark:text-slate-500 font-bold block">Total Pengeluaran</span>
                    <p class="text-2xl font-black font-display text-slate-950 dark:text-white">
                        Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}
                    </p>
                </div>
                <span class="p-3 bg-red-50 dark:bg-red-500/10 border border-red-100 dark:border-red-500/20 text-red-500 dark:text-red-400 rounded-xl">
                    <i class="fa-solid fa-arrow-trend-up"></i>
                </span>
            </div>
            <div class="mt-4 pt-4 border-t border-slate-100 dark:border-slate-800/40 text-xs text-slate-500 dark:text-slate-400">
                Akumulasi seluruh transaksi pengeluaran
            </div>
        </div>

        {{-- Total Pemasukan --}}
        <div class="bg-white dark:bg-[#121415] border border-slate-200/80 dark:border-slate-800/50 p-6 rounded-2xl shadow-sm relative overflow-hidden group">
            <div class="absolute -top-10 -right-10 w-28 h-28 bg-emerald-500/5 dark:bg-emerald-500/10 rounded-full blur-2xl group-hover:bg-emerald-500/10 transition-colors"></div>
            <div class="flex justify-between items-start">
                <div class="space-y-1">
                    <span class="text-xs uppercase tracking-wider text-slate-400 dark:text-slate-500 font-bold block">Total Pemasukan</span>
                    <p class="text-2xl font-black font-display text-slate-950 dark:text-white">
                        Rp {{ number_format($totalPemasukan, 0, ',', '.') }}
                    </p>
                </div>
                <span class="p-3 bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-100 dark:border-emerald-500/20 text-emerald-600 dark:text-emerald-400 rounded-xl">
                    <i class="fa-solid fa-arrow-trend-down"></i>
                </span>
            </div>
            <div class="mt-4 pt-4 border-t border-slate-100 dark:border-slate-800/40 text-xs text-slate-500 dark:text-slate-400">
                Akumulasi seluruh transaksi pemasukan
            </div>
        </div>

        {{-- Alokasi Tabungan --}}
        <div class="bg-white dark:bg-[#121415] border border-slate-200/80 dark:border-slate-800/50 p-6 rounded-2xl shadow-sm relative overflow-hidden group">
            <div class="absolute -top-10 -right-10 w-28 h-28 bg-blue-500/5 dark:bg-blue-500/10 rounded-full blur-2xl group-hover:bg-blue-500/10 transition-colors"></div>
            <div class="flex justify-between items-start">
                <div class="space-y-1">
                    <span class="text-xs uppercase tracking-wider text-slate-400 dark:text-slate-500 font-bold block">Alokasi Tabungan</span>
                    <p class="text-2xl font-black font-display text-slate-950 dark:text-white">
                        Rp {{ number_format($totalTabungan, 0, ',', '.') }}
                    </p>
                </div>
                <span class="p-3 bg-blue-50 dark:bg-blue-500/10 border border-blue-100 dark:border-blue-500/20 text-blue-600 dark:text-blue-400 rounded-xl">
                    <i class="fa-solid fa-piggy-bank"></i>
                </span>
            </div>
            <div class="mt-4 pt-4 border-t border-slate-100 dark:border-slate-800/40 text-xs text-slate-500 dark:text-slate-400">
                Total dana tersimpan di Saving Goals
            </div>
        </div>

    </section>

    {{-- ================================================================= --}}
    {{-- TOP 3 — Pemasukan & Pengeluaran Terbesar                          --}}
    {{-- ================================================================= --}}
    <section class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- Top 3 Pemasukan --}}
        <div class="bg-white dark:bg-[#121415] border border-slate-200/80 dark:border-slate-800/50 rounded-2xl shadow-sm p-6 flex flex-col gap-5">
            <div class="flex justify-between items-center">
                <h3 class="font-bold text-base font-display text-slate-900 dark:text-white flex items-center gap-2">
                    <i class="fa-solid fa-arrow-trend-down text-emerald-500"></i>
                    Top 3 Hari Pemasukan Terbesar
                </h3>
                <span class="text-[10px] uppercase font-black tracking-wider text-slate-400 dark:text-slate-500 bg-slate-100 dark:bg-slate-800/80 px-2.5 py-1 rounded-md">Income</span>
            </div>

            <div class="flex flex-col gap-3">
                @forelse($topPemasukan as $i => $row)
                @php
                    $medals = ['🥇','🥈','🥉'];
                    $medal  = $medals[$i] ?? '#'.($i+1);
                    $date   = \Carbon\Carbon::parse($row->transaction_date);
                    $bars   = ['bg-emerald-500','bg-emerald-400','bg-emerald-300'];
                    $bar    = $bars[$i] ?? 'bg-emerald-300';
                    $maxTop = $topPemasukan->first()->total ?? 1;
                    $pct    = round(($row->total / $maxTop) * 100);
                @endphp
                <div class="flex flex-col gap-1.5 pb-4 border-b border-slate-100 dark:border-slate-800/40 last:border-0 last:pb-0">
                    <div class="flex justify-between items-center">
                        <div class="flex items-center gap-2.5">
                            <span class="text-lg leading-none">{{ $medal }}</span>
                            <div>
                                <p class="text-sm font-bold text-slate-800 dark:text-slate-200">
                                    {{ $date->translatedFormat('l, d M Y') }}
                                </p>
                                <p class="text-[10px] text-slate-400">{{ $row->jumlah }} transaksi</p>
                            </div>
                        </div>
                        <span class="text-sm font-black text-emerald-500 dark:text-emerald-400 font-mono">
                            Rp {{ number_format($row->total, 0, ',', '.') }}
                        </span>
                    </div>
                    <div class="w-full bg-slate-100 dark:bg-slate-800/80 rounded-full h-1.5">
                        <div class="{{ $bar }} h-1.5 rounded-full transition-all duration-500" style="width: {{ $pct }}%"></div>
                    </div>
                </div>
                @empty
                <div class="flex flex-col items-center justify-center gap-2 py-8 text-slate-400 dark:text-slate-500 text-center">
                    <i class="fa-solid fa-inbox text-2xl"></i>
                    <p class="text-xs">Belum ada data pemasukan.</p>
                </div>
                @endforelse
            </div>
        </div>

        {{-- Top 3 Pengeluaran --}}
        <div class="bg-white dark:bg-[#121415] border border-slate-200/80 dark:border-slate-800/50 rounded-2xl shadow-sm p-6 flex flex-col gap-5">
            <div class="flex justify-between items-center">
                <h3 class="font-bold text-base font-display text-slate-900 dark:text-white flex items-center gap-2">
                    <i class="fa-solid fa-arrow-trend-up text-red-500"></i>
                    Top 3 Hari Pengeluaran Terbesar
                </h3>
                <span class="text-[10px] uppercase font-black tracking-wider text-slate-400 dark:text-slate-500 bg-slate-100 dark:bg-slate-800/80 px-2.5 py-1 rounded-md">Expense</span>
            </div>

            <div class="flex flex-col gap-3">
                @forelse($topPengeluaran as $i => $row)
                @php
                    $medals = ['🥇','🥈','🥉'];
                    $medal  = $medals[$i] ?? '#'.($i+1);
                    $date   = \Carbon\Carbon::parse($row->transaction_date);
                    $bars   = ['bg-red-500','bg-red-400','bg-red-300'];
                    $bar    = $bars[$i] ?? 'bg-red-300';
                    $maxTop = $topPengeluaran->first()->total ?? 1;
                    $pct    = round(($row->total / $maxTop) * 100);
                @endphp
                <div class="flex flex-col gap-1.5 pb-4 border-b border-slate-100 dark:border-slate-800/40 last:border-0 last:pb-0">
                    <div class="flex justify-between items-center">
                        <div class="flex items-center gap-2.5">
                            <span class="text-lg leading-none">{{ $medal }}</span>
                            <div>
                                <p class="text-sm font-bold text-slate-800 dark:text-slate-200">
                                    {{ $date->translatedFormat('l, d M Y') }}
                                </p>
                                <p class="text-[10px] text-slate-400">{{ $row->jumlah }} transaksi</p>
                            </div>
                        </div>
                        <span class="text-sm font-black text-red-500 dark:text-red-400 font-mono">
                            Rp {{ number_format($row->total, 0, ',', '.') }}
                        </span>
                    </div>
                    <div class="w-full bg-slate-100 dark:bg-slate-800/80 rounded-full h-1.5">
                        <div class="{{ $bar }} h-1.5 rounded-full transition-all duration-500" style="width: {{ $pct }}%"></div>
                    </div>
                </div>
                @empty
                <div class="flex flex-col items-center justify-center gap-2 py-8 text-slate-400 dark:text-slate-500 text-center">
                    <i class="fa-solid fa-inbox text-2xl"></i>
                    <p class="text-xs">Belum ada data pengeluaran.</p>
                </div>
                @endforelse
            </div>
        </div>

    </section>

</main>

<script>
flatpickr("#filter_date", {
    mode: "range",
    dateFormat: "Y-m-d",
    altInput: true,
    altFormat: "d M Y",
    locale: { rangeSeparator: " s/d " },
    defaultDate: "{{ request('date_range') }}",
    onChange: function(selectedDates) {
        if (selectedDates.length === 2) {
            document.getElementById('form_filter_date').submit();
        }
    }
});
</script>

@endsection