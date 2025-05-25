<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin() {
        return view('auth.login');
    }

    public function login(Request $request) {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $role = auth()->user()->role;
            if ($role == 'admin') {
                return redirect('/admin/dashboard');
            } elseif ($role == 'dosen') {
                return redirect('/dosen/dashboard');
            } else {
                return redirect('/mahasiswa/dashboard');
            }
        }

        return back()->withErrors(['email' => 'Login gagal.']);
    }

    public function logout() {
        Auth::logout();
        return redirect('/login');
    }
}
