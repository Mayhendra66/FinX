@extends('layouts.app')



@section('content')
    <main class="flex-1 md:ml-[240px] p-margin-mobile md:p-margin-desktop bg-grid-pattern min-h-screen">
        <!-- Header -->
        <header class="flex flex-col md:flex-row md:items-end justify-between gap-md mb-xl">
            <div>
                <h2 class="font-display-lg text-display-lg text-on-surface mb-1">Selamat Datang Kembali</h2>
                <p class="font-body-md text-body-md text-on-surface-variant">
                    Ringkasan keuangan Anda hari ini, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}.
                </p>
            </div>
            <div class="flex gap-sm">
                <button
                    class="bg-surface-container-high border border-outline-variant text-on-surface px-4 py-2 rounded-lg font-label-md text-label-md hover:bg-surface-container-highest transition-colors flex items-center gap-xs w-full md:w-auto justify-center">
                    <span class="material-symbols-outlined text-[18px]">download</span>
                    Unduh Laporan
                </button>
            </div>
        </header>
        <!-- KPI Cards Bento Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-md mb-xl">
            <!-- Total Saldo -->
            <div
                class="bg-[#0A0B0D] border border-[#1E2025] p-md rounded-xl relative overflow-hidden group hover:border-primary/50 transition-colors">
                <div
                    class="absolute top-0 right-0 w-32 h-32 bg-primary/10 rounded-full blur-3xl -mr-16 -mt-16 pointer-events-none">
                </div>
                <div class="flex justify-between items-start mb-lg">
                    <span class="font-label-md text-label-md text-on-surface-variant uppercase tracking-wider">Total
                        Saldo</span>
                    <span
                        class="material-symbols-outlined text-primary bg-primary/10 p-1.5 rounded-lg">account_balance</span>
                </div>
                <h3 class="font-display-lg text-display-lg text-on-surface mb-2 tracking-tight">Rp
                    {{ number_format($totalSaldo, 0, ',', '.') }}</h3>
                <div class="flex items-center gap-xs font-label-sm text-label-sm">
                    <span class="text-emerald-400 bg-emerald-400/10 px-1.5 py-0.5 rounded flex items-center gap-1"><span
                            class="material-symbols-outlined text-[12px]">trending_flat</span> 0%</span>
                    <span class="text-on-surface-variant">vs bulan lalu</span>
                </div>
            </div>
            <!-- Pengeluaran Bulan Ini -->
            <div
                class="bg-[#0A0B0D] border border-[#1E2025] p-md rounded-xl relative overflow-hidden group hover:border-primary/50 transition-colors">
                <div class="flex justify-between items-start mb-lg">
                    <span
                        class="font-label-md text-label-md text-on-surface-variant uppercase tracking-wider">Pengeluaran</span>
                    <span class="material-symbols-outlined text-error bg-error/10 p-1.5 rounded-lg">shopping_cart</span>
                </div>
                <h3 class="font-headline-md text-headline-md text-on-surface mb-2 tracking-tight">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</h3>
                <div class="flex items-center gap-xs font-label-sm text-label-sm">
                    <span class="text-error bg-error/10 px-1.5 py-0.5 rounded flex items-center gap-1"><span
                            class="material-symbols-outlined text-[12px]">trending_flat</span> 0%</span>
                    <span class="text-on-surface-variant">vs bulan lalu</span>
                </div>
            </div>
            <!-- Tabungan -->
            <div
                class="bg-[#0A0B0D] border border-[#1E2025] p-md rounded-xl relative overflow-hidden group hover:border-primary/50 transition-colors md:col-span-2 lg:col-span-1">
                <div class="flex justify-between items-start mb-lg">
                    <span
                        class="font-label-md text-label-md text-on-surface-variant uppercase tracking-wider">Tabungan</span>
                    <span class="material-symbols-outlined text-primary bg-primary/10 p-1.5 rounded-lg">savings</span>
                </div>
                <h3 class="font-headline-md text-headline-md text-on-surface mb-2 tracking-tight">Rp 0</h3>
                <div class="w-full bg-surface-container-high rounded-full h-1.5 mt-4 mb-2">
                    <div class="bg-primary h-1.5 rounded-full" style="width: 0%;"></div>
                </div>
                <p class="font-label-sm text-label-sm text-on-surface-variant text-right">0% dari target</p>
            </div>
        </div>
        <!-- Main Dashboard Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-md">
            <!-- Chart Section (Spans 2 columns on large screens) -->
            <div class="lg:col-span-2 flex flex-col">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
                    <h2 class="text-[24px] sm:text-[32px] font-bold text-white tracking-tight">Ringkasan Arus Kas</h2>
                    <select
                        class="w-full sm:w-auto bg-[#121414] border border-outline-variant/30 text-white text-[14px] rounded-lg px-4 py-2 focus:outline-none appearance-none pr-10 relative bg-[url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%2224%22%20height%3D%2224%22%20viewBox%3D%220%200%2024%2024%22%20fill%3D%22none%22%20stroke%3D%22%23ffffff%22%20stroke-width%3D%222%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%3E%3Cpolyline%20points%3D%226%209%2012%2015%2018%209%22%3E%3C%2Fpolyline%3E%3C%2Fsvg%3E')] bg-no-repeat bg-[right_10px_center] bg-[length:16px_16px]">
                        <option>Bulan Ini</option>
                        <option>3 Bulan Terakhir</option>
                        <option>Tahun Ini</option>
                    </select>
                </div>
                <div class="flex flex-col gap-6">
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                        <div class="bg-[#0A0B0D] p-6 rounded-[16px] border border-chart-green/20 relative overflow-hidden">
                            <div
                                class="absolute right-6 w-12 h-12 bg-chart-green/10 rounded-full flex items-center justify-center top-6">
                                <span class="material-symbols-outlined text-chart-green">account_balance_wallet</span>
                            </div>
                            <p class="text-chart-green text-[15px] font-medium mb-2">Total Pemasukan</p>
                            <div class="flex flex-col"><span class="text-[28px] font-bold text-white mb-3">0
                                    Jt</span><span class="text-on-surface-variant text-[14px] flex items-center gap-2">Belum
                                    ada data
                                    pembanding</span></div>
                        </div>
                        <div class="bg-[#0A0B0D] p-6 rounded-[16px] border border-chart-red/20 relative overflow-hidden">
                            <div
                                class="absolute right-6 w-12 h-12 bg-chart-red/10 rounded-full flex items-center justify-center top-6">
                                <span class="material-symbols-outlined text-chart-red">account_balance_wallet</span>
                            </div>
                            <p class="text-chart-red text-[15px] font-medium mb-2">Total Pengeluaran</p>
                            <div class="flex flex-col"><span class="text-[28px] font-bold text-white mb-3">0
                                    Jt</span><span class="text-on-surface-variant text-[14px] flex items-center gap-2">Belum
                                    ada data
                                    pembanding</span></div>
                        </div>
                        <div
                            class="bg-[#0A0B0D] p-6 rounded-[16px] border border-chart-blue/20 relative overflow-hidden sm:col-span-2 md:col-span-1">
                            <div
                                class="absolute right-6 w-12 h-12 bg-chart-blue/10 rounded-full flex items-center justify-center top-6">
                                <span class="material-symbols-outlined text-chart-blue">pie_chart</span>
                            </div>
                            <p class="text-chart-blue text-[15px] font-medium mb-2">0% dari target</p>
                            <div class="flex flex-col"><span class="text-[28px] font-bold text-white mb-3">0
                                    Jt</span><span class="text-on-surface-variant text-[14px] flex items-center gap-2">Belum
                                    ada data
                                    pembanding</span></div>
                        </div>
                    </div>
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mt-2 gap-4">
                        <div class="flex gap-6 font-medium text-[15px] text-white">
                            <div class="flex items-center gap-2">
                                <div class="w-4 h-4 rounded-full bg-chart-green"></div>Pemasukan
                            </div>
                            <div class="flex items-center gap-2">
                                <div class="w-4 h-4 rounded-full bg-chart-red"></div>Pengeluaran
                            </div>
                        </div>
                        <div
                            class="w-full sm:w-auto bg-transparent border border-outline-variant/30 px-4 py-2 rounded-lg font-medium text-[14px] text-white flex justify-center items-center gap-2 cursor-pointer hover:bg-white/5 transition-colors">
                            <span class="material-symbols-outlined text-[18px]">bar_chart</span>Visualisasi Data
                            Interaktif
                        </div>
                    </div>
                    <div class="mt-4 overflow-x-auto pb-4">
                        <div class="min-w-[600px]">
                            <p class="text-white text-[13px] mb-4">Rp (Juta)</p>
                            <div
                                class="relative h-[300px] flex items-end justify-between gap-6 pl-10 border-b border-outline-variant/20">
                                <div
                                    class="absolute left-0 top-0 h-full w-full flex flex-col justify-between pointer-events-none">
                                    <div class="flex items-center w-full"><span
                                            class="text-[13px] text-white w-8 text-right mr-2">14 Jt</span>
                                        <div class="border-t border-outline-variant/20 w-full"></div>
                                    </div>
                                    <div class="flex items-center w-full"><span
                                            class="text-[13px] text-white w-8 text-right mr-2">12 Jt</span>
                                        <div class="border-t border-outline-variant/20 w-full"></div>
                                    </div>
                                    <div class="flex items-center w-full"><span
                                            class="text-[13px] text-white w-8 text-right mr-2">10 Jt</span>
                                        <div class="border-t border-outline-variant/20 w-full"></div>
                                    </div>
                                    <div class="flex items-center w-full"><span
                                            class="text-[13px] text-white w-8 text-right mr-2">8 Jt</span>
                                        <div class="border-t border-outline-variant/20 w-full"></div>
                                    </div>
                                    <div class="flex items-center w-full"><span
                                            class="text-[13px] text-white w-8 text-right mr-2">6 Jt</span>
                                        <div class="border-t border-outline-variant/20 w-full"></div>
                                    </div>
                                    <div class="flex items-center w-full"><span
                                            class="text-[13px] text-white w-8 text-right mr-2">4 Jt</span>
                                        <div class="border-t border-outline-variant/20 w-full"></div>
                                    </div>
                                    <div class="flex items-center w-full"><span
                                            class="text-[13px] text-white w-8 text-right mr-2">2 Jt</span>
                                        <div class="border-t border-outline-variant/20 w-full"></div>
                                    </div>
                                    <div class="flex items-center w-full"><span
                                            class="text-[13px] text-white w-8 text-right mr-2">0</span>
                                        <div class="w-full"></div>
                                    </div>
                                </div>
                                <div class="flex-1 flex flex-col items-center gap-2 h-full justify-end z-10">
                                    <div class="flex items-end gap-1 w-full h-full justify-center">
                                        <div class="w-[45%] max-w-[40px] bg-chart-green rounded-t-sm" style="height: 0%;">
                                        </div>
                                        <div class="w-[45%] max-w-[40px] bg-chart-red rounded-t-sm" style="height: 0%;">
                                        </div>
                                    </div><span class="text-[14px] text-white mt-3">1–7</span>
                                </div>
                                <div class="flex-1 flex flex-col items-center gap-2 h-full justify-end z-10">
                                    <div class="flex items-end gap-1 w-full h-full justify-center">
                                        <div class="w-[45%] max-w-[40px] bg-chart-green rounded-t-sm" style="height: 0%;">
                                        </div>
                                        <div class="w-[45%] max-w-[40px] bg-chart-red rounded-t-sm" style="height: 0%;">
                                        </div>
                                    </div><span class="text-[14px] text-white mt-3">8–14</span>
                                </div>
                                <div class="flex-1 flex flex-col items-center gap-2 h-full justify-end z-10">
                                    <div class="flex items-end gap-1 w-full h-full justify-center">
                                        <div class="w-[45%] max-w-[40px] bg-chart-green rounded-t-sm" style="height: 0%;">
                                        </div>
                                        <div class="w-[45%] max-w-[40px] bg-chart-red rounded-t-sm" style="height: 0%;">
                                        </div>
                                    </div><span class="text-[14px] text-white mt-3">15–21</span>
                                </div>
                                <div class="flex-1 flex flex-col items-center gap-2 h-full justify-end z-10">
                                    <div class="flex items-end gap-1 w-full h-full justify-center">
                                        <div class="w-[45%] max-w-[40px] bg-chart-green rounded-t-sm" style="height: 0%;">
                                        </div>
                                        <div class="w-[45%] max-w-[40px] bg-chart-red rounded-t-sm" style="height: 0%;">
                                        </div>
                                    </div><span class="text-[14px] text-white mt-3">22–28</span>
                                </div>
                                <div class="flex-1 flex flex-col items-center gap-2 h-full justify-end z-10">
                                    <div class="flex items-end gap-1 w-full h-full justify-center">
                                        <div class="w-[45%] max-w-[40px] bg-chart-green rounded-t-sm" style="height: 0%;">
                                        </div>
                                        <div class="w-[45%] max-w-[40px] bg-chart-red rounded-t-sm" style="height: 0%;">
                                        </div>
                                    </div><span class="text-[14px] text-white mt-3">29–30</span>
                                </div>
                                <div class="flex-1 flex flex-col items-center gap-2 h-full justify-end z-10">
                                    <div class="flex items-end gap-1 w-full h-full justify-center">
                                        <div class="w-[45%] max-w-[40px] bg-chart-green rounded-t-sm" style="height: 0%;">
                                        </div>
                                        <div class="w-[45%] max-w-[40px] bg-chart-red rounded-t-sm" style="height: 0%;">
                                        </div>
                                    </div><span class="text-[14px] text-white mt-3">31</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Recent Transactions List -->
            <div class="bg-[#0A0B0D] border border-[#1E2025] rounded-xl flex flex-col mt-md lg:mt-0">
                <div class="p-md border-b border-[#1E2025] flex justify-between items-center">
                    <h3 class="font-headline-sm text-headline-sm text-on-surface">Transaksi Terakhir</h3>
                    <a class="font-label-sm text-label-sm text-primary hover:underline" href="#">Lihat Semua</a>
                </div>
                <div class="flex-1 overflow-y-auto">
                    <div
                        class="p-xl flex flex-col items-center justify-center text-center opacity-50 h-full min-h-[200px]">
                        <span class="material-symbols-outlined text-[48px] mb-2">receipt_long</span>
                        <p class="font-label-md">Belum ada transaksi</p>
                        <p class="text-[12px]">Mulai catat pengeluaran Anda hari ini.</p>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
