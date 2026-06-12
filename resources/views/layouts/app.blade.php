<!DOCTYPE html>
<html class="dark" lang="id" style="">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>KeuanganKU Dashboard</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&amp;display=swap" rel="stylesheet" />
    <link
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap"
        rel="stylesheet" />
          <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont/dist/tabler-icons.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://unpkg.com/lucide@latest"></script>
      
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    "colors": {
                        "secondary-container": "#464749",
                        "primary": "#b7c4ff",
                        "on-error-container": "#ffdad6",
                        "surface-container": "#1e2020",
                        "on-tertiary-container": "#e4e4eb",
                        "inverse-on-surface": "#2f3131",
                        "tertiary-container": "#64666c",
                        "surface-container-lowest": "#0c0f0f",
                        "on-secondary": "#303033",
                        "on-error": "#690005",
                        "tertiary": "#c6c6cd",
                        "primary-fixed": "#dde1ff",
                        "on-background": "#e2e2e2",
                        "error-container": "#93000a",
                        "on-tertiary-fixed-variant": "#45474c",
                        "on-primary-container": "#dfe3ff",
                        "secondary": "#c7c6c9",
                        "surface-container-high": "#282a2b",
                        "surface-container-low": "#1a1c1c",
                        "error": "#ffb4ab",
                        "surface": "#121414",
                        "on-primary-fixed": "#001452",
                        "tertiary-fixed-dim": "#c6c6cd",
                        "secondary-fixed": "#e3e2e5",
                        "on-primary-fixed-variant": "#0038b6",
                        "on-secondary-container": "#b6b5b7",
                        "on-tertiary-fixed": "#191c20",
                        "surface-variant": "#333535",
                        "surface-bright": "#37393a",
                        "on-tertiary": "#2e3036",
                        "on-secondary-fixed-variant": "#464749",
                        "primary-container": "#0052ff",
                        "on-surface-variant": "#c3c5d9",
                        "on-primary": "#002682",
                        "on-secondary-fixed": "#1b1c1e",
                        "surface-tint": "#b7c4ff",
                        "surface-container-highest": "#333535",
                        "primary-fixed-dim": "#b7c4ff",
                        "outline-variant": "#434656",
                        "outline": "#8d90a2",
                        "tertiary-fixed": "#e2e2e9",
                        "secondary-fixed-dim": "#c7c6c9",
                        "inverse-surface": "#e2e2e2",
                        "inverse-primary": "#004ced",
                        "background": "#121414",
                        "surface-dim": "#121414",
                        "on-surface": "#e2e2e2",
                        // Custom colors from image
                        "chart-green": "#2bb673",
                        "chart-red": "#9c3838",
                        "chart-blue": "#3b82f6"
                    },
                    "borderRadius": {
                        "DEFAULT": "0.125rem",
                        "lg": "0.25rem",
                        "xl": "0.5rem",
                        "full": "0.75rem"
                    },
                    "spacing": {
                        "lg": "24px",
                        "md": "16px",
                        "xs": "8px",
                        "gutter": "20px",
                        "margin-desktop": "64px",
                        "sm": "12px",
                        "margin-mobile": "16px",
                        "xl": "32px",
                        "base": "4px"
                    },
                    "fontFamily": {
                        "label-sm": ["Inter"],
                        "label-md": ["Inter"],
                        "body-md": ["Inter"],
                        "body-lg": ["Inter"],
                        "headline-md": ["Inter"],
                        "headline-sm": ["Inter"],
                        "headline-lg-mobile": ["Inter"],
                        "display-lg": ["Inter"]
                    },
                    "fontSize": {
                        "label-sm": ["11px", {
                            "lineHeight": "14px",
                            "fontWeight": "600"
                        }],
                        "label-md": ["12px", {
                            "lineHeight": "16px",
                            "letterSpacing": "0.02em",
                            "fontWeight": "500"
                        }],
                        "body-md": ["14px", {
                            "lineHeight": "20px",
                            "fontWeight": "400"
                        }],
                        "body-lg": ["16px", {
                            "lineHeight": "24px",
                            "fontWeight": "400"
                        }],
                        "headline-md": ["24px", {
                            "lineHeight": "32px",
                            "letterSpacing": "-0.01em",
                            "fontWeight": "600"
                        }],
                        "headline-sm": ["20px", {
                            "lineHeight": "28px",
                            "fontWeight": "600"
                        }],
                        "headline-lg-mobile": ["24px", {
                            "lineHeight": "32px",
                            "fontWeight": "700"
                        }],
                        "display-lg": ["32px", {
                            "lineHeight": "40px",
                            "letterSpacing": "-0.02em",
                            "fontWeight": "700"
                        }]
                    }
                }
            }
        }
    </script>
    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }

        .material-symbols-outlined.fill {
            font-variation-settings: 'FILL' 1, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }

        /* Subtle grid background for the financial feel */
        .bg-grid-pattern {
            background-image:
                linear-gradient(to right, rgba(255, 255, 255, 0.02) 1px, transparent 1px),
                linear-gradient(to bottom, rgba(255, 255, 255, 0.02) 1px, transparent 1px);
            background-size: 40px 40px;
        }
    </style>
</head>

<body
    class="bg-background text-on-surface font-body-md min-h-screen flex flex-col md:flex-row overflow-x-hidden antialiased selection:bg-primary-container selection:text-white pb-20 md:pb-0">

    @include('layouts.sidebar') {{-- Sidebar desktop --}}

    <main>
        @yield('content') {{-- konten tiap halaman masuk sini --}}
    </main>

    @include('layouts.bottom-nav') {{-- Bottom nav mobile --}}
</body>

</html>
