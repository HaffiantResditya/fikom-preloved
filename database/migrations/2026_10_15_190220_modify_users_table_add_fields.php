<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('whatsapp_number', 20)->nullable()->after('password');
            $table->enum('role', ['admin', 'student'])->default('student')->after('whatsapp_number');
            $table->enum('account_status', ['pending', 'approved', 'rejected'])->default('pending')->after('role');
            $table->string('profile_photo', 255)->nullable()->after('account_status');
            $table->dropColumn('email_verified_at'); // Ganti dengan account_status flow
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->timestamp('email_verified_at')->nullable();
            $table->dropColumn(['whatsapp_number', 'role', 'account_status', 'profile_photo']);
        });
    }
};
