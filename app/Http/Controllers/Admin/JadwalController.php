<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dosen;
use App\Models\Jadwal;
use App\Models\Matakuliah;
use App\Models\PeriodeAkademik;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class JadwalController extends Controller
{
    public function create()
    {
        $matakuliahs = Matakuliah::where('status', 'aktif')->get();
        $dosens = Dosen::where('status', 'aktif')->get();
        $periode = PeriodeAkademik::where('is_active', true)->first();
        
        return view('admin.jadwal.create', compact('matakuliahs', 'dosens', 'periode'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'matakuliah_id' => 'required|exists:matakuliahs,id',
            'dosen_id' => 'required|exists:dosens,id',
            'hari' => 'required|in:senin,selasa,rabu,kamis,jumat,sabtu',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
            'ruangan' => 'required|string',
            'kapasitas' => 'required|integer|min:1',
        ]);

        $periode = PeriodeAkademik::where('is_active', true)->firstOrFail();

        try {
            // Check for schedule conflicts
            $existingJadwal = Jadwal::where('hari', $request->hari)
                ->where('semester', $periode->semester)
                ->where('tahun_akademik', $periode->tahun_akademik)
                ->where(function($query) use ($request) {
                    $query->where(function($q) use ($request) {
                        $q->where('jam_mulai', '<=', $request->jam_mulai)
                          ->where('jam_selesai', '>', $request->jam_mulai);
                    })->orWhere(function($q) use ($request) {
                        $q->where('jam_mulai', '<', $request->jam_selesai)
                          ->where('jam_selesai', '>=', $request->jam_selesai);
                    });
                })
                ->where(function($query) use ($request) {
                    $query->where('ruangan', $request->ruangan)
                          ->orWhere('dosen_id', $request->dosen_id);
                })
                ->first();

            if ($existingJadwal) {
                return back()->withInput()->with('error', 'Terjadi konflik jadwal dengan jadwal yang sudah ada.');
            }

            $jadwal = Jadwal::create([
                'matakuliah_id' => $request->matakuliah_id,
                'dosen_id' => $request->dosen_id,
                'hari' => $request->hari,
                'jam_mulai' => $request->jam_mulai,
                'jam_selesai' => $request->jam_selesai,
                'ruangan' => $request->ruangan,
                'kapasitas' => $request->kapasitas,
                'semester' => $periode->semester,
                'tahun_akademik' => $periode->tahun_akademik,
                'status' => 'aktif',
                'terisi' => 0,
            ]);

            return redirect()->route('admin.jadwal.create')
                ->with('success', 'Jadwal kuliah berhasil ditambahkan.');

        } catch (\Exception $e) {
            Log::error('Error creating jadwal: ' . $e->getMessage());
            Log::error($e->getTraceAsString());
            
            return back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat menyimpan jadwal: ' . $e->getMessage());
        }
    }
} 