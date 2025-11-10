<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BalitaController;
// use App\Http\Controllers\Api\KegiatanController;
// use App\Http\Controllers\Api\PengukuranController;
// use App\Http\Controllers\Api\ImunisasiController;
// use App\Http\Controllers\Api\VitaminObatController;
// use App\Http\Controllers\Api\KaderController;
// use App\Http\Controllers\Api\DashboardController;
// use App\Http\Controllers\Api\LaporanController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes - Posyandu System
|--------------------------------------------------------------------------
*/

// Authentication Routes (Public)
Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    
    // Protected Auth Routes
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/user', [AuthController::class, 'user']);
    });
});

// Protected Routes (Require Authentication)
Route::middleware('auth:sanctum')->group(function () {
    
    // Balita Routes
    Route::prefix('balita')->group(function () {
        Route::get('/', [BalitaController::class, 'index']);
        Route::get('/search', [BalitaController::class, 'searchByCode']);
        Route::get('/{id}', [BalitaController::class, 'show']);
        Route::post('/', [BalitaController::class, 'store']);
        Route::put('/{id}', [BalitaController::class, 'update']);
        Route::post('/{id}', [BalitaController::class, 'update']); // For multipart/form-data
        Route::delete('/{id}', [BalitaController::class, 'destroy']);
    });

    // TODO: Uncomment setelah controller dibuat di phase selanjutnya
    
    // // Kegiatan Routes
    // Route::prefix('kegiatan')->group(function () {
    //     Route::get('/', [KegiatanController::class, 'index']);
    //     Route::get('/{id}', [KegiatanController::class, 'show']);
    //     Route::post('/', [KegiatanController::class, 'store']);
    //     Route::put('/{id}', [KegiatanController::class, 'update']);
    //     Route::delete('/{id}', [KegiatanController::class, 'destroy']);
    // });

    // // Pengukuran Routes
    // Route::prefix('pengukuran')->group(function () {
    //     Route::get('/balita/{balita_id}', [PengukuranController::class, 'getByBalita']);
    //     Route::post('/', [PengukuranController::class, 'store']);
    //     Route::put('/{id}', [PengukuranController::class, 'update']);
    //     Route::delete('/{id}', [PengukuranController::class, 'destroy']);
    // });

    // // Imunisasi Routes
    // Route::prefix('imunisasi')->group(function () {
    //     Route::get('/balita/{balita_id}', [ImunisasiController::class, 'getByBalita']);
    //     Route::get('/jadwal/{balita_id}', [ImunisasiController::class, 'getJadwalBelumLengkap']);
    //     Route::post('/', [ImunisasiController::class, 'store']);
    //     Route::put('/{id}', [ImunisasiController::class, 'update']);
    //     Route::delete('/{id}', [ImunisasiController::class, 'destroy']);
    // });

    // // Vitamin & Obat Routes
    // Route::prefix('vitamin-obat')->group(function () {
    //     Route::get('/balita/{balita_id}', [VitaminObatController::class, 'getByBalita']);
    //     Route::post('/', [VitaminObatController::class, 'store']);
    //     Route::put('/{id}', [VitaminObatController::class, 'update']);
    //     Route::delete('/{id}', [VitaminObatController::class, 'destroy']);
    // });

    // // Kader Routes
    // Route::prefix('kader')->group(function () {
    //     Route::get('/', [KaderController::class, 'index']);
    //     Route::post('/', [KaderController::class, 'store']);
    //     Route::put('/{id}', [KaderController::class, 'update']);
    //     Route::delete('/{id}', [KaderController::class, 'destroy']);
    // });

    // // Dashboard & Statistik Routes
    // Route::prefix('dashboard')->group(function () {
    //     Route::get('/stats', [DashboardController::class, 'getStatistics']);
    //     Route::get('/grafik-pertumbuhan/{balita_id}', [DashboardController::class, 'getGrafikPertumbuhan']);
    //     Route::get('/statistik-gizi', [DashboardController::class, 'getStatistikGizi']);
    //     Route::get('/statistik-imunisasi', [DashboardController::class, 'getStatistikImunisasi']);
    // });

    // // Laporan & Export Routes
    // Route::prefix('laporan')->group(function () {
    //     Route::get('/bulanan', [LaporanController::class, 'getLaporanBulanan']);
    // });

    // Route::prefix('export')->group(function () {
    //     Route::get('/balita', [LaporanController::class, 'exportBalita']);
    //     Route::get('/laporan-bulanan', [LaporanController::class, 'exportLaporanBulanan']);
    // });
});