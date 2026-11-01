<?php

use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminProductController;
use App\Http\Controllers\Admin\AdminSoldController;
use App\Http\Controllers\Admin\AdminUserController;
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

// ── Halaman Pending & Suspended ─────────────────────────────────────────────
Route::get('/pending', [HomeController::class, 'pendingNotice'])
    ->middleware('auth')
    ->name('pending.notice');

Route::get('/suspended', [HomeController::class, 'suspendedNotice'])
    ->middleware('auth')
    ->name('suspended.notice');

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

// ── Admin Panel Kustom (Template Prototype) ───────────────────────────────────
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('login.submit');
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');

    Route::middleware(['admin'])->group(function () {
        Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::post('/dashboard/products/{product}/approve', [AdminDashboardController::class, 'approve'])->name('dashboard.products.approve');
        Route::post('/dashboard/products/{product}/reject', [AdminDashboardController::class, 'reject'])->name('dashboard.products.reject');

        // Moderasi Barang
        Route::get('/barang', [AdminProductController::class, 'index'])->name('products');
        Route::post('/barang/{product}/approve', [AdminProductController::class, 'approve'])->name('products.approve');
        Route::post('/barang/{product}/reject', [AdminProductController::class, 'reject'])->name('products.reject');
        Route::delete('/barang/{product}', [AdminProductController::class, 'destroy'])->name('products.destroy');

        // Moderasi User
        Route::get('/user', [AdminUserController::class, 'index'])->name('users');
        Route::post('/user/{user}/approve', [AdminUserController::class, 'approve'])->name('users.approve');
        Route::post('/user/{user}/suspend', [AdminUserController::class, 'suspend'])->name('users.suspend');
        Route::delete('/user/{user}', [AdminUserController::class, 'destroy'])->name('users.destroy');

        // Barang Terjual
        Route::get('/barang-terjual', [AdminSoldController::class, 'index'])->name('sold');
        Route::post('/barang-terjual/{product}/reactivate', [AdminSoldController::class, 'reactivate'])->name('sold.reactivate');
    });
});

require __DIR__ . '/auth.php';
