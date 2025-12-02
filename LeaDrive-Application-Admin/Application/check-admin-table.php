<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== STRUKTUR TABEL ADMIN ===" . PHP_EOL . PHP_EOL;

$columns = DB::select("
    SELECT column_name, data_type, is_nullable, column_default
    FROM information_schema.columns 
    WHERE table_name = 'admin' 
    ORDER BY ordinal_position
");

echo "Kolom yang ada:" . PHP_EOL;
foreach ($columns as $col) {
    echo "- {$col->column_name} ({$col->data_type})" . 
         ($col->is_nullable === 'NO' ? ' NOT NULL' : ' NULL') . PHP_EOL;
}

echo PHP_EOL;

// Cek apakah remember_token ada
$hasRememberToken = false;
foreach ($columns as $col) {
    if ($col->column_name === 'remember_token') {
        $hasRememberToken = true;
        break;
    }
}

if ($hasRememberToken) {
    echo "✓ Kolom 'remember_token' sudah ada" . PHP_EOL;
} else {
    echo "✗ Kolom 'remember_token' BELUM ada" . PHP_EOL;
    echo PHP_EOL;
    echo "Menambahkan kolom remember_token..." . PHP_EOL;
    
    try {
        DB::statement("ALTER TABLE admin ADD COLUMN remember_token VARCHAR(100) NULL");
        echo "✓ Kolom remember_token berhasil ditambahkan!" . PHP_EOL;
    } catch (\Exception $e) {
        echo "✗ Error: " . $e->getMessage() . PHP_EOL;
    }
}
