@extends('layouts.admin')

@section('title', 'Dashboard')

@push('styles')
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
            max-width: 520px;
            box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25);
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }
        .modal-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 18px 24px;
            border-bottom: 1px solid var(--border);
        }
        .modal-header-left {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .modal-title {
            font-size: 16px;
            font-weight: 700;
            color: var(--text);
        }
        .modal-subtitle {
            font-size: 12px;
            color: var(--text-muted);
            margin-top: 2px;
        }
        .btn-close-modal {
            background: none;
            border: none;
            font-size: 24px;
            cursor: pointer;
            color: var(--text-light);
            line-height: 1;
        }
    </style>
@endpush

@section('content')
    <!-- Header Halaman -->
    <div class="page-header">
        <h1 class="page-title">
            Dashboard
            <span class="badge-live">Live Feed</span>
        </h1>

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

    @if (session('error'))
        <div style="background: #fef2f2; border: 1px solid #fecaca; color: #dc2626; padding: 12px 16px; border-radius: var(--radius); margin-bottom: 20px; font-size: 13px; display: flex; align-items: center; gap: 8px;">
            <span>⚠️</span>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <!-- ===== KARTU STATISTIK ===== -->
    <section aria-label="Ringkasan Performa Sistem">
        <div class="stats-grid">

            <!-- Barang Pending -->
            <article class="stat-card">
                <div class="stat-card-top">
                    <div class="stat-card-icon orange">
                        <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    </div>
                    <span class="stat-card-badge orange">⚡ Prioritas</span>
                </div>
                <p class="stat-card-title">Barang Pending</p>
                <p class="stat-card-number">{{ $pendingCount }} <span class="stat-delta orange">Butuh Kurasi</span></p>
                <p class="stat-card-desc">Perlu diperiksa &amp; validasi</p>
            </article>

            <!-- Barang Diterima -->
            <article class="stat-card">
                <div class="stat-card-top">
                    <div class="stat-card-icon green">
                        <svg viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                    </div>
                    <span class="stat-card-badge green">Aktif</span>
                </div>
                <p class="stat-card-title">Barang Diterima</p>
                <p class="stat-card-number">{{ $approvedCount }} <span class="stat-delta green">Tayang</span></p>
                <p class="stat-card-desc">Siap tampil di etalase kampus</p>
            </article>

            <!-- Barang Terjual -->
            <article class="stat-card">
                <div class="stat-card-top">
                    <div class="stat-card-icon blue">
                        <svg viewBox="0 0 24 24"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                    </div>
                    <span class="stat-card-badge blue">Sirkulasi Sukses</span>
                </div>
                <p class="stat-card-title">Barang Terjual</p>
                <p class="stat-card-number">{{ $soldCount }} <span class="stat-delta blue">Rp {{ number_format($soldTotalAmount, 0, ',', '.') }}</span></p>
                <p class="stat-card-desc">Sudah laku &amp; transaksi selesai</p>
            </article>

            <!-- Penjual Aktif -->
            <article class="stat-card">
                <div class="stat-card-top">
                    <div class="stat-card-icon navy">
                        <svg viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                    </div>
                    <span class="stat-card-badge navy">Akun Approved</span>
                </div>
                <p class="stat-card-title">Penjual Aktif</p>
                <p class="stat-card-number">{{ $activeSellers }} <span class="stat-delta blue">Mahasiswa</span></p>
                <p class="stat-card-desc">Akun terverifikasi aktif</p>
            </article>

        </div>
    </section>

    <!-- ===== TABEL: BARANG MENUNGGU PERSETUJUAN ===== -->
    <section class="section-card" aria-label="Barang Menunggu Persetujuan">
        <div class="section-header">
            <h2 class="section-title">
                Barang Menunggu Persetujuan
                <span class="badge-count">{{ $pendingProducts->count() }} Antrean</span>
            </h2>
            <a href="{{ Route::has('admin.products') ? route('admin.products', ['status' => 'pending']) : '#' }}" class="section-link">Lihat Semua →</a>
        </div>

        @if ($pendingProducts->isEmpty())
            <div style="text-align: center; padding: 40px 20px; color: var(--text-muted);">
                <div style="font-size: 32px; margin-bottom: 8px;">🎉</div>
                <p style="font-weight: 600;">Semua barang sudah diperiksa!</p>
                <p style="font-size: 12px; margin-top: 4px;">Tidak ada antrean barang baru yang menunggu persetujuan.</p>
            </div>
        @else
            <table class="data-table" aria-label="Daftar barang yang membutuhkan validasi admin">
                <thead>
                    <tr>
                        <th style="width: 70px;">Foto</th>
                        <th>Nama Barang</th>
                        <th>Penjual</th>
                        <th>Harga</th>
                        <th>Status</th>
                        <th style="text-align: right;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pendingProducts as $product)
                        <tr>
                            <td>
                                @if ($product->primaryImage)
                                    <img class="tbl-photo" src="{{ $product->primaryImage->url }}" alt="{{ $product->title }}">
                                @else
                                    <img class="tbl-photo" src="{{ asset('admin-assets/assets/Logo.png') }}" style="object-fit: contain; background: #f8fafc;" alt="{{ $product->title }}">
                                @endif
                            </td>
                            <td>
                                <p class="tbl-item-name">{{ $product->title }}</p>
                                <p class="tbl-item-meta">📂 {{ $product->category->name ?? 'Tanpa Kategori' }} &bull; Kondisi: {{ $product->condition }}</p>
                            </td>
                            <td>
                                <div class="seller-wrap">
                                    <div class="seller-avatar" style="background:#2563eb;">
                                        {{ strtoupper(substr($product->seller->name ?? 'U', 0, 2)) }}
                                    </div>
                                    <div>
                                        <p class="seller-info-name">{{ $product->seller->name ?? 'Mahasiswa' }}</p>
                                        <p class="seller-info-meta">{{ $product->seller->email ?? '-' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="tbl-price">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                            </td>
                            <td>
                                <span class="badge badge-pending">⏳ Pending</span>
                            </td>
                            <td>
                                <div class="actions-wrap" style="justify-content: flex-end;">
                                    <!-- Aksi Terima (Buka Popup Terima) -->
                                    <button type="button" 
                                            class="btn-sm btn-terima" 
                                            title="Setujui & Terbitkan Barang"
                                            onclick="openApproveModal('{{ route('admin.products.approve', $product) }}', {{ json_encode([
                                                'title'    => $product->title,
                                                'price'    => number_format($product->price, 0, ',', '.'),
                                                'category' => $product->category->name ?? 'Tanpa Kategori',
                                                'seller'   => $product->seller->name ?? 'Mahasiswa',
                                                'image'    => $product->primaryImage?->url ?? asset('admin-assets/assets/Logo.png')
                                            ]) }})">
                                        ✓ Terima
                                    </button>

                                    <!-- Aksi Tolak (Buka Popup Tolak) -->
                                    <button type="button" 
                                            class="btn-sm btn-tolak" 
                                            title="Tolak Barang"
                                            onclick="openRejectModal('{{ route('admin.products.reject', $product) }}', '{{ addslashes($product->title) }}')">
                                        ✕ Tolak
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </section>

    <!-- ===== TABEL: BARANG TERBARU ===== -->
    <section class="section-card" aria-label="Barang Terbaru">
        <div class="section-header">
            <h2 class="section-title">Barang Terbaru</h2>
            <a href="{{ Route::has('admin.products') ? route('admin.products') : '#' }}" class="section-link">Lihat Semua →</a>
        </div>

        @if ($latestProducts->isEmpty())
            <div style="text-align: center; padding: 40px 20px; color: var(--text-muted);">
                <p>Belum ada produk yang diunggah.</p>
            </div>
        @else
            <table class="data-table" aria-label="Daftar barang terbaru yang diunggah pengguna">
                <thead>
                    <tr>
                        <th style="width: 70px;">Foto</th>
                        <th>Nama Barang</th>
                        <th>Penjual</th>
                        <th>Harga</th>
                        <th>Status</th>
                        <th>Tanggal Upload</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($latestProducts as $item)
                        <tr>
                            <td>
                                @if ($item->primaryImage)
                                    <img class="tbl-photo" src="{{ $item->primaryImage->url }}" alt="{{ $item->title }}">
                                @else
                                    <img class="tbl-photo" src="{{ asset('admin-assets/assets/Logo.png') }}" style="object-fit: contain; background: #f8fafc;" alt="{{ $item->title }}">
                                @endif
                            </td>
                            <td>
                                <p class="tbl-item-name">{{ $item->title }}</p>
                                <p class="tbl-item-meta">📂 {{ $item->category->name ?? 'Umum' }}</p>
                            </td>
                            <td>
                                <div class="seller-wrap">
                                    <div class="seller-avatar" style="background:#4b5563;">
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
                            <td style="color: var(--text-muted); font-size: 12px;">
                                {{ $item->created_at ? $item->created_at->translatedFormat('d M Y, H:i') : '-' }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </section>

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
    // Modal Terima Barang
    function openApproveModal(approveUrl, productData) {
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

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            closeRejectModal();
            closeApproveModal();
        }
    });
</script>
@endpush
