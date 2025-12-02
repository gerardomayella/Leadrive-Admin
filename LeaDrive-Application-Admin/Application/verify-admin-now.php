<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Admin;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

echo "\n";
echo "========================================\n";
echo "  VERIFIKASI ADMIN LOGIN - REAL TIME\n";
echo "========================================\n\n";

// 1. Cek semua admin di database
echo "1. ADMIN DI DATABASE:\n";
echo "   -------------------\n";
$admins = DB::table('admin')->get();

if ($admins->isEmpty()) {
    echo "   ❌ TIDAK ADA ADMIN!\n\n";
    echo "   Membuat admin baru...\n";
    
    DB::table('admin')->insert([
        'nama' => 'Admin',
        'email' => 'admin@gmail.com',
        'password' => Hash::make('password'),
        'created_at' => now(),
        'updated_at' => now(),
    ]);
    
    echo "   ✅ Admin berhasil dibuat!\n\n";
    $admins = DB::table('admin')->get();
}

foreach ($admins as $admin) {
    echo "   Email: {$admin->email}\n";
    echo "   Nama: {$admin->nama}\n";
    echo "   ID: {$admin->id}\n";
    
    // Test password
    $passwords = ['password', 'admin123', 'Password'];
    echo "   Test password:\n";
    foreach ($passwords as $pwd) {
        $valid = Hash::check($pwd, $admin->password);
        if ($valid) {
            echo "     ✅ '{$pwd}' - VALID\n";
        }
    }
    echo "\n";
}

// 2. Test Auth::attempt
echo "2. TEST AUTH::ATTEMPT:\n";
echo "   -------------------\n";

$testCredentials = [
    ['email' => 'admin@gmail.com', 'password' => 'password'],
];

foreach ($testCredentials as $cred) {
    echo "   Testing: {$cred['email']} / {$cred['password']}\n";
    
    try {
        $result = Auth::guard('admin')->attempt($cred);
        if ($result) {
            $user = Auth::guard('admin')->user();
            echo "   ✅ LOGIN BERHASIL!\n";
            echo "   Logged in sebagai: {$user->nama}\n";
            Auth::guard('admin')->logout();
        } else {
            echo "   ❌ LOGIN GAGAL\n";
            
            // Debug kenapa gagal
            $admin = Admin::where('email', $cred['email'])->first();
            if (!$admin) {
                echo "   Alasan: Email tidak ditemukan\n";
            } else {
                $pwdMatch = Hash::check($cred['password'], $admin->password);
                if (!$pwdMatch) {
                    echo "   Alasan: Password tidak cocok\n";
                    echo "   Hash di DB: " . substr($admin->password, 0, 30) . "...\n";
                } else {
                    echo "   Alasan: Unknown (padahal email & password cocok)\n";
                }
            }
        }
    } catch (\Exception $e) {
        echo "   ❌ ERROR: {$e->getMessage()}\n";
    }
    echo "\n";
}

// 3. Cek konfigurasi auth
echo "3. KONFIGURASI AUTH:\n";
echo "   -------------------\n";

$guards = config('auth.guards');
if (isset($guards['admin'])) {
    echo "   ✅ Guard 'admin' ada\n";
    echo "      Driver: {$guards['admin']['driver']}\n";
    echo "      Provider: {$guards['admin']['provider']}\n";
} else {
    echo "   ❌ Guard 'admin' TIDAK ADA!\n";
}

$providers = config('auth.providers');
if (isset($providers['admins'])) {
    echo "   ✅ Provider 'admins' ada\n";
    echo "      Driver: {$providers['admins']['driver']}\n";
    echo "      Model: {$providers['admins']['model']}\n";
} else {
    echo "   ❌ Provider 'admins' TIDAK ADA!\n";
}

echo "\n";

// 4. Cek routes
echo "4. ROUTES ADMIN:\n";
echo "   -------------------\n";
$routes = app('router')->getRoutes();
$adminRoutes = [];
foreach ($routes as $route) {
    if (str_starts_with($route->uri(), 'admin/')) {
        $adminRoutes[] = $route->uri();
    }
}

if (empty($adminRoutes)) {
    echo "   ❌ TIDAK ADA ROUTES ADMIN!\n";
} else {
    echo "   ✅ Routes admin ditemukan:\n";
    foreach ($adminRoutes as $uri) {
        echo "      - /{$uri}\n";
    }
}

echo "\n";
echo "========================================\n";
echo "  KESIMPULAN\n";
echo "========================================\n\n";

echo "✅ KREDENSIAL YANG BENAR:\n";
echo "   Email: admin@gmail.com\n";
echo "   Password: password\n\n";

echo "✅ URL LOGIN ADMIN:\n";
echo "   http://127.0.0.1:8000/admin/login\n\n";

echo "⚠️  JANGAN GUNAKAN:\n";
echo "   http://127.0.0.1:8000/ (ini untuk pemilik kursus)\n\n";

echo "========================================\n\n";
