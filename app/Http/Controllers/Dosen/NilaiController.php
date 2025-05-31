<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use App\Models\KRS;
use App\Models\Nilai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class NilaiController extends Controller
{
    public function index()
    {
        $dosen = Auth::user()->dosen;
        $jadwals = Jadwal::with(['matakuliah', 'krs.mahasiswa'])
            ->where('dosen_id', $dosen->id)
            ->where('status', 'aktif')
            ->get();

        return view('dosen.nilai.index', compact('jadwals'));
    }

    public function show($jadwal_id)
    {
        $jadwal = Jadwal::with(['matakuliah', 'krs.mahasiswa', 'krs.nilai'])
            ->where('dosen_id', Auth::user()->dosen->id)
            ->findOrFail($jadwal_id);

        $mahasiswas = KRS::with(['mahasiswa', 'nilai'])
            ->where('jadwal_id', $jadwal_id)
            ->get();

        return view('dosen.nilai.show', compact('jadwal', 'mahasiswas'));
    }

    public function store(Request $request, $jadwal_id)
    {
        $dosen = Auth::user()->dosen;
        $jadwal = Jadwal::where('id', $jadwal_id)->where('dosen_id', $dosen->id)->first();

        if (!$jadwal) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access to this class'
            ], 403);
        }

        try {
            $request->validate([
                'nilai' => 'required|array',
                'nilai.*.uts' => 'required|numeric|min:0|max:100',
                'nilai.*.uas' => 'required|numeric|min:0|max:100',
                'nilai.*.tugas' => 'required|numeric|min:0|max:100',
                'nilai.*.krs_id' => 'required|exists:krs,id'
            ]);

            DB::beginTransaction();

            foreach ($request->nilai as $krs_id => $nilai_data) {
                $krs = KRS::findOrFail($nilai_data['krs_id']);
                
                // Calculate final grade
                $nilai_akhir = ($nilai_data['uts'] * 0.3) + ($nilai_data['uas'] * 0.4) + ($nilai_data['tugas'] * 0.3);
                $nilai_result = Nilai::hitungNilaiHuruf($nilai_akhir);

                Nilai::updateOrCreate(
                    ['krs_id' => $krs->id],
                    [
                        'nilai_uts' => $nilai_data['uts'],
                        'nilai_uas' => $nilai_data['uas'],
                        'nilai_tugas' => $nilai_data['tugas'],
                        'nilai_angka' => $nilai_akhir,
                        'nilai_huruf' => $nilai_result['huruf'],
                        'nilai_indeks' => $nilai_result['indeks']
                    ]
                );
            }

            DB::commit();
            
            Log::info('Grades stored successfully', [
                'jadwal_id' => $jadwal_id,
                'dosen_id' => $dosen->id
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Nilai berhasil disimpan'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error storing grades', [
                'message' => $e->getMessage(),
                'jadwal_id' => $jadwal_id,
                'dosen_id' => $dosen->id
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan nilai'
            ], 500);
        }
    }
} 