<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\KRS;
use App\Models\Nilai;
use App\Models\Jadwal;
use App\Models\Presensi;
use App\Models\Mahasiswa;
use App\Models\TugasMahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MahasiswaDashboardController extends Controller
{
    public function index()
    {
        $mahasiswa = Auth::user()->mahasiswa;
        $today = Carbon::now();
        $semester = getCurrentSemester();

        // Hitung IPK
        $nilai = Nilai::where('mahasiswa_id', $mahasiswa->id)
            ->select(
                DB::raw('SUM(nilai * matakuliah.sks) as total_nilai'),
                DB::raw('SUM(matakuliah.sks) as total_sks')
            )
            ->join('krs', 'nilai.krs_id', '=', 'krs.id')
            ->join('jadwal', 'krs.jadwal_id', '=', 'jadwal.id')
            ->join('matakuliah', 'jadwal.matakuliah_id', '=', 'matakuliah.id')
            ->first();

        $ipk = $nilai->total_sks > 0 ? $nilai->total_nilai / $nilai->total_sks : 0;

        // Hitung persentase kehadiran
        $presensi = Presensi::whereHas('jadwal.krs', function($query) use ($mahasiswa, $semester) {
            $query->where('mahasiswa_id', $mahasiswa->id)
                ->where('semester', $semester);
        })->get();

        $total_pertemuan = $presensi->count();
        $hadir = $presensi->where('status', 'hadir')->count();
        $kehadiran_percentage = $total_pertemuan > 0 ? ($hadir / $total_pertemuan) * 100 : 0;

        $data = [
            'mahasiswa' => $mahasiswa,
            'krs_status' => KRS::where('mahasiswa_id', $mahasiswa->id)
                ->where('semester', $semester)
                ->where('status', 'approved')
                ->exists(),
            'ipk' => $ipk,
            'total_sks' => $nilai->total_sks,
            'kehadiran_percentage' => round($kehadiran_percentage, 2),
            'jadwal_hari_ini' => Jadwal::whereHas('krs', function($query) use ($mahasiswa) {
                $query->where('mahasiswa_id', $mahasiswa->id);
            })->where('hari', strtolower($today->format('l')))
                ->orderBy('jam_mulai')
                ->get(),
            'tugas' => TugasMahasiswa::whereHas('tugas.jadwal.krs', function($query) use ($mahasiswa) {
                $query->where('mahasiswa_id', $mahasiswa->id);
            })->where('status', 'belum_dikumpulkan')
                ->whereDate('deadline', '>=', $today)
                ->orderBy('deadline')
                ->get(),
        ];

        return view('mahasiswa.dashboard', $data);
    }
} 