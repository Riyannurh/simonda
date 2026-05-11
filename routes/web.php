<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\DataUmkmController;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\Pendamping\DashboardController as PendampingDashboardController;
use App\Http\Controllers\KepalaBagian\DashboardController as KepalaBagianDashboardController;

// Landing Page
Route::get('/', [LandingController::class, 'index'])->name('landing');

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected Routes
Route::middleware(['auth', 'no-cache'])->group(function () {
    // General Dashboard Route
    Route::get('/dashboard', function () {
        $user = auth()->user();
        switch($user->role) {
            case 'admin':
                return redirect()->route('admin.dashboard');
            case 'pendamping':
                return redirect()->route('pendamping.dashboard');
            case 'kepala_bagian':
                return redirect()->route('kepala-bagian.dashboard');
            default:
                return redirect()->route('landing');
        }
    })->name('dashboard');
    
    // Profile Routes
    Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'index'])->name('profile');
    Route::put('/profile', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [\App\Http\Controllers\ProfileController::class, 'updatePassword'])->name('profile.password');
    
    // Admin Routes
    Route::prefix('admin')->name('admin.')->middleware('role:admin')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        
        // Master Data Routes
        Route::resource('legalitas-usaha', \App\Http\Controllers\Admin\MasterLegalitasUsahaController::class);
        Route::resource('kategori-usaha', \App\Http\Controllers\Admin\KategoriUsahaController::class)->except(['show', 'create', 'edit']);
        Route::resource('kelas-usaha', \App\Http\Controllers\Admin\KelasUsahaController::class)->except(['show', 'create', 'edit']);
        
        // Management Routes
        Route::resource('pengguna', \App\Http\Controllers\Admin\PenggunaController::class)->except(['show', 'create', 'edit']);
        
        // Data UMKM Routes
        Route::get('/data-umkm', [DataUmkmController::class, 'index'])->name('data-umkm.index');
        Route::get('/data-umkm/api/wilayah', [DataUmkmController::class, 'getWilayah'])->name('data-umkm.api.wilayah');
        Route::post('/data-umkm', [DataUmkmController::class, 'store'])->name('data-umkm.store');
        Route::get('/data-umkm/{id}', [DataUmkmController::class, 'show'])->name('data-umkm.show');
        Route::get('/data-umkm/{id}/detail', [DataUmkmController::class, 'detail'])->name('data-umkm.detail');
        Route::get('/data-umkm/{id}/edit', [DataUmkmController::class, 'edit'])->name('data-umkm.edit');
        Route::put('/data-umkm/{id}', [DataUmkmController::class, 'update'])->name('data-umkm.update');
        Route::delete('/data-umkm/{id}', [DataUmkmController::class, 'destroy'])->name('data-umkm.destroy');
        
        Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
        Route::get('/laporan/export/excel', [LaporanController::class, 'exportExcel'])->name('laporan.export.excel');
        Route::get('/laporan/export/pdf', [LaporanController::class, 'exportPdf'])->name('laporan.export.pdf');

        // Notifications
        Route::get('/notifications', [\App\Http\Controllers\Admin\NotificationController::class, 'index'])->name('notifications.index');
        Route::get('/notifications/unread-count', [\App\Http\Controllers\Admin\NotificationController::class, 'unreadCount'])->name('notifications.unread-count');
        Route::delete('/notifications/{id}', [\App\Http\Controllers\Admin\NotificationController::class, 'destroy'])->name('notifications.destroy');
    });
    
    // Pendamping Routes
    Route::prefix('pendamping')->name('pendamping.')->middleware('role:pendamping')->group(function () {
        Route::get('/dashboard', [PendampingDashboardController::class, 'index'])->name('dashboard');
        
        // Data UMKM
        Route::get('/data-umkm', [\App\Http\Controllers\Pendamping\DataUmkmController::class, 'index'])->name('data-umkm.index');
        Route::get('/data-umkm/api/wilayah', [\App\Http\Controllers\Pendamping\DataUmkmController::class, 'getWilayah'])->name('data-umkm.api.wilayah');
        Route::post('/data-umkm', [\App\Http\Controllers\Pendamping\DataUmkmController::class, 'store'])->name('data-umkm.store');
        Route::get('/data-umkm/{id}/detail', [\App\Http\Controllers\Pendamping\DataUmkmController::class, 'detail'])->name('data-umkm.detail');
        Route::get('/data-umkm/{id}/edit', [\App\Http\Controllers\Pendamping\DataUmkmController::class, 'edit'])->name('data-umkm.edit');
        Route::put('/data-umkm/{id}', [\App\Http\Controllers\Pendamping\DataUmkmController::class, 'update'])->name('data-umkm.update');
        Route::delete('/data-umkm/{id}', [\App\Http\Controllers\Pendamping\DataUmkmController::class, 'destroy'])->name('data-umkm.destroy');

        Route::get('/laporan', [\App\Http\Controllers\Pendamping\LaporanController::class, 'index'])->name('laporan.index');
        Route::get('/laporan/export/excel', [\App\Http\Controllers\Pendamping\LaporanController::class, 'exportExcel'])->name('laporan.export.excel');
        Route::get('/laporan/export/pdf', [\App\Http\Controllers\Pendamping\LaporanController::class, 'exportPdf'])->name('laporan.export.pdf');
    });
    
    // Kepala Bagian Routes
    Route::prefix('kepala-bagian')->name('kepala-bagian.')->middleware('role:kepala_bagian')->group(function () {
        Route::get('/dashboard', [KepalaBagianDashboardController::class, 'index'])->name('dashboard');
        
        Route::get('/data-umkm', [\App\Http\Controllers\KepalaBagian\DataUmkmController::class, 'index'])->name('data-umkm.index');
        Route::get('/data-umkm/{id}/detail', [\App\Http\Controllers\KepalaBagian\DataUmkmController::class, 'detail'])->name('data-umkm.detail');

        Route::get('/laporan', [\App\Http\Controllers\KepalaBagian\LaporanController::class, 'index'])->name('laporan.index');
        Route::get('/laporan/export/excel', [\App\Http\Controllers\KepalaBagian\LaporanController::class, 'exportExcel'])->name('laporan.export.excel');
        Route::get('/laporan/export/pdf', [\App\Http\Controllers\KepalaBagian\LaporanController::class, 'exportPdf'])->name('laporan.export.pdf');
    });
});
