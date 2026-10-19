# Product Requirements Document (PRD) & Technical Guide
**Nama Proyek:** FIKOM Preloved  
**Tim Pengembang:** Kelompok 22 (Web UI/UX Developer)  
**Tujuan Dokumen:** Panduan utama (*Single Source of Truth*) untuk pengembangan, arsitektur, dan kolaborasi tim menggunakan Laravel 12.

---

## 1. Ringkasan Proyek
**FIKOM Preloved** adalah aplikasi *marketplace* lokal berbasis *Customer-to-Customer* (C2C) yang dirancang eksklusif untuk mahasiswa Fakultas Ilmu Komputer. 
Platform ini memecahkan masalah penumpukan barang bekas mahasiswa tingkat akhir (alat praktikum, perabot kos, buku modul) dan kebutuhan mahasiswa baru akan barang murah. 
Transaksi dilakukan 100% secara tatap muka atau *Cash on Delivery* (COD) di sekitar lingkungan kampus, sehingga memangkas biaya ongkos kirim dan menghilangkan risiko penipuan dari luar kampus.

---

## 2. Spesifikasi Teknologi (Tech Stack)
*   **Backend Framework:** Laravel 12 (PHP)
*   **Database:** MySQL / MariaDB
*   **Frontend / UI:** Laravel Blade, Tailwind CSS, Alpine.js
*   **Admin Dashboard:** Custom Blade & Controller (Multi-Auth Guard `admin`)
*   **Dependencies Tambahan:**
    *   `laravel/breeze` : Scaffold autentikasi dasar.
    *   `intervention/image` : Pemrosesan dan kompresi gambar (mencegah *hosting* penuh).
    *   `blade-ui-kit/blade-heroicons` : Pustaka ikon SVG yang ringan.
    *   `spatie/laravel-sluggable` : Pembuat URL *slug* (SEO friendly).

---

## 3. Fitur Utama
1.  **Autentikasi SSO Kampus:** Registrasi dilindungi secara ketat dan hanya menerima domain email resmi kampus (cth: `@udb.mhs.ac.id`).
2.  **Sistem Moderasi Berjenjang:** Pengguna baru dan iklan barang baru tidak akan langsung tayang; wajib melalui *approval* (persetujuan) dari Admin BEM via *dashboard*.
3.  **Katalog & Filter Pintar:** Halaman beranda dan pencarian dengan filter kategori spesifik (Perabot, Elektronik, Buku/Modul, dll).
4.  **Posting Iklan Multi-Foto:** Formulir jual barang yang mendukung unggahan hingga 5 foto dengan fitur kompresi otomatis (WebP).
5.  **Integrasi Pesan WhatsApp (Tanpa Keranjang):** Pembeli langsung diarahkan ke WhatsApp penjual melalui *link* yang digenerate otomatis beserta format pesannya.

---

## 4. Struktur Database (Schema)

### 4.1. `users` (Tabel Pengguna)
*   `id` (BIGINT, PK)
*   `name` (VARCHAR 100)
*   `email` (VARCHAR 100, Unique)
*   `password` (VARCHAR 255)
*   `whatsapp_number` (VARCHAR 20)
*   `role` (ENUM: 'admin', 'student') -> *Default: 'student'*
*   `account_status` (ENUM: 'pending', 'approved', 'rejected') -> *Default: 'pending'*
*   `profile_photo` (VARCHAR 255, Nullable)

### 4.2. `categories` (Tabel Kategori)
*   `id` (INT, PK)
*   `name` (VARCHAR 50)
*   `slug` (VARCHAR 50, Unique)

### 4.3. `products` (Tabel Iklan Barang)
*   `id` (BIGINT, PK)
*   `seller_id` (BIGINT, FK ke `users.id` - Cascade Delete)
*   `category_id` (INT, FK ke `categories.id` - Cascade Delete)
*   `title` (VARCHAR 150)
*   `slug` (VARCHAR 150, Unique)
*   `price` (INT)
*   `condition` (ENUM: 'Baru', 'Bekas Seperti Baru', 'Bekas Layak Pakai')
*   `description` (TEXT)
*   `location` (VARCHAR 150)
*   `cod_area` (VARCHAR 150)
*   `status` (ENUM: 'pending', 'approved', 'rejected', 'sold') -> *Default: 'pending'*

### 4.4. `product_images` (Tabel Relasi Foto)
*   `id` (BIGINT, PK)
*   `product_id` (BIGINT, FK ke `products.id` - Cascade Delete)
*   `image_path` (VARCHAR 255)
*   `is_primary` (BOOLEAN) -> *Penentu foto sampul depan*

---

## 5. Arsitektur File & Folder
Struktur modifikasi MVC di Laravel 12 untuk proyek ini:

```text
fikom-preloved/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Auth/RegisteredUserController.php # (Logika Validasi Email Kampus)
│   │   │   ├── HomeController.php    
│   │   │   ├── CatalogController.php 
│   │   │   └── ProductController.php # (Logika Upload Iklan & Redirect WA)
│   │   └── Middleware/
│   │       └── CheckApprovedUser.php # (Blokir akses user 'pending')
│   ├── Models/                       # (User, Category, Product, ProductImage)
│   └── Http/Controllers/Admin/       # (AdminAuthController, AdminDashboardController, dll.)
├── public/
│   ├── images/                       # (Tempat simpan Aset Statis: logo.png, ilustrasi)
│   └── storage/                      # (Symlink ke storage/app/public)
├── resources/
│   └── views/
│       ├── components/               # (Navbar, Footer, ProductCard)
│       ├── errors/                   # (403.blade.php & 404.blade.php Custom)
│       └── pages/
│           ├── home.blade.php
│           ├── catalog.blade.php
│           └── products/             # (create.blade.php, show.blade.php, success.blade.php)
└── routes/
    └── web.php                       # (Definisi Endpoint)