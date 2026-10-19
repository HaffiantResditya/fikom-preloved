<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Dashboard') - FIKOM PRELOVED</title>

    <!-- Google Fonts Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('admin-assets/assets/Logo.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('admin-assets/assets/Logo.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('admin-assets/assets/Logo.png') }}">

    <!-- Shared Base Styles -->
    <link rel="stylesheet" href="{{ asset('admin-assets/style/style.css') }}">

    <!-- Page Specific Styles -->
    @stack('styles')
</head>
<body>

    <!-- =============================================
         HEADER GLOBAL
         ============================================= -->
    <header class="site-header">
        <a href="{{ route('admin.dashboard') }}" class="header-logo">
            <img src="{{ asset('admin-assets/assets/Logo.png') }}" alt="Logo FIKOM PRELOVED">
            <span class="header-logo-text">
                <span class="t-fikom">FIKOM</span><span class="t-preloved"> PRELOVED</span>
            </span>
        </a>

        <div style="display: flex; align-items: center; gap: 16px; margin-right: 20px;">
            <a href="{{ route('home') }}" target="_blank" style="display: inline-flex; align-items: center; gap: 6px; font-size: 12px; font-weight: 600; color: #64748b; background: #f1f5f9; padding: 6px 12px; border-radius: 6px; transition: all 0.2s;" onmouseover="this.style.color='#2563eb'" onmouseout="this.style.color='#64748b'">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                </svg>
                Lihat Web Publik
            </a>

            <nav class="header-user" aria-label="Navigasi Pengguna">
                <div class="header-user-avatar" aria-hidden="true">
                    {{ strtoupper(substr(auth('admin')->user()->name ?? 'A', 0, 1)) }}
                </div>
                <span>{{ auth('admin')->user()->name ?? 'Administrator' }}</span>
            </nav>
        </div>
    </header>

    <!-- =============================================
         SIDEBAR NAVIGASI
         ============================================= -->
    <aside class="sidebar">
        <p class="sidebar-label">Navigasi Utama</p>
        <nav class="sidebar-nav" aria-label="Menu Utama">
            <ul>
                <!-- Dashboard -->
                <li>
                    <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" {!! request()->routeIs('admin.dashboard') ? 'aria-current="page"' : '' !!}>
                        <svg viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
                        Dashboard
                    </a>
                </li>

                <!-- Moderasi Barang -->
                <li>
                    <a href="{{ Route::has('admin.products') ? route('admin.products') : '#' }}" class="{{ request()->routeIs('admin.products*') ? 'active' : '' }}" {!! request()->routeIs('admin.products*') ? 'aria-current="page"' : '' !!}>
                        <svg viewBox="0 0 24 24"><path d="M20 7H4a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2z"/><path d="M16 3l-4 4-4-4"/></svg>
                        Barang
                    </a>
                </li>

                <!-- Moderasi User -->
                <li>
                    <a href="{{ Route::has('admin.users') ? route('admin.users') : '#' }}" class="{{ request()->routeIs('admin.users*') ? 'active' : '' }}" {!! request()->routeIs('admin.users*') ? 'aria-current="page"' : '' !!}>
                        <svg viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
                        User
                    </a>
                </li>

                <!-- Barang Terjual -->
                <li>
                    <a href="{{ Route::has('admin.sold') ? route('admin.sold') : '#' }}" class="{{ request()->routeIs('admin.sold*') ? 'active' : '' }}" {!! request()->routeIs('admin.sold*') ? 'aria-current="page"' : '' !!}>
                        <svg viewBox="0 0 24 24"><polyline points="20 12 20 22 4 22 4 12"/><rect x="2" y="7" width="20" height="5"/><line x1="12" y1="22" x2="12" y2="7"/><path d="M12 7H7.5a2.5 2.5 0 0 1 0-5C11 2 12 7 12 7z"/><path d="M12 7h4.5a2.5 2.5 0 0 0 0-5C13 2 12 7 12 7z"/></svg>
                        Barang Terjual
                    </a>
                </li>
            </ul>
        </nav>

        <!-- Tombol Logout -->
        <div class="sidebar-logout">
            <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
            <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" title="Keluar dari Panel Admin">
                <svg viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                Keluar
            </a>
        </div>
    </aside>

    <!-- =============================================
         KONTEN UTAMA
         ============================================= -->
    <main class="main-content">
        @yield('content')
    </main>

    <!-- Scripts -->
    @stack('scripts')
</body>
</html>
