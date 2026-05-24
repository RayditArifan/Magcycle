<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MitraRegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MitraController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\MitraPasswordResetController;
use App\Http\Controllers\PengambilanSampahController;
use App\Http\Controllers\PengambilanSampahMitraController;
use App\Http\Controllers\PanduanSetorController;
use App\Http\Controllers\SiklusMaggotController;
use App\Http\Controllers\LaporanController;

/* Umum */
Route::redirect('/', '/login');
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

Route::get('/admin/panduan-setor', [PanduanSetorController::class, 'index'])
    ->name('admin.panduan-setor.index');
Route::post('/admin/panduan-setor', [PanduanSetorController::class, 'store'])
    ->name('admin.panduan-setor.store');
Route::put('/admin/panduan-setor/{idPanduan}', [PanduanSetorController::class, 'update'])
    ->name('admin.panduan-setor.update');
Route::delete('/admin/panduan-setor/{idPanduan}', [PanduanSetorController::class, 'destroy'])
    ->name('admin.panduan-setor.destroy');

Route::get('/admin/pengambilan-sampah', [PengambilanSampahController::class, 'index'])
    ->name('admin.pengambilan-sampah.index');
Route::patch('/admin/pengambilan-sampah/{jadwal}/status', [PengambilanSampahController::class, 'updateStatus'])
    ->name('admin.pengambilan-sampah.update-status');
Route::get('/admin/pengambilan-sampah/riwayat', [PengambilanSampahController::class, 'riwayat'])
    ->name('admin.pengambilan-sampah.riwayat');
Route::patch('/admin/pengambilan-sampah/riwayat/{jadwal}/catatan', [PengambilanSampahController::class, 'simpanCatatan'])
    ->name('admin.pengambilan-sampah.simpan-catatan');

Route::get('/admin/siklus-maggot', [SiklusMaggotController::class, 'index'])
    ->name('admin.siklus-maggot.index');
Route::post('/admin/siklus-maggot', [SiklusMaggotController::class, 'store'])
    ->name('admin.siklus-maggot.store');
Route::get('/admin/siklus-maggot/{siklus}', [SiklusMaggotController::class, 'show'])
    ->whereNumber('siklus')
    ->name('admin.siklus-maggot.show');
Route::put('/admin/siklus-maggot/{siklus}', [SiklusMaggotController::class, 'update'])
    ->whereNumber('siklus')
    ->name('admin.siklus-maggot.update');

Route::get('/admin/laporanku/buat-laporan-sampah', [LaporanController::class, 'buatLaporanSampah'])
    ->name('admin.laporanku.buat-laporan-sampah');
Route::post('/admin/laporanku/buat-laporan-sampah', [LaporanController::class, 'storeLaporanSampah'])
    ->name('admin.laporanku.store-laporan-sampah');
Route::get('/admin/laporanku/laporan-sampah', [LaporanController::class, 'lihatLaporanSampah'])
    ->name('admin.laporanku.laporan-sampah');
Route::get('/admin/laporanku/laporan-produksi', [LaporanController::class, 'lihatLaporanProduksi'])
    ->name('admin.laporanku.laporan-produksi');

/* API Publik */
Route::get('/api/kecamatan', function (\Illuminate\Http\Request $request) {
    $kecamatan = \Illuminate\Support\Facades\DB::table('kecamatan')
        ->where('id_kota', $request->id_kota)
        ->orderBy('nama_kecamatan')
        ->get(['id_kecamatan', 'nama_kecamatan']);
    return response()->json($kecamatan);
});

/* Mitra */

Route::get('/beranda', [MitraController::class, 'beranda'])->name('mitra.beranda');
Route::get('/mitra/profile', [ProfileController::class, 'mitraIndex'])->name('mitra.profile');
Route::get('/mitra/profile/edit', [ProfileController::class, 'mitraEdit'])->name('mitra.profile.edit');
Route::post('/mitra/profile/update', [ProfileController::class, 'mitraUpdate'])->name('mitra.profile.update');

Route::get('/mitra/pengambilan-sampah', [PengambilanSampahMitraController::class, 'index'])
    ->name('mitra.pengambilan-sampah.index');
Route::post('/mitra/pengambilan-sampah', [PengambilanSampahMitraController::class, 'store'])
    ->name('mitra.pengambilan-sampah.store');
Route::get('/mitra/pengambilan-sampah/riwayat', [PengambilanSampahMitraController::class, 'riwayat'])
    ->name('mitra.pengambilan-sampah.riwayat');
Route::get('/mitra/pengambilan-sampah/{jadwal}', [PengambilanSampahMitraController::class, 'show'])
    ->name('mitra.pengambilan-sampah.show');
Route::patch('/mitra/pengambilan-sampah/{jadwal}/batal', [PengambilanSampahMitraController::class, 'batal'])
    ->name('mitra.pengambilan-sampah.batal');
