<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('serenatacoffee');
})->name('home');

Route::get('/reservasi', function () {
    return view('reservasi');
})->name('reservasi.form');


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', fn() => view('admin.dashboard'))->name('dashboard');

    Route::get('/menu', fn() => view('admin.menu.index'))->name('menu.index');
    Route::post('/menu', fn() => back()->with('success', 'Menu berhasil disimpan (dummy)'))->name('menu.store');

    Route::get('/galeri', fn() => view('admin.galeri.index'))->name('galeri.index');
    Route::post('/galeri', fn() => back()->with('success', 'Foto berhasil diupload (dummy)'))->name('galeri.store');

    Route::get('/testimoni', fn() => view('admin.testimoni.index'))->name('testimoni.index');
    Route::post('/testimoni', fn() => back()->with('success', 'Testimoni berhasil disimpan (dummy)'))->name('testimoni.store');

    Route::get('/kontak', fn() => view('admin.kontak.edit'))->name('kontak.edit');
    Route::put('/kontak', fn() => back()->with('success', 'Info kontak berhasil diperbarui (dummy)'))->name('kontak.update');

    Route::get('/reservasi', fn() => view('admin.reservasi.index'))->name('reservasi.index');
});

require __DIR__.'/auth.php';