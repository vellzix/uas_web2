<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Jadwal;
use Illuminate\Support\Facades\Auth;

class DosenController extends Controller
{
    public function jadwalMengajar()
    {
        $dosen = Auth::user()->dosen;
        $jadwals = Jadwal::with(['matakuliah', 'krs.mahasiswa'])
            ->where('dosen_id', $dosen->id)
            ->where('status', 'aktif')
            ->get();

        return view('dosen.jadwal.index', compact('jadwals'));
    }
}
