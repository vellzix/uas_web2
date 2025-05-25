<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DummyUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
    User::create([
        'name' => 'Admin',
        'email' => 'admin@kampus.ac.id',
        'password' => bcrypt('password'),
        'role' => 'admin',
    ]);
    }
}
