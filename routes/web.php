<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PengelolaanAkunController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\JadwalController;
use App\Http\Controllers\UnggahJadwalController;
use App\Http\Controllers\PerubahanJadwalController;
use App\Http\Controllers\JadwalTaController;
use App\Http\Controllers\JadwalTpbController;
use App\Http\Controllers\JadwalProdiController;
use App\Http\Controllers\JadwalLainController;
use App\Http\Controllers\SetujuPerubahanJadwalController;
use App\Http\Controllers\TolakPerubahanJadwalController;
use App\Http\Controllers\PengaturanController;
use App\Http\Controllers\KalenderController;
use App\Http\Controllers\Auth\PasswordController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::get('/dashboard', function () {
    return redirect()->route('jadwal-prodi.index');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'menu'])->group(function () {
    Route::get('/jadwal-saya', [JadwalController::class, 'pribadi'])->name('jadwal-saya');
    Route::get('/jadwal-tabrakan', [JadwalController::class, 'tabrakan'])->name('jadwal-tabrakan');
    Route::put('/jadwal/{jadwal}/ambil', [JadwalController::class, 'ambil'])->name('jadwal.ambil');
    Route::get('/jadwal/export', [JadwalController::class, 'exportView'])->name('jadwal.export');
    Route::post('/jadwal/export', [JadwalController::class, 'export'])->name('jadwal.export');

//    Jadwal resource
    Route::get('/jadwal/{id}', [\App\Http\Controllers\JadwalBaseController::class, 'show'])->name('jadwal.show');
    Route::resource('jadwal-ta', JadwalTaController::class)->parameters(['jadwal-ta' => 'id'])->except(['edit', 'create']);
    Route::resource('jadwal-tpb', JadwalTpbController::class)->parameters(['jadwal-tpb' => 'id'])->except(['edit', 'create']);
    Route::resource('jadwal-prodi', JadwalProdiController::class)->parameters(['jadwal-prodi' => 'id'])->except(['edit', 'create']);
    Route::resource('jadwal-lain', JadwalLainController::class)->parameters(['jadwal-lain' => 'id'])->except(['edit', 'create']);

    Route::get('/unggah-jadwal', [UnggahJadwalController::class, 'index'])->name('unggah-jadwal');
    Route::post('/unggah-jadwal', [UnggahJadwalController::class, 'store'])->name('unggah-jadwal.store');

    Route::get('/pengelolaan-akun', PengelolaanAkunController::class)->name('pengelolaan-akun');

    Route::get('/pengajuan-perubahan-jadwal', [PerubahanJadwalController::class, 'index'])->name('perubahan-jadwal.index');
    Route::post('pengajuan-perubahan-jadwal/setuju/{id}', SetujuPerubahanJadwalController::class)->name('perubahan-jadwal.setuju');
    Route::post('pengajuan-perubahan-jadwal/tolak/{id}', TolakPerubahanJadwalController::class)->name('perubahan-jadwal.tolak');
    Route::get('/pengajuan-perubahan-jadwal/create', [PerubahanJadwalController::class, 'create'])->name('perubahan-jadwal.create');
    Route::post('/pengajuan-perubahan-jadwal/create', [PerubahanJadwalController::class, 'store'])->name('perubahan-jadwal.store');

    Route::resource('/user', UserController::class)->except(['edit', 'create']);
    Route::resource('/role', RoleController::class)->except(['edit', 'create']);

    Route::get('/pengaturan', [PengaturanController::class, 'index'])->name('pengaturan.index');
    Route::patch('/pengaturan', [PengaturanController::class, 'update'])->name('pengaturan.update');
    Route::post('clear-data', [PengaturanController::class, 'clear_data'])->name('pengaturan.clear-data');

    Route::get('/profile', [PasswordController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
