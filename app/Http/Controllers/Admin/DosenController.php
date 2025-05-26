<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dosen;
use App\Models\Prodi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class DosenController extends Controller
{
    public function index()
    {
        $dosens = Dosen::with(['prodi', 'user'])
            ->latest()
            ->paginate(10);

        $prodis = Prodi::orderBy('nama')->get();

        return view('admin.dosen.index', compact('dosens', 'prodis'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nip' => 'required|unique:dosens,nip',
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'prodi_id' => 'required|exists:prodis,id',
            'bidang' => 'required|string|max:255',
            'foto' => 'nullable|image|max:2048',
            'password' => 'required|min:8',
        ]);

        DB::beginTransaction();
        try {
            // Create user
            $user = User::create([
                'name' => $request->nama,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'dosen',
            ]);

            // Handle foto upload
            $fotoPath = null;
            if ($request->hasFile('foto')) {
                $fotoPath = $request->file('foto')->store('dosen-photos', 'public');
            }

            // Create dosen
            Dosen::create([
                'user_id' => $user->id,
                'nip' => $request->nip,
                'nama' => $request->nama,
                'prodi_id' => $request->prodi_id,
                'bidang' => $request->bidang,
                'foto' => $fotoPath,
                'status' => 'aktif',
            ]);

            DB::commit();
            return redirect()->route('admin.dosen.index')->with('success', 'Data dosen berhasil ditambahkan');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan saat menambahkan data dosen');
        }
    }

    public function edit(Dosen $dosen)
    {
        return response()->json([
            'id' => $dosen->id,
            'nip' => $dosen->nip,
            'nama' => $dosen->nama,
            'email' => $dosen->user->email,
            'no_hp' => $dosen->no_hp,
            'tempat_lahir' => $dosen->tempat_lahir,
            'tanggal_lahir' => $dosen->tanggal_lahir,
            'jenis_kelamin' => $dosen->jenis_kelamin,
            'agama' => $dosen->agama,
            'prodi_id' => $dosen->prodi_id,
            'bidang' => $dosen->bidang,
            'user' => [
                'email' => $dosen->user->email
            ]
        ]);
    }

    public function update(Request $request, Dosen $dosen)
    {
        $request->validate([
            'nip' => 'required|unique:dosens,nip,' . $dosen->id,
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $dosen->user_id,
            'prodi_id' => 'required|exists:prodis,id',
            'bidang' => 'required|string|max:255',
            'foto' => 'nullable|image|max:2048',
            'password' => 'nullable|min:8',
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

            $dosen->user->update($userData);

            // Handle foto upload
            if ($request->hasFile('foto')) {
                // Delete old foto
                if ($dosen->foto) {
                    Storage::disk('public')->delete($dosen->foto);
                }
                $fotoPath = $request->file('foto')->store('dosen-photos', 'public');
            }

            // Update dosen
            $dosen->update([
                'nip' => $request->nip,
                'nama' => $request->nama,
                'prodi_id' => $request->prodi_id,
                'bidang' => $request->bidang,
                'foto' => $request->hasFile('foto') ? $fotoPath : $dosen->foto,
            ]);

            DB::commit();
            return redirect()->route('admin.dosen.index')->with('success', 'Data dosen berhasil diperbarui');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan saat memperbarui data dosen');
        }
    }

    public function destroy(Dosen $dosen)
    {
        DB::beginTransaction();
        try {
            // Delete foto if exists
            if ($dosen->foto) {
                Storage::disk('public')->delete($dosen->foto);
            }

            // Delete user and dosen (cascade)
            $dosen->user->delete();

            DB::commit();
            return redirect()->route('admin.dosen.index')->with('success', 'Data dosen berhasil dihapus');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan saat menghapus data dosen');
        }
    }
} 