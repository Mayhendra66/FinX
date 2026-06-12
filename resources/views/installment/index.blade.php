@extends('layouts.app')

@section('content')

  <main class="flex-1 md:ml-[240px] p-margin-mobile md:p-margin-desktop bg-grid-pattern min-h-screen">
    
    <header class="flex flex-col md:flex-row md:items-center justify-between gap-4">
      <div>
        <h2 class="text-2xl font-black text-white tracking-tight flex items-center gap-3">
          <span class="p-2 rounded-xl bg-[#0052FF]/10 text-[#b7c4ff] border border-[#0052FF]/20 flex items-center justify-center">
            <i data-lucide="calculator" class="w-6 h-6"></i>
          </span>
          Manajemen & Kalkulator Cicilan
        </h2>
        <p class="text-slate-400 text-xs mt-1.5 font-medium">
          Simulasikan pembayaran kredit flat Anda, hitung suku bunga, serta catat pelunasan berjalan dengan akurasi terpusat.
        </p>
      </div>
      
      <div class="flex items-center gap-3 bg-[#0A0B0D] border border-[#1E2025] px-4 py-2.5 rounded-xl self-start md:self-auto">
        <div class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></div>
        <span class="text-xs font-bold text-white tracking-wide">
          Total Aktif: <span id="active_tracker_count" class="text-[#0052ff] ml-1 font-black">{{ $installments->where('status', '!=', 'lunas')->count() }} Terpasang</span>
        </span>
      </div>
    </header>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start mt-6">
      
      <section id="form_simulator_section" class="lg:col-span-5 bg-[#0A0B0D] border border-[#1E2025] rounded-2xl p-5 md:p-6 shadow-xl space-y-5">
        <div>
          <h3 class="text-sm font-extrabold text-white flex items-center gap-2">
            <i data-lucide="banknote" class="w-4 h-4 text-[#0052ff]"></i>
            Simulator & Tambah Cicilan
          </h3>
          <p class="text-[10px] text-slate-400 mt-1 font-medium leading-relaxed">
            Gunakan formulir untuk mensimulasikan pokok dan bunga sebelum menyimpannya ke daftar pelacakan aktif Anda.
          </p>
        </div>

       <form id="cicilan_form" action="{{ route('cicilan.store') }}" method="POST" class="space-y-4">
          @csrf
          
          @if ($errors->any())
            <div class="p-3 bg-red-500/20 border border-red-500/50 rounded-lg text-xs text-red-400">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>• {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
          @endif
          
          <div>
            <label for="account_id" class="text-[11px] font-bold text-slate-400 block mb-1">
              Pilih Akun / Rekening
            </label>
          
            <select name="account_id" id="filter_wallet" required
              class="w-full bg-[#121414] border border-[#434656]/50 rounded-lg px-3 py-2 text-xs text-white focus:outline-none focus:border-[#0052ff] transition-all cursor-pointer font-medium">
                    <option value="" disabled selected>Pilih Rekening</option>
                    @foreach($accounts as $account)
                        <option value="{{ $account->id }}" {{ old('account_id') == $account->id ? 'selected' : '' }}>
                            {{ $account->name }}
                        </option>
                    @endforeach
                </select>
          </div>

          <div>
            <label for="name" class="text-[11px] font-bold text-slate-400 block mb-1">
              Keterangan / Nama Barang
            </label>
            <input
              id="name"
              name="name"
              type="text"
              required
              value="{{ old('name') }}"
              placeholder="Contoh: MacBook Pro 14 M3, KPR Rumah, Motor"
              class="w-full bg-[#121414] border border-[#434656]/50 rounded-lg px-3 py-2 text-xs text-white placeholder-slate-500 focus:outline-none focus:border-[#0052ff] transition-all font-medium"
            />
          </div>

          <div class="grid grid-cols-2 gap-3">
            <div>
              <label for="total_amount" class="text-[11px] font-bold text-slate-400 block mb-1">
                Harga Total / Total Amount (Rp)
              </label>
              <input
                id="total_amount"
                name="total_amount"
                type="number"
                required
                min="1000"
                value="{{ old('total_amount') }}"
                placeholder="Harga tunai"
                oninput="liveCalculate()"
                class="w-full bg-[#121414] border border-[#434656]/50 rounded-lg px-3 py-2 text-xs text-white placeholder-slate-500 focus:outline-none focus:border-[#0052ff] transition-all font-medium"
              />
            </div>

            <div>
              <label for="downPayment" class="text-[11px] font-bold text-slate-400 block mb-1">
                Uang Muka / DP (Rp)
              </label>
              <input
                id="downPayment"
                type="number"
                min="0"
                placeholder="Opsional (0)"
                oninput="liveCalculate()"
                class="w-full bg-[#121414] border border-[#434656]/50 rounded-lg px-3 py-2 text-xs text-white placeholder-slate-500 focus:outline-none focus:border-[#0052ff] transition-all font-medium"
              />
            </div>
          </div>

          <div class="grid grid-cols-3 gap-2">
            <div>
              <label for="interestRate" class="text-[11px] font-bold text-slate-400 block mb-1">
                Bunga (%/Thn)
              </label>
              <div class="relative">
                <input
                  id="interestRate"
                  type="number"
                  step="0.1"
                  min="0"
                  value="0"
                  oninput="liveCalculate()"
                  class="w-full bg-[#121414] border border-[#434656]/50 rounded-lg pl-2 pr-6 py-2 text-xs text-white focus:outline-none focus:border-[#0052ff] transition-all font-medium"
                />
                <span class="absolute right-2 top-2 text-slate-500 text-xs font-bold">%</span>
              </div>
            </div>

            <div>
              <label for="tenure_type" class="text-[11px] font-bold text-slate-400 block mb-1">
                Satuan Tenor
              </label>
              <select
                id="tenure_type"
                onchange="liveCalculate()"
                class="w-full bg-[#121414] border border-[#434656]/50 rounded-lg px-1 py-2 text-xs text-white focus:outline-none focus:border-[#0052ff] transition-all cursor-pointer font-medium"
              >
                <option value="bulan" selected>Bulan</option>
                <option value="tahun">Tahun</option>
              </select>
            </div>

            <div>
              <label for="tenure_value" class="text-[11px] font-bold text-slate-400 block mb-1">
                Durasi
              </label>
              <input
                id="tenure_value"
                type="number"
                min="1"
                value="12"
                oninput="liveCalculate()"
                class="w-full bg-[#121414] border border-[#434656]/50 rounded-lg px-2 py-2 text-xs text-white focus:outline-none focus:border-[#0052ff] transition-all font-medium"
              />
            </div>
          </div>

          <input type="hidden" id="total_months" name="total_months" value="12">
          <input type="hidden" id="monthly_amount" name="monthly_amount" value="0">

          <div class="grid grid-cols-2 gap-3">
            <div>
              <label for="start_date" class="text-[11px] font-bold text-slate-400 block mb-1">
                Tanggal Mulai
              </label>
              <input
                id="start_date"
                name="start_date"
                type="date"
                required
                value="{{ old('start_date', date('Y-m-d')) }}"
                class="w-full bg-[#121414] border border-[#434656]/50 rounded-lg px-3 py-2 text-xs text-white focus:outline-none focus:border-[#0052ff] transition-all font-medium"
              />
            </div>

            <div>
              <label for="paid_months" class="text-[11px] font-bold text-slate-400 block mb-1">
                Sudah Dibayar (Bulan)
              </label>
              <input
                id="paid_months"
                name="paid_months"
                type="number"
                min="0"
                value="{{ old('paid_months', 0) }}"
                class="w-full bg-[#121414] border border-[#434656]/50 rounded-lg px-3 py-2 text-xs text-white focus:outline-none focus:border-[#0052ff] transition-all font-medium"
              />
            </div>
          </div>

          <div class="flex items-center justify-between p-3 bg-black/20 rounded-xl border border-white/5">
            <span class="text-[11px] font-bold text-slate-400">Status Pembayaran Langsung Lunas?</span>
            <label class="relative inline-flex items-center cursor-pointer">
              <input type="checkbox" id="status_toggle" name="status" value="lunas" class="sr-only peer">
              <div class="w-9 h-5 bg-zinc-800 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-slate-400 after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-emerald-500 peer-checked:after:bg-white"></div>
            </label>
          </div>

          <div class="p-4 bg-black/40 rounded-xl border border-white/5 space-y-2 text-xs">
            <span class="text-[9px] uppercase tracking-wider font-extrabold text-slate-400 block mb-1">
              Live Preview Hasil Simulasi
            </span>
            <div class="flex justify-between items-center text-slate-400">
              <span>Total Bulan Terhitung:</span>
              <span id="preview_months_count" class="font-bold text-white font-mono">12 Bulan</span>
            </div>
            <div class="flex justify-between items-center text-slate-400">
              <span>Bunga Flat per Bulan:</span>
              <span id="preview_interest" class="font-bold text-red-400 font-mono">+ Rp 0</span>
            </div>
            <div class="flex justify-between items-center pt-2 border-t border-white/5">
              <span class="text-xs text-[#b7c4ff] font-bold">Cicilan Bulanan (monthly_amount):</span>
              <span id="preview_monthly" class="text-sm text-emerald-400 font-black font-mono">Rp 0</span>
            </div>
          </div>

          <button
            type="submit"
            class="w-full flex items-center justify-center gap-1.5 py-2.5 bg-[#0052ff] hover:bg-[#0047df] rounded-lg text-xs font-bold text-white transition-all shadow-md cursor-pointer active:scale-95"
          >
            <i data-lucide="plus" class="w-4 h-4"></i>
            Simpan Cicilan
          </button>
</form>
      </section>

      <section id="tracker_list_section" class="lg:col-span-7 bg-[#0A0B0D] border border-[#1E2025] rounded-2xl p-5 md:p-6 shadow-xl space-y-4">
        <div>
          <h3 class="text-sm font-extrabold text-white">Cicilan Aktif Anda</h3>
          <p class="text-[10px] text-slate-400 mt-0.5 font-medium">
            Daftar kewajiban angsuran bulanan yang ditarik secara dinamis
          </p>
        </div>

        <div id="installments_grid" class="space-y-4 max-h-[580px] overflow-y-auto pr-1">
          @forelse($installments as $installment)
            @php
              $progressPercentage = $installment->total_months > 0 ? round(($installment->paid_months / $installment->total_months) * 100) : 0;
              $remainingMonths = $installment->total_months - $installment->paid_months;
              $totalPaidNominal = $installment->monthly_amount * $installment->paid_months;
              $remainingNominal = ($installment->monthly_amount * $installment->total_months) - $totalPaidNominal;
            @endphp

            <div id="item_{{ $installment->id }}" class="p-4 rounded-xl border {{ $installment->status == 'lunas' ? 'border-emerald-500/20 bg-[#121414]/20' : 'border-[#434656]/20 bg-[#121414]/40' }} relative overflow-hidden">
              
              <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-3 relative z-10">
                <div>
                  <div class="flex items-center gap-2">
                    <h4 class="text-xs font-black text-white capitalize">{{ $installment->name }}</h4>
                    @if($installment->status == 'lunas' || $progressPercentage >= 100)
                      <span class="px-2 py-0.5 rounded text-[8px] font-bold bg-[#2bb673]/10 text-emerald-400 flex items-center gap-0.5">
                        <i data-lucide="check" class="w-2.5 h-2.5"></i> Lunas
                      </span>
                    @else
                      <span class="px-2 py-0.5 rounded text-[8px] font-bold bg-amber-500/10 text-amber-400">
                        Aktif ({{ $remainingMonths }} Bln Sisa)
                      </span>
                    @endif
                  </div>
                  <p class="text-[10px] text-slate-400 mt-1 flex items-center gap-1">
                    <i data-lucide="calendar" class="w-3.5 h-3.5 text-[#0052ff]"></i>
                    Mulai: {{ $installment->start_date }} ({{ $installment->total_months }} bulan)
                  </p>
                </div>

                <div class="text-left sm:text-right">
                  <p class="text-[10px] text-slate-400">Angsuran Per Bulan</p>
                  <p class="text-xs font-black text-emerald-450">Rp {{ number_format($installment->monthly_amount, 0, ',', '.') }}</p>
                </div>
              </div>

              <div class="grid grid-cols-3 gap-2 bg-black/30 p-2.5 rounded-lg text-[9px] mb-3 relative z-10 font-mono">
                <div>
                  <span class="text-slate-400 block text-[8px]">Harga Total (DB):</span>
                  <span class="font-bold text-slate-300">Rp {{ number_format($installment->total_amount, 0, ',', '.') }}</span>
                </div>
                <div>
                  <span class="text-slate-400 block text-[8px]">Estimasi Dibayar:</span>
                  <span class="font-bold text-emerald-400">Rp {{ number_format($totalPaidNominal, 0, ',', '.') }}</span>
                </div>
                <div>
                  <span class="text-slate-400 block text-[8px]">Sisa Beban:</span>
                  <span class="font-bold text-red-400">Rp {{ number_format(max(0, $remainingNominal), 0, ',', '.') }}</span>
                </div>
              </div>

              <div class="space-y-1 mb-4 relative z-10">
                <div class="flex justify-between text-[9px] text-slate-400">
                  <span>Angsuran Ke-{{ $installment->paid_months }} / {{ $installment->total_months }} bln</span>
                  <span class="font-bold text-white">{{ $progressPercentage }}%</span>
                </div>
                <div class="w-full h-1.5 bg-black/40 rounded-full overflow-hidden">
                  <div class="h-full rounded-full {{ $installment->status == 'lunas' ? 'bg-emerald-500' : 'bg-[#0052ff]' }}" style="width: {{ min(100, $progressPercentage) }}%"></div>
                </div>
              </div>

              <div class="flex items-center justify-between pt-2 border-t border-[#1E2025] relative z-10">
                
                <form action="{{ route('cicilan.update', $installment->id) }}" method="POST">
                  @csrf
                  @method('PUT')
                  <input type="hidden" name="paid_months" value="{{ $installment->paid_months + 1 }}">
                  <button
                    type="submit"
                    class="px-3 py-1 bg-blue-500/10 hover:bg-[#0052ff] border border-[#0052ff]/30 text-white rounded-lg text-[9px] font-bold transition-all flex items-center gap-1 cursor-pointer"
                    {{ $installment->status == 'lunas' || $progressPercentage >= 100 ? 'disabled' : '' }}
                  >
                    <i data-lucide="check-circle" class="w-3.5 h-3.5"></i>
                    +1 Bulan Angsuran
                  </button>
                </form>

                <form action="{{ route('cicilan.destroy', $installment->id) }}" method="POST" class="inline-block">
                  @csrf
                  @method('DELETE')
                  <button
                    type="button" onclick="confirmDelete(this)"
                    class="p-1 px-2.5 border border-red-500/20 bg-red-500/10 text-red-400 hover:bg-red-500/20 rounded-lg transition-all text-[9px] font-bold flex items-center gap-1"
                  >
                    <i data-lucide="trash-2" class="w-3 h-3"></i>
                    Hapus
                  </button>
                </form>
              </div>
            </div>
          @empty
            <div class="text-center py-8 border border-dashed border-[#1E2025] rounded-xl text-slate-500 text-xs">
              Belum ada data cicilan yang tersimpan
            </div>
          @endforelse
        </div>
      </section>

    </div>
  </main>

<script src="{{ asset('js/formatIDR.js') }}"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function () {
        // Trigger SweetAlert jika ada error saldo dari controller
        @if(session('error_saldo'))
            Swal.fire({
                icon: 'error',
                title: 'Transaksi Gagal',
                text: "{{ session('error_saldo') }}",
                background: '#121414',
                color: '#fff',
                confirmButtonColor: '#0052ff'
            });
        @endif

        // Optional: Trigger SweetAlert jika sukses
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: "{{ session('success') }}",
                background: '#121414',
                color: '#fff',
                confirmButtonColor: '#0052ff'
            });
        @endif
    });

    function confirmDelete(button) {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Data cicilan ini akan dihapus secara permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444', // Merah untuk konfirmasi hapus
            cancelButtonColor: '#434656',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
            background: '#121414',
            color: '#fff'
        }).then((result) => {
            if (result.isConfirmed) {
                // Submit form terkait jika user menekan tombol "Ya, Hapus!"
                button.closest('form').submit();
            }
        });
    }
</script>
  <script>
    lucide.createIcons();

    function liveCalculate() {
      const priceVal = parseFloat(document.getElementById('total_amount').value) || 0;
      const dpVal = parseFloat(document.getElementById('downPayment').value) || 0;
      const interestVal = parseFloat(document.getElementById('interestRate').value) || 0;
      
      const tenureValue = parseInt(document.getElementById('tenure_value').value) || 1;
      const tenureType = document.getElementById('tenure_type').value;

      // Konversi Tenor jika memilih Tahun ke Bulan
      let totalMonths = tenureValue;
      if (tenureType === 'tahun') {
        totalMonths = tenureValue * 12;
      }

      // Set input hidden total_months untuk dikirim ke DB
      document.getElementById('total_months').value = totalMonths;
      document.getElementById('preview_months_count').innerText = totalMonths + ' Bulan';

      // Hitung Pokok Utang
      const principal = Math.max(0, priceVal - dpVal);

      // Kalkulasi Bunga Flat
      const totalInterest = principal * (interestVal / 100) * (totalMonths / 12);
      const monthlyInterest = totalMonths > 0 ? (totalInterest / totalMonths) : 0;
      document.getElementById('preview_interest').innerText = '+ Rp ' + Math.round(monthlyInterest).toLocaleString('id-ID');

      // Angsuran Bulanan Total (monthly_amount)
      const monthlyPrincipal = totalMonths > 0 ? (principal / totalMonths) : 0;
      const grandMonthly = Math.round(monthlyPrincipal + monthlyInterest);
      
      // Set input hidden monthly_amount untuk dikirim ke DB
      document.getElementById('monthly_amount').value = grandMonthly;
      document.getElementById('preview_monthly').innerText = 'Rp ' + grandMonthly.toLocaleString('id-ID');
    }

    // Jalankan kalkulasi awal saat halaman dimuat
    liveCalculate();
  </script>
@endsection