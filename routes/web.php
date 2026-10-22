<?php

use App\Http\Controllers\CatalogController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// ── Halaman Publik ──────────────────────────────────────────────────────────
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/catalog', [CatalogController::class, 'index'])->name('catalog');
Route::get('/kategori', [HomeController::class, 'categories'])->name('categories');
Route::get('/products/{slug}', [ProductController::class, 'show'])->name('products.show');

// ── Halaman Pending (setelah register, sebelum approved) ────────────────────
Route::get('/pending', [HomeController::class, 'pendingNotice'])
    ->middleware('auth')
    ->name('pending.notice');

// ── Halaman yang Butuh Login + Approved ─────────────────────────────────────
Route::middleware(['auth', 'approved'])->group(function () {
    // Dashboard
    Route::get('/dashboard', function () {
        if (auth()->user()->isAdmin()) {
            return redirect('/admin');
        }
        return redirect()->route('home');
    })->name('dashboard');

    // Profile
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Iklan Barang
    Route::get('/jual', [ProductController::class, 'create'])->name('products.create');
    Route::post('/jual', [ProductController::class, 'store'])->name('products.store');
    Route::get('/iklan-saya', [ProductController::class, 'myListings'])->name('products.my-listings');
    Route::get('/jual/sukses', [ProductController::class, 'success'])->name('products.success');
    Route::patch('/jual/{product}/terjual', [ProductController::class, 'markAsSold'])->name('products.mark-sold');
    Route::delete('/jual/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
});

require __DIR__ . '/auth.php';
