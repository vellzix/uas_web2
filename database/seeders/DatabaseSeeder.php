<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Prodi;
use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\Matakuliah;
use App\Models\Jadwal;
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
        $dosen = Dosen::create([
            'user_id' => User::create([
                'name' => 'Dr. John Doe',
                'email' => 'dosen@example.com',
                'password' => Hash::make('password'),
                'role' => 'dosen',
            ])->id,
            'nip' => '198501012010121001',
            'nama' => 'Dr. John Doe',
            'prodi_id' => $prodi->id,
            'bidang' => 'Pemrograman dan Rekayasa Perangkat Lunak',
            'status' => 'aktif',
        ]);

        // Create mahasiswa
        $mahasiswa = Mahasiswa::create([
            'user_id' => User::create([
                'name' => 'Jane Smith',
                'email' => 'mahasiswa@example.com',
                'password' => Hash::make('password'),
                'role' => 'mahasiswa',
            ])->id,
            'nim' => '2024010001',
            'nama' => 'Jane Smith',
            'no_hp' => '081234567890',
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '2000-01-01',
            'jenis_kelamin' => 'P',
            'agama' => 'Islam',
            'prodi_id' => $prodi->id,
            'angkatan' => '2024',
            'status' => 'aktif',
        ]);

        // Create matakuliah
        $matakuliah = Matakuliah::create([
            'kode' => 'IF101',
            'nama' => 'Pemrograman Dasar',
            'sks' => 3,
            'semester' => 1,
            'prodi_id' => $prodi->id,
            'status' => 'aktif',
        ]);

        // Create jadwal
        Jadwal::create([
            'matakuliah_id' => $matakuliah->id,
            'dosen_id' => $dosen->id,
            'hari' => 'Senin',
            'jam_mulai' => '08:00:00',
            'jam_selesai' => '10:30:00',
            'ruangan' => 'Lab 1',
            'semester' => '1',
            'tahun_akademik' => '2023/2024',
            'status' => 'aktif',
            'kapasitas' => 40,
            'terisi' => 0,
        ]);

        $this->call([
            PeriodeAkademikSeeder::class,
        ]);
    }
}
