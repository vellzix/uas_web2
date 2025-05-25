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

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::middleware('role:admin')->prefix('admin')->group(function () {
        Route::resource('mahasiswa', Admin\MahasiswaController::class);
        Route::resource('dosen', Admin\DosenController::class);
        Route::resource('matakuliah', Admin\MataKuliahController::class);
        Route::resource('pengumuman', Admin\PengumumanController::class);
    });

    Route::middleware('role:dosen')->prefix('dosen')->group(function () {
        Route::get('jadwal', [Dosen\JadwalController::class, 'index']);
        Route::post('nilai', [Dosen\NilaiController::class, 'store']);
    });

    Route::middleware('role:mahasiswa')->prefix('mahasiswa')->group(function () {
        Route::get('jadwal', [Mahasiswa\JadwalController::class, 'index']);
        Route::get('transkrip', [Mahasiswa\NilaiController::class, 'index']);
        Route::post('daftar-mk', [Mahasiswa\MataKuliahController::class, 'store']);
    });
});


Route::get('/', fn() => redirect('/login'));
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth');

// Dashboard redirect
Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('auth');

// Admin
Route::prefix('admin')->middleware(['auth'])->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index']);
    Route::get('/mahasiswa', [AdminController::class, 'manageMahasiswa']);
    Route::get('/dosen', [AdminController::class, 'manageDosen']);
    Route::get('/matakuliah', [AdminController::class, 'manageMatakuliah']);
    Route::get('/ruangan', [AdminController::class, 'manageRuangan']);
    Route::get('/pengumuman', [AdminController::class, 'managePengumuman']);
});

// Dosen
Route::prefix('dosen')->middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DosenController::class, 'index']);
    Route::get('/jadwal', [DosenController::class, 'jadwalMengajar']);
    Route::get('/input-nilai/{id}', [DosenController::class, 'inputNilai']);
    Route::post('/simpan-nilai', [DosenController::class, 'simpanNilai']);
});

// Mahasiswa
Route::prefix('mahasiswa')->middleware(['auth'])->group(function () {
    Route::get('/dashboard', [MahasiswaController::class, 'index']);
    Route::get('/krs', [MahasiswaController::class, 'krsForm']);
    Route::post('/krs', [MahasiswaController::class, 'submitKrs']);
    Route::get('/nilai', [MahasiswaController::class, 'lihatNilai']);
    Route::get('/transkrip', [MahasiswaController::class, 'transkrip']);
});

// Authentication routes
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Admin routes
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        // Add more admin routes here
    });

    // Dosen routes
    Route::middleware(['role:dosen'])->prefix('dosen')->name('dosen.')->group(function () {
        Route::get('/dashboard', [DosenDashboardController::class, 'index'])->name('dashboard');
        // Add more dosen routes here
    });

    // Mahasiswa routes
    Route::middleware(['role:mahasiswa'])->prefix('mahasiswa')->name('mahasiswa.')->group(function () {
        Route::get('/dashboard', [MahasiswaDashboardController::class, 'index'])->name('dashboard');
        // Add more mahasiswa routes here
    });
});

require __DIR__.'/auth.php';
