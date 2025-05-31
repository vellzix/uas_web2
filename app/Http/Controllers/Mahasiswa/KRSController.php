<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use App\Models\KRS;
use App\Models\PeriodeAkademik;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class KRSController extends Controller
{
    public function form()
    {
        // Get active periode
        $periode = PeriodeAkademik::where('is_active', true)->firstOrFail();
        
        // Check if KRS period is active
        if (!$periode->isKRSPeriodActive()) {
            return redirect()->route('mahasiswa.dashboard')
                ->with('error', 'Periode pengisian KRS belum dibuka atau sudah ditutup.');
        }
        
        $mahasiswa = Auth::user()->mahasiswa;
        
        // Get student's academic info
        $ipk = $mahasiswa->calculateIPK();
        $total_sks = $mahasiswa->calculateTotalSKS();
        $max_sks = $this->getMaxSKS($ipk);
        
        // Get available jadwal with eager loading
        $jadwals = Jadwal::with(['matakuliah', 'dosen'])
            ->where('semester', $periode->semester)
            ->where('tahun_akademik', $periode->tahun_akademik)
            ->where('status', 'aktif')
            ->get();
            
        // Get already selected jadwals
        $selected_jadwals = KRS::where('mahasiswa_id', $mahasiswa->id)
            ->where('periode_akademik_id', $periode->id)
            ->pluck('jadwal_id')
            ->toArray();
        
        return view('mahasiswa.krs.form', compact(
            'periode', 
            'ipk', 
            'total_sks', 
            'max_sks', 
            'jadwals',
            'selected_jadwals'
        ));
    }
    
    public function store(Request $request)
    {
        $periode = PeriodeAkademik::where('is_active', true)->firstOrFail();
        
        if (!$periode->isKRSPeriodActive()) {
            return redirect()->route('mahasiswa.dashboard')
                ->with('error', 'Periode pengisian KRS belum dibuka atau sudah ditutup.');
        }
        
        $mahasiswa = Auth::user()->mahasiswa;
        $jadwal_ids = $request->input('jadwal_ids', []);
        
        // Validate total SKS
        $total_sks = Jadwal::whereIn('jadwal.id', $jadwal_ids)
            ->join('matakuliahs', 'jadwal.matakuliah_id', '=', 'matakuliahs.id')
            ->sum('matakuliahs.sks');
            
        $max_sks = $this->getMaxSKS($mahasiswa->calculateIPK());
        
        if ($total_sks > $max_sks) {
            return redirect()->back()
                ->with('error', "Total SKS ($total_sks) melebihi batas maksimal ($max_sks).");
        }
        
        // Validate jadwal conflicts
        $jadwals = Jadwal::whereIn('jadwal.id', $jadwal_ids)->get();
        foreach ($jadwals as $jadwal1) {
            foreach ($jadwals as $jadwal2) {
                if ($jadwal1->id !== $jadwal2->id && $this->isJadwalConflict($jadwal1, $jadwal2)) {
                    return redirect()->back()
                        ->with('error', "Terdapat konflik jadwal antara {$jadwal1->matakuliah->nama} dan {$jadwal2->matakuliah->nama}.");
                }
            }
        }
        
        // Validate kapasitas
        foreach ($jadwals as $jadwal) {
            if ($jadwal->terisi >= $jadwal->kapasitas) {
                return redirect()->back()
                    ->with('error', "Kapasitas untuk mata kuliah {$jadwal->matakuliah->nama} sudah penuh.");
            }
        }
        
        try {
            DB::beginTransaction();
            
            // Delete existing KRS entries for this period
            KRS::where('mahasiswa_id', $mahasiswa->id)
                ->where('periode_akademik_id', $periode->id)
                ->delete();
            
            // Create new KRS entries and update terisi count
            foreach ($jadwal_ids as $jadwal_id) {
                KRS::create([
                    'mahasiswa_id' => $mahasiswa->id,
                    'jadwal_id' => $jadwal_id,
                    'periode_akademik_id' => $periode->id,
                    'status' => 'disetujui'
                ]);
                
                // Increment terisi count
                Jadwal::where('id', $jadwal_id)->increment('terisi');
            }
            
            DB::commit();
            
            return redirect()->route('mahasiswa.dashboard')
                ->with('success', 'KRS berhasil disimpan.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menyimpan KRS. Silakan coba lagi.');
        }
    }
    
    private function getMaxSKS($ipk)
    {
        if ($ipk >= 3.0) return 24;
        if ($ipk >= 2.5) return 21;
        if ($ipk >= 2.0) return 18;
        return 15;
    }
    
    private function isJadwalConflict($jadwal1, $jadwal2)
    {
        if ($jadwal1->hari !== $jadwal2->hari) {
            return false;
        }
        
        $start1 = strtotime($jadwal1->jam_mulai);
        $end1 = strtotime($jadwal1->jam_selesai);
        $start2 = strtotime($jadwal2->jam_mulai);
        $end2 = strtotime($jadwal2->jam_selesai);
        
        return ($start1 < $end2 && $end1 > $start2);
    }
} 