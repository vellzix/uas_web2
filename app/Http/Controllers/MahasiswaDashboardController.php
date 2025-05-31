<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Krs;
use App\Models\Nilai;
use App\Models\Jadwal;
use App\Models\Presensi;
use App\Models\Mahasiswa;
use App\Models\Pengumuman;
use App\Models\TugasMahasiswa;
use App\Models\PeriodeAkademik;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MahasiswaDashboardController extends Controller
{
    public function index()
    {
        $mahasiswa = auth()->user()->mahasiswa;
        
        // Cek periode KRS
        $periode = PeriodeAkademik::where('is_active', true)->first();
        $is_periode_krs = $periode ? $periode->isKRSPeriodActive() : false;

        // Ambil pengumuman yang aktif
        $pengumuman = Pengumuman::where('status', 'published')
            ->where('tanggal_mulai', '<=', now())
            ->where('tanggal_selesai', '>=', now())
            ->where(function($query) {
                $query->where('tipe', 'umum')
                      ->orWhere('tipe', 'mahasiswa');
            })
            ->latest()
            ->take(5)
            ->get();

        // Hitung IPK
        try {
            $nilai = DB::table('krs')
                ->join('nilais', 'krs.id', '=', 'nilais.krs_id')
                ->join('jadwal', 'krs.jadwal_id', '=', 'jadwal.id')
                ->join('matakuliahs', 'jadwal.matakuliah_id', '=', 'matakuliahs.id')
                ->where('krs.mahasiswa_id', $mahasiswa->id)
                ->select(
                    DB::raw('SUM(nilais.nilai_indeks * matakuliahs.sks) as total_nilai'),
                    DB::raw('SUM(matakuliahs.sks) as total_sks')
                )
                ->first();

            $ipk = $nilai && $nilai->total_sks > 0 ? round($nilai->total_nilai / $nilai->total_sks, 2) : 0;
            $total_sks = $nilai ? ($nilai->total_sks ?? 0) : 0;
        } catch (\Exception $e) {
            // Jika terjadi error (misal tabel belum ada), set nilai default
            $ipk = 0;
            $total_sks = 0;
        }

        // Hitung total SKS yang sudah diambil (termasuk yang belum ada nilai)
        $total_sks_diambil = DB::table('krs')
            ->join('jadwal', 'krs.jadwal_id', '=', 'jadwal.id')
            ->join('matakuliahs', 'jadwal.matakuliah_id', '=', 'matakuliahs.id')
            ->where('krs.mahasiswa_id', $mahasiswa->id)
            ->sum('matakuliahs.sks');

        // Hitung persentase kehadiran semester ini
        $semester_aktif = $periode ? $periode->semester : null;
        try {
            // Get all matakuliah_ids from student's KRS
            $matakuliah_ids = DB::table('krs')
                ->join('jadwal', 'krs.jadwal_id', '=', 'jadwal.id')
                ->where('krs.mahasiswa_id', $mahasiswa->id)
                ->where('jadwal.semester', $semester_aktif)
                ->pluck('jadwal.matakuliah_id');

            // Get presensi records for these matakuliah
            $presensi = Presensi::where('mahasiswa_id', $mahasiswa->id)
                ->whereIn('matakuliah_id', $matakuliah_ids)
                ->get();

            $total_pertemuan = $presensi->count();
            $hadir = $presensi->where('status', 'hadir')->count();
            $kehadiran_percentage = $total_pertemuan > 0 ? round(($hadir / $total_pertemuan) * 100) : 0;
        } catch (\Exception $e) {
            \Log::error('Error calculating attendance:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            $kehadiran_percentage = 0;
        }

        // Ambil jadwal hari ini
        try {
            $jadwal_hari_ini = Jadwal::whereHas('krs', function($query) use ($mahasiswa) {
                $query->where('mahasiswa_id', $mahasiswa->id);
            })
            ->where('hari', strtolower(Carbon::now()->locale('id')->dayName))
            ->orderBy('jam_mulai')
            ->get();
        } catch (\Exception $e) {
            $jadwal_hari_ini = collect();
        }

        // Ambil tugas yang belum dikumpulkan
        try {
            $tugas = TugasMahasiswa::with(['tugas.jadwal.matakuliah'])
                ->where('mahasiswa_id', $mahasiswa->id)
                ->where('status', 'belum')
                ->whereHas('tugas', function($query) {
                    $query->where('deadline', '>=', now());
                })
                ->get();
        } catch (\Exception $e) {
            $tugas = collect();
        }

        // Status KRS
        $krs_status = Krs::where('mahasiswa_id', $mahasiswa->id)
            ->where('periode_akademik_id', $periode ? $periode->id : null)
            ->where('status', 'disetujui')
            ->exists();

        return view('mahasiswa.dashboard', compact(
            'mahasiswa',
            'is_periode_krs',
            'pengumuman',
            'ipk',
            'total_sks',
            'total_sks_diambil',
            'kehadiran_percentage',
            'jadwal_hari_ini',
            'tugas',
            'krs_status'
        ));
    }
} 