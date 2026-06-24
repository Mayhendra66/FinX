@extends('layouts.app')

@section('content')
<main class="flex-1 md:ml-[240px] p-margin-mobile md:p-margin-desktop bg-grid-pattern min-h-screen flex flex-col gap-6">

    {{-- ================================================================= --}}
    {{-- MODAL INTRO (Hanya muncul pertama kali buka saving goals)         --}}
    {{-- ================================================================= --}}
    @if(!auth()->user()->saving_goals_intro_seen)
    <div id="introModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/70 backdrop-blur-sm">
        <div class="bg-[#0A0B0D] border border-[#1E2025] rounded-2xl p-7 max-w-md w-full mx-4 shadow-2xl">
            <div class="flex items-center gap-3 mb-5">
                <span class="p-2 rounded-lg bg-blue-500/10 text-blue-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </span>
                <h2 class="text-base font-extrabold text-white">Sebelum Mulai Menabung 👋</h2>
            </div>

            <div class="space-y-4 mb-6">
                {{-- Jam Produktif --}}
                <div class="flex gap-3 p-3.5 bg-amber-500/5 border border-amber-500/20 rounded-xl">
                    <span class="text-amber-400 mt-0.5 shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </span>
                    <div>
                        <p class="text-xs font-bold text-amber-400 mb-1">Jam Transaksi Produktif</p>
                        <p class="text-[11px] text-slate-400 leading-relaxed">
                            Transaksi Saving Goals hanya dapat dilakukan pada <b class="text-slate-200">jam 08.00 – 21.00 WIB</b>. Di luar jam tersebut, transaksi akan ditolak sementara.
                        </p>
                    </div>
                </div>

                {{-- Bunga 5% --}}
                <div class="flex gap-3 p-3.5 bg-blue-500/5 border border-blue-500/20 rounded-xl">
                    <span class="text-blue-400 mt-0.5 shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </span>
                    <div>
                        <p class="text-xs font-bold text-blue-400 mb-1">Biaya Administrasi Bulanan 5%</p>
                        <p class="text-[11px] text-slate-400 leading-relaxed">
                            Setiap bulan, saldo tabungan kamu akan dikenakan <b class="text-slate-200">potongan 5%</b> sebagai biaya administrasi pengelolaan dana. Pastikan kamu rutin setor agar target tetap tercapai.
                        </p>
                    </div>
                </div>

                {{-- Info Umum --}}
                <div class="flex gap-3 p-3.5 bg-emerald-500/5 border border-emerald-500/20 rounded-xl">
                    <span class="text-emerald-400 mt-0.5 shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </span>
                    <div>
                        <p class="text-xs font-bold text-emerald-400 mb-1">Dana Aman & Terpisah</p>
                        <p class="text-[11px] text-slate-400 leading-relaxed">
                            Saldo yang kamu setor akan langsung dipotong dari <b class="text-slate-200">Main Account</b> dan disimpan terpisah. Kamu bisa tarik kapan saja selama jam produktif.
                        </p>
                    </div>
                </div>
            </div>

            {{-- Checkbox Konfirmasi --}}
            <label class="flex items-start gap-3 cursor-pointer mb-5 group">
                <input type="checkbox" id="introCheck" onchange="toggleIntroBtn()"
                    class="mt-0.5 w-4 h-4 rounded border-slate-700 bg-slate-800 text-blue-500 cursor-pointer shrink-0">
                <span class="text-[11px] text-slate-400 group-hover:text-slate-300 transition-colors leading-relaxed">
                    Saya memahami ketentuan jam transaksi, biaya administrasi 5% per bulan, dan cara kerja Saving Goals di aplikasi ini.
                </span>
            </label>

            <button id="introProceedBtn" onclick="markIntroSeen()" disabled
                class="w-full py-3 bg-blue-600 hover:bg-blue-500 disabled:opacity-40 disabled:cursor-not-allowed text-white font-bold text-xs rounded-xl transition-all tracking-wide uppercase cursor-pointer">
                Saya Mengerti, Mulai Menabung 🚀
            </button>
        </div>
    </div>
    @endif

    {{-- ================================================================= --}}
    {{-- HEADER                                                             --}}
    {{-- ================================================================= --}}
    <header class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-white/5 pb-6">
        <div class="space-y-1">
            <div class="flex items-center gap-2">
                <span class="p-2 rounded-lg bg-blue-500/10 text-blue-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </span>
                <h1 class="text-2xl font-extrabold tracking-tight text-white">Target Impian & Tabungan</h1>
            </div>
            <p class="text-xs text-slate-400">Kelola impian masa depan, simpan modal terpisah, dan pantau kemajuan target keuangan Anda.</p>
        </div>

        <div class="flex gap-3">
            {{-- Jam Produktif Badge --}}
            <div id="jamBadge" class="bg-[#0A0B0D] border border-[#1E2025] rounded-xl p-3 px-4 flex items-center gap-2">
                <span id="jamDot" class="w-2 h-2 rounded-full bg-slate-500"></span>
                <div>
                    <span class="text-[10px] text-slate-400 font-semibold uppercase tracking-wider block">Jam Transaksi</span>
                    <span id="jamStatus" class="text-xs font-bold text-slate-300">Mengecek...</span>
                </div>
            </div>
            {{-- Total Terkumpul --}}
            <div class="bg-[#0A0B0D] border border-[#1E2025] rounded-xl p-3 px-5 flex flex-col justify-center">
                <span class="text-[10px] text-slate-400 font-semibold uppercase tracking-wider">Total Terkumpul</span>
                <span class="text-base font-bold text-emerald-400 font-mono">
                    Rp {{ number_format($savingGoals->sum('current_amount'), 0, ',', '.') }}
                </span>
            </div>
        </div>
    </header>

    {{-- ================================================================= --}}
    {{-- GRID UTAMA                                                         --}}
    {{-- ================================================================= --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">

        {{-- ============================================================= --}}
        {{-- FORM CREATE                                                    --}}
        {{-- ============================================================= --}}
        <section class="bg-[#0A0B0D] border border-[#1E2025] rounded-2xl p-6 space-y-5">
            <div class="border-b border-white/5 pb-3">
                <h2 class="text-sm font-bold text-white flex items-center gap-2">
                    <span class="inline-block w-2 h-2 rounded-full bg-blue-500"></span>
                    Tambah Rencana Impian Baru
                </h2>
                <p class="text-[11px] text-slate-400">Atur anggaran impian Anda secara konsisten</p>
            </div>

            <form id="create_goal_form" class="space-y-4" onsubmit="event.preventDefault(); handleCreateGoal();">

                {{-- Nama Impian --}}
                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-semibold text-slate-300" for="goal_name">Nama Impian *</label>
                    <input id="goal_name" type="text" placeholder="Contoh: Beli Macbook Pro M4" required
                        class="w-full bg-[#121414] border border-[#2d2f39] text-white rounded-lg p-2.5 text-xs focus:ring-1 focus:ring-blue-500 focus:border-blue-500 outline-none">
                </div>

                {{-- Nominal Target & Dana Awal --}}
                <div class="grid grid-cols-2 gap-4">
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-semibold text-slate-300" for="goal_target">Target (Rp) *</label>
                        <input id="goal_target" type="text" placeholder="Rp 0" required oninput="formatIDR(this)"
                            class="w-full bg-[#121414] border border-[#2d2f39] text-white rounded-lg p-2.5 text-xs focus:ring-1 focus:ring-blue-500 outline-none">
                    </div>
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-semibold text-slate-300" for="goal_initial">Dana Awal (Rp)</label>
                        <input id="goal_initial" type="text" placeholder="Rp 0" oninput="formatIDR(this)"
                            class="w-full bg-[#121414] border border-[#2d2f39] text-white rounded-lg p-2.5 text-xs focus:ring-1 focus:ring-blue-500 outline-none">
                    </div>
                </div>

                {{-- Info saldo Main Account --}}
                <div class="flex items-center gap-2 p-2.5 bg-slate-900/60 border border-slate-800/60 rounded-lg">
                    <svg class="w-3.5 h-3.5 text-slate-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                    </svg>
                    <span class="text-[10px] text-slate-500">
                        Saldo Main Account:
                        <b class="text-slate-300 font-mono">Rp {{ number_format($mainAccount->balance ?? 0, 0, ',', '.') }}</b>
                    </span>
                </div>

                {{-- Kategori --}}
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

                {{-- Tenggat Waktu --}}
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

        {{-- ============================================================= --}}
        {{-- LIST SAVING GOALS                                              --}}
        {{-- ============================================================= --}}
        <section class="lg:col-span-2 space-y-4">
            <div class="flex items-center justify-between">
                <h2 class="text-sm font-extrabold text-white">Progress Pengumpulan Tabungan</h2>
                <span class="text-xs bg-slate-800 border border-[#1E2025] px-2.5 py-1 rounded-full text-slate-300" id="goal_count">
                    {{ $savingGoals->count() }} Target Aktif
                </span>
            </div>

            @if($savingGoals->isEmpty())
            <div class="p-8 border border-dashed border-[#1E2025] rounded-2xl flex flex-col items-center justify-center text-center text-slate-500 gap-2">
                <svg class="w-10 h-10 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z"/>
                </svg>
                <div class="text-xs font-semibold">Tidak ada target impian saat ini</div>
                <p class="text-[10px] text-slate-500 max-w-xs">Komitmen tabungan Anda akan tampil di halaman ini.</p>
            </div>
            @else

            <div id="goals_grid" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($savingGoals as $goal)
                @php
                    $isTargetReached = $goal->current_amount >= $goal->target_amount;
                    $percent = $isTargetReached ? 100 : ($goal->target_amount > 0 ? ($goal->current_amount / $goal->target_amount) * 100 : 0);
                    $kekurangan = $isTargetReached ? 0 : ($goal->target_amount - $goal->current_amount);
                    $barColor = $isTargetReached ? 'bg-emerald-500' : 'bg-blue-600';
                    $percentTextColor = $isTargetReached ? 'text-emerald-400' : 'text-blue-400';

                    // Hitung bunga 5% per bulan simulasi
                    $now = \Carbon\Carbon::now();
                    $created = \Carbon\Carbon::parse($goal->created_at ?? now());
                    $monthsPassed = (int) $created->diffInMonths($now);
                    $bungaTotal = $goal->current_amount * (1 - pow(0.95, $monthsPassed));
                @endphp

                <div class="bg-[#0A0B0D] border border-[#1E2025] rounded-2xl p-5 hover:border-slate-700/50 transition-all flex flex-col justify-between relative overflow-hidden group">

                    {{-- Top Row --}}
                    <div class="space-y-4">
                        <div class="flex justify-between items-start gap-2">
                            <div class="flex items-center gap-2.5">
                                <div class="p-2 w-10 h-10 rounded-xl flex items-center justify-center category-icon" data-category="{{ $goal->category }}"></div>
                                <div>
                                    <h3 class="text-xs font-bold text-white group-hover:text-blue-400 transition-colors">{{ $goal->name }}</h3>
                                    <span class="text-[9px] bg-[#121414] border border-[#1E2025] px-2 py-0.5 rounded-full text-slate-400">{{ $goal->category }}</span>
                                </div>
                            </div>
                            <button onclick="handleDeleteGoal({{ $goal->id }})" class="p-1.5 rounded-lg text-slate-400 hover:bg-red-500/10 hover:text-red-400 transition-all cursor-pointer">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </div>

                        {{-- Angka Finansial --}}
                        <div class="grid grid-cols-2 gap-2 border-t border-b border-white/5 py-3">
                            <div>
                                <span class="text-[9px] text-slate-500 uppercase font-semibold">Terkumpul</span>
                                <p class="text-xs font-bold text-emerald-400 font-mono">Rp {{ number_format($goal->current_amount, 0, ',', '.') }}</p>
                            </div>
                            <div class="text-right">
                                <span class="text-[9px] text-slate-500 uppercase font-semibold">Target</span>
                                <p class="text-xs font-bold text-white font-mono">Rp {{ number_format($goal->target_amount, 0, ',', '.') }}</p>
                            </div>
                        </div>

                        {{-- Estimasi Bunga --}}
                        @if($monthsPassed > 0 && $bungaTotal > 0)
                        <div class="flex items-center gap-2 p-2 bg-amber-500/5 border border-amber-500/15 rounded-lg">
                            <svg class="w-3 h-3 text-amber-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                            <span class="text-[9px] text-amber-400">
                                Admin 5%/bln selama {{ $monthsPassed }} bln ≈
                                <b>Rp {{ number_format($bungaTotal, 0, ',', '.') }}</b> terpotong
                            </span>
                        </div>
                        @endif

                        {{-- Deadline --}}
                        <div class="flex items-center gap-1.5">
                            <svg class="w-3 h-3 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <span class="text-[10px] text-slate-500">Tenggat: <b class="text-slate-400">{{ \Carbon\Carbon::parse($goal->deadline)->format('d M Y') }}</b></span>
                        </div>

                        {{-- Progress Bar --}}
                        <div class="space-y-1.5">
                            <div class="flex justify-between items-center text-[10px]">
                                <span class="text-slate-400">
                                    @if($isTargetReached)
                                        <b class="text-emerald-400">🎉 Target Terpenuhi!</b>
                                    @else
                                        Kurang: <b class="text-amber-500">Rp {{ number_format($kekurangan, 0, ',', '.') }}</b>
                                    @endif
                                </span>
                                <span class="font-extrabold {{ $percentTextColor }}">{{ number_format($percent, 1) }}%</span>
                            </div>
                            <div class="w-full bg-[#121414] h-2 rounded-full overflow-hidden">
                                <div class="{{ $barColor }} h-2 rounded-full transition-all duration-500" style="width: {{ $percent }}%"></div>
                            </div>
                        </div>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="mt-4 pt-4 border-t border-white/5 grid grid-cols-2 gap-2">
                        <button onclick="promptSetorDana({{ $goal->id }}, {{ $kekurangan }}, '{{ $goal->name }}')"
                            class="py-1.5 bg-emerald-500/10 hover:bg-emerald-500/20 text-emerald-400 font-bold text-[11px] rounded-lg border border-emerald-500/20 flex items-center justify-center gap-1.5 cursor-pointer hover:scale-[1.02] active:scale-95 transition-all">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Setor Dana
                        </button>
                        <button onclick="promptTarikDana({{ $goal->id }}, {{ $goal->current_amount }}, '{{ $goal->name }}')"
                            class="py-1.5 bg-rose-500/10 hover:bg-rose-500/20 text-rose-400 font-bold text-[11px] rounded-lg border border-rose-500/20 flex items-center justify-center gap-1.5 cursor-pointer hover:scale-[1.02] active:scale-95 transition-all">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                            </svg>
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

{{-- ================================================================= --}}
{{-- SCRIPTS                                                            --}}
{{-- ================================================================= --}}
<script src="{{ asset('js/formatIDR.js') }}"></script>
<script>
const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
const mainBalance = {{ $mainAccount->balance ?? 0 }};

// ----------------------------------------------------------------
// CEK JAM PRODUKTIF (08:00 - 21:00)
// ----------------------------------------------------------------
function isJamProduktif() {
    const now = new Date();
    const hour = now.getHours();
    return hour >= 8 && hour < 21;
}

function updateJamBadge() {
    const aktif = isJamProduktif();
    const dot = document.getElementById('jamDot');
    const status = document.getElementById('jamStatus');
    if (!dot || !status) return;
    if (aktif) {
        dot.className = 'w-2 h-2 rounded-full bg-emerald-400';
        status.textContent = 'Aktif 08.00–21.00';
        status.className = 'text-xs font-bold text-emerald-400';
    } else {
        dot.className = 'w-2 h-2 rounded-full bg-red-400';
        status.textContent = 'Di luar jam produktif';
        status.className = 'text-xs font-bold text-red-400';
    }
}
updateJamBadge();
setInterval(updateJamBadge, 60000);

// ----------------------------------------------------------------
// GUARD JAM PRODUKTIF — tampilkan SweetAlert jika di luar jam
// ----------------------------------------------------------------
function guardJam() {
    if (!isJamProduktif()) {
        Swal.fire({
            title: '⏰ Di Luar Jam Produktif',
            html: `<p style="font-size:13px; color:#94a3b8; line-height:1.7">Transaksi Saving Goals hanya bisa dilakukan antara <b style="color:#f1f5f9">08.00 – 21.00 WIB</b>.<br>Silakan coba lagi besok pagi ya!</p>`,
            icon: 'warning',
            confirmButtonText: 'Oke, Nanti Lagi',
            confirmButtonColor: '#f59e0b',
            background: '#0A0B0D',
            color: '#e2e2e2',
        });
        return false;
    }
    return true;
}

// ----------------------------------------------------------------
// MODAL INTRO
// ----------------------------------------------------------------
function toggleIntroBtn() {
    const checked = document.getElementById('introCheck').checked;
    document.getElementById('introProceedBtn').disabled = !checked;
}

function markIntroSeen() {
    fetch("{{ route('saving-goals.intro-seen') }}", {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': csrfToken, 'Content-Type': 'application/json' }
    }).then(() => {
        document.getElementById('introModal').remove();
    });
}

// ----------------------------------------------------------------
// CATEGORY ICONS
// ----------------------------------------------------------------
document.addEventListener('DOMContentLoaded', function () {
    const iconsMap = {
        'Gawai':       { cls: 'bg-orange-500/10 text-orange-400',  svg: `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>` },
        'Liburan':     { cls: 'bg-blue-500/10 text-blue-400',      svg: `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>` },
        'Dana Darurat':{ cls: 'bg-red-500/10 text-red-400',        svg: `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>` },
        'Kendaraan':   { cls: 'bg-purple-500/10 text-purple-400',  svg: `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>` },
        'Properti':    { cls: 'bg-emerald-500/10 text-emerald-400',svg: `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>` },
    };
    document.querySelectorAll('.category-icon').forEach(el => {
        const cat = el.getAttribute('data-category');
        const cfg = iconsMap[cat] || iconsMap['Dana Darurat'];
        el.classList.add(...cfg.cls.split(' '));
        el.innerHTML = cfg.svg;
    });
});

// ----------------------------------------------------------------
// CREATE GOAL
// ----------------------------------------------------------------
function handleCreateGoal() {
    if (!guardJam()) return;

    const name      = document.getElementById('goal_name').value;
    const target    = parseFloat(getRawValue(document.getElementById('goal_target').value)) || 0;
    const initial   = parseFloat(getRawValue(document.getElementById('goal_initial').value)) || 0;
    const date      = document.getElementById('goal_date').value;
    const category  = document.getElementById('goal_category').value;

    if (target <= 0) {
        return Swal.fire({ title: 'Error!', text: 'Nominal target tidak boleh 0.', icon: 'error', background: '#0A0B0D', color: '#e2e2e2', confirmButtonColor: '#0052ff' });
    }
    if (initial > target) {
        return Swal.fire({ title: 'Error!', text: 'Dana awal tidak boleh melebihi target.', icon: 'error', background: '#0A0B0D', color: '#e2e2e2', confirmButtonColor: '#0052ff' });
    }
    if (initial > mainBalance) {
        return Swal.fire({ title: 'Saldo Tidak Cukup!', text: 'Dana awal melebihi saldo Main Account kamu.', icon: 'error', background: '#0A0B0D', color: '#e2e2e2', confirmButtonColor: '#0052ff' });
    }

    const form = document.createElement('form');
    form.method = 'POST';
    form.action = "{{ route('saving-goals.store') }}";
    const fields = { _token: csrfToken, name, target_amount: target, current_amount: initial, deadline: date, category };
    for (const k in fields) {
        const inp = document.createElement('input');
        inp.type = 'hidden'; inp.name = k; inp.value = fields[k];
        form.appendChild(inp);
    }
    document.body.appendChild(form);
    form.submit();
}

// ----------------------------------------------------------------
// DELETE GOAL
// ----------------------------------------------------------------
function handleDeleteGoal(goalId) {
    if (!guardJam()) return;
    Swal.fire({
        title: 'Hapus Rencana Impian?',
        text: 'Saldo akan dikembalikan ke Main Account kamu.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#374151',
        confirmButtonText: 'Ya, Hapus & Kembalikan',
        cancelButtonText: 'Batal',
        background: '#0A0B0D',
        color: '#e2e2e2',
    }).then(result => {
        if (!result.isConfirmed) return;
        fetch(`/saving-goals/${goalId}`, {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': csrfToken, 'Content-Type': 'application/json', 'Accept': 'application/json' }
        }).then(r => r.json()).then(data => {
            if (data.success) {
                Swal.fire({ title: 'Terhapus!', text: data.message, icon: 'success', background: '#0A0B0D', color: '#e2e2e2', confirmButtonColor: '#0052ff' })
                .then(() => window.location.reload());
            }
        });
    });
}

// ----------------------------------------------------------------
// SETOR DANA
// ----------------------------------------------------------------
function promptSetorDana(goalId, minNeeded, goalName) {
    if (!guardJam()) return;
    Swal.fire({
        title: 'Setor Dana Tabungan',
        html: `
        <div style="text-align:left;display:flex;flex-direction:column;gap:12px;margin-top:10px">
            <p style="font-size:12px;color:#94a3b8">Sisa kekurangan: <b style="color:#f1f5f9">Rp ${minNeeded.toLocaleString('id-ID')}</b></p>
            <div style="display:flex;flex-direction:column;gap:4px">
                <label style="font-size:11px;font-weight:bold;color:#c3c5d9">Nominal Setor (Rp)</label>
                <input type="text" id="swal_amount" placeholder="Contoh: Rp 500.000" oninput="formatIDR(this)"
                    style="width:100%;background:#121414;color:#e2e2e2;border:1px solid rgba(67,70,86,0.4);border-radius:8px;padding:10px 12px;font-size:13px;outline:none">
            </div>
        </div>`,
        showCancelButton: true,
        confirmButtonText: 'Setor Sekarang',
        cancelButtonText: 'Batal',
        confirmButtonColor: '#10b981',
        cancelButtonColor: '#374151',
        background: '#0A0B0D',
        color: '#e2e2e2',
        focusConfirm: false,
        preConfirm: () => {
            const amount = parseFloat(getRawValue(document.getElementById('swal_amount').value));
            if (!amount || amount <= 0) return Swal.showValidationMessage('Masukkan nominal yang valid');
            if (amount > mainBalance) return Swal.showValidationMessage('Saldo Main Account tidak mencukupi');
            return { amount };
        }
    }).then(res => {
        if (res.isConfirmed && res.value) executeTransaction(goalId, 'setor', res.value.amount);
    });
}

// ----------------------------------------------------------------
// TARIK DANA
// ----------------------------------------------------------------
function promptTarikDana(goalId, currentAmount, goalName) {
    if (!guardJam()) return;
    Swal.fire({
        title: 'Tarik Dana Simpanan',
        html: `
        <div style="text-align:left;display:flex;flex-direction:column;gap:12px;margin-top:10px">
            <p style="font-size:12px;color:#94a3b8">Maksimum tarik: <b style="color:#f1f5f9">Rp ${currentAmount.toLocaleString('id-ID')}</b></p>
            <div style="display:flex;flex-direction:column;gap:4px">
                <label style="font-size:11px;font-weight:bold;color:#c3c5d9">Nominal Tarik (Rp)</label>
                <input type="text" id="swal_amount" placeholder="Contoh: Rp 200.000" oninput="formatIDR(this)"
                    style="width:100%;background:#121414;color:#e2e2e2;border:1px solid rgba(67,70,86,0.4);border-radius:8px;padding:10px 12px;font-size:13px;outline:none">
            </div>
        </div>`,
        showCancelButton: true,
        confirmButtonText: 'Tarik Dana',
        cancelButtonText: 'Batal',
        confirmButtonColor: '#f43f5e',
        cancelButtonColor: '#374151',
        background: '#0A0B0D',
        color: '#e2e2e2',
        focusConfirm: false,
        preConfirm: () => {
            const amount = parseFloat(getRawValue(document.getElementById('swal_amount').value));
            if (!amount || amount <= 0 || amount > currentAmount) return Swal.showValidationMessage('Nominal tidak valid');
            return { amount };
        }
    }).then(res => {
        if (res.isConfirmed && res.value) executeTransaction(goalId, 'tarik', res.value.amount);
    });
}

// ----------------------------------------------------------------
// EXECUTE TRANSAKSI
// ----------------------------------------------------------------
function executeTransaction(goalId, type, amount) {
    fetch(`/saving-goals/${goalId}`, {
        method: 'PUT',
        headers: { 'X-CSRF-TOKEN': csrfToken, 'Content-Type': 'application/json', 'Accept': 'application/json' },
        body: JSON.stringify({ type, amount })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            Swal.fire({ title: 'Berhasil!', text: data.message, icon: 'success', background: '#0A0B0D', color: '#e2e2e2', confirmButtonColor: '#0052ff' })
            .then(() => window.location.reload());
        } else {
            Swal.fire({ title: 'Gagal!', text: data.message, icon: 'error', background: '#0A0B0D', color: '#e2e2e2', confirmButtonColor: '#ef4444' });
        }
    });
}
</script>
@endsection