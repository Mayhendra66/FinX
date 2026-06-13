<div 
  id="sidebar_backdrop" 
  onclick="toggleSidebar()" 
  class="fixed inset-0 bg-black/50 z-40 hidden md:hidden transition-opacity duration-300"
></div>

<nav class="md:hidden h-[64px] w-full sticky top-0 z-40 bg-white/80 dark:bg-surface-container/80 backdrop-blur-3xl border-b border-white/20 dark:border-outline-variant/20 shadow-xl flex justify-between items-center px-gutter">
  <div class="flex items-center gap-sm">
    <img alt="KeuanganKU Logo" class="h-8 w-8 object-contain" src="https://lh3.googleusercontent.com/aida-public/AB6AXuDEU-7ZLqkpZruJzvNt2KZO_Z2F3hMj-AZNNoHIshVHCh3xpH8Wz_aWTq-4iTTkqRX4ymVP84mcOqnmrcpyhn1EMQ8U265SbDBwFkfCx6QmaWeSNQxw_Y0wbNCZNdzXtq2KnIx2VL-yDMykwm9MFsEwrLeEzQ__nwDygrKltsE5QGyGvpW3-QPzYKy3GnPnwLccjP2_vP5bvIb8n1hDOsfXz3OiI6l43PM1saTWr1QBNpccLznEAwswB7hExTaOMI0IsRifLHTAravl" />
    <span class="font-headline-md text-headline-md font-bold text-primary dark:text-primary-fixed">KeuanganKU</span>
  </div>
  <div class="flex items-center gap-xs">
    <button class="p-2 text-on-surface-variant dark:text-surface-variant hover:bg-white/40 dark:hover:bg-white/10 transition-all duration-200 rounded-full">
      <span class="material-symbols-outlined">notifications</span>
    </button>
    <button onclick="toggleSidebar()" class="p-2 text-on-surface-variant dark:text-surface-variant hover:bg-white/40 dark:hover:bg-white/10 transition-all duration-200 rounded-full">
      <span class="material-symbols-outlined">menu</span>
    </button>
  </div>
</nav>

<aside 
  id="desktop_sidebar"
  class="flex flex-col py-6 gap-6 fixed left-0 top-0 h-full w-[240px] z-50 bg-[#0c0f0f]/95 backdrop-blur-xl border-r border-white/10 transition-transform duration-300 transform -translate-x-full md:translate-x-0"
>
  <div class="px-6 flex items-center gap-3 mb-2 shrink-0">
    <img alt="KeuanganKU Logo" class="h-10 w-10 object-contain rounded-lg" src="https://lh3.googleusercontent.com/aida-public/AB6AXuDEU-7ZLqkpZruJzvNt2KZO_Z2F3hMj-AZNNoHIshVHCh3xpH8Wz_aWTq-4iTTkqRX4ymVP84mcOqnmrcpyhn1EMQ8U265SbDBwFkfCx6QmaWeSNQxw_Y0wbNCZNdzXtq2KnIx2VL-yDMykwm9MFsEwrLeEzQ__nwDygrKltsE5QGyGvpW3-QPzYKy3GnPnwLccjP2_vP5bvIb8n1hDOsfXz3OiI6l43PM1saTWr1QBNpccLznEAwswB7hExTaOMI0IsRifLHTAravl"/>
    <div>
      <h1 class="font-bold text-base text-[#b7c4ff] leading-none">KeuanganKU</h1>
      <span class="text-[11px] font-semibold text-[#c3c5d9] block mt-1">Personal Finance</span>
    </div>
  </div>

  <div class="px-6 py-3 border-y border-white/5 flex items-center gap-3 shrink-0">
    <div class="w-9 h-9 bg-[#0052ff]/20 text-[#b7c4ff] rounded-xl flex items-center justify-center font-bold text-sm shrink-0 border border-[#0052ff]/30">
      {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
    </div>
    <div class="flex flex-col min-w-0 flex-1">
      <span class="text-xs font-bold text-[#e1e2e6] truncate" title="{{ auth()->user()->name }}">{{ auth()->user()->name }}</span>
      <span class="text-[10px] text-[#c3c5d9]/50 truncate" title="{{ auth()->user()->email }}">{{ auth()->user()->email }}</span>
    </div>
  </div>

  <nav id="sidebar_nav" class="flex-1 px-3 space-y-1 overflow-y-auto scrollbar-thin pr-1.5">
    <a id="menu_item_dashboard" href="{{ route('dashboard') }}" class="w-full flex items-center gap-3 px-4 py-2.5 rounded-lg transition-all duration-200 font-medium text-sm border-l-4 {{ request()->routeIs('dashboard') ? 'text-[#b7c4ff] bg-white/5 border-[#0052FF]' : 'border-transparent text-[#c3c5d9]/60 hover:bg-white/5 hover:text-[#e2e2e2]' }}">
      <svg class="w-5 h-5 {{ request()->routeIs('dashboard') ? 'text-[#0052FF]' : 'text-current' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
        <rect x="3" y="3" width="7" height="9" rx="1"></rect>
        <rect x="14" y="3" width="7" height="5" rx="1"></rect>
        <rect x="14" y="12" width="7" height="9" rx="1"></rect>
        <rect x="3" y="16" width="7" height="5" rx="1"></rect>
      </svg>
      <span>Dashboard</span>
    </a>

    <a id="menu_item_dompet" href="{{ route('akun.index') }}" class="w-full flex items-center gap-3 px-4 py-2.5 rounded-lg transition-all duration-200 font-medium text-sm border-l-4 {{ request()->routeIs('akun.index') ? 'text-[#b7c4ff] bg-white/5 border-[#0052FF]' : 'border-transparent text-[#c3c5d9]/60 hover:bg-white/5 hover:text-[#e2e2e2]' }}">
      <svg class="w-5 h-5 {{ request()->routeIs('akun.index') ? 'text-[#0052FF]' : 'text-current' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
        <rect x="2" y="5" width="20" height="14" rx="2" ry="2"></rect>
        <line x1="2" y1="10" x2="22" y2="10"></line>
      </svg>
      <span>Dompet</span>
    </a>

    <a id="menu_item_analisis" href="#" class="w-full flex items-center gap-3 px-4 py-2.5 rounded-lg transition-all duration-200 font-medium text-sm border-l-4 {{ request()->routeIs('analisis.index') ? 'text-[#b7c4ff] bg-white/5 border-[#0052FF]' : 'border-transparent text-[#c3c5d9]/60 hover:bg-white/5 hover:text-[#e2e2e2]' }}">
      <svg class="w-5 h-5 {{ request()->routeIs('analisis.index') ? 'text-[#0052FF]' : 'text-current' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
        <line x1="18" y1="20" x2="18" y2="10"></line>
        <line x1="12" y1="20" x2="12" y2="4"></line>
        <line x1="6" y1="20" x2="6" y2="14"></line>
      </svg>
      <span>Analisis</span>
    </a>

    <a id="menu_item_transaksi" href="{{ route('transactions.index') }}" class="w-full flex items-center gap-3 px-4 py-2.5 rounded-lg transition-all duration-200 font-medium text-sm border-l-4 {{ request()->routeIs('transactions.index') ? 'text-[#b7c4ff] bg-white/5 border-[#0052FF]' : 'border-transparent text-[#c3c5d9]/60 hover:bg-white/5 hover:text-[#e2e2e2]' }}">
      <svg class="w-5 h-5 {{ request()->routeIs('transactions.index') ? 'text-[#0052FF]' : 'text-current' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
        <polyline points="14 2 14 8 20 8"></polyline>
        <line x1="16" y1="13" x2="8" y2="13"></line>
        <line x1="16" y1="17" x2="8" y2="17"></line>
        <polyline points="10 9 9 9 8 9"></polyline>
      </svg>
      <span>Transaksi</span>
    </a>

    <a id="menu_item_cicilan" href="{{ route('cicilan.index') }}" class="w-full flex items-center gap-3 px-4 py-2.5 rounded-lg transition-all duration-200 font-medium text-sm border-l-4 {{ request()->routeIs('cicilan.index') ? 'text-[#b7c4ff] bg-white/5 border-[#0052FF]' : 'border-transparent text-[#c3c5d9]/60 hover:bg-white/5 hover:text-[#e2e2e2]' }}">
      <svg class="w-5 h-5 {{ request()->routeIs('cicilan.index') ? 'text-[#0052FF]' : 'text-current' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
        <line x1="19" y1="5" x2="5" y2="19"></line>
        <circle cx="6.5" cy="6.5" r="2.5"></circle>
        <circle cx="17.5" cy="17.5" r="2.5"></circle>
      </svg>
      <span>Cicilan</span>
    </a>

    <a id="menu_item_saving_goals" href="{{ route('saving-goals.index') }}" class="w-full flex items-center gap-3 px-4 py-2.5 rounded-lg transition-all duration-200 font-semibold text-sm border-l-4 {{ request()->routeIs('saving-goals.index') ? 'text-[#b7c4ff] bg-white/5 border-[#0052FF]' : 'border-transparent text-[#c3c5d9]/60 hover:bg-white/5 hover:text-[#e2e2e2]' }}">
  <svg class="w-5 h-5 {{ request()->routeIs('saving-goals.index') ? 'text-[#0052FF]' : 'text-current' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
    <circle cx="12" cy="12" r="10"></circle>
    <circle cx="12" cy="12" r="6"></circle>
    <circle cx="12" cy="12" r="1").></circle>
  </svg>
  <span>Saving Goals</span>
</a>

    <a id="menu_item_kategori" href="{{ route('categories.index') }}" class="w-full flex items-center gap-3 px-4 py-2.5 rounded-lg transition-all duration-200 font-medium text-sm border-l-4 {{ request()->routeIs('categories.index') ? 'text-[#b7c4ff] bg-white/5 border-[#0052FF]' : 'border-transparent text-[#c3c5d9]/60 hover:bg-white/5 hover:text-[#e2e2e2]' }}">
      <svg class="w-5 h-5 {{ request()->routeIs('categories.index') ? 'text-[#0052FF]' : 'text-current' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
        <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path>
        <line x1="7" y1="7" x2="7.01" y2="7"></line>
      </svg>
      <span>Kategori</span>
    </a>

    <a id="menu_item_anggaran" href="{{ route('budgeting.index') }}" class="w-full flex items-center gap-3 px-4 py-2.5 rounded-lg transition-all duration-200 font-medium text-sm border-l-4 {{ request()->routeIs('budgeting.index') ? 'text-[#b7c4ff] bg-white/5 border-[#0052FF]' : 'border-transparent text-[#c3c5d9]/60 hover:bg-white/5 hover:text-[#e2e2e2]' }}">
  <svg class="w-5 h-5 {{ request()->routeIs('budgeting.index') ? 'text-[#0052FF]' : 'text-current' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
    <path d="M21.21 15.89A10 10 0 1 1 8 2.83"></path>
    <path d="M22 12A10 10 0 0 0 12 2v10z"></path>
  </svg>
  <span>Anggaran</span>
</a>

    <a id="menu_item_pengaturan" href="{{ route('profile.edit') }}" class="w-full flex items-center gap-3 px-4 py-2.5 rounded-lg transition-all duration-200 font-medium text-sm border-l-4 {{ request()->routeIs('profile.edit') ? 'text-[#b7c4ff] bg-white/5 border-[#0052FF]' : 'border-transparent text-[#c3c5d9]/60 hover:bg-white/5 hover:text-[#e2e2e2]' }}">
      <svg class="w-5 h-5 {{ request()->routeIs('profile.edit') ? 'text-[#0052FF]' : 'text-current' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
        <circle cx="12" cy="12" r="3"></circle>
        <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"></path>
      </svg>
      <span>Pengaturan</span>
    </a>
  </nav>

  <div class="px-6 mt-auto flex flex-col gap-4 shrink-0">
    <a id="add_transaction_btn" href="#" class="w-full bg-[#0052FF] text-white py-2.5 rounded-lg font-bold text-xs hover:bg-[#004CED] transition-all flex items-center justify-center gap-2 shadow-lg active:scale-[0.98]">
      <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
        <line x1="12" y1="5" x2="12" y2="19"></line>
        <line x1="5" y1="12" x2="19" y2="12"></line>
      </svg>
      <span>Tambah Transaksi</span>
    </a>

    <form method="POST" action="{{ route('logout') }}" class="w-full shrink-0">
      @csrf
      <button type="submit" class="w-full bg-transparent border border-red-500/20 text-red-400 py-2.5 rounded-lg font-bold text-xs hover:bg-red-500/10 transition-all flex items-center justify-center gap-2 active:scale-[0.98]">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
          <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
        </svg>
        <span>Logout</span>
      </button>
    </form>
  </div>
</aside>

<script>
  function toggleSidebar() {
    const sidebar = document.getElementById('desktop_sidebar');
    const backdrop = document.getElementById('sidebar_backdrop');
    
    // Toggle class translate untuk slide effect sidebar
    sidebar.classList.toggle('-translate-x-full');
    
    // Toggle class hidden untuk backdrop
    backdrop.classList.toggle('hidden');
  }
</script>