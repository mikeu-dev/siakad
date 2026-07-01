<?php

use App\Http\Controllers\Admin\AuditLogController;
use App\Http\Controllers\Admin\DosenController;
use App\Http\Controllers\Admin\KeuanganController;
use App\Http\Controllers\Admin\MahasiswaController;
use App\Http\Controllers\Admin\MataKuliahController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Dosen\NilaiController as DosenNilaiController;
use App\Http\Controllers\Mahasiswa\KeuanganController as MahasiswaKeuanganController;
use App\Http\Controllers\Mahasiswa\KrsController;
use App\Http\Controllers\Mahasiswa\NilaiController as MahasiswaNilaiController;
use Illuminate\Support\Facades\Route;

Route::inertia('/', 'Welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::inertia('dashboard', 'Dashboard')->name('dashboard');

    // Administrator routes
    Route::middleware('role:administrator')->prefix('admin')->name('admin.')->group(function () {
        Route::resource('mahasiswa', MahasiswaController::class)->except(['show']);
        Route::resource('dosen', DosenController::class)->except(['show']);
        Route::resource('mata-kuliah', MataKuliahController::class)->except(['show']);
        Route::resource('keuangan', KeuanganController::class)->except(['show']);
        Route::resource('users', UserController::class)->except(['show']);
        Route::get('audit-log', [AuditLogController::class, 'index'])->name('audit-log.index');
    });

    // Dosen routes
    Route::middleware('role:dosen')->prefix('dosen')->name('dosen.')->group(function () {
        Route::get('nilai', [DosenNilaiController::class, 'index'])->name('nilai.index');
        Route::post('nilai', [DosenNilaiController::class, 'store'])->name('nilai.store');
        Route::patch('nilai/{nilai}/finalisasi', [DosenNilaiController::class, 'finalisasi'])->name('nilai.finalisasi');
    });

    // Mahasiswa routes
    Route::middleware('role:mahasiswa')->prefix('mahasiswa')->name('mahasiswa.')->group(function () {
        Route::get('krs', [KrsController::class, 'index'])->name('krs.index');
        Route::post('krs', [KrsController::class, 'store'])->name('krs.store');
        Route::delete('krs/{krs}', [KrsController::class, 'destroy'])->name('krs.destroy');
        Route::get('nilai', [MahasiswaNilaiController::class, 'index'])->name('nilai.index');
        Route::get('keuangan', [MahasiswaKeuanganController::class, 'index'])->name('keuangan.index');
    });
});

require __DIR__.'/settings.php';
