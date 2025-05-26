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
        // Mahasiswa 1
        $user1 = User::create([
            'name' => 'Andi Pratama',
            'email' => 'andi.pratama@mhs.siakad.ac.id',
            'password' => Hash::make('mahasiswa123'),
            'role' => 'mahasiswa',
            'email_verified_at' => now(),
        ]);

        Mahasiswa::create([
            'user_id' => $user1->id,
            'nim' => '2024010001',
            'nama' => $user1->name,
            'angkatan' => '2024',
            'program_studi' => 'Teknik Informatika',
            'status' => 'Aktif',
        ]);

        // Mahasiswa 2
        $user2 = User::create([
            'name' => 'Dewi Lestari',
            'email' => 'dewi.lestari@mhs.siakad.ac.id',
            'password' => Hash::make('mahasiswa123'),
            'role' => 'mahasiswa',
            'email_verified_at' => now(),
        ]);

        Mahasiswa::create([
            'user_id' => $user2->id,
            'nim' => '2024010002',
            'nama' => $user2->name,
            'angkatan' => '2024',
            'program_studi' => 'Sistem Informasi',
            'status' => 'Aktif',
        ]);

        // Mahasiswa 3
        $user3 = User::create([
            'name' => 'Muhammad Rizki',
            'email' => 'muhammad.rizki@mhs.siakad.ac.id',
            'password' => Hash::make('mahasiswa123'),
            'role' => 'mahasiswa',
            'email_verified_at' => now(),
        ]);

        Mahasiswa::create([
            'user_id' => $user3->id,
            'nim' => '2024010003',
            'nama' => $user3->name,
            'angkatan' => '2024',
            'program_studi' => 'Teknik Informatika',
            'status' => 'Aktif',
        ]);

        // Mahasiswa 4
        $user4 = User::create([
            'name' => 'Sarah Putri',
            'email' => 'sarah.putri@mhs.siakad.ac.id',
            'password' => Hash::make('mahasiswa123'),
            'role' => 'mahasiswa',
            'email_verified_at' => now(),
        ]);

        Mahasiswa::create([
            'user_id' => $user4->id,
            'nim' => '2024010004',
            'nama' => $user4->name,
            'angkatan' => '2024',
            'program_studi' => 'Sistem Informasi',
            'status' => 'Aktif',
        ]);
    }
}