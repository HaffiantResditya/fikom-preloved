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
        <link rel="icon" type="image/svg+xml" href="{{ asset('images/logo_fikompreloved.svg') }}">
        <link rel="shortcut icon" type="image/svg+xml" href="{{ asset('images/logo_fikompreloved.svg') }}">
        <link rel="apple-touch-icon" href="{{ asset('images/logo_fikompreloved.svg') }}">

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
            }

            .fikom-logo {
                height: 90px;
                max-height: 95px;
                width: auto;
                max-width: 100%;
                object-fit: contain;
                display: block;
                margin: 0 auto;
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
                transition: background-color 0.2s ease-in-out;
            }

            .fikom-btn-primary:hover {
                background-color: #173b6c;
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
        </style>
    </head>
    <body class="antialiased p-4">
        <!-- Slot Konten Utama -->
        <div class="w-full flex justify-center items-center">
            {{ $slot }}
        </div>
    </body>
</html>
