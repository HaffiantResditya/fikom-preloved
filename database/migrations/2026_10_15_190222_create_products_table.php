<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('seller_id')->constrained('users')->cascadeOnDelete();
            $table->unsignedInteger('category_id');
            $table->foreign('category_id')->references('id')->on('categories')->cascadeOnDelete();
            $table->string('title', 150);
            $table->string('slug', 150)->unique();
            $table->unsignedInteger('price');
            $table->string('condition', 50)->default('Baik');
            $table->text('description');
            $table->string('location', 150);
            $table->string('cod_area', 150);
            $table->enum('status', ['pending', 'approved', 'rejected', 'sold'])->default('pending');
            $table->text('rejection_reason')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
