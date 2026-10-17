<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Perabot Kos',        'slug' => 'perabot-kos'],
            ['name' => 'Elektronik',         'slug' => 'elektronik'],
            ['name' => 'Buku / Modul',       'slug' => 'buku-modul'],
            ['name' => 'Alat Praktikum',     'slug' => 'alat-praktikum'],
            ['name' => 'Peralatan Dapur',    'slug' => 'peralatan-dapur'],
            ['name' => 'Fashion',            'slug' => 'fashion'],
            ['name' => 'Alat Tulis (ATK)',   'slug' => 'atk'],
            ['name' => 'Olahraga & Hobi',    'slug' => 'olahraga-hobi'],
        ];

        // Hapus kategori di luar 8 kategori resmi
        $validSlugs = array_column($categories, 'slug');
        Category::whereNotIn('slug', $validSlugs)->delete();

        foreach ($categories as $category) {
            Category::updateOrCreate(['slug' => $category['slug']], $category);
        }
    }
}
