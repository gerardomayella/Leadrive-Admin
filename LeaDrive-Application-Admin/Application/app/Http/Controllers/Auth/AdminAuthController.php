<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;

class AdminAuthController extends Controller
{
    /**
     * Show admin login form
     */
    public function showLoginForm()
    {
        return view('admin.login');
    }

    /**
     * Handle admin login
     */
    public function login(Request $request)
    {
        try {
            // Log untuk debugging
            \Log::info('Admin login attempt', [
                'email' => $request->email,
                'ip' => $request->ip(),
            ]);

            $request->validate([
                'email' => 'required|email',
                'password' => 'required|string',
            ]);

            $credentials = $request->only('email', 'password');

            // Debug: Cek apakah admin ada
            $admin = Admin::where('email', $credentials['email'])->first();
            if (!$admin) {
                \Log::warning('Admin not found', ['email' => $credentials['email']]);
                return back()->withErrors([
                    'email' => 'Email tidak ditemukan.',
                ])->onlyInput('email');
            }

            // Debug: Cek password
            if (!Hash::check($credentials['password'], $admin->password)) {
                \Log::warning('Invalid password', ['email' => $credentials['email']]);
                return back()->withErrors([
                    'email' => 'Password salah.',
                ])->onlyInput('email');
            }

            // Attempt login dengan error handling
            if (Auth::guard('admin')->attempt($credentials, $request->filled('remember'))) {
                $request->session()->regenerate();
                
                \Log::info('Admin login successful', [
                    'admin_id' => Auth::guard('admin')->user()->id,
                    'admin_name' => Auth::guard('admin')->user()->name,
                ]);

                return redirect()->intended(route('admin.dashboard'))
                    ->with('success', 'Login berhasil! Selamat datang, ' . Auth::guard('admin')->user()->name);
            }

            \Log::error('Auth::attempt failed despite valid credentials', ['email' => $credentials['email']]);

            return back()->withErrors([
                'email' => 'Login gagal. Silakan coba lagi.',
            ])->onlyInput('email');
            
        } catch (\Exception $e) {
            \Log::error('Admin login exception', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            
            return back()->withErrors([
                'email' => 'Terjadi kesalahan sistem. Silakan coba lagi atau hubungi administrator.',
            ])->onlyInput('email');
        }
    }

    /**
     * Handle admin logout
     */
    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login')
            ->with('success', 'Logout berhasil.');
    }

    /**
     * Show admin dashboard
     */
    public function dashboard()
    {
        // Get monthly revenue for the current year
        $monthlyRevenue = \Illuminate\Support\Facades\DB::table('transactions')
            ->select(
                \Illuminate\Support\Facades\DB::raw('EXTRACT(MONTH FROM created_at) as month'),
                \Illuminate\Support\Facades\DB::raw('SUM(amount) as total')
            )
            ->where('status', 'success')
            ->whereYear('created_at', date('Y'))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Format data for Chart.js
        $months = [];
        $revenue = [];
        
        // Initialize all 12 months with 0
        for ($i = 1; $i <= 12; $i++) {
            $months[] = date('M', mktime(0, 0, 0, $i, 1));
            $found = $monthlyRevenue->firstWhere('month', $i);
            $revenue[] = $found ? $found->total : 0;
        }

        return view('admin.dashboard', compact('months', 'revenue'));
    }

    /**
     * Show verifikasi kursus page
     */
    public function verifikasi()
    {
        return view('admin.verifikasi');
    }

    /**
     * API: Check admin credentials (untuk testing)
     */
    public function checkCredentials(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $admin = Admin::where('email', $request->email)->first();

        if (!$admin) {
            return response()->json([
                'success' => false,
                'message' => 'Email tidak ditemukan',
            ], 404);
        }

        $passwordMatch = Hash::check($request->password, $admin->password);

        return response()->json([
            'success' => $passwordMatch,
            'message' => $passwordMatch ? 'Kredensial valid' : 'Password salah',
            'admin' => $passwordMatch ? [
                'id' => $admin->id,
                'nama' => $admin->name,
                'email' => $admin->email,
            ] : null,
        ]);
    }
}
