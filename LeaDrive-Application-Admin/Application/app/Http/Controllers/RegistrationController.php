<?php

namespace App\Http\Controllers;

use App\Models\Request as RequestModel;
use App\Models\DokumenKursus;
use App\Services\SupabaseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class RegistrationController extends Controller
{
    /**
     * Show registration step 1 form
     */
    public function showStep1()
    {
        return view('register-step1');
    }

    /**
     * Handle step 1 submission
     */
    public function step1(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required','string','email','max:255',
                Rule::unique('kursus','email'),
                Rule::unique('request_akun','email'),
            ],
            'nomor_hp' => [
                'required', 'regex:/^[0-9]{8,20}$/',
                Rule::unique('request_akun','nomor_hp'),
            ],
            'password' => [
                'required',
                'string',
                'min:8',
                'regex:/[a-z]/',
                'regex:/[A-Z]/',
                'regex:/[0-9]/',
            ],
            'password_confirmation' => 'required|string|same:password',
            'terms' => 'accepted',
        ], [
            'password.regex' => 'Password harus mengandung huruf besar, huruf kecil, dan angka.',
            'terms.accepted' => 'Anda harus menyetujui syarat & ketentuan.',
        ]);

        // Store step 1 data in session
        $request->session()->put('registration.step1', $validated);

        return redirect()->route('register.step2');
    }

    /**
     * Show registration step 2 form
     */
    public function showStep2(Request $request)
    {
        if (!$request->session()->has('registration.step1')) {
            return redirect()->route('register.step1')->withErrors([
                'session' => 'Silakan lengkapi data akun dasar terlebih dahulu.',
            ]);
        }

        return view('register-step2');
    }

    /**
     * Handle step 2 submission
     */
    public function step2(Request $request)
    {
        if (!$request->session()->has('registration.step1')) {
            return redirect()->route('register.step1');
        }

        $validated = $request->validate([
            'nama_kursus' => 'required|string|max:255',
            'lokasi' => 'required|string',
            'jam_buka' => 'required|date_format:H:i',
            'jam_tutup' => 'required|date_format:H:i|after:jam_buka',
        ], [
            'jam_tutup.after' => 'Jam tutup harus setelah jam buka.',
        ]);

        // Store step 2 data in session
        $request->session()->put('registration.step2', $validated);

        return redirect()->route('register.step3');
    }

    /**
     * Show registration step 3 form
     */
    public function showStep3(Request $request)
    {
        if (!$request->session()->has('registration.step1') || !$request->session()->has('registration.step2')) {
            return redirect()->route('register.step1')->withErrors([
                'session' => 'Silakan lengkapi data sebelumnya terlebih dahulu.',
            ]);
        }

        return view('register-step3');
    }

    /**
     * Handle step 3 submission and complete registration
     */
    public function step3(Request $request)
    {
        if (!$request->session()->has('registration.step1') || !$request->session()->has('registration.step2')) {
            return redirect()->route('register.step1');
        }

        $validated = $request->validate([
            'ktp' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'izin_usaha' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'sertif_instruktur' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'dokumen_legal' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ], [
            'ktp.required' => 'KTP pemilik kursus wajib diupload.',
            'izin_usaha.required' => 'Izin usaha/SIUP wajib diupload.',
            'ktp.max' => 'File KTP maksimal 5MB.',
            'izin_usaha.max' => 'File Izin Usaha maksimal 5MB.',
            'sertif_instruktur.max' => 'File Sertifikat Instruktur maksimal 5MB.',
            'dokumen_legal.max' => 'File Dokumen Legal maksimal 5MB.',
        ]);

        try {
            DB::beginTransaction();

            // Get data from session
            $step1 = $request->session()->get('registration.step1');
            $step2 = $request->session()->get('registration.step2');


            // Initialize Supabase Service
            $supabaseService = new SupabaseService();

            // Upload documents to Supabase Storage
            $uploadedFiles = [];
            $errors = [];

            // Upload KTP
            $ktpUrl = $supabaseService->uploadKursusDocument($request->file('ktp'), 'ktp');
            if (!$ktpUrl) {
                $errors[] = 'Gagal mengupload KTP ke Supabase. Pastikan SUPABASE_KEY sudah di-set di file .env';
                \Log::error('KTP upload failed', [
                    'file' => $request->file('ktp')->getClientOriginalName(),
                    'size' => $request->file('ktp')->getSize(),
                ]);
            } else {
                $uploadedFiles['ktp'] = $ktpUrl;
            }

            // Upload Izin Usaha
            $izinUsahaUrl = $supabaseService->uploadKursusDocument($request->file('izin_usaha'), 'izin');
            if (!$izinUsahaUrl) {
                $errors[] = 'Gagal mengupload Izin Usaha ke Supabase. Pastikan SUPABASE_KEY sudah di-set di file .env';
                \Log::error('Izin Usaha upload failed', [
                    'file' => $request->file('izin_usaha')->getClientOriginalName(),
                    'size' => $request->file('izin_usaha')->getSize(),
                ]);
            } else {
                $uploadedFiles['izin_usaha'] = $izinUsahaUrl;
            }

            // Upload Sertifikat Instruktur (optional)
            $sertifUrl = null;
            if ($request->hasFile('sertif_instruktur')) {
                $sertifUrl = $supabaseService->uploadKursusDocument($request->file('sertif_instruktur'), 'dokumenlain');
                if ($sertifUrl) {
                    $uploadedFiles['sertif_instruktur'] = $sertifUrl;
                }
                // Tidak error jika optional file gagal upload
            }

            // Upload Dokumen Legal (optional)
            $dokumenLegalUrl = null;
            if ($request->hasFile('dokumen_legal')) {
                $dokumenLegalUrl = $supabaseService->uploadKursusDocument($request->file('dokumen_legal'), 'dokumenlain');
                if ($dokumenLegalUrl) {
                    $uploadedFiles['dokumen_legal'] = $dokumenLegalUrl;
                }
                // Tidak error jika optional file gagal upload
            }

            // Jika ada error pada required documents, rollback
            if (!empty($errors)) {
                DB::rollBack();
                
                // Delete uploaded files from Supabase
                foreach ($uploadedFiles as $url) {
                    // Extract bucket and path from URL
                    $this->deleteSupabaseFile($supabaseService, $url);
                }

                return back()->withErrors([
                    'error' => implode(' ', $errors),
                ])->withInput();
            }

            // Create request_akun sesuai schema
            $requestModel = RequestModel::create([
                'waktu' => now(),
                'nama' => $step1['name'], // Nama pemilik kursus
                'nama_kursus' => $step2['nama_kursus'],
                'lokasi' => $step2['lokasi'],
                'jam_buka' => $step2['jam_buka'],
                'jam_tutup' => $step2['jam_tutup'],
                'password' => Hash::make($step1['password']),
                'nomor_hp' => $step1['nomor_hp'],
                'email' => $step1['email'],
                'status' => 'pending', // Default status pending untuk verifikasi admin
            ]);

            
            DokumenKursus::create([
                'ktp' => $ktpUrl,
                'izin_usaha' => $izinUsahaUrl, // lowercase untuk match database
                'sertif_instruktur' => $sertifUrl,
                'dokumen_legal' => $dokumenLegalUrl,
                'id_request' => $requestModel->id_request,
            ]);

            

            DB::commit();

            // Clear registration session
            $request->session()->forget('registration');

            return redirect()->route('login')->with('success', 'Registrasi berhasil! Akun Anda sedang menunggu persetujuan admin.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            // Delete uploaded files from Supabase if any
            if (isset($supabaseService) && !empty($uploadedFiles)) {
                foreach ($uploadedFiles as $url) {
                    $this->deleteSupabaseFile($supabaseService, $url);
                }
            }

            return back()->withErrors([
                'error' => 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage(),
            ])->withInput();
        }
    }

    /**
     * Go back to previous step
     */
    public function back(Request $request, $step)
    {
        if ($step == 2) {
            return redirect()->route('register.step1');
        } elseif ($step == 3) {
            return redirect()->route('register.step2');
        }

        return redirect()->route('register.step1');
    }

    /**
     * Delete file from Supabase using URL
     *
     * @param SupabaseService $supabaseService
     * @param string $url
     * @return void
     */
    private function deleteSupabaseFile(SupabaseService $supabaseService, string $url): void
    {
        try {
            // Extract bucket and path from Supabase URL
            // URL format: {storage_url}/object/public/{bucket}/{path}
            $urlParts = parse_url($url);
            $pathParts = explode('/object/public/', $url);
            
            if (isset($pathParts[1])) {
                $bucketAndPath = $pathParts[1];
                $parts = explode('/', $bucketAndPath, 2);
                
                if (count($parts) === 2) {
                    $bucket = $parts[0];
                    $filePath = $parts[1];
                    $supabaseService->deleteFile($bucket, $filePath);
                }
            }
        } catch (\Exception $e) {
            // Log error but don't throw
            \Log::error('Failed to delete file from Supabase during rollback', [
                'url' => $url,
                'error' => $e->getMessage(),
            ]);
        }
    }
}

