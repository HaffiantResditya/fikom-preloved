<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'FIKOM PRELOVED') }}</title>

        <!-- Google Fonts: Poppins & Figtree -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Figtree:wght@400;500;600;700&display=swap" rel="stylesheet">

        <!-- Favicon -->
        <link rel="icon" type="image/png" href="{{ asset('admin-assets/assets/Logo.png') }}">
        <link rel="shortcut icon" type="image/png" href="{{ asset('admin-assets/assets/Logo.png') }}">
        <link rel="apple-touch-icon" href="{{ asset('admin-assets/assets/Logo.png') }}">

        <!-- Tailwind CSS CDN Fallback + Vite Compiled Assets -->
        <script src="https://cdn.tailwindcss.com"></script>
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Standalone Styles for Guaranteed Robust Rendering -->
        <style>
            body {
                font-family: 'Poppins', 'Figtree', sans-serif;
                margin: 0;
                padding: 0;
                min-height: 100vh;
                display: flex;
                align-items: center;
                justify-content: center;
                background-color: #f3f7fd;
                background-image: url("{{ asset('images/fikom_preloved_background.svg') }}");
                background-repeat: no-repeat;
                background-size: cover;
                background-position: center center;
                background-attachment: fixed;
            }

            .fikom-card {
                background-color: #ffffff;
                border-radius: 28px;
                border: 1.5px solid #d1d5db;
                box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.06), 0 8px 10px -6px rgba(0, 0, 0, 0.04);
                overflow: hidden;
                margin: 24px auto;
                width: 100%;
                box-sizing: border-box;
                animation: fikomFadeInUp 0.4s cubic-bezier(0.16, 1, 0.3, 1) forwards;
            }

            .fikom-logo {
                height: 90px;
                max-height: 95px;
                width: auto;
                max-width: 100%;
                object-fit: contain;
                display: block;
                margin: 0 auto;
                user-select: none;
                pointer-events: none;
            }

            .fikom-input {
                width: 100%;
                height: 42px;
                padding-left: 42px;
                padding-right: 16px;
                font-size: 13px;
                color: #1f2937;
                background-color: #ffffff;
                border: 1.5px solid #9ca3af;
                border-radius: 9999px;
                outline: none;
                transition: all 0.2s ease-in-out;
                box-sizing: border-box;
            }

            .fikom-input:focus {
                border-color: #1e4b8a;
                box-shadow: 0 0 0 3px rgba(30, 75, 138, 0.15);
            }

            .relative:focus-within svg {
                color: #1e4b8a;
                transform: scale(1.08);
            }

            .relative svg {
                transition: transform 0.2s ease, color 0.2s ease;
            }

            .fikom-input-password {
                padding-right: 42px;
            }

            .fikom-btn-primary {
                width: 100%;
                height: 44px;
                background-color: #1e4b8a;
                color: #ffffff;
                font-weight: 600;
                font-size: 14px;
                border-radius: 9999px;
                border: none;
                cursor: pointer;
                box-shadow: 0 4px 6px -1px rgba(30, 75, 138, 0.25);
                display: flex;
                align-items: center;
                justify-content: center;
                transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
                transform: translateY(0);
            }

            .fikom-btn-primary:hover {
                background-color: #173b6c;
                transform: translateY(-1.5px);
                box-shadow: 0 6px 14px -2px rgba(30, 75, 138, 0.35);
            }

            .fikom-btn-primary:active {
                transform: scale(0.98) translateY(0);
                box-shadow: 0 2px 4px -1px rgba(30, 75, 138, 0.2);
            }

            .fikom-btn-primary:disabled {
                opacity: 0.75;
                cursor: not-allowed;
                transform: none;
            }

            .fikom-spinner {
                width: 16px;
                height: 16px;
                border: 2px solid rgba(255, 255, 255, 0.35);
                border-radius: 50%;
                border-top-color: #ffffff;
                animation: fikom-spin 0.6s linear infinite;
                display: inline-block;
                margin-right: 8px;
            }

            @keyframes fikom-spin {
                to { transform: rotate(360deg); }
            }

            .fikom-divider {
                position: relative;
                margin: 20px 0;
                text-align: center;
            }

            .fikom-divider hr {
                border: 0;
                border-top: 1px solid #e5e7eb;
                margin: 0;
            }

            .fikom-divider span {
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                background: #ffffff;
                padding: 0 12px;
                font-size: 12px;
                color: #9ca3af;
            }

            /* Strength Bar Transition */
            .strength-meter-bar {
                transition: width 0.35s cubic-bezier(0.34, 1.56, 0.64, 1), background-color 0.3s ease;
            }

            /* Micro-Animations Keyframes */
            @keyframes fikomFadeInUp {
                from {
                    opacity: 0;
                    transform: translateY(14px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            @keyframes fikomShake {
                0%, 100% { transform: translateX(0); }
                15%, 45%, 75% { transform: translateX(-5px); }
                30%, 60%, 90% { transform: translateX(5px); }
            }

            .shake-error {
                animation: fikomShake 0.45s ease-in-out;
            }

            @keyframes fikomPop {
                0% { transform: scale(0.8); opacity: 0; }
                70% { transform: scale(1.05); }
                100% { transform: scale(1); opacity: 1; }
            }

            .pop-badge {
                animation: fikomPop 0.25s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
            }

            @keyframes fikomWiggle {
                0%, 100% { transform: rotate(0deg); }
                20% { transform: rotate(-10deg); }
                40% { transform: rotate(10deg); }
                60% { transform: rotate(-6deg); }
                80% { transform: rotate(6deg); }
            }

            .fikom-wiggle {
                display: inline-block;
                animation: fikomWiggle 1.8s ease-in-out infinite;
                transform-origin: center;
            }
        </style>
    </head>
    <body class="antialiased p-4">
        <!-- Slot Konten Utama -->
        <div class="w-full min-h-screen flex flex-col justify-center items-center py-6">
            {{ $slot }}
        </div>

        <!-- Global Scripts untuk Form Auth -->
        <script>
            // Toggle intip password
            function togglePasswordVisibility(inputId, iconId) {
                const passwordInput = document.getElementById(inputId);
                const eyeIcon = document.getElementById(iconId);
                if (!passwordInput || !eyeIcon) return;

                if (passwordInput.type === 'password') {
                    passwordInput.type = 'text';
                    eyeIcon.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />`;
                } else {
                    passwordInput.type = 'password';
                    eyeIcon.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />`;
                }
            }

            // Anti double-submit & loading spinner otomatis untuk tombol form
            document.addEventListener('DOMContentLoaded', function () {
                document.querySelectorAll('form').forEach(function (form) {
                    form.addEventListener('submit', function (e) {
                        if (form.checkValidity && !form.checkValidity()) {
                            return; // Biarkan HTML5 validation berjalan jika ada yang belum terisi
                        }
                        const btn = form.querySelector('button[type="submit"]');
                        if (btn && !btn.disabled) {
                            setTimeout(function () {
                                btn.disabled = true;
                                const originalText = btn.innerText.trim();
                                btn.innerHTML = `<span class="fikom-spinner"></span> Memproses...`;
                            }, 20);
                        }
                    });
                });
            });

            // Modal Helper untuk Syarat & Ketentuan / Kebijakan
            function openFikomModal(modalId) {
                const modal = document.getElementById(modalId);
                if (modal) {
                    modal.classList.remove('hidden');
                    modal.classList.add('flex');
                    const modalBox = modal.querySelector('div');
                    if (modalBox) {
                        modalBox.classList.add('pop-badge');
                    }
                    document.body.style.overflow = 'hidden';
                }
            }

            function closeFikomModal(modalId) {
                const modal = document.getElementById(modalId);
                if (modal) {
                    modal.classList.add('hidden');
                    modal.classList.remove('flex');
                    document.body.style.overflow = 'auto';
                }
            }
        </script>
    </body>
</html>
