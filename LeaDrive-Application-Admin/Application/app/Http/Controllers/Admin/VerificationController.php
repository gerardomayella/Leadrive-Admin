<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Request as RequestModel;
use App\Models\Kursus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class VerificationController extends Controller
{
    /**
     * Tampilkan daftar request yang perlu diverifikasi
     */
    public function index(Request $request)
    {
        $status = $request->get('status', 'pending');
        
        $requests = RequestModel::with('dokumenKursus')
            ->where('status', $status)
            ->orderBy('waktu', 'desc')
            ->paginate(10);
        
        $counts = [
            'pending' => RequestModel::where('status', 'pending')->count(),
            'approved' => RequestModel::where('status', 'approved')->count(),
            'rejected' => RequestModel::where('status', 'rejected')->count(),
        ];
        
        return view('admin.verifikasi', compact('requests', 'status', 'counts'));
    }
    
    /**
     * Tampilkan detail request untuk verifikasi
     */
    public function show($id)
    {
        $requestData = RequestModel::with('dokumenKursus')->findOrFail($id);
        
        return view('admin.verifikasi-detail', compact('requestData'));
    }
    
    /**
     * Approve request dan buat akun kursus
     */
    public function approve($id)
    {
        try {
            DB::beginTransaction();
            
            $requestData = RequestModel::with('dokumenKursus')->findOrFail($id);
            
            // Cek apakah sudah diproses
            if ($requestData->status !== 'pending') {
                return back()->withErrors(['error' => 'Request sudah diproses sebelumnya.']);
            }
            
            // Validasi data required
            if (!$requestData->password) {
                return back()->withErrors(['error' => 'Password tidak ditemukan di request data.']);
            }
            
            // Log untuk debugging
            \Log::info('Creating kursus from request', [
                'id_request' => $requestData->id_request,
                'nama_kursus' => $requestData->nama_kursus,
                'email' => $requestData->email,
                'has_password' => !empty($requestData->password),
                'has_dokumen' => $requestData->dokumenKursus !== null,
            ]);
            
            // Buat akun kursus baru
            $kursus = Kursus::create([
                'nama_kursus' => $requestData->nama_kursus ?? 'Unknown',
                'email' => $requestData->email ?? 'noemail@example.com',
                'password' => $requestData->password, // Already hashed
                'nomor_hp' => $requestData->nomor_hp ?? '',
                'lokasi' => $requestData->lokasi ?? '',
                'jam_buka' => $requestData->jam_buka ?? '00:00:00',
                'jam_tutup' => $requestData->jam_tutup ?? '23:59:59',
                'status' => '1', // Set status aktif agar bisa login
                'ktp' => $requestData->dokumenKursus->ktp ?? null,
                'izin_usaha' => $requestData->dokumenKursus->izin_usaha ?? null,
                'sertif_instruktur' => $requestData->dokumenKursus->sertif_instruktur ?? null,
                'dokumen_legal' => $requestData->dokumenKursus->dokumen_legal ?? null,
            ]);
            
            // Update status request
            $requestData->update(['status' => 'approved']);
            
            DB::commit();
            
            \Log::info('Kursus created successfully', ['id_kursus' => $kursus->id_kursus]);
            
            return redirect()->route('admin.verifikasi')
                ->with('success', 'Request berhasil disetujui! Akun kursus telah dibuat.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            \Log::error('Error approving request', [
                'id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            
            return back()->withErrors([
                'error' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }
    
    /**
     * Reject request
     */
    public function reject(Request $request, $id)
    {
        $request->validate([
            'alasan' => 'required|string|max:500'
        ]);
        
        try {
            $requestData = RequestModel::findOrFail($id);
            
            // Cek apakah sudah diproses
            if ($requestData->status !== 'pending') {
                return back()->withErrors(['error' => 'Request sudah diproses sebelumnya.']);
            }
            
            // Update status request
            $requestData->update([
                'status' => 'rejected',
                // Bisa tambahkan kolom alasan_reject jika perlu
            ]);
            
            return redirect()->route('admin.verifikasi')
                ->with('success', 'Request berhasil ditolak.');
                
        } catch (\Exception $e) {
            return back()->withErrors([
                'error' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }
    
    /**
     * Set request kembali ke pending
     */
    public function setPending($id)
    {
        try {
            $requestData = RequestModel::findOrFail($id);
            
            $requestData->update(['status' => 'pending']);
            
            return redirect()->route('admin.verifikasi')
                ->with('success', 'Status request dikembalikan ke pending.');
                
        } catch (\Exception $e) {
            return back()->withErrors([
                'error' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }
}
