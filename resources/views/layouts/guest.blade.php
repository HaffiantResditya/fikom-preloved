<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'FIKOM PRELOVED') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts & Tailwind -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased min-h-screen flex items-center justify-center p-4 bg-no-repeat bg-cover bg-center bg-fixed"
          style="background-image: url('{{ asset('assets/images/fikom preloved_Background.svg') }}');">
        
        <!-- Slot Konten Utama (Langsung memuat login.blade.php) -->
        <div class="w-full flex justify-center items-center">
            {{ $slot }}
        </div>

    </body>
</html>