<?php

use App\Http\Controllers\RekomendasiController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\KandunganGiziController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PemilihanController;
use App\Http\Controllers\Admin\RiwayatPemilihanController;

// ── PUBLIC — Orang Tua ────────────────────────────────────────────
Route::get( '/rekomendasi',        [RekomendasiController::class, 'index'])  ->name('rekomendasi.index');
Route::post('/rekomendasi/proses', [RekomendasiController::class, 'proses']) ->name('rekomendasi.proses');
Route::post(
    '/rekomendasi/pilih',
    [PemilihanController::class, 'store']
)->name('rekomendasi.pilih');

// ── ADMIN — Key-based access (/admin?key=admin123) ────────────────
Route::prefix('admin')
     ->middleware('admin.key')
     ->name('admin.')
     ->group(function () {

    // Dashboard & Debug
    Route::get('/',       [DashboardController::class, 'index']) ->name('dashboard');
    Route::get('/debug',  [DashboardController::class, 'debug']) ->name('debug');

    // Menu CRUD
    Route::resource('menu', MenuController::class)->except(['show']);

    // Kandungan Gizi
    Route::get( '/gizi',           [KandunganGiziController::class, 'index'])  ->name('gizi.index');
    Route::get( '/gizi/{menu}/edit', [KandunganGiziController::class, 'edit'])   ->name('gizi.edit');
    Route::put( '/gizi/{menu}',    [KandunganGiziController::class, 'update']) ->name('gizi.update');

    Route::get(
        '/riwayat-pemilihan',
        [RiwayatPemilihanController::class, 'index']
    )->name('riwayat.index');
});
