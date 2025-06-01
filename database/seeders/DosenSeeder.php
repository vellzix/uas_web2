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
        $dosens = [
            [
                'name' => 'Dr. Budi Santoso, M.Kom.',
                'email' => 'budi.santoso@siakad.ac.id',
                'nip' => '198505152010121002',
                'no_hp' => '081234567801',
                'tempat_lahir' => 'Jakarta',
                'tanggal_lahir' => '1985-05-15',
                'jenis_kelamin' => 'L',
                'agama' => 'Islam',
                'prodi_id' => 1,
                'matakuliah_id' => 1,
            ],
            [
                'name' => 'Dr. Siti Aminah, M.T.',
                'email' => 'siti.aminah@siakad.ac.id',
                'nip' => '197708232005012001',
                'no_hp' => '081234567802',
                'tempat_lahir' => 'Bandung',
                'tanggal_lahir' => '1977-08-23',
                'jenis_kelamin' => 'P',
                'agama' => 'Islam',
                'prodi_id' => 2,
                'matakuliah_id' => 2,
            ],
            [
                'name' => 'Prof. Ahmad Wijaya, Ph.D.',
                'email' => 'ahmad.wijaya@siakad.ac.id',
                'nip' => '196505051990031002',
                'no_hp' => '081234567803',
                'tempat_lahir' => 'Surabaya',
                'tanggal_lahir' => '1965-05-05',
                'jenis_kelamin' => 'L',
                'agama' => 'Islam',
                'prodi_id' => 1,
                'matakuliah_id' => 3,
            ],
            [
                'name' => 'Dr. Rina Kartika, M.Si.',
                'email' => 'rina.kartika@siakad.ac.id',
                'nip' => '198012102008012003',
                'no_hp' => '081234567804',
                'tempat_lahir' => 'Yogyakarta',
                'tanggal_lahir' => '1980-12-10',
                'jenis_kelamin' => 'P',
                'agama' => 'Kristen',
                'prodi_id' => 2,
                'matakuliah_id' => 4,
            ],
            [
                'name' => 'Dr. Hendra Pratama, M.Kom.',
                'email' => 'hendra.pratama@siakad.ac.id',
                'nip' => '197503201999031001',
                'no_hp' => '081234567805',
                'tempat_lahir' => 'Medan',
                'tanggal_lahir' => '1975-03-20',
                'jenis_kelamin' => 'L',
                'agama' => 'Islam',
                'prodi_id' => 1,
                'matakuliah_id' => 5,
            ],
            [
                'name' => 'Dr. Maya Sari, M.T.',
                'email' => 'maya.sari@siakad.ac.id',
                'nip' => '198207152010012004',
                'no_hp' => '081234567806',
                'tempat_lahir' => 'Palembang',
                'tanggal_lahir' => '1982-07-15',
                'jenis_kelamin' => 'P',
                'agama' => 'Islam',
                'prodi_id' => 2,
                'matakuliah_id' => 6,
            ],
            [
                'name' => 'Dr. Agus Setiawan, M.Kom.',
                'email' => 'agus.setiawan@siakad.ac.id',
                'nip' => '197809182003121003',
                'no_hp' => '081234567807',
                'tempat_lahir' => 'Semarang',
                'tanggal_lahir' => '1978-09-18',
                'jenis_kelamin' => 'L',
                'agama' => 'Katolik',
                'prodi_id' => 1,
                'matakuliah_id' => 7,
            ],
            [
                'name' => 'Dr. Dewi Lestari, M.Si.',
                'email' => 'dewi.lestari@siakad.ac.id',
                'nip' => '198411252012012005',
                'no_hp' => '081234567808',
                'tempat_lahir' => 'Makassar',
                'tanggal_lahir' => '1984-11-25',
                'jenis_kelamin' => 'P',
                'agama' => 'Islam',
                'prodi_id' => 2,
                'matakuliah_id' => 8,
            ],
            [
                'name' => 'Dr. Rudi Hermawan, M.T.',
                'email' => 'rudi.hermawan@siakad.ac.id',
                'nip' => '197601302001121004',
                'no_hp' => '081234567809',
                'tempat_lahir' => 'Denpasar',
                'tanggal_lahir' => '1976-01-30',
                'jenis_kelamin' => 'L',
                'agama' => 'Hindu',
                'prodi_id' => 1,
                'matakuliah_id' => 9,
            ],
            [
                'name' => 'Dr. Fitri Handayani, M.Kom.',
                'email' => 'fitri.handayani@siakad.ac.id',
                'nip' => '198906142015012006',
                'no_hp' => '081234567810',
                'tempat_lahir' => 'Padang',
                'tanggal_lahir' => '1989-06-14',
                'jenis_kelamin' => 'P',
                'agama' => 'Islam',
                'prodi_id' => 2,
                'matakuliah_id' => 10,
            ],
        ];

        foreach ($dosens as $dosenData) {
            $user = User::create([
                'name' => $dosenData['name'],
                'email' => $dosenData['email'],
                'password' => Hash::make('password123'),
                'role' => 'dosen',
                'email_verified_at' => now(),
            ]);

            Dosen::create([
                'user_id' => $user->id,
                'nip' => $dosenData['nip'],
                'nama' => $dosenData['name'],
                'no_hp' => $dosenData['no_hp'],
                'tempat_lahir' => $dosenData['tempat_lahir'],
                'tanggal_lahir' => $dosenData['tanggal_lahir'],
                'jenis_kelamin' => $dosenData['jenis_kelamin'],
                'agama' => $dosenData['agama'],
                'prodi_id' => $dosenData['prodi_id'],
                'matakuliah_id' => $dosenData['matakuliah_id'],
                'status' => 'Aktif',
            ]);
        }
    }
}
