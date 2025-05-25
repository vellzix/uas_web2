<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Matakuliah;
use App\Models\Krs;
use App\Models\Nilai;

class MahasiswaController extends Controller
{
    public function index() {
        return view('mahasiswa.dashboard');
    }

    public function krsForm() {
        $matakuliah = Matakuliah::all();
        return view('mahasiswa.krs', compact('matakuliah'));
    }

    public function submitKrs(Request $request) {
        foreach ($request->matakuliah_id as $id) {
            Krs::create([
                'user_id' => auth()->id(),
                'matakuliah_id' => $id,
            ]);
        }
        return redirect()->back()->with('success', 'KRS berhasil disimpan.');
    }

    public function lihatNilai() {
        $nilai = Nilai::whereHas('krs', function ($q) {
            $q->where('user_id', auth()->id());
        })->get();
        return view('mahasiswa.nilai', compact('nilai'));
    }

    public function transkrip() {
        // Asumsikan sama seperti lihat nilai tapi diformat lebih formal
        return view('mahasiswa.transkrip', [
            'nilai' => $this->lihatNilai()->getData()['nilai']
        ]);
    }
}
