<?php

namespace App\Http\Controllers;

use App\Models\Kursus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Show login form
     */
    public function showLoginForm()
    {
        return view('login');
    }

    /**
     * Handle login request
     */
    public function login(Request $request)
    {
        $request->validate([
            'email_or_phone' => 'required|string',
            'password' => 'required|string',
        ]);

        $identifier = $request->input('email_or_phone');

        // Login hanya menggunakan email pada tabel kursus
        if (!filter_var($identifier, FILTER_VALIDATE_EMAIL)) {
            throw ValidationException::withMessages([
                'email_or_phone' => ['Gunakan email terdaftar untuk masuk.'],
            ]);
        }

        $kursus = Kursus::where('email', $identifier)->first();

        if (!$kursus) {
            throw ValidationException::withMessages([
                'email_or_phone' => ['Email atau password salah.'],
            ]);
        }

        $password = (string) $request->input('password');

        $passwordMatches = false;
        if (!empty($kursus->password)) {
            // Coba verifikasi hash terlebih dahulu
            $passwordMatches = Hash::check($password, $kursus->password) || $kursus->password === $password;
        }

        if (!$passwordMatches) {
            throw ValidationException::withMessages([
                'email_or_phone' => ['Email atau password salah.'],
            ]);
        }

        if (!(bool) $kursus->status) {
            return back()->withErrors([
                'email_or_phone' => 'Akun kursus Anda belum disetujui admin.',
            ]);
        }

        // Simpan session sederhana untuk menandai login kursus
        $request->session()->put('kursus_id', $kursus->id_kursus);
        $request->session()->regenerate();

        return redirect()->intended('/dashboard');
    }

    /**
     * Handle logout request
     */
    public function logout(Request $request)
    {
        $request->session()->forget('kursus_id');
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}

