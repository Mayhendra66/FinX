@extends('layouts.app')

@section('content')
    <main class="flex-1 md:ml-[240px] p-margin-mobile md:p-margin-desktop bg-grid-pattern min-h-screen">

        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-2xl font-black text-slate-850 dark:text-white tracking-tight flex items-center gap-2">
                    <span
                        class="p-2.5 rounded-xl bg-blue-50 dark:bg-[#0052FF]/10 text-blue-600 dark:text-[#b7c4ff] border border-blue-200 dark:border-[#0052FF]/20">
                        <i class="fa-solid fa-layer-group text-lg"></i>
                    </span>
                    Alokasi & Manajemen Anggaran (Budget)
                </h2>
            </div>
        </div>

        <form action="{{ route('budgeting.index') }}" method="GET"
            class="mt-5 bg-white dark:bg-[#0A0B0D] border border-slate-200 dark:border-[#1E2025] rounded-xl p-4 flex flex-col sm:flex-row items-center justify-between gap-4 shadow-sm">
            <div class="flex items-center gap-2.5">
                <i class="fa-regular fa-calendar-days text-blue-600 dark:text-[#0052ff] text-xl"></i>
                <div>
                    <h4 class="text-xs font-bold text-slate-800 dark:text-white">Pilih Periode Pengukuran</h4>
                    <p class="text-[10px] text-slate-550 dark:text-slate-400 font-medium">Budgets dan progress kalkulasi
                        konsumsi berubah dinamis sesuai bulan terpilih.</p>
                </div>
            </div>

            <div class="flex gap-2 w-full sm:w-auto">
                <select id="filter_month" name="filter_month" onchange="this.form.submit()"
                    class="w-full sm:w-auto bg-slate-50 dark:bg-[#121414] border border-slate-350 dark:border-[#434656]/50 rounded-lg px-3 py-2 text-xs text-slate-800 dark:text-white font-bold cursor-pointer focus:outline-none focus:border-blue-500">
                    @foreach (range(1, 12) as $m)
                        <option value="{{ $m }}" {{ $selectedMonth == $m ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                        </option>
                    @endforeach
                </select>

                <select id="filter_year" name="filter_year" onchange="this.form.submit()"
                    class="w-full sm:w-auto bg-slate-50 dark:bg-[#121414] border border-slate-350 dark:border-[#434656]/50 rounded-lg px-3 py-2 text-xs text-slate-800 dark:text-white font-bold cursor-pointer focus:outline-none focus:border-blue-500">
                    @foreach (range(now()->year, now()->year + 1) as $y)
                        <option value="{{ $y }}" {{ $selectedYear == $y ? 'selected' : '' }}>{{ $y }}
                        </option>
                    @endforeach
                </select>
            </div>
        </form>

        <div class="mt-5 grid grid-cols-1 md:grid-cols-3 gap-5">

            <div
                class="p-5 rounded-xl border bg-white dark:bg-[#0A0B0D] border-slate-200 dark:border-[#1E2025] flex flex-col justify-between shadow-sm relative overflow-hidden">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-[10px] uppercase font-bold text-slate-500 dark:text-slate-400 tracking-wider">Total
                            Anggaran Dialokasi</p>
                        <h3 id="stat_total_budget" class="text-xl font-black text-slate-800 dark:text-white mt-1">Rp
                            {{ number_format($totalBudget ?? 0, 0, ',', '.') }}</h3>
                    </div>
                    <div class="p-3 bg-blue-500/10 text-blue-650 dark:text-[#b7c4ff] rounded-lg">
                        <i class="fa-solid fa-arrow-trend-up"></i>
                    </div>
                </div>
                <div class="text-[10px] text-slate-400 mt-4 pt-3 border-t border-slate-100 dark:border-white/5 font-medium">
                    Mencakup {{ $budgets->count() }} kategori batas belanja di periode aktif Anda.
                </div>
            </div>

            <div
                class="p-5 rounded-xl border bg-white dark:bg-[#0A0B0D] border-slate-200 dark:border-[#1E2025] flex flex-col justify-between shadow-sm relative overflow-hidden">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-[10px] uppercase font-bold text-slate-500 dark:text-slate-400 tracking-wider">Total
                            Realisasi Terpakai</p>
                        <h3 id="stat_total_spent" class="text-xl font-black text-slate-800 dark:text-white mt-1">Rp
                            {{ number_format($totalSpent ?? 0, 0, ',', '.') }}</h3>
                    </div>
                    <div class="p-3 bg-emerald-500/10 text-emerald-500 dark:text-[#2bb673] rounded-lg">
                        <i class="fa-solid fa-wallet"></i>
                    </div>
                </div>
                <div
                    class="text-[10px] text-slate-400 mt-4 pt-3 border-t border-slate-100 dark:border-white/5 font-medium flex items-center justify-between">
                    <span>Persentase konsumsi:</span>
                    <span id="stat_percentage"
                        class="font-bold text-emerald-500">{{ ($totalBudget ?? 0) > 0 ? round((($totalSpent ?? 0) / $totalBudget) * 100) : 0 }}%
                        Terpakai</span>
                </div>
            </div>

            <div
                class="p-5 rounded-xl border bg-white dark:bg-[#0A0B0D] border-slate-200 dark:border-[#1E2025] flex flex-col justify-between shadow-sm relative overflow-hidden">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-[10px] uppercase font-bold text-slate-500 dark:text-slate-400 tracking-wider">Sisa
                            Saldo Anggaran</p>
                        @php $diffSum = ($totalBudget ?? 0) - ($totalSpent ?? 0); @endphp
                        <h3 id="stat_total_remain"
                            class="text-xl font-black mt-1 {{ $diffSum < 0 ? 'text-red-500' : 'text-emerald-600 dark:text-emerald-400' }}">
                            {{ $diffSum < 0 ? 'Defisit ' : '' }}Rp {{ number_format(abs($diffSum), 0, ',', '.') }}
                        </h3>
                    </div>
                    <div class="p-3 bg-emerald-500/10 text-emerald-500 dark:text-emerald-400 rounded-lg">
                        <i
                            class="fa-solid {{ $diffSum < 0 ? 'fa-circle-exclamation text-red-500' : 'fa-circle-check' }}"></i>
                    </div>
                </div>
                <div class="text-[10px] text-slate-400 mt-4 pt-3 border-t border-slate-100 dark:border-white/5 font-medium">
                    {{ $diffSum < 0 ? 'Alokasi dana kritis! Segera kurangi pengeluaran Anda.' : 'Status Aman! Alokasi pengeluaran masih berada dalam koridor rencana.' }}
                </div>
            </div>

        </div>

        <div class="mt-6 grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

            <div
                class="lg:col-span-4 bg-white dark:bg-[#0A0B0D] border border-slate-200 dark:border-[#1E2025] rounded-xl p-5 md:p-6 shadow-sm space-y-4">

                <header>
                    <div class="flex items-center gap-2">
                        <i
                            class="fa-solid {{ isset($editableBudget) ? 'fa-pen-to-square text-amber-500' : 'fa-plus text-blue-600 dark:text-[#0052ff]' }}"></i>
                        <h3 id="form_title" class="text-sm font-bold text-slate-800 dark:text-white">
                            {{ isset($editableBudget) ? 'Edit Anggaran Terpilih' : 'Tambah Batas Anggaran' }}
                        </h3>
                    </div>
                    <p class="text-[10px] text-slate-550 dark:text-slate-400 leading-normal mt-1">
                        Terapkan batas pembatasan keras (hard-cap) per kategori belanja agar asisten finansial dapat
                        mengunci dompet.
                    </p>
                </header>

                <form
                    action="{{ isset($editableBudget) ? route('budgeting.update', $editableBudget->id) : route('budgeting.store') }}"
                    method="POST" id="budget_form" class="space-y-4">
                    @csrf
                    @if (isset($editableBudget))
                        @method('PUT')
                    @endif

                    <div>
                        <label for="category_id"
                            class="text-[11px] font-bold text-slate-505 dark:text-slate-400 block mb-1">Kategori
                            Pengeluaran</label>
                        <select id="category_id" name="category_id" required
                            class="w-full bg-slate-50 dark:bg-[#121414] border border-slate-300 dark:border-[#434656]/50 rounded-lg px-3 py-2 text-xs text-slate-800 dark:text-white focus:outline-none focus:border-blue-500 cursor-pointer font-medium">
                            <option value="" disabled {{ !isset($editableBudget) ? 'selected' : '' }}>-- Pilih
                                Kategori --</option>
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->id }}"
                                    {{ old('category_id', $editableBudget->category_id ?? '') == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="amount"
                            class="text-[11px] font-bold text-slate-505 dark:text-slate-400 block mb-1">Batas Nominal Limit
                            (Rp)</label>
                        <input type="text" id="amount" name="amount"
                            value="{{ old('amount', $editableBudget->amount ?? '') }}" oninput="formatIDR(this)" required
                            placeholder="Contoh: Rp 1.500.000"
                            class="w-full bg-slate-50 dark:bg-[#121414] border border-slate-300 dark:border-[#434656]/50 rounded-lg px-3 py-2 text-xs text-slate-800 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:border-blue-500 font-medium">
                    </div>

                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label for="month"
                                class="text-[11px] font-bold text-slate-505 dark:text-slate-400 block mb-1">Bulan</label>
                            <select id="month" name="month" required
                                class="w-full bg-slate-50 dark:bg-[#121414] border border-slate-300 dark:border-[#434656]/50 rounded-lg px-3 py-2 text-xs text-slate-800 dark:text-white focus:outline-none focus:border-blue-500 cursor-pointer font-medium">
                                @foreach (range(1, 12) as $m)
                                    <option value="{{ $m }}"
                                        {{ old('month', $editableBudget->month ?? $selectedMonth) == $m ? 'selected' : '' }}>
                                        {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="year"
                                class="text-[11px] font-bold text-slate-505 dark:text-slate-400 block mb-1">Tahun</label>
                            <select id="year" name="year" required
                                class="w-full bg-slate-50 dark:bg-[#121414] border border-slate-300 dark:border-[#434656]/50 rounded-lg px-3 py-2 text-xs text-slate-800 dark:text-white focus:outline-none focus:border-blue-500 cursor-pointer font-medium">
                                @foreach (range(now()->year, now()->year + 1) as $y)
                                    <option value="{{ $y }}"
                                        {{ old('year', $editableBudget->year ?? $selectedYear) == $y ? 'selected' : '' }}>
                                        {{ $y }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="flex gap-2">
                        @if (isset($editableBudget))
                            <a href="{{ route('budgeting.index') }}" id="btn_cancel_edit"
                                class="flex-1 py-2.5 bg-slate-200 dark:bg-[#1E2025] hover:bg-slate-300 dark:hover:bg-[#1E2025]/80 rounded-lg text-xs font-bold text-slate-700 dark:text-white transition-all text-center">
                                Batal
                            </a>
                        @endif
                        <button type="submit" id="btn_submit_budget"
                            class="flex-1 py-2.5 bg-blue-600 hover:bg-blue-700 dark:bg-[#0052ff] dark:hover:bg-[#0047df] rounded-lg text-xs font-bold text-white transition-all shadow-md active:scale-95 text-center cursor-pointer">
                            {{ isset($editableBudget) ? 'Simpan Perubahan' : 'Simpan Anggaran' }}
                        </button>
                    </div>
                </form>

                <div
                    class="p-4 rounded-xl bg-blue-500/5 border border-blue-500/10 flex gap-2.5 text-[11px] text-slate-505 dark:text-slate-450 leading-relaxed">
                    <i class="fa-solid fa-circle-info text-blue-500 mt-0.5 shrink-0 text-xs"></i>
                    <div>
                        <span class="font-bold text-slate-800 dark:text-white block mb-0.5">Saran Alokasi</span>
                        Batasi budget kategori kebutuhan sekunder (seperti hiburan / makan restoran mewah) di kisaran bawah
                        30% dari total pendapatan bulanan Anda.
                    </div>
                </div>

            </div>

            <div
                class="lg:col-span-8 bg-white dark:bg-[#0A0B0D] border border-slate-200 dark:border-[#1E2025] rounded-xl p-5 md:p-6 shadow-sm space-y-4">

                <div class="flex items-center justify-between border-b border-slate-100 dark:border-white/5 pb-3">
                    <div>
                        <h3 class="text-sm font-bold text-slate-800 dark:text-white flex items-center gap-1.5">
                            Daftar & Progress Pemakaian Kategori
                        </h3>
                        <p id="tracker_period_title" class="text-[10px] text-slate-500 dark:text-slate-400 font-medium">
                            {{ $showAll
                                ? 'Menampilkan seluruh data anggaran dari semua periode'
                                : 'Batas alokasi vs realisasi untuk periode ' .
                                    \Carbon\Carbon::create()->month($selectedMonth)->translatedFormat('F') .
                                    ' ' .
                                    $selectedYear }}
                        </p>
                    </div>
                    <div class="flex items-center gap-2">
                        <span id="tracker_count_badge"
                            class="text-[10px] text-slate-400 dark:text-slate-500 font-bold bg-slate-50 dark:bg-[#121414] px-3 py-1.5 border border-slate-200 dark:border-white/5 rounded-lg">
                            {{ $budgets->count() }} Anggaran Terdaftar
                        </span>

                        @if ($showAll)
                            <a href="{{ route('budgeting.index', ['filter_month' => $selectedMonth, 'filter_year' => $selectedYear]) }}"
                                class="text-[10px] font-bold px-3 py-1.5 rounded-lg border border-slate-200 dark:border-white/10 bg-slate-100 dark:bg-white/5 text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-white/10 transition-all">
                                <i class="fa-solid fa-filter mr-1"></i> Kembali Filter
                            </a>
                        @else
                            <a href="{{ route('budgeting.index', ['show_all' => true]) }}"
                                class="text-[10px] font-bold px-3 py-1.5 rounded-lg border border-blue-200 dark:border-[#0052ff]/30 bg-blue-50 dark:bg-[#0052ff]/10 text-blue-600 dark:text-[#b7c4ff] hover:bg-blue-100 dark:hover:bg-[#0052ff]/20 transition-all">
                                <i class="fa-solid fa-list mr-1"></i> Lihat Semua
                            </a>
                        @endif
                    </div>
                </div>

                <div id="budget_list_container"
                    class="space-y-4 max-h-[600px] overflow-y-auto pr-1 scrollbar-thin scrollbar-thumb-slate-300 dark:scrollbar-thumb-white/10 scrollbar-track-transparent">
                    @forelse($budgets as $budget)
                        @php
                            $categoryName = strtolower($budget->category->name ?? '');

                            $percentage =
                                $budget->amount > 0 ? min(100, round(($budget->spent / $budget->amount) * 100)) : 0;
                            $isOverBudget = $budget->spent > $budget->amount;
                            $diff = abs($budget->amount - $budget->spent);

                            $iconClass = 'fa-solid fa-tag';
                            $iconBgColor = 'bg-blue-500/10 text-blue-400';
                            if (str_contains($categoryName, 'makan')) {
                                $iconClass = 'fa-solid fa-utensils';
                                $iconBgColor = 'bg-orange-500/10 text-orange-400';
                            } elseif (str_contains($categoryName, 'transport')) {
                                $iconClass = 'fa-solid fa-car';
                                $iconBgColor = 'bg-blue-400/10 text-blue-400';
                            } elseif (str_contains($categoryName, 'belanja')) {
                                $iconClass = 'fa-solid fa-shopping-bag';
                                $iconBgColor = 'bg-indigo-500/10 text-indigo-400';
                            }

                            $borderStyle = $isOverBudget
                                ? 'border-red-500/25 bg-red-500/[0.01]'
                                : ($percentage >= 80
                                    ? 'border-amber-500/25 bg-amber-500/[0.01]'
                                    : 'border-slate-200 dark:border-[#434656]/20 bg-slate-50/50 dark:bg-[#121414]/25');
                        @endphp

                        <div
                            class="p-4 rounded-lg border-2 {{ $borderStyle }} flex flex-col justify-between transition-all relative overflow-hidden">
                            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
                                <div class="flex items-center gap-3">
                                    <div class="p-2.5 rounded-lg {{ $iconBgColor }}">
                                        <i class="{{ $iconClass }} text-md"></i>
                                    </div>
                                    <div>
                                        <h4 class="text-xs font-black text-slate-800 dark:text-white capitalize">
                                            {{ $budget->category->name ?? '-' }}</h4>
                                        <p class="text-[10px] text-slate-550 dark:text-slate-440 mt-0.5">Periode:
                                            {{ \Carbon\Carbon::create()->month($budget->month)->translatedFormat('F') }}
                                            {{ $budget->year }}</p>
                                    </div>
                                </div>
                                <div>
                                    @if ($isOverBudget)
                                        <span
                                            class="px-2 py-0.5 rounded text-[9px] font-black tracking-tight bg-red-100 dark:bg-red-500/15 text-red-650 dark:text-red-400 border border-red-200">
                                            <i class="fa-solid fa-circle-exclamation"></i> Overspent
                                        </span>
                                    @elseif($percentage >= 80)
                                        <span
                                            class="px-2 py-0.5 rounded text-[9px] font-black tracking-tight bg-amber-50 dark:bg-amber-500/15 text-amber-700 dark:text-amber-450 border border-amber-200">
                                            <i class="fa-solid fa-triangle-exclamation"></i> Hampir Habis
                                        </span>
                                    @else
                                        <span
                                            class="px-2 py-0.5 rounded text-[9px] font-black tracking-tight bg-emerald-50 dark:bg-emerald-500/15 text-emerald-600 dark:text-emerald-450 border border-emerald-200">
                                            <i class="fa-solid fa-shield"></i> Aman
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="mt-4 grid grid-cols-3 gap-3 border-t border-slate-100 dark:border-white/5 pt-3.5">
                                <div>
                                    <span class="text-[9px] text-slate-400 block">Target Batasan:</span>
                                    <span class="text-xs font-black text-slate-700 dark:text-white font-mono">Rp
                                        {{ number_format($budget->amount, 0, ',', '.') }}</span>
                                </div>
                                <div>
                                    <span class="text-[9px] text-slate-400 block">Riil Keluar:</span>
                                    <span
                                        class="text-xs font-black font-mono {{ $isOverBudget ? 'text-red-500' : 'text-slate-700 dark:text-white' }}">Rp
                                        {{ number_format($budget->spent, 0, ',', '.') }}</span>
                                </div>
                                <div>
                                    <span class="text-[9px] text-slate-400 block">Sisa Alokasi:</span>
                                    <span
                                        class="text-xs font-black font-mono {{ $isOverBudget ? 'text-red-500' : 'text-emerald-500' }}">
                                        {{ $isOverBudget ? 'Defisit ' : '' }}Rp {{ number_format($diff, 0, ',', '.') }}
                                    </span>
                                </div>
                            </div>

                            <div class="space-y-1 mt-4">
                                <div class="flex justify-between items-center text-[9px] text-slate-505 font-bold">
                                    <span>Pemakaian Alokasi</span>
                                    <span
                                        class="font-extrabold text-slate-800 dark:text-white font-mono">{{ $percentage }}%</span>
                                </div>
                                <div
                                    class="w-full h-2 bg-slate-100 dark:bg-black/30 rounded-lg overflow-hidden border border-slate-200 dark:border-white/5">
                                    <div class="h-full rounded-lg transition-all duration-500 {{ $isOverBudget ? 'bg-red-500' : ($percentage >= 80 ? 'bg-amber-500' : 'bg-blue-600 dark:bg-[#0052ff]') }}"
                                        style="width: {{ $percentage }}%"></div>
                                </div>
                            </div>

                            <div
                                class="flex items-center justify-end gap-2.5 mt-4 pt-3.5 border-t border-slate-100 dark:border-white/5">
                                <a href="{{ route('budgeting.index', ['edit' => $budget->id, 'filter_month' => $selectedMonth, 'filter_year' => $selectedYear]) }}"
                                    class="p-1.5 rounded-lg bg-blue-100 dark:bg-[#0052FF]/10 text-blue-650 dark:text-[#b7c4ff] hover:bg-blue-200 dark:hover:bg-[#0052FF]/20 transition-all cursor-pointer flex items-center justify-center">
                                    <i class="fa-solid fa-pen-to-square text-xs mr-1"></i>
                                    <span class="text-[10px] font-bold">Edit</span>
                                </a>
                                <form action="{{ route('budgeting.destroy', $budget->id) }}" method="POST"
                                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus anggaran ini?')"
                                    class="inline">
                                    @csrf
                                    @if(true)
                                        @method('DELETE')
                                    @endif
                                    <button type="submit"
                                        class="p-1.5 rounded-lg bg-red-100 dark:bg-red-400/10 text-red-650 dark:text-red-400 hover:bg-red-200 dark:hover:bg-red-450/20 transition-all cursor-pointer flex items-center justify-center">
                                        <i class="fa-solid fa-trash text-xs mr-1"></i>
                                        <span class="text-[10px] font-bold">Hapus</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div
                            class="p-8 text-center border-2 border-dashed border-slate-200 dark:border-white/10 rounded-xl text-slate-400 text-xs font-medium">
                            <i class="fa-solid fa-folder-open text-xl block mb-2 text-slate-300"></i>
                            Belum ada batas anggaran yang diatur untuk periode ini.
                        </div>
                    @endforelse
                </div>

            </div>

        </div>

    </main>

    <script src="{{ asset('js/formatIDR.js') }}"></script>
    <script>
        const swalBg = '#1a1d1d';
        const swalColor = '#e2e2e2';

        document.addEventListener('DOMContentLoaded', function() {

            const amountInput = document.getElementById('amount');
            const budgetForm = document.getElementById('budget_form');

            // ── GANTI BLOK INI (Taruh di sini) ──────────────────────────
            if (amountInput && amountInput.value) {
                let initialValue = amountInput.value;

                // PERBAIKAN: Jika nilai dari database mengandung titik desimal/sen (ex: 100000.00)
                // Pisahkan dan ambil angka utuh sebelum titik desimalnya saja
                if (initialValue.includes('.')) {
                    initialValue = initialValue.split('.')[0];
                }

                // Strip semua karakter non-angka yang tersisa, lalu format ke IDR
                amountInput.value = initialValue.replace(/[^0-9]/g, '');
                formatIDR(amountInput);
            }

            // ── Bersihkan format sebelum submit ────────────────────────
            if (budgetForm && amountInput) {
                budgetForm.addEventListener('submit', function(e) {
                    // Validasi nominal > 0
                    const raw = getRawValue(amountInput.value);
                    if (!raw || parseFloat(raw) <= 0) {
                        e.preventDefault();
                        Swal.fire({
                            title: 'Nominal Salah',
                            text: 'Batas nominal anggaran harus lebih besar dari Rp 0.',
                            icon: 'error',
                            background: swalBg,
                            color: swalColor,
                            confirmButtonColor: '#0052ff'
                        });
                        return;
                    }
                    // Strip format sebelum kirim ke Laravel
                    amountInput.value = raw;
                });
            }

            // ── Flash messages ─────────────────────────────────────────
            @if (session('success'))
                Swal.fire({
                    title: 'Berhasil!',
                    text: "{{ session('success') }}",
                    icon: 'success',
                    background: swalBg,
                    color: swalColor,
                    confirmButtonColor: '#0052ff'
                });
            @endif

            @if (session('error'))
                Swal.fire({
                    title: 'Gagal!',
                    text: "{{ session('error') }}",
                    icon: 'error',
                    background: swalBg,
                    color: swalColor,
                    confirmButtonColor: '#ef4444'
                });
            @endif

            @if ($errors->any())
                Swal.fire({
                    title: 'Validasi Gagal',
                    html: `{!! implode('<br>', $errors->all()) !!}`,
                    icon: 'warning',
                    background: swalBg,
                    color: swalColor,
                    confirmButtonColor: '#0052ff'
                });
            @endif

        });

        // ── SweetAlert confirm delete ──────────────────────────────────
        function confirmDelete(event, form) {
            event.preventDefault();
            Swal.fire({
                title: 'Hapus Anggaran?',
                html: 'Apakah Anda yakin ingin membatalkan plafon batas anggaran kategori ini?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#374151',
                background: swalBg,
                color: swalColor
            }).then((result) => {
                if (result.isConfirmed) form.submit();
            });
            return false;
        }
    </script>
@endsection
