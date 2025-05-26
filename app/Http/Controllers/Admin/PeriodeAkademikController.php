<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PeriodeAkademik;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PeriodeAkademikController extends Controller
{
    public function index()
    {
        $periodes = PeriodeAkademik::latest()->paginate(10);
        return view('admin.periode-akademik.index', compact('periodes'));
    }

    public function create()
    {
        return view('admin.periode-akademik.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'tahun_akademik' => 'required|string',
            'semester' => 'required|in:ganjil,genap',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after:tanggal_mulai',
            'krs_mulai' => 'required|date',
            'krs_selesai' => 'required|date|after:krs_mulai',
        ]);

        // Jika periode baru diset aktif, nonaktifkan semua periode lain
        if ($request->has('is_active') && $request->is_active) {
            PeriodeAkademik::query()->update(['is_active' => false]);
        }

        PeriodeAkademik::create([
            'nama' => $request->nama,
            'tahun_akademik' => $request->tahun_akademik,
            'semester' => $request->semester,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'krs_mulai' => $request->krs_mulai,
            'krs_selesai' => $request->krs_selesai,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.periode-akademik.index')
            ->with('success', 'Periode akademik berhasil ditambahkan');
    }

    public function edit(PeriodeAkademik $periodeAkademik)
    {
        return view('admin.periode-akademik.edit', compact('periodeAkademik'));
    }

    public function update(Request $request, PeriodeAkademik $periodeAkademik)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'tahun_akademik' => 'required|string',
            'semester' => 'required|in:ganjil,genap',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after:tanggal_mulai',
            'krs_mulai' => 'required|date',
            'krs_selesai' => 'required|date|after:krs_mulai',
        ]);

        // Jika periode ini diset aktif, nonaktifkan semua periode lain
        if ($request->has('is_active') && $request->is_active) {
            PeriodeAkademik::where('id', '!=', $periodeAkademik->id)
                ->update(['is_active' => false]);
        }

        $periodeAkademik->update([
            'nama' => $request->nama,
            'tahun_akademik' => $request->tahun_akademik,
            'semester' => $request->semester,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'krs_mulai' => $request->krs_mulai,
            'krs_selesai' => $request->krs_selesai,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.periode-akademik.index')
            ->with('success', 'Periode akademik berhasil diperbarui');
    }

    public function destroy(PeriodeAkademik $periodeAkademik)
    {
        if ($periodeAkademik->krs()->exists()) {
            return back()->with('error', 'Tidak dapat menghapus periode yang sudah memiliki data KRS');
        }

        $periodeAkademik->delete();
        return redirect()->route('admin.periode-akademik.index')
            ->with('success', 'Periode akademik berhasil dihapus');
    }
} 