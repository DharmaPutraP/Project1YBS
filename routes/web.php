<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\OilController;
// use App\Http\Controllers\TimbanganController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UserController;
// use App\Http\Controllers\SettingController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// ─── Guest routes ─────────────────────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    // Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    // Route::post('/register', [AuthController::class, 'register']);
});

// ─── Authenticated routes ──────────────────────────────────────────────────────
Route::middleware('auth')->group(function () {

    // ── Dashboard ──────────────────────────────────────────────────────────────
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard')->middleware('permission:view dashboard');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // ══════════════════════════════════════════════════════════════════════════
    // ── OIL LOSSES ──────────────────────────────────────────────────────────
    // ══════════════════════════════════════════════════════════════════════════
    Route::prefix('oil')->name('oil.')->group(function () {
        Route::get('/', [OilController::class, 'index'])
            ->name('index')
            ->middleware('permission:view oil losses');

        Route::get('/olwb', [OilController::class, 'olwbIndex'])
            ->name('olwb')
            ->middleware('permission:view olwb');

        Route::post('/olwb/export', [OilController::class, 'exportOlwb'])
            ->name('olwb.export')
            ->middleware('permission:export olwb reports');

        Route::get('/report', [OilController::class, 'reportIndex'])
            ->name('report')
            ->middleware('permission:export performance oil losses');

        Route::post('/report/export', [OilController::class, 'exportPerformance'])
            ->name('report.export')
            ->middleware('permission:export performance oil losses');

        Route::get('/create', [OilController::class, 'create'])
            ->name('create')
            ->middleware('permission:create oil losses');

        Route::post('/', [OilController::class, 'store'])
            ->name('store')
            ->middleware('permission:create oil losses');

        Route::get('/{oilCalculation}', [OilController::class, 'show'])
            ->name('show')
            ->middleware('permission:view oil losses');

        Route::get('/{oilCalculation}/edit', [OilController::class, 'edit'])
            ->name('edit')
            ->middleware('permission:edit oil losses');

        Route::put('/{oilCalculation}', [OilController::class, 'update'])
            ->name('update')
            ->middleware('permission:edit oil losses');

        Route::delete('/{oilCalculation}', [OilController::class, 'destroy'])
            ->name('destroy')
            ->middleware('permission:delete oil losses');

        Route::delete('/records/{oilRecord}', [OilController::class, 'destroyRecord'])
            ->name('records.destroy')
            ->middleware('permission:delete oil losses');
    });

    // ══════════════════════════════════════════════════════════════════════════
    // ── LAPORAN (REPORTS) ─────────────────────────────────────────────────────
    // ══════════════════════════════════════════════════════════════════════════
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [ReportController::class, 'index'])
            ->name('index')
            ->middleware('permission:view laporan oil losses');

        Route::post('/export', [ReportController::class, 'export'])
            ->name('export')
            ->middleware('permission:export laporan oil losses');
    });

    // ══════════════════════════════════════════════════════════════════════════
    // ── MANAJEMEN PENGGUNA ────────────────────────────────────────────────────
    // ══════════════════════════════════════════════════════════════════════════
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [UserController::class, 'index'])
            ->name('index')
            ->middleware('permission:view users');

        Route::get('/create', [UserController::class, 'create'])
            ->name('create')
            ->middleware('permission:create users');

        Route::post('/', [AuthController::class, 'register'])
            ->name('store')
            ->middleware('permission:create users');

        Route::get('/{id}/edit', [UserController::class, 'edit'])
            ->name('edit')
            ->middleware('permission:edit users');

        Route::put('/{id}', [UserController::class, 'update'])
            ->name('update')
            ->middleware('permission:edit users');

        Route::delete('/{id}', [UserController::class, 'destroy'])
            ->name('destroy')
            ->middleware('permission:delete users');

        Route::post('/{id}/reset-password', [UserController::class, 'resetPassword'])
            ->name('reset-password')
            ->middleware('permission:reset user password');
    });

});
