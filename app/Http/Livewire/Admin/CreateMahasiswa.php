<?php

namespace App\Http\Livewire\Admin;

use App\Models\Prodi;
use LivewireUI\Modal\ModalComponent;
use App\Models\Mahasiswa;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Livewire\WithFileUploads;

class CreateMahasiswa extends ModalComponent
{
    use WithFileUploads;

    public $nim;
    public $nama;
    public $email;
    public $password;
    public $prodi_id;
    public $semester;
    public $angkatan;
    public $foto;

    protected $rules = [
        'nim' => 'required|unique:mahasiswas,nim',
        'nama' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|min:8',
        'prodi_id' => 'required|exists:prodis,id',
        'semester' => 'required|integer|min:1|max:14',
        'angkatan' => 'required|integer|min:2000',
        'foto' => 'nullable|image|max:2048',
    ];

    public function render()
    {
        return view('livewire.admin.create-mahasiswa', [
            'prodis' => Prodi::all(),
            'angkatans' => range(date('Y'), date('Y') - 4),
        ]);
    }

    public function save()
    {
        $this->validate();

        DB::beginTransaction();
        try {
            // Create user
            $user = User::create([
                'name' => $this->nama,
                'email' => $this->email,
                'password' => Hash::make($this->password),
                'role' => 'mahasiswa',
            ]);

            // Handle foto upload
            $fotoPath = null;
            if ($this->foto) {
                $fotoPath = $this->foto->store('mahasiswa-photos', 'public');
            }

            // Create mahasiswa
            Mahasiswa::create([
                'user_id' => $user->id,
                'nim' => $this->nim,
                'nama' => $this->nama,
                'prodi_id' => $this->prodi_id,
                'semester' => $this->semester,
                'foto' => $fotoPath,
                'angkatan' => $this->angkatan,
                'status' => 'aktif',
            ]);

            DB::commit();
            $this->closeModal();
            $this->emit('mahasiswaCreated');
            session()->flash('success', 'Mahasiswa berhasil ditambahkan');
        } catch (\Exception $e) {
            DB::rollback();
            session()->flash('error', 'Terjadi kesalahan saat menambahkan mahasiswa');
        }
    }
} 