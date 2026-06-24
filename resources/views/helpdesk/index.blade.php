@extends('layouts.app')

@section('content')
<div class="flex-1 md:ml-[500px] p-margin-mobile md:p-margin-desktop bg-grid-pattern min-h-screen">

  @if(session('success'))
    <div class="p-4 bg-emerald-500/10 border border-emerald-500/20 rounded-xl flex items-center gap-3 text-emerald-400 text-xs font-semibold animate-fade-in">
      <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
      </svg>
      <span>{{ session('success') }}</span>
    </div>
  @endif

  <div class="bg-[#0c0f0f]/95 backdrop-blur-xl border border-white/10 rounded-xl overflow-hidden shadow-xl">
    <div class="p-6 border-b border-white/5">
      <h2 class="text-base font-bold text-[#e1e2e6]">Buat Tiket Bantuan</h2>
      <p class="text-xs text-[#c3c5d9]/60 mt-1">Jelaskan kendala atau pertanyaan Anda. Tim kami akan segera merespons.</p>
    </div>

    <form action="{{ route('helpdesk.store') }}" method="POST" class="p-6 space-y-5">
      @csrf

      <div>
        <label for="category" class="block text-xs font-semibold text-[#c3c5d9]/80 mb-2">Kategori Masalah</label>
        <select id="category" name="category" required class="w-full bg-white/5 border border-white/10 rounded-lg px-4 py-2.5 text-sm text-[#e1e2e6] focus:outline-none focus:border-[#0052ff] transition-all">
          <option value="" disabled selected class="bg-[#0c0f0f]">Pilih Kategori</option>
          <option value="Teknis" class="bg-[#0c0f0f]">Kendala Teknis</option>
          <option value="Tagihan / Pembayaran" class="bg-[#0c0f0f]">Tagihan / Pembayaran</option>
          <option value="Akun" class="bg-[#0c0f0f]">Manajemen Akun</option>
          <option value="Lainnya" class="bg-[#0c0f0f]">Lainnya</option>
        </select>
        @error('category') <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
      </div>

      <div>
        <label for="subject" class="block text-xs font-semibold text-[#c3c5d9]/80 mb-2">Subjek / Judul Masalah</label>
        <input type="text" id="subject" name="subject" required value="{{ old('subject') }}" placeholder="Contoh: Gagal sinkronisasi mutasi rekening" class="w-full bg-white/5 border border-white/10 rounded-lg px-4 py-2.5 text-sm text-[#e1e2e6] placeholder-[#c3c5d9]/30 focus:outline-none focus:border-[#0052ff] transition-all" />
        @error('subject') <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
      </div>

      <div>
        <label class="block text-xs font-semibold text-[#c3c5d9]/80 mb-2">Tingkat Prioritas</label>
        <div class="grid grid-cols-3 gap-3">
          <label class="flex items-center justify-center p-3 border border-white/10 rounded-lg cursor-pointer hover:bg-white/5 text-xs font-medium text-[#c3c5d9]/80 has-[:checked]:border-blue-500 has-[:checked]:text-[#b7c4ff] has-[:checked]:bg-blue-500/10 transition-all">
            <input type="radio" name="priority" value="low" checked class="hidden">
            Rendah
          </label>
          <label class="flex items-center justify-center p-3 border border-white/10 rounded-lg cursor-pointer hover:bg-white/5 text-xs font-medium text-[#c3c5d9]/80 has-[:checked]:border-amber-500 has-[:checked]:text-amber-400 has-[:checked]:bg-amber-500/10 transition-all">
            <input type="radio" name="priority" value="medium" class="hidden">
            Sedang
          </label>
          <label class="flex items-center justify-center p-3 border border-white/10 rounded-lg cursor-pointer hover:bg-white/5 text-xs font-medium text-[#c3c5d9]/80 has-[:checked]:border-red-500 has-[:checked]:text-red-400 has-[:checked]:bg-red-500/10 transition-all">
            <input type="radio" name="priority" value="high" class="hidden">
            Tinggi
          </label>
        </div>
        @error('priority') <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
      </div>

      <div class="flex items-center justify-end gap-3 pt-4 border-t border-white/5">
        <a href="{{ route('dashboard') }}" class="px-4 py-2 border border-white/10 hover:border-white/20 text-[#e1e2e6] font-semibold text-xs rounded-lg transition-all active:scale-[0.98]">
          Batal
        </a>
        <button type="submit" class="px-4 py-2 bg-[#0052ff] hover:bg-[#0052ff]/90 text-white font-semibold text-xs rounded-lg transition-all shadow-lg shadow-[#0052ff]/20 active:scale-[0.98]">
          Kirim Tiket
        </button>
      </div>
    </form>
  </div>
</div>
@endsection