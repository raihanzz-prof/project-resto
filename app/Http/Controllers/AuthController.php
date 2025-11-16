<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Menampilkan halaman login
    public function showLoginForm()
    {
        return view('login_view'); // sesuai struktur kamu
    }

    // Proses login
    public function login(Request $request)
    {
        // Validasi input dari form
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Ambil input email dan password
        $credentials = $request->only('email', 'password');

        // Coba login
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $user = Auth::user();

            // Arahkan sesuai role
            switch ($user->role) {
                case 'admin':
                    return redirect('/admin');
                case 'waiter':
                    return redirect('/waiter');
                case 'kasir':
                    return redirect('/kasir');
                case 'owner':
                    return redirect('/owner');
                default:
                    Auth::logout();
                    return redirect('/login')->with('error', 'Role tidak dikenali.');
            }
        }

        // Kalau gagal login
        return back()->with('error', 'Email atau password salah.');
    }

    // Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
