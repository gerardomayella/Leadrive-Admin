<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Admin;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

echo "=== DEBUG ADMIN LOGIN ===" . PHP_EOL . PHP_EOL;

// 1. Cek data admin di database
echo "1. Data admin di database:" . PHP_EOL;
$admins = DB::table('admin')->get();
foreach ($admins as $admin) {
    echo "   - ID: {$admin->id}" . PHP_EOL;
    echo "     Email: {$admin->email}" . PHP_EOL;
    echo "     Nama: {$admin->name}" . PHP_EOL;
    echo "     Password Hash: " . substr($admin->password, 0, 20) . "..." . PHP_EOL;
    echo "     Created: {$admin->created_at}" . PHP_EOL;
    echo PHP_EOL;
}

// 2. Test password dengan berbagai kemungkinan
echo "2. Test password hash:" . PHP_EOL;
$admin = Admin::first();
if ($admin) {
    $passwords = ['password', 'admin123', 'Password', 'PASSWORD'];
    foreach ($passwords as $pwd) {
        $valid = Hash::check($pwd, $admin->password);
        echo "   Password '{$pwd}': " . ($valid ? "✓ VALID" : "✗ INVALID") . PHP_EOL;
    }
} else {
    echo "   ✗ Tidak ada admin di database!" . PHP_EOL;
}

echo PHP_EOL;

// 3. Cek Model Admin
echo "3. Cek Model Admin:" . PHP_EOL;
$adminModel = Admin::where('email', 'admin@gmail.com')->first();
if ($adminModel) {
    echo "   ✓ Model Admin ditemukan" . PHP_EOL;
    echo "   - Primary Key: {$adminModel->getKeyName()}" . PHP_EOL;
    echo "   - Table: {$adminModel->getTable()}" . PHP_EOL;
    echo "   - Hidden: " . implode(', ', $adminModel->getHidden()) . PHP_EOL;
    echo "   - Fillable: " . implode(', ', $adminModel->getFillable()) . PHP_EOL;
} else {
    echo "   ✗ Model Admin tidak ditemukan!" . PHP_EOL;
}

echo PHP_EOL;

// 4. Test Auth::attempt
echo "4. Test Auth::attempt dengan guard admin:" . PHP_EOL;
$credentials = [
    'email' => 'admin@gmail.com',
    'password' => 'password',
];

try {
    $result = Auth::guard('admin')->attempt($credentials);
    if ($result) {
        echo "   ✓ Auth::attempt BERHASIL" . PHP_EOL;
        $user = Auth::guard('admin')->user();
        echo "   - Logged in sebagai: {$user->nama}" . PHP_EOL;
        Auth::guard('admin')->logout();
    } else {
        echo "   ✗ Auth::attempt GAGAL" . PHP_EOL;
        
        // Debug lebih lanjut
        echo PHP_EOL;
        echo "   Debug tambahan:" . PHP_EOL;
        
        // Cek apakah email ada
        $adminByEmail = Admin::where('email', $credentials['email'])->first();
        if ($adminByEmail) {
            echo "   - Email ditemukan: ✓" . PHP_EOL;
            echo "   - Password match: " . (Hash::check($credentials['password'], $adminByEmail->password) ? "✓" : "✗") . PHP_EOL;
            
            // Cek remember_token column
            $columns = DB::select("SELECT column_name FROM information_schema.columns WHERE table_name = 'admin'");
            echo "   - Kolom di tabel admin: " . PHP_EOL;
            foreach ($columns as $col) {
                echo "     * {$col->column_name}" . PHP_EOL;
            }
        } else {
            echo "   - Email tidak ditemukan: ✗" . PHP_EOL;
        }
    }
} catch (\Exception $e) {
    echo "   ✗ ERROR: " . $e->getMessage() . PHP_EOL;
    echo "   File: " . $e->getFile() . ":" . $e->getLine() . PHP_EOL;
}

echo PHP_EOL;

// 5. Cek config auth
echo "5. Cek konfigurasi auth:" . PHP_EOL;
$guards = config('auth.guards');
if (isset($guards['admin'])) {
    echo "   ✓ Guard 'admin' terdaftar" . PHP_EOL;
    echo "   - Driver: {$guards['admin']['driver']}" . PHP_EOL;
    echo "   - Provider: {$guards['admin']['provider']}" . PHP_EOL;
} else {
    echo "   ✗ Guard 'admin' tidak terdaftar!" . PHP_EOL;
}

$providers = config('auth.providers');
if (isset($providers['admins'])) {
    echo "   ✓ Provider 'admins' terdaftar" . PHP_EOL;
    echo "   - Driver: {$providers['admins']['driver']}" . PHP_EOL;
    echo "   - Model: {$providers['admins']['model']}" . PHP_EOL;
} else {
    echo "   ✗ Provider 'admins' tidak terdaftar!" . PHP_EOL;
}

echo PHP_EOL;
echo "=== DEBUG SELESAI ===" . PHP_EOL;
