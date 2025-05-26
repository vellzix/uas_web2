<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PeriodeAkademik;
use Carbon\Carbon;

class PeriodeAkademikSeeder extends Seeder
{
    public function run(): void
    {
        // Nonaktifkan semua periode yang ada
        PeriodeAkademik::query()->update(['is_active' => false]);

        // Buat periode aktif untuk semester saat ini
        $now = Carbon::now();
        $semester = $now->month >= 2 && $now->month <= 7 ? 'genap' : 'ganjil';
        $tahun = $now->year;
        $tahunAkademik = $semester === 'genap' ? 
            ($tahun-1).'/'.$tahun : 
            $tahun.'/'.($tahun+1);

        PeriodeAkademik::create([
            'nama' => 'Semester ' . ucfirst($semester) . ' ' . $tahunAkademik,
            'tahun_akademik' => $tahunAkademik,
            'semester' => $semester,
            'tanggal_mulai' => $now->copy()->startOfMonth(),
            'tanggal_selesai' => $now->copy()->addMonths(6)->endOfMonth(),
            'krs_mulai' => $now->copy()->subDays(7),
            'krs_selesai' => $now->copy()->addDays(7),
            'is_active' => true
        ]);
    }
} 