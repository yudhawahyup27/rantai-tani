<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('username', 'password');

        // Validasi input
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            // Pastikan user aktif dan memiliki role
            if (!$user->roles) {
                Auth::logout();
                return redirect()->route('login')->with('error', 'Akun Anda tidak memiliki akses yang valid.');
            }

            $role = strtolower($user->roles->role);

            switch ($role) {
                case 'administrator':
                    return redirect()->route('dashboard.administrator');
                case 'mitra':
                    return redirect()->route('dashboard.mitra');
                case 'investor':
                    return redirect()->route('dashboard.investor');
                default:
                    Auth::logout();
                    return redirect()->route('login')->with('error', 'Role tidak dikenali.');
            }
        }

        // Jika autentikasi gagal
        return redirect()->route('login')
            ->withInput($request->only('username'))
            ->with('error', 'Username atau password salah. Silakan periksa kembali akun Anda.');
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }
}
