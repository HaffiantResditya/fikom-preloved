<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE products MODIFY COLUMN `condition` VARCHAR(50) NOT NULL DEFAULT 'Baik'");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE products MODIFY COLUMN `condition` ENUM('Baru', 'Bekas Seperti Baru', 'Bekas Layak Pakai') NOT NULL");
        }
    }
};
