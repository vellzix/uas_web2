<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Dosen;
use Illuminate\Support\Facades\Hash;

class DosenSeeder extends Seeder
{
    public function run(): void
    {
        // Dosen 1
        $user1 = User::create([
            'name' => 'Dr. Budi Santoso, M.Kom.',
            'email' => 'budi.santoso@siakad.ac.id',
            'password' => Hash::make('dosen123'),
            'role' => 'dosen',
            'email_verified_at' => now(),
        ]);

        Dosen::create([
            'user_id' => $user1->id,
            'nip' => '198505152010121002',
            'nama' => $user1->name,
            'bidang_keahlian' => 'Artificial Intelligence',
            'gelar_akademik' => 'Dr.',
        ]);

        // Dosen 2
        $user2 = User::create([
            'name' => 'Dr. Siti Aminah, M.T.',
            'email' => 'siti.aminah@siakad.ac.id',
            'password' => Hash::make('dosen123'),
            'role' => 'dosen',
            'email_verified_at' => now(),
        ]);

        Dosen::create([
            'user_id' => $user2->id,
            'nip' => '197708232005012001',
            'nama' => $user2->name,
            'bidang_keahlian' => 'Software Engineering',
            'gelar_akademik' => 'Dr.',
        ]);

        // Dosen 3
        $user3 = User::create([
            'name' => 'Prof. Ahmad Wijaya, Ph.D.',
            'email' => 'ahmad.wijaya@siakad.ac.id',
            'password' => Hash::make('dosen123'),
            'role' => 'dosen',
            'email_verified_at' => now(),
        ]);

        Dosen::create([
            'user_id' => $user3->id,
            'nip' => '196505051990031002',
            'nama' => $user3->name,
            'bidang_keahlian' => 'Database Systems',
            'gelar_akademik' => 'Prof.',
        ]);
    }
}