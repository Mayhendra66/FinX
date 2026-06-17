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

        <button onclick="bukaModalCreate()"
            class="cursor-pointer inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-500 active:scale-95 transition text-white px-4 py-2.5 rounded-lg text-xs font-bold shadow-lg shadow-blue-600/20">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Transaksi Baru
        </button>
    </header>

    {{-- ==============================
         FILTER SECTION
         — Submit via GET ke route transactions.index
         — Kategori di-filter client-side berdasarkan tipe yang dipilih
    ============================== --}}
    <form method="GET" action="{{ route('transactions.index') }}" id="filter_form">
        <section class="bg-neutral-900 border border-neutral-800 rounded-xl p-5 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-6 gap-4">

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

            {{-- 3. Dropdown Kategori (difilter JS berdasarkan tipe) --}}
            <div class="flex flex-col gap-1.5">
                <label class="text-xs font-semibold text-neutral-300">Kategori</label>
                <select name="category_id" id="filter_category"
                    class="w-full bg-neutral-950 border border-neutral-800 rounded-lg px-3 py-2 text-xs text-white focus:outline-none focus:border-blue-500 cursor-pointer">
                    {{-- Options di-render JS via renderFilterCategory() --}}
                </select>
            </div>

            {{-- 4. Dropdown Rekening/Dompet --}}
            <div class="flex flex-col gap-1.5">
                <label class="text-xs font-semibold text-neutral-300">Rekening Dompet</label>
                <select name="account_id" id="filter_wallet"
                    class="w-full bg-neutral-950 border border-neutral-800 rounded-lg px-3 py-2 text-xs text-white focus:outline-none focus:border-blue-500 cursor-pointer">
                    <option value="all" {{ request('account_id', 'all') === 'all' ? 'selected' : '' }}>Semua Rekening</option>
                    @foreach($accounts as $account)
                        <option value="{{ $account->id }}" {{ request('account_id') == $account->id ? 'selected' : '' }}>
                            {{ $account->name }}
                        </option>
                    @endforeach
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

    {{-- ==============================
         TABEL TRANSAKSI
    ============================== --}}
    <section class="bg-neutral-900 border border-neutral-800 rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-neutral-950 border-b border-neutral-800 text-[10px] font-bold text-neutral-400 uppercase tracking-wider">
                        <th class="p-4">Tanggal</th>
                        <th class="p-4">Deskripsi</th>
                        <th class="p-4">Kategori</th>
                        <th class="p-4">Rekening Asal</th>
                        <th class="p-4 text-right">Jumlah</th>
                        <th class="p-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-800 text-xs">
                    @forelse($transactions as $transaction)
                    <tr class="hover:bg-neutral-800/30 transition">
                        <td class="p-4 font-mono text-neutral-400">
                            {{ \Carbon\Carbon::parse($transaction->transaction_date)->format('Y-m-d') }}
                        </td>
                        <td class="p-4 font-semibold text-white">{{ $transaction->note ?? '-' }}</td>
                        <td class="p-4">
                            @if($transaction->type === 'income')
                                <span class="bg-emerald-500/10 text-emerald-400 px-2.5 py-1 rounded-full text-[10px] font-bold">
                                    {{ $transaction->category->name ?? '-' }}
                                </span>
                            @else
                                <span class="bg-red-500/10 text-red-400 px-2.5 py-1 rounded-full text-[10px] font-bold">
                                    {{ $transaction->category->name ?? '-' }}
                                </span>
                            @endif
                        </td>
                        <td class="p-4 text-neutral-300">{{ $transaction->account->name ?? '-' }}</td>
                        <td class="p-4 font-mono font-bold text-right {{ $transaction->type === 'income' ? 'text-emerald-400' : 'text-red-400' }}">
                            {{ $transaction->type === 'income' ? '+' : '-' }}Rp {{ number_format($transaction->amount, 0, ',', '.') }}
                        </td>
                        <td class="p-4 text-center">
                            <div class="inline-flex gap-2">
                                <button
                                    onclick="bukaModalUpdate(
                                        {{ $transaction->id }},
                                        '{{ addslashes($transaction->note) }}',
                                        {{ $transaction->category_id }},
                                        {{ $transaction->account_id }},
                                        {{ $transaction->amount }},
                                        '{{ $transaction->type }}',
                                        '{{ $transaction->created_at->toISOString() }}'
                                    )"
                                    class="cursor-pointer p-1.5 rounded bg-neutral-800 hover:bg-neutral-700 text-blue-400 transition"
                                    title="Ubah Transaksi">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                    </svg>
                                </button>

                                <button
                                    onclick="hapusTransaksi({{ $transaction->id }})"
                                    class="cursor-pointer p-1.5 rounded bg-neutral-800 hover:bg-red-500/20 text-red-400 transition"
                                    title="Hapus Transaksi">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>

                                <form id="delete-form-{{ $transaction->id }}"
                                    action="{{ route('transactions.destroy', $transaction->id) }}"
                                    method="POST" class="hidden">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </div>
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
<div id="modal_create" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center hidden p-4">
    <div class="bg-neutral-900 border border-neutral-800 rounded-xl w-full max-w-md p-6 relative shadow-2xl">
        <h2 class="text-base font-bold text-white mb-4 flex items-center gap-2">
            <span class="p-1.5 rounded bg-emerald-500/10 text-emerald-400">+</span>
            Tambah Data Transaksi
        </h2>

        <form id="form_add_transaction" class="space-y-4 text-xs">
            @csrf

            <div>
                <label class="block text-neutral-300 font-semibold mb-1">Tipe</label>
                <div class="grid grid-cols-2 gap-2">
                    <label class="border border-neutral-800 bg-neutral-950 p-2 text-center rounded-lg cursor-pointer block">
                        <input type="radio" name="type" id="create_type_income" value="income" class="mr-1" checked> Pemasukan
                    </label>
                    <label class="border border-neutral-800 bg-neutral-950 p-2 text-center rounded-lg cursor-pointer block">
                        <input type="radio" name="type" id="create_type_expense" value="expense" class="mr-1"> Pengeluaran
                    </label>
                </div>
            </div>

            <div>
                <label class="block text-neutral-300 font-semibold mb-1">Pilih Rekening Dompet</label>
                <select name="account_id" required class="w-full bg-neutral-950 border border-neutral-800 rounded-lg p-2.5 text-white">
                    @foreach($accounts as $account)
                        <option value="{{ $account->id }}">{{ $account->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-neutral-300 font-semibold mb-1">Jumlah Nominal (Rupiah)</label>
                <input type="text" id="create_amount" name="amount" required
                    placeholder="Contoh: Rp 150.000"
                    oninput="formatIDR(this)"
                    class="w-full bg-neutral-950 border border-neutral-800 rounded-lg p-2.5 text-white">
            </div>

            <div>
                <label class="block text-neutral-300 font-semibold mb-1">Kategori</label>
                <select name="category_id" id="create_category" required
                    class="w-full bg-neutral-950 border border-neutral-800 rounded-lg p-2.5 text-white">
                </select>
            </div>

            {{-- Tanggal Create (Modifikasi Flatpickr) --}}
            <div>
                <label class="block text-neutral-300 font-semibold mb-1">Tanggal Transaksi</label>
                <div class="relative">
                    <i class="fa-solid fa-calendar-days absolute left-3 top-1/2 -translate-y-1/2 text-neutral-500 text-xs pointer-events-none"></i>
                    <input type="text" name="transaction_date" id="create_transaction_date" required
                        class="w-full bg-neutral-950 border border-neutral-800 rounded-lg pl-9 pr-3 py-2.5 text-white focus:outline-none focus:border-blue-500 cursor-pointer shadow-sm">
                </div>
            </div>

            <div>
                <label class="block text-neutral-300 font-semibold mb-1">Deskripsi / Catatan</label>
                <textarea name="note" placeholder="Tulis catatan di sini..."
                    class="w-full bg-neutral-950 border border-neutral-800 rounded-lg p-2.5 text-white h-16"></textarea>
            </div>

            <div class="flex justify-end gap-2 pt-2">
                <button type="button" onclick="tutupModalCreate()"
                    class="cursor-pointer px-4 py-2 rounded bg-neutral-800 hover:bg-neutral-700 text-neutral-300">Batal</button>
                <button type="button" onclick="submitCreate()"
                    class="cursor-pointer px-4 py-2 rounded bg-blue-600 hover:bg-blue-500 text-white font-bold">Simpan Transaksi</button>
            </div>
        </form>
    </div>
</div>

<div id="modal_update" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center hidden p-4">
    <div class="bg-neutral-900 border border-neutral-800 rounded-xl w-full max-w-md p-6 relative shadow-2xl">
        <h2 class="text-base font-bold text-white mb-4 flex items-center gap-2">
            <span class="p-1.5 rounded bg-blue-500/10 text-blue-400">✎</span>
            Ubah Data Transaksi
        </h2>

        <form id="form_edit_transaction" class="space-y-4 text-xs">
            @csrf
            <input type="hidden" id="edit_id">

            <div>
                <label class="block text-neutral-300 font-semibold mb-1">Tipe</label>
                <div class="grid grid-cols-2 gap-2">
                    <label class="border border-neutral-800 bg-neutral-950 p-2 text-center rounded-lg cursor-pointer block">
                        <input type="radio" name="type" id="edit_type_income" value="income" class="mr-1"> Pemasukan
                    </label>
                    <label class="border border-neutral-800 bg-neutral-950 p-2 text-center rounded-lg cursor-pointer block">
                        <input type="radio" name="type" id="edit_type_expense" value="expense" class="mr-1"> Pengeluaran
                    </label>
                </div>
            </div>

            <div>
                <label class="block text-neutral-300 font-semibold mb-1">Pilih Rekening Dompet</label>
                <select id="edit_wallet" name="account_id"
                    class="w-full bg-neutral-950 border border-neutral-800 rounded-lg p-2.5 text-white">
                    @foreach($accounts as $account)
                        <option value="{{ $account->id }}">{{ $account->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-neutral-300 font-semibold mb-1">Jumlah Nominal (Rupiah)</label>
                <input type="number" id="edit_amount" name="amount"
                    class="w-full bg-neutral-950 border border-neutral-800 rounded-lg p-2.5 text-white">
            </div>

            <div>
                <label class="block text-neutral-300 font-semibold mb-1">Kategori</label>
                <select id="edit_category" name="category_id"
                    class="w-full bg-neutral-950 border border-neutral-800 rounded-lg p-2.5 text-white">
                </select>
            </div>

            {{-- Tanggal Update (Ditambahkan agar sinkron) --}}
            <div>
                <label class="block text-neutral-300 font-semibold mb-1">Tanggal Transaksi</label>
                <div class="relative">
                    <i class="fa-solid fa-calendar-days absolute left-3 top-1/2 -translate-y-1/2 text-neutral-500 text-xs pointer-events-none"></i>
                    <input type="text" name="transaction_date" id="edit_transaction_date" required
                        class="w-full bg-neutral-950 border border-neutral-800 rounded-lg pl-9 pr-3 py-2.5 text-white focus:outline-none focus:border-blue-500 cursor-pointer shadow-sm">
                </div>
            </div>

            <div>
                <label class="block text-neutral-300 font-semibold mb-1">Deskripsi / Catatan</label>
                <textarea id="edit_description" name="note"
                    class="w-full bg-neutral-950 border border-neutral-800 rounded-lg p-2.5 text-white h-16"></textarea>
            </div>

            <div class="flex justify-end gap-2 pt-2">
                <button type="button" onclick="tutupModalUpdate()"
                    class="cursor-pointer px-4 py-2 rounded bg-neutral-800 hover:bg-neutral-700 text-neutral-300">Batal</button>
                <button type="button" onclick="submitUpdate()"
                    class="cursor-pointer px-4 py-2 rounded bg-blue-600 hover:bg-blue-500 text-white font-bold">Ubah Transaksi</button>
            </div>
        </form>
    </div>
</div>


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
    const createDatePicker = flatpickr("#create_transaction_date", {
        ...baseDateConfig,
        defaultDate: "{{ date('Y-m-d') }}"
    });

    // Modal Update Tanggal
    const editDatePicker = flatpickr("#edit_transaction_date", {
        ...baseDateConfig
    });

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

    // ==============================
    // MODAL CREATE
    // ==============================
    function bukaModalCreate() {
        document.getElementById('create_type_income').checked = true;
        renderCategoryOptions(document.getElementById('create_category'), 'income');
        
        if (createDatePicker) {
            createDatePicker.setDate("{{ date('Y-m-d') }}");
        }
        
        document.getElementById('modal_create').classList.remove('hidden');
    }

    function tutupModalCreate() {
        document.getElementById('modal_create').classList.add('hidden');
        document.getElementById('form_add_transaction').reset();
    }

    document.querySelectorAll('[name="type"][id^="create_type"]').forEach(radio => {
        radio.addEventListener('change', function () {
            renderCategoryOptions(document.getElementById('create_category'), this.value);
        });
    });

    async function submitCreate() {
        const konfirmasi = await Swal.fire({
            title: 'Simpan Transaksi?',
            text: 'Pastikan semua data sudah benar sebelum disimpan.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#0052ff',
            cancelButtonColor: '#374151',
            confirmButtonText: 'Ya, Simpan!',
            cancelButtonText: 'Cek Lagi',
            background: '#17181c',
            color: '#fff',
            customClass: { popup: 'border border-neutral-800 rounded-xl' }
        });
        if (!konfirmasi.isConfirmed) return;

        const form   = document.getElementById('form_add_transaction');
        const csrf   = form.querySelector('[name="_token"]').value;
        const formData = new FormData(form);
        formData.set('amount', getRawValue(document.getElementById('create_amount').value));

        try {
            const response = await fetch('{{ route('transactions.store') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrf,
                    'Accept': 'application/json',
                },
                body: formData,
            });

            const data = await response.json();
            tutupModalCreate();

            if (response.ok) {
                Swal.fire({
                    title: 'Transaksi Ditambahkan!',
                    text: data.message ?? 'Data transaksi berhasil tersimpan.',
                    icon: 'success',
                    background: '#17181c',
                    color: '#fff',
                    confirmButtonColor: '#0052ff',
                    customClass: { popup: 'border border-neutral-800 rounded-xl' }
                }).then(() => window.location.reload());
            } else {
                const errMsg = data.errors
                    ? Object.values(data.errors).flat().join('\n')
                    : (data.message ?? 'Terjadi kesalahan, coba lagi.');
                Swal.fire({
                    title: 'Gagal!',
                    text: errMsg,
                    icon: 'error',
                    background: '#17181c',
                    color: '#fff',
                    confirmButtonColor: '#ef4444',
                });
            }
        } catch (e) {
            console.error(e);
            Swal.fire({ title: 'Error!', text: 'Request gagal: ' + e.message, icon: 'error', background: '#17181c', color: '#fff' });
        }
    }

    // ==============================
    // MODAL UPDATE
    // ==============================
    function bukaModalUpdate(id, desc, categoryId, accountId, amount, type, createdAt) {
        const now       = new Date();
        const created     = new Date(createdAt);
        const diffJam     = (now - created) / (1000 * 60 * 60);

        if (diffJam > 24) {
            Swal.fire({
                title: 'Tidak Dapat Diedit!',
                html: `
                    <p style="font-size:13px; color:#d1d5db; line-height:1.6">
                        Mohon lakukan <strong style="color:#fff">pengecekan ulang</strong> terlebih dahulu.<br><br>
                        Transaksi ini <strong style="color:#f87171">tidak dapat diperbarui</strong> karena sudah melewati batas waktu <strong style="color:#fff">24 jam</strong> sejak dicatat.
                    </p>
                `,
                icon: 'warning',
                background: '#17181c',
                color: '#fff',
                confirmButtonColor: '#0052ff',
                confirmButtonText: 'Mengerti',
                customClass: { popup: 'border border-neutral-800 rounded-xl' }
            });
            return;
        }

        document.getElementById('edit_id').value          = id;
        document.getElementById('edit_description').value = desc;
        document.getElementById('edit_wallet').value      = accountId;
        document.getElementById('edit_amount').value      = amount;

        document.getElementById('edit_type_income').checked  = (type === 'income');
        document.getElementById('edit_type_expense').checked = (type === 'expense');

        renderCategoryOptions(document.getElementById('edit_category'), type, categoryId);
        
        if (editDatePicker) {
            editDatePicker.setDate(createdAt);
        }

        document.getElementById('modal_update').classList.remove('hidden');
    }

    function tutupModalUpdate() {
        document.getElementById('modal_update').classList.add('hidden');
    }

    document.querySelectorAll('[name="type"][id^="edit_type"]').forEach(radio => {
        radio.addEventListener('change', function () {
            renderCategoryOptions(document.getElementById('edit_category'), this.value);
        });
    });

    async function submitUpdate() {
        const konfirmasi = await Swal.fire({
            title: 'Simpan Perubahan?',
            text: 'Pastikan data yang diubah sudah benar sebelum diperbarui.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#0052ff',
            cancelButtonColor: '#374151',
            confirmButtonText: 'Ya, Perbarui!',
            cancelButtonText: 'Cek Lagi',
            background: '#17181c',
            color: '#fff',
            customClass: { popup: 'border border-neutral-800 rounded-xl' }
        });
        if (!konfirmasi.isConfirmed) return;

        const id     = document.getElementById('edit_id').value;
        const form   = document.getElementById('form_edit_transaction');
        const csrf   = form.querySelector('[name="_token"]').value;
        const formData = new FormData(form);

        formData.append('_method', 'PUT');

        try {
            const response = await fetch(`/transactions/${id}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrf,
                    'Accept': 'application/json',
                },
                body: formData,
            });

            const data = await response.json();
            tutupModalUpdate();

            if (response.ok) {
                Swal.fire({
                    title: 'Transaksi Diperbarui!',
                    text: data.message ?? 'Perubahan berhasil disimpan.',
                    icon: 'success',
                    background: '#17181c',
                    color: '#fff',
                    confirmButtonColor: '#0052ff',
                    customClass: { popup: 'border border-neutral-800 rounded-xl' }
                }).then(() => window.location.reload());
            } else {
                const errMsg = data.errors
                    ? Object.values(data.errors).flat().join('\n')
                    : (data.message ?? 'Terjadi kesalahan.');
                Swal.fire({
                    title: 'Gagal!',
                    text: errMsg,
                    icon: 'error',
                    background: '#17181c',
                    color: '#fff',
                    confirmButtonColor: '#ef4444',
                });
            }
        } catch (e) {
            console.error(e);
            Swal.fire({ title: 'Error!', text: 'Request gagal: ' + e.message, icon: 'error', background: '#17181c', color: '#fff' });
        }
    }

    // ==============================
    // HAPUS TRANSAKSI
    // ==============================
    function hapusTransaksi(id) {
        Swal.fire({
            title: 'Apakah Anda Yakin?',
            text: 'Transaksi ini akan dihapus permanen dari rekening bersangkutan.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#374151',
            confirmButtonText: 'Ya, Hapus Saja!',
            cancelButtonText: 'Batal',
            background: '#17181c',
            color: '#fff',
            customClass: { popup: 'border border-neutral-800 rounded-xl' }
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById(`delete-form-${id}`).submit();
            }
        });
    }
</script>

@endsection