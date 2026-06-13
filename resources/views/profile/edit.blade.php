@extends('layouts.app')

@section('content')


    <main class="flex-1 md:ml-[240px] p-margin-mobile md:p-margin-desktop bg-grid-pattern min-h-screen">

        <header class="pb-5 border-b border-[#1E2025] flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-3xl font-black tracking-tight text-[#e2e2e2] mb-1">{{ __('Pengaturan Profil & Keamanan') }}</h2>
                <p class="text-sm text-[#c3c5d9]">Kelola informasi dasar akun KeuanganKU Anda serta pembaruan kata sandi di gawai lokal ini.</p>
            </div>

            <form method="POST" action="{{ route('logout') }}" class="shrink-0">
                @csrf
                <button type="submit" class="w-full sm:w-fit bg-[#121415] hover:bg-[#1E2025] border border-[#434656]/30 text-gray-300 px-4 py-2.5 rounded-xl text-xs font-black transition-all active:scale-95 flex items-center justify-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    <span>{{ __('Log Out') }}</span>
                </button>
            </form>
        </header>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 items-start mt-4">

            <section class="bg-[#0A0B0D] border border-[#1E2025] rounded-2xl p-6 relative overflow-hidden flex flex-col gap-6">
                <div class="absolute top-4 right-4 bg-[#0052ff]/10 text-[#b7c4ff] text-[10px] uppercase font-black tracking-widest px-2.5 py-1 rounded-md border border-[#0052ff]/30">
                    KeuanganKU
                </div>

                <header class="flex items-center gap-3 pb-4 border-b border-[#1E2025]">
                    <div class="p-2 bg-[#0052ff]/10 border border-[#0052ff]/20 rounded-lg text-[#b7c4ff]">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-extrabold text-sm text-white uppercase tracking-wider">Update Informasi</h3>
                        <p class="text-[10px] text-[#c3c5d9]/50">Perbarui rincian foto serta data dasar pengguna aplikasi Anda.</p>
                    </div>
                </header>

                <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="flex flex-col gap-6">
                    @csrf
                    @method('patch')

                    <div class="flex flex-col sm:flex-row items-center gap-5 p-4 bg-[#121415]/40 border border-[#1E2025] rounded-xl w-full">
                        <div class="relative shrink-0 group">
                            @if($user->photo)
                                <img src="{{ asset($user->photo) }}" class="w-20 h-20 rounded-2xl object-cover shadow-xl border border-[#1E2025]">
                                
                                <button type="button" id="btn-delete-photo" class="absolute -top-2 -right-2 bg-red-600 hover:bg-red-700 text-white p-1.5 rounded-lg shadow-lg cursor-pointer border border-[#0A0B0D] transition-transform hover:scale-105" title="Hapus Foto Profil">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            @else
                                @php
                                    $firstName = explode(' ', trim($user->name))[0] ?? 'KU';
                                    $initials = strtoupper(substr($firstName, 0, 2));
                                @endphp
                                <div class="w-20 h-20 rounded-2xl flex items-center justify-center font-extrabold text-2xl shadow-xl bg-gradient-to-tr from-[#7B2CBF] via-[#9D4EDD] to-[#E0AAFF] text-white">
                                    {{ $initials }}
                                </div>
                            @endif

                            <label for="photo-upload" class="absolute -bottom-2 -right-2 bg-[#0052ff] hover:bg-[#004ced] text-white p-2 rounded-lg shadow-lg cursor-pointer border border-[#0A0B0D] transition-transform hover:scale-105">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </label>
                            <input type="file" id="photo-upload" name="photo" accept="image/*" class="hidden">
                        </div>

                        <div class="flex flex-col items-center sm:items-start text-center sm:text-left gap-1">
                            <h4 class="text-sm font-black text-white">{{ $user->name }}</h4>
                            <span class="text-xs text-[#c3c5d9]/60 font-mono mb-2">{{ $user->email }}</span>
                            <span class="text-[10px] text-[#c3c5d9]/40 font-mono">Format yang diterima: JPEG / PNG</span>
                            @if ($errors->has('photo'))
                                <span class="text-red-500 text-[10px] mt-1">{{ $errors->first('photo') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="flex flex-col gap-1.5">
                            <label for="nama-lengkap" class="text-xs text-[#c3c5d9] font-semibold">Nama Lengkap</label>
                            <input type="text" id="nama-lengkap" name="name" value="{{ old('name', $user->name) }}" class="bg-[#121414] border border-[#434656]/50 rounded-lg px-3 py-2.5 text-xs text-white focus:outline-none focus:border-[#0052ff] font-medium" placeholder="Contoh: Budi Santoso" required autocomplete="name" />
                            @if ($errors->has('name'))
                                <span class="text-red-500 text-[10px] mt-1">{{ $errors->first('name') }}</span>
                            @endif
                        </div>

                        <div class="flex flex-col gap-1.5">
                            <label for="email" class="text-xs text-[#c3c5d9] font-semibold">Alamat Email</label>
                            <input type="text" id="email" name="email" value="{{ old('email', $user->email) }}" class="bg-[#121414] border border-[#434656]/50 rounded-lg px-3 py-2.5 text-xs text-white focus:outline-none focus:border-[#0052ff] font-medium" placeholder="email@domain.com" required autocomplete="username" />
                            @if ($errors->has('email'))
                                <span class="text-red-500 text-[10px] mt-1">{{ $errors->first('email') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="flex items-center gap-4 self-end mt-2">
                        @if (session('status') === 'profile-updated')
                            <p class="text-xs text-emerald-500 font-medium">{{ __('Tersimpan.') }}</p>
                        @endif

                        <button type="submit" class="w-full sm:w-fit bg-[#0052ff] hover:bg-[#004ced] text-white px-5 py-2.5 rounded-lg text-xs font-black transition-all active:scale-95 flex items-center justify-center gap-1.5">
                            <span>{{ __('Simpan Perubahan Informasi') }}</span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                        </button>
                    </div>
                </form>
            </section>

            <section class="bg-[#0A0B0D] border border-[#1E2025] rounded-2xl p-6 flex flex-col gap-6">
    <header class="flex items-center gap-3 pb-4 border-b border-[#1E2025]">
    <div class="p-2 bg-[#0052ff]/10 border border-[#0052ff]/20 rounded-lg text-[#b7c4ff]">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
            <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
        </svg>
    </div>
    <div>
        <h3 class="font-extrabold text-sm text-white uppercase tracking-wider">Update Password</h3>
        <p class="text-[10px] text-[#c3c5d9]/50">Konfigurasi ulang kata sandi otentikasi masuk ke platform Anda.</p>
    </div>
</header>

    <form method="post" action="{{ route('password.update') }}" class="flex flex-col gap-4">
        @csrf
        @method('put')

        {{-- INPUT CURRENT PASSWORD HANYA MUNCUL JIKA USER DAFTAR MANUAL --}}
        @if(!auth()->user()->google_id)
            <div class="flex flex-col gap-1.5">
                <label for="update_password_current_password" class="text-xs text-[#c3c5d9] font-semibold">Current password (password sekarang)</label>
                <div class="relative">
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-[#c3c5d9]/60">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </span>
                    <input type="password" id="update_password_current_password" name="current_password" class="w-full bg-[#121414] border border-[#434656]/50 rounded-lg pl-9 pr-4 py-2.5 text-xs text-white focus:outline-none focus:border-[#0052ff] font-mono" placeholder="Masukkan password yang aktif saat ini" required autocomplete="current-password" />
                </div>
                @if ($errors->updatePassword->has('current_password'))
                    <span class="text-red-500 text-[10px] mt-1">{{ $errors->updatePassword->first('current_password') }}</span>
                @endif
            </div>
        @endif

        <div class="flex flex-col gap-1.5">
            <label for="update_password_password" class="text-xs text-[#c3c5d9] font-semibold">New password</label>
            <div class="relative">
                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-[#c3c5d9]/60">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                </span>
                <input type="password" id="update_password_password" name="password" class="w-full bg-[#121414] border border-[#434656]/50 rounded-lg pl-9 pr-4 py-2.5 text-xs text-white focus:outline-none focus:border-[#0052ff] font-mono" placeholder="Buat password baru (minimum 8 karakter)" required autocomplete="new-password" />
            </div>
            @if ($errors->updatePassword->has('password'))
                <span class="text-red-500 text-[10px] mt-1">{{ $errors->updatePassword->first('password') }}</span>
            @endif
        </div>

        <div class="flex flex-col gap-1.5">
            <label for="update_password_password_confirmation" class="text-xs text-[#c3c5d9] font-semibold">Confirm password (password baru yang diulang)</label>
            <div class="relative">
                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-[#c3c5d9]/60">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                </span>
                <input type="password" id="update_password_password_confirmation" name="password_confirmation" class="w-full bg-[#121414] border border-[#434656]/50 rounded-lg pl-9 pr-4 py-2.5 text-xs text-white focus:outline-none focus:border-[#0052ff] font-mono" placeholder="Tulis ulang password baru" required autocomplete="new-password" />
            </div>
            @if ($errors->updatePassword->has('password_confirmation'))
                <span class="text-red-500 text-[10px] mt-1">{{ $errors->updatePassword->first('password_confirmation') }}</span>
            @endif
        </div>

        <div class="flex items-center gap-4 self-end mt-2">
            <button type="submit" class="w-full sm:w-fit bg-emerald-600 hover:bg-emerald-700 text-white px-5 py-2.5 rounded-lg text-xs font-black transition-all active:scale-95 flex items-center justify-center gap-1.5">
                <span>{{ __('Update dan Simpan Sandi') }}</span>
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </button>
        </div>
    </form>
</section>

        </div>

        <div class="mt-8 pt-8 border-t border-red-500/10 mb-6">
            <div class="bg-[#1C090A] border border-red-500/15 p-6 rounded-2xl w-full flex flex-col md:flex-row items-center justify-between gap-6">
                <div class="flex flex-col gap-1 text-center md:text-left">
                    <span class="text-red-400 font-extrabold text-xs uppercase tracking-widest block">ZONA BAHAYA</span>
                    <h4 class="text-white text-base font-extrabold">{{ __('Hapus Seluruh Data Pelacakan') }}</h4>
                    <p class="text-[#c3c5d9]/60 text-xs max-w-lg">Tindakan ini akan menghapus akun beserta seluruh data transaksi, anggaran, dan visual profil Anda secara permanen.</p>
                </div>

                <form method="post" action="{{ route('profile.destroy') }}" id="delete-account-form">
                    @csrf
                    @method('delete')
                    <input type="hidden" name="password" id="delete-password-payload">

                    <button type="button" id="btn-delete-account" class="bg-red-600 hover:bg-red-700 text-white font-black text-xs px-6 py-3.5 rounded-xl cursor-pointer transition-transform hover:scale-102 active:scale-95 shrink-0 flex items-center gap-2 uppercase tracking-wider">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        <span>{{ __('Delete Account') }}</span>
                    </button>
                </form>
            </div>
        </div>

        <div id="crop-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/80 backdrop-blur-sm p-4">
            <div class="bg-[#0A0B0D] border border-[#1E2025] rounded-2xl w-full max-w-md overflow-hidden flex flex-col shadow-2xl">
                <div class="p-4 border-b border-[#1E2025] flex justify-between items-center">
                    <h3 class="text-sm font-black text-white uppercase tracking-wider">Sesuaikan Foto Profil</h3>
                </div>
                <div class="p-4 flex items-center justify-center bg-[#121415]/50 max-h-[400px]">
                    <img id="crop-image" class="max-w-full max-h-[300px] block">
                </div>
                <div class="p-4 border-t border-[#1E2025] flex items-center justify-end gap-3 bg-[#121415]/20">
                    <button type="button" id="cancel-crop" class="px-4 py-2 rounded-lg text-xs font-bold text-gray-400 hover:bg-[#1E2025] transition-all">Batal</button>
                    <button type="button" id="save-crop" class="bg-[#0052ff] hover:bg-[#004ced] text-white px-4 py-2 rounded-lg text-xs font-black transition-all active:scale-95">Potong & Simpan</button>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('profile.photo.destroy') }}" id="delete-photo-form" class="hidden">
            @csrf
            @method('delete')
        </form>

    </main>

    <script>
        // --- LOGIC CROPPER ---
        const photoUpload = document.getElementById('photo-upload');
        const cropModal = document.getElementById('crop-modal');
        const cropImage = document.getElementById('crop-image');
        const cancelCrop = document.getElementById('cancel-crop');
        const saveCrop = document.getElementById('save-crop');
        let cropper;

        photoUpload.addEventListener('change', function(e) {
            const files = e.target.files;
            if (files && files.length > 0) {
                const file = files[0];
                const reader = new FileReader();
                reader.onload = function(event) {
                    cropImage.src = event.target.result;
                    cropModal.classList.remove('hidden');
                    cropModal.classList.add('flex');
                    if (cropper) cropper.destroy();
                    cropper = new Cropper(cropImage, {
                        aspectRatio: 1,
                        viewMode: 1,
                        dragMode: 'move',
                        background: false,
                        autoCropArea: 1
                    });
                };
                reader.readAsDataURL(file);
            }
        });

        cancelCrop.addEventListener('click', function() {
            cropModal.classList.add('hidden');
            cropModal.classList.remove('flex');
            photoUpload.value = '';
            if (cropper) cropper.destroy();
        });

        saveCrop.addEventListener('click', function() {
            if (!cropper) return;
            const canvas = cropper.getCroppedCanvas({
                width: 400,
                height: 400
            });
            canvas.toBlob(function(blob) {
                const croppedFile = new File([blob], "profile_cropped.png", {
                    type: "image/png"
                });
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(croppedFile);
                photoUpload.files = dataTransfer.files;
                cropModal.classList.add('hidden');
                cropModal.classList.remove('flex');
                cropper.destroy();
                photoUpload.form.submit();
            }, 'image/png');
        });

        // --- LOGIC SWEETALERT CONFIRMATION HAPUS FOTO ---
        const btnDeletePhoto = document.getElementById('btn-delete-photo');
        if (btnDeletePhoto) {
            btnDeletePhoto.addEventListener('click', function () {
                Swal.fire({
                    title: 'Hapus Foto Profil?',
                    text: "Foto profil akan dihapus dari server dan kembali menggunakan inisial nama Anda.",
                    icon: 'warning',
                    background: '#0A0B0D',
                    color: '#e2e2e2',
                    showCancelButton: true,
                    confirmButtonColor: '#dc2626',
                    cancelButtonColor: '#1E2025',
                    confirmButtonText: 'Ya, Hapus Foto',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('delete-photo-form').submit();
                    }
                });
            });
        }

        // Notifikasi Berhasil Hapus Foto
        @if (session('status') === 'photo-deleted')
            Swal.fire({
                icon: 'success',
                title: 'Foto Dihapus',
                text: 'Foto profil Anda berhasil dihapus secara permanen.',
                background: '#0A0B0D',
                color: '#e2e2e2',
                confirmButtonColor: '#0052ff'
            });
        @endif

        // --- LOGIC SWEETALERT PASSWORD UPDATE ---
        @if (session('status') === 'password-updated')
            Swal.fire({
                icon: 'success',
                title: 'Sandi Diperbarui',
                text: 'Kata sandi otentikasi akun Anda berhasil dikonfigurasi ulang.',
                background: '#0A0B0D',
                color: '#e2e2e2',
                confirmButtonColor: '#0052ff'
            });
        @endif

        // --- LOGIC SWEETALERT DELETE ACCOUNT FLOW WITH AJAX ---
        document.getElementById('btn-delete-account').addEventListener('click', async function() {
            const {
                value: passwordValues
            } = await Swal.fire({
                title: 'ZONA BAHAYA',
                text: 'Masukkan password Anda 2 kali untuk konfirmasi penghapusan permanen.',
                html: `
                    <div class="flex flex-col gap-3 text-left">
                        <input id="swal-password-1" type="password" placeholder="Masukkan Password Anda" class="w-full bg-[#121414] border border-[#434656]/50 rounded-lg px-3 py-2.5 text-xs text-white focus:outline-none focus:border-red-500 font-mono">
                        <input id="swal-password-2" type="password" placeholder="Ulangi Masukkan Password" class="w-full bg-[#121414] border border-[#434656]/50 rounded-lg px-3 py-2.5 text-xs text-white focus:outline-none focus:border-red-500 font-mono">
                    </div>
                `,
                background: '#0A0B0D',
                color: '#e2e2e2',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                confirmButtonText: 'HAPUS AKUN',
                cancelButtonText: 'BATAL',
                focusConfirm: false,
                preConfirm: () => {
                    const pass1 = document.getElementById('swal-password-1').value;
                    const pass2 = document.getElementById('swal-password-2').value;

                    if (!pass1 || !pass2) {
                        Swal.showValidationMessage('Kedua kolom password wajib diisi!');
                        return false;
                    }
                    if (pass1 !== pass2) {
                        Swal.showValidationMessage('Konfirmasi password tidak cocok!');
                        return false;
                    }
                    return pass1;
                }
            });

            if (passwordValues) {
                try {
                    const response = await fetch("{{ route('profile.destroy') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            _method: 'DELETE',
                            password: passwordValues
                        })
                    });

                    if (!response.ok) {
                        const errorData = await response.json();
                        throw new Error(errorData.message || 'Password yang Anda masukkan salah.');
                    }

                    let timerInterval;
                    let currentCount = 1;

                    await Swal.fire({
                        title: 'Memproses Penghapusan',
                        html: 'Akun akan dihapus <b id="countdown-num" class="text-red-500 font-black">1</b>',
                        timer: 5000,
                        background: '#0A0B0D',
                        color: '#e2e2e2',
                        showConfirmButton: false,
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        didOpen: () => {
                            const numEl = document.getElementById('countdown-num');
                            timerInterval = setInterval(() => {
                                currentCount++;
                                if (currentCount <= 5) {
                                    numEl.textContent = currentCount;
                                }
                            }, 1000);
                        },
                        willClose: () => {
                            clearInterval(timerInterval);
                        }
                    });

                    await Swal.fire({
                        icon: 'success',
                        title: 'Akun Terhapus',
                        text: 'Terima kasih telah menjadi pengguna KeuanganKu',
                        background: '#0A0B0D',
                        color: '#e2e2e2',
                        showConfirmButton: false,
                        timer: 3000,
                        allowOutsideClick: false,
                        allowEscapeKey: false
                    });

                    window.location.href = "{{ url('/') }}";

                } catch (error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal Menghapus Akun',
                        text: error.message,
                        background: '#0A0B0D',
                        color: '#e2e2e2',
                        confirmButtonColor: '#0052ff'
                    });
                }
            }
        });
    </script>
@endsection