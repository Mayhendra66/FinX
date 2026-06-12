<!DOCTYPE html>
<html class="dark" lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Atur Ulang Kata Sandi - KeuanganKU</title>
    
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet"/>
    
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    "colors": {
                        "tertiary-fixed": "#e2e2e9",
                        "secondary-container": "#464749",
                        "on-tertiary-fixed": "#191c20",
                        "surface-dim": "#121414",
                        "outline": "#8d90a2",
                        "on-tertiary-fixed-variant": "#45474c",
                        "surface-container-low": "#1a1c1c",
                        "secondary": "#c7c6c9",
                        "inverse-primary": "#004ced",
                        "on-primary-container": "#dfe3ff",
                        "on-tertiary-container": "#e4e4eb",
                        "secondary-fixed-dim": "#c7c6c9",
                        "background": "#050505",
                        "surface-variant": "#333535",
                        "on-secondary-fixed-variant": "#464749",
                        "on-error": "#690005",
                        "outline-variant": "#1E2025",
                        "on-secondary-container": "#b6b5b7",
                        "primary-fixed": "#dde1ff",
                        "on-primary-fixed": "#001452",
                        "on-background": "#e2e2e2",
                        "error": "#ffb4ab",
                        "on-primary": "#ffffff",
                        "surface-container-lowest": "#0c0f0f",
                        "on-secondary-fixed": "#1b1c1e",
                        "tertiary": "#c6c6cd",
                        "surface-container-highest": "#333535",
                        "on-secondary": "#303033",
                        "on-primary-fixed-variant": "#0038b6",
                        "surface-container-high": "#282a2b",
                        "on-error-container": "#ffdad6",
                        "tertiary-fixed-dim": "#c6c6cd",
                        "inverse-surface": "#e2e2e2",
                        "surface-bright": "#37393a",
                        "inverse-on-surface": "#2f3131",
                        "on-tertiary": "#2e3036",
                        "error-container": "#93000a",
                        "on-surface-variant": "#c3c5d9",
                        "tertiary-container": "#64666c",
                        "on-surface": "#e2e2e2",
                        "primary-fixed-dim": "#b7c4ff",
                        "surface-tint": "#b7c4ff",
                        "secondary-fixed": "#e3e2e5",
                        "surface": "#0A0B0D",
                        "primary-container": "#0052FF",
                        "primary": "#0052FF",
                        "surface-container": "#1E2025"
                    },
                    "borderRadius": {
                        "DEFAULT": "0.125rem",
                        "lg": "0.25rem",
                        "xl": "0.5rem",
                        "full": "0.75rem"
                    },
                    "spacing": {
                        "md": "16px",
                        "lg": "24px",
                        "xl": "32px",
                        "sm": "12px",
                        "xs": "8px",
                        "base": "4px",
                        "gutter": "20px",
                        "margin-desktop": "64px",
                        "margin-mobile": "16px"
                    },
                    "fontFamily": {
                        "headline-sm": ["Inter"],
                        "headline-lg-mobile": ["Inter"],
                        "headline-md": ["Inter"],
                        "label-md": ["Inter"],
                        "body-md": ["Inter"],
                        "display-lg": ["Inter"],
                        "body-lg": ["Inter"],
                        "label-sm": ["Inter"]
                    },
                    "fontSize": {
                        "headline-sm": ["20px", { "lineHeight": "28px", "fontWeight": "600" }],
                        "headline-lg-mobile": ["24px", { "lineHeight": "32px", "fontWeight": "700" }],
                        "headline-md": ["24px", { "lineHeight": "32px", "letterSpacing": "-0.01em", "fontWeight": "600" }],
                        "label-md": ["12px", { "lineHeight": "16px", "letterSpacing": "0.02em", "fontWeight": "500" }],
                        "body-md": ["14px", { "lineHeight": "20px", "fontWeight": "400" }],
                        "display-lg": ["32px", { "lineHeight": "40px", "letterSpacing": "-0.02em", "fontWeight": "700" }],
                        "body-lg": ["16px", { "lineHeight": "24px", "fontWeight": "400" }],
                        "label-sm": ["11px", { "lineHeight": "14px", "fontWeight": "600" }]
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-background text-on-background min-h-screen flex items-center justify-center p-margin-mobile md:p-margin-desktop font-body-md selection:bg-primary-container selection:text-on-primary">

    <main class="w-full max-w-md bg-surface border border-outline-variant rounded-xl p-xl shadow-2xl relative overflow-hidden">
        <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-transparent via-primary to-transparent opacity-50"></div>
        
        <div class="flex flex-col items-center text-center mb-xl">
            <div class="w-16 h-16 mb-lg rounded-lg bg-surface-container flex items-center justify-center border border-outline-variant">
                <span class="material-symbols-outlined text-[28px] text-primary">key</span>
            </div>
            <h1 class="font-headline-md text-headline-md mb-xs md:font-display-lg md:text-display-lg text-on-surface">Atur Ulang Kata Sandi</h1>
            <p class="font-body-md text-body-md text-on-surface-variant max-w-sm mx-auto leading-relaxed">
                {{ __('Masukkan email Anda untuk mengatur ulang kata sandi.') }}
            </p>
        </div>

        @if (session('status'))
            <div class="flex items-center gap-2 bg-emerald-950/20 border border-emerald-500/20 text-emerald-400 p-sm rounded-lg text-xs mb-md">
                <span class="material-symbols-outlined text-[18px] text-emerald-500">check_circle</span>
                <span>{{ session('status') }}</span>
            </div>
        @endif

        @if ($errors->any())
            <div class="flex items-center gap-2 bg-red-950/20 border border-red-500/20 text-red-400 p-sm rounded-lg text-xs mb-md">
                <span class="material-symbols-outlined text-[18px] text-red-500">warning</span>
                <span>{{ $errors->first() }}</span>
            </div>
        @endif

        <section id="section_create" class="space-y-4">
            <form method="POST" action="{{ route('password.email') }}" class="space-y-md">
                @csrf

                <div>
                    <label class="block font-label-md text-label-md text-on-surface-variant mb-base" for="email">Alamat Email Pengguna</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-sm text-on-surface-variant">
                            <span class="material-symbols-outlined text-[18px]">mail</span>
                        </span>
                        <input class="w-full bg-background border border-outline-variant rounded-lg py-sm pl-[36px] pr-sm font-body-md text-on-surface focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-colors placeholder-on-surface-variant/50" 
                            id="email" 
                            type="email" 
                            name="email" 
                            value="{{ old('email') }}" 
                            placeholder="nama@email.com" 
                            required 
                            autofocus />
                    </div>
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <button class="w-full min-h-[40px] bg-primary-container text-on-primary font-label-md text-label-md rounded-lg py-sm hover:bg-on-primary-fixed-variant transition-colors flex items-center justify-center gap-xs mt-lg" type="submit">
                    {{ __('Email Password Reset Link') }}
                    <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                </button>
            </form>
        </section>

        <div class="relative flex items-center pt-lg mt-xl border-t border-outline-variant">
            <a class="w-full text-center font-label-md text-label-md text-primary hover:text-primary-fixed transition-colors flex items-center justify-center gap-xs" href="{{ route('login') }}">
                <span class="material-symbols-outlined text-[16px]">arrow_back</span>
                Kembali ke Layar Login / Masuk
            </a>
        </div>
    </main>
</body>
</html>