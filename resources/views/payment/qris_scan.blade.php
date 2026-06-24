@extends('layouts.app')

@section('content')
<div class="flex-1 md:ml-[240px] p-margin-mobile md:p-margin-desktop bg-grid-pattern min-h-screen space-y-6">
    
    <div class="w-full max-w-xl bg-[#0a0b0e] text-white flex flex-col rounded-3xl overflow-hidden shadow-2xl border border-white/[0.07] p-6 md:p-8">

        {{-- Header --}}
        <div class="flex items-center justify-between pb-6 border-b border-white/[0.06]">
            <button type="button" onclick="closeQrisModal()"
                class="p-2 hover:bg-white/10 rounded-full text-slate-400 hover:text-white transition-colors focus:outline-none">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </button>
            <div class="flex flex-col items-center flex-1">
                <span class="text-[11px] font-extrabold uppercase tracking-[0.2em] text-blue-400">Fin X</span>
                <h2 class="text-lg font-black tracking-widest text-white uppercase mt-0.5">PT SIMULASI JAYA</h2>
            </div>
            <div class="w-9"></div>
        </div>

        {{-- Form Transaksi --}}
        <form action="{{ route('scan.store') }}" method="POST" class="pt-6 flex flex-col gap-5">
    @csrf

    <input type="hidden"
           name="description"
           value="PEMBAYARAN QRIS SCAN">

    {{-- Input Nominal --}}
    <div class="flex flex-col gap-2">
        <label for="nominal_display"
            class="block text-xs font-extrabold uppercase tracking-wider text-slate-400">
            Nominal Pembayaran
        </label>

        <div class="relative rounded-xl shadow-sm">

            {{-- Yang dikirim ke controller --}}
            <input type="hidden"
                   name="amount"
                   id="nominal_raw"
                   value="">

            {{-- Yang dilihat user --}}
            <input
                type="text"
                id="nominal_display"
                placeholder="Rp 0"
                required
                oninput="
                    formatIDR(this);
                    document.getElementById('nominal_raw').value = getRawValue(this.value);
                "
                class="block w-full px-4 py-3 bg-white/[0.03] border border-white/[0.08] rounded-xl text-white font-bold text-lg placeholder-slate-600 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-all">
        </div>
    </div>

    {{-- note --}}
    <div class="flex flex-col gap-2">
        <label for="note"
            class="block text-xs font-extrabold uppercase tracking-wider text-slate-400">
            note / Keterangan
        </label>

        <input
            type="text"
            name="note"
            id="note"
            placeholder="Contoh: Pembayaran Lapangan"
            class="block w-full px-4 py-3 bg-white/[0.03] border border-white/[0.08] rounded-xl text-white text-sm placeholder-slate-600 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-all">
    </div>

    {{-- Info Pengguna --}}
    <div class="bg-white/[0.02] border border-white/[0.05] rounded-xl p-4 flex justify-between items-center text-xs mt-2">
        <span class="text-slate-400 font-medium">Pelanggan:</span>
        <span class="text-white font-bold">
            {{ auth()->user()->name ?? 'Pengguna Fin X' }}
        </span>
    </div>

    {{-- Submit --}}
    <button
        type="submit"
        class="w-full py-3.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-black rounded-xl flex items-center justify-center gap-2 transition-all focus:outline-none shadow-lg shadow-blue-600/20 mt-2">
        Proses Pembayaran
    </button>
</form>

        {{-- Footer Security --}}
        <div class="pt-6 mt-6 border-t border-white/[0.06]">
            <div class="flex items-center gap-3 bg-blue-500/5 border border-blue-500/10 rounded-xl p-4">
                <i data-lucide="shield-check" class="w-5 h-5 text-blue-400 flex-shrink-0"></i>
                <p class="text-xs text-slate-500 leading-relaxed">
                    Transaksi dienkripsi & aman. Data Anda dilindungi dengan standar keamanan tinggi.
                </p>
            </div>
        </div>

    </div>
</div>
<script src="{{asset('js/formatIDR.js')}}"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {

    const form = document.querySelector('form');
    const nominalDisplay = document.getElementById('nominal_display');
    const nominalRaw = document.getElementById('nominal_raw');

    form.addEventListener('submit', function () {

        nominalRaw.value = getRawValue(nominalDisplay.value);

    });

});
</script>
@endsection