<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Dosen;
use App\Models\Jadwal;
use App\Models\KRS;
use App\Models\Tugas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DosenDashboardController extends Controller
{
    public function index()
    {
        $dosen = Auth::user()->dosen;
        $today = Carbon::now();

        $data = [
            'dosen' => $dosen,
            'total_kelas' => Jadwal::where('dosen_id', $dosen->id)
                ->where('semester', getCurrentSemester())
                ->count(),
            'total_mahasiswa' => KRS::whereHas('jadwal', function($query) use ($dosen) {
                $query->where('dosen_id', $dosen->id)
                    ->where('semester', getCurrentSemester());
            })->count(),
            'pending_krs' => KRS::whereHas('jadwal', function($query) use ($dosen) {
                $query->where('dosen_id', $dosen->id);
            })->where('status', 'pending')->count(),
            'jadwal_hari_ini' => Jadwal::where('dosen_id', $dosen->id)
                ->where('hari', strtolower($today->format('l')))
                ->orderBy('jam_mulai')
                ->get(),
            'tugas_pending' => Tugas::whereHas('jadwal', function($query) use ($dosen) {
                $query->where('dosen_id', $dosen->id);
            })->whereHas('pengumpulan', function($query) {
                $query->where('status', 'submitted');
            })->where('status', 'belum_dinilai')
            ->get(),
        ];

        return view('dosen.dashboard', $data);
    }
} 