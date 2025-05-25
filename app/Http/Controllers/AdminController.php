<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Matakuliah;
use App\Models\Ruangan;
use App\Models\Pengumuman;

class AdminController extends Controller
{
    public function index() {
        return view('admin.dashboard');
    }

    public function manageMahasiswa() {
        $mahasiswa = User::where('role', 'mahasiswa')->get();
        return view('admin.mahasiswa.index', compact('mahasiswa'));
    }

    public function manageDosen() {
        $dosen = User::where('role', 'dosen')->get();
        return view('admin.dosen.index', compact('dosen'));
    }

    public function manageMatakuliah() {
        $matakuliah = Matakuliah::all();
        return view('admin.matakuliah.index', compact('matakuliah'));
    }

    public function manageRuangan() {
        $ruangan = Ruangan::all();
        return view('admin.ruangan.index', compact('ruangan'));
    }

    public function managePengumuman() {
        $pengumuman = Pengumuman::latest()->get();
        return view('admin.pengumuman.index', compact('pengumuman'));
    }
}
