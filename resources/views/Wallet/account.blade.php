@extends('layouts.app')

@section('content')
    <main class="flex-1 md:ml-[240px] p-margin-mobile md:p-margin-desktop bg-grid-pattern min-h-screen">

        <header class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
            <div>
                <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-white flex items-center gap-2">
                    <svg class="w-8 h-8 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                            d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z">
                        </path>
                    </svg>
                    Keuanganku <span
                        class="text-xs font-bold bg-brand/10 text-brand px-2.5 py-1 rounded-full uppercase border border-brand/20">Dompet
                        & Kas</span>
                </h1>
                <p class="text-xs sm:text-sm text-slate-400 mt-1">Kelola penempatan dana, rekap saldo terpadu, dan riwayat
                    mutasi kas Anda.</p>
            </div>

            <div class="flex items-start gap-2 w-full sm:w-auto">
                <div class="flex flex-col w-full sm:w-auto">
                    <button onclick="bukaModalTransfer()"
                        @if (($akun->count() ?? 0) < 2) disabled 
              class="w-full sm:w-auto bg-accentGray/40 text-slate-500 border border-slate-800 px-4 py-2.5 rounded-xl font-bold text-xs flex items-center justify-center gap-2 cursor-not-allowed opacity-60"
            @else 
              class="w-full sm:w-auto bg-accentGray hover:bg-slate-800 text-slate-200 border border-slate-700/60 transition px-4 py-2.5 rounded-xl font-bold text-xs flex items-center justify-center gap-2 cursor-pointer" @endif>
                        <svg class="w-4 h-4 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                        </svg>
                        Transfer Aset
                    </button>
                    @if (($akun->count() ?? 0) < 2)
                        <span class="text-[10px] text-red-500 font-medium mt-1.5 block text-center sm:text-left">Anda harus
                            memiliki minimal 2 rekening untuk melakukan transfer</span>
                    @endif
                </div>

                <button onclick="bukaModalTambah()"
                    class="flex-1 sm:flex-initial bg-[#0052ff] hover:bg-[#0047db] text-white transition px-4 py-2.5 rounded-xl font-bold text-xs flex items-center justify-center gap-2 cursor-pointer shadow-lg shadow-brand/15 h-[42px]">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Tambah Rekening
                </button>
            </div>
        </header>

        <section
            class="bg-gradient-to-r from-cardBg to-accentGray border border-slate-800/80 rounded-2xl p-6 sm:p-8 mb-8 relative overflow-hidden shadow-2xl">
            <div class="absolute -right-10 -top-10 w-40 h-40 bg-brand/10 rounded-full blur-3xl pointer-events-none"></div>
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 relative z-10">
                <div>
                    <span class="text-[10px] uppercase tracking-widest text-[#0052ff] font-bold block mb-1">Total Aset
                        Likuid Gabungan</span>
                    <h2 class="text-3xl sm:text-4xl font-black text-white tracking-tight">
                        Rp {{ number_format($akun->sum('balance') ?? 0, 0, ',', '.') }}
                    </h2>
                </div>
                <p class="text-xs text-slate-400 max-w-sm md:text-right leading-relaxed">
                    Akumulasi total kas representatif dari saldo semua akun bank aktif, aset kripto di ledger, dan simpanan
                    fisik dompet tunai Anda.
                </p>
            </div>
        </section>

        <section class="mb-8">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-sm font-bold uppercase tracking-wider text-slate-400">Daftar Rekening & Dompet Anda</h3>
                <span class="text-[10px] bg-slate-800 text-slate-300 font-bold px-2 py-0.5 rounded-full">
                    {{ $akun->count() ?? 0 }} Akun
                </span>
            </div>

            <div id="rekening_grid" class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                @forelse ($akun ?? [] as $item)
                    <div
                        class="bg-cardBg border border-slate-800 hover:border-brand/40 transition-all rounded-xl p-5 flex flex-col justify-between min-h-[140px] group relative shadow-lg">
                        <div class="flex justify-between items-start">
                            <div class="flex items-center gap-3">
                                @if (($item->type ?? 'cash') === 'bank')
                                    <div
                                        class="w-8 h-8 rounded-lg flex items-center justify-center text-white font-bold text-xs bg-blue-600">
                                        <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                            </path>
                                        </svg>
                                    </div>
                                @elseif(($item->type ?? 'cash') === 'ewallet')
                                    <!-- PERBAIKAN: Gunakan satu @ saja -->
                                    <div
                                        class="w-8 h-8 rounded-lg flex items-center justify-center text-white font-bold text-xs bg-purple-600">
                                        <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a1 1 0 11-2 0 1 1 0 012 0z" />
                                        </svg>
                                    </div>
                                @else
                                    <div
                                        class="w-8 h-8 rounded-lg flex items-center justify-center text-white font-bold text-xs bg-emerald-600">
                                        <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                            </path>
                                        </svg>
                                    </div>
                                @endif
                                <div>
                                    <h4 class="text-xs font-bold text-white leading-tight">{{ $item->name ?? 'N/A' }}</h4>
                                    <span
                                        class="text-[10px] text-slate-400 block mt-0.5 tracking-wider font-mono">{{ $item->account_no ?? 'Tunai' }}</span>
                                </div>
                            </div>
                            <div class="flex items-center gap-1.5">
                                <div
                                    class="flex items-center gap-1 sm:opacity-0 group-hover:opacity-100 transition-opacity">
                                    <button
                                        onclick="muatEdisiRekening('{{ $item->id }}', '{{ $item->name }}', '{{ $item->type }}', '{{ $item->account_no }}', '{{ (int) $item->balance }}')"
                                        class="p-1 text-slate-400 hover:text-yellow-400 hover:bg-slate-800 rounded transition"
                                        title="Sunting">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z">
                                            </path>
                                        </svg>
                                    </button>
                                    <button
                                        onclick="hapusRekeningYakin('{{ route('akun.destroy', $item->id) }}', '{{ $item->name }}')"
                                        class="p-1 text-slate-400 hover:text-red-400 hover:bg-red-800/10 rounded transition"
                                        title="Hapus">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                            </path>
                                        </svg>
                                    </button>
                                </div>
                                <span
                                    class="text-[8px] uppercase font-semibold text-slate-300 px-2 py-0.5 bg-slate-800/80 border border-slate-700/50 rounded shrink-0">
                                    {{ ($item->type ?? 'cash') === 'bank' ? 'BANK' : (($item->type ?? 'cash') === 'ewallet' ? 'E-WALLET' : 'CASH') }}
                                </span>
                            </div>
                        </div>

                        <div class="mt-6 flex justify-between items-end">
                            <div>
                                <span
                                    class="text-[9px] text-[#0052ff] block mb-0.5 font-bold uppercase tracking-wider">Saldo
                                    Terkini</span>
                                <p class="text-lg font-bold text-white tracking-tight">Rp
                                    {{ number_format($item->balance ?? 0, 0, ',', '.') }}</p>
                            </div>
                            <div class="flex items-center gap-1">
                                <span
                                    class="w-1.5 h-1.5 rounded-full {{ ($item->balance ?? 0) > 0 ? 'bg-[#2bb673] animate-pulse' : 'bg-red-400' }}"></span>
                                <span
                                    class="text-[9px] uppercase font-bold text-slate-400">{{ ($item->balance ?? 0) > 0 ? 'Aktif' : 'Kosong' }}</span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div
                        class="col-span-1 sm:col-span-2 flex flex-col items-center justify-center py-12 border border-dashed border-slate-800 rounded-2xl bg-cardBg/30">
                        <svg class="w-8 h-8 text-slate-600 mb-2" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0a2 2 0 01-2 2H6a2 2 0 01-2-2m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5a2 2 0 012-2h12a2 2 0 012 2z">
                            </path>
                        </svg>
                        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Belum ada Account/Wallet
                        </p>
                    </div>
                @endforelse
            </div>
        </section>

        <div id="modal_tambah"
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/70 backdrop-blur-sm hidden">
            <div
                class="w-full max-w-md bg-cardBg rounded-2xl border border-slate-800 shadow-2xl overflow-hidden animate-in fade-in zoom-in-95 duration-150">
                <div class="px-6 py-4 bg-accentGray border-b border-slate-800 flex justify-between items-center">
                    <h3 class="text-sm font-bold text-white uppercase tracking-wider flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-brand"></span>
                        Tambah Akun / Rekening Baru
                    </h3>
                    <button type="button" onclick="tutupModalTambah()"
                        class="text-slate-400 hover:text-white transition cursor-pointer">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <form id="form_tambah" action="{{ route('akun.store') }}" method="POST"
                    class="p-6 flex flex-col gap-4">
                    @csrf
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-semibold text-slate-350" for="add_name">Nama Rekening/Sari Bank</label>
                        <input type="text" id="add_name" name="name"
                            placeholder="Contoh: Bank Central Asia (BCA)"
                            class="w-full bg-black border border-slate-800 rounded-lg px-3 py-2.5 text-xs text-white focus:outline-none focus:ring-1 focus:ring-brand focus:border-brand"
                            required />
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="flex flex-col gap-1.5">
                            <label class="text-xs font-semibold text-slate-350" for="add_type">Jenis Rekening</label>
                            <select id="add_type" name="type"
                                class="w-full bg-black border border-slate-800 rounded-lg px-3 py-2.5 text-xs text-white focus:outline-none focus:ring-1 focus:ring-brand focus:border-brand">
                                <option value="bank">Rekening Bank</option>
                                <option value="ewallet">E-Wallet</option>
                                <option value="cash">Cash</option>
                            </select>
                        </div>
                        <div class="flex flex-col gap-1.5">
                            <label class="text-xs font-semibold text-slate-350" for="add_no">Nomor Rekening /
                        </label>
                            <input type="text" id="add_no" name="account_no" placeholder="Contoh: 8201-xxxx"
                                class="w-full bg-black border border-slate-800 rounded-lg px-3 py-2.5 text-xs text-white focus:outline-none focus:ring-1 focus:ring-brand focus:border-brand" />
                        </div>
                    </div>
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-semibold text-slate-350" for="add_balance">Saldo Awal Terkini</label>
                        <input type="text" id="add_balance" placeholder="Rp 0" oninput="formatIDR(this)"
                            class="w-full bg-black border border-slate-800 rounded-lg px-3 py-2.5 text-xs text-white focus:outline-none focus:ring-1 focus:ring-brand focus:border-brand"
                            required />
                        <input type="hidden" id="add_balance_raw" name="balance" />
                    </div>
                    <div class="flex justify-end gap-2 pt-4 border-t border-slate-800/60">
                        <button type="button" onclick="tutupModalTambah()"
                            class="px-4 py-2 text-xs font-bold text-slate-400 hover:bg-slate-800 rounded-lg cursor-pointer">Batal</button>
                        <button type="submit"
                            class="px-5 py-2 text-xs font-bold bg-brand hover:bg-brand/90 text-white rounded-lg cursor-pointer transition">Simpan
                            Rekening</button>
                    </div>
                </form>
            </div>
        </div>

        <div id="modal_edit"
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/70 backdrop-blur-sm hidden">
            <div
                class="w-full max-w-md bg-cardBg rounded-2xl border border-slate-800 shadow-2xl overflow-hidden animate-in fade-in zoom-in-95 duration-150">
                <div class="px-6 py-4 bg-accentGray border-b border-slate-800 flex justify-between items-center">
                    <h3 class="text-sm font-bold text-white uppercase tracking-wider flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-yellow-500 animate-pulse"></span>
                        Edit Detail Rekening
                    </h3>
                    <button type="button" onclick="tutupModalEdit()"
                        class="text-slate-400 hover:text-white transition cursor-pointer">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <form id="form_edit" method="POST" class="p-6 flex flex-col gap-4">
                    @csrf
                    @method('PUT')
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-semibold text-slate-350" for="edit_name">Nama Rekening/Bank</label>
                        <input type="text" id="edit_name" name="name"
                            class="w-full bg-black border border-slate-800 rounded-lg px-3 py-2.5 text-xs text-white focus:outline-none focus:ring-1 focus:ring-brand focus:border-brand"
                            required />
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="flex flex-col gap-1.5">
                            <label class="text-xs font-semibold text-slate-350" for="edit_type">Kategori Aset</label>
                            <select id="edit_type" name="type"
                                class="w-full bg-black border border-slate-800 rounded-lg px-3 py-2.5 text-xs text-white focus:outline-none focus:ring-1 focus:ring-brand focus:border-brand">
                                <option value="bank">Rekening Bank</option>
                                <option value="ewallet">E-Wallet</option>
                                <option value="cash">Cash</option>
                            </select>
                        </div>
                        <div class="flex flex-col gap-1.5">
                            <label class="text-xs font-semibold text-slate-350" for="edit_no">Nomor Akun / Alamat</label>
                            <input type="text" id="edit_no" name="account_no"
                                class="w-full bg-black border border-slate-800 rounded-lg px-3 py-2.5 text-xs text-white focus:outline-none focus:ring-1 focus:ring-brand focus:border-brand" />
                        </div>
                    </div>
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-semibold text-slate-350" for="edit_balance">Nominal Saldo
                            Terkini</label>
                        <input type="text" id="edit_balance" placeholder="Rp 0" oninput="formatIDR(this)"
                            class="w-full bg-black border border-slate-800 rounded-lg px-3 py-2.5 text-xs text-white focus:outline-none focus:ring-1 focus:ring-brand focus:border-brand"
                            required />
                        <input type="hidden" id="edit_balance_raw" name="balance" />
                    </div>
                    <div class="flex justify-end gap-2 pt-4 border-t border-slate-800/60">
                        <button type="button" onclick="tutupModalEdit()"
                            class="px-4 py-2 text-xs font-bold text-slate-400 hover:bg-slate-800 rounded-lg cursor-pointer">Batal</button>
                        <button type="submit"
                            class="px-5 py-2 text-xs font-bold bg-yellow-500 hover:bg-yellow-600 text-black rounded-lg cursor-pointer transition">Simpan
                            Perubahan</button>
                    </div>
                </form>
            </div>
        </div>

        <div id="modal_transfer"
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/70 backdrop-blur-sm hidden">
            <div
                class="w-full max-w-md bg-cardBg rounded-2xl border border-slate-800 shadow-2xl overflow-hidden animate-in fade-in zoom-in-95 duration-150">
                <div class="px-6 py-4 bg-accentGray border-b border-slate-800 flex justify-between items-center">
                    <h3 class="text-sm font-bold text-white uppercase tracking-wider flex items-center gap-2">
                        <svg class="w-4 h-4 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                        </svg>
                        Transfer Antar Dompet / Rekening
                    </h3>
                    <button type="button" onclick="tutupModalTransfer()"
                        class="text-slate-400 hover:text-white transition cursor-pointer">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <form id="form_transfer" action="{{ route('akun.transfer') }}" method="POST"
                    class="p-6 flex flex-col gap-4">
                    @csrf
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-semibold text-slate-350" for="transfer_from">Pindahkan Dana
                            Dari</label>
                        <select id="transfer_from" name="from_account_id"
                            class="w-full bg-black border border-slate-800 rounded-lg px-3 py-2.5 text-xs text-white focus:outline-none focus:ring-1 focus:ring-brand focus:border-brand"
                            required>
                            <option value="" class="text-slate-400">-- Pilih Rekening Pengirim --</option>
                            @foreach ($akun ?? [] as $item)
                                <option value="{{ $item->id }}">
                                    {{ $item->name }} (Rp {{ number_format($item->balance ?? 0, 0, ',', '.') }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-semibold text-slate-350" for="transfer_to">Ke Rekening Tujuan</label>
                        <select id="transfer_to" name="to_account_id"
                            class="w-full bg-black border border-slate-800 rounded-lg px-3 py-2.5 text-xs text-white focus:outline-none focus:ring-1 focus:ring-brand focus:border-brand"
                            required>
                            <option value="" class="text-slate-400">-- Pilih Rekening Penerima --</option>
                            @foreach ($akun ?? [] as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-semibold text-slate-350" for="transfer_amount">Nominal Transfer</label>
                        <input type="text" id="transfer_amount" placeholder="Rp 0" oninput="formatIDR(this)"
                            class="w-full bg-black border border-slate-800 rounded-lg px-3 py-2.5 text-xs text-white focus:outline-none focus:ring-1 focus:ring-brand focus:border-brand"
                            required />
                        <input type="hidden" id="transfer_amount_raw" name="amount" />
                    </div>
                    <div class="flex justify-end gap-2 pt-4 border-t border-slate-800/60">
                        <button type="button" onclick="tutupModalTransfer()"
                            class="px-4 py-2 text-xs font-bold text-slate-400 hover:bg-slate-800 rounded-lg cursor-pointer">Batal</button>
                        <button type="submit"
                            class="px-5 py-2 text-xs font-bold bg-brand hover:bg-brand/95 text-white rounded-lg cursor-pointer transition">Eksekusi
                            Transfer</button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <script src="{{ asset('js/formatIDR.js') }}"></script>
    <script>
        document.getElementById('form_tambah').addEventListener('submit', function() {
            document.getElementById('add_balance_raw').value = getRawValue(document.getElementById('add_balance')
                .value);
        });

        document.getElementById('form_edit').addEventListener('submit', function() {
            document.getElementById('edit_balance_raw').value = getRawValue(document.getElementById('edit_balance')
                .value);
        });

        document.getElementById('form_transfer').addEventListener('submit', function() {
            document.getElementById('transfer_amount_raw').value = getRawValue(document.getElementById(
                'transfer_amount').value);
        });

        function bukaModalTambah() {
            document.getElementById('modal_tambah').classList.remove('hidden');
        }

        function tutupModalTambah() {
            document.getElementById('modal_tambah').classList.add('hidden');
            document.getElementById('form_tambah').reset();
        }

        function bukaModalTransfer() {
            document.getElementById('modal_transfer').classList.remove('hidden');
        }

        function tutupModalTransfer() {
            document.getElementById('modal_transfer').classList.add('hidden');
            document.getElementById('form_transfer').reset();
        }

        function tutupModalEdit() {
            document.getElementById('modal_edit').classList.add('hidden');
            document.getElementById('form_edit').reset();
        }

        function muatEdisiRekening(id, name, type, accountNo, balance) {
            const formEdit = document.getElementById('form_edit');
            formEdit.action = `/akun/${id}`;

            document.getElementById('edit_name').value = name;
            document.getElementById('edit_type').value = type;
            document.getElementById('edit_no').value = accountNo;

            const balanceInput = document.getElementById('edit_balance');
            balanceInput.value = balance;
            formatIDR(balanceInput);

            document.getElementById('modal_edit').classList.remove('hidden');
        }

        function hapusRekeningYakin(actionUrl, nama) {
            Swal.fire({
                title: 'Konfirmasi Penghapusan',
                text: `Apakah Anda yakin ingin menghapus rekening "${nama}"? Aksi ini tidak dapat dikembalikan.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#334155',
                confirmButtonText: 'Ya, Hapus Akun',
                cancelButtonText: 'Batal',
                background: '#13141b',
                color: '#f1f5f9'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.createElement('form');
                    form.action = actionUrl;
                    form.method = 'POST';
                    form.innerHTML = `
            @csrf
            @method('DELETE')
          `;
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }
    </script>
@endsection
