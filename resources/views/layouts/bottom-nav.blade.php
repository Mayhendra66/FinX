<nav class="md:hidden fixed bottom-0 left-0 w-full z-50 bg-surface-container/90 backdrop-blur-md border-t border-outline-variant/20 flex justify-around items-center h-[72px] px-2 pb-safe">
    
    <a class="flex flex-col items-center gap-1 p-2 {{ request()->routeIs('dashboard') ? 'text-primary dark:text-primary-fixed' : 'text-on-surface-variant dark:text-surface-variant' }}" href="{{ route('dashboard') }}">
        <span class="material-symbols-outlined {{ request()->routeIs('dashboard') ? 'fill' : '' }}" {{ request()->routeIs('dashboard') ? 'data-weight=fill' : '' }}>dashboard</span>
        <span class="text-[10px] font-medium">Dashboard</span>
    </a>

    <a class="flex flex-col items-center gap-1 p-2 {{ request()->routeIs('akun.index') ? 'text-primary dark:text-primary-fixed' : 'text-on-surface-variant dark:text-surface-variant' }}" href="{{ route('akun.index') }}">
        <span class="material-symbols-outlined {{ request()->routeIs('akun.index') ? 'fill' : '' }}" {{ request()->routeIs('akun.index') ? 'data-weight=fill' : '' }}>account_balance_wallet</span>
        <span class="text-[10px] font-medium">Dompet</span>
    </a>

    <div class="-mt-8">
        <button type="button" onclick="bukaModalCreate()" class="bg-primary-container text-white p-3 rounded-full shadow-lg flex items-center justify-center hover:bg-primary-container/90 transition-colors cursor-pointer">
            <span class="material-symbols-outlined text-[24px]">add</span>
        </button>
    </div>

    <a class="flex flex-col items-center gap-1 p-2 {{ request()->routeIs('analysis') ? 'text-primary dark:text-primary-fixed' : 'text-on-surface-variant dark:text-surface-variant' }}" href="#">
        <span class="material-symbols-outlined {{ request()->routeIs('analysis') ? 'fill' : '' }}" {{ request()->routeIs('analysis') ? 'data-weight=fill' : '' }}>analytics</span>
        <span class="text-[10px] font-medium">Analisis</span>
    </a>

    <a class="flex flex-col items-center gap-1 p-2 {{ request()->routeIs('transactions.index') ? 'text-primary dark:text-primary-fixed' : 'text-on-surface-variant dark:text-surface-variant' }}" href="{{ route('transactions.index') }}"> 
        <span class="material-symbols-outlined {{ request()->routeIs('transactions.index') ? 'fill' : '' }}" {{ request()->routeIs('transactions.index') ? 'data-weight=fill' : '' }}>receipt_long</span>
        <span class="text-[10px] font-medium">Transaksi</span>
    </a>
</nav>

{{-- ==============================
     MODAL CREATE GLOBAL
============================== --}}
<div id="modal_create" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center hidden p-4">
    <div class="bg-neutral-900 border border-neutral-800 rounded-xl w-full max-w-md p-6 relative shadow-2xl">
        <h2 class="text-base font-bold text-white mb-4 flex items-center gap-2">
            <span class="p-1.5 rounded bg-emerald-500/10 text-emerald-400">+</span>
            Tambah Data Transaksi
        </h2>

        <form id="form_add_transaction" class="space-y-4 text-xs">
            @csrf

            <div>
                <label class="block text-neutral-300 font-semibold mb-1">Tipe</label>
                <div class="grid grid-cols-2 gap-2">
                    <label class="border border-neutral-800 bg-neutral-950 p-2 text-center rounded-lg cursor-pointer block">
                        <input type="radio" name="type" id="create_type_income" value="income" class="mr-1" checked> Pemasukan
                    </label>
                    <label class="border border-neutral-800 bg-neutral-950 p-2 text-center rounded-lg cursor-pointer block">
                        <input type="radio" name="type" id="create_type_expense" value="expense" class="mr-1"> Pengeluaran
                    </label>
                </div>
            </div>

            <div>
                <label class="block text-neutral-300 font-semibold mb-1">Pilih Rekening Dompet</label>
                <select name="account_id" required class="w-full bg-neutral-950 border border-neutral-800 rounded-lg p-2.5 text-white">
                    @if(isset($accounts))
                        @foreach($accounts as $account)
                            <option value="{{ $account->id }}">{{ $account->name }}</option>
                        @endforeach
                    @endif
                </select>
            </div>

            <div>
                <label class="block text-neutral-300 font-semibold mb-1">Jumlah Nominal (Rupiah)</label>
                <input type="text" id="create_amount" name="amount" required placeholder="Contoh: Rp 150.000" oninput="formatIDR(this)" class="w-full bg-neutral-950 border border-neutral-800 rounded-lg p-2.5 text-white">
            </div>

            <div>
                <label class="block text-neutral-300 font-semibold mb-1">Kategori</label>
                <select id="create_category" name="category_id" required class="w-full bg-neutral-950 border border-neutral-800 rounded-lg p-2.5 text-white">
                </select>
            </div>

            <div>
                <label class="block text-neutral-300 font-semibold mb-1">Tanggal Transaksi</label>
                <input type="date" name="transaction_date" required value="{{ date('Y-m-d') }}" class="w-full bg-neutral-950 border border-neutral-800 rounded-lg p-2.5 text-white">
            </div>

            <div>
                <label class="block text-neutral-300 font-semibold mb-1">Deskripsi / Catatan</label>
                <textarea name="note" placeholder="Tulis catatan di sini..." class="w-full bg-neutral-950 border border-neutral-800 rounded-lg p-2.5 text-white h-16"></textarea>
            </div>

            <div class="flex justify-end gap-2 pt-2">
                <button type="button" onclick="tutupModalCreate()" class="cursor-pointer px-4 py-2 rounded bg-neutral-800 hover:bg-neutral-700 text-neutral-300">Batal</button>
                <button type="button" onclick="submitCreate()" class="cursor-pointer px-4 py-2 rounded bg-blue-600 hover:bg-blue-500 text-white font-bold">Simpan Transaksi</button>
            </div>
        </form>
    </div>
</div>

<script>
// 1. Inisialisasi Data Kategori Global dari Blade
const globalCategories = @json($categories ?? []);

// 2. Fungsi Render Kategori Berdasarkan Tipe
function renderCreateCategory() {
    const categorySelect = document.getElementById('create_category');
    if (!categorySelect) return;

    const selectedType = document.querySelector('input[name="type"]:checked')?.value || 'income';
    categorySelect.innerHTML = '';

    const filteredCategories = globalCategories.filter(category => category.type === selectedType);

    if (filteredCategories.length === 0) {
        const option = document.createElement('option');
        option.value = '';
        option.textContent = '-- Tidak ada kategori --';
        categorySelect.appendChild(option);
        return;
    }

    filteredCategories.forEach(category => {
        const option = document.createElement('option');
        option.value = category.id;
        option.textContent = category.name;
        categorySelect.appendChild(option);
    });
}

// 3. Event Listener untuk Perubahan Tipe Radio Button
document.addEventListener('DOMContentLoaded', function() {
    const typeRadios = document.querySelectorAll('input[name="type"]');
    typeRadios.forEach(radio => {
        radio.addEventListener('change', renderCreateCategory);
    });
    
    // Auto-display session success dari backend jika ada redirect murni
    @if(session('success'))
        Swal.fire({
            title: 'Berhasil!',
            text: "{{ session('success') }}",
            icon: 'success',
            background: '#17181c',
            color: '#fff',
            confirmButtonColor: '#0052ff',
            customClass: { popup: 'border border-neutral-800 rounded-xl' }
        });
    @endif
});

function bukaModalCreate() {
    const modal = document.getElementById('modal_create');
    if (modal) {
        modal.classList.remove('hidden');
        renderCreateCategory();
    }
}

function tutupModalCreate() {
    const modal = document.getElementById('modal_create');
    if (modal) {
        modal.classList.add('hidden');
        document.getElementById('form_add_transaction').reset();
    }
}

function formatIDR(input) {
    let value = input.value.replace(/[^,\d]/g, '').toString();
    let split = value.split(',');
    let sisa = split[0].length % 3;
    let rupiah = split[0].substr(0, sisa);
    let ribuan = split[0].substr(sisa).match(/\d{3}/gi);

    if (ribuan) {
        let separator = sisa ? '.' : '';
        rupiah += separator + ribuan.join('.');
    }

    rupiah = split[1] !== undefined ? rupiah + ',' + split[1] : rupiah;
    input.value = value ? 'Rp ' + rupiah : '';
}

// ==============================
// 4. SUBMIT CREATE (TAMBAH DATA)
// ==============================
async function submitCreate() {
    const form = document.getElementById('form_add_transaction');
    if (!form.checkValidity()) {
        form.reportValidity();
        return;
    }

    // Konfirmasi SweetAlert sebelum submit
    const konfirmasi = await Swal.fire({
        title: 'Simpan Transaksi?',
        text: 'Pastikan semua data sudah benar sebelum disimpan.',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#0052ff',
        cancelButtonColor: '#374151',
        confirmButtonText: 'Ya, Simpan!',
        cancelButtonText: 'Cek Lagi',
        background: '#17181c',
        color: '#fff',
        customClass: { popup: 'border border-neutral-800 rounded-xl' }
    });
    if (!konfirmasi.isConfirmed) return;

    const csrf = form.querySelector('[name="_token"]').value;
    const formData = new FormData(form);
    
    // Bersihkan format rupiah ke angka murni
    let amountRaw = formData.get('amount') || '';
    let amountClean = amountRaw.replace(/[^0-9]/g, '');
    formData.set('amount', amountClean);

    try {
        const response = await fetch("{{ route('transactionsmobile.store') }}", {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrf,
                'Accept': 'application/json',
            },
            body: formData,
        });

        const data = await response.json();
        tutupModalCreate();

        if (response.ok) {
            Swal.fire({
                title: 'Transaksi Ditambahkan!',
                text: data.message ?? 'Data transaksi berhasil tersimpan.',
                icon: 'success',
                background: '#17181c',
                color: '#fff',
                confirmButtonColor: '#0052ff',
                customClass: { popup: 'border border-neutral-800 rounded-xl' }
            }).then(() => window.location.reload());
        } else {
            const errMsg = data.errors
                ? Object.values(data.errors).flat().join('\n')
                : (data.message ?? 'Terjadi kesalahan, coba lagi.');
            Swal.fire({
                title: 'Gagal Menyimpan!',
                text: errMsg,
                icon: 'error',
                background: '#17181c',
                color: '#fff',
                confirmButtonColor: '#ef4444',
            });
        }
    } catch (e) {
        console.error(e);
        Swal.fire({ title: 'Error!', text: 'Request gagal: ' + e.message, icon: 'error', background: '#17181c', color: '#fff' });
    }
}

// ==============================
// 5. MODAL UPDATE
// ==============================
function bukaModalUpdate(id, desc, categoryId, accountId, amount, type, createdAt) {
    const now = new Date();
    const created = new Date(createdAt);
    const diffJam = (now - created) / (1000 * 60 * 60);

    if (diffJam > 24) {
        Swal.fire({
            title: 'Tidak Dapat Diedit!',
            html: `
                <p style="font-size:13px; color:#d1d5db; line-height:1.6">
                    Mohon lakukan <strong style="color:#fff">pengecekan ulang</strong> terlebih dahulu.<br><br>
                    Transaksi ini <strong style="color:#f87171">tidak dapat diperbarui</strong> karena sudah melewati batas waktu <strong style="color:#fff">24 jam</strong> sejak dicatat.
                </p>
            `,
            icon: 'warning',
            background: '#17181c',
            color: '#fff',
            confirmButtonColor: '#0052ff',
            confirmButtonText: 'Mengerti',
            customClass: { popup: 'border border-neutral-800 rounded-xl' }
        });
        return;
    }

    document.getElementById('edit_id').value = id;
    document.getElementById('edit_description').value = desc;
    document.getElementById('edit_wallet').value = accountId;
    document.getElementById('edit_amount').value = amount;

    document.getElementById('edit_type_income').checked = (type === 'income');
    document.getElementById('edit_type_expense').checked = (type === 'expense');

    if (typeof renderCategoryOptions === "function") {
        renderCategoryOptions(document.getElementById('edit_category'), type, categoryId);
    }

    document.getElementById('modal_update').classList.remove('hidden');
}

function tutupModalUpdate() {
    document.getElementById('modal_update').classList.add('hidden');
}

async function submitUpdate() {
    const form = document.getElementById('form_edit_transaction');
    if (!form.checkValidity()) {
        form.reportValidity();
        return;
    }

    const konfirmasi = await Swal.fire({
        title: 'Simpan Perubahan?',
        text: 'Pastikan data yang diubah sudah benar sebelum diperbarui.',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#0052ff',
        cancelButtonColor: '#374151',
        confirmButtonText: 'Ya, Perbarui!',
        cancelButtonText: 'Cek Lagi',
        background: '#17181c',
        color: '#fff',
        customClass: { popup: 'border border-neutral-800 rounded-xl' }
    });
    if (!konfirmasi.isConfirmed) return;

    const id = document.getElementById('edit_id').value;
    const csrf = form.querySelector('[name="_token"]').value;
    const formData = new FormData(form);

    formData.append('_method', 'PUT');

    // Bersihkan format rupiah pada input edit jika ada formatnya
    let amountInput = document.getElementById('edit_amount');
    if(amountInput) {
        let amountClean = amountInput.value.replace(/[^0-9]/g, '');
        formData.set('amount', amountClean);
    }

    try {
        const response = await fetch(`/transactionsmobile/${id}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrf,
                'Accept': 'application/json',
            },
            body: formData,
        });

        const data = await response.json();
        tutupModalUpdate();

        if (response.ok) {
            Swal.fire({
                title: 'Transaksi Diperbarui!',
                text: data.message ?? 'Perubahan berhasil disimpan.',
                icon: 'success',
                background: '#17181c',
                color: '#fff',
                confirmButtonColor: '#0052ff',
                customClass: { popup: 'border border-neutral-800 rounded-xl' }
            }).then(() => window.location.reload());
        } else {
            const errMsg = data.errors
                ? Object.values(data.errors).flat().join('\n')
                : (data.message ?? 'Terjadi kesalahan mendaftarkan perubahan.');
            Swal.fire({
                title: 'Gagal Menyimpan!',
                text: errMsg,
                icon: 'error',
                background: '#17181c',
                color: '#fff',
                confirmButtonColor: '#ef4444',
            });
        }
    } catch (e) {
        console.error(e);
        Swal.fire({ title: 'Error!', text: 'Request gagal: ' + e.message, icon: 'error', background: '#17181c', color: '#fff' });
    }
}

// ==============================
// 6. HAPUS TRANSAKSI (AJAX FETCH)
// ==============================
async function hapusTransaksi(id) {
    const konfirmasi = await Swal.fire({
        title: 'Apakah Anda Yakin?',
        text: 'Transaksi ini akan dihapus permanen dari rekening bersangkutan.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#374151',
        confirmButtonText: 'Ya, Hapus Saja!',
        cancelButtonText: 'Batal',
        background: '#17181c',
        color: '#fff',
        customClass: { popup: 'border border-neutral-800 rounded-xl' }
    });

    if (!konfirmasi.isConfirmed) return;

    // Ambil token CSRF global dari form mana saja yang tersedia di DOM
    const token = document.querySelector('[name="_token"]')?.value;

    try {
        const response = await fetch(`/transactionsmobile/${id}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': token,
                'X-HTTP-Method-Override': 'DELETE', 
                'Accept': 'application/json',
            }
        });

        if (response.ok) {
            await Swal.fire({
                title: 'Berhasil Dihapus!',
                text: 'Transaksi berhasil dihapus dari sistem.',
                icon: 'success',
                background: '#17181c',
                color: '#fff',
                confirmButtonColor: '#0052ff',
                customClass: { popup: 'border border-neutral-800 rounded-xl' }
            });
            window.location.reload();
        } else {
            Swal.fire({
                title: 'Gagal Menghapus!',
                text: 'Gagal menghapus transaksi dari server.',
                icon: 'error',
                background: '#17181c',
                color: '#fff',
                confirmButtonColor: '#ef4444',
            });
        }
    } catch (e) {
        console.error(e);
        Swal.fire({ title: 'Error!', text: 'Request gagal: ' + e.message, icon: 'error', background: '#17181c', color: '#fff' });
    }
}
</script>