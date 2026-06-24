@extends('layouts.app')

@section('content')
    <main class="flex-1 md:ml-[240px] w-full min-h-screen p-4 sm:p-6 lg:p-8 bg-grid-pattern box-border overflow-x-hidden flex flex-col">

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

            <button onclick="bukaModalTambah()"
                class="w-full sm:w-auto bg-[#0052ff] hover:bg-[#0047db] text-white transition-all duration-250 px-5 py-2.5 rounded-xl font-bold text-xs flex items-center justify-center gap-2 cursor-pointer shadow-lg shadow-blue-600/15 h-[42px] hover:-translate-y-0.5 active:translate-y-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path>
                </svg>
                Tambah Rekening
            </button>
        </header>

        <section class="mb-8">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400">Daftar Rekening & Dompet Anda</h3>
                <span class="text-[10px] bg-slate-800 text-slate-300 font-mono font-bold px-2.5 py-0.5 rounded-full border border-slate-700/50">
                    {{ $akun->count() ?? 0 }} Akun
                </span>
            </div>

<div id="rekening_grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 gap-4">
    @forelse ($akun ?? [] as $item)
        {{-- Pengecekan gabungan antara type_valid dan name agar deteksi warna selalu akurat --}}
        @php
            $providerKey = strtolower(($item->type_valid ?? '') . ' ' . ($item->name ?? ''));
            
            if (str_contains($providerKey, 'shopee')) {
                $bgClass = 'bg-[#ee4d2d] border-[#ff6647] text-white';
                $textNoRek = 'text-white';
                $btnClass = 'text-white/70 hover:text-red-200 hover:bg-white/10';
            } elseif (str_contains($providerKey, 'gopay')) {
                $bgClass = 'bg-[#00aed6] border-[#22c3e6] text-white';
                $textNoRek = 'text-white';
                $btnClass = 'text-white/70 hover:text-red-200 hover:bg-white/10';
            } elseif (str_contains($providerKey, 'bca')) {
                $bgClass = 'bg-[#002060] border-[#003399] text-white';
                $textNoRek = 'text-white';
                $btnClass = 'text-white/70 hover:text-red-200 hover:bg-white/10';
            } elseif (str_contains($providerKey, 'mandiri')) {
                $bgClass = 'bg-gradient-to-br from-[#fdb813] to-white border-[#fdb813] text-slate-900';
                $textNoRek = 'text-slate-900';
                $btnClass = 'text-slate-700 hover:text-red-600 hover:bg-red-500/10';
            } else {
                $bgClass = 'bg-cardBg border-slate-800/80 text-white';
                $textNoRek = 'text-white';
                $btnClass = 'text-white/70 hover:text-red-200 hover:bg-white/10';
            }
        @endphp

        <div class="transition-all duration-300 rounded-xl p-5 flex flex-col justify-between min-h-[120px] group relative border overflow-hidden shadow-lg {{ $bgClass }}">
            
            <div class="absolute -right-6 -bottom-6 w-24 h-24 rounded-full opacity-10 bg-white pointer-events-none"></div>

            <div class="flex justify-between items-start gap-3 relative z-10">
                <div class="flex items-start gap-3.5 w-full overflow-hidden">
                    @if (($item->type ?? 'cash') === 'bank')
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center font-bold shrink-0 shadow-md bg-white/15 text-current">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                </path>
                            </svg>
                        </div>
                    @elseif(($item->type ?? 'cash') === 'ewallet')
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center font-bold shrink-0 shadow-md bg-white/15 text-current">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a1 1 0 11-2 0 1 1 0 012 0z" />
                            </svg>
                        </div>
                    @else
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center font-bold shrink-0 shadow-md bg-white/15 text-current">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                </path>
                            </svg>
                        </div>
                    @endif
                    
                    <div class="overflow-hidden flex-1">
                        <h4 class="text-xs font-bold uppercase opacity-85 tracking-wider truncate">{{ $item->name ?? 'N/A' }}</h4>
                        <span class="text-lg font-extrabold block mt-1 tracking-wide font-mono truncate {{ $textNoRek }}">
                            {{ $item->account_no ?? 'Tunai' }}
                        </span>
                    </div>
                </div>

                <div class="flex items-center gap-1.5 shrink-0">
                    <div class="flex items-center gap-1 opacity-100 sm:opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                        <button
                            onclick="hapusRekeningYakin('{{ route('akun.destroy', $item->id) }}', '{{ $item->name }}')"
                            class="p-1.5 rounded-lg transition-colors cursor-pointer {{ $btnClass }}"
                            title="Hapus">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                </path>
                            </svg>
                        </button>
                    </div>
                    <span class="text-[9px] uppercase font-extrabold px-2 py-0.5 rounded tracking-wider bg-white/20 text-current border border-white/10">
                        {{ $item->type_valid ?? (($item->type ?? 'cash') === 'bank' ? 'BANK' : 'CASH') }}
                    </span>
                </div>
            </div>
            
        </div>
    @empty
        <div class="col-span-1 sm:col-span-2 flex flex-col items-center justify-center py-12 border border-dashed border-slate-800 rounded-2xl bg-cardBg/30">
            <svg class="w-8 h-8 text-slate-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0a2 2 0 01-2 2H6a2 2 0 01-2-2m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5a2 2 0 012-2h12a2 2 0 012 2z">
                </path>
            </svg>
            <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Belum ada Account/Wallet</p>
        </div>
    @endforelse
</div>
        </section>

        <div id="modal_tambah" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/70 backdrop-blur-sm hidden">
            <div class="w-full max-w-md bg-cardBg rounded-2xl border border-slate-800 shadow-2xl overflow-hidden animate-in fade-in zoom-in-95 duration-150">
                <div class="px-6 py-4 bg-accentGray border-b border-slate-800 flex justify-between items-center">
                    <h3 class="text-sm font-bold text-white uppercase tracking-wider flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-brand"></span>
                        Tambah Akun / Rekening Baru
                    </h3>
                    <button type="button" onclick="tutupModalTambah()" class="text-slate-400 hover:text-white transition cursor-pointer">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

               <form id="form_tambah" action="{{ route('akun.store') }}" method="POST" class="p-6 flex flex-col gap-4">
    @csrf

    <div class="flex flex-col gap-1.5">
        <label class="text-xs font-semibold text-slate-350" for="add_name">Nama Pemilik Rekening</label>
        <input type="text" id="add_name" name="name" placeholder="Contoh: Budi Santoso"
            class="w-full bg-black border border-slate-800 rounded-lg px-3 py-2.5 text-xs text-white focus:outline-none focus:ring-1 focus:ring-brand focus:border-brand"
            required />
    </div>

    <div class="flex flex-col gap-1.5">
        <label class="text-xs font-semibold text-slate-350" for="add_type">Jenis Rekening</label>
        <select id="add_type" name="type" onchange="updateProviderOptions()"
            class="w-full bg-black border border-slate-800 rounded-lg px-3 py-2.5 text-xs text-white focus:outline-none focus:ring-1 focus:ring-brand focus:border-brand" required>
            <option value="" disabled selected>-- Pilih Jenis --</option>
            <option value="ewallet">E-Wallet</option>
            <option value="bank">Bank</option>
        </select>
    </div>

    <div class="flex flex-col gap-1.5" id="wrap_provider">
        <label class="text-xs font-semibold text-slate-350" for="add_type_valid">Provider / Bank</label>
        <select id="add_type_valid" name="type_valid"
            class="w-full bg-black border border-slate-800 rounded-lg px-3 py-2.5 text-xs text-white focus:outline-none focus:ring-1 focus:ring-brand focus:border-brand disabled:opacity-40 disabled:cursor-not-allowed"
            disabled required>
            <option value="">-- Pilih jenis rekening dulu --</option>
        </select>
    </div>

    <div class="flex flex-col gap-1.5">
        <label class="text-xs font-semibold text-slate-350" for="add_no">Nomor Rekening / No. HP</label>
        <input type="text" id="add_no" name="account_no" placeholder="Contoh: 081234567890"
            class="w-full bg-black border border-slate-800 rounded-lg px-3 py-2.5 text-xs text-white focus:outline-none focus:ring-1 focus:ring-brand focus:border-brand" />
    </div>

    <div class="flex justify-end gap-2 pt-4 border-t border-slate-800/60">
        <button type="button" onclick="tutupModalTambah()"
            class="px-4 py-2 text-xs font-bold text-slate-400 hover:bg-slate-800 rounded-lg cursor-pointer">Batal</button>
        <button type="submit"
            class="px-5 py-2 text-xs font-bold bg-brand hover:bg-brand/90 text-white rounded-lg cursor-pointer transition">Simpan Rekening</button>
    </div>
</form>
            </div>
        </div>
        
    </main>

    <script src="{{ asset('js/formatIDR.js') }}"></script>
    <script>
        const PROVIDER_OPTIONS = {
            ewallet: [
                'GoPay', 'OVO', 'DANA', 'ShopeePay', 'LinkAja',
                'Jenius', 'DOKU Wallet', 'iSaku', 'Sakuku (BCA)',
                'PayLater Tokopedia', 'Blu by BCA Digital', 'Netzme',
            ],
            bank: [
                'BCA (Bank Central Asia)', 'Mandiri', 'BRI (Bank Rakyat Indonesia)',
                'BNI (Bank Negara Indonesia)', 'BTN (Bank Tabungan Negara)',
                'CIMB Niaga', 'Danamon', 'Permata Bank', 'Panin Bank',
                'Maybank Indonesia', 'OCBC NISP', 'Blu by BCA Digital',
                'Jago (Bank Jago)', 'SeaBank', 'Allo Bank', 'Bank Neo Commerce (BNC)',
                'Bank Muamalat', 'Bank Syariah Indonesia (BSI)',
            ],
        };

        function updateProviderOptions() {
            const type     = document.getElementById('add_type').value;
            const select   = document.getElementById('add_type_valid');
            const options  = PROVIDER_OPTIONS[type] ?? [];

            select.innerHTML = '<option value="">-- Pilih Provider --</option>';
            options.forEach(p => {
                const opt = document.createElement('option');
                opt.value = p;
                opt.textContent = p;
                select.appendChild(opt);
            });

            select.disabled = options.length === 0;
            select.required = options.length > 0;
        }

        function bukaModalTambah() {
            document.getElementById('modal_tambah').classList.remove('hidden');
        }

        function tutupModalTambah() {
            document.getElementById('modal_tambah').classList.add('hidden');
            document.getElementById('form_tambah').reset();
            const select = document.getElementById('add_type_valid');
            select.innerHTML = '<option value="">-- Pilih jenis rekening dulu --</option>';
            select.disabled = true;
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

        function muatEdisiRekening(id, name, type, accountNo) {
            const formEdit = document.getElementById('form_edit');
            formEdit.action = `/akun/${id}`;

            document.getElementById('edit_name').value = name;
            document.getElementById('edit_type').value = type;
            document.getElementById('edit_no').value = accountNo;

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