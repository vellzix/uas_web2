<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
use App\Models\Prodi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class MahasiswaController extends Controller
{
    public function index()
    {
        $mahasiswas = Mahasiswa::with(['prodi', 'user'])
            ->latest()
            ->paginate(10);

        $prodis = Prodi::orderBy('nama')->get();
        $angkatans = range(date('Y'), date('Y') - 4);

        return view('admin.mahasiswa.index', compact('mahasiswas', 'prodis', 'angkatans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nim' => 'required|unique:mahasiswas,nim',
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'no_hp' => 'required|string|max:15',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:L,P',
            'agama' => 'required|string',
            'prodi_id' => 'required|exists:prodis,id',
            'angkatan' => 'required|integer|min:2000',
            'foto' => 'nullable|image|max:2048'
        ]);

        DB::beginTransaction();
        try {
            // Create user
            $user = User::create([
                'name' => $request->nama,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'mahasiswa',
            ]);

            // Handle foto upload
            $fotoPath = null;
            if ($request->hasFile('foto')) {
                $fotoPath = $request->file('foto')->store('mahasiswa-photos', 'public');
            }

            // Create mahasiswa
            Mahasiswa::create([
                'user_id' => $user->id,
                'nim' => $request->nim,
                'nama' => $request->nama,
                'no_hp' => $request->no_hp,
                'tempat_lahir' => $request->tempat_lahir,
                'tanggal_lahir' => $request->tanggal_lahir,
                'jenis_kelamin' => $request->jenis_kelamin,
                'agama' => $request->agama,
                'prodi_id' => $request->prodi_id,
                'angkatan' => $request->angkatan,
                'foto' => $fotoPath,
                'status' => 'aktif',
            ]);

            DB::commit();

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Mahasiswa berhasil ditambahkan'
                ]);
            }

            return redirect()->route('admin.mahasiswa.index')
                ->with('success', 'Mahasiswa berhasil ditambahkan');

        } catch (\Exception $e) {
            DB::rollback();
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan saat menambahkan mahasiswa',
                    'error' => $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'Terjadi kesalahan saat menambahkan mahasiswa');
        }
    }

    public function edit(Mahasiswa $mahasiswa)
    {
        $mahasiswa->load('user', 'prodi');
        return response()->json($mahasiswa);
    }

    public function update(Request $request, Mahasiswa $mahasiswa)
    {
        $request->validate([
            'nim' => 'required|unique:mahasiswas,nim,' . $mahasiswa->id,
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $mahasiswa->user_id,
            'no_hp' => 'required|string|max:15',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:L,P',
            'agama' => 'required|string',
            'prodi_id' => 'required|exists:prodis,id',
            'angkatan' => 'required|integer|min:2000',
            'foto' => 'nullable|image|max:2048',
            'status' => 'required|in:aktif,nonaktif'
        ]);

        DB::beginTransaction();
        try {
            // Update user
            $userData = [
                'name' => $request->nama,
                'email' => $request->email,
            ];

            if ($request->filled('password')) {
                $userData['password'] = Hash::make($request->password);
            }

            $mahasiswa->user->update($userData);

            // Handle foto upload
            if ($request->hasFile('foto')) {
                // Delete old foto
                if ($mahasiswa->foto) {
                    Storage::disk('public')->delete($mahasiswa->foto);
                }
                $fotoPath = $request->file('foto')->store('mahasiswa-photos', 'public');
            }

            // Update mahasiswa
            $mahasiswa->update([
                'nim' => $request->nim,
                'nama' => $request->nama,
                'no_hp' => $request->no_hp,
                'tempat_lahir' => $request->tempat_lahir,
                'tanggal_lahir' => $request->tanggal_lahir,
                'jenis_kelamin' => $request->jenis_kelamin,
                'agama' => $request->agama,
                'prodi_id' => $request->prodi_id,
                'angkatan' => $request->angkatan,
                'foto' => $request->hasFile('foto') ? $fotoPath : $mahasiswa->foto,
                'status' => $request->status
            ]);

            DB::commit();

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Data mahasiswa berhasil diperbarui'
                ]);
            }

            return redirect()->route('admin.mahasiswa.index')
                ->with('success', 'Data mahasiswa berhasil diperbarui');

        } catch (\Exception $e) {
            DB::rollback();
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan saat memperbarui data mahasiswa'
                ], 500);
            }

            return back()->with('error', 'Terjadi kesalahan saat memperbarui data mahasiswa');
        }
    }

    public function destroy(Mahasiswa $mahasiswa)
    {
        DB::beginTransaction();
        try {
            // Delete foto if exists
            if ($mahasiswa->foto) {
                Storage::disk('public')->delete($mahasiswa->foto);
            }

            // Delete user and mahasiswa
            $mahasiswa->user->delete();
            $mahasiswa->delete();

            DB::commit();

            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Mahasiswa berhasil dihapus'
                ]);
            }

            return redirect()->route('admin.mahasiswa.index')
                ->with('success', 'Mahasiswa berhasil dihapus');

        } catch (\Exception $e) {
            DB::rollback();
            
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan saat menghapus mahasiswa'
                ], 500);
            }

            return back()->with('error', 'Terjadi kesalahan saat menghapus mahasiswa');
        }
    }
} 