@extends('layouts.app')

@section('content')
  <main class="flex-1 md:ml-[240px] p-margin-mobile md:p-margin-desktop bg-grid-pattern min-h-screen flex flex-col gap-6">
    
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
      <div>
        <h2 class="text-2xl font-black text-slate-800 dark:text-white tracking-tight flex items-center gap-2">
          <span class="p-2 rounded-xl bg-blue-50 dark:bg-[#0052FF]/10 text-brand border border-blue-150 dark:border-[#0052FF]/20">
            <i data-lucide="tag" class="w-6 h-6"></i>
          </span>
          Kategori Transaksi
        </h2>
        <p class="text-slate-500 dark:text-slate-400 text-xs mt-1.5">
          Kelola & sesuaikan kategori pemasukan (income) dan pengeluaran (expense) Anda sesuai kebutuhan finansial.
        </p>
      </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
      
      <div 
        id="tab-income"
        onclick="switchTab('income')"
        class="p-5 rounded-xl border transition-all cursor-pointer relative overflow-hidden bg-white dark:bg-[#0A0B0D] border-emerald-500/40 shadow-sm"
      >
        <div class="flex items-center justify-between">
          <div>
            <p class="text-xs text-slate-500 dark:text-slate-400 font-semibold mb-1">Pendapatan (Income)</p>
            <h3 class="text-xl font-bold text-slate-800 dark:text-white">
              <span id="income-count">{{ $categories->where('type', 'income')->count() }}</span> Kategori
            </h3>
          </div>
          <div class="p-3 rounded-lg bg-emerald-500/10 text-emerald-600 dark:text-[#2bb673]">
            <i data-lucide="trending-up" class="w-5 h-5"></i>
          </div>
        </div>
        <div id="line-income" class="absolute bottom-0 left-0 w-full h-1 bg-emerald-500"></div>
      </div>

      <div 
        id="tab-expense"
        onclick="switchTab('expense')"
        class="p-5 rounded-xl border transition-all cursor-pointer relative overflow-hidden bg-white dark:bg-[#0A0B0D] border-slate-200 dark:border-[#1E2025] hover:bg-slate-50 dark:hover:bg-[#1E2025]/20"
      >
        <div class="flex items-center justify-between">
          <div>
            <p class="text-xs text-slate-500 dark:text-slate-400 font-semibold mb-1">Pengeluaran (Expense)</p>
            <h3 class="text-xl font-bold text-slate-800 dark:text-white">
              <span id="expense-count">{{ $categories->where('type', 'expense')->count() }}</span> Kategori
            </h3>
          </div>
          <div class="p-3 rounded-lg bg-slate-100 dark:bg-[#121414] text-slate-500 dark:text-slate-400">
            <i data-lucide="trending-down" class="w-5 h-5"></i>
          </div>
        </div>
        <div id="line-expense" class="absolute bottom-0 left-0 w-full h-1 bg-red-500 hidden"></div>
      </div>

    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
      
      <div class="lg:col-span-4 bg-white dark:bg-[#0A0B0D] border border-slate-200 dark:border-[#1E2025] rounded-xl p-6 shadow-sm">
        <h3 class="text-sm font-bold text-slate-800 dark:text-white mb-4 flex items-center gap-2">
          <i data-lucide="folder-plus" class="w-4 h-4 text-brand"></i>
          Tambah Kategori Baru
        </h3>
        
        <form action="{{ route('categories.store') }}" method="POST" id="form-add-category" class="space-y-4">
          @csrf
          
          <input type="hidden" name="type" id="category_type" value="{{ old('type', 'income') }}">
          <input type="hidden" name="icon" id="category_icon" value="tag">

          <div>
            <label class="text-[11px] font-bold text-slate-500 dark:text-slate-400 block mb-1">
              Grup Kategori
            </label>
            <div class="grid grid-cols-2 gap-2 p-1.5 bg-slate-50 dark:bg-black/30 border border-slate-200 dark:border-[#1E2025]/50 rounded-lg">
              <button
                type="button"
                id="btn-toggle-income"
                onclick="switchTab('income')"
                class="py-1.5 px-3 rounded-lg text-[11px] font-bold transition-all text-center cursor-pointer bg-emerald-500 text-white shadow-md"
              >
                Pendapatan
              </button>
              <button
                type="button"
                id="btn-toggle-expense"
                onclick="switchTab('expense')"
                class="py-1.5 px-3 rounded-lg text-[11px] font-bold transition-all text-center cursor-pointer text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-white"
              >
                Pengeluaran
              </button>
            </div>
          </div>

          <div>
            <label for="new_cat_input" class="text-[11px] font-bold text-slate-500 dark:text-slate-400 block mb-1">
              Nama Kategori
            </label>
            <input
              id="new_cat_input"
              name="name"
              type="text"
              value="{{ old('name') }}"
              placeholder="Contoh: Kesehatan, Freelance, Pajak"
              maxlength="25"
              required
              class="w-full bg-slate-50 dark:bg-[#121414] border border-slate-300 dark:border-[#434656]/50 rounded-lg px-3 py-2 text-xs text-slate-800 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:border-blue-500 transition-all font-medium"
            />
            @error('name')
              <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p>
            @enderror
          </div>

          <button
            type="button"
            onclick="confirmSaveCategory()"
            id="btn-submit"
            class="w-full flex items-center justify-center gap-1.5 py-2.5 rounded-lg text-xs font-bold text-white transition-all shadow-md active:scale-95 cursor-pointer bg-emerald-600 hover:bg-emerald-700"
          >
            <i data-lucide="plus" class="w-4 h-4"></i>
            Simpan Kategori
          </button>
        </form>

        <div class="mt-6 p-4 rounded-lg bg-blue-500/5 border border-blue-500/10 flex gap-3 text-[11px] text-slate-500 dark:text-slate-400 leading-relaxed">
          <i data-lucide="info" class="w-4 h-4 text-blue-400 shrink-0 mt-0.5"></i>
          <div>
            <span class="font-bold text-slate-800 dark:text-white block mb-0.5">Saran Penamaan</span>
            Gunakan nama kategori yang pendek dan spesifik agar mudah dipahami dalam grafik analitik bulanan Anda.
          </div>
        </div>
      </div>

      <div class="lg:col-span-8 bg-white dark:bg-[#0A0B0D] border border-slate-200 dark:border-[#1E2025] rounded-xl p-6 shadow-sm">
        <div class="flex items-center justify-between mb-4">
          <h3 class="text-sm font-bold text-slate-800 dark:text-white flex items-center gap-2">
            Daftar Kategori (<span id="active-list-title">Pemasukan</span>)
          </h3>
          <span class="text-[10px] font-semibold text-slate-500">
            <span id="active-list-count">0</span> Item Terdaftar
          </span>
        </div>

        <div id="categories-grid" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3">
          @foreach($categories as $category)
            <div data-type="{{ $category->type }}" class="category-item flex items-center justify-between p-3.5 rounded-lg bg-slate-50 dark:bg-[#121414]/40 border border-slate-200 dark:border-[#434656]/20 hover:border-slate-300 dark:hover:border-white/10 transition-all group">
              <div class="flex items-center gap-3 min-w-0">
                <div class="p-2 rounded-lg {{ $category->type === 'income' ? 'bg-emerald-500/10 text-emerald-500' : 'bg-red-500/10 text-red-500' }} shrink-0">
                  <i data-lucide="{{ $category->icon ?? 'tag' }}" class="w-4 h-4"></i>
                </div>
                <div class="min-w-0">
                  <p class="text-xs font-bold text-slate-700 dark:text-white truncate capitalize">{{ $category->name }}</p>
                  <p class="text-[9px] text-slate-500 dark:text-slate-400 mt-0.5 font-medium">Aktif</p>
                </div>
              </div>
              
              <form action="{{ route('categories.destroy', $category->id) }}" method="POST" id="delete-form-{{ $category->id }}">
                @csrf
                @method('DELETE')
                <button
                  type="button"
                  onclick="confirmDelete('{{ $category->id }}', '{{ $category->name }}')"
                  class="p-1.5 rounded-lg bg-red-500/10 text-red-500 hover:bg-red-500/20 opacity-60 hover:opacity-100 transition-all cursor-pointer shrink-0 ml-2"
                  title="Hapus kategori {{ $category->name }}"
                >
                  <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                </button>
              </form>
            </div>
          @endforeach
        </div>

        <div id="empty-state" class="hidden text-center py-12 rounded-lg border border-dashed border-slate-200 dark:border-slate-800">
          <i data-lucide="tag" class="w-8 h-8 text-slate-400 dark:text-slate-600 mx-auto mb-2 opacity-55"></i>
          <p class="text-slate-500 dark:text-slate-400 text-xs font-semibold">Belum Ada Kategori</p>
          <p class="text-[10px] text-slate-400 dark:text-slate-500 mt-1">Gunakan formulir disamping untuk membuat kategori baru.</p>
        </div>
      </div>

    </div>

    <div class="p-4 rounded-lg bg-blue-500/5 border border-blue-500/10 flex gap-3 text-xs text-slate-500 dark:text-slate-400 leading-relaxed">
      <i data-lucide="info" class="w-5 h-5 text-blue-400 shrink-0 mt-0.5"></i>
      <div>
        <span class="font-bold text-slate-800 dark:text-white block mb-0.5">Sistem Kategorisasi Cerdas</span>
        Setiap transaksi yang Anda tambahkan dikelompokkan berdasarkan kategori ini untuk menghasilkan bagan dan analisis pengeluaran yang mendalam di halaman Analisis dan Dashboard utama.
      </div>
    </div>
</main>
@endsection

@if(session('success'))
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      Swal.fire({
        title: 'Berhasil!',
        text: {!! json_encode(session('success')) !!}, // Perbaikan di baris ini
        icon: 'success',
        showConfirmButton: false,
        timer: 2000,
        background: '#13141b',
        color: '#f1f5f9'
      });
    });
  </script>
@endif
<script>
  let activeTab = "{{ old('type', 'income') }}"; 

  document.addEventListener('DOMContentLoaded', () => {
    switchTab(activeTab);
    lucide.createIcons();
  });

  function switchTab(tab) {
    activeTab = tab;
    document.getElementById('category_type').value = tab;
    
    const tabIncome = document.getElementById('tab-income');
    const tabExpense = document.getElementById('tab-expense');
    const lineIncome = document.getElementById('line-income');
    const lineExpense = document.getElementById('line-expense');
    const btnIncome = document.getElementById('btn-toggle-income');
    const btnExpense = document.getElementById('btn-toggle-expense');
    const btnSubmit = document.getElementById('btn-submit');

    const items = document.querySelectorAll('.category-item');
    let visibleCount = 0;

    items.forEach(item => {
      if (item.getAttribute('data-type') === tab) {
        item.classList.remove('hidden');
        visibleCount++;
      } else {
        item.classList.add('hidden');
      }
    });

    const emptyState = document.getElementById('empty-state');
    if (visibleCount === 0) {
      emptyState.classList.remove('hidden');
    } else {
      emptyState.classList.add('hidden');
    }

    document.getElementById('active-list-count').innerText = visibleCount;

    if (tab === 'income') {
      tabIncome.className = "p-5 rounded-xl border transition-all cursor-pointer relative overflow-hidden bg-white dark:bg-[#0A0B0D] border-emerald-500/40 shadow-sm";
      tabExpense.className = "p-5 rounded-xl border transition-all cursor-pointer relative overflow-hidden bg-white dark:bg-[#0A0B0D] border-slate-200 dark:border-[#1E2025] hover:bg-slate-50 dark:hover:bg-[#1E2025]/20";
      lineIncome.classList.remove('hidden');
      lineExpense.classList.add('hidden');
      btnIncome.className = "py-1.5 px-3 rounded-lg text-[11px] font-bold transition-all text-center cursor-pointer bg-emerald-500 text-white shadow-md";
      btnExpense.className = "py-1.5 px-3 rounded-lg text-[11px] font-bold transition-all text-center cursor-pointer text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-white";
      btnSubmit.className = "w-full flex items-center justify-center gap-1.5 py-2.5 rounded-lg text-xs font-bold text-white transition-all shadow-md active:scale-95 cursor-pointer bg-emerald-600 hover:bg-emerald-700";
      document.getElementById('active-list-title').innerText = "Pemasukan";
    } else {
      tabExpense.className = "p-5 rounded-xl border transition-all cursor-pointer relative overflow-hidden bg-white dark:bg-[#0A0B0D] border-red-500/40 shadow-sm";
      tabIncome.className = "p-5 rounded-xl border transition-all cursor-pointer relative overflow-hidden bg-white dark:bg-[#0A0B0D] border-slate-200 dark:border-[#1E2025] hover:bg-slate-50 dark:hover:bg-[#1E2025]/20";
      lineExpense.classList.remove('hidden');
      lineIncome.classList.add('hidden');
      btnExpense.className = "py-1.5 px-3 rounded-lg text-[11px] font-bold transition-all text-center cursor-pointer bg-red-500 text-white shadow-md";
      btnIncome.className = "py-1.5 px-3 rounded-lg text-[11px] font-bold transition-all text-center cursor-pointer text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-white";
      btnSubmit.className = "w-full flex items-center justify-center gap-1.5 py-2.5 rounded-lg text-xs font-bold text-white transition-all shadow-md active:scale-95 cursor-pointer bg-red-500 hover:bg-red-600";
      document.getElementById('active-list-title').innerText = "Pengeluaran";
    }
  }

  // Validasi & Konfirmasi Simpan Data Baru (Tengah & Tema Gelap)
  function confirmSaveCategory() {
    const inputNama = document.getElementById('new_cat_input');
    const namaKategori = inputNama.value.trim();

    if (!namaKategori) {
      inputNama.reportValidity();
      return;
    }

    Swal.fire({
      title: 'Konfirmasi Penyimpanan',
      text: `Apakah Anda yakin ingin menyimpan kategori "${namaKategori}"?`,
      icon: 'question',
      showCancelButton: true,
      confirmButtonText: 'Ya, Simpan',
      cancelButtonText: 'Batal',
      confirmButtonColor: activeTab === 'income' ? '#059669' : '#dc2626',
      cancelButtonColor: '#334155',
      background: '#13141b',
      color: '#f1f5f9'
    }).then((result) => {
      if (result.isConfirmed) {
        // PERBAIKAN: Form sekarang teridentifikasi dengan sukses dan disubmit secara native
        document.getElementById('form-add-category').submit();
      }
    });
  }

  function confirmDelete(id, name) {
    Swal.fire({
      title: 'Yakin Ingin Menghapus?',
      text: `Kategori "${name}" akan dihapus permanen dari sistem.`,
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Ya, Hapus!',
      cancelButtonText: 'Batal',
      confirmButtonColor: '#ef4444',
      cancelButtonColor: '#334155',
      background: '#13141b',
      color: '#f1f5f9'
    }).then((result) => {
      if (result.isConfirmed) {
        document.getElementById(`delete-form-${id}`).submit();
      }
    });
  }
</script>