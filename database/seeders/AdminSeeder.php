<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Admin Utama',
            'email' => 'admin@siakad.ac.id',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        // Create additional admin users
        User::create([
            'name' => 'Admin Akademik',
            'email' => 'akademik@siakad.ac.id',
            'password' => Hash::make('akademik123'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);
    }
}