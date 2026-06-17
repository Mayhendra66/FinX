@extends('layouts.app')

@section('content')
    <main class="flex-1 md:ml-[240px] p-margin-mobile md:p-6 bg-grid-pattern min-h-screen flex flex-col gap-6 w-full max-w-[1600px] mx-auto">
        <header class="flex flex-col md:flex-row md:items-end justify-between gap-md mb-4">
            <div>
                <h2 class="font-display-lg text-display-lg text-on-surface mb-1">Selamat Datang Kembali</h2>
                <p class="font-body-md text-body-md text-on-surface-variant">
                    Ringkasan keuangan Anda hari ini, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}.
                </p>
            </div>
        </header>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-4">
            <div class="bg-[#0A0B0D] border border-[#1E2025] p-6 rounded-xl relative overflow-hidden group hover:border-primary/50 transition-colors">
                <div class="absolute top-0 right-0 w-32 h-32 bg-primary/10 rounded-full blur-3xl -mr-16 -mt-16 pointer-events-none"></div>
                <div class="flex justify-between items-start mb-lg">
                    <span class="font-label-md text-label-md text-on-surface-variant uppercase tracking-wider">Total Saldo</span>
                    <span class="material-symbols-outlined text-primary bg-primary/10 p-1.5 rounded-lg">account_balance</span>
                </div>
                <h3 class="font-display-lg text-display-lg text-on-surface mb-2 tracking-tight">
                    Rp {{ number_format($totalSaldo, 0, ',', '.') }}
                </h3>
                <div class="flex items-center gap-xs font-label-sm text-label-sm">
                    @if($persenSaldo > 0)
                        <span class="text-emerald-400 bg-emerald-400/10 px-1.5 py-0.5 rounded flex items-center gap-1">
                            <span class="material-symbols-outlined text-[12px]">trending_up</span> +{{ $persenSaldo }}%
                        </span>
                    @elseif($persenSaldo < 0)
                        <span class="text-error bg-error/10 px-1.5 py-0.5 rounded flex items-center gap-1">
                            <span class="material-symbols-outlined text-[12px]">trending_down</span> {{ $persenSaldo }}%
                        </span>
                    @else
                        <span class="text-on-surface-variant bg-[#1E2025] px-1.5 py-0.5 rounded flex items-center gap-1">
                            <span class="material-symbols-outlined text-[12px]">trending_flat</span> 0%
                        </span>
                    @endif
                    <span class="text-on-surface-variant">vs bulan lalu</span>
                </div>
            </div>

            <div class="bg-[#0A0B0D] border border-[#1E2025] p-6 rounded-xl relative overflow-hidden group hover:border-primary/50 transition-colors">
                <div class="flex justify-between items-start mb-lg">
                    <span class="font-label-md text-label-md text-on-surface-variant uppercase tracking-wider">Pengeluaran</span>
                    <span class="material-symbols-outlined text-error bg-error/10 p-1.5 rounded-lg">shopping_cart</span>
                </div>
                <h3 class="font-headline-md text-headline-md text-on-surface mb-2 tracking-tight">
                    Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}
                </h3>
                <div class="flex items-center gap-xs font-label-sm text-label-sm">
                    @if($persenPengeluaran > 0)
                        <span class="text-error bg-error/10 px-1.5 py-0.5 rounded flex items-center gap-1">
                            <span class="material-symbols-outlined text-[12px]">trending_up</span> +{{ $persenPengeluaran }}%
                        </span>
                    @elseif($persenPengeluaran < 0)
                        <span class="text-emerald-400 bg-emerald-400/10 px-1.5 py-0.5 rounded flex items-center gap-1">
                            <span class="material-symbols-outlined text-[12px]">trending_down</span> {{ $persenPengeluaran }}%
                        </span>
                    @else
                        <span class="text-on-surface-variant bg-[#1E2025] px-1.5 py-0.5 rounded flex items-center gap-1">
                            <span class="material-symbols-outlined text-[12px]">trending_flat</span> 0%
                        </span>
                    @endif
                    <span class="text-on-surface-variant">vs bulan lalu</span>
                </div>
            </div>

            <div class="bg-[#0A0B0D] border border-[#1E2025] p-6 rounded-xl relative overflow-hidden group hover:border-primary/50 transition-colors md:col-span-2 lg:col-span-1">
                <div class="flex justify-between items-start mb-lg">
                    <span class="font-label-md text-label-md text-on-surface-variant uppercase tracking-wider">Tabungan</span>
                    <span class="material-symbols-outlined text-primary bg-primary/10 p-1.5 rounded-lg">savings</span>
                </div>
                <h3 class="font-headline-md text-headline-md text-on-surface mb-2 tracking-tight">
                    Rp {{ number_format($totalTabungan, 0, ',', '.') }}
                </h3>
                <div class="w-full bg-surface-container-high rounded-full h-1.5 mt-4 mb-2">
                    <div class="bg-primary h-1.5 rounded-full" style="width: {{ min($persenTabungan, 100) }}%;"></div>
                </div>
                <p class="font-label-sm text-label-sm text-on-surface-variant text-right">
                    {{ $persenTabungan }}% dari target
                </p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
            <div class="lg:col-span-2 flex flex-col bg-[#0A0B0D] border border-[#1E2025] p-6 rounded-xl">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
                    <div>
                        <h2 class="text-[22px] font-bold text-white tracking-tight">Ringkasan Arus Kas</h2>
                    </div>
                    <select id="filterArusKas" class="w-full sm:w-auto bg-[#121414] border border-outline-variant/30 text-white text-[14px] rounded-lg px-4 py-2 focus:outline-none appearance-none pr-10 relative bg-[url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%2224%22%20height%3D%2224%22%20viewBox%3D%220%200%2024%2024%22%20fill%3D%22none%22%20stroke%3D%22%23ffffff%22%20stroke-width%3D%222%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%3E%3Cpolyline%20points%3D%226%209%2012%2015%2018%209%22%3E%3C%2Fpolyline%3E%3C%2Fsvg%3E')] bg-no-repeat bg-[right_10px_center] bg-[length:16px_16px]">
                        <option value="bulan_ini" {{ $filter == 'bulan_ini' ? 'selected' : '' }}>Bulan Ini</option>
                        <option value="3_bulan" {{ $filter == '3_bulan' ? 'selected' : '' }}>3 Bulan Terakhir</option>
                        <option value="tahun_ini" {{ $filter == 'tahun_ini' ? 'selected' : '' }}>Tahun Ini</option>
                    </select>
                </div>

                <div class="flex flex-col gap-6">
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                        <div class="bg-[#121414] p-5 rounded-[16px] border border-chart-green/20 relative overflow-hidden">
                            <p class="text-chart-green text-[14px] font-medium mb-1">Total Pemasukan</p>
                            <span class="text-[20px] font-bold text-white block mb-1">
                                Rp {{ number_format($totalPemasukan, 0, ',', '.') }}
                            </span>
                            <p class="text-[12px] text-on-surface-variant mt-1">Disesuaikan Oleh Filter</p>
                        </div>

                        <div class="bg-[#121414] p-5 rounded-[16px] border border-chart-red/20 relative overflow-hidden">
                            <p class="text-chart-red text-[14px] font-medium mb-1">Total Pengeluaran</p>
                            <span class="text-[20px] font-bold text-white block mb-1">
                                Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}
                            </span>
                            <p class="text-[12px] text-on-surface-variant mt-1">Disesuaikan Oleh Filter</p>
                        </div>

                        <div class="bg-[#121414] p-5 rounded-[16px] border border-chart-blue/20 relative overflow-hidden sm:col-span-2 md:col-span-1">
                            <p class="text-chart-blue text-[14px] font-medium mb-1">Sisa Anggaran</p>
                            <span class="text-[20px] font-bold text-white block mb-1">
                                Rp {{ number_format($sisaAnggaran, 0, ',', '.') }}
                            </span>
                            <span class="text-on-surface-variant text-[12px]">
                                {{ $totalPemasukan > 0 ? number_format(($sisaAnggaran / $totalPemasukan) * 100, 1, ',', '.') : 0 }}% dari total anggaran
                            </span>
                        </div>
                    </div>

                    <div class="mt-4 overflow-x-auto pb-2">
                        <div class="min-w-[600px]">
                            <p class="text-white text-[13px] mb-4 font-medium">Grafik Mingguan</p>
                            <div class="relative h-[260px] flex items-end justify-between gap-6 pl-4 border-b border-outline-variant/20">
                                @foreach($chartMingguan as $label => $data)
                                    <div class="flex-1 flex flex-col items-center gap-2 h-full justify-end z-10">
                                        <div class="flex items-end gap-1 w-full h-full justify-center">
                                            <div class="w-[35%] max-w-[30px] bg-chart-green rounded-t-sm transition-all duration-500" 
                                                 style="height: {{ $data['persen_income'] }}%;" 
                                                 title="Pemasukan: Rp {{ number_format($data['income'], 0, ',', '.') }}">
                                            </div>
                                            <div class="w-[35%] max-w-[30px] bg-chart-red rounded-t-sm transition-all duration-500" 
                                                 style="height: {{ $data['persen_expense'] }}%;" 
                                                 title="Pengeluaran: Rp {{ number_format($data['expense'], 0, ',', '.') }}">
                                            </div>
                                        </div>
                                        <span class="text-[12px] text-on-surface-variant mt-2">{{ $label }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-[#0A0B0D] border border-[#1E2025] rounded-xl flex flex-col w-full">
                <div class="p-4 border-b border-[#1E2025] flex justify-between items-center">
                    <h3 class="font-headline-sm text-white font-semibold text-[16px]">Transaksi Hari Ini</h3>
                    <a class="font-label-sm text-label-sm text-primary hover:underline" href="{{ route('transactions.index') }}">Lihat Semua</a>
                </div>
                <div class="flex-1 overflow-y-auto max-h-[480px]">
                    @forelse($recentTransactions as $transaction)
                        <div class="p-4 border-b border-[#1E2025]/60 flex justify-between items-center last:border-0">
                            <div class="flex items-center gap-3">
                                <span class="material-symbols-outlined p-2 rounded-lg text-[18px] 
                                    {{ $transaction->type == 'income' ? 'text-chart-green bg-chart-green/10' : 'text-chart-red bg-chart-red/10' }}">
                                    {{ $transaction->type == 'income' ? 'arrow_downward' : 'shopping_cart' }}
                                </span>
                                <div>
                                    <p class="font-label-md text-white font-medium text-[14px]">
                                        {{ $transaction->category->name ?? 'Tanpa Anggaran' }}
                                    </p>
                                    <p class="text-[11px] text-on-surface-variant">
                                        {{ $transaction->note ?? 'Tidak ada catatan' }}
                                    </p>
                                </div>
                            </div>
                            <span class="font-label-md font-semibold text-[14px] {{ $transaction->type == 'income' ? 'text-chart-green' : 'text-chart-red' }}">
                                {{ $transaction->type == 'income' ? '+' : '-' }} Rp {{ number_format($transaction->amount, 0, ',', '.') }}
                            </span>
                        </div>
                    @empty
                        <div class="p-8 flex flex-col items-center justify-center text-center opacity-50 min-h-[250px]">
                            <span class="material-symbols-outlined text-[40px] mb-2 text-on-surface-variant">receipt_long</span>
                            <p class="font-label-md text-[14px] text-white">Belum ada transaksi hari ini</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </main>

    <script>
        document.getElementById('filterArusKas').addEventListener('change', function() {
            const filterValue = this.value;
            const currentUrl = new URL(window.location.href);
            currentUrl.searchParams.set('filter', filterValue);
            window.location.href = currentUrl.toString();
        });
    </script>
@endsection