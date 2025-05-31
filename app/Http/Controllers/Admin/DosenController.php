<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dosen;
use App\Models\Prodi;
use App\Models\User;
use App\Models\Matakuliah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class DosenController extends Controller
{
    public function index()
    {
        $dosens = Dosen::with(['prodi', 'user', 'matakuliah'])
            ->latest()
            ->paginate(10);

        $prodis = Prodi::orderBy('nama')->get();
        $matakuliahs = Matakuliah::where('status', 'aktif')->orderBy('nama')->get();

        return view('admin.dosen.index', compact('dosens', 'prodis', 'matakuliahs'));
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'nip' => 'required|unique:dosens,nip',
                'nama' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|min:8',
                'no_hp' => 'required|string|max:20',
                'tempat_lahir' => 'required|string|max:255',
                'tanggal_lahir' => 'required|date',
                'jenis_kelamin' => 'required|in:L,P',
                'agama' => 'required|string|max:50',
                'prodi_id' => 'required|exists:prodis,id',
                'matakuliah_id' => 'required|exists:matakuliahs,id',
                'foto' => 'nullable|image|max:2048',
            ]);

            DB::beginTransaction();

            // Create user
            $user = User::create([
                'name' => $validated['nama'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => 'dosen',
            ]);

            // Handle foto upload
            $fotoPath = null;
            if ($request->hasFile('foto')) {
                $fotoPath = $request->file('foto')->store('dosen-photos', 'public');
            }

            // Create dosen
            $dosen = Dosen::create([
                'user_id' => $user->id,
                'nip' => $validated['nip'],
                'nama' => $validated['nama'],
                'no_hp' => $validated['no_hp'],
                'tempat_lahir' => $validated['tempat_lahir'],
                'tanggal_lahir' => $validated['tanggal_lahir'],
                'jenis_kelamin' => $validated['jenis_kelamin'],
                'agama' => $validated['agama'],
                'prodi_id' => $validated['prodi_id'],
                'matakuliah_id' => $validated['matakuliah_id'],
                'foto' => $fotoPath,
                'status' => 'aktif',
            ]);

            DB::commit();

            \Log::info('Dosen created successfully', [
                'dosen_id' => $dosen->id,
                'user_id' => $user->id
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Data dosen berhasil ditambahkan'
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Validation error when creating dosen', [
                'errors' => $e->errors(),
                'request' => $request->all()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Error creating dosen', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request' => $request->all()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menambahkan data dosen'
            ], 500);
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
            'matakuliah_id' => $dosen->matakuliah_id,
            'user' => [
                'email' => $dosen->user->email
            ]
        ]);
    }

    public function update(Request $request, Dosen $dosen)
    {
        try {
            $request->validate([
                'nip' => 'required|unique:dosens,nip,' . $dosen->id,
                'nama' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email,' . $dosen->user_id,
                'no_hp' => 'required|string|max:20',
                'tempat_lahir' => 'required|string|max:255',
                'tanggal_lahir' => 'required|date',
                'jenis_kelamin' => 'required|in:L,P',
                'agama' => 'required|string|max:50',
                'prodi_id' => 'required|exists:prodis,id',
                'matakuliah_id' => 'required|exists:matakuliahs,id',
                'foto' => 'nullable|image|max:2048',
                'password' => 'nullable|min:8',
            ]);

            DB::beginTransaction();

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
                'no_hp' => $request->no_hp,
                'tempat_lahir' => $request->tempat_lahir,
                'tanggal_lahir' => $request->tanggal_lahir,
                'jenis_kelamin' => $request->jenis_kelamin,
                'agama' => $request->agama,
                'prodi_id' => $request->prodi_id,
                'matakuliah_id' => $request->matakuliah_id,
                'foto' => $request->hasFile('foto') ? $fotoPath : $dosen->foto,
            ]);

            DB::commit();

            \Log::info('Dosen updated successfully', [
                'dosen_id' => $dosen->id,
                'user_id' => $dosen->user_id
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Data dosen berhasil diperbarui'
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollback();
            \Log::error('Validation error when updating dosen', [
                'errors' => $e->errors(),
                'request' => $request->all()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Error updating dosen', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request' => $request->all()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui data dosen'
            ], 500);
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