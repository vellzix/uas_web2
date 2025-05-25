<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Prodi;
use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\Matakuliah;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@admin.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Create prodi
        $prodi = Prodi::create([
            'nama' => 'Teknik Informatika',
            'kode' => 'TI',
            'jenjang' => 'S1',
            'fakultas' => 'Fakultas Teknik',
            'status' => 'aktif',
        ]);

        // Create dosen
        $dosenUser = User::create([
            'name' => 'Dosen',
            'email' => 'dosen@dosen.com',
            'password' => Hash::make('password'),
            'role' => 'dosen',
        ]);

        Dosen::create([
            'user_id' => $dosenUser->id,
            'prodi_id' => $prodi->id,
            'nip' => '123456789',
            'nama' => 'Dr. John Doe',
            'bidang' => 'Pemrograman',
            'status' => 'aktif',
        ]);

        // Create mahasiswa
        $mahasiswaUser = User::create([
            'name' => 'Mahasiswa',
            'email' => 'mahasiswa@mahasiswa.com',
            'password' => Hash::make('password'),
            'role' => 'mahasiswa',
        ]);

        Mahasiswa::create([
            'user_id' => $mahasiswaUser->id,
            'prodi_id' => $prodi->id,
            'nim' => '123456',
            'nama' => 'Jane Doe',
            'semester' => 1,
            'angkatan' => 2024,
            'status' => 'aktif',
        ]);

        // Create matakuliah
        Matakuliah::create([
            'prodi_id' => $prodi->id,
            'kode' => 'MK001',
            'nama' => 'Pemrograman Web',
            'sks' => 3,
            'semester' => 1,
            'deskripsi' => 'Mata kuliah pemrograman web dasar',
            'status' => 'aktif',
        ]);
    }
}
