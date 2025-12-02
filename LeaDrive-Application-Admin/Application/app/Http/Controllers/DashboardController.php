<?php

namespace App\Http\Controllers;

use App\Models\Kursus;
use App\Models\Instruktur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
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
        
        // Statistik
        $stats = [
            'total_instruktur' => $kursus->instrukturs->count(),
            'instruktur_aktif' => $kursus->instrukturs->where('status_aktif', true)->count(),
            'total_sertifikat' => $kursus->instrukturs->whereNotNull('sertifikasi')->count(),
        ];
        
        // Instruktur terbaru (gunakan id_instruktur untuk ordering karena tanggal_bergabung mungkin belum ada)
        $latestInstrukturs = $kursus->instrukturs()
            ->orderBy('id_instruktur', 'desc')
            ->limit(5)
            ->get();
        
        return view('dashboard.index', compact('kursus', 'stats', 'latestInstrukturs'));
    }
}
