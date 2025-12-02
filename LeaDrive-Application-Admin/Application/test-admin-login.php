<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

echo "=== TEST ADMIN LOGIN ===" . PHP_EOL . PHP_EOL;

// Test 1: Cek admin di database
echo "1. Mencari admin dengan email: admin@gmail.com" . PHP_EOL;
$admin = Admin::where('email', 'admin@gmail.com')->first();

if ($admin) {
    echo "   ✓ Admin ditemukan!" . PHP_EOL;
    echo "   - ID: " . $admin->id . PHP_EOL;
    echo "   - Nama: " . $admin->nama . PHP_EOL;
    echo "   - Email: " . $admin->email . PHP_EOL;
} else {
    echo "   ✗ Admin tidak ditemukan!" . PHP_EOL;
    exit(1);
}

echo PHP_EOL;

// Test 2: Verifikasi password
echo "2. Verifikasi password 'password'" . PHP_EOL;
$passwordValid = Hash::check('password', $admin->password);

if ($passwordValid) {
    echo "   ✓ Password VALID!" . PHP_EOL;
} else {
    echo "   ✗ Password TIDAK VALID!" . PHP_EOL;
    exit(1);
}

echo PHP_EOL;

// Test 3: Simulasi login dengan Auth::attempt
echo "3. Simulasi login dengan Auth::attempt" . PHP_EOL;
$credentials = [
    'email' => 'admin@gmail.com',
    'password' => 'password',
];

if (Auth::guard('admin')->attempt($credentials)) {
    echo "   ✓ Login BERHASIL!" . PHP_EOL;
    $loggedAdmin = Auth::guard('admin')->user();
    echo "   - Logged in sebagai: " . $loggedAdmin->nama . PHP_EOL;
    Auth::guard('admin')->logout();
} else {
    echo "   ✗ Login GAGAL!" . PHP_EOL;
    exit(1);
}

echo PHP_EOL;
echo "=== SEMUA TEST BERHASIL ===" . PHP_EOL;
echo PHP_EOL;
echo "Kredensial Admin:" . PHP_EOL;
echo "Email: admin@gmail.com" . PHP_EOL;
echo "Password: password" . PHP_EOL;
echo PHP_EOL;
echo "URL Login: http://localhost:8000/admin/login" . PHP_EOL;
