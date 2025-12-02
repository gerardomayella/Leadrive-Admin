# Database Admin - Setup Selesai

## ✅ Yang Sudah Dibuat

### 1. Tabel `admin` di PostgreSQL
Struktur tabel:
- `id_admin` (BIGSERIAL, Primary Key)
- `nama` (VARCHAR 100)
- `email` (VARCHAR 255, UNIQUE)
- `password` (VARCHAR 255, hashed)
- `created_at` (TIMESTAMPTZ)
- `updated_at` (TIMESTAMPTZ)

### 2. Migration
File: `database/migrations/2025_11_04_000000_create_admin_table.php`

### 3. Model Eloquent
File: `app/Models/Admin.php`
- Extends `Authenticatable` (siap untuk login/auth)
- Primary key: `id_admin`
- Mass assignable: `nama`, `email`, `password`
- Password otomatis di-hash

### 4. Seeder (DatabaseSeeder)
File: `database/seeders/DatabaseSeeder.php`
- Membuat akun admin default saat `php artisan db:seed`

### 5. Akun Admin Default
**Email:** `admin@gmail.com`  
**Password:** (sudah di-hash di database)

## 🔧 PHP Extensions yang Diaktifkan
Di `D:\Xampp\php\php.ini`:
- `extension=pdo_pgsql` ✅
- `extension=pgsql` ✅
- `extension=mbstring` ✅

## 📝 Cara Menggunakan

### Login Admin (Contoh Controller)
```php
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

// Cek login
$admin = Admin::where('email', $request->email)->first();

if ($admin && Hash::check($request->password, $admin->password)) {
    // Login berhasil
    auth()->guard('admin')->login($admin);
}
```

### Membuat Admin Baru
```php
use App\Models\Admin;

Admin::create([
    'nama' => 'Admin Baru',
    'email' => 'admin2@example.com',
    'password' => bcrypt('password123'),
]);
```

### Query Admin
```php
// Semua admin
$admins = Admin::all();

// Cari by email
$admin = Admin::where('email', 'admin@gmail.com')->first();

// Update
$admin->update(['nama' => 'Admin Updated']);
```

## 🔐 Keamanan
- Password disimpan dalam bentuk hash (bcrypt)
- Email bersifat UNIQUE
- Model sudah siap untuk Laravel Authentication

## 📊 Status Migration
Jalankan `php artisan migrate:status` untuk melihat status semua migration.

---
**Dibuat:** 4 November 2025  
**Database:** PostgreSQL (Supabase)
