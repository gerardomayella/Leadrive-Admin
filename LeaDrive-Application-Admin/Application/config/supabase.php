<?php

/*
|--------------------------------------------------------------------------
| Supabase Configuration
|--------------------------------------------------------------------------
|
| Konfigurasi untuk Supabase Storage API
| 
| Auto-detect dari DB_HOST jika SUPABASE_URL tidak di-set
| Format DB_HOST Supabase: db.xxxxx.supabase.co
|
*/

// Helper function untuk extract Supabase URL dari DB config
$getSupabaseUrl = function() {
    // Cek SUPABASE_URL dulu
    $supabaseUrl = env('SUPABASE_URL');
    if ($supabaseUrl) {
        return rtrim($supabaseUrl, '/');
    }
    
    // Cek DB_HOST untuk auto-detect
    $dbHost = env('DB_HOST');
    if ($dbHost && str_contains($dbHost, '.supabase.co')) {
        // Extract project ID dari DB_HOST: db.xxxxx.supabase.co
        $parts = explode('.', $dbHost);
        if (count($parts) >= 2 && $parts[0] === 'db') {
            $projectId = $parts[1];
            return 'https://' . $projectId . '.supabase.co';
        }
    }
    
    // Cek DB_URL jika format connection string
    $dbUrl = env('DB_URL');
    if ($dbUrl && preg_match('/@([^.]+)\.supabase\.co/', $dbUrl, $matches)) {
        $projectId = $matches[1];
        return 'https://' . $projectId . '.supabase.co';
    }
    
    return '';
};

$supabaseUrl = $getSupabaseUrl();
$storageUrl = env('SUPABASE_STORAGE_URL');
if (!$storageUrl && $supabaseUrl) {
    $storageUrl = $supabaseUrl . '/storage/v1';
}

return [
    /*
    |--------------------------------------------------------------------------
    | Supabase API Configuration
    |--------------------------------------------------------------------------
    |
    | URL dan key untuk akses Supabase API
    |
    */
    'url' => $supabaseUrl,
    'key' => env('SUPABASE_KEY', ''),
    'storage_url' => $storageUrl,
    
    /*
    |--------------------------------------------------------------------------
    | Storage Buckets
    |--------------------------------------------------------------------------
    |
    | Daftar bucket yang digunakan di Supabase Storage
    | Pastikan bucket ini sudah dibuat di Supabase Dashboard > Storage
    |
    */
    'buckets' => [
        'kursus' => env('SUPABASE_BUCKET_KURSUS', 'kursus'),
        'instruktur' => env('SUPABASE_BUCKET_INSTRUKTUR', 'instruktur'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Folder Structure
    |--------------------------------------------------------------------------
    |
    | Struktur folder dalam bucket sesuai dengan struktur di Supabase Storage
    |
    */
    'folders' => [
        'kursus' => [
            'ktp' => 'ktp',
            'izin' => 'izin',
            'dokumenlain' => 'dokumenlain',
            'fotokursus' => 'fotokursus',
        ],
        'instruktur' => [
            'sim' => 'sim',
            'fotoinstruktur' => 'fotoinstruktur',
            'sertifikat' => 'sertifikat',
        ],
    ],
    
    /*
    |--------------------------------------------------------------------------
    | Upload Settings
    |--------------------------------------------------------------------------
    |
    | Konfigurasi untuk upload file
    |
    */
    'upload' => [
        'max_file_size' => env('SUPABASE_MAX_FILE_SIZE', 5242880), // 5MB in bytes
        'allowed_mime_types' => [
            'image/jpeg',
            'image/jpg',
            'image/png',
            'application/pdf',
        ],
        'allowed_extensions' => ['jpg', 'jpeg', 'png', 'pdf'],
    ],
];

