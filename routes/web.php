<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductImageController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn() => redirect()->route('catalog.index'));
Route::get('/dashboard', fn() => redirect()->route('catalog.index'))->name('dashboard');

// Public catalog
Route::prefix('catalog')->group(function () {
    Route::get('/', [CatalogController::class, 'index'])->name('catalog.index');
    Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::resource('orders', OrderController::class);
    Route::resource('carts', CartController::class);
});

// Admin section
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('index');
    Route::resource('products', ProductController::class)->except(['show']);
    Route::resource('categories', CategoryController::class);
    Route::delete('products/{product}/images/{image}', [ProductImageController::class, 'destroy'])
        ->name('product-images.destroy');
    Route::patch('products/{product}/images/{image}/primary', [ProductImageController::class, 'setPrimary'])
        ->name('product-images.set-primary');
});


require __DIR__.'/auth.php';
