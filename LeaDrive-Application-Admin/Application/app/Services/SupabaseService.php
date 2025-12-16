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

    public $debug = []; // store last successful info for debugging
    private $last_response = null;

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

        $this->debug = [];
        $this->last_response = null;
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

            $this->recordResponseInfo('uploadFile', $bucket, $response);

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

    /**
     * Hitung jumlah user langsung dari Supabase (REST / PostgREST)
     *
     * @param mixed|null $kursusId
     * @return int|null jumlah user atau null jika gagal/ tidak ditemukan tabel
     */
    public function countUsers($kursusId = null): ?int
    {
        if (!$this->url || !$this->key) {
            Log::warning('Supabase config missing for countUsers', ['has_url' => !empty($this->url), 'has_key' => !empty($this->key)]);
            return null;
        }

        $tables = ['users', 'user', 'public.users', 'auth.users'];
        foreach ($tables as $table) {
            $filters = [];
            if (!is_null($kursusId)) {
                $filters[] = 'kursus_id=eq.' . rawurlencode($kursusId);
                $filters[] = "user_metadata->>kursus_id=eq." . rawurlencode($kursusId);
            }
            $filters[] = null;

            foreach ($filters as $filter) {
                try {
                    // Use select=id&limit=0 to force Content-Range header with count
                    $base = rtrim($this->url, '/') . '/rest/v1/' . $table;
                    $query = '?select=id&limit=0' . ($filter ? ('&' . $filter) : '');
                    $url = $base . $query;

                    $response = Http::withHeaders([
                        'apikey' => $this->key,
                        'Authorization' => 'Bearer ' . $this->key,
                        'Prefer' => 'count=exact',
                        'Accept' => 'application/json',
                    ])->get($url);

                    $this->recordResponseInfo('countUsers', $table, $response);

                    if (!$response->successful()) {
                        Log::debug('Supabase countUsers not successful', [
                            'table' => $table, 'filter' => $filter, 'status' => $response->status(),
                        ]);
                        continue;
                    }

                    $contentRange = $response->header('content-range') ?? $response->header('Content-Range');
                    if ($contentRange && preg_match('/\/(\d+)$/', $contentRange, $m)) {
                        $count = (int) $m[1];
                        Log::info('Supabase countUsers success', ['table' => $table, 'filter' => $filter, 'count' => $count]);
                        $this->debug['countUsers'] = ['table' => $table, 'filter' => $filter, 'count' => $count];
                        return $count;
                    }

                    // Fallback: count returned array (should be empty because limit=0 but keep for safety)
                    $body = $response->json();
                    if (is_array($body)) {
                        $count = count($body);
                        Log::info('Supabase countUsers fallback array count', ['table' => $table, 'filter' => $filter, 'count' => $count]);
                        $this->debug['countUsers'] = ['table' => $table, 'filter' => $filter, 'count' => $count, 'fallback' => true];
                        return $count;
                    }
                } catch (\Exception $e) {
                    Log::debug('Exception in countUsers', ['table' => $table, 'filter' => $filter, 'err' => $e->getMessage()]);
                }
            }
        }

        Log::warning('Unable to determine user count from Supabase; no matching table/permission', []);
        return null;
    }

    /**
     * Jumlahkan kolom transaksi dari tabel transaction(s) di Supabase
     *
     * @param mixed|null $kursusId
     * @return float|null jumlah total atau null jika gagal / tidak ditemukan tabel/kolom
     */
    public function sumTransactions($kursusId = null): ?float
    {
        if (!$this->url || !$this->key) {
            Log::warning('Supabase config missing for sumTransactions', ['has_url' => !empty($this->url), 'has_key' => !empty($this->key)]);
            return null;
        }

        $tables = ['transactions', 'transaction', 'public.transactions', 'public.transaction'];
        $amountCols = ['amount', 'total', 'price', 'nominal', 'gross_amount', 'value'];

        foreach ($tables as $table) {
            foreach ($amountCols as $col) {
                $filters = [];
                if (!is_null($kursusId)) {
                    $filters[] = 'kursus_id=eq.' . rawurlencode($kursusId);
                    $filters[] = "metadata->>kursus_id=eq." . rawurlencode($kursusId);
                    $filters[] = "user_metadata->>kursus_id=eq." . rawurlencode($kursusId);
                }
                $filters[] = null;

                foreach ($filters as $filter) {
                    try {
                        $base = rtrim($this->url, '/') . '/rest/v1/' . $table;
                        $select = 'select=sum(' . $col . ') as total';
                        $url = $base . '?' . $select . ($filter ? ('&' . $filter) : '');

                        $response = Http::withHeaders([
                            'apikey' => $this->key,
                            'Authorization' => 'Bearer ' . $this->key,
                            'Accept' => 'application/json',
                        ])->get($url);

                        $this->recordResponseInfo('sumTransactions', $table, $response);

                        if (!$response->successful()) {
                            Log::debug('Supabase sumTransactions not successful', [
                                'table' => $table, 'col' => $col, 'filter' => $filter, 'status' => $response->status(),
                            ]);
                            continue;
                        }

                        $body = $response->json();
                        if (is_array($body) && isset($body[0]['total'])) {
                            $val = $body[0]['total'];
                            if (is_null($val)) {
                                Log::info('Supabase sumTransactions found null total (0)', ['table' => $table, 'col' => $col, 'filter' => $filter]);
                                $this->debug['sumTransactions'] = ['table'=>$table,'col'=>$col,'filter'=>$filter,'sum'=>0.0];
                                return 0.0;
                            }
                            $normalized = str_replace(',', '', (string)$val);
                            $sum = (float)$normalized;
                            Log::info('Supabase sumTransactions success', ['table' => $table, 'col' => $col, 'filter' => $filter, 'sum' => $sum]);
                            $this->debug['sumTransactions'] = ['table'=>$table,'col'=>$col,'filter'=>$filter,'sum'=>$sum];
                            return $sum;
                        }
                    } catch (\Exception $e) {
                        Log::debug('Exception in sumTransactions', ['table' => $table, 'col' => $col, 'filter' => $filter, 'err' => $e->getMessage()]);
                    }
                }
            }
        }

        Log::warning('Unable to calculate transaction sum from Supabase; no matching table/column/permission', []);
        return null;
    }

    /**
     * Ambil daftar transaksi terbaru dari Supabase (coba beberapa tabel/kolom)
     *
     * @param mixed|null $kursusId
     * @param int $limit
     * @return array|null array of normalized transactions or null jika gagal
     */
    public function getTransactions($kursusId = null, int $limit = 10): ?array
    {
        if (!$this->url || !$this->key) {
            Log::warning('Supabase config missing for getTransactions', ['has_url' => !empty($this->url), 'has_key' => !empty($this->key)]);
            return null;
        }

        $tables = ['transactions', 'transaction', 'public.transactions', 'public.transaction'];
        $orders = ['created_at.desc', 'id.desc'];
        $amountCols = ['amount', 'total', 'price', 'nominal', 'gross_amount', 'value'];

        foreach ($tables as $table) {
            $filters = [];
            if (!is_null($kursusId)) {
                $filters[] = 'kursus_id=eq.' . rawurlencode($kursusId);
                $filters[] = "metadata->>kursus_id=eq." . rawurlencode($kursusId);
                $filters[] = "user_metadata->>kursus_id=eq." . rawurlencode($kursusId);
            }
            $filters[] = null;

            foreach ($filters as $filter) {
                foreach ($orders as $order) {
                    try {
                        $base = rtrim($this->url, '/') . '/rest/v1/' . rawurlencode($table);
                        $q = '?select=*&order=' . rawurlencode($order) . '&limit=' . intval($limit);
                        if ($filter) $q .= '&' . $filter;
                        $url = $base . $q;

                        $response = Http::withHeaders([
                            'apikey' => $this->key,
                            'Authorization' => 'Bearer ' . $this->key,
                            'Accept' => 'application/json',
                        ])->get($url);

                        $this->recordResponseInfo('getTransactions', $table, $response);

                        if (!$response->successful()) continue;

                        $body = $response->json();
                        if (!is_array($body)) continue;

                        // Normalisasi transaksi
                        $transactions = [];
                        foreach ($body as $t) {
                            $amount = $t['amount'] ?? $t['total'] ?? $t['price'] ?? $t['nominal'] ?? $t['gross_amount'] ?? $t['value'] ?? null;
                            $normalized = [
                                'id' => $t['id'] ?? null,
                                'kursus_id' => $t['kursus_id'] ?? null,
                                'user_id' => $t['user_id'] ?? null,
                                'amount' => $amount,
                                'created_at' => $t['created_at'] ?? null,
                                'updated_at' => $t['updated_at'] ?? null,
                                'raw' => $t,
                            ];

                            $transactions[] = $normalized;
                        }

                        Log::info('Supabase getTransactions success', [
                            'table' => $table, 'filter' => $filter, 'order' => $order, 'count' => count($transactions)
                        ]);
                        $this->debug['getTransactions'] = ['table'=>$table,'filter'=>$filter,'order'=>$order,'count'=>count($transactions)];
                        return $transactions;
                    } catch (\Exception $e) {
                        Log::debug('Exception in getTransactions', ['table' => $table, 'filter' => $filter, 'err' => $e->getMessage()]);
                        continue;
                    }
                }
            }
        }

        return null;
    }

    /**
     * Hitung jumlah user yang terverifikasi
     *
     * @param mixed|null $kursusId
     * @return int|null jumlah user terverifikasi atau null jika gagal
     */
    public function countVerifiedUsers($kursusId = null): ?int
    {
        if (!$this->url || !$this->key) {
            Log::warning('Supabase config missing for countVerifiedUsers', ['has_url' => !empty($this->url), 'has_key' => !empty($this->key)]);
            return null;
        }

        $tables = ['users', 'user', 'public.users', 'auth.users'];
        $verifyCols = ['email_verified_at', 'confirmed_at', 'email_confirmed_at'];

        foreach ($tables as $table) {
            foreach ($verifyCols as $col) {
                $filters = [];
                if (!is_null($kursusId)) {
                    $filters[] = 'kursus_id=eq.' . rawurlencode($kursusId);
                    $filters[] = "user_metadata->>kursus_id=eq." . rawurlencode($kursusId);
                }
                // add not null filter for verification column
                $filters[] = $col . '=not.is.null';
                $filters[] = null;

                foreach ($filters as $filter) {
                    try {
                        $base = rtrim($this->url, '/') . '/rest/v1/' . $table;
                        $query = '?select=id&limit=0' . ($filter ? ('&' . $filter) : '');
                        $url = $base . $query;

                        $response = Http::withHeaders([
                            'apikey' => $this->key,
                            'Authorization' => 'Bearer ' . $this->key,
                            'Prefer' => 'count=exact',
                            'Accept' => 'application/json',
                        ])->get($url);

                        if (!$response->successful()) {
                            continue;
                        }

                        $contentRange = $response->header('content-range') ?? $response->header('Content-Range');
                        if ($contentRange && preg_match('/\/(\d+)$/', $contentRange, $m)) {
                            $val = (int) $m[1];
                            $this->debug['countVerifiedUsers'] = ['table'=>$table, 'col'=>$col, 'filter'=>$filter, 'count'=>$val];
                            return $val;
                        }

                        $body = $response->json();
                        if (is_array($body)) {
                            return count($body);
                        }
                    } catch (\Exception $e) {
                        Log::debug('Exception in countVerifiedUsers', ['table' => $table, 'col' => $col, 'err' => $e->getMessage()]);
                    }
                }
            }
        }

        return null;
    }

    /**
     * Ambil daftar user terbaru dari Supabase (normalize id,name,email,verified)
     */
    public function getUsers($kursusId = null, int $limit = 10): ?array
    {
        if (!$this->url || !$this->key) {
            Log::warning('Supabase config missing for getUsers', ['has_url' => !empty($this->url), 'has_key' => !empty($this->key)]);
            return null;
        }

        $tables = ['users', 'user', 'public.users', 'auth.users'];
        foreach ($tables as $table) {
            $filters = [];
            if (!is_null($kursusId)) {
                $filters[] = 'kursus_id=eq.' . rawurlencode($kursusId);
                $filters[] = "user_metadata->>kursus_id=eq." . rawurlencode($kursusId);
            }
            $filters[] = null;

            foreach ($filters as $filter) {
                try {
                    $base = rtrim($this->url, '/') . '/rest/v1/' . $table;
                    $q = '?select=id,name,full_name,email,email_verified_at,user_metadata&order=id.desc&limit=' . intval($limit);
                    if ($filter) $q .= '&' . $filter;
                    $url = $base . $q;

                    $response = Http::withHeaders([
                        'apikey' => $this->key,
                        'Authorization' => 'Bearer ' . $this->key,
                        'Accept' => 'application/json',
                    ])->get($url);

                    if (!$response->successful()) continue;

                    $body = $response->json();
                    if (!is_array($body)) continue;

                    $users = [];
                    foreach ($body as $u) {
                        $meta = [];
                        if (isset($u['user_metadata'])) {
                            $meta = is_string($u['user_metadata']) ? json_decode($u['user_metadata'], true) ?? [] : (array)$u['user_metadata'];
                        }
                        $name = $u['name'] ?? $u['full_name'] ?? $meta['full_name'] ?? $meta['name'] ?? null;
                        $verified = !empty($u['email_verified_at']) || !empty($u['confirmed_at'] ?? null) || !empty($u['email_confirmed_at'] ?? null);
                        $users[] = [
                            'id' => $u['id'] ?? null,
                            'name' => $name ?? ($u['email'] ?? null),
                            'email' => $u['email'] ?? null,
                            'verified' => (bool)$verified,
                            'raw' => $u,
                        ];
                    }

                    Log::info('Supabase getUsers success', ['table'=>$table,'filter'=>$filter,'count'=>count($users)]);
                    $this->debug['getUsers'] = ['table'=>$table,'filter'=>$filter,'count'=>count($users)];
                    return $users;
                } catch (\Exception $e) {
                    Log::debug('Exception in getUsers', ['table' => $table, 'err' => $e->getMessage()]);
                    continue;
                }
            }
        }

        return null;
    }

    // Example: record minimal info about last response
    private function recordResponseInfo(string $context, $table, $response)
    {
        try {
            $this->last_response = [
                'context' => $context,
                'table' => $table,
                'status' => $response->status(),
                'body_snippet' => substr((string)$response->body(), 0, 200),
                'headers' => $response->headers(),
            ];
            $this->debug['last_response'] = $this->last_response;
        } catch (\Exception $e) {
            $this->debug['last_response_error'] = $e->getMessage();
        }
    }

    /**
     * Debug helper: check connectivity/permissions to Supabase by probing a few known tables.
     *
     * @param mixed|null $kursusId (optional; used to test filtered queries)
     * @return array status info (ok boolean and details)
     */
    public function debugStatus($kursusId = null): array
    {
        $status = [
            'ok' => false,
            'checked' => [],
            'has_url' => !empty($this->url),
            'has_key' => !empty($this->key),
        ];

        if (!$this->url || !$this->key) {
            $status['reason'] = 'missing_config';
            $this->debug['status_check'] = $status;
            return $status;
        }

        $tables = ['users', 'transactions', 'auth.users'];
        foreach ($tables as $table) {
            try {
                $base = rtrim($this->url, '/') . '/rest/v1/' . rawurlencode($table);
                $q = '?select=id&limit=1';
                if (!is_null($kursusId)) {
                    $q .= '&kursus_id=eq.' . rawurlencode($kursusId);
                }
                $url = $base . $q;

                $response = Http::withHeaders([
                    'apikey' => $this->key,
                    'Authorization' => 'Bearer ' . $this->key,
                    'Accept' => 'application/json',
                    'Prefer' => 'count=exact',
                ])->get($url);

                $this->recordResponseInfo('debug_probe', $table, $response);

                $entry = [
                    'table' => $table,
                    'status' => $response->status(),
                    'body_snippet' => substr((string)$response->body(), 0, 200),
                ];
                $status['checked'][] = $entry;

                if ($response->successful()) {
                    // try extract count if present
                    $contentRange = $response->header('content-range') ?? $response->header('Content-Range');
                    if ($contentRange && preg_match('/\/(\d+)$/', $contentRange, $m)) {
                        $entry['count'] = (int)$m[1];
                        $status['ok'] = true;
                        $status['success_on'] = $entry;
                        $this->debug['status_check'] = $status;
                        return $status;
                    }
                    // success but no content-range: still treat as reachable
                    $status['ok'] = true;
                    $status['success_on'] = $entry;
                    $this->debug['status_check'] = $status;
                    return $status;
                }
            } catch (\Exception $e) {
                $status['checked'][] = ['table' => $table, 'error' => $e->getMessage()];
            }
        }

        $status['reason'] = 'no_table_access_or_permission';
        $this->debug['status_check'] = $status;
        return $status;
    }
}

