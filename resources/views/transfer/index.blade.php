@extends('layouts.app')

@section('content')
<div class="flex-1 md:ml-[300px] p-margin-mobile md:p-6 bg-grid-pattern min-h-screen flex flex-col gap-6 w-full max-w-[1600px] mx-auto mt-10">

    <div class="mb-2">
        <h1 class="text-3xl font-bold tracking-tight text-white">Transfer</h1>
        <p class="text-sm text-slate-400 mt-1">Lakukan transaksi Transfer pada Fin X</p>
    </div>

    <section>
        {{-- Header + Search --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-5">
            <div class="flex items-center gap-3">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400">Transfer ke orang lain</h3>
                <span class="text-[10px] bg-slate-800 text-slate-300 font-mono font-bold px-2.5 py-0.5 rounded-full border border-slate-700/50">
                    {{ $akun->count() ?? 0 }} Akun
                </span>
            </div>

            {{-- Search --}}
            <div class="relative w-full sm:w-64">
                <div class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-500 pointer-events-none">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z"/>
                    </svg>
                </div>
                <input
                    type="text"
                    id="searchInput"
                    placeholder="Cari nama atau nomor akun..."
                    oninput="filterRekening(this.value)"
                    class="w-full bg-white/[0.04] border border-white/10 rounded-xl pl-9 pr-4 py-2 text-xs text-slate-300 placeholder-slate-600 outline-none focus:border-blue-500/50 focus:ring-2 focus:ring-blue-500/10 transition-all"
                >
            </div>
        </div>

        {{-- List --}}
        <div id="rekening_list" class="flex flex-col gap-2">
            @forelse ($akun ?? [] as $item)
                @php
                    $providerKey = strtolower(($item->type_valid ?? '') . ' ' . ($item->name ?? ''));

                    if (str_contains($providerKey, 'shopee')) {
                        $accent = 'border-l-[#ee4d2d]';
                        $badge  = 'bg-[#ee4d2d]/15 text-[#ff6647] border-[#ee4d2d]/20';
                        $dot    = 'bg-[#ee4d2d]';
                        $icon   = 'text-[#ff6647] bg-[#ee4d2d]/10';
                    } elseif (str_contains($providerKey, 'gopay')) {
                        $accent = 'border-l-[#00aed6]';
                        $badge  = 'bg-[#00aed6]/15 text-[#22c3e6] border-[#00aed6]/20';
                        $dot    = 'bg-[#00aed6]';
                        $icon   = 'text-[#22c3e6] bg-[#00aed6]/10';
                    } elseif (str_contains($providerKey, 'bca')) {
                        $accent = 'border-l-[#003399]';
                        $badge  = 'bg-[#003399]/20 text-[#4d79ff] border-[#003399]/30';
                        $dot    = 'bg-[#003399]';
                        $icon   = 'text-[#4d79ff] bg-[#003399]/15';
                    } elseif (str_contains($providerKey, 'mandiri')) {
                        $accent = 'border-l-[#fdb813]';
                        $badge  = 'bg-[#fdb813]/15 text-[#fdb813] border-[#fdb813]/20';
                        $dot    = 'bg-[#fdb813]';
                        $icon   = 'text-[#fdb813] bg-[#fdb813]/10';
                    } else {
                        $accent = 'border-l-slate-600';
                        $badge  = 'bg-slate-800 text-slate-400 border-slate-700/50';
                        $dot    = 'bg-slate-500';
                        $icon   = 'text-slate-400 bg-slate-800';
                    }
                @endphp

                <a href="{{ route('transfer.create', ['account_id' => $item->id]) }}"
                   data-name="{{ strtolower($item->name ?? '') }}"
                   data-no="{{ strtolower($item->account_no ?? '') }}"
                   class="rekening-item group flex items-center gap-4 bg-white/[0.03] hover:bg-white/[0.06] border border-white/[0.08] border-l-2 {{ $accent }} rounded-xl px-4 py-3 transition-all duration-200 hover:scale-[1.005]">

                    {{-- Icon --}}
                    <div class="w-9 h-9 rounded-lg flex items-center justify-center shrink-0 {{ $icon }}">
                        @if (($item->type ?? 'cash') === 'bank')
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                        @elseif(($item->type ?? 'cash') === 'ewallet')
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a1 1 0 11-2 0 1 1 0 012 0z"/>
                            </svg>
                        @else
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        @endif
                    </div>

                    {{-- Info --}}
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-slate-200 truncate">{{ $item->name ?? 'N/A' }}</p>
                        <p class="text-xs font-mono text-slate-500 mt-0.5 truncate">{{ $item->account_no ?? 'Tunai' }}</p>
                    </div>

                    {{-- Badge --}}
                    <span class="text-[9px] uppercase font-bold px-2 py-0.5 rounded border tracking-wider shrink-0 {{ $badge }}">
                        {{ $item->type_valid ?? (($item->type ?? 'cash') === 'bank' ? 'BANK' : 'CASH') }}
                    </span>

                    {{-- Arrow --}}
                    <div class="text-slate-600 group-hover:text-slate-400 transition-colors shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </div>
                </a>
            @empty
                <div id="empty-state" class="flex flex-col items-center justify-center py-12 border border-dashed border-slate-800 rounded-2xl bg-slate-900/30">
                    <svg class="w-8 h-8 text-slate-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0a2 2 0 01-2 2H6a2 2 0 01-2-2m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5a2 2 0 012-2h12a2 2 0 012 2z"/>
                    </svg>
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Belum ada Account/Wallet</p>
                </div>
            @endforelse
        </div>

        {{-- Empty search state --}}
        <div id="no-result" class="hidden flex-col items-center justify-center py-12 border border-dashed border-slate-800 rounded-2xl bg-slate-900/30 mt-2">
            <svg class="w-8 h-8 text-slate-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z"/>
            </svg>
            <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Akun tidak ditemukan</p>
        </div>
    </section>
</div>

<script>
function filterRekening(query) {
    const q = query.toLowerCase().trim();
    const items = document.querySelectorAll('.rekening-item');
    let visible = 0;

    items.forEach(item => {
        const name = item.dataset.name ?? '';
        const no   = item.dataset.no ?? '';
        const match = name.includes(q) || no.includes(q);
        item.style.display = match ? '' : 'none';
        if (match) visible++;
    });

    const noResult = document.getElementById('no-result');
    noResult.style.display = visible === 0 ? 'flex' : 'none';
}
</script>
@endsection