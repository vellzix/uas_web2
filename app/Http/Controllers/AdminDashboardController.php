<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\Prodi;
use App\Models\Matakuliah;
use App\Models\SystemLog;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $data = [
            'total_mahasiswa' => Mahasiswa::count(),
            'total_dosen' => Dosen::count(),
            'total_prodi' => Prodi::count(),
            'total_matkul' => Matakuliah::count(),
            'latest_users' => User::latest()->take(5)->get(),
            'system_logs' => SystemLog::latest()->take(5)->get(),
        ];

        return view('admin.dashboard', $data);
    }
} 