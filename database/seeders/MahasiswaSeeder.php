<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Mahasiswa;
use Illuminate\Support\Facades\Hash;

class MahasiswaSeeder extends Seeder
{
    public function run(): void
    {
        $mahasiswas = [
            [
                'name' => 'Andi Pratama',
                'email' => 'andi.pratamas@mhs.siakad.ac.id',
                'nim' => '2024010011',
                'no_hp' => '081234567901',
                'tempat_lahir' => 'Jakarta',
                'tanggal_lahir' => '2003-01-15',
                'jenis_kelamin' => 'L',
                'agama' => 'Islam',
                'prodi_id' => 1,
                'angkatan' => '2024',
            ],
            [
                'name' => 'Dewi Lestari',
                'email' => 'dewi.lestari@mhs.siakad.ac.id',
                'nim' => '2024010002',
                'no_hp' => '081234567902',
                'tempat_lahir' => 'Bandung',
                'tanggal_lahir' => '2003-05-22',
                'jenis_kelamin' => 'P',
                'agama' => 'Islam',
                'prodi_id' => 2,
                'angkatan' => '2024',
            ],
            [
                'name' => 'Muhammad Rizki',
                'email' => 'muhammad.rizki@mhs.siakad.ac.id',
                'nim' => '2024010003',
                'no_hp' => '081234567903',
                'tempat_lahir' => 'Surabaya',
                'tanggal_lahir' => '2003-08-10',
                'jenis_kelamin' => 'L',
                'agama' => 'Islam',
                'prodi_id' => 1,
                'angkatan' => '2024',
            ],
            [
                'name' => 'Sarah Putri',
                'email' => 'sarah.putri@mhs.siakad.ac.id',
                'nim' => '2024010004',
                'no_hp' => '081234567904',
                'tempat_lahir' => 'Yogyakarta',
                'tanggal_lahir' => '2003-12-03',
                'jenis_kelamin' => 'P',
                'agama' => 'Kristen',
                'prodi_id' => 2,
                'angkatan' => '2024',
            ],
            [
                'name' => 'Bayu Setiawan',
                'email' => 'bayu.setiawan@mhs.siakad.ac.id',
                'nim' => '2024010005',
                'no_hp' => '081234567905',
                'tempat_lahir' => 'Medan',
                'tanggal_lahir' => '2003-03-18',
                'jenis_kelamin' => 'L',
                'agama' => 'Islam',
                'prodi_id' => 1,
                'angkatan' => '2024',
            ],
            [
                'name' => 'Sari Indah',
                'email' => 'sari.indah@mhs.siakad.ac.id',
                'nim' => '2024010006',
                'no_hp' => '081234567906',
                'tempat_lahir' => 'Palembang',
                'tanggal_lahir' => '2003-07-25',
                'jenis_kelamin' => 'P',
                'agama' => 'Islam',
                'prodi_id' => 2,
                'angkatan' => '2024',
            ],
            [
                'name' => 'Dimas Aditya',
                'email' => 'dimas.aditya@mhs.siakad.ac.id',
                'nim' => '2024010007',
                'no_hp' => '081234567907',
                'tempat_lahir' => 'Semarang',
                'tanggal_lahir' => '2003-11-12',
                'jenis_kelamin' => 'L',
                'agama' => 'Katolik',
                'prodi_id' => 1,
                'angkatan' => '2024',
            ],
            [
                'name' => 'Putri Maharani',
                'email' => 'putri.maharani@mhs.siakad.ac.id',
                'nim' => '2024010008',
                'no_hp' => '081234567908',
                'tempat_lahir' => 'Makassar',
                'tanggal_lahir' => '2003-04-08',
                'jenis_kelamin' => 'P',
                'agama' => 'Islam',
                'prodi_id' => 2,
                'angkatan' => '2024',
            ],
            [
                'name' => 'Arief Wicaksono',
                'email' => 'arief.wicaksono@mhs.siakad.ac.id',
                'nim' => '2024010009',
                'no_hp' => '081234567909',
                'tempat_lahir' => 'Denpasar',
                'tanggal_lahir' => '2003-09-30',
                'jenis_kelamin' => 'L',
                'agama' => 'Hindu',
                'prodi_id' => 1,
                'angkatan' => '2024',
            ],
            [
                'name' => 'Nurul Fadilah',
                'email' => 'nurul.fadilah@mhs.siakad.ac.id',
                'nim' => '2024010010',
                'no_hp' => '081234567910',
                'tempat_lahir' => 'Padang',
                'tanggal_lahir' => '2003-06-14',
                'jenis_kelamin' => 'P',
                'agama' => 'Islam',
                'prodi_id' => 2,
                'angkatan' => '2024',
            ],
        ];

        foreach ($mahasiswas as $mahasiswaData) {
            $user = User::create([
                'name' => $mahasiswaData['name'],
                'email' => $mahasiswaData['email'],
                'password' => Hash::make('password123'),
                'role' => 'mahasiswa',
                'email_verified_at' => now(),
            ]);

            Mahasiswa::create([
                'user_id' => $user->id,
                'nim' => $mahasiswaData['nim'],
                'nama' => $mahasiswaData['name'],
                'no_hp' => $mahasiswaData['no_hp'],
                'tempat_lahir' => $mahasiswaData['tempat_lahir'],
                'tanggal_lahir' => $mahasiswaData['tanggal_lahir'],
                'jenis_kelamin' => $mahasiswaData['jenis_kelamin'],
                'agama' => $mahasiswaData['agama'],
                'prodi_id' => $mahasiswaData['prodi_id'],
                'angkatan' => $mahasiswaData['angkatan'],
                'status' => 'Aktif',
            ]);
        }
    }
}
