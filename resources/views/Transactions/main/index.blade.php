{{-- transactions/main/index.blade.php --}}

@extends('layouts.app')

@section('content')

{{--
    Data kategori di-encode ke JSON untuk dipakai JS filter modal.
    Semua kategori dikirim, JS yang handle filter berdasarkan type.
--}}
@php
    $categoriesJson = $categories->map(fn($c) => ['id' => $c->id, 'name' => $c->name, 'type' => $c->type])->toJson();
@endphp

<main class="flex-1 md:ml-[240px] p-margin-mobile md:p-margin-desktop bg-grid-pattern min-h-screen space-y-6">

    {{-- ==============================
         HEADER SECTION
    ============================== --}}
    <header class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 border-b border-neutral-800 pb-5">
        <div>
            <h1 class="text-2xl font-extrabold text-white tracking-tight flex items-center gap-2">
                <span class="p-2 rounded-lg bg-blue-600/10 text-blue-500">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M21 18v1c0 1.1-.9 2-2 2H5c-1.11 0-2-.9-2-2V5c0-1.1.89-2 2-2h14c1.1 0 2 .9 2 2v1h-9c-1.11 0-2 .9-2 2v8c0 1.1.89 2 2 2h9zm-9-2h10V8H12v8zm4-2.5c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5z"/>
                    </svg>
                </span>
                Riwayat Transaksi Keuangan
            </h1>
            <p class="text-xs text-neutral-400 mt-1">Daftar arus kas masuk dan keluar beserta integrasi penyaringan rekening dompet.</p>
        </div>

        
    </header>

    {{-- ==============================
         FILTER SECTION
         — Submit via GET ke route transactions.index
         — Kategori di-filter client-side berdasarkan tipe yang dipilih
    ============================== --}}
    <form method="GET" action="{{ route('transactions.index') }}" id="filter_form">
        <section class="bg-neutral-900 border border-neutral-800 rounded-xl p-5 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-4">

            {{-- 1. Pencarian Kata Kunci --}}
<div class="flex flex-col gap-1.5">
    <label class="text-xs font-semibold text-neutral-300">Cari Deskripsi</label>
    <div class="flex gap-1.5">
        <input type="text" name="search" id="filter_search"
            value="{{ request('search') }}"
            placeholder="Cari transaksi..."
            class="w-full bg-neutral-950 border border-neutral-800 rounded-lg px-3 py-2 text-xs text-white placeholder-neutral-500 focus:outline-none focus:border-blue-500">
        <button type="button" onclick="document.getElementById('filter_form').submit()"
            class="cursor-pointer px-3 py-2 bg-blue-600 hover:bg-blue-500 rounded-lg text-white text-xs font-bold shrink-0">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 11A6 6 0 111 11a6 6 0 0116 0z"/>
            </svg>
        </button>
    </div>
</div>

            {{-- 2. Dropdown Tipe Saldo —— trigger filter kategori --}}
            <div class="flex flex-col gap-1.5">
                <label class="text-xs font-semibold text-neutral-300">Tipe Saldo</label>
                <select name="type" id="filter_type"
                    class="w-full bg-neutral-950 border border-neutral-800 rounded-lg px-3 py-2 text-xs text-white focus:outline-none focus:border-blue-500 cursor-pointer">
                    <option value="all" {{ request('type', 'all') === 'all' ? 'selected' : '' }}>Semua Tipe</option>
                    <option value="income"  {{ request('type') === 'income'  ? 'selected' : '' }}>Pemasukan (+)</option>
                    <option value="expense" {{ request('type') === 'expense' ? 'selected' : '' }}>Pengeluaran (-)</option>
                </select>
            </div>



            {{-- 5. Tanggal Mulai --}}
<div class="flex flex-col gap-1.5">
    <label class="text-xs font-semibold text-neutral-300">Tanggal Mulai</label>
    <div class="relative">
        <i class="fa-solid fa-calendar-days absolute left-3 top-1/2 -translate-y-1/2 text-neutral-500 text-xs pointer-events-none"></i>
        <input type="text" name="start_date" id="filter_start_date"
            value="{{ request('start_date') }}"
            placeholder="Pilih tanggal mulai"
            class="w-full bg-neutral-950 border border-neutral-800 rounded-lg pl-9 pr-3 py-2 text-xs text-white focus:outline-none focus:border-blue-500 cursor-pointer shadow-sm placeholder:text-neutral-600">
    </div>
</div>

{{-- 6. Tanggal Selesai --}}
<div class="flex flex-col gap-1.5">
    <label class="text-xs font-semibold text-neutral-300">Tanggal Selesai</label>
    <div class="flex gap-1.5">
        <div class="relative w-full">
            <i class="fa-solid fa-calendar-days absolute left-3 top-1/2 -translate-y-1/2 text-neutral-500 text-xs pointer-events-none"></i>
            <input type="text" name="end_date" id="filter_end_date"
                value="{{ request('end_date') }}"
                placeholder="Pilih tanggal selesai"
                class="w-full bg-neutral-950 border border-neutral-800 rounded-lg pl-9 pr-3 py-2 text-xs text-white focus:outline-none focus:border-blue-500 cursor-pointer shadow-sm placeholder:text-neutral-600">
        </div>
        <button type="button" id="btn_apply_date"
            class="cursor-pointer px-4 py-2 bg-blue-600 hover:bg-blue-500 rounded-lg text-white text-xs font-bold shrink-0 transition-colors shadow-lg shadow-blue-600/10">
            OK
        </button>
    </div>
</div>

        </section>
    </form>

{{-- TABEL TRANSAKSI --}}
<section class="bg-neutral-900 border border-neutral-800 rounded-xl overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-neutral-950 border-b border-neutral-800 text-[10px] font-bold text-neutral-400 uppercase tracking-wider">
                    <th class="p-4">Tanggal</th>
                    <th class="p-4">Deskripsi</th>
                    <th class="p-4">Tipe</th>
                    <th class="p-4">No. Rekening</th>
                    <th class="p-4">Kepada</th>
                    <th class="p-4 text-right">Jumlah</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-neutral-800 text-xs">
                @forelse($transactions as $transaction)
                <tr class="hover:bg-neutral-800/30 transition">

                    {{-- Tanggal --}}
                    <td class="p-4 font-mono text-neutral-400">
                        {{ \Carbon\Carbon::parse($transaction->transaction_date)->format('Y-m-d') }}
                    </td>

                    {{-- Deskripsi --}}
                   {{-- Deskripsi --}}
<td class="p-4 font-semibold text-white">
    @if($transaction->type === 'income' && is_null($transaction->account_id))
        @if(is_null($transaction->category_id) && $transaction->note === null)
            Top Up VA
        @else
            Top Up Gerai
        @endif
    @else
        {{ $transaction->note ?? '-' }}
    @endif
</td>



                    {{-- Tipe --}}
                    <td class="p-4">
                        @if($transaction->type === 'income')
                            <span class="bg-emerald-500/10 text-emerald-400 px-2.5 py-1 rounded-full text-[10px] font-bold">
                                Income
                            </span>
                        @else
                            <span class="bg-red-500/10 text-red-400 px-2.5 py-1 rounded-full text-[10px] font-bold">
                                Expense
                            </span>
                        @endif
                    </td>

                    {{-- No. Rekening dari main-account --}}
                    <td class="p-4 font-mono text-neutral-300">
                        {{ $mainAccount->account_no ?? '-' }}
                    </td>

                    {{-- Kepada --}}
<td class="p-4 text-neutral-300">
    @if($transaction->type === 'expense' && $transaction->account)
        {{ $transaction->account->name }}
    @else
        <span class="text-neutral-600">-</span>
    @endif
</td>
                    {{-- Jumlah --}}
                    <td class="p-4 font-mono font-bold text-right {{ $transaction->type === 'income' ? 'text-emerald-400' : 'text-red-400' }}">
                        {{ $transaction->type === 'income' ? '+' : '-' }}Rp {{ number_format($transaction->amount, 0, ',', '.') }}
                    </td>

                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-8 text-neutral-500 text-xs">
                        Tidak ada data transaksi yang cocok dengan saringan filter.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</section>

</main>

{{-- ==============================
     MODAL CREATE
============================== --}}



{{-- ==============================
     JAVASCRIPT
============================== --}}
<script src="{{asset('js/formatIDR.js')}}"></script>
<script>
    // ==============================
    // DATA KATEGORI dari PHP (JSON)
    // ==============================
    const ALL_CATEGORIES = {!! $categoriesJson !!};

    // ==============================
    // HELPER: render options kategori berdasarkan tipe
    // target     = DOM select element
    // filterType = 'income' | 'expense' | 'all'
    // selectedId = id yang mau dipre-select (opsional)
    // ==============================
    function renderCategoryOptions(target, filterType, selectedId = null) {
        if (!target) return;
        const filtered = filterType === 'all'
            ? ALL_CATEGORIES
            : ALL_CATEGORIES.filter(c => c.type === filterType);

        target.innerHTML = filtered.map(c =>
            `<option value="${c.id}" ${c.id == selectedId ? 'selected' : ''}>${c.name}</option>`
        ).join('');
    }

    // ==============================
    // FILTER PAGE — render kategori saat halaman load & saat tipe berubah
    // ==============================
    const filterTypeEl     = document.getElementById('filter_type');
    const filterCategoryEl = document.getElementById('filter_category');

    function initFilterCategory() {
        if (!filterTypeEl || !filterCategoryEl) return;
        
        const currentType       = filterTypeEl.value;
        const currentCategoryId = '{{ request('category_id') }}';

        filterCategoryEl.innerHTML = `<option value="all">Semua Kategori</option>`;

        const filtered = currentType === 'all'
            ? ALL_CATEGORIES
            : ALL_CATEGORIES.filter(c => c.type === currentType);

        filtered.forEach(c => {
            const opt = document.createElement('option');
            opt.value = c.id;
            opt.textContent = c.name;
            if (c.id == currentCategoryId) opt.selected = true;
            filterCategoryEl.appendChild(opt);
        });
    }

    // Render saat halaman load
    initFilterCategory();

    if (filterTypeEl) {
        filterTypeEl.addEventListener('change', function () {
            initFilterCategory();
            filterCategoryEl.value = 'all';
        });
    }

    // ==============================
    // INITIALIZATION: FLATPICKR CONFIGS
    // ==============================
    const baseDateConfig = {
        dateFormat: "Y-m-d",
        altInput: true,
        altFormat: "d M Y", 
        disableMobile: "true"
    };

    // Filter Tanggal Mulai
    const startDatePicker = flatpickr("#filter_start_date", {
        ...baseDateConfig,
        onChange: function(selectedDates, dateStr) {
            if (typeof endDatePicker !== 'undefined') {
                endDatePicker.set('minDate', dateStr);
            }
        }
    });

    // Filter Tanggal Selesai
    const endDatePicker = flatpickr("#filter_end_date", {
        ...baseDateConfig,
        minDate: document.getElementById('filter_start_date')?.value || ""
    });

    // Modal Create Tanggal
  
    // ==============================
    // AUTO-SUBMIT & ACTION FILTERS
    // ==============================
    document.querySelectorAll('#filter_form select').forEach(el => {
        el.addEventListener('change', () => document.getElementById('filter_form').submit());
    });

    const filterSearchEl = document.getElementById('filter_search');
    if (filterSearchEl) {
        filterSearchEl.addEventListener('keydown', function (e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                document.getElementById('filter_form').submit();
            }
        });
    }

    const btnApplyDateEl = document.getElementById('btn_apply_date');
    if (btnApplyDateEl) {
        btnApplyDateEl.addEventListener('click', function () {
            document.getElementById('filter_form').submit();
        });
    }



   

    
</script>

@endsection