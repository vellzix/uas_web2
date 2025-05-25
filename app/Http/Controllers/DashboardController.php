<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Redirect berdasarkan role
        if ($user->hasRole('admin')) {
            return app(AdminDashboardController::class)->index();
        } elseif ($user->hasRole('dosen')) {
            return app(DosenDashboardController::class)->index();
        } elseif ($user->hasRole('mahasiswa')) {
            return app(MahasiswaDashboardController::class)->index();
        }

        // Default fallback
        return view('dashboard');
    }
}
