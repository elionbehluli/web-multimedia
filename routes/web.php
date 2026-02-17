<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', [App\Http\Controllers\ProductController::class, 'index']);
Route::get('/products/{product}', [App\Http\Controllers\ProductController::class, 'show'])->name('products.show');
Route::get('/admin', [App\Http\Controllers\AdminController::class, 'index'])->name('admin.products.index');
Route::get('/admin/products/{product}', [App\Http\Controllers\AdminController::class, 'show'])->name('admin.products.show');
Route::post('/admin/products/{product}/images', [App\Http\Controllers\AdminController::class, 'storeImage'])->name('admin.products.images.store');

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
