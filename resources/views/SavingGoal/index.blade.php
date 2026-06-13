@extends('layouts.app')

@section('content')
    <main class="flex-1 md:ml-[240px] p-margin-mobile md:p-margin-desktop bg-grid-pattern min-h-screen flex flex-col gap-6">

        <!-- Header Page -->
        <header class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-white/5 pb-6">
            <div class="space-y-1">
                <div class="flex items-center gap-2">
                    <span class="p-2 rounded-lg bg-blue-500/10 text-blue-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </span>
                    <h1 class="text-2xl font-extrabold tracking-tight text-white">Target Impian & Tabungan</h1>
                </div>
                <p class="text-xs text-slate-400">Kelola impian masa depan, simpan modal terpisah, dan pantau kemajuan
                    target keuangan Anda.</p>
            </div>

            <!-- Ringkasan Singkat Stat -->
            <div class="flex gap-4">
    <div class="bg-[#0A0B0D] border border-[#1E2025] rounded-xl p-3 px-5 flex flex-col justify-center">
        <span class="text-[10px] text-slate-400 font-semibold uppercase tracking-wider">Total Terkumpul</span>
        <span class="text-base font-bold text-emerald-400 font-mono">
            Rp {{ number_format($savingGoals->sum('current_amount'), 0, ',', '.') }}
        </span>
    </div>
</div>
        </header>

        <!-- Grid Utama: Create Form & Goals List -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">

            <!-- 1. #CREATE - FORM TAMBAH GOAL (Kiri) -->
            <section class="bg-[#0A0B0D] border border-[#1E2025] rounded-2xl p-6 space-y-5">
                <div class="border-b border-white/5 pb-3">
                    <h2 class="text-sm font-bold text-white flex items-center gap-2">
                        <span class="inline-block w-2 h-2 rounded-full bg-blue-500"></span>
                        Tambah Rencana Impian Baru
                    </h2>
                    <p class="text-[11px] text-slate-400">Atur anggaran impian Anda secara konsisten</p>
                </div>

                <form id="create_goal_form" class="space-y-4" onsubmit="event.preventDefault(); handleCreateGoal();">

                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-semibold text-slate-300" for="goal_name">Nama Impian *</label>
                        <input id="goal_name" type="text" placeholder="Contoh: Beli Macbook Pro M4" required
                            class="w-full bg-[#121414] border border-[#2d2f39] text-white rounded-lg p-2.5 text-xs focus:ring-1 focus:ring-blue-500 focus:border-blue-500 outline-none">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
    <div class="flex flex-col gap-1.5">
        <label class="text-xs font-semibold text-slate-300" for="goal_target">Nominal Target (Rp) *</label>
        <input 
            id="goal_target" 
            type="text" 
            placeholder="cth: Rp 24.000.000" 
            required 
            oninput="formatIDR(this)"
            class="w-full bg-[#121414] border border-[#2d2f39] text-white rounded-lg p-2.5 text-xs focus:ring-1 focus:ring-blue-500 outline-none"
        >
    </div>
    <div class="flex flex-col gap-1.5">
        <label class="text-xs font-semibold text-slate-300" for="goal_initial">Dana Awal (Rp)</label>
        <input 
            id="goal_initial" 
            type="text" 
            placeholder="Mulai dari Rp 0" 
            oninput="formatIDR(this)"
            class="w-full bg-[#121414] border border-[#2d2f39] text-white rounded-lg p-2.5 text-xs focus:ring-1 focus:ring-blue-500 outline-none"
        >
    </div>
</div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="flex flex-col gap-1.5">
                            <label class="text-xs font-semibold text-slate-300" for="goal_account">Pilih Rekening *</label>
                            <select id="goal_account" required
                                class="w-full bg-[#121414] border border-[#2d2f39] text-white rounded-lg p-2.5 text-xs focus:ring-1 focus:ring-blue-500 outline-none cursor-pointer">
                                <option value="" disabled selected>Pilih Rekening</option>
                                @foreach ($accounts as $account)
                                    <option value="{{ $account->id }}">{{ $account->name }} (Rp
                                        {{ number_format($account->balance, 0, ',', '.') }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex flex-col gap-1.5">
                            <label class="text-xs font-semibold text-slate-300" for="goal_category">Kategori *</label>
                            <select id="goal_category" required
                                class="w-full bg-[#121414] border border-[#2d2f39] text-white rounded-lg p-2.5 text-xs focus:ring-1 focus:ring-blue-500 outline-none cursor-pointer">
                                <option value="" disabled selected>Pilih Kategori</option>
                                <option value="Dana Darurat">Dana Darurat</option>
                                <option value="Liburan">Liburan / Travel</option>
                                <option value="Gawai">Gawai / Elektronik</option>
                                <option value="Kendaraan">Kendaraan</option>
                                <option value="Properti">Properti / Rumah</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-semibold text-slate-300" for="goal_date">Tenggat Waktu *</label>
                        <input id="goal_date" type="date" required
                            class="w-full bg-[#121414] border border-[#2d2f39] text-white rounded-lg p-2.5 text-xs focus:ring-1 focus:ring-blue-500 outline-none cursor-pointer">
                    </div>

                    <button type="submit"
                        class="w-full py-3 bg-blue-600 hover:bg-blue-500 active:scale-95 text-white font-extrabold text-xs rounded-xl transition-all shadow-lg shadow-blue-600/10 tracking-wide uppercase mt-4 cursor-pointer">
                        Tambahkan Komitmen Impian
                    </button>
                </form>
            </section>

            <!-- 2. #UPDATE & LIST VIEW - DAFTAR TARGET IMPIAN (Kanan) -->
            <section class="lg:col-span-2 space-y-4">
    <div class="flex items-center justify-between">
        <h2 class="text-sm font-extrabold text-white flex items-center gap-2">
            Progress Pengumpulan Tabungan
        </h2>
        <span class="text-xs bg-slate-800 border border-[#1E2025] px-2.5 py-1 rounded-full text-slate-300" id="goal_count">
            {{ $savingGoals->count() }} Target Aktif
        </span>
    </div>

    <!-- Empty State Alert (Jika Kosong) -->
    @if($savingGoals->isEmpty())
    <div id="empty_state" class="p-8 border border-dashed border-[#1E2025] rounded-2xl flex flex-col items-center justify-center text-center text-slate-500 gap-2">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
        </svg>
        <div class="text-xs font-semibold">Tidak ada target impian saat ini</div>
        <p class="text-[10px] text-slate-500 max-w-xs">Komitmen tabungan Anda akan tampil di halaman ini.</p>
    </div>
    @else

    <!-- Grid Cards Target Impian -->
    <div id="goals_grid" class="grid grid-cols-1 md:grid-cols-2 gap-4">
        @foreach($savingGoals as $goal)
            @php
                // Logika Perhitungan Progress & Sisa Kekurangan
                $isTargetReached = $goal->current_amount >= $goal->target_amount;
                $percent = $isTargetReached ? 100 : ($goal->target_amount > 0 ? ($goal->current_amount / $goal->target_amount) * 100 : 0);
                $kekurangan = $isTargetReached ? 0 : ($goal->target_amount - $goal->current_amount);
                
                // Judul dan Warna Progress Bar Dinamis
                $barColor = $isTargetReached ? 'bg-emerald-500' : 'bg-blue-600';
                $percentTextColor = $isTargetReached ? 'text-emerald-400' : 'text-blue-400';
            @endphp

            <div class="bg-[#0A0B0D] border border-[#1E2025] rounded-2xl p-5 hover:border-slate-700/50 transition-all flex flex-col justify-between relative overflow-hidden group">
                <div class="space-y-4">
                    <!-- Top Row Info -->
                    <div class="flex justify-between items-start gap-2">
                        <div class="flex items-center gap-2.5">
                            <!-- Icon Wrapper Dinamis Berdasarkan Kategori via JS Element Injection / Data Attribute -->
                            <div class="p-2 w-10 h-10 rounded-xl flex items-center justify-center category-icon" data-category="{{ $goal->category }}">
                                <!-- Diisi SVG secara otomatis oleh fungsi JavaScript di bawah -->
                            </div>
                            <div>
                                <h3 class="text-xs font-bold text-white group-hover:text-blue-400 transition-colors">
                                    {{ $goal->name }}
                                </h3>
                                <span class="text-[9px] bg-[#121414] border border-[#1E2025] px-2 py-0.5 rounded-full text-slate-400">
                                    {{ $goal->category }}
                                </span>
                            </div>
                        </div>

                        <!-- Action #delete -->
                        <button onclick="handleDeleteGoal({{ $goal->id }})" class="p-1.5 rounded-lg text-slate-400 hover:bg-red-500/10 hover:text-red-400 transition-all cursor-pointer">
                            <svg xmlns="http://www.w3.org/2000/xl" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </div>

                    <!-- Angka Finansial -->
                    <div class="grid grid-cols-2 gap-2 border-t border-b border-white/5 py-3">
                        <div>
                            <span class="text-[9px] text-slate-500 uppercase font-semibold">Terkumpul</span>
                            <p class="text-xs font-bold text-emerald-400 font-mono">Rp {{ number_format($goal->current_amount, 0, ',', '.') }}</p>
                        </div>
                        <div class="text-right">
                            <span class="text-[9px] text-slate-500 uppercase font-semibold">Plafon Target</span>
                            <p class="text-xs font-bold text-white font-mono">Rp {{ number_format($goal->target_amount, 0, ',', '.') }}</p>
                        </div>
                    </div>

                    <!-- Progress Bar -->
                    <div class="space-y-1.5">
                        <div class="flex justify-between items-center text-[10px]">
                            <span class="text-slate-400 font-medium">
                                @if($isTargetReached)
                                    <b class="text-emerald-400">🎉 Target Terpenuhi / Lunas</b>
                                @else
                                    Kekurangan: <b class="text-amber-500">Rp {{ number_format($kekurangan, 0, ',', '.') }}</b>
                                @endif
                            </span>
                            <span class="font-extrabold {{ $percentTextColor }}">{{ number_format($percent, 1) }}%</span>
                        </div>
                        <div class="w-full bg-[#121414] h-2 rounded-full overflow-hidden">
                            <div class="{{ $barColor }} h-2 rounded-full transition-all duration-500" style="width: {{ $percent }}%"></div>
                        </div>
                    </div>
                </div>

                <!-- Bagian Action Setor & Tarik Dana -->
                <div class="mt-4 pt-4 border-t border-white/5 grid grid-cols-2 gap-2">
                    <button onclick="promptSetorDana({{ $goal->id }}, {{ $kekurangan }}, '{{ $goal->name }}')"
                        class="py-1.5 bg-emerald-500/10 hover:bg-emerald-500/20 text-emerald-400 font-bold text-[11px] rounded-lg border border-emerald-500/20 text-center flex items-center justify-center gap-1.5 cursor-pointer hover:scale-[1.02] active:scale-95 transition-all">
                        Setor Dana
                    </button>
                    <button onclick="promptTarikDana({{ $goal->id }}, {{ $goal->current_amount }}, '{{ $goal->name }}')"
                        class="py-1.5 bg-rose-500/10 hover:bg-rose-500/20 text-rose-400 font-bold text-[11px] rounded-lg border border-rose-500/20 text-center flex items-center justify-center gap-1.5 cursor-pointer hover:scale-[1.02] active:scale-95 transition-all">
                        Tarik Simpanan
                    </button>
                </div>
            </div>
        @endforeach
    </div>
    @endif
</section>

        </div>

    </main>
    <!-- ========================================================================= -->
    <!-- AKHIR DARI BAGIAN UTAMA #MAIN                                              -->
    <!-- ========================================================================= -->


    <!-- ========================================================================= -->
    <!-- #SWEETALERT & LOGIC SCRIPTS (Diletakkan di bawah dokumen HTML)             -->
    <!-- ========================================================================= -->
   <script src="{{ asset('js/formatIDR.js') }}"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    const iconsMap = {
        'Gawai': {
            class: 'bg-orange-500/10 text-orange-400',
            svg: `<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>`
        },
        'Liburan': {
            class: 'bg-blue-500/10 text-blue-400',
            svg: `<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" /></svg>`
        },
        'Dana Darurat': {
            class: 'bg-red-500/10 text-red-400',
            svg: `<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>`
        },
        'Kendaraan': {
            class: 'bg-purple-500/10 text-purple-400',
            svg: `<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" /></svg>`
        },
        'Properti': {
            class: 'bg-emerald-500/10 text-emerald-400',
            svg: `<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>`
        }
    };

    document.querySelectorAll('.category-icon').forEach(el => {
        const cat = el.getAttribute('data-category');
        const config = iconsMap[cat] || iconsMap['Dana Darurat']; 
        el.className += ` ${config.class}`;
        el.innerHTML = config.svg;
    });
});

const activeWallets = @json($accounts);
const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

// #CREATE: Penambahan Target Komitmen Baru
function handleCreateGoal() {
    const name = document.getElementById('goal_name').value;
    const target = parseFloat(getRawValue(document.getElementById('goal_target').value)) || 0;
    const initial = parseFloat(getRawValue(document.getElementById('goal_initial').value)) || 0;
    const date = document.getElementById('goal_date').value;
    const category = document.getElementById('goal_category').value;
    const accountId = document.getElementById('goal_account').value;

    // Error Handling Validasi Input
    if (target <= 0) {
        Swal.fire({
            title: 'Error!',
            text: 'Nominal target tidak boleh kosong atau 0.',
            icon: 'error',
            background: '#1a1d1d',
            color: '#e2e2e2',
            confirmButtonColor: '#0052ff'
        });
        return;
    }

    if (initial > target) {
        Swal.fire({
            title: 'Error!',
            text: 'Dana awal tidak boleh melebihi target utama tabungan.',
            icon: 'error',
            background: '#1a1d1d',
            color: '#e2e2e2',
            confirmButtonColor: '#0052ff'
        });
        return;
    }

    // Ambil saldo dari rekening yang dipilih untuk validasi dana awal
    const selectedWallet = activeWallets.find(w => w.id == accountId);
    if (selectedWallet && initial > selectedWallet.balance) {
        Swal.fire({
            title: 'Error!',
            text: 'Dana awal tidak boleh melebihi saldo rekening saat ini.',
            icon: 'error',
            background: '#1a1d1d',
            color: '#e2e2e2',
            confirmButtonColor: '#0052ff'
        });
        return;
    }

    const form = document.createElement('form');
    form.method = 'POST';
    form.action = "{{ route('saving-goals.store') }}";

    const inputs = {
        _token: csrfToken,
        name: name,
        target_amount: target,
        current_amount: initial,
        deadline: date,
        category: category,
        account_id: accountId
    };

    for (const key in inputs) {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = key;
        input.value = inputs[key];
        form.appendChild(input);
    }

    document.body.appendChild(form);
    form.submit();
}

// #DELETE: Penghapusan Target Impian dengan Pengembalian Saldo
function handleDeleteGoal(goalId) {
    Swal.fire({
        title: 'Hapus Rencana Impian?',
        text: 'Apakah yakin ingin dihapus? Jika dihapus maka saldo dikembalikan seluruhnya ke rekening asal.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#374151',
        confirmButtonText: 'Ya, Hapus & Kembalikan Saldo',
        cancelButtonText: 'Batal',
        background: '#1a1d1d',
        color: '#e2e2e2',
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`/saving-goals/${goalId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            title: 'Terhapus!',
                            text: data.message,
                            icon: 'success',
                            background: '#1a1d1d',
                            color: '#e2e2e2',
                            confirmButtonColor: '#0052ff'
                        }).then(() => window.location.reload());
                    }
                });
        }
    });
}

// #UPDATE: SETOR DANA DARI REKENING 
function promptSetorDana(goalId, minNeeded, goalName) {
    const walletOptions = activeWallets.map(w =>
        `<option value="${w.id}" style="background-color: #121414; color: #e2e2e2;">${w.name} (Saldo: Rp ${w.balance.toLocaleString('id-ID')})</option>`
    ).join('');

    const htmlContent = `<div style="text-align: left; font-family: sans-serif; display: flex; flex-direction: column; gap: 12px; margin-top: 10px;">
        <p style="font-size: 12px; color: #94a3b8; margin-bottom: 4px;">Sisa kekurangan s/d lunas: <b>Rp ${minNeeded.toLocaleString('id-ID')}</b></p>
        <div style="display: flex; flex-direction: column; gap: 4px;">
            <label style="font-size: 11px; font-weight: bold; color: #c3c5d9;">Sumber Rekening Pengirim</label>
            <select id="swal_wallet_id" style="width: 100%; background-color: #121414; color: #e2e2e2; border: 1px solid rgba(67, 70, 86, 0.4); border-radius: 8px; padding: 10px 12px; font-size: 13px; outline: none; cursor: pointer;">
                ${walletOptions}
            </select>
        </div>
        <div style="display: flex; flex-direction: column; gap: 4px;">
            <label style="font-size: 11px; font-weight: bold; color: #c3c5d9;">Nominal Setor (Rp)</label>
            <input 
                type="text" 
                id="swal_amount" 
                placeholder="Contoh: Rp 1.000.000" 
                oninput="formatIDR(this)"
                style="width: 100%; background-color: #121414; color: #e2e2e2; border: 1px solid rgba(67, 70, 86, 0.4); border-radius: 8px; padding: 10px 12px; font-size: 13px; outline: none;"
            >
        </div>
    </div>`;

    Swal.fire({
        title: 'Tambah Dana Tabungan',
        html: htmlContent,
        showCancelButton: true,
        confirmButtonText: 'Setor Tabungan',
        cancelButtonText: 'Batal',
        confirmButtonColor: '#10b981',
        cancelButtonColor: '#374151',
        background: '#1a1d1d',
        color: '#e2e2e2',
        focusConfirm: false,
        preConfirm: () => {
            const walletId = document.getElementById('swal_wallet_id').value;
            const amount = parseFloat(getRawValue(document.getElementById('swal_amount').value));
            
            if (!walletId) return Swal.showValidationMessage('Harap pilih rekening');
            if (!amount || amount <= 0) return Swal.showValidationMessage('Masukkan nominal yang valid');
            return { walletId, amount };
        }
    }).then((res) => {
        if (res.isConfirmed && res.value) {
            executeTransaction(goalId, 'setor', res.value.walletId, res.value.amount);
        }
    });
}

// #UPDATE: TARIK DANA KE REKENING
function promptTarikDana(goalId, currentAmount, goalName) {
    const walletOptions = activeWallets.map(w =>
        `<option value="${w.id}" style="background-color: #121414; color: #e2e2e2;">${w.name}</option>`
    ).join('');

    const htmlContent = `
    <div style="text-align: left; font-family: sans-serif; display: flex; flex-direction: column; gap: 12px; margin-top: 10px;">
      <p style="font-size: 12px; color: #94a3b8; margin-bottom: 4px;">Plafond Maksimum Rilis: <b>Rp ${currentAmount.toLocaleString('id-ID')}</b></p>
      <div style="display: flex; flex-direction: column; gap: 4px;">
        <label style="font-size: 11px; font-weight: bold; color: #c3c5d9;">Tujuan Rekening Penerima</label>
        <select id="swal_wallet_id" style="width: 100%; background-color: #121414; color: #e2e2e2; border: 1px solid rgba(67, 70, 86, 0.4); border-radius: 8px; padding: 10px 12px; font-size: 13px; outline: none; cursor: pointer;">
          ${walletOptions}
        </select>
      </div>
      <div style="display: flex; flex-direction: column; gap: 4px;">
        <label style="font-size: 11px; font-weight: bold; color: #c3c5d9;">Nominal Tarik (Rp)</label>
        <input 
            type="text" 
            id="swal_amount" 
            placeholder="Contoh: Rp 500.000" 
            oninput="formatIDR(this)"
            style="width: 100%; background-color: #121414; color: #e2e2e2; border: 1px solid rgba(67, 70, 86, 0.4); border-radius: 8px; padding: 10px 12px; font-size: 13px; outline: none;"
        >
      </div>`;

    Swal.fire({
        title: 'Tarik Dana Rencana',
        html: htmlContent,
        showCancelButton: true,
        confirmButtonText: 'Tarik Dana',
        cancelButtonText: 'Batal',
        confirmButtonColor: '#f43f5e',
        cancelButtonColor: '#374151',
        background: '#1a1d1d',
        color: '#e2e2e2',
        focusConfirm: false,
        preConfirm: () => {
            const walletId = document.getElementById('swal_wallet_id').value;
            const amount = parseFloat(getRawValue(document.getElementById('swal_amount').value));
            
            if (!walletId) return Swal.showValidationMessage('Harap pilih rekening tujuan');
            if (!amount || amount <= 0 || amount > currentAmount) return Swal.showValidationMessage('Nominal penarikan tidak valid');
            return { walletId, amount };
        }
    }).then((res) => {
        if (res.isConfirmed && res.value) {
            executeTransaction(goalId, 'tarik', res.value.walletId, res.value.amount);
        }
    });
}

// Fungsi pembantu transaksi ke Backend via Fetch API
function executeTransaction(goalId, type, accountId, amount) {
    fetch(`/saving-goals/${goalId}`, {
        method: 'PUT',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        }, // <-- Koma penutup yang sebelumnya hilang sudah diperbaiki disini
        body: JSON.stringify({
            type,
            account_id: accountId,
            amount
        })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                title: 'Berhasil!',
                text: data.message,
                icon: 'success',
                background: '#1a1d1d',
                color: '#e2e2e2',
                confirmButtonColor: '#0052ff'
            }).then(() => window.location.reload());
        } else {
            Swal.fire({
                title: 'Gagal!',
                text: data.message,
                icon: 'error',
                background: '#1a1d1d',
                color: '#e2e2e2',
                confirmButtonColor: '#ef4444'
            });
        }
    });
}
</script>
@endsection
