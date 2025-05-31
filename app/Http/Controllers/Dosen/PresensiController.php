<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use App\Models\KRS;
use App\Models\Presensi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PresensiController extends Controller
{
    public function index()
    {
        $dosen = Auth::user()->dosen;
        $jadwals = Jadwal::with(['matakuliah', 'krs.mahasiswa'])
            ->where('dosen_id', $dosen->id)
            ->where('status', 'aktif')
            ->get();

        return view('dosen.presensi.index', compact('jadwals'));
    }

    public function show($jadwal_id)
    {
        $jadwal = Jadwal::with(['matakuliah', 'krs.mahasiswa', 'presensi'])
            ->where('dosen_id', Auth::user()->dosen->id)
            ->findOrFail($jadwal_id);

        $mahasiswas = KRS::with('mahasiswa')
            ->where('jadwal_id', $jadwal_id)
            ->get()
            ->pluck('mahasiswa');

        // Get the latest pertemuan_ke
        $latest_pertemuan = Presensi::where('jadwal_id', $jadwal_id)
            ->max('pertemuan_ke') ?? 0;

        return view('dosen.presensi.show', compact('jadwal', 'mahasiswas', 'latest_pertemuan'));
    }

    public function store(Request $request, $jadwal_id)
    {
        try {
            \Log::info('Starting presensi store process for jadwal_id: ' . $jadwal_id);
            \Log::info('Request data:', $request->all());
            
            $request->validate([
                'pertemuan_ke' => 'required|integer|min:1',
                'tanggal' => 'required|date',
                'presensi' => 'required|array',
                'presensi.*.status' => 'required|in:hadir,izin,sakit,alpha',
                'presensi.*.keterangan' => 'nullable|string',
            ]);

            \Log::info('Validation passed');

            $jadwal = Jadwal::where('dosen_id', Auth::user()->dosen->id)
                ->findOrFail($jadwal_id);

            \Log::info('Found jadwal:', ['jadwal_id' => $jadwal->id, 'matakuliah' => $jadwal->matakuliah->nama]);

            // Check if any presensi already exists for this pertemuan
            $existingPresensi = Presensi::where('matakuliah_id', $jadwal->matakuliah_id)
                ->where('pertemuan_ke', $request->pertemuan_ke)
                ->exists();

            if ($existingPresensi) {
                \Log::warning('Duplicate presensi attempt', [
                    'matakuliah_id' => $jadwal->matakuliah_id,
                    'pertemuan_ke' => $request->pertemuan_ke
                ]);
                return back()
                    ->withInput()
                    ->with('error', 'Presensi untuk pertemuan ke-' . $request->pertemuan_ke . ' sudah ada.');
            }

            DB::beginTransaction();
            \Log::info('Starting transaction');

            // Get list of valid mahasiswa_ids for this jadwal
            $validMahasiswaIds = KRS::where('jadwal_id', $jadwal_id)
                ->pluck('mahasiswa_id')
                ->toArray();

            \Log::info('Valid mahasiswa IDs:', ['ids' => $validMahasiswaIds]);

            foreach ($request->presensi as $mahasiswa_id => $data) {
                try {
                    // Validate that the mahasiswa is actually enrolled in this class
                    if (!in_array($mahasiswa_id, $validMahasiswaIds)) {
                        throw new \Exception("Invalid mahasiswa_id: {$mahasiswa_id} for matakuliah: {$jadwal->matakuliah_id}");
                    }

                    \Log::info('Creating presensi for mahasiswa', [
                        'mahasiswa_id' => $mahasiswa_id,
                        'status' => $data['status']
                    ]);

                    Presensi::create([
                        'jadwal_id' => $jadwal_id,
                        'matakuliah_id' => $jadwal->matakuliah_id,
                        'mahasiswa_id' => $mahasiswa_id,
                        'pertemuan_ke' => $request->pertemuan_ke,
                        'tanggal' => $request->tanggal,
                        'status' => $data['status'],
                        'keterangan' => $data['keterangan'] ?? null,
                    ]);
                } catch (\Exception $e) {
                    \Log::error('Error saving individual presensi:', [
                        'mahasiswa_id' => $mahasiswa_id,
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString()
                    ]);
                    throw $e;
                }
            }

            DB::commit();
            \Log::info('Transaction committed successfully');
            
            return redirect()->route('dosen.presensi.show', $jadwal_id)
                ->with('success', 'Data presensi berhasil disimpan');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error in PresensiController@store:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);
            
            $errorMessage = 'Terjadi kesalahan saat menyimpan presensi. ';
            if ($e instanceof \Illuminate\Validation\ValidationException) {
                $errorMessage .= 'Mohon periksa kembali data yang diinput.';
            } elseif ($e instanceof \Illuminate\Database\QueryException) {
                if (str_contains($e->getMessage(), 'Duplicate entry')) {
                    $errorMessage .= 'Data presensi untuk pertemuan ini sudah ada.';
                } else {
                    $errorMessage .= 'Terjadi kesalahan database. Silakan coba lagi.';
                }
            } else {
                $errorMessage .= 'Silakan coba lagi atau hubungi administrator.';
            }
            
            return back()
                ->withInput()
                ->with('error', $errorMessage);
        }
    }

    public function rekap($jadwal_id)
    {
        $jadwal = Jadwal::with(['matakuliah', 'krs.mahasiswa'])
            ->where('dosen_id', Auth::user()->dosen->id)
            ->findOrFail($jadwal_id);

        $presensis = Presensi::where('matakuliah_id', $jadwal->matakuliah_id)
            ->get()
            ->groupBy('mahasiswa_id');

        $mahasiswas = KRS::with('mahasiswa')
            ->where('jadwal_id', $jadwal_id)
            ->get()
            ->pluck('mahasiswa');

        return view('dosen.presensi.rekap', compact('jadwal', 'presensis', 'mahasiswas'));
    }
} 