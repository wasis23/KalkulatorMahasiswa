<?php

use App\Http\Controllers\PublicCalculatorController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AdminProdiController;
use App\Http\Controllers\AdminBeasiswaController;

// Public route for student calculator
Route::get('/', [PublicCalculatorController::class, 'index'])->name('home');

// Authentication routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Admin panel routes (Protected by Auth middleware)
Route::middleware(['auth'])->prefix('admin')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('admin.dashboard');

    // Prodi Management (CRUD)
    Route::prefix('prodi')->group(function () {
        Route::get('/', [AdminProdiController::class, 'index'])->name('admin.prodi.index');
        Route::get('/create', [AdminProdiController::class, 'create'])->name('admin.prodi.create');
        Route::post('/', [AdminProdiController::class, 'store'])->name('admin.prodi.store');
        Route::get('/{prodi}/edit', [AdminProdiController::class, 'edit'])->name('admin.prodi.edit');
        Route::put('/{prodi}', [AdminProdiController::class, 'update'])->name('admin.prodi.update');
        Route::delete('/{prodi}', [AdminProdiController::class, 'destroy'])->name('admin.prodi.destroy');
    });

    // Beasiswa Management & Rules Engine
    Route::prefix('beasiswa')->group(function () {
        Route::get('/', [AdminBeasiswaController::class, 'index'])->name('admin.beasiswa.index');
        Route::post('/', [AdminBeasiswaController::class, 'store'])->name('admin.beasiswa.store');
        Route::put('/{beasiswa}', [AdminBeasiswaController::class, 'update'])->name('admin.beasiswa.update');
        Route::delete('/{beasiswa}', [AdminBeasiswaController::class, 'destroy'])->name('admin.beasiswa.destroy');
        
        // Rules Engine
        Route::get('/{beasiswa}/rules', [AdminBeasiswaController::class, 'manageRules'])->name('admin.beasiswa.rules');
        Route::post('/{beasiswa}/rules', [AdminBeasiswaController::class, 'storeRule'])->name('admin.beasiswa.storeRule');
        Route::delete('/{beasiswa}/rules/{rule}', [AdminBeasiswaController::class, 'destroyRule'])->name('admin.beasiswa.destroyRule');
    });
});
