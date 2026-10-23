# 🎓 FIKOM Preloved

[![Laravel](https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![TailwindCSS](https://img.shields.io/badge/TailwindCSS-3.x-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)](https://tailwindcss.com)
[![Alpine.js](https://img.shields.io/badge/Alpine.js-3.x-8BC0D0?style=for-the-badge&logo=alpinedotjs&logoColor=white)](https://alpinejs.dev)
[![Vercel](https://img.shields.io/badge/Deployment-Vercel_Serverless-000000?style=for-the-badge&logo=vercel&logoColor=white)](https://vercel.com)
[![Aiven MySQL](https://img.shields.io/badge/Database-Aiven_Cloud_MySQL-FF3E00?style=for-the-badge&logo=mysql&logoColor=white)](https://aiven.io)
[![Cloudinary](https://img.shields.io/badge/Media-Cloudinary_CDN-3448C5?style=for-the-badge&logo=cloudinary&logoColor=white)](https://cloudinary.com)
[![Admin Panel](https://img.shields.io/badge/Admin_Panel-Custom_Blade-4F46E5?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)

**FIKOM Preloved** adalah aplikasi *marketplace* lokal berbasis *Customer-to-Customer* (C2C) yang dirancang khusus untuk mahasiswa Fakultas Ilmu Komputer. Platform ini memfasilitasi mahasiswa tingkat akhir yang ingin menjual perlengkapan kuliah/kos (laptop, modul/buku, alat praktikum, perabot kos) kepada mahasiswa baru dengan harga bersahabat.

Seluruh transaksi dilakukan **100% secara tatap muka / Cash on Delivery (COD)** di lingkungan kampus demi keamanan transaksi tanpa risiko penipuan dan bebas ongkir.

🌐 **Demo Live Website:** [https://fikom-preloved.vercel.app](https://fikom-preloved.vercel.app)

---

## ✨ Fitur Utama

1. **🔐 Autentikasi Domain Resmi Kampus (SSO Guard):**
   * Pendaftaran akun mahasiswa dibatasi secara ketat hanya menerima email berdomain `@mhs.udb.ac.id`.
2. **🛡️ Moderasi Terpusat Berjenjang:**
   * Akun mahasiswa baru wajib ditinjau admin sebelum dapat memasang iklan.
   * Iklan produk berstatus `pending` dan harus disetujui admin sebelum tayang di etalase publik.
3. **🔍 Katalog Pintar & Multi-Filter:**
   * Dual-slider harga interaktif (Rp 0 s/d Rp 1.000.000) dengan kelipatan presisi Rp 25.000.
   * Standardisasi 3 kondisi barang: *Seperti Baru*, *Baik*, dan *Layak Pakai*.
   * Filter cepat berdasarkan 7 kategori barang preloved mahasiswa.
   * Penulisan waktu relatif dalam Bahasa Indonesia (*"1 hari yang lalu"*, *"6 hari yang lalu"*).
4. **📸 Integrasi Media Cloudinary:**
   * Foto produk dan profil disimpan langsung ke Cloudinary Media CDN, memastikan gambar selalu online di seluruh perangkat tanpa membebani disk hosting.
5. **💬 Transaksi WhatsApp Terstruktur (Tanpa Keranjang):**
   * Tombol *"Chat Penjual via WhatsApp"* langsung membuka aplikasi WhatsApp dengan nomor terformat internasional (`62xxx`) dan template pesan otomatis berisi nama barang, harga, serta ajakan COD.
6. **📱 Navigasi Responsif Penuh (Mobile-First):**
   * Tampilan navbar adaptif di layar smartphone kecil (360px–420px) dengan akses cepat tombol *Masuk*, *Daftar*, dan *Mulai Jual*.
   * Menu profil dropdown yang rapi, luas, dan profesional.
7. **⚙️ Panel Admin Kustom (Custom Blade & Multi-Auth Guard):**
   * Portal login admin independen di `/admin/login` (guard `admin`).
   * Statistik rekapitulasi barang aktif, pending, ditolak, dan barang terjual.
   * Moderasi produk (setujui atau tolak dengan alasan `rejection_reason`).
   * Moderasi mahasiswa (verifikasi KTM/identitas, setujui akun, atau suspend).
   * Riwayat barang terjual dengan fitur reaktivasi iklan.

---

## 🛠️ Arsitektur & Spesifikasi Teknologi

| Komponen | Teknologi | Keterangan |
| :--- | :--- | :--- |
| **Backend Framework** | Laravel 12 (PHP 8.2+) | Model-View-Controller murni |
| **Hosting & Deployment** | Vercel Serverless | Runtime `vercel-php@0.7.3` |
| **Database** | Aiven Cloud MySQL 8.x | Database online terenkripsi SSL |
| **Media Storage** | Cloudinary CDN | Penyimpanan gambar cloud realtime |
| **Frontend UI** | Blade + Tailwind CSS + Alpine.js | Tampilan modern, responsif, dan interaktif |
| **Admin Panel** | Custom Blade Panel | Menggantikan Filament untuk efisiensi memori RAM |

---

## 🚀 Panduan Menjalankan Proyek di Lokal

### 1. Prasyarat Sistem
* PHP >= 8.2 (ekstensi `pdo_mysql`, `mbstring`, `gd`, `fileinfo`)
* Composer
* Node.js & NPM
* Akun MySQL (Lokal atau Aiven Cloud)

### 2. Kloning Repositori
```bash
git clone https://github.com/HaffiantResditya/fikom-preloved.git
cd fikom-preloved
```

### 3. Pasang Dependensi
```bash
composer install
npm install
```

### 4. Konfigurasi Lingkungan (`.env`)
Salin file `.env.example` ke `.env`:
```bash
cp .env.example .env
php artisan key:generate
```

Sesuaikan variabel database dan Cloudinary di file `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=mysql-fikom-preloved-fikom-preloved.k.aivencloud.com
DB_PORT=20705
DB_DATABASE=defaultdb
DB_USERNAME=avnadmin
DB_PASSWORD=your_password

CLOUDINARY_CLOUD_NAME=your_cloud_name
CLOUDINARY_API_KEY=your_api_key
CLOUDINARY_API_SECRET=your_api_secret
```

### 5. Kompilasi Aset Frontend
```bash
npm run build
```

### 6. Jalankan Server Pengembangan
```bash
php artisan serve
```
Buka peramban di: **`http://127.0.0.1:8000`**

---

## 🌐 Panduan Deployment ke Vercel

Proyek ini telah dikonfigurasi penuh untuk serverless deployment di Vercel:

1. **Import Repositori:** Masuk ke dashboard [Vercel](https://vercel.com) dan import repo GitHub `fikom-preloved`.
2. **Framework Preset:** Pilih **`Other`**.
3. **Environment Variables:** Masukkan variabel penting:
   * `APP_NAME`, `APP_ENV=production`, `APP_KEY`, `APP_DEBUG=false`, `APP_URL=https://your-app.vercel.app`
   * `DB_CONNECTION=mysql`, `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`
   * `CLOUDINARY_CLOUD_NAME`, `CLOUDINARY_API_KEY`, `CLOUDINARY_API_SECRET`
4. **Deploy:** Klik tombol **Deploy**. Vercel akan otomatis membaca file `vercel.json` dan meluncurkan website.

---

## 🔑 Akun Uji Coba Bawaan (Demo Credentials)

| Peran (Role) | Email | Password | Hak Akses |
| :--- | :--- | :--- | :--- |
| **Admin** | `admin@mhs.udb.ac.id` | `admin12345` | Portal Admin di `/admin` |
| **Mahasiswa (Aktif)** | `budi.santoso@mhs.udb.ac.id` | `password123` | Buat & kelola iklan di `/jual` |
| **Mahasiswa (Baru)** | `siti.nurhaliza@mhs.udb.ac.id` | `password123` | Menunggu approval pendaftaran |

---

## 🧪 Pengujian Otomatis (Automated Tests)

Jalankan pengujian unit dan fitur untuk memastikan integritas logika sistem:
```bash
php artisan test
```
*Hasil:* **27 passed (65 assertions) — 100% Pass**

---

## 👥 Tim Pengembang
* **Fakultas Ilmu Komputer (FIKOM)**
* Proyek Akhir Magang & Portofolio Rekayasa Web
