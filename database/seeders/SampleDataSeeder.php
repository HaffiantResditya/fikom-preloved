<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;

class SampleDataSeeder extends Seeder
{
    public function run(): void
    {
        // Pastikan folder storage public products ada
        $storagePath = storage_path('app/public/products');
        if (!File::exists($storagePath)) {
            File::makeDirectory($storagePath, 0755, true);
        }

        // 1. Akun Mahasiswa Disetujui (Approved Student)
        $studentApproved = User::firstOrCreate(
            ['email' => 'budi.santoso@mhs.udb.ac.id'],
            [
                'name'            => 'Budi Santoso',
                'password'        => Hash::make('password123'),
                'whatsapp_number' => '081298765432',
                'role'            => 'student',
                'account_status'  => 'approved',
            ]
        );

        // 2. Akun Mahasiswa Menunggu Approval (Pending Student)
        User::firstOrCreate(
            ['email' => 'siti.nurhaliza@mhs.udb.ac.id'],
            [
                'name'            => 'Siti Nurhaliza',
                'password'        => Hash::make('password123'),
                'whatsapp_number' => '081377889900',
                'role'            => 'student',
                'account_status'  => 'pending',
            ]
        );

        // Kategori Map
        $catElektronik = Category::where('slug', 'elektronik')->first();
        $catBuku       = Category::where('slug', 'buku-modul')->first();
        $catPerabot    = Category::where('slug', 'perabot-kos')->first();
        $catPraktikum  = Category::where('slug', 'alat-praktikum')->first();

        // Helper untuk membuat dummy image WebP via GD
        $createImage = function (string $text, string $bgColorHex, string $filename) use ($storagePath) {
            $filePath = $storagePath . '/' . $filename;
            if (File::exists($filePath)) {
                return 'products/' . $filename;
            }

            $img = imagecreatetruecolor(600, 600);
            
            // Convert Hex to RGB
            list($r, $g, $b) = sscanf($bgColorHex, "#%02x%02x%02x");
            $bgColor = imagecolorallocate($img, $r, $g, $b);
            $textColor = imagecolorallocate($img, 255, 255, 255);
            $subTextColor = imagecolorallocate($img, 240, 240, 240);

            imagefilledrectangle($img, 0, 0, 600, 600, $bgColor);

            // Add placeholder text (simple built-in font)
            imagestring($img, 5, 40, 260, "FIKOM PRELOVED", $textColor);
            imagestring($img, 5, 40, 290, $text, $textColor);
            imagestring($img, 3, 40, 330, "Kondisi: Bekas Layak Pakai", $subTextColor);

            imagepng($img, $filePath);
            imagedestroy($img);

            return 'products/' . $filename;
        };

        $sampleProducts = [
            [
                'title'       => 'Mouse Wireless Logitech M331 Silent Plus',
                'category_id' => $catElektronik?->id ?? 1,
                'price'       => 110000,
                'condition'   => 'Bekas Seperti Baru',
                'description' => "Mouse jarang dipakai karena sudah ganti trackpad. Klik silent tidak berisik di perpustakaan/lab kampus.\nKelengkapan: Mouse + USB Receiver bawaan original. Baterai baru diganti.",
                'location'    => 'Kos Palur Asri No. 12',
                'cod_area'    => 'Gedung FIKOM / Lab Komputer',
                'status'      => 'approved',
                'image_text'  => 'Logitech M331 Silent',
                'image_color' => '#4338ca', // Indigo
                'file_name'   => 'sample_mouse_m331.png',
            ],
            [
                'title'       => 'Buku Algoritma & Struktur Data edisi 3',
                'category_id' => $catBuku?->id ?? 2,
                'price'       => 45000,
                'condition'   => 'Bekas Layak Pakai',
                'description' => "Buku referensi matkul Algoritma semester 1 & 2. Masih sangat rapi, tidak ada coretan pulpen, hanya sedikit stabilo kuning di bab Sorting.",
                'location'    => 'Wisma Mahasiswa Kartasura',
                'cod_area'    => 'Perpustakaan Pusat Kampus',
                'status'      => 'approved',
                'image_text'  => 'Buku Struktur Data',
                'image_color' => '#0d9488', // Teal
                'file_name'   => 'sample_buku_struktur_data.png',
            ],
            [
                'title'       => 'Arduino Uno R3 + Sensor Kit Praktikum IoT',
                'category_id' => $catPraktikum?->id ?? 5,
                'price'       => 140000,
                'condition'   => 'Bekas Seperti Baru',
                'description' => "Paket komplit untuk praktikum Embedded System / IoT FIKOM. Sudah termasuk kabel jumper, breadboard, sensor suhu DHT11, LCD 16x2 I2C, servo sg90.",
                'location'    => 'Gondangrejo dekat Kampus 2',
                'cod_area'    => 'Kantin FIKOM Kampus Utama',
                'status'      => 'approved',
                'image_text'  => 'Arduino Uno R3 Kit',
                'image_color' => '#0284c7', // Sky Blue
                'file_name'   => 'sample_arduino_uno.png',
            ],
            [
                'title'       => 'Meja Belajar Laptop Lipat Kayu Minimalis',
                'category_id' => $catPerabot?->id ?? 3,
                'price'       => 50000,
                'condition'   => 'Bekas Layak Pakai',
                'description' => "Meja lipat portable cocok untuk nugas di kos atau di kasur. Kaki kokoh, ada tempat holder tablet dan gelas minum. Dijual karena mau pindah kos.",
                'location'    => 'Gang Melati Kos Putri',
                'cod_area'    => 'Depan Gerbang Barat Kampus',
                'status'      => 'approved',
                'image_text'  => 'Meja Lipat Laptop',
                'image_color' => '#b45309', // Amber
                'file_name'   => 'sample_meja_lipat.png',
            ],
            [
                'title'       => 'Kalkulator Scientific Casio fx-991EX ClassWiz',
                'category_id' => $catElektronik?->id ?? 1,
                'price'       => 185000,
                'condition'   => 'Bekas Seperti Baru',
                'description' => "Kalkulator wajib untuk ujian kalkulus dan matematika diskrit. Layar tajam, panel surya berfungsi 100%, casing penutup masih mulus.",
                'location'    => 'Pabelan dekat Kampus',
                'cod_area'    => 'Gazebo FIKOM',
                'status'      => 'approved',
                'image_text'  => 'Casio fx-991EX',
                'image_color' => '#334155', // Slate
                'file_name'   => 'sample_casio_fx991ex.png',
            ],
            [
                'title'       => 'Keyboard Mechanical Redragon K552 Red Switch',
                'category_id' => $catElektronik?->id ?? 1,
                'price'       => 230000,
                'condition'   => 'Bekas Seperti Baru',
                'description' => "Red switch empuk dan tidak berisik untuk ngetik coding berjam-jam. RGB backlight hidup semua, box dan keycap puller masih ada.",
                'location'    => 'Jalan Garuda No. 5',
                'cod_area'    => 'Gedung Mahasiswa Kampus',
                'status'      => 'pending', // Menunggu approval Admin
                'image_text'  => 'Redragon K552 RGB',
                'image_color' => '#dc2626', // Red
                'file_name'   => 'sample_keyboard_k552.png',
            ],
        ];

        foreach ($sampleProducts as $item) {
            $imagePath = $createImage($item['image_text'], $item['image_color'], $item['file_name']);

            $product = Product::firstOrCreate(
                ['title' => $item['title']],
                [
                    'seller_id'   => $studentApproved->id,
                    'category_id' => $item['category_id'],
                    'price'       => $item['price'],
                    'condition'   => $item['condition'],
                    'description' => $item['description'],
                    'location'    => $item['location'],
                    'cod_area'    => $item['cod_area'],
                    'status'      => $item['status'],
                ]
            );

            ProductImage::firstOrCreate(
                ['product_id' => $product->id, 'image_path' => $imagePath],
                ['is_primary' => true]
            );
        }
    }
}
