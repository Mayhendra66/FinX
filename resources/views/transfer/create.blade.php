@extends('layouts.app')

@section('content')
<div class="flex-1 md:ml-[500px] p-margin-mobile md:p-6 bg-grid-pattern min-h-screen flex flex-col gap-7 w-full max-w-[1600px] mx-auto mt-10">

    <div class="mb-2">
        <h1 class="text-3xl font-bold tracking-tight text-slate-100">Transfer</h1>
        <p class="text-xs text-slate-500 mt-1">Lakukan transaksi Transfer pada Fin X</p>
    </div>

    <div class="w-full max-w-lg border border-white/[0.09] rounded-2xl p-7 backdrop-blur-md bg-transparent">

        <h2 class="text-[10px] font-bold uppercase tracking-widest text-slate-500 mb-6 flex items-center gap-2">
            <span class="w-[3px] h-[14px] bg-blue-500 rounded-full flex-shrink-0"></span>
            Detail Transaksi Transfer
        </h2>

        @if ($errors->any())
            <div class="mb-5 p-3 bg-red-500/10 border border-red-500/20 text-red-400 rounded-xl text-xs">
                <ul class="space-y-1">
                    @foreach ($errors->all() as $error)
                        <li class="flex items-center gap-1.5">
                            <span class="w-1 h-1 bg-red-400 rounded-full"></span>
                            {{ $error }}
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('transfer.store') }}" method="POST" class="space-y-4">
            @csrf

            <input type="hidden" name="account_id" value="{{ $account->id }}">
            <input type="hidden" name="amount" id="nominal_raw" value="{{ old('amount') }}">

            {{-- Penerima --}}
            <div>
                <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-1.5">Penerima</label>
                <div class="relative flex items-center">
                    <input type="text"
                           value="{{ $account->name }}"
                           readonly
                           class="w-full bg-white/[0.03] border border-white/10 rounded-[10px] pl-4 pr-10 py-[0.7rem] text-sm font-medium text-slate-500 outline-none cursor-not-allowed select-none">
                    <div class="absolute right-3 text-slate-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Nominal Transfer --}}
            <div>
                <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-1.5">Nominal Transfer</label>
                <input type="text"
                       id="nominal_display"
                       oninput="syncTransferAmount(this)"
                       placeholder="Rp 0"
                       class="w-full bg-white/[0.04] border border-white/10 rounded-[10px] px-4 py-[0.7rem] text-sm font-mono font-bold text-slate-100 placeholder-slate-700 outline-none focus:border-blue-500/50 focus:bg-blue-500/[0.04] focus:ring-[3px] focus:ring-blue-500/[0.07] transition-all">
            </div>

            {{-- Catatan --}}
            <div>
                <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-1.5">
                    Catatan Pesan
                    <span class="normal-case tracking-normal font-normal text-slate-600 text-[9px]">(opsional)</span>
                </label>
                <input type="text"
                       name="catatan"
                       value="{{ old('catatan') }}"
                       placeholder="Contoh: Bayar hutang"
                       class="w-full bg-white/[0.04] border border-white/10 rounded-[10px] px-4 py-[0.7rem] text-sm text-slate-300 placeholder-slate-700 outline-none focus:border-blue-500/50 focus:bg-blue-500/[0.04] focus:ring-[3px] focus:ring-blue-500/[0.07] transition-all">
            </div>

            {{-- Divider --}}
            <div class="h-px bg-white/[0.06]"></div>

            {{-- Submit --}}
            <button type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-500 active:scale-[0.99] text-white font-bold py-3 px-4 rounded-[10px] transition-all duration-200 text-sm tracking-wide cursor-pointer flex items-center justify-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                </svg>
                Konfirmasi Transfer
            </button>

        </form>
    </div>
</div>

<script>
function syncTransferAmount(element) {
    let rawValue = element.value.replace(/\D/g, '');
    document.getElementById('nominal_raw').value = rawValue;
    element.value = rawValue ? 'Rp ' + new Intl.NumberFormat('id-ID').format(rawValue) : '';
}
</script>
@endsection