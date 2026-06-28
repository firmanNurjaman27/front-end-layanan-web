<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DestinasiController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Middleware\AdminAuthenticate;

// ============================================
// PUBLIC ROUTES
// ============================================

// Halaman utama
Route::get('/', [HomeController::class, 'index'])->name('home');

// Destinasi wisata
Route::get('/destinasi', [DestinasiController::class, 'index'])->name('destinasi.index');
Route::get('/destinasi/{id}', [DestinasiController::class, 'show'])->name('destinasi.show');

// ============================================
// AUTH ROUTES
// ============================================

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ============================================
// ADMIN ROUTES (Protected)
// ============================================

Route::prefix('admin')->middleware(AdminAuthenticate::class)->group(function () {

    // Dashboard
    Route::get('/', [AdminController::class, 'dashboard'])->name('admin.dashboard');

    // Manajemen Reservasi
    Route::post('/reservasi', [AdminController::class, 'storeReservasi'])->name('admin.reservasi.store');
    Route::delete('/reservasi/{id}', [AdminController::class, 'destroyReservasi'])->name('admin.reservasi.destroy');

    // Manajemen Destinasi
    Route::get('/destinasi', [AdminController::class, 'destinasiIndex'])->name('admin.destinasi');
    Route::post('/destinasi', [AdminController::class, 'storeDestinasi'])->name('admin.destinasi.store');
    Route::delete('/destinasi/{id}', [AdminController::class, 'destroyDestinasi'])->name('admin.destinasi.destroy');
});
