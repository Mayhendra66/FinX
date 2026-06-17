@extends('layouts.app')
@section('content')

  <main class="flex-1 md:ml-[240px] p-margin-mobile md:p-margin-desktop bg-grid-pattern min-h-screen flex flex-col gap-6">

    <!-- Top Header Overview -->
    <header class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 border-b border-slate-200 dark:border-slate-800/65 pb-6">
      <div>
        <h1 class="text-3xl font-black font-display tracking-tight text-slate-900 dark:text-white flex items-center gap-3">
          <span class="w-11 h-11 flex items-center justify-center rounded-2xl bg-blue-600 text-white shadow-lg shadow-blue-500/20">
            <i class="fa-solid fa-chart-pie text-xl"></i>
          </span>
          Analisis Portofolio & Biaya
        </h1>
        <p class="text-sm text-slate-500 dark:text-slate-400 mt-2">
          Visualisasikan distribusi biaya, rasio likuiditas aset, dan optimalkan efisiensi keuangan Anda.
        </p>
      </div>

      <form id="form_filter_date" method="GET" action="{{ url()->current() }}" class="flex items-center gap-3 w-full md:w-auto">
  <div class="relative w-full md:w-56">
    <i class="fa-solid fa-calendar-days absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 dark:text-slate-500 text-xs pointer-events-none"></i>
    <input
      id="filter_date"
      name="date_range"
      type="text"
      value="{{ request('date_range') }}"
      placeholder="Pilih tanggal transaksi"
      class="w-full bg-white dark:bg-[#121414] border border-slate-250 dark:border-slate-800 rounded-xl pl-9 pr-3.5 py-2.5 text-xs font-bold text-slate-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 cursor-pointer shadow-sm placeholder:text-slate-400 placeholder:font-medium"
    >
  </div>
</form>
    </header>

    <!-- KPI Widgets Row -->
    <section class="grid grid-cols-1 md:grid-cols-3 gap-6">

      <!-- Card: Total Pengeluaran -->
      <div class="bg-white dark:bg-[#121415] border border-slate-200/80 dark:border-slate-800/50 p-6 rounded-2xl shadow-sm relative overflow-hidden group">
        <div class="absolute -top-10 -right-10 w-28 h-28 bg-red-500/5 dark:bg-red-500/10 rounded-full blur-2xl group-hover:bg-red-500/10 transition-colors"></div>
        <div class="flex justify-between items-start">
          <div class="space-y-1">
            <span class="text-xs uppercase tracking-wider text-slate-400 dark:text-slate-500 font-bold block">Total Pengeluaran</span>
            <p id="total_ex_value" class="text-2xl font-black font-display text-slate-950 dark:text-white">
                Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}
            </p>
          </div>
          <span class="p-3 bg-red-50 dark:bg-red-500/10 border border-red-100 dark:border-red-500/20 text-red-500 dark:text-red-400 rounded-xl">
            <i class="fa-solid fa-arrow-trend-up"></i>
          </span>
        </div>
        <div class="mt-4 pt-4 border-t border-slate-100 dark:border-slate-800/40 flex items-center justify-between text-xs">
          <span class="text-slate-500 dark:text-slate-400">Akumulasi seluruh transaksi pengeluaran</span>
        </div>
      </div>

      

      <!-- Card: Sisa Anggaran -->
      <div class="bg-white dark:bg-[#121415] border border-slate-200/80 dark:border-slate-800/50 p-6 rounded-2xl shadow-sm relative overflow-hidden group">
        <div class="absolute -top-10 -right-10 w-28 h-28 bg-blue-500/5 dark:bg-blue-500/10 rounded-full blur-2xl group-hover:bg-blue-500/10 transition-colors"></div>
        <div class="flex justify-between items-start">
          <div class="space-y-1">
            <span class="text-xs uppercase tracking-wider text-slate-400 dark:text-slate-500 font-bold block">Sisa Anggaran Belanja</span>
            <p id="total_budget_value" class="text-2xl font-black font-display {{ $sisaAnggaran < 0 ? 'text-red-500' : 'text-slate-950 dark:text-white' }}">
                Rp {{ number_format($sisaAnggaran, 0, ',', '.') }}
            </p>
          </div>
          <span class="p-3 bg-blue-50 dark:bg-blue-500/10 border border-blue-100 dark:border-blue-500/20 text-blue-600 dark:text-[#b7c4ff] rounded-xl">
            <i class="fa-solid fa-bullseye"></i>
          </span>
        </div>
        <div class="mt-4 pt-4 border-t border-slate-100 dark:border-slate-800/40 flex items-center justify-between text-xs">
          <span class="text-slate-500 dark:text-slate-400">Status Penggunaan</span>
          @if($sisaAnggaran < 0)
            <span class="text-red-500 font-bold"><i class="fa-solid fa-triangle-exclamation mr-0.5"></i> Anggaran Terlampaui</span>
          @else
            <span class="text-blue-605 dark:text-blue-400 font-bold"><i class="fa-solid fa-clock mr-0.5"></i> Sisa {{ $persenAnggaranSisa }}% Aman</span>
          @endif
        </div>
      </div>


      <!-- Card: Alokasi Tabungan -->
      <div class="bg-white dark:bg-[#121415] border border-slate-200/80 dark:border-slate-800/50 p-6 rounded-2xl shadow-sm relative overflow-hidden group">
        <div class="absolute -top-10 -right-10 w-28 h-28 bg-emerald-500/5 dark:bg-emerald-500/10 rounded-full blur-2xl group-hover:bg-emerald-500/10 transition-colors"></div>
        <div class="flex justify-between items-start">
          <div class="space-y-1">
            <span class="text-xs uppercase tracking-wider text-slate-400 dark:text-slate-500 font-bold block">Alokasi Tabungan</span>
            <p id="total_save_value" class="text-2xl font-black font-display text-slate-950 dark:text-white">
                Rp {{ number_format($totalTabungan, 0, ',', '.') }}
            </p>
          </div>
          <span class="p-3 bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-100 dark:border-emerald-500/20 text-emerald-600 dark:text-emerald-400 rounded-xl">
            <i class="fa-solid fa-piggy-bank"></i>
          </span>
        </div>
        <div class="mt-4 pt-4 border-t border-slate-100 dark:border-slate-800/40 flex items-center justify-between text-xs">
          <span class="text-slate-500 dark:text-slate-400">Total dana tertaut pada {{ $savingGoals->count() }} target impian</span>
        </div>
      </div>

    </section>

    <!-- Komposisi Pengeluaran per Kategori & Tautan Saving Goal Aktif sejajar dalam satu baris -->
    <section class="grid grid-cols-1 lg:grid-cols-2 gap-6">

      <!-- Komposisi Pengeluaran per Kategori -->
      <div class="bg-white dark:bg-[#121415] border border-slate-200/80 dark:border-slate-800/50 rounded-2xl shadow-sm p-6 flex flex-col gap-5">
        <div class="flex justify-between items-center">
          <h3 class="font-bold text-base font-display text-slate-900 dark:text-white flex items-center gap-2">
            <i class="fa-solid fa-chart-simple text-blue-600"></i>
            Komposisi Pengeluaran per Kategori
          </h3>
          <span class="text-[10px] uppercase font-black tracking-wider text-slate-400 dark:text-slate-500 bg-slate-100 dark:bg-slate-800/80 px-2.5 py-1 rounded-md">Live Jurnal</span>
        </div>

        <div class="flex flex-col gap-4">
          @forelse($expenseCategories as $category)
            @php
                // Warna interaktif berdasarkan persentase kontribusi kategori
                // terhadap total pengeluaran: <50% hijau, 50-80% amber, >80% merah
                if ($category->percent > 80) {
                    $barColor = 'bg-red-500';
                    $textColor = 'text-red-500 dark:text-red-400';
                } elseif ($category->percent >= 50) {
                    $barColor = 'bg-amber-500';
                    $textColor = 'text-amber-500 dark:text-amber-400';
                } else {
                    $barColor = 'bg-emerald-500';
                    $textColor = 'text-emerald-500 dark:text-emerald-400';
                }
            @endphp
            <div class="flex flex-col gap-1.5 pb-4 border-b border-slate-100 dark:border-slate-800/40 last:border-0 last:pb-0">
              <div class="flex justify-between items-center text-xs">
                <span class="font-bold text-slate-800 dark:text-slate-200 capitalize">
                    <i class="fa-solid {{ $category->icon ?? 'fa-tag' }} text-slate-450 mr-2"></i> {{ $category->name }}
                </span>
                <span class="text-slate-600 dark:text-slate-400 font-medium">
                  Rp {{ number_format($category->total_amount, 0, ',', '.') }}
                  <span class="{{ $textColor }} font-bold">({{ $category->percent }}%)</span>
                </span>
              </div>
              <div class="w-full bg-slate-100 dark:bg-slate-800/80 rounded-full h-2">
                <div class="{{ $barColor }} h-2 rounded-full transition-all duration-500" style="width: {{ $category->percent }}%;"></div>
              </div>
            </div>
          @empty
            <div class="flex flex-col items-center justify-center text-center gap-2 py-6 text-slate-400 dark:text-slate-500">
                <i class="fa-solid fa-chart-simple text-2xl"></i>
                <p class="text-xs">Belum ada transaksi pengeluaran tercatat.</p>
            </div>
          @endforelse
        </div>
      </div>

      <!-- Tautan Saving Goal Aktif -->
      <div class="bg-white dark:bg-[#121415] border border-slate-200/80 dark:border-slate-800/50 rounded-2xl shadow-sm p-6 flex flex-col gap-5">
        <div class="flex justify-between items-center">
          <h3 class="font-bold text-base font-display text-slate-900 dark:text-white flex items-center gap-2">
            <i class="fa-solid fa-piggy-bank text-emerald-600"></i>
            Tautan Saving Goal Aktif
          </h3>
          <span class="text-[10px] uppercase font-black tracking-wider text-slate-400 dark:text-slate-500 bg-slate-100 dark:bg-slate-800/80 px-2.5 py-1 rounded-md">Live Jurnal</span>
        </div>

        <div class="flex flex-col gap-4">
          @forelse($savingGoals as $goal)
            @php
                // Warna interaktif berdasarkan persentase tercapai:
                // >50% hijau, 20-50% biru, <20% abu
                if ($goal->percent > 50) {
                    $goalBarColor = 'bg-emerald-500';
                } elseif ($goal->percent >= 20) {
                    $goalBarColor = 'bg-blue-600 dark:bg-blue-500';
                } else {
                    $goalBarColor = 'bg-slate-400 dark:bg-slate-600';
                }

                $linkedAccount = $akun->firstWhere('id', $goal->account_id);
            @endphp
            <div class="flex flex-col gap-1.5 pb-4 border-b border-slate-100 dark:border-slate-800/40 last:border-0 last:pb-0">
              <div class="flex justify-between items-center text-xs">
                <span class="font-bold text-slate-800 dark:text-slate-200">{{ $goal->name }}</span>
                <span class="text-slate-550 dark:text-slate-450 font-bold">
                    Rp {{ number_format($goal->current_amount, 0, ',', '.') }}
                    <span class="text-slate-400">/ {{ number_format($goal->target_amount, 0, ',', '.') }}</span>
                </span>
              </div>
              <div class="w-full bg-slate-100 dark:bg-slate-800/80 rounded-full h-2">
                <div class="{{ $goalBarColor }} h-2 rounded-full transition-all duration-500" style="width: {{ $goal->percent }}%;"></div>
              </div>
              @if($linkedAccount)
                <span class="text-[10px] text-slate-400 block"><i class="fa-solid fa-info-circle mr-1"></i> Tertaut dengan Rekening {{ $linkedAccount->name }}</span>
              @endif
            </div>
          @empty
            <div class="flex flex-col items-center justify-center text-center gap-2 py-6 text-slate-400 dark:text-slate-500">
                <i class="fa-solid fa-piggy-bank text-2xl"></i>
                <p class="text-xs">Belum ada target impian yang dibuat.</p>
            </div>
          @endforelse
        </div>
      </div>

    </section>

  </main>



  <script>
    // ==========================================
    // FILTER TANGGAL (Flatpickr - Range Picker)
    // ==========================================
    flatpickr("#filter_date", {
    mode: "range",
    dateFormat: "Y-m-d",
    altInput: true,
    altFormat: "d M Y",
    locale: {
      rangeSeparator: " s/d "
    },
    defaultDate: "{{ request('date_range') }}", // Menjaga value tetap terisi setelah reload halaman
    onChange: function(selectedDates, dateStr) {
      // Form hanya akan disubmit otomatis jika user sudah memilih kedua tanggal (start & end)
      if (selectedDates.length === 2) {
        document.getElementById('form_filter_date').submit();
      }
    }
  });
  </script>
  @endsection