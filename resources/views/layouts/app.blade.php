<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Marketplace preloved eksklusif mahasiswa FIKOM — jual beli barang bekas dengan aman dan mudah di lingkungan kampus.">

    <title>{{ isset($title) ? $title . ' — ' : '' }}{{ config('app.name', 'FIKOM Preloved') }}</title>

    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('images/logo-fikom-preloved.svg') }}">
    <link rel="shortcut icon" type="image/svg+xml" href="{{ asset('images/logo-fikom-preloved.svg') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/logo-fikom-preloved.svg') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="font-sans antialiased text-gray-800 min-h-screen flex flex-col"
      style="background-image: url('{{ asset('images/fikom_preloved_background.svg') }}'); background-repeat: no-repeat; background-size: cover; background-position: center top; background-attachment: fixed;">

    {{-- Navbar --}}
    @include('components.navbar')

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
            <div class="bg-green-50 border border-green-200 text-green-800 rounded-xl px-4 py-3 flex items-center gap-2">
                <x-heroicon-o-check-circle class="w-5 h-5 text-green-500 flex-shrink-0"/>
                <span>{{ session('success') }}</span>
            </div>
        </div>
    @endif
    @if(session('error'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
            <div class="bg-red-50 border border-red-200 text-red-800 rounded-xl px-4 py-3 flex items-center gap-2">
                <x-heroicon-o-x-circle class="w-5 h-5 text-red-500 flex-shrink-0"/>
                <span>{{ session('error') }}</span>
            </div>
        </div>
    @endif

    {{-- Content --}}
    <main class="flex-grow">
        {{ $slot }}
    </main>

    {{-- Footer --}}
    @include('components.footer')

    @stack('scripts')
</body>
</html>
