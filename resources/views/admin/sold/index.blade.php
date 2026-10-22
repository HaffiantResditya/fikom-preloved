@extends('layouts.admin')

@section('title', 'Barang Terjual')

@push('styles')
    <link rel="stylesheet" href="{{ asset('admin-assets/style/barang_terjual.css') }}">
    <link rel="stylesheet" href="{{ asset('admin-assets/style/detail.css') }}">
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
        .detail-trx-dialog {
            background: #ffffff;
            border-radius: var(--radius);
            width: 100%;
            max-width: 780px;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25);
            display: flex;
            flex-direction: column;
        }
        .stats-mini-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 16px;
            margin-bottom: 24px;
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
            <h1 class="page-title">Barang Terjual</h1>
            <span class="badge-audit-subtitle">Audit &amp; Pemantauan Transaksi Selesai</span>
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

    <!-- 3 Mini Statistik Card -->
    <div class="stats-mini-grid">
        <div style="background: #ffffff; border: 1px solid var(--border); border-radius: var(--radius); padding: 18px 20px; box-shadow: var(--shadow);">
            <div style="font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase;">Total Barang Terjual</div>
            <div style="font-size: 26px; font-weight: 800; color: var(--text); margin-top: 4px;">{{ $totalSoldCount }} <span style="font-size: 13px; font-weight: 600; color: #2db87a;">Unit</span></div>
            <p style="font-size: 11px; color: var(--text-muted); margin-top: 2px;">Transaksi mahasiswa terselesaikan</p>
        </div>

        <div style="background: #ffffff; border: 1px solid var(--border); border-radius: var(--radius); padding: 18px 20px; box-shadow: var(--shadow);">
            <div style="font-size: 11px; font-weight: 700; color: #2563eb; text-transform: uppercase;">Perputaran Uang Mahasiswa</div>
            <div style="font-size: 26px; font-weight: 800; color: #2563eb; margin-top: 4px;">Rp {{ number_format($totalSoldAmount, 0, ',', '.') }}</div>
            <p style="font-size: 11px; color: var(--text-muted); margin-top: 2px;">Total sirkulasi nilai preloved</p>
        </div>

        <div style="background: #ffffff; border: 1px solid var(--border); border-radius: var(--radius); padding: 18px 20px; box-shadow: var(--shadow);">
            <div style="font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase;">Rata-Rata Nilai Barang</div>
            <div style="font-size: 26px; font-weight: 800; color: var(--text); margin-top: 4px;">Rp {{ number_format($averagePrice, 0, ',', '.') }}</div>
            <p style="font-size: 11px; color: var(--text-muted); margin-top: 2px;">Harga transaksi wajar kampus</p>
        </div>
    </div>

    <!-- Search Bar Full -->
    <div class="search-card-wrap" style="margin-bottom: 20px;">
        <form action="{{ route('admin.sold') }}" method="GET" role="search">
            <div class="search-full-input">
                <svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                <input
                    type="search"
                    id="search-trx"
                    name="search"
                    value="{{ $searchQuery }}"
                    placeholder="Cari berdasarkan nama barang, penjual, lokasi COD..."
                    aria-label="Cari transaksi">
            </div>
        </form>
    </div>

    <!-- Card Tabel Konten -->
    <section class="section-card">
        @if ($soldProducts->isEmpty())
            <div style="text-align: center; padding: 50px 20px; color: var(--text-muted);">
                <div style="font-size: 40px; margin-bottom: 8px;">🤝</div>
                <h3 style="font-size: 16px; font-weight: 700; color: var(--text);">Belum ada barang terjual</h3>
                <p style="font-size: 13px; margin-top: 4px;">Barang yang ditandai telah terjual oleh penjual akan muncul di sini untuk rekap audit.</p>
            </div>
        @else
            <table class="data-table" aria-label="Daftar riwayat transaksi barang terjual">
                <thead>
                    <tr>
                        <th>TRANSAKSI &amp; WAKTU</th>
                        <th>BARANG TERJUAL</th>
                        <th>PENJUAL</th>
                        <th>TITIK COD AMAN</th>
                        <th>STATUS PENJUALAN</th>
                        <th>STATUS AUDIT</th>
                        <th style="text-align: right; width: 120px;">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($soldProducts as $item)
                        @php
                            $trxCode = 'TRX-' . ($item->updated_at ? $item->updated_at->format('Ymd') : date('Ymd')) . '-' . sprintf('%03d', $item->id);
                            $primaryImg = $item->primaryImage?->url ?? ($item->images->first()?->url ?? asset('admin-assets/assets/Logo.png'));
                        @endphp
                        <tr>
                            <td>
                                <a href="javascript:void(0)" 
                                   class="trx-id" 
                                   style="font-weight: 700; color: #2563eb;"
                                   onclick="openTrxModal({{ json_encode([
                                       'trx_code'    => $trxCode,
                                       'title'       => $item->title,
                                       'price'       => number_format($item->price, 0, ',', '.'),
                                       'category'    => $item->category->name ?? 'Katalog Kampus',
                                       'condition'   => $item->condition,
                                       'description' => $item->description,
                                       'location'    => $item->location ?? 'Area Kampus',
                                       'cod_area'    => $item->cod_area ?? 'Kampus Utama',
                                       'seller_name' => $item->seller->name ?? 'Mahasiswa',
                                       'seller_email'=> $item->seller->email ?? '-',
                                       'seller_wa'   => $item->seller->whatsapp_number ?? '-',
                                       'date'        => $item->updated_at ? $item->updated_at->translatedFormat('d F Y, H:i') : '-',
                                       'image'       => $primaryImg
                                   ]) }})">
                                    {{ $trxCode }}
                                </a>
                                <p class="trx-time" style="font-size: 11px; color: var(--text-muted); margin-top: 2px;">
                                    {{ $item->updated_at ? $item->updated_at->translatedFormat('d M Y, H:i') : '-' }} WIB
                                </p>
                            </td>
                            <td>
                                <div class="product-cell">
                                    <img src="{{ $primaryImg }}" alt="{{ $item->title }}" class="product-img" style="width: 44px; height: 44px; object-fit: cover; border-radius: 6px;">
                                    <div class="product-info">
                                        <p class="product-title" style="font-weight: 700; font-size: 13px;">{{ $item->title }}</p>
                                        <p class="product-cat" style="font-size: 11px; color: var(--text-muted);">{{ $item->category->name ?? 'Kategori' }} &bull; {{ $item->condition }}</p>
                                        <p class="product-price" style="font-weight: 700; color: #059669; font-size: 12px;">Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="user-cell">
                                    @if ($item->seller?->profile_photo_url)
                                        <img src="{{ $item->seller->profile_photo_url }}" alt="{{ $item->seller->name }}" class="user-badge-icon" style="width: 24px; height: 24px; border-radius: 50%; object-fit: cover; border: 1px solid var(--border);" onerror="this.style.display='none'; this.nextElementSibling.style.display='inline-flex';">
                                        <span class="user-badge-icon" aria-hidden="true" style="display: none; width: 24px; height: 24px; border-radius: 50%; background: #2563eb; color: #fff; font-size: 11px; align-items: center; justify-content: center; font-weight: 700;">
                                            {{ strtoupper(substr($item->seller->name ?? 'U', 0, 1)) }}
                                        </span>
                                    @else
                                        <span class="user-badge-icon" aria-hidden="true" style="width: 24px; height: 24px; border-radius: 50%; background: #2563eb; color: #fff; font-size: 11px; display: inline-flex; align-items: center; justify-content: center; font-weight: 700;">
                                            {{ strtoupper(substr($item->seller->name ?? 'U', 0, 1)) }}
                                        </span>
                                    @endif
                                    <div>
                                        <p class="user-name" style="font-weight: 600; font-size: 12px;">{{ $item->seller->name ?? 'Mahasiswa' }}</p>
                                        <p style="font-size: 11px; color: var(--text-muted);">{{ $item->seller->email ?? '-' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="cod-cell">
                                    <div class="cod-loc-line" style="display: flex; align-items: center; gap: 4px; font-size: 12px; font-weight: 600; color: var(--text);">
                                        <svg class="cod-pin-icon" style="width: 13px; height: 13px; stroke: #2563eb; fill: none; stroke-width: 2;" viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                                        <span>{{ $item->location ?? 'Area Kampus' }}</span>
                                    </div>
                                    <p class="cod-sub" style="font-size: 11px; color: var(--text-muted); margin-left: 17px;">{{ $item->cod_area ?? 'Titik Temu Mahasiswa' }}</p>
                                </div>
                            </td>
                            <td>
                                <span class="badge-terjual" style="display: inline-flex; align-items: center; gap: 4px; background: #f0fdf4; color: #16a34a; border: 1px solid #bbf7d0; padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 700;">
                                    <svg viewBox="0 0 24 24" style="width: 12px; height: 12px; stroke: currentColor; fill: none; stroke-width: 2.5;"><polyline points="20 6 9 17 4 12"/></svg>
                                    Terjual
                                </span>
                            </td>
                            <td>
                                <div class="audit-cell">
                                    <span class="badge-audit-selesai" style="display: inline-flex; align-items: center; gap: 6px; font-size: 11px; font-weight: 700; color: #059669;">
                                        <span class="audit-dot" style="width: 6px; height: 6px; border-radius: 50%; background: #10b981;"></span> Selesai
                                    </span>
                                    <span class="audit-note" style="display: block; font-size: 10px; color: var(--text-muted); margin-top: 2px;">Konfirmasi Penjual</span>
                                </div>
                            </td>
                            <td>
                                <div class="actions-row" style="display: flex; align-items: center; justify-content: flex-end; gap: 6px;">
                                    <!-- Tombol Detail Transaksi -->
                                    <button type="button" 
                                            class="btn-action-icon" 
                                            title="Lihat Detail Transaksi"
                                            onclick="openTrxModal({{ json_encode([
                                                'trx_code'    => $trxCode,
                                                'title'       => $item->title,
                                                'price'       => number_format($item->price, 0, ',', '.'),
                                                'category'    => $item->category->name ?? 'Katalog Kampus',
                                                'condition'   => $item->condition,
                                                'description' => $item->description,
                                                'location'    => $item->location ?? 'Area Kampus',
                                                'cod_area'    => $item->cod_area ?? 'Kampus Utama',
                                                'seller_name'   => $item->seller->name ?? 'Mahasiswa',
                                                'seller_email'  => $item->seller->email ?? '-',
                                                'seller_wa'     => $item->seller->whatsapp_number ?? '-',
                                                'seller_avatar' => $item->seller?->profile_photo_url,
                                                'date'          => $item->updated_at ? $item->updated_at->translatedFormat('d F Y, H:i') : '-',
                                                'image'         => $primaryImg
                                            ]) }})">
                                        <svg viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                    </button>

                                    <!-- Tombol Aktifkan Kembali jika salah tandai terjual -->
                                    <form action="{{ route('admin.sold.reactivate', $item) }}" method="POST" onsubmit="return confirm('Kembalikan status barang ini menjadi Aktif di etalase?');">
                                        @csrf
                                        <button type="submit" class="btn-action-icon" title="Kembalikan ke Katalog Aktif" style="color: #64748b;">
                                            <svg viewBox="0 0 24 24"><polyline points="23 4 23 10 17 10"/><polyline points="1 20 1 14 7 14"/><path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"/></svg>
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
                    Menampilkan <strong>{{ $soldProducts->firstItem() ?? 0 }}</strong> - <strong>{{ $soldProducts->lastItem() ?? 0 }}</strong> dari <strong>{{ $soldProducts->total() }}</strong> barang terjual
                </div>
                <div class="pagination-links">
                    {{ $soldProducts->links('pagination::simple-default') }}
                </div>
            </div>
        @endif
    </section>

    <!-- =============================================
         POPUP MODAL: DETAIL BARANG TERJUAL (detail.html)
         ============================================= -->
    <div class="modal-overlay" id="modal-detail-trx" onclick="if(event.target === this) closeTrxModal();">
        <div class="detail-trx-dialog">

            <!-- Header Modal -->
            <div class="detail-header" style="padding: 20px 24px; border-bottom: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between;">
                <div class="detail-header-left">
                    <div class="detail-title-row" style="display: flex; align-items: center; gap: 10px;">
                        <h2 class="detail-title" style="font-size: 16px; font-weight: 800; color: var(--text);">Detail Transaksi Selesai</h2>
                        <span class="badge-selesai-terverifikasi" style="display: inline-flex; align-items: center; gap: 4px; background: #e6f9f1; color: #2db87a; font-size: 11px; font-weight: 700; padding: 4px 10px; border-radius: 20px;">
                            <svg viewBox="0 0 24 24" style="width: 12px; height: 12px; stroke: currentColor; fill: none; stroke-width: 2.5;"><polyline points="20 6 9 17 4 12"/></svg>
                            Selesai &amp; Terverifikasi
                        </span>
                    </div>
                    <p class="detail-subtitle" style="font-size: 12px; color: var(--text-muted); margin-top: 3px;">
                        <span id="trx-modal-code" style="font-weight: 700; color: #2563eb;">TRX-000</span> &bull; Diselesaikan: <span id="trx-modal-date">-</span>
                    </p>
                </div>
                <button type="button" class="btn-close-modal" aria-label="Tutup jendela modal" onclick="closeTrxModal()">&times;</button>
            </div>

            <!-- Body Modal -->
            <div class="detail-body" style="padding: 24px;">

                <!-- Card Rangkuman Produk -->
                <div class="product-summary-card" style="display: flex; gap: 18px; background: #f8fafc; border: 1px solid var(--border); border-radius: var(--radius); padding: 18px; margin-bottom: 20px;">
                    <div class="product-cover-wrap" style="position: relative; width: 120px; height: 120px; flex-shrink: 0;">
                        <span class="badge-terjual-overlay" style="position: absolute; top: 6px; left: 6px; background: #16a34a; color: #fff; font-size: 9px; font-weight: 800; padding: 2px 6px; border-radius: 4px; z-index: 2;">TERJUAL</span>
                        <img id="trx-modal-photo" src="" alt="Cover" class="product-cover-img" style="width: 100%; height: 100%; object-fit: cover; border-radius: 8px; border: 1px solid #e2e8f0; background: #fff;">
                    </div>

                    <div class="product-details-wrap" style="flex: 1; min-width: 0;">
                        <span class="product-cat-path" id="trx-modal-category" style="font-size: 11px; font-weight: 600; color: #2563eb; text-transform: uppercase;">Kategori &bull; Kampus</span>
                        <h3 class="product-name-heading" id="trx-modal-title" style="font-size: 15px; font-weight: 700; color: var(--text); margin: 3px 0 6px 0;">-</h3>
                        <p class="product-desc-text" id="trx-modal-desc" style="font-size: 12px; color: var(--text-muted); line-height: 1.5; margin-bottom: 12px;">-</p>

                        <!-- Grid 2x2 Spesifikasi -->
                        <div class="trx-specs-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; border-top: 1px solid #e2e8f0; padding-top: 10px;">
                            <div class="spec-item">
                                <span class="spec-label" style="display: block; font-size: 10px; color: var(--text-muted); text-transform: uppercase;">Harga Terjual:</span>
                                <span class="spec-value price-accent" id="trx-modal-price" style="font-size: 14px; font-weight: 800; color: #059669;">Rp 0</span>
                            </div>
                            <div class="spec-item">
                                <span class="spec-label" style="display: block; font-size: 10px; color: var(--text-muted); text-transform: uppercase;">Metode Transaksi:</span>
                                <span class="spec-value" style="font-size: 12px; font-weight: 600;">COD Langsung di Kampus</span>
                            </div>
                            <div class="spec-item">
                                <span class="spec-label" style="display: block; font-size: 10px; color: var(--text-muted); text-transform: uppercase;">Kondisi Barang:</span>
                                <span class="spec-value" id="trx-modal-condition" style="font-size: 12px; font-weight: 600;">-</span>
                            </div>
                            <div class="spec-item">
                                <span class="spec-label" style="display: block; font-size: 10px; color: var(--text-muted); text-transform: uppercase;">Lokasi Serah Terima:</span>
                                <span class="spec-value" id="trx-modal-location" style="font-size: 12px; font-weight: 600;">-</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Informasi Penjual -->
                <div style="background: #ffffff; border: 1px solid var(--border); border-radius: var(--radius); padding: 16px;">
                    <h4 style="font-size: 13px; font-weight: 700; color: var(--text); margin-bottom: 8px;">Penjual Terdaftar</h4>
                    <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 10px;">
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <img id="trx-modal-seller-img" src="" alt="Avatar" style="display: none; width: 36px; height: 36px; border-radius: 50%; object-fit: cover; border: 1px solid var(--border);">
                            <div id="trx-modal-seller-box" style="width: 36px; height: 36px; border-radius: 50%; background: #2563eb; color: #fff; font-size: 14px; display: flex; align-items: center; justify-content: center; font-weight: 700;">U</div>
                            <div>
                                <p id="trx-modal-seller-name" style="font-weight: 700; font-size: 13px;">-</p>
                                <p id="trx-modal-seller-email" style="font-size: 12px; color: var(--text-muted);">-</p>
                            </div>
                        </div>
                        <a id="trx-modal-wa-btn" href="#" target="_blank" style="display: inline-flex; align-items: center; gap: 6px; font-size: 12px; font-weight: 600; color: #16a34a; background: #f0fdf4; border: 1px solid #bbf7d0; padding: 6px 12px; border-radius: 6px; text-decoration: none;">
                            💬 WhatsApp: <span id="trx-modal-seller-wa">-</span>
                        </a>
                    </div>
                </div>

            </div>

            <!-- Footer Modal -->
            <div class="modal-footer" style="padding: 16px 24px; border-top: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between; background: #fafafa;">
                <button type="button" onclick="window.print()" style="background: none; border: 1px solid var(--border); padding: 8px 14px; border-radius: 6px; font-size: 12px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 6px; color: var(--text);">
                    🖨️ Cetak Bukti Audit
                </button>
                <button type="button" class="btn-modal-action btn-minta-foto" onclick="closeTrxModal()" style="border: 1px solid var(--border); background: #fff; padding: 8px 18px; border-radius: 6px; cursor: pointer; font-size: 13px;">
                    Tutup
                </button>
            </div>

        </div>
    </div>
@endsection

@push('scripts')
<script>
    function openTrxModal(data) {
        document.getElementById('trx-modal-code').textContent = data.trx_code;
        document.getElementById('trx-modal-date').textContent = data.date;
        document.getElementById('trx-modal-title').textContent = data.title;
        document.getElementById('trx-modal-category').textContent = data.category;
        document.getElementById('trx-modal-desc').textContent = data.description || 'Tidak ada deskripsi.';
        document.getElementById('trx-modal-price').textContent = 'Rp ' + data.price;
        document.getElementById('trx-modal-condition').textContent = data.condition;
        document.getElementById('trx-modal-location').textContent = data.location + ' (' + data.cod_area + ')';
        document.getElementById('trx-modal-photo').src = data.image || '{{ asset("admin-assets/assets/Logo.png") }}';

        document.getElementById('trx-modal-seller-name').textContent = data.seller_name;
        document.getElementById('trx-modal-seller-email').textContent = data.seller_email;
        document.getElementById('trx-modal-seller-wa').textContent = data.seller_wa;
        document.getElementById('trx-modal-wa-btn').href = 'https://wa.me/' + (data.seller_wa ? data.seller_wa.replace(/[^0-9]/g, '') : '');

        const trxSellerImg = document.getElementById('trx-modal-seller-img');
        const trxSellerBox = document.getElementById('trx-modal-seller-box');
        if (data.seller_avatar) {
            trxSellerImg.src = data.seller_avatar;
            trxSellerImg.style.display = 'block';
            trxSellerBox.style.display = 'none';
        } else {
            trxSellerImg.style.display = 'none';
            trxSellerBox.style.display = 'flex';
            trxSellerBox.textContent = (data.seller_name || 'U').substring(0, 1).toUpperCase();
        }

        document.getElementById('modal-detail-trx').classList.add('is-open');
    }

    function closeTrxModal() {
        document.getElementById('modal-detail-trx').classList.remove('is-open');
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeTrxModal();
        }
    });
</script>
@endpush
