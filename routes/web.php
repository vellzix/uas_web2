<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\RoleMiddleware;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DosenController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\DosenDashboardController;
use App\Http\Controllers\MahasiswaDashboardController;
use App\Http\Controllers\Admin\MahasiswaController as AdminMahasiswaController;
use App\Http\Controllers\Admin\DosenController as AdminDosenController;
use App\Http\Controllers\Admin\MataKuliahController as AdminMataKuliahController;
use App\Http\Controllers\Admin\PengumumanController as AdminPengumumanController;
use App\Http\Controllers\Admin\PeriodeAkademikController;
use App\Http\Controllers\Admin\JadwalController;
use App\Http\Controllers\Dosen\PresensiController;
use App\Http\Controllers\Dosen\NilaiController;
// Route::get('/', function () {
//     return view('welcome');
// });

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });

Route::get('/', fn() => redirect('/login'));

// Guest routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// Authenticated routes
Route::middleware(['auth'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Admin routes
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::resource('mahasiswa', AdminMahasiswaController::class);
        Route::resource('dosen', AdminDosenController::class);
        Route::resource('matakuliah', AdminMataKuliahController::class);
        Route::resource('pengumuman', AdminPengumumanController::class);
        Route::resource('periode-akademik', PeriodeAkademikController::class);
        Route::get('/ruangan', [AdminController::class, 'manageRuangan'])->name('ruangan.index');
        
        // Jadwal routes
        Route::get('/jadwal/create', [JadwalController::class, 'create'])->name('jadwal.create');
        Route::post('/jadwal', [JadwalController::class, 'store'])->name('jadwal.store');
    });

    // Dosen routes
    Route::middleware(['role:dosen'])->prefix('dosen')->name('dosen.')->group(function () {
        Route::get('/dashboard', [DosenDashboardController::class, 'index'])->name('dashboard');
        Route::get('/jadwal', [DosenController::class, 'jadwalMengajar'])->name('jadwal');
        
        // Nilai routes
        Route::get('/nilai', [NilaiController::class, 'index'])->name('nilai.index');
        Route::get('/nilai/{jadwal}', [NilaiController::class, 'show'])->name('nilai.show');
        Route::post('/nilai/{jadwal}', [NilaiController::class, 'store'])->name('nilai.store');
        
        // Presensi routes
        Route::get('/presensi', [PresensiController::class, 'index'])->name('presensi.index');
        Route::get('/presensi/{jadwal}', [PresensiController::class, 'show'])->name('presensi.show');
        Route::post('/presensi/{jadwal}', [PresensiController::class, 'store'])->name('presensi.store');
        Route::get('/presensi/{jadwal}/rekap', [PresensiController::class, 'rekap'])->name('presensi.rekap');
    });

    // Mahasiswa routes
    Route::middleware(['auth', 'role:mahasiswa'])->prefix('mahasiswa')->name('mahasiswa.')->group(function () {
        Route::get('/dashboard', [MahasiswaDashboardController::class, 'index'])->name('dashboard');
        
        // KRS Routes
        Route::get('/krs', [App\Http\Controllers\Mahasiswa\KRSController::class, 'form'])->name('krs.form');
        Route::post('/krs', [App\Http\Controllers\Mahasiswa\KRSController::class, 'store'])->name('krs.store');
        
        // Nilai Routes
        Route::get('/nilai', [MahasiswaController::class, 'lihatNilai'])->name('nilai');
        Route::get('/transkrip', [MahasiswaController::class, 'transkrip'])->name('transkrip');
    });
});

require __DIR__.'/auth.php';