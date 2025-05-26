<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengumuman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PengumumanController extends Controller
{
    public function index()
    {
        $pengumumans = Pengumuman::latest()->paginate(10);
        return view('admin.pengumuman.index', compact('pengumumans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'isi' => 'required|string',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'tipe' => 'required|in:umum,mahasiswa,dosen',
            'status' => 'required|in:draft,published',
        ]);

        try {
            $pengumuman = Pengumuman::create([
                'judul' => $request->judul,
                'isi' => $request->isi,
                'tanggal_mulai' => $request->tanggal_mulai,
                'tanggal_selesai' => $request->tanggal_selesai,
                'tipe' => $request->tipe,
                'status' => $request->status,
                'created_by' => Auth::id(),
            ]);

            if ($request->wantsJson()) {
                return response()->json([
                    'message' => 'Pengumuman berhasil ditambahkan',
                    'data' => $pengumuman
                ]);
            }

            return redirect()->route('admin.pengumuman.index')
                ->with('success', 'Pengumuman berhasil ditambahkan');
        } catch (\Exception $e) {
            if ($request->wantsJson()) {
                return response()->json([
                    'message' => 'Terjadi kesalahan saat menambahkan pengumuman',
                    'error' => $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'Terjadi kesalahan saat menambahkan pengumuman');
        }
    }

    public function update(Request $request, Pengumuman $pengumuman)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'isi' => 'required|string',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'tipe' => 'required|in:umum,mahasiswa,dosen',
            'status' => 'required|in:draft,published',
        ]);

        try {
            $pengumuman->update([
                'judul' => $request->judul,
                'isi' => $request->isi,
                'tanggal_mulai' => $request->tanggal_mulai,
                'tanggal_selesai' => $request->tanggal_selesai,
                'tipe' => $request->tipe,
                'status' => $request->status,
                'updated_by' => Auth::id(),
            ]);

            if ($request->wantsJson()) {
                return response()->json([
                    'message' => 'Pengumuman berhasil diperbarui',
                    'data' => $pengumuman
                ]);
            }

            return redirect()->route('admin.pengumuman.index')
                ->with('success', 'Pengumuman berhasil diperbarui');
        } catch (\Exception $e) {
            if ($request->wantsJson()) {
                return response()->json([
                    'message' => 'Terjadi kesalahan saat memperbarui pengumuman',
                    'error' => $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'Terjadi kesalahan saat memperbarui pengumuman');
        }
    }

    public function destroy(Pengumuman $pengumuman)
    {
        try {
            $pengumuman->delete();
            return redirect()->route('admin.pengumuman.index')
                ->with('success', 'Pengumuman berhasil dihapus');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat menghapus pengumuman');
        }
    }

    public function show(Pengumuman $pengumuman)
    {
        return response()->json($pengumuman);
    }

    public function edit(Pengumuman $pengumuman)
    {
        return response()->json($pengumuman);
    }
} 