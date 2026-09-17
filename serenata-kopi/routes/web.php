<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\GalleryController;
use App\Http\Controllers\Admin\TestimonialController;
use App\Http\Controllers\Admin\ContactController;
use App\Http\Controllers\Admin\ReservationController as AdminReservationController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| HALAMAN PUBLIC
|--------------------------------------------------------------------------
*/
use App\Http\Controllers\HomeController;
// ... (taruh use di atas bareng use yang lain)

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/reservasi', [ReservationController::class, 'create'])->name('reservasi.form');
Route::post('/reservasi', [ReservationController::class, 'store'])->name('reservasi.store');

/*
|--------------------------------------------------------------------------
| HALAMAN USER (harus login)
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| HALAMAN ADMIN (harus login + role admin)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/menu', [MenuController::class, 'index'])->name('menu.index');
    Route::post('/menu', [MenuController::class, 'store'])->name('menu.store');
    Route::get('/menu/{menu}/edit', [MenuController::class, 'edit'])->name('menu.edit');
    Route::put('/menu/{menu}', [MenuController::class, 'update'])->name('menu.update');
    Route::delete('/menu/{menu}', [MenuController::class, 'destroy'])->name('menu.destroy');

    Route::get('/galeri', [GalleryController::class, 'index'])->name('galeri.index');
    Route::post('/galeri', [GalleryController::class, 'store'])->name('galeri.store');
    Route::delete('/galeri/{galeri}', [GalleryController::class, 'destroy'])->name('galeri.destroy');

    Route::get('/testimoni', [TestimonialController::class, 'index'])->name('testimoni.index');
    Route::post('/testimoni', [TestimonialController::class, 'store'])->name('testimoni.store');
    Route::get('/testimoni/{testimoni}/edit', [TestimonialController::class, 'edit'])->name('testimoni.edit');
    Route::put('/testimoni/{testimoni}', [TestimonialController::class, 'update'])->name('testimoni.update');
    Route::delete('/testimoni/{testimoni}', [TestimonialController::class, 'destroy'])->name('testimoni.destroy');

    Route::get('/kontak', [ContactController::class, 'edit'])->name('kontak.edit');
    Route::put('/kontak', [ContactController::class, 'update'])->name('kontak.update');

    Route::get('/reservasi', [AdminReservationController::class, 'index'])->name('reservasi.index');
    Route::put('/reservasi/{reservasi}/confirm', [AdminReservationController::class, 'confirm'])->name('reservasi.confirm');
});

require __DIR__.'/auth.php';