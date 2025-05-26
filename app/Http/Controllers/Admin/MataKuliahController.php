<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Matakuliah;
use App\Models\Prodi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MataKuliahController extends Controller
{
    public function index()
    {
        $matakuliahs = Matakuliah::with('prodi')
            ->latest()
            ->paginate(10);

        $prodis = Prodi::orderBy('nama')->get();

        return view('admin.matakuliah.index', compact('matakuliahs', 'prodis'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode' => 'required|unique:matakuliahs,kode',
            'nama' => 'required|string|max:255',
            'sks' => 'required|integer|min:1|max:6',
            'semester' => 'required|integer|min:1|max:8',
            'prodi_id' => 'required|exists:prodis,id',
            'deskripsi' => 'nullable|string',
        ]);

        try {
            Matakuliah::create([
                'kode' => strtoupper($request->kode),
                'nama' => $request->nama,
                'sks' => $request->sks,
                'semester' => $request->semester,
                'prodi_id' => $request->prodi_id,
                'deskripsi' => $request->deskripsi,
                'status' => 'aktif',
            ]);

            return redirect()->route('admin.matakuliah.index')
                ->with('success', 'Mata kuliah berhasil ditambahkan');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat menambahkan mata kuliah');
        }
    }

    public function update(Request $request, Matakuliah $matakuliah)
    {
        $request->validate([
            'kode' => 'required|unique:matakuliahs,kode,' . $matakuliah->id,
            'nama' => 'required|string|max:255',
            'sks' => 'required|integer|min:1|max:6',
            'semester' => 'required|integer|min:1|max:8',
            'prodi_id' => 'required|exists:prodis,id',
            'deskripsi' => 'nullable|string',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        try {
            $matakuliah->update([
                'kode' => strtoupper($request->kode),
                'nama' => $request->nama,
                'sks' => $request->sks,
                'semester' => $request->semester,
                'prodi_id' => $request->prodi_id,
                'deskripsi' => $request->deskripsi,
                'status' => $request->status,
            ]);

            return redirect()->route('admin.matakuliah.index')
                ->with('success', 'Mata kuliah berhasil diperbarui');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat memperbarui mata kuliah');
        }
    }

    public function destroy(Matakuliah $matakuliah)
    {
        try {
            // Check if the matakuliah is being used in jadwal
            if ($matakuliah->jadwal()->exists()) {
                return back()->with('error', 'Mata kuliah tidak dapat dihapus karena sedang digunakan dalam jadwal');
            }

            $matakuliah->delete();
            return redirect()->route('admin.matakuliah.index')
                ->with('success', 'Mata kuliah berhasil dihapus');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat menghapus mata kuliah');
        }
    }

    public function show(Matakuliah $matakuliah)
    {
        return response()->json($matakuliah->load('prodi'));
    }

    public function edit(Matakuliah $matakuliah)
    {
        return response()->json($matakuliah->load('prodi'));
    }
} 