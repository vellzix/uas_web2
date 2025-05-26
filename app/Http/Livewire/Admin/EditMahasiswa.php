<?php

namespace App\Http\Livewire\Admin;

use App\Models\Prodi;
use LivewireUI\Modal\ModalComponent;
use App\Models\Mahasiswa;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\WithFileUploads;

class EditMahasiswa extends ModalComponent
{
    use WithFileUploads;

    public $mahasiswaId;
    public $nim;
    public $nama;
    public $email;
    public $prodi_id;
    public $semester;
    public $angkatan;
    public $status;
    public $foto;
    public $temp_foto;

    protected function rules()
    {
        return [
            'nim' => 'required|unique:mahasiswas,nim,' . $this->mahasiswaId,
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $this->mahasiswa->user_id,
            'prodi_id' => 'required|exists:prodis,id',
            'semester' => 'required|integer|min:1|max:14',
            'angkatan' => 'required|integer|min:2000',
            'status' => 'required|in:aktif,nonaktif',
            'temp_foto' => 'nullable|image|max:2048',
        ];
    }

    public function mount($mahasiswaId)
    {
        $this->mahasiswaId = $mahasiswaId;
        $mahasiswa = Mahasiswa::findOrFail($mahasiswaId);
        
        $this->nim = $mahasiswa->nim;
        $this->nama = $mahasiswa->nama;
        $this->email = $mahasiswa->user->email;
        $this->prodi_id = $mahasiswa->prodi_id;
        $this->semester = $mahasiswa->semester;
        $this->angkatan = $mahasiswa->angkatan;
        $this->status = $mahasiswa->status;
        $this->foto = $mahasiswa->foto;
    }

    public function getMahasiswaProperty()
    {
        return Mahasiswa::find($this->mahasiswaId);
    }

    public function render()
    {
        return view('livewire.admin.edit-mahasiswa', [
            'prodis' => Prodi::all(),
            'angkatans' => range(date('Y'), date('Y') - 4),
        ]);
    }

    public function save()
    {
        $this->validate();

        DB::beginTransaction();
        try {
            $mahasiswa = $this->mahasiswa;

            // Update user
            $mahasiswa->user->update([
                'name' => $this->nama,
                'email' => $this->email,
            ]);

            // Handle foto upload
            if ($this->temp_foto) {
                if ($mahasiswa->foto) {
                    Storage::disk('public')->delete($mahasiswa->foto);
                }
                $fotoPath = $this->temp_foto->store('mahasiswa-photos', 'public');
            }

            // Update mahasiswa
            $mahasiswa->update([
                'nim' => $this->nim,
                'nama' => $this->nama,
                'prodi_id' => $this->prodi_id,
                'semester' => $this->semester,
                'foto' => $this->temp_foto ? $fotoPath : $this->foto,
                'angkatan' => $this->angkatan,
                'status' => $this->status,
            ]);

            DB::commit();
            $this->closeModal();
            $this->emit('mahasiswaUpdated');
            session()->flash('success', 'Data mahasiswa berhasil diperbarui');
        } catch (\Exception $e) {
            DB::rollback();
            session()->flash('error', 'Terjadi kesalahan saat memperbarui data mahasiswa');
        }
    }
} 