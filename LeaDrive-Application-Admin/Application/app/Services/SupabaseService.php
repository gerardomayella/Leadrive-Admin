<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\UploadedFile;

class SupabaseService
{
    protected $url;
    protected $key;
    protected $storageUrl;

    public function __construct()
    {
        // Ambil dari config (sudah ada auto-detect logic di config/supabase.php)
        $this->url = config('supabase.url');
        $this->key = config('supabase.key');
        $this->storageUrl = config('supabase.storage_url');
        
        // Validasi konfigurasi
        if (!$this->key) {
            \Log::error('SUPABASE_KEY tidak ditemukan di .env. Upload ke Supabase Storage akan gagal. Silakan tambahkan SUPABASE_KEY di file .env');
        }
        
        if (!$this->storageUrl) {
            \Log::error('SUPABASE_STORAGE_URL tidak ditemukan. Silakan tambahkan SUPABASE_STORAGE_URL di file .env');
        }
    }

    /**
     * Upload file ke Supabase Storage bucket
     *
     * @param UploadedFile $file
     * @param string $bucket Nama bucket (kursus atau instruktur)
     * @param string $folder Nama folder dalam bucket
     * @param string|null $fileName Nama file custom (optional)
     * @return string|null Public URL file atau null jika gagal
     */
    public function uploadFile(UploadedFile $file, string $bucket, string $folder, ?string $fileName = null): ?string
    {
        // Validasi konfigurasi Supabase
        if (!$this->key || !$this->storageUrl) {
            Log::error('Supabase configuration is missing', [
                'has_key' => !empty($this->key),
                'has_storage_url' => !empty($this->storageUrl),
            ]);
            return null;
        }
        
        // Validasi file sebelum upload
        if (!$this->validateFile($file)) {
            return null;
        }
        
        try {
            // Generate unique filename jika tidak disediakan
            if (!$fileName) {
                $extension = $file->getClientOriginalExtension();
                $fileName = uniqid() . '_' . time() . '.' . $extension;
            }

            // Path file di bucket (pastikan tidak ada leading/trailing slash)
            $filePath = trim($folder . '/' . $fileName, '/');

            // Baca file content
            $fileContent = file_get_contents($file->getRealPath());
            $mimeType = $file->getMimeType();

            // Supabase Storage API endpoint
            $uploadUrl = rtrim($this->storageUrl, '/') . '/object/' . $bucket . '/' . $filePath;

            // Upload ke Supabase Storage menggunakan PUT method
            $response = Http::withHeaders([
                'apikey' => $this->key,
                'Authorization' => 'Bearer ' . $this->key,
                'Content-Type' => $mimeType,
                'x-upsert' => 'true', // Overwrite jika file sudah ada
            ])->withBody($fileContent, $mimeType)
              ->put($uploadUrl);

            if ($response->successful()) {
                // Generate public URL
                $publicUrl = $this->getPublicUrl($bucket, $filePath);
                Log::info('File uploaded successfully to Supabase', [
                    'bucket' => $bucket,
                    'path' => $filePath,
                    'url' => $publicUrl,
                ]);
                return $publicUrl;
            } else {
                Log::error('Failed to upload file to Supabase', [
                    'bucket' => $bucket,
                    'path' => $filePath,
                    'status' => $response->status(),
                    'body' => $response->body(),
                    'headers' => $response->headers(),
                ]);
                return null;
            }
        } catch (\Exception $e) {
            Log::error('Exception while uploading file to Supabase', [
                'bucket' => $bucket,
                'folder' => $folder,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return null;
        }
    }

    /**
     * Validasi file berdasarkan konfigurasi
     *
     * @param UploadedFile $file
     * @return bool
     */
    private function validateFile(UploadedFile $file): bool
    {
        $maxSize = config('supabase.upload.max_file_size');
        $allowedMimes = config('supabase.upload.allowed_mime_types');

        // Validasi ukuran file
        if ($file->getSize() > $maxSize) {
            Log::error('File size exceeds the maximum allowed size', [
                'file_size' => $file->getSize(),
                'max_size' => $maxSize,
            ]);
            return false;
        }

        // Validasi tipe MIME
        if (!in_array($file->getMimeType(), $allowedMimes)) {
            Log::error('File MIME type is not allowed', [
                'mime_type' => $file->getMimeType(),
                'allowed_mimes' => $allowedMimes,
            ]);
            return false;
        }

        return true;
    }

    /**
     * Get public URL untuk file di Supabase Storage
     *
     * @param string $bucket
     * @param string $filePath
     * @return string
     */
    public function getPublicUrl(string $bucket, string $filePath): string
    {
        // Supabase Storage public URL format: {storage_url}/object/public/{bucket}/{path}
        return rtrim($this->storageUrl, '/') . '/object/public/' . $bucket . '/' . $filePath;
    }

    /**
     * Delete file dari Supabase Storage
     *
     * @param string $bucket
     * @param string $filePath
     * @return bool
     */
    public function deleteFile(string $bucket, string $filePath): bool
    {
        try {
            $response = Http::withHeaders([
                'apikey' => $this->key,
                'Authorization' => 'Bearer ' . $this->key,
            ])->delete(
                $this->storageUrl . '/object/' . $bucket . '/' . $filePath
            );

            if ($response->successful()) {
                Log::info('File deleted successfully from Supabase', [
                    'bucket' => $bucket,
                    'path' => $filePath,
                ]);
                return true;
            } else {
                Log::error('Failed to delete file from Supabase', [
                    'bucket' => $bucket,
                    'path' => $filePath,
                    'status' => $response->status(),
                ]);
                return false;
            }
        } catch (\Exception $e) {
            Log::error('Exception while deleting file from Supabase', [
                'bucket' => $bucket,
                'path' => $filePath,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    /**
     * Upload dokumen kursus ke bucket kursus
     *
     * @param UploadedFile $file
     * @param string $documentType (ktp, izin, dokumenlain, fotokursus)
     * @return string|null
     */
    public function uploadKursusDocument(UploadedFile $file, string $documentType): ?string
    {
        $bucket = config('supabase.buckets.kursus');
        $folder = config("supabase.folders.kursus.{$documentType}");

        if (!$folder) {
            Log::error('Invalid document type for kursus', ['document_type' => $documentType]);
            return null;
        }
        
        return $this->uploadFile($file, $bucket, $folder);
    }

    /**
     * Upload dokumen instruktur ke bucket instruktur
     *
     * @param UploadedFile $file
     * @param string $documentType (sim, fotoinstruktur, sertifikat)
     * @return string|null
     */
    public function uploadInstrukturDocument(UploadedFile $file, string $documentType): ?string
    {
        $bucket = config('supabase.buckets.instruktur');
        $folder = config("supabase.folders.instruktur.{$documentType}");

        if (!$folder) {
            Log::error('Invalid document type for instruktur', ['document_type' => $documentType]);
            return null;
        }
        
        return $this->uploadFile($file, $bucket, $folder);
    }
}

