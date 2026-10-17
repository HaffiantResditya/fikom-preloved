<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Seed kategori
        $this->call(CategorySeeder::class);

        // Buat akun admin default
        User::firstOrCreate(
            ['email' => 'admin@mhs.udb.ac.id'],
            [
                'name'            => 'Admin',
                'password'        => Hash::make('admin12345'),
                'role'            => 'admin',
                'account_status'  => 'approved',
                'whatsapp_number' => '081234567890',
            ]
        );

        // Seed sample users & products
        $this->call(SampleDataSeeder::class);
    }
}
