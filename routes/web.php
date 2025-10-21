<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KuisController;
use App\Http\Controllers\MateriController;
use App\Http\Controllers\PermainanController;

// Redirect root ke login
Route::get('/', function () {
    return redirect()->route('login');
});

// Auth Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login/admin', [LoginController::class, 'loginAdmin'])->name('login.admin');
    Route::post('/login/request-otp', [LoginController::class, 'requestOtp'])->name('login.request-otp');
    Route::post('/login/verify-otp', [LoginController::class, 'verifyOtp'])->name('login.verify-otp');
    Route::post('/login/complete-profile', [LoginController::class, 'completeProfile'])->name('login.complete-profile');
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Admin Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'admin'])->name('dashboard');

    // Whitelist Management
    Route::get('/whitelist', [AdminController::class, 'whitelistIndex'])->name('whitelist.index');
    Route::post('/whitelist', [AdminController::class, 'whitelistStore'])->name('whitelist.store');
    Route::delete('/whitelist/{id}', [AdminController::class, 'whitelistDestroy'])->name('whitelist.destroy');

    // Akun Management
    Route::get('/akun', [AdminController::class, 'akunIndex'])->name('akun.index');
    Route::delete('/akun/{id}', [AdminController::class, 'akunDestroy'])->name('akun.destroy');
});

// Guru Routes
Route::middleware(['auth', 'guru'])->prefix('guru')->name('guru.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'guru'])->name('dashboard');
});

// Wali Murid Routes
Route::middleware(['auth', 'wali_murid'])->prefix('wali-murid')->name('wali-murid.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'waliMurid'])->name('dashboard');
});

// Kuis Management Routes (Guru & Admin only)
Route::middleware(['auth', 'guru_or_admin'])->prefix('kuis')->name('kuis.')->group(function () {
    Route::get('/create', [KuisController::class, 'create'])->name('create');
    Route::post('/', [KuisController::class, 'store'])->name('store');
    Route::get('/{id}/edit', [KuisController::class, 'edit'])->name('edit');
    Route::put('/{id}', [KuisController::class, 'update'])->name('update');
    Route::put('/{id}/status', [KuisController::class, 'updateStatus'])->name('update-status');
    Route::delete('/{id}', [KuisController::class, 'destroy'])->name('destroy');

    // Soal Management
    Route::post('/{kuisId}/soal', [KuisController::class, 'storeSoal'])->name('soal.store');
    Route::put('/soal/{soalId}', [KuisController::class, 'updateSoal'])->name('soal.update');
    Route::delete('/soal/{soalId}', [KuisController::class, 'destroySoal'])->name('soal.destroy');
    Route::post('/{kuisId}/soal/reorder', [KuisController::class, 'reorderSoal'])->name('soal.reorder');
});

// Shared Routes (All Authenticated Users)
Route::middleware('auth')->group(function () {
    // Materi
    Route::get('/materi', [MateriController::class, 'index'])->name('materi.index');
    Route::get('/materi/alfabet', [MateriController::class, 'alfabet'])->name('materi.alfabet');
    Route::get('/materi/warna', [MateriController::class, 'warna'])->name('materi.warna');
    Route::get('/materi/hewan', [MateriController::class, 'hewan'])->name('materi.hewan');

    // Permainan
    Route::get('/permainan', [PermainanController::class, 'index'])->name('permainan.index');
    Route::get('/permainan/puzzle', [PermainanController::class, 'puzzle'])->name('permainan.puzzle');

    // Kuis - View & Play (All users)
    Route::get('/kuis', [KuisController::class, 'index'])->name('kuis.index');
    Route::get('/kuis/{id}', [KuisController::class, 'show'])->name('kuis.show');
    Route::get('/kuis/{id}/soal', [KuisController::class, 'getSoal'])->name('kuis.get-soal');
});
