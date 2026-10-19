@extends('layouts.admin')

@section('title', 'Moderasi Barang')

@push('styles')
    <link rel="stylesheet" href="{{ asset('admin-assets/style/moderasi_barang.css') }}">
    <link rel="stylesheet" href="{{ asset('admin-assets/style/detail_barang.css') }}">
    <link rel="stylesheet" href="{{ asset('admin-assets/style/popup_tolak.css') }}">
    <style>
        .modal-overlay {
            display: none;
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(4px);
            z-index: 999;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .modal-overlay.is-open {
            display: flex;
        }
        .modal-dialog {
            background: #ffffff;
            border-radius: var(--radius);
            width: 100%;
            max-width: 860px;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25);
            display: flex;
            flex-direction: column;
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
            <h1 class="page-title">Moderasi Barang</h1>
            <span class="badge-subtitle">Katalog Kampus</span>
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
            <nav class="filter-tabs" aria-label="Filter Status Barang">
                <a href="{{ route('admin.products', array_merge(request()->except('status', 'page'), ['status' => 'all'])) }}" 
                   class="filter-tab {{ $statusFilter === 'all' ? 'active' : '' }}">
                   Semua <span class="badge-tab-count">{{ $allCount }}</span>
                </a>
                <a href="{{ route('admin.products', array_merge(request()->except('status', 'page'), ['status' => 'pending'])) }}" 
                   class="filter-tab {{ $statusFilter === 'pending' ? 'active' : '' }}">
                   Menunggu Persetujuan <span class="badge-tab-count">{{ $pendingCount }}</span>
                </a>
                <a href="{{ route('admin.products', array_merge(request()->except('status', 'page'), ['status' => 'approved'])) }}" 
                   class="filter-tab {{ $statusFilter === 'approved' ? 'active' : '' }}">
                   Diterima <span class="badge-tab-count">{{ $approvedCount }}</span>
                </a>
                <a href="{{ route('admin.products', array_merge(request()->except('status', 'page'), ['status' => 'rejected'])) }}" 
                   class="filter-tab {{ $statusFilter === 'rejected' ? 'active' : '' }}">
                   Ditolak <span class="badge-tab-count">{{ $rejectedCount }}</span>
                </a>
            </nav>
        </div>

        <!-- Search & Filter Form -->
        <form class="search-filter-row" action="{{ route('admin.products') }}" method="GET" role="search">
            @if ($statusFilter !== 'all')
                <input type="hidden" name="status" value="{{ $statusFilter }}">
            @endif

            <div class="search-input-wrap">
                <svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                <input type="search"
                       id="cari-barang"
                       name="search"
                       value="{{ $searchQuery }}"
                       placeholder="Cari berdasarkan nama barang, deskripsi, atau penjual..."
                       aria-label="Cari barang">
            </div>

            <select class="filter-select" id="kategori" name="kategori" onchange="this.form.submit()">
                <option value="">Semua Kategori</option>
                @foreach ($categories as $cat)
                    <option value="{{ $cat->id }}" {{ $categoryFilter == $cat->id ? 'selected' : '' }}>
                        {{ $cat->name }}
                    </option>
                @endforeach
            </select>

            <select class="filter-select" id="urutkan" name="urutkan" onchange="this.form.submit()">
                <option value="terbaru" {{ $sortBy === 'terbaru' ? 'selected' : '' }}>Terbaru Diunggah</option>
                <option value="terlama" {{ $sortBy === 'terlama' ? 'selected' : '' }}>Terlama</option>
                <option value="harga-asc" {{ $sortBy === 'harga-asc' ? 'selected' : '' }}>Harga Terendah</option>
                <option value="harga-desc" {{ $sortBy === 'harga-desc' ? 'selected' : '' }}>Harga Tertinggi</option>
            </select>
        </form>

        <!-- Tabel Data Barang -->
        @if ($products->isEmpty())
            <div style="text-align: center; padding: 50px 20px; color: var(--text-muted);">
                <div style="font-size: 40px; margin-bottom: 8px;">📦</div>
                <h3 style="font-size: 16px; font-weight: 700; color: var(--text);">Tidak ada barang yang ditemukan</h3>
                <p style="font-size: 13px; margin-top: 4px;">Coba sesuaikan kata kunci pencarian atau ganti filter status di atas.</p>
            </div>
        @else
            <table class="data-table" aria-label="Daftar antrean moderasi barang">
                <thead>
                    <tr>
                        <th style="width: 70px;">Foto</th>
                        <th>Info Barang</th>
                        <th>Penjual / Mahasiswa</th>
                        <th>Harga</th>
                        <th>Status</th>
                        <th style="text-align: right; width: 170px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($products as $item)
                        @php
                            $primaryImg = $item->primaryImage?->url ?? ($item->images->first()?->url ?? asset('admin-assets/assets/Logo.png'));
                            $allImages = $item->images->map(fn($img) => $img->url)->values();
                            if ($allImages->isEmpty()) {
                                $allImages = collect([$primaryImg]);
                            }
                        @endphp
                        <tr>
                            <td>
                                <img class="tbl-photo" src="{{ $primaryImg }}" alt="{{ $item->title }}">
                            </td>
                            <td>
                                <p class="tbl-item-name">{{ $item->title }}</p>
                                <p class="tbl-item-meta">
                                    📂 {{ $item->category->name ?? 'Tanpa Kategori' }} &bull; Kondisi: <strong>{{ $item->condition }}</strong>
                                </p>
                            </td>
                            <td>
                                <div class="seller-wrap">
                                    <div class="seller-avatar" style="background:#2563eb;">
                                        {{ strtoupper(substr($item->seller->name ?? 'U', 0, 2)) }}
                                    </div>
                                    <div>
                                        <p class="seller-info-name">{{ $item->seller->name ?? 'Mahasiswa' }}</p>
                                        <p class="seller-info-meta">{{ $item->seller->email ?? '-' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="tbl-price">Rp {{ number_format($item->price, 0, ',', '.') }}</span>
                            </td>
                            <td>
                                @if ($item->status === 'approved')
                                    <span class="badge badge-diterima">✓ Diterima</span>
                                @elseif ($item->status === 'pending')
                                    <span class="badge badge-pending">⏳ Pending</span>
                                @elseif ($item->status === 'rejected')
                                    <span class="badge badge-ditolak">✕ Ditolak</span>
                                @elseif ($item->status === 'sold')
                                    <span class="badge" style="background: #f1f5f9; color: #475569;">🤝 Terjual</span>
                                @else
                                    <span class="badge">{{ ucfirst($item->status) }}</span>
                                @endif
                            </td>
                            <td>
                                <div class="actions-wrap" style="justify-content: flex-end; gap: 6px;">
                                    <!-- Tombol Detail (Buka Modal) -->
                                    <button type="button" 
                                            class="btn-icon" 
                                            title="Lihat Detail Barang"
                                            onclick="openDetailModal({{ json_encode([
                                                'id'          => $item->id,
                                                'title'       => $item->title,
                                                'price'       => number_format($item->price, 0, ',', '.'),
                                                'category'    => $item->category->name ?? 'Tanpa Kategori',
                                                'condition'   => $item->condition,
                                                'location'    => $item->location ?? '-',
                                                'cod_area'    => $item->cod_area ?? '-',
                                                'description' => $item->description,
                                                'status'      => $item->status,
                                                'created_at'  => $item->created_at ? $item->created_at->translatedFormat('d F Y, H:i') : '-',
                                                'seller_name' => $item->seller->name ?? 'Mahasiswa',
                                                'seller_email'=> $item->seller->email ?? '-',
                                                'seller_wa'   => $item->seller->whatsapp_number ?? '-',
                                                'images'      => $allImages,
                                                'approve_url' => route('admin.products.approve', $item),
                                                'reject_url'  => route('admin.products.reject', $item),
                                            ]) }})">
                                        <svg viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                    </button>

                                    @if ($item->status === 'pending')
                                        <!-- Tombol Terima (Buka Popup Terima) -->
                                        <button type="button" 
                                                class="btn-sm btn-terima" 
                                                title="Setujui & Terbitkan Barang"
                                                onclick="openApproveModal('{{ route('admin.products.approve', $item) }}', {{ json_encode([
                                                    'title'    => $item->title,
                                                    'price'    => number_format($item->price, 0, ',', '.'),
                                                    'category' => $item->category->name ?? 'Tanpa Kategori',
                                                    'seller'   => $item->seller->name ?? 'Mahasiswa',
                                                    'image'    => $primaryImg
                                                ]) }})">
                                            ✓
                                        </button>

                                        <!-- Tombol Tolak (Buka Popup Tolak) -->
                                        <button type="button" 
                                                class="btn-sm btn-tolak" 
                                                title="Tolak Barang"
                                                onclick="openRejectModal('{{ route('admin.products.reject', $item) }}', '{{ addslashes($item->title) }}')">
                                            ✕
                                        </button>
                                    @endif

                                    <!-- Tombol Hapus -->
                                    <form action="{{ route('admin.products.destroy', $item) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus barang ini secara permanen?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-icon" style="color: #ef4444;" title="Hapus Permanen">
                                            <svg viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Pagination Bar -->
            <div class="pagination-container">
                <div style="font-size: 13px; color: var(--text-muted);">
                    Menampilkan <strong>{{ $products->firstItem() ?? 0 }}</strong> - <strong>{{ $products->lastItem() ?? 0 }}</strong> dari <strong>{{ $products->total() }}</strong> barang
                </div>
                <div class="pagination-links">
                    {{ $products->links('pagination::simple-default') }}
                </div>
            </div>
        @endif

    </section>

    <!-- =============================================
         POPUP MODAL: DETAIL & KEPUTUSAN MODERASI
         ============================================= -->
    <div class="modal-overlay" id="modal-detail-barang" onclick="if(event.target === this) closeDetailModal();">
        <div class="modal-dialog">

            <!-- Header Modal -->
            <div class="modal-header">
                <div class="modal-header-left">
                    <div class="modal-icon-badge">
                        <svg viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M7 8h10"/><path d="M7 12h10"/><path d="M7 16h6"/></svg>
                    </div>
                    <div class="modal-title-group">
                        <div class="modal-title-row">
                            <h2 class="modal-title" id="m-title">Detail Barang</h2>
                            <span id="m-status-badge" class="badge-menunggu-moderasi">Pending</span>
                        </div>
                        <p class="modal-subtitle">Kategori: <strong id="m-category">Buku</strong> &bull; Diunggah: <span id="m-date">-</span></p>
                    </div>
                </div>
                <button type="button" class="btn-close-modal" onclick="closeDetailModal()">&times;</button>
            </div>

            <!-- Body Modal -->
            <div class="modal-body">
                <!-- Kolom Kiri: Galeri Foto -->
                <div class="gallery-column">
                    <div class="main-photo-wrap">
                        <span class="photo-badge-top">
                            <svg style="width:12px;height:12px;stroke:currentColor;fill:none;stroke-width:2;" viewBox="0 0 24 24"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/><circle cx="12" cy="13" r="4"/></svg>
                            Foto Fisik Barang
                        </span>
                        <img id="m-mainPhoto" src="" alt="Foto Utama" class="main-photo-img" style="max-height: 260px; object-fit: contain; width: 100%; background: #f8fafc;">
                    </div>

                    <div class="thumbs-row" id="m-thumbs-container">
                        <!-- Thumbnail items dynamically populated -->
                    </div>
                </div>

                <!-- Kolom Kanan: Detail Informasi Barang -->
                <div class="info-column">
                    <h3 class="info-item-title" id="m-name">-</h3>
                    <div class="info-price" id="m-price">Rp 0</div>

                    <div style="margin-bottom: 12px; font-size: 13px; color: var(--text-muted);">
                        <span>Kondisi: <strong id="m-condition">-</strong></span> &bull;
                        <span>Lokasi / COD: <strong id="m-location">-</strong></span>
                    </div>

                    <h4 class="section-sublabel">Deskripsi Barang:</h4>
                    <div class="item-desc-box" id="m-description" style="max-height: 120px; overflow-y: auto;">
                        -
                    </div>

                    <!-- Profil Penjual -->
                    <div class="meta-cards-grid" style="margin-top: 14px;">
                        <div class="meta-subcard" style="width: 100%;">
                            <div class="meta-subcard-title">Data Penjual / Mahasiswa</div>
                            <div class="seller-profile-row">
                                <div class="seller-avatar" id="m-seller-avatar" style="background:#2563eb; width:34px; height:34px; border-radius:50%; color:#fff; display:flex; align-items:center; justify-content:center; font-weight:700;">U</div>
                                <div>
                                    <p class="seller-name-bold" id="m-seller-name">-</p>
                                    <p class="seller-sub-meta" id="m-seller-email">-</p>
                                </div>
                            </div>
                            <div style="margin-top: 8px;">
                                <a id="m-seller-wa-btn" href="#" target="_blank" style="display:inline-flex; align-items:center; gap:6px; font-size:12px; font-weight:600; color:#16a34a; background:#f0fdf4; border:1px solid #bbf7d0; padding:4px 10px; border-radius:6px; text-decoration:none;">
                                    💬 Hubungi via WhatsApp: <span id="m-seller-wa">-</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer Modal: Aksi Keputusan -->
            <div class="modal-footer" style="padding: 16px 24px; border-top: 1px solid var(--border); display: flex; align-items: center; justify-content: flex-end; gap: 10px; background: #fafafa;">
                <button type="button" 
                        class="btn-sm btn-tolak" 
                        style="padding: 9px 20px; font-size: 13px;"
                        onclick="triggerRejectFromDetail()">
                    ✕ Tolak Barang
                </button>

                <button type="button" 
                        class="btn-sm btn-terima" 
                        style="padding: 9px 22px; font-size: 13px; font-weight: 700;"
                        onclick="triggerApproveFromDetail()">
                    ✓ Setujui &amp; Terbitkan
                </button>
            </div>

        </div>
    </div>

    <!-- =============================================
         POPUP MODAL: TERIMA & TERBITKAN BARANG
         ============================================= -->
    <div class="modal-overlay" id="modal-terima-barang" onclick="if(event.target === this) closeApproveModal();">
        <div class="modal-dialog modal-dialog-sm" style="border-top: 4px solid #2db87a;">

            <!-- Header Modal -->
            <div class="modal-header">
                <div class="modal-header-left">
                    <div style="width: 40px; height: 40px; border-radius: 10px; background: #e6f9f1; color: #2db87a; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                        <svg viewBox="0 0 24 24" style="width: 22px; height: 22px; stroke: currentColor; fill: none; stroke-width: 2.2;"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                    </div>
                    <div class="modal-title-group">
                        <h2 class="modal-title" style="color: #065f46;">Setujui &amp; Terbitkan Barang</h2>
                        <p class="modal-subtitle">Barang akan langsung lolos kurasi dan tayang di etalase katalog.</p>
                    </div>
                </div>
                <button type="button" class="btn-close-modal" aria-label="Batal dan tutup" onclick="closeApproveModal()">&times;</button>
            </div>

            <!-- Formulir Persetujuan -->
            <form id="form-approve-product" action="" method="POST">
                @csrf
                <div class="modal-reject-body" style="padding: 20px 24px;">

                    <!-- Kartu Preview Barang -->
                    <div style="background: #f8fafc; border: 1px solid var(--border); border-radius: 10px; padding: 12px; display: flex; align-items: center; gap: 12px; margin-bottom: 16px;">
                        <img id="approve-item-photo" src="" alt="Preview" style="width: 54px; height: 54px; object-fit: cover; border-radius: 8px; border: 1px solid #e2e8f0; background: #fff;">
                        <div style="flex: 1; min-width: 0;">
                            <h4 id="approve-item-title" style="font-size: 13px; font-weight: 700; color: var(--text); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; margin-bottom: 2px;">-</h4>
                            <p style="font-size: 11px; color: var(--text-muted);">
                                <span id="approve-item-category">-</span> &bull; Penjual: <strong id="approve-item-seller">-</strong>
                            </p>
                            <div id="approve-item-price" style="font-size: 13px; font-weight: 800; color: #059669; margin-top: 2px;">Rp 0</div>
                        </div>
                    </div>

                    <!-- Checklist Standar Kurasi Kampus -->
                    <div style="background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 8px; padding: 12px 14px; margin-bottom: 14px;">
                        <p style="font-size: 12px; font-weight: 700; color: #166534; margin-bottom: 6px;">Checklist Standar Kurasi Kampus:</p>
                        <ul style="font-size: 11px; color: #15803d; line-height: 1.6; list-style: none; padding-left: 0;">
                            <li style="display:flex; align-items:center; gap:6px;">
                                <svg style="width:13px;height:13px;stroke:#16a34a;stroke-width:2.5;fill:none;" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
                                Foto fisik barang asli, jelas, dan representatif
                            </li>
                            <li style="display:flex; align-items:center; gap:6px;">
                                <svg style="width:13px;height:13px;stroke:#16a34a;stroke-width:2.5;fill:none;" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
                                Kondisi dan deskripsi tidak mengandung unsur manipulasi
                            </li>
                            <li style="display:flex; align-items:center; gap:6px;">
                                <svg style="width:13px;height:13px;stroke:#16a34a;stroke-width:2.5;fill:none;" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
                                Harga wajar dan terjangkau untuk mahasiswa
                            </li>
                        </ul>
                    </div>

                    <div class="form-reject-group no-margin">
                        <label for="catatan-approve" style="display: block; font-size: 12px; font-weight: 600; color: var(--text); margin-bottom: 4px;">
                            Pesan Apresiasi untuk Mahasiswa <span class="label-optional">(Opsional)</span>
                        </label>
                        <input type="text" id="catatan-approve" name="catatan_admin" placeholder="Contoh: Iklan Anda telah disetujui dan siap dibeli teman kampus!" style="width: 100%; padding: 9px 12px; border: 1.5px solid var(--border); border-radius: 8px; font-size: 12px; background: #fff; outline:none;">
                    </div>

                </div>

                <!-- Footer Modal -->
                <div class="modal-footer" style="padding: 16px 24px; border-top: 1px solid var(--border); display: flex; align-items: center; justify-content: flex-end; gap: 10px; background: #fafafa;">
                    <button type="button" class="btn-modal-action btn-minta-foto" onclick="closeApproveModal()" style="border: 1px solid var(--border); background: #fff; padding: 8px 16px; border-radius: 6px; cursor: pointer; font-size: 13px;">
                        Batal
                    </button>
                    <button type="submit" class="btn-modal-action" style="background: #2db87a; color: #ffffff; border: none; padding: 9px 22px; border-radius: 6px; cursor: pointer; display: inline-flex; align-items: center; gap: 6px; font-weight: 700; font-size: 13px; box-shadow: 0 2px 4px rgba(45,184,122,0.3);">
                        <svg viewBox="0 0 24 24" style="width: 15px; height: 15px; stroke: #fff; fill: none; stroke-width: 2.4;"><polyline points="20 6 9 17 4 12"/></svg>
                        Konfirmasi Terima &amp; Terbitkan
                    </button>
                </div>
            </form>

        </div>
    </div>

    <!-- =============================================
         POPUP MODAL: TOLAK PENAYANGAN BARANG (popup_tolak.html)
         ============================================= -->
    <div class="modal-overlay" id="modal-tolak-barang" onclick="if(event.target === this) closeRejectModal();">
        <div class="modal-dialog modal-dialog-sm">

            <!-- Header Modal -->
            <div class="modal-header">
                <div class="modal-header-left">
                    <div class="badge-warning-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                    </div>
                    <div class="modal-title-group">
                        <h2 class="modal-title" id="reject-title">Tolak Penayangan Barang</h2>
                        <p class="modal-subtitle" id="reject-product-subtitle">Pemberitahuan akan dikirim ke mahasiswa terkait.</p>
                    </div>
                </div>
                <button type="button" class="btn-close-modal" aria-label="Batal dan tutup" onclick="closeRejectModal()">&times;</button>
            </div>

            <!-- Formulir Penolakan -->
            <form id="form-reject-product" action="" method="POST">
                @csrf
                <div class="modal-reject-body">
                    <div class="form-reject-group">
                        <label for="alasan-penolakan">Alasan Penolakan</label>
                        <select id="alasan-penolakan" name="alasan" required>
                            <option value="Foto Buram / Tidak Sesuai Fisik Asli" selected>Foto Buram / Tidak Sesuai Fisik Asli</option>
                            <option value="Kategori Barang Tidak Sesuai">Kategori Barang Tidak Sesuai</option>
                            <option value="Barang Terlarang / Melanggar Aturan Kampus">Barang Terlarang / Melanggar Aturan Kampus</option>
                            <option value="Indikasi Barang Tiruan (KW)">Indikasi Barang Tiruan (KW)</option>
                            <option value="Harga Tidak Wajar / Manipulatif">Harga Tidak Wajar / Manipulatif</option>
                        </select>
                    </div>

                    <div class="form-reject-group no-margin">
                        <label for="catatan-tambahan">Catatan Tambahan untuk Mahasiswa <span class="label-optional">(Opsional)</span></label>
                        <textarea id="catatan-tambahan" name="catatan" rows="4" placeholder="Sebutkan secara rinci bagian yang perlu diperbaiki oleh mahasiswa..."></textarea>
                    </div>
                </div>

                <!-- Footer Modal -->
                <div class="modal-footer" style="padding: 16px 24px; border-top: 1px solid var(--border); display: flex; align-items: center; justify-content: flex-end; gap: 10px; background: #fafafa;">
                    <button type="button" class="btn-modal-action btn-minta-foto" onclick="closeRejectModal()" style="border: 1px solid var(--border); background: #fff; padding: 8px 16px; border-radius: 6px; cursor: pointer; font-size: 13px;">
                        Batal
                    </button>
                    <button type="submit" class="btn-modal-action btn-confirm-reject" style="background: var(--red); color: #ffffff; border: none; padding: 8px 18px; border-radius: 6px; cursor: pointer; display: inline-flex; align-items: center; gap: 6px; font-weight: 600; font-size: 13px;">
                        <svg viewBox="0 0 24 24" style="width: 14px; height: 14px; stroke: #fff; fill: none; stroke-width: 2;"><circle cx="12" cy="12" r="10"/><line x1="4.93" y1="4.93" x2="19.07" y2="19.07"/></svg>
                        Konfirmasi Tolak
                    </button>
                </div>
            </form>

        </div>
    </div>
@endsection

@push('scripts')
<script>
    let activeProductData = null;

    function openDetailModal(data) {
        activeProductData = data;

        document.getElementById('m-name').textContent = data.title;
        document.getElementById('m-price').textContent = 'Rp ' + data.price;
        document.getElementById('m-category').textContent = data.category;
        document.getElementById('m-condition').textContent = data.condition;
        document.getElementById('m-location').textContent = (data.location || '-') + ' / ' + (data.cod_area || '-');
        document.getElementById('m-description').textContent = data.description || 'Tidak ada deskripsi.';
        document.getElementById('m-date').textContent = data.created_at;

        // Seller
        document.getElementById('m-seller-name').textContent = data.seller_name;
        document.getElementById('m-seller-email').textContent = data.seller_email;
        document.getElementById('m-seller-wa').textContent = data.seller_wa;
        document.getElementById('m-seller-wa-btn').href = 'https://wa.me/' + (data.seller_wa ? data.seller_wa.replace(/[^0-9]/g, '') : '');
        document.getElementById('m-seller-avatar').textContent = (data.seller_name || 'U').substring(0, 2).toUpperCase();

        // Status badge
        const badge = document.getElementById('m-status-badge');
        badge.textContent = data.status.toUpperCase();

        // Images gallery
        const mainPhoto = document.getElementById('m-mainPhoto');
        const thumbsContainer = document.getElementById('m-thumbs-container');
        thumbsContainer.innerHTML = '';

        if (data.images && data.images.length > 0) {
            mainPhoto.src = data.images[0];
            data.images.forEach((imgUrl, idx) => {
                const thumb = document.createElement('div');
                thumb.className = 'thumb-item ' + (idx === 0 ? 'active' : '');
                thumb.style.cursor = 'pointer';
                thumb.innerHTML = `<img src="${imgUrl}" class="thumb-img" style="width: 48px; height: 48px; object-fit: cover; border-radius: 6px;">`;
                thumb.onclick = function () {
                    mainPhoto.src = imgUrl;
                    document.querySelectorAll('.thumb-item').forEach(el => el.classList.remove('active'));
                    thumb.classList.add('active');
                };
                thumbsContainer.appendChild(thumb);
            });
        }

        document.getElementById('modal-detail-barang').classList.add('is-open');
    }

    function closeDetailModal() {
        document.getElementById('modal-detail-barang').classList.remove('is-open');
    }

    function triggerRejectFromDetail() {
        if (activeProductData) {
            openRejectModal(activeProductData.reject_url, activeProductData.title);
        }
    }

    function triggerApproveFromDetail() {
        if (activeProductData) {
            openApproveModal(activeProductData.approve_url, {
                title: activeProductData.title,
                price: activeProductData.price,
                category: activeProductData.category,
                seller: activeProductData.seller_name,
                image: (activeProductData.images && activeProductData.images.length > 0) ? activeProductData.images[0] : ''
            });
        }
    }

    // Modal Terima Barang
    function openApproveModal(approveUrl, productData) {
        closeDetailModal();

        document.getElementById('form-approve-product').action = approveUrl;
        document.getElementById('approve-item-title').textContent = productData.title;
        document.getElementById('approve-item-category').textContent = productData.category;
        document.getElementById('approve-item-seller').textContent = productData.seller;
        document.getElementById('approve-item-price').textContent = 'Rp ' + productData.price;
        document.getElementById('approve-item-photo').src = productData.image || '{{ asset("admin-assets/assets/Logo.png") }}';

        document.getElementById('modal-terima-barang').classList.add('is-open');
    }

    function closeApproveModal() {
        document.getElementById('modal-terima-barang').classList.remove('is-open');
    }

    // Modal Tolak Barang
    function openRejectModal(rejectUrl, productTitle) {
        closeDetailModal();

        document.getElementById('form-reject-product').action = rejectUrl;
        if (productTitle) {
            document.getElementById('reject-product-subtitle').innerHTML = 'Barang: <strong>' + productTitle + '</strong>';
        } else {
            document.getElementById('reject-product-subtitle').textContent = 'Pemberitahuan akan dikirim ke mahasiswa terkait.';
        }

        document.getElementById('modal-tolak-barang').classList.add('is-open');
    }

    function closeRejectModal() {
        document.getElementById('modal-tolak-barang').classList.remove('is-open');
    }

    // Tutup modal jika tombol ESC ditekan
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            closeDetailModal();
            closeRejectModal();
            closeApproveModal();
        }
    });
</script>
@endpush
