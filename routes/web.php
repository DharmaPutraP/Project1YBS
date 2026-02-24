<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\LabController;
use App\Http\Controllers\TimbanganController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SettingController;
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
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // ─── Laboratorium ──────────────────────────────────────────────────────────
    Route::get('/lab', [LabController::class, 'index'])->name('lab.index');

    // ─── Timbangan ─────────────────────────────────────────────────────────────
    Route::get('/timbangan', [TimbanganController::class, 'index'])->name('timbangan.index');

    // ─── Laporan ───────────────────────────────────────────────────────────────
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');

    // ─── Kelola Pengguna ───────────────────────────────────────────────────────
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::post('/users', [AuthController::class, 'register'])->name('users.store');

    // ─── Pengaturan ────────────────────────────────────────────────────────────
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
});
