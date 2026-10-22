# 🎓 FIKOM Preloved

[![Laravel](https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![TailwindCSS](https://img.shields.io/badge/TailwindCSS-3.x-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)](https://tailwindcss.com)
[![Alpine.js](https://img.shields.io/badge/Alpine.js-3.x-8BC0D0?style=for-the-badge&logo=alpinedotjs&logoColor=white)](https://alpinejs.dev)
[![Blade](https://img.shields.io/badge/Admin_Panel-Custom_Blade-1E40AF?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![MySQL](https://img.shields.io/badge/MySQL-8.x-4479A1?style=for-the-badge&logo=mysql&logoColor=white)](https://www.mysql.com)

**FIKOM Preloved** adalah aplikasi *marketplace* lokal berbasis *Customer-to-Customer* (C2C) yang dirancang eksklusif untuk mahasiswa Fakultas Ilmu Komputer. Platform ini menghubungkan mahasiswa tingkat akhir yang ingin menjual barang preloved (alat praktikum, perabot kos, modul/buku kuliah, perangkat elektronik) dengan mahasiswa baru yang membutuhkan barang berkualitas dengan harga terjangkau.

Transaksi dilakukan **100% secara tatap muka / Cash on Delivery (COD)** di lingkungan kampus sehingga memangkas biaya ongkos kirim dan mengeliminasi risiko penipuan.

---

## ✨ Fitur Utama

1. **🔐 Autentikasi Khusus Domain Kampus (SSO Guard):**
   * Pendaftaran akun dibatasi secara ketat hanya untuk email resmi kampus `@mhs.udb.ac.id`.
2. **🛡️ Moderasi Berjenjang (Admin Review):**
   * Akun mahasiswa yang baru mendaftar berstatus `pending` dan wajib diverifikasi oleh Admin sebelum dapat memposting iklan.
   * Setiap iklan barang yang diunggah harus disetujui Admin sebelum tayang di katalog publik.
3. **🔍 Katalog Pintar & Multi-Filter:**
   * Filter cepat berdasarkan 7 kategori (Elektronik, Modul/Buku, Perabot Kos, Pakaian, Alat Praktikum, Olahraga, dll).
   * Filter berdasarkan kondisi barang (*Baru*, *Bekas Seperti Baru*, *Bekas Layak Pakai*) serta fitur pencarian judul.
4. **📸 Unggah Iklan Multi-Foto & Kompresi Otomatis:**
   * Formulir unggah interaktif hingga 5 foto dengan *drag-and-drop* dan pratinjau langsung via Alpine.js.
   * Gambar otomatis dikompresi dan di-resize menggunakan `Intervention Image` untuk menghemat ruang penyimpanan.
5. **💬 Integrasi WhatsApp Otomatis (Tanpa Keranjang):**
   * Tombol kontak langsung membuka aplikasi WhatsApp penjual dengan nomor terformat internasional (`62xxx`) beserta format draf pesan otomatis berisi nama barang, harga, dan ajakan COD.
6. **📋 Halaman "Iklan Saya":**
   * Memantau status iklan pribadi (*Menunggu Review*, *Tayang*, *Ditolak*, *Terjual*).
   * Opsi menandai barang sebagai **"Terjual"** (*Sold Out*) atau menghapus iklan sewaktu-waktu.
7. **⚙️ Panel Admin Kustom (Custom Blade & Multi-Auth Guard):**
   * Portal login admin independen di `/admin/login` (guard `admin`).
   * Manajemen & moderasi persetujuan pengguna (*Approve / Suspend user*).
   * Manajemen persetujuan iklan barang (*Approve / Reject product*) beserta alasan penolakan.
   * Laporan rekapitulasi barang terjual (*Sold Products & Omset Turnover*).

---

## 🛠️ Spesifikasi Teknologi (Tech Stack)

* **Backend:** PHP 8.2+, Laravel 12
* **Database:** MySQL / MariaDB
* **Admin Dashboard:** Custom Blade & Controller (Multi-Auth Guard `admin`)
* **Frontend UI:** Laravel Blade, Tailwind CSS, Alpine.js
* **Komponen & Paket Tambahan:**
  * `laravel/breeze` : Autentikasi dasar.
  * `intervention/image` : Pemrosesan dan kompresi gambar.
  * `blade-ui-kit/blade-heroicons` : Pustaka ikon SVG.
  * `spatie/laravel-sluggable` : Generator URL slug otomatis ramah SEO.

---

## 🚀 Panduan Instalasi & Menjalankan Proyek

### 1. Prasyarat Sistem
* PHP >= 8.2 (dengan ekstensi `pdo_mysql`, `mbstring`, `gd`, `fileinfo`)
* MySQL / MariaDB (via XAMPP atau Laragon)
* Composer
* Node.js & NPM

### 2. Kloning / Buka Direktori Proyek
```bash
cd fikom-preloved
```

### 3. Pasang Dependensi Backend & Frontend
```bash
composer install
npm install
```

### 4. Konfigurasi Lingkungan (.env)
Salin file `.env.example` ke `.env`:
```bash
cp .env.example .env
php artisan key:generate
```

Pastikan konfigurasi database di file `.env` sudah sesuai dengan server MySQL lokal Anda:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=fikom_preloved
DB_USERNAME=root
DB_PASSWORD=
```

### 5. Buat Database & Jalankan Migrasi + Seeder
Buat database `fikom_preloved` di MySQL/phpMyAdmin, lalu jalankan:
```bash
php artisan migrate:fresh --seed
```

### 6. Hubungkan Storage Simbolik
```bash
php artisan storage:link
```

### 7. Kompilasi Aset Frontend
```bash
# Untuk mode produksi:
npm run build

# Atau untuk mode development dengan hot-reload:
npm run dev
```

### 8. Jalankan Server Lokal
```bash
php artisan serve
```
Akses aplikasi melalui peramban web di: **`http://127.0.0.1:8000`**

---

## 🔑 Akun Bawaan untuk Pengujian (Seeded Data)

Database telah dilengkapi dengan data *dummy* realistis untuk mempermudah demonstrasi:

| Peran (Role) | Email | Password | Status Akun | Hak Akses |
|---|---|---|---|---|
| **Admin** | `admin@mhs.udb.ac.id` | `admin12345` | `approved` | Akses Dashboard Admin di `/admin` |
| **Mahasiswa (Aktif)** | `budi.santoso@mhs.udb.ac.id` | `password123` | `approved` | Pasang iklan di `/jual` & kelola di `/iklan-saya` |
| **Mahasiswa (Baru)** | `siti.nurhaliza@mhs.udb.ac.id` | `password123` | `pending` | Menunggu approval (halaman notice) |

> 📌 **Akses Panel Admin:** [http://127.0.0.1:8000/admin](http://127.0.0.1:8000/admin)

---

## 🧪 Menjalankan Pengujian Otomatis (Automated Tests)

Aplikasi dilengkapi dengan rangkaian pengujian fitur (*Feature Tests*) untuk memverifikasi autentikasi email kampus, proteksi middleware, format pesan WhatsApp, serta manajemen profil:

```bash
php artisan test
```

Hasil: **27 passed (65 assertions) — 100% Pass**

---

## 📂 Struktur Direktori Utama

```text
fikom-preloved/
├── app/
│   ├── Http/
│   │   ├── Controllers/          # HomeController, CatalogController, ProductController, Admin/*
│   │   └── Middleware/           # CheckApprovedUser, EnsureUserIsAdmin
│   └── Models/                   # User, Category, Product, ProductImage
├── database/
│   ├── migrations/               # Schema database
│   └── seeders/                  # CategorySeeder, DatabaseSeeder, SampleDataSeeder
├── resources/
│   └── views/
│       ├── admin/                # auth/login, dashboard, products, users, sold
│       ├── layouts/              # app, guest, admin
│       ├── components/           # Navbar, Footer, ProductCard, dll.
│       ├── errors/               # 403.blade.php & 404.blade.php
│       └── pages/                # home, catalog, pending, suspended, products/*
└── routes/
    ├── web.php                   # Rute publik, mahasiswa, & admin kustom
    └── auth.php                  # Rute autentikasi Breeze
```

---

## 👥 Tim Pengembang

* **Kelompok 22** — Web UI/UX & Fullstack Developer
* Fakultas Ilmu Komputer
