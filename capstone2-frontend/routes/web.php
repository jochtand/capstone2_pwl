<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RuanganController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\KepalaLabController;
use App\Http\Controllers\KaprodiController; 
use App\Http\Middleware\CheckUserSession;
use App\Http\Controllers\StafAdminController;
use App\Http\Controllers\StafLabController;

Route::get('/', function () {
    // 1. Cek apakah ada sesi user yang masih aktif
    if (session()->has('user')) {
        $role = session('user.role');
        switch ($role) {
            case 'Administrator': return redirect('/admin/ruangan');
            case 'Kepala Laboratorium': return redirect('/kepala-lab/draft');
            case 'Kaprodi': return redirect('/kaprodi/review');
            case 'Staf Administrasi': return redirect('/staf-admin/pengadaan');
            // FIX: Arahkan Staf Lab ke dashboard inventaris barunya
            case 'Staf Laboratorium': return redirect('/staf-lab/inventaris'); 
        }
    }
    
    // 2. Tampilkan halaman login dengan instruksi Anti-Cache
    return response()->view('auth.login')
        ->header('Cache-Control', 'no-cache, no-store, max-age=0, must-revalidate')
        ->header('Pragma', 'no-cache')
        ->header('Expires', 'Sat, 01 Jan 1990 00:00:00 GMT');
});

// Route Autentikasi
Route::get('/login', [AuthController::class, 'showLogin']);
Route::post('/login', [AuthController::class, 'processLogin']);
Route::get('/logout', [AuthController::class, 'logout']);

// ==========================================
// GRUP ROUTE KHUSUS ADMINISTRATOR
// ==========================================
Route::middleware([CheckUserSession::class.':Administrator'])->group(function () {
    // Ruangan
    Route::get('/admin/ruangan', [RuanganController::class, 'index']);
    Route::post('/admin/ruangan', [RuanganController::class, 'store']);
    Route::post('/admin/ruangan/{id}/update', [RuanganController::class, 'update']);
    Route::post('/admin/ruangan/{id}/delete', [RuanganController::class, 'destroy']);
    Route::get('/admin/users', [UserController::class, 'index']);
    Route::post('/admin/users', [UserController::class, 'store']);
    Route::post('/admin/users/{id}/update', [UserController::class, 'update']);
    Route::post('/admin/users/{id}/delete', [UserController::class, 'destroy']);
});

// ==========================================
// GRUP ROUTE KHUSUS KEPALA LABORATORIUM
// ==========================================
Route::middleware([CheckUserSession::class.':Kepala Laboratorium'])->group(function () {
    Route::get('/kepala-lab/draft', [KepalaLabController::class, 'index']);
    Route::get('/kepala-lab/draft/create', [KepalaLabController::class, 'create']);
    Route::post('/kepala-lab/draft', [KepalaLabController::class, 'store']);
    Route::get('/kepala-lab/draft/{id}', [KepalaLabController::class, 'show']);
    Route::post('/kepala-lab/draft/{id}/submit', [KepalaLabController::class, 'submit']);
    Route::post('/kepala-lab/item/{id}/update', [KepalaLabController::class, 'updateItem']);
    Route::post('/kepala-lab/item/{id}/delete', [KepalaLabController::class, 'destroyItem']);
    Route::get('/kepala-lab/inventaris', [KepalaLabController::class, 'inventaris']);
    Route::post('/kepala-lab/draft/{id}/delete', [KepalaLabController::class, 'destroyDraft']);
});

// ==========================================
// GRUP ROUTE KHUSUS KAPRODI
// ==========================================
Route::middleware([CheckUserSession::class.':Kaprodi'])->group(function () {
    Route::get('/kaprodi/review', [KaprodiController::class, 'index']);
    Route::post('/kaprodi/draft/{id}/review', [KaprodiController::class, 'review']);
    Route::get('/kaprodi/draft/{id}', [KaprodiController::class, 'show']);
    Route::post('/kaprodi/draft/{id}/finalize', [KaprodiController::class, 'finalize']);
    Route::get('/kaprodi/draft/{id}/pdf', [KaprodiController::class, 'cetakPdf']);
    Route::get('/kaprodi/inventaris', [KaprodiController::class, 'inventaris']);
});

// ==========================================
// GRUP ROUTE KHUSUS STAF ADMINISTRASI
// ==========================================
Route::middleware([CheckUserSession::class.':Staf Administrasi'])->group(function () {
    Route::get('/staf-admin/pengadaan', [StafAdminController::class, 'index']);
    Route::get('/staf-admin/pengadaan/{id}', [StafAdminController::class, 'detail']);
    Route::post('/staf-admin/barang/{id}/terima', [StafAdminController::class, 'terimaBarang']);
    Route::get('/staf-admin/inventaris', [StafAdminController::class, 'inventaris']);
});

// ==========================================
// GRUP ROUTE KHUSUS STAF LABORATORIUM
// ==========================================
Route::middleware([CheckUserSession::class.':Staf Laboratorium'])->group(function () {
    Route::get('/staf-lab/inventaris', [StafLabController::class, 'inventaris']);
    Route::post('/staf-lab/inventaris', [StafLabController::class, 'storeInventaris']);
    Route::post('/staf-lab/inventaris/{id}/update', [StafLabController::class, 'updateInventaris']);
    Route::post('/staf-lab/inventaris/{id}/replace', [StafLabController::class, 'replaceInventaris']);
    Route::post('/staf-lab/bhp', [StafLabController::class, 'storeBhp']);
    Route::post('/staf-lab/bhp/{id}/update', [StafLabController::class, 'updateBhp']);
});