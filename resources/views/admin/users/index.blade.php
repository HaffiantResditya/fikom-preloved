@extends('layouts.admin')

@section('title', 'Moderasi User')

@push('styles')
    <link rel="stylesheet" href="{{ asset('admin-assets/style/moderasi_user.css') }}">
    <link rel="stylesheet" href="{{ asset('admin-assets/style/detail_user.css') }}">
    <link rel="stylesheet" href="{{ asset('admin-assets/style/popup_tolak.css') }}">
    <style>
        .modal-overlay {
            display: none;
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(15, 23, 42, 0.65);
            backdrop-filter: blur(4px);
            z-index: 999;
            align-items: center;
            justify-content: center;
            padding: 20px;
            animation: fadeInOverlay 0.2s ease-out;
        }
        .modal-overlay.is-open {
            display: flex;
        }
        @keyframes fadeInOverlay {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        .user-modal-dialog {
            background: #ffffff;
            border-radius: var(--radius);
            width: 100%;
            max-width: 680px;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25);
            display: flex;
            flex-direction: column;
            animation: scaleInModal 0.25s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .modal-dialog-sm {
            background: #ffffff;
            border-radius: var(--radius);
            width: 100%;
            max-width: 500px;
            box-shadow: 0 25px 50px -12px rgba(0,0,0,0.3);
            overflow: hidden;
            animation: scaleInModal 0.25s cubic-bezier(0.16, 1, 0.3, 1);
        }
        @keyframes scaleInModal {
            from { transform: scale(0.95); opacity: 0; }
            to { transform: scale(1); opacity: 1; }
        }
        .btn-secondary {
            background: #f1f5f9;
            color: #475569;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }
        .btn-secondary:hover {
            background: #e2e8f0;
            color: #1e293b;
        }
        .badge-tab-count {
            display: inline-block;
            background: rgba(0,0,0,0.06);
            padding: 2px 8px;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 700;
            margin-left: 6px;
        }
        .filter-tab.active .badge-tab-count {
            background: rgba(255,255,255,0.25);
            color: #ffffff;
        }
        .pagination-container {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 18px 24px;
            border-top: 1px solid var(--border);
            flex-wrap: wrap;
            gap: 12px;
        }
        .pagination-links {
            display: flex;
            gap: 6px;
        }
        .pagination-links a, .pagination-links span {
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
            border: 1px solid var(--border);
            background: #fff;
            color: var(--text);
            text-decoration: none;
        }
        .pagination-links span.active {
            background: var(--primary);
            color: #fff;
            border-color: var(--primary);
        }
    </style>
@endpush

@section('content')
    <!-- Header Halaman -->
    <div class="page-header">
        <div class="page-title-wrap">
            <h1 class="page-title">Moderasi &amp; Verifikasi User</h1>
            <span class="badge-siakad">🎓 Identitas Mahasiswa Kampus</span>
        </div>

        <div class="page-filter">
            <span style="font-size: 13px; color: var(--text-muted); font-weight: 500;">
                Hari Ini: <strong>{{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</strong>
            </span>
            <button type="button" class="btn-refresh" onclick="window.location.reload();" aria-label="Segarkan Data" title="Segarkan Data">
                <svg viewBox="0 0 24 24"><polyline points="23 4 23 10 17 10"/><polyline points="1 20 1 14 7 14"/><path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"/></svg>
            </button>
        </div>
    </div>

    <!-- Alert Notifikasi Flash -->
    @if (session('success'))
        <div style="background: #f0fdf4; border: 1px solid #bbf7d0; color: #16a34a; padding: 12px 16px; border-radius: var(--radius); margin-bottom: 20px; font-size: 13px; display: flex; align-items: center; gap: 8px;">
            <span>✅</span>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <!-- Card Konten Utama -->
    <section class="section-card">

        <!-- Filter Bar: Tab Status -->
        <div class="filter-bar">
            <nav class="filter-tabs" aria-label="Filter Status Akun">
                <a href="{{ route('admin.users', array_merge(request()->except('status', 'page'), ['status' => 'all'])) }}" 
                   class="filter-tab {{ $statusFilter === 'all' ? 'active' : '' }}">
                   Semua Mahasiswa <span class="badge-tab-count">{{ $totalUsers }}</span>
                </a>
                <a href="{{ route('admin.users', array_merge(request()->except('status', 'page'), ['status' => 'approved'])) }}" 
                   class="filter-tab {{ $statusFilter === 'approved' ? 'active' : '' }}">
                   Aktif <span class="badge-tab-count">{{ $approvedUsers }}</span>
                </a>
                <a href="{{ route('admin.users', array_merge(request()->except('status', 'page'), ['status' => 'pending'])) }}" 
                   class="filter-tab {{ $statusFilter === 'pending' ? 'active' : '' }}">
                   Menunggu Verifikasi <span class="badge-tab-count">{{ $pendingUsers }}</span>
                </a>
                <a href="{{ route('admin.users', array_merge(request()->except('status', 'page'), ['status' => 'suspended'])) }}" 
                   class="filter-tab {{ $statusFilter === 'suspended' ? 'active' : '' }}">
                   Nonaktif <span class="badge-tab-count">{{ $suspendedUsers }}</span>
                </a>
            </nav>
        </div>

        <!-- Search Form -->
        <form class="search-filter-row" action="{{ route('admin.users') }}" method="GET" role="search">
            @if ($statusFilter !== 'all')
                <input type="hidden" name="status" value="{{ $statusFilter }}">
            @endif

            <div class="search-input-wrap" style="flex: 1;">
                <svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                <input type="search"
                       id="search-user"
                       name="search"
                       value="{{ $searchQuery }}"
                       placeholder="Cari berdasarkan nama mahasiswa, email kampus, atau nomor WhatsApp..."
                       aria-label="Cari pengguna">
            </div>

            <button type="submit" class="btn-refresh" style="width: auto; padding: 0 16px; gap: 6px; font-weight: 600;">
                Cari User
            </button>
        </form>

        <!-- Tabel Data User -->
        @if ($users->isEmpty())
            <div style="text-align: center; padding: 50px 20px; color: var(--text-muted);">
                <div style="font-size: 40px; margin-bottom: 8px;">👥</div>
                <h3 style="font-size: 16px; font-weight: 700; color: var(--text);">Tidak ada mahasiswa ditemukan</h3>
                <p style="font-size: 13px; margin-top: 4px;">Coba sesuaikan kata kunci pencarian atau ganti filter status di atas.</p>
            </div>
        @else
            <table class="data-table" aria-label="Daftar mahasiswa terdaftar">
                <thead>
                    <tr>
                        <th>Mahasiswa &amp; Kontak</th>
                        <th>Program Studi / Fakultas</th>
                        <th>Barang Preloved</th>
                        <th>Status Akun</th>
                        <th>Bergabung</th>
                        <th style="text-align: right; width: 160px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $student)
                        @php
                            $userProducts = $student->products->map(function($p) {
                                $img = $p->primaryImage?->url ?? ($p->images->first()?->url ?? asset('admin-assets/assets/Logo.png'));
                                return [
                                    'title'    => $p->title,
                                    'price'    => 'Rp ' . number_format($p->price, 0, ',', '.'),
                                    'status'   => $p->status,
                                    'image'    => $img,
                                    'category' => $p->category?->name ?? 'Preloved',
                                ];
                            });
                        @endphp
                        <tr>
                            <td>
                                <div class="user-profile-wrap">
                                    @if ($student->profile_photo_url)
                                        <img src="{{ $student->profile_photo_url }}" 
                                             alt="{{ $student->name }}" 
                                             class="user-avatar" 
                                             style="width:42px; height:42px; border-radius:50%; object-fit:cover; border:2px solid var(--border);"
                                             onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                        <div class="user-avatar" style="display:none; background:#2563eb; color:#fff; align-items:center; justify-content:center; font-weight:700; font-size:14px; border-radius:50%; width:42px; height:42px; flex-shrink:0;">
                                            {{ strtoupper(substr($student->name, 0, 2)) }}
                                        </div>
                                    @else
                                        <div class="user-avatar" style="background:#2563eb; color:#fff; display:flex; align-items:center; justify-content:center; font-weight:700; font-size:14px; border-radius:50%; width:42px; height:42px; flex-shrink:0;">
                                            {{ strtoupper(substr($student->name, 0, 2)) }}
                                        </div>
                                    @endif
                                    <div>
                                        <p class="user-info-name" style="font-weight: 700; color: var(--text); font-size: 13px;">
                                            {{ $student->name }}
                                            @if ($student->account_status === 'approved')
                                                <span class="verified-icon" style="color: #2563eb; font-size: 12px;" title="Terverifikasi">✓</span>
                                            @endif
                                        </p>
                                        <a href="mailto:{{ $student->email }}" class="user-info-email" style="font-size: 12px; color: var(--text-muted);">
                                            {{ $student->email }}
                                        </a>
                                        <p class="user-info-phone" style="font-size: 11px; color: #16a34a; margin-top: 2px;">
                                            💬 {{ $student->whatsapp_number ?? '-' }}
                                        </p>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <p class="faculty-name" style="font-weight: 600; font-size: 13px;">{{ $student->faculty ?? 'Fakultas Ilmu Komputer' }}</p>
                                <p class="faculty-prodi" style="font-size: 12px; color: var(--text-muted);">{{ $student->major ?? 'S1 Informatika' }}</p>
                            </td>
                            <td>
                                <p class="item-count" style="font-size: 16px; font-weight: 800; color: var(--text);">
                                    {{ $student->products_count }}
                                </p>
                                <p class="item-count-label {{ $student->products_count > 0 ? 'aktif' : 'pending' }}">
                                    Barang
                                </p>
                            </td>
                            <td>
                                @if ($student->account_status === 'approved')
                                    <span class="badge-user-aktif">✓ Aktif</span>
                                @elseif ($student->account_status === 'pending')
                                    <span class="badge" style="background: #fff7ed; color: #c2410c; border: 1px solid #ffedd5; padding: 4px 10px; border-radius: 20px; font-weight: 700; font-size: 11px;">
                                        ⏳ Menunggu
                                    </span>
                                @else
                                    <span class="badge" style="background: #f1f5f9; color: #64748b; padding: 4px 10px; border-radius: 20px; font-weight: 700; font-size: 11px;">
                                        ✕ Nonaktif
                                    </span>
                                @endif
                            </td>
                            <td style="color: var(--text-muted); font-size: 12px;">
                                {{ $student->created_at ? $student->created_at->translatedFormat('d M Y') : '-' }}
                            </td>
                            <td>
                                <div class="actions-wrap" style="justify-content: flex-end; gap: 6px;">
                                    <!-- Tombol Detail User -->
                                    <button type="button" 
                                            class="btn-detail-user"
                                            title="Lihat Detail Profil"
                                            onclick="openUserModal({{ json_encode([
                                                'id'             => $student->id,
                                                'name'           => $student->name,
                                                'email'          => $student->email,
                                                'whatsapp'       => $student->whatsapp_number ?? '-',
                                                'avatar_url'     => $student->profile_photo_url,
                                                'major'          => $student->major ?? 'S1 Informatika',
                                                'status'         => $student->account_status,
                                                'created_at'     => $student->created_at ? $student->created_at->translatedFormat('d F Y') : '-',
                                                'products_count' => $student->products_count,
                                                'products'       => $userProducts,
                                                'approve_url'    => route('admin.users.approve', $student),
                                                'suspend_url'    => route('admin.users.suspend', $student),
                                            ]) }})">
                                        <svg viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                        Detail
                                    </button>

                                    @if ($student->account_status === 'pending')
                                        <!-- Tombol Verifikasi Cepat (Membuka Popup Elegan) -->
                                        <button type="button" 
                                                class="btn-sm btn-terima" 
                                                title="Verifikasi & Setujui Akun"
                                                onclick="openApproveUserModal('{{ route('admin.users.approve', $student) }}', {{ json_encode([
                                                    'name'       => $student->name,
                                                    'email'      => $student->email,
                                                    'whatsapp'   => $student->whatsapp_number ?? '-',
                                                    'avatar_url' => $student->profile_photo_url,
                                                    'major'      => $student->major ?? 'S1 Informatika',
                                                    'created_at' => $student->created_at ? $student->created_at->translatedFormat('d F Y') : '-'
                                                ]) }})">
                                            ✓
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Pagination Bar -->
            <div class="pagination-container">
                <div style="font-size: 13px; color: var(--text-muted);">
                    Menampilkan <strong>{{ $users->firstItem() ?? 0 }}</strong> - <strong>{{ $users->lastItem() ?? 0 }}</strong> dari <strong>{{ $users->total() }}</strong> mahasiswa
                </div>
                <div class="pagination-links">
                    {{ $users->links('pagination::simple-default') }}
                </div>
            </div>
        @endif

    </section>

    <!-- =============================================
         POPUP MODAL: DETAIL USER (detail_user.html)
         ============================================= -->
    <div class="modal-overlay" id="modal-detail-user" onclick="if(event.target === this) closeUserModal();">
        <div class="user-modal-dialog">

            <!-- Header Modal -->
            <div class="user-modal-header">
                <div class="user-modal-header-left">
                    <div class="user-modal-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    </div>
                    <div class="user-modal-title-group">
                        <div class="user-modal-title-row">
                            <h2 class="user-modal-title" id="u-modal-name">Profil Mahasiswa</h2>
                            <span class="badge-aktif-kuliah" id="u-status-badge">Aktif Kuliah</span>
                        </div>
                        <p class="user-modal-subtitle">Terverifikasi Email Kampus (@mhs.udb.ac.id)</p>
                    </div>
                </div>
                <button type="button" class="btn-close-modal" aria-label="Tutup jendela modal" onclick="closeUserModal()">&times;</button>
            </div>

            <!-- Body Modal -->
            <div class="user-modal-body" style="padding: 24px;">

                <!-- 1. Card Profil Utama Mahasiswa -->
                <div class="student-profile-card">
                    <div class="student-avatar-wrap" style="position:relative; width:64px; height:64px; border-radius:50%; overflow:hidden; flex-shrink:0; border:2px solid #cbd5e1;">
                        <img id="u-avatar-img" src="" alt="Foto Profil" class="student-avatar-img" style="display:none; width:100%; height:100%; object-fit:cover;" onerror="this.style.display='none'; document.getElementById('u-avatar-box').style.display='flex';">
                        <div class="student-avatar-fallback" id="u-avatar-box" style="width: 100%; height: 100%; border-radius: 50%; background: #2563eb; color: #fff; font-size: 24px; font-weight: 800; display: flex; align-items: center; justify-content: center;">
                            U
                        </div>
                        <span class="badge-verified-avatar" title="Terverifikasi Kampus">✓</span>
                    </div>

                    <div class="student-info-col">
                        <div class="student-name-row">
                            <h3 class="student-full-name" id="u-full-name">-</h3>
                            <span class="npm-badge" id="u-email-badge">Email Kampus</span>
                        </div>
                        <div class="student-faculty-row">
                            <svg viewBox="0 0 24 24"><path d="M3 21h18M3 10h18M5 10v11M19 10v11M9 10v11M15 10v11M12 2L2 7h20L12 2z"/></svg>
                            <span><strong>Fakultas Ilmu Komputer</strong> &bull; <span id="u-prodi-text">S1 Informatika</span></span>
                        </div>
                        <p class="student-class-row" id="u-joined-date">Terdaftar sejak: -</p>
                    </div>
                </div>

                <!-- 2. Baris Informasi Kontak -->
                <div class="contact-cards-row" style="margin-top: 16px;">
                    <a id="u-mail-link" href="#" class="contact-pill" title="Kirim email">
                        <svg viewBox="0 0 24 24"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                        <span id="u-email-text">-</span>
                    </a>
                    <a id="u-wa-link" href="#" target="_blank" class="contact-pill" title="Hubungi via WhatsApp">
                        <svg viewBox="0 0 24 24"><rect x="5" y="2" width="14" height="20" rx="2" ry="2"/><line x1="12" y1="18" x2="12.01" y2="18"/></svg>
                        <span id="u-wa-text">-</span>
                    </a>
                </div>

                <!-- 3. Rekam Jejak Jual-Beli -->
                <div class="reputation-grid" style="margin-top: 16px;">
                    <div class="reputation-card">
                        <div class="rep-icon-wrap" aria-hidden="true">
                            <svg viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><polyline points="9 12 11 14 15 10"/></svg>
                        </div>
                        <div class="rep-info">
                            <span class="rep-label">Status Verifikasi</span>
                            <h4 class="rep-title" id="u-rep-status">Valid</h4>
                            <p class="rep-desc">Sesuai domain email kampus</p>
                        </div>
                    </div>

                    <div class="reputation-card">
                        <div class="rep-icon-wrap" aria-hidden="true">
                            <svg viewBox="0 0 24 24"><circle cx="12" cy="8" r="7"/><polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88"/></svg>
                        </div>
                        <div class="rep-info">
                            <span class="rep-label">Total Iklan Preloved</span>
                            <h4 class="rep-title"><span id="u-total-items">0</span> Barang</h4>
                            <p class="rep-desc">Terdaftar sebagai Penjual</p>
                        </div>
                    </div>
                </div>

                <!-- 4. Daftar Barang yang Dijual -->
                <div class="active-items-section" style="margin-top: 18px;">
                    <div class="active-items-header">
                        <div class="active-items-title">
                            <svg viewBox="0 0 24 24"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/><polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/></svg>
                            <span>Daftar Barang Dijual</span>
                        </div>
                        <span class="active-items-count" id="u-items-count-badge">0 Barang</span>
                    </div>

                    <div class="active-items-grid" id="u-products-list" style="max-height: 220px; overflow-y: auto; padding-right: 2px;">
                        <!-- Dynamically filled with products -->
                    </div>
                </div>

            </div>

            <!-- Footer Modal: Aksi Moderasi User -->
            <div class="modal-footer" style="padding: 16px 24px; border-top: 1px solid var(--border); display: flex; align-items: center; justify-content: flex-end; gap: 10px; background: #fafafa;">
                <button type="button" 
                        id="u-btn-suspend-trigger"
                        class="btn-sm btn-tolak" 
                        style="padding: 9px 20px; font-size: 13px;"
                        onclick="triggerSuspendFromDetail()">
                    ✕ Nonaktifkan Akun
                </button>

                <button type="button" 
                        id="u-btn-approve-trigger"
                        class="btn-sm btn-terima" 
                        style="padding: 9px 22px; font-size: 13px; font-weight: 700;"
                        onclick="triggerApproveFromDetail()">
                    ✓ Setujui Akun
                </button>
            </div>

        </div>
    </div>

    <!-- =============================================
         POPUP MODAL: VERIFIKASI & SETUJUI MAHASISWA
         ============================================= -->
    <div class="modal-overlay" id="modal-terima-user" onclick="if(event.target === this) closeApproveUserModal();">
        <div class="modal-dialog-sm" style="border-top: 4px solid #16a34a;">
            
            <!-- Header Modal -->
            <div class="modal-header" style="padding: 20px 24px; border-bottom: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between;">
                <div style="display: flex; align-items: center; gap: 12px;">
                    <div style="width: 42px; height: 42px; border-radius: 10px; background: #dcfce7; color: #16a34a; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                        <svg viewBox="0 0 24 24" style="width: 24px; height: 24px; stroke: currentColor; fill: none; stroke-width: 2.2;"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                    </div>
                    <div>
                        <h2 style="font-size: 16px; font-weight: 700; color: #14532d; margin: 0;">Verifikasi &amp; Setujui Akun</h2>
                        <p style="font-size: 12px; color: var(--text-muted); margin: 2px 0 0 0;">Mahasiswa akan langsung aktif dan dapat bertransaksi preloved.</p>
                    </div>
                </div>
                <button type="button" class="btn-close-modal" aria-label="Batal dan tutup" onclick="closeApproveUserModal()">&times;</button>
            </div>

            <!-- Formulir Persetujuan -->
            <form id="form-approve-user" action="" method="POST">
                @csrf
                <div style="padding: 20px 24px;">

                    <!-- Kartu Preview Mahasiswa -->
                    <div style="background: #f8fafc; border: 1px solid var(--border); border-radius: 10px; padding: 14px; display: flex; align-items: center; gap: 14px; margin-bottom: 16px;">
                        <div style="position: relative; width: 48px; height: 48px; border-radius: 50%; overflow: hidden; flex-shrink: 0; border: 1.5px solid #cbd5e1;">
                            <img id="approve-user-avatar-img" src="" alt="Foto Mahasiswa" style="display:none; width: 100%; height: 100%; object-fit: cover;" onerror="this.style.display='none'; document.getElementById('approve-user-avatar').style.display='flex';">
                            <div id="approve-user-avatar" style="width: 100%; height: 100%; background: #2563eb; color: #fff; font-weight: 800; font-size: 16px; display: flex; align-items: center; justify-content: center;">
                                U
                            </div>
                        </div>
                        <div style="flex: 1; min-width: 0;">
                            <h4 id="approve-user-name" style="font-size: 14px; font-weight: 700; color: var(--text); margin: 0 0 2px 0;">Nama Mahasiswa</h4>
                            <p id="approve-user-email" style="font-size: 12px; color: #2563eb; margin: 0 0 4px 0;">email@mhs.udb.ac.id</p>
                            <p style="font-size: 11px; color: var(--text-muted); margin: 0;">Fakultas Ilmu Komputer &bull; <span id="approve-user-major">S1 Informatika</span></p>
                        </div>
                    </div>

                    <!-- Checklist Validasi Kampus -->
                    <div style="background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 8px; padding: 12px 14px;">
                        <p style="font-size: 12px; font-weight: 700; color: #166534; margin: 0 0 6px 0;">Konfirmasi Verifikasi Identitas:</p>
                        <ul style="font-size: 11px; color: #15803d; line-height: 1.6; list-style: none; padding-left: 0; margin: 0;">
                            <li style="display:flex; align-items:center; gap:6px;">
                                <svg style="width:14px;height:14px;stroke:#16a34a;stroke-width:2.5;fill:none;" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
                                Domain email resmi terverifikasi: <strong>@mhs.udb.ac.id</strong>
                            </li>
                            <li style="display:flex; align-items:center; gap:6px;">
                                <svg style="width:14px;height:14px;stroke:#16a34a;stroke-width:2.5;fill:none;" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
                                Akses login mandiri mahasiswa langsung diaktifkan
                            </li>
                            <li style="display:flex; align-items:center; gap:6px;">
                                <svg style="width:14px;height:14px;stroke:#16a34a;stroke-width:2.5;fill:none;" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
                                Mahasiswa dapat langsung memasang iklan &amp; bertransaksi
                            </li>
                        </ul>
                    </div>

                </div>

                <!-- Footer Aksi -->
                <div style="padding: 14px 24px; border-top: 1px solid var(--border); display: flex; align-items: center; justify-content: flex-end; gap: 10px; background: #fafafa;">
                    <button type="button" class="btn-sm btn-secondary" style="padding: 8px 18px; font-size: 13px;" onclick="closeApproveUserModal()">
                        Batal
                    </button>
                    <button type="submit" class="btn-sm btn-terima" style="padding: 9px 24px; font-size: 13px; font-weight: 700;">
                        ✓ Ya, Setujui Akun
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- =============================================
         POPUP MODAL: NONAKTIFKAN AKUN MAHASISWA
         ============================================= -->
    <div class="modal-overlay" id="modal-suspend-user" onclick="if(event.target === this) closeSuspendUserModal();">
        <div class="modal-dialog-sm" style="border-top: 4px solid #ef4444;">
            
            <!-- Header Modal -->
            <div class="modal-header" style="padding: 20px 24px; border-bottom: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between;">
                <div style="display: flex; align-items: center; gap: 12px;">
                    <div class="badge-warning-icon">
                        <svg viewBox="0 0 24 24"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                    </div>
                    <div>
                        <h2 class="modal-title" style="color: #991b1b; font-size: 16px; margin: 0;">Nonaktifkan Akun Mahasiswa</h2>
                        <p class="modal-subtitle" style="margin: 2px 0 0 0;">Akses mahasiswa ke platform FIKOM PRELOVED akan ditangguhkan.</p>
                    </div>
                </div>
                <button type="button" class="btn-close-modal" aria-label="Batal dan tutup" onclick="closeSuspendUserModal()">&times;</button>
            </div>

            <!-- Formulir Penangguhan -->
            <form id="form-suspend-user" action="" method="POST">
                @csrf
                <div class="modal-reject-body" style="padding: 20px 24px;">

                    <!-- Kartu Info Mahasiswa -->
                    <div style="background: #f8fafc; border: 1px solid var(--border); border-radius: 10px; padding: 12px 14px; display: flex; align-items: center; gap: 12px; margin-bottom: 16px;">
                        <div style="position: relative; width: 44px; height: 44px; border-radius: 50%; overflow: hidden; flex-shrink: 0; border: 1.5px solid #cbd5e1;">
                            <img id="suspend-user-avatar-img" src="" alt="Foto Mahasiswa" style="display:none; width: 100%; height: 100%; object-fit: cover;" onerror="this.style.display='none'; document.getElementById('suspend-user-avatar').style.display='flex';">
                            <div id="suspend-user-avatar" style="width: 100%; height: 100%; background: #64748b; color: #fff; font-weight: 800; font-size: 15px; display: flex; align-items: center; justify-content: center;">
                                U
                            </div>
                        </div>
                        <div style="flex: 1; min-width: 0;">
                            <h4 id="suspend-user-name" style="font-size: 13px; font-weight: 700; color: var(--text); margin: 0 0 2px 0;">Nama Mahasiswa</h4>
                            <p id="suspend-user-email" style="font-size: 11px; color: var(--text-muted); margin: 0;">email@mhs.udb.ac.id</p>
                        </div>
                    </div>

                    <!-- Alasan Penonaktifan -->
                    <div class="form-reject-group">
                        <label for="suspend-reason-select">Alasan Penonaktifan <span style="color: #ef4444;">*</span></label>
                        <select id="suspend-reason-select" name="reason" required>
                            <option value="" disabled selected>-- Pilih Alasan Penonaktifan --</option>
                            <option value="Pelanggaran Syarat & Ketentuan FIKOM Preloved">Pelanggaran Syarat &amp; Ketentuan FIKOM Preloved</option>
                            <option value="Laporan Penipuan atau Transaksi Bermasalah">Laporan Penipuan atau Transaksi Bermasalah</option>
                            <option value="Iklan Barang Terlarang / Tidak Pantas">Iklan Barang Terlarang / Tidak Pantas</option>
                            <option value="Spam / Aktivitas Akun Mencurigakan">Spam / Aktivitas Akun Mencurigakan</option>
                            <option value="Permintaan Mahasiswa Sendiri">Permintaan Mahasiswa Sendiri</option>
                            <option value="Lainnya">Alasan Lainnya</option>
                        </select>
                    </div>

                    <!-- Catatan Tambahan -->
                    <div class="form-reject-group no-margin">
                        <label for="suspend-note">Catatan Tambahan <span class="label-optional">(opsional)</span></label>
                        <textarea id="suspend-note" name="note" rows="3" placeholder="Tulis catatan atau kronologi singkat untuk arsip admin..."></textarea>
                    </div>

                </div>

                <!-- Footer Aksi -->
                <div style="padding: 14px 24px; border-top: 1px solid var(--border); display: flex; align-items: center; justify-content: flex-end; gap: 10px; background: #fafafa;">
                    <button type="button" class="btn-sm btn-secondary" style="padding: 8px 18px; font-size: 13px;" onclick="closeSuspendUserModal()">
                        Batal
                    </button>
                    <button type="submit" class="btn-sm btn-confirm-reject" style="padding: 9px 22px; font-size: 13px; font-weight: 700; display: inline-flex; align-items: center; gap: 6px;">
                        ✕ Konfirmasi Nonaktifkan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    let activeUserModalData = null;

    function openUserModal(user) {
        activeUserModalData = user;

        document.getElementById('u-modal-name').textContent = user.name;
        document.getElementById('u-full-name').textContent = user.name;
        document.getElementById('u-email-text').textContent = user.email;
        document.getElementById('u-mail-link').href = 'mailto:' + user.email;
        document.getElementById('u-wa-text').textContent = user.whatsapp;
        document.getElementById('u-wa-link').href = 'https://wa.me/' + (user.whatsapp ? user.whatsapp.replace(/[^0-9]/g, '') : '');
        document.getElementById('u-joined-date').textContent = 'Terdaftar sejak: ' + user.created_at;
        // Avatar foto profil atau fallback inisial
        const avatarImg = document.getElementById('u-avatar-img');
        const avatarBox = document.getElementById('u-avatar-box');
        if (user.avatar_url) {
            avatarImg.src = user.avatar_url;
            avatarImg.style.display = 'block';
            avatarBox.style.display = 'none';
        } else {
            avatarImg.style.display = 'none';
            avatarBox.style.display = 'flex';
            avatarBox.textContent = (user.name || 'U').substring(0, 2).toUpperCase();
        }

        const prodiText = document.getElementById('u-prodi-text');
        if (prodiText) {
            prodiText.textContent = user.major || 'S1 Informatika';
        }

        // Status badge
        const statusBadge = document.getElementById('u-status-badge');
        statusBadge.textContent = user.status.toUpperCase();
        if (user.status === 'approved') {
            statusBadge.className = 'badge-aktif-kuliah';
        } else {
            statusBadge.className = 'badge';
            statusBadge.style.background = '#fff7ed';
            statusBadge.style.color = '#c2410c';
        }

        document.getElementById('u-rep-status').textContent = user.status === 'approved' ? 'Aktif Terverifikasi' : 'Menunggu Verifikasi';
        document.getElementById('u-total-items').textContent = user.products_count;
        document.getElementById('u-items-count-badge').textContent = user.products_count + ' Barang';

        // Tampilkan/sembunyikan tombol aksi sesuai status
        const btnApprove = document.getElementById('u-btn-approve-trigger');
        const btnSuspend = document.getElementById('u-btn-suspend-trigger');
        if (user.status === 'approved') {
            btnApprove.style.display = 'none';
            btnSuspend.style.display = 'inline-block';
        } else if (user.status === 'suspended' || user.status === 'rejected') {
            btnApprove.style.display = 'inline-block';
            btnApprove.textContent = '✓ Aktifkan Kembali Akun';
            btnSuspend.style.display = 'none';
        } else {
            btnApprove.style.display = 'inline-block';
            btnApprove.textContent = '✓ Setujui Akun';
            btnSuspend.style.display = 'inline-block';
        }

        // Populate products list
        const listContainer = document.getElementById('u-products-list');
        listContainer.innerHTML = '';

        if (user.products && user.products.length > 0) {
            user.products.forEach(p => {
                const itemDiv = document.createElement('div');
                itemDiv.className = 'active-item-card';

                let statusBadge = '';
                if (p.status === 'approved') {
                    statusBadge = '<span style="font-size:10px; color:#16a34a; font-weight:600; display:flex; align-items:center; gap:3px;"><span style="display:inline-block; width:5px; height:5px; border-radius:50%; background:#16a34a;"></span> Tayang</span>';
                } else if (p.status === 'sold') {
                    statusBadge = '<span style="font-size:10px; color:#64748b; font-weight:600; display:flex; align-items:center; gap:3px;"><span style="display:inline-block; width:5px; height:5px; border-radius:50%; background:#64748b;"></span> Terjual</span>';
                } else if (p.status === 'rejected') {
                    statusBadge = '<span style="font-size:10px; color:#dc2626; font-weight:600; display:flex; align-items:center; gap:3px;"><span style="display:inline-block; width:5px; height:5px; border-radius:50%; background:#dc2626;"></span> Ditolak</span>';
                } else {
                    statusBadge = '<span style="font-size:10px; color:#d97706; font-weight:600; display:flex; align-items:center; gap:3px;"><span style="display:inline-block; width:5px; height:5px; border-radius:50%; background:#d97706;"></span> Menunggu</span>';
                }

                itemDiv.innerHTML = `
                    <img src="${p.image}" class="active-item-thumb" alt="${p.title}" onerror="this.src='{{ asset('admin-assets/assets/Logo.png') }}'">
                    <div class="active-item-info">
                        <span class="active-item-name" title="${p.title}">${p.title}</span>
                        <span class="active-item-price">${p.price}</span>
                        ${statusBadge}
                    </div>
                `;
                listContainer.appendChild(itemDiv);
            });
        } else {
            listContainer.innerHTML = '<div style="font-size:12px; color:var(--text-muted); padding:10px;">Mahasiswa ini belum mengunggah barang preloved.</div>';
        }

        document.getElementById('modal-detail-user').classList.add('is-open');
    }

    function closeUserModal() {
        document.getElementById('modal-detail-user').classList.remove('is-open');
    }

    function triggerApproveFromDetail() {
        if (activeUserModalData) {
            closeUserModal();
            openApproveUserModal(activeUserModalData.approve_url, {
                name: activeUserModalData.name,
                email: activeUserModalData.email,
                whatsapp: activeUserModalData.whatsapp,
                avatar_url: activeUserModalData.avatar_url,
                major: activeUserModalData.major,
                created_at: activeUserModalData.created_at
            });
        }
    }

    function triggerSuspendFromDetail() {
        if (activeUserModalData) {
            closeUserModal();
            openSuspendUserModal(activeUserModalData.suspend_url, {
                name: activeUserModalData.name,
                email: activeUserModalData.email,
                avatar_url: activeUserModalData.avatar_url
            });
        }
    }

    // Modal Verifikasi & Setujui User
    function openApproveUserModal(approveUrl, userData) {
        document.getElementById('form-approve-user').action = approveUrl;
        document.getElementById('approve-user-name').textContent = userData.name;
        document.getElementById('approve-user-email').textContent = userData.email;

        // Avatar foto atau inisial
        const approveImg = document.getElementById('approve-user-avatar-img');
        const approveBox = document.getElementById('approve-user-avatar');
        if (userData.avatar_url) {
            approveImg.src = userData.avatar_url;
            approveImg.style.display = 'block';
            approveBox.style.display = 'none';
        } else {
            approveImg.style.display = 'none';
            approveBox.style.display = 'flex';
            approveBox.textContent = (userData.name || 'U').substring(0, 2).toUpperCase();
        }

        const approveMajor = document.getElementById('approve-user-major');
        if (approveMajor) {
            approveMajor.textContent = userData.major || 'S1 Informatika';
        }

        document.getElementById('modal-terima-user').classList.add('is-open');
    }

    function closeApproveUserModal() {
        document.getElementById('modal-terima-user').classList.remove('is-open');
    }

    // Modal Nonaktifkan User
    function openSuspendUserModal(suspendUrl, userData) {
        document.getElementById('form-suspend-user').action = suspendUrl;
        document.getElementById('suspend-user-name').textContent = userData.name;
        document.getElementById('suspend-user-email').textContent = userData.email;

        // Avatar foto atau inisial
        const suspendImg = document.getElementById('suspend-user-avatar-img');
        const suspendBox = document.getElementById('suspend-user-avatar');
        if (userData.avatar_url) {
            suspendImg.src = userData.avatar_url;
            suspendImg.style.display = 'block';
            suspendBox.style.display = 'none';
        } else {
            suspendImg.style.display = 'none';
            suspendBox.style.display = 'flex';
            suspendBox.textContent = (userData.name || 'U').substring(0, 2).toUpperCase();
        }

        document.getElementById('suspend-reason-select').selectedIndex = 0;
        document.getElementById('suspend-note').value = '';

        document.getElementById('modal-suspend-user').classList.add('is-open');
    }

    function closeSuspendUserModal() {
        document.getElementById('modal-suspend-user').classList.remove('is-open');
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeUserModal();
            closeApproveUserModal();
            closeSuspendUserModal();
        }
    });
</script>
@endpush
