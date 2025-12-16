<?php

namespace App\Http\Controllers;

use App\Models\Kursus;
use App\Models\Instruktur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Services\SupabaseService;

class DashboardController extends Controller
{
    protected $supabase;

    public function __construct(SupabaseService $supabase)
    {
        $this->supabase = $supabase;
    }

    /**
     * Tampilkan dashboard kursus
     */
    public function index(Request $request)
    {
        // Ambil kursus_id dari session
        $kursusId = $request->session()->get('kursus_id');
        
        if (!$kursusId) {
            return redirect('/login')->withErrors(['error' => 'Silakan login terlebih dahulu']);
        }
        
        // Ambil data kursus
        $kursus = Kursus::with('instrukturs')->find($kursusId);
        
        if (!$kursus) {
            return redirect('/login')->withErrors(['error' => 'Data kursus tidak ditemukan']);
        }
        
        // Statistik instruktur
        $stats = [
            'total_instruktur' => $kursus->instrukturs->count(),
            'instruktur_aktif' => $kursus->instrukturs->where('status_aktif', true)->count(),
            'total_sertifikat' => $kursus->instrukturs->whereNotNull('sertifikasi')->count(),
        ];

        // Ambil statistik user sesuai data di Supabase (cocokkan struktur tabel/kolom)
        $userTable = null;
        if (Schema::hasTable('users')) {
            $userTable = 'users';
        } elseif (Schema::hasTable('auth.users')) {
            $userTable = 'auth.users';
        }

        if (!$userTable) {
            // Tidak ada tabel user yang dikenali
            $stats['total_users'] = 0;
            $stats['users_verified'] = 0;
            $stats['users_unverified'] = 0;
            $latestUsers = collect();
        } else {
            $userQuery = DB::table($userTable);

            // Filter berdasarkan kursus: cek kolom kursus_id atau di dalam user_metadata JSON (Supabase)
            if (Schema::hasColumn($userTable, 'kursus_id')) {
                $userQuery = $userQuery->where('kursus_id', $kursusId);
            } elseif (Schema::hasColumn($userTable, 'user_metadata')) {
                // Postgres JSON operator ->> untuk mengambil value string
                $userQuery = $userQuery->whereRaw("user_metadata->>'kursus_id' = ?", [$kursusId]);
            }

            // Tentukan kolom verifikasi yang tersedia
            $verifyCols = ['email_verified_at', 'confirmed_at', 'email_confirmed_at'];
            $verifiedCol = null;
            foreach ($verifyCols as $c) {
                if (Schema::hasColumn($userTable, $c)) {
                    $verifiedCol = $c;
                    break;
                }
            }

            // Hitung totals
            $totalUsers = (clone $userQuery)->count();
            $verifiedUsers = $verifiedCol ? (clone $userQuery)->whereNotNull($verifiedCol)->count() : 0;
            $unverifiedUsers = max(0, $totalUsers - $verifiedUsers);

            // Ambil user terbaru (urut berdasarkan id atau created_at jika tersedia)
            $orderBy = Schema::hasColumn($userTable, 'id') ? 'id' : (Schema::hasColumn($userTable, 'created_at') ? 'created_at' : null);
            $latestRaw = (clone $userQuery)
                ->orderBy($orderBy ?? 'email', 'desc')
                ->limit(10)
                ->get();

            // Normalisasi hasil: ambil name (kolom atau dari JSON), email, status verifikasi
            $latestUsers = $latestRaw->map(function ($u) use ($verifiedCol) {
                $name = null;
                if (isset($u->name) && $u->name) {
                    $name = $u->name;
                } elseif (isset($u->full_name) && $u->full_name) {
                    $name = $u->full_name;
                } elseif (isset($u->user_metadata) && $u->user_metadata) {
                    $meta = is_string($u->user_metadata) ? json_decode($u->user_metadata, true) : (array) $u->user_metadata;
                    $name = $meta['full_name'] ?? $meta['name'] ?? null;
                }

                $verified = false;
                if ($verifiedCol && isset($u->{$verifiedCol})) {
                    $verified = !empty($u->{$verifiedCol});
                }

                return (object)[
                    'id' => $u->id ?? null,
                    'name' => $name ?? ($u->email ?? null),
                    'email' => $u->email ?? null,
                    'verified' => (bool) $verified,
                ];
            });

            $stats['total_users'] = $totalUsers;
            $stats['users_verified'] = $verifiedUsers;
            $stats['users_unverified'] = $unverifiedUsers;
        }

        // Ambil data dari Supabase
        $latestUsers = $this->supabase->getUsers($kursusId, 10) ?? [];
        $supabaseUserCount = $this->supabase->countUsers($kursusId);
        $verifiedCount = $this->supabase->countVerifiedUsers($kursusId);
        $totalRevenue = $this->supabase->sumTransactions($kursusId);
        $transactions = $this->supabase->getTransactions($kursusId, 10) ?? [];

        // Normalisasi transactions
        $transactions = array_map(function($t) {
            $t['id'] = $t['id'] ?? ($t['raw']['id'] ?? $t['raw']['transaction_id'] ?? null);
            $t['amount'] = $t['amount'] ?? (isset($t['raw']['amount']) ? (float) $t['raw']['amount'] : $t['amount'] ?? 0.0);
            return $t;
        }, $transactions);

        // Stats assignment (0 valid)
        $stats['total_users'] = is_numeric($supabaseUserCount) ? (int)$supabaseUserCount : ($stats['total_users'] ?? 0);
        $stats['users_verified'] = is_numeric($verifiedCount) ? (int)$verifiedCount : ($stats['users_verified'] ?? 0);
        $stats['users_unverified'] = max(0, $stats['total_users'] - $stats['users_verified']);
        $stats['total_revenue'] = is_numeric($totalRevenue) ? (float)$totalRevenue : ($stats['total_revenue'] ?? 0);

        // Prepare debug info and log it
        $supabase_debug = $this->supabase->debug ?? [];
        \Log::info('Supabase debug info for dashboard', $supabase_debug);

        // New: explicit status probe to help debugging connectivity/permissions
        $supabase_status = $this->supabase->debugStatus($kursusId);
        \Log::info('Supabase status probe for dashboard', $supabase_status);

        return view('dashboard.index', compact('kursus', 'stats', 'latestInstrukturs', 'latestUsers', 'totalRevenue', 'transactions', 'supabase_debug', 'supabase_status'));
    }
}
