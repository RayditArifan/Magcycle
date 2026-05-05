<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MitraRegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MitraController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\MitraPasswordResetController;



/* Umum */
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/register', [MitraRegisterController::class, 'create'])->name('mitra.register');
Route::post('/register', [MitraRegisterController::class, 'store'])->name('mitra.register.store');

Route::get('/mitra/lupa-password', [MitraPasswordResetController::class, 'showForgotForm'])
    ->name('mitra.password.forgot');
Route::post('/mitra/lupa-password', [MitraPasswordResetController::class, 'sendResetLink'])
    ->name('mitra.password.sendLink');

Route::get('/mitra/reset-password/{token}', [MitraPasswordResetController::class, 'showResetForm'])
    ->name('mitra.password.reset');
Route::post('/mitra/reset-password/{token}', [MitraPasswordResetController::class, 'resetPassword'])
    ->name('mitra.password.update');

/* Admin */
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/admin/profile', [ProfileController::class, 'adminIndex'])->name('admin.profile');
Route::get('/admin/profile/edit', [ProfileController::class, 'adminEdit'])->name('admin.profile.edit');
Route::post('/admin/profile/edit', [ProfileController::class, 'adminUpdate'])->name('admin.profile.update');
Route::post('/admin/profile/update', [ProfileController::class, 'adminUpdate'])->name('admin.profile.update');

Route::get('/admin/profil-mitra', [ProfileController::class, 'adminMitraIndex'])
    ->name('admin.mitra.profiles');

Route::get('/admin/profil-mitra/{id_mitra}', [ProfileController::class, 'adminMitraShow'])
    ->name('admin.mitra.profiles.show');

Route::post('/admin/profil-mitra/{id_mitra}', [ProfileController::class, 'adminMitraUpdateStatus'])
    ->name('admin.mitra.profiles.update');

Route::get('/admin/manajemen-stok', [StockController::class, 'index'])
    ->name('admin.stok.index');
Route::post('/admin/manajemen-stok', [StockController::class, 'store'])
    ->name('admin.stok.store');
Route::post('/admin/manajemen-stok/{id_stok}/update', [StockController::class, 'update'])
    ->name('admin.stok.update');

/* Mitra */

Route::get('/beranda', [MitraController::class, 'beranda'])->name('mitra.beranda');
Route::get('/mitra/profile', [ProfileController::class, 'mitraIndex'])->name('mitra.profile');
Route::get('/mitra/profile/edit', [ProfileController::class, 'mitraEdit'])->name('mitra.profile.edit');
Route::post('/mitra/profile/update', [ProfileController::class, 'mitraUpdate'])->name('mitra.profile.update');
