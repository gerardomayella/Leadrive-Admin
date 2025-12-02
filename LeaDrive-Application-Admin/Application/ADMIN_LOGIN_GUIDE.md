# 🔐 Panduan Login Admin - LeadDrive

## ✅ Masalah yang Sudah Diperbaiki

### 1. **Kolom `remember_token` Ditambahkan**
- Tabel `admin` sekarang memiliki kolom `remember_token` yang diperlukan Laravel Auth
- Migration sudah diupdate untuk konsistensi

### 2. **Logging & Debug Ditambahkan**
- Controller sekarang memberikan pesan error yang lebih spesifik:
  - "Email tidak ditemukan" - jika email salah
  - "Password salah" - jika password salah
  - "Login gagal" - jika ada masalah lain

### 3. **Semua Test Berhasil**
- ✓ Admin ditemukan di database
- ✓ Password hash valid
- ✓ Auth::attempt berhasil

---

## 🚀 Cara Login Admin

### **Kredensial Login**
```
Email: admin@gmail.com
Password: password
```

### **URL Login**
```
http://127.0.0.1:8000/admin/login
```

### **Langkah-langkah:**

1. **Pastikan server berjalan**
   ```bash
   php artisan serve
   ```

2. **Buka browser dan akses:**
   ```
   http://127.0.0.1:8000/admin/login
   ```

3. **Masukkan kredensial:**
   - Email: `admin@gmail.com`
   - Password: `password`

4. **Klik "Login sebagai Admin"**

5. **Anda akan diarahkan ke Dashboard Admin**

---

## 🔍 Troubleshooting

### **Jika Login Gagal:**

#### 1. **Cek Log Laravel**
```bash
tail -f storage/logs/laravel.log
```

Cari pesan error seperti:
- `Admin not found` - Email salah
- `Invalid password` - Password salah
- `Auth::attempt failed` - Masalah konfigurasi

#### 2. **Test Kredensial Manual**
Jalankan script test:
```bash
php test-admin-login.php
```

Jika test berhasil tapi login di browser gagal, kemungkinan masalah:
- CSRF token tidak valid
- Session tidak berfungsi
- Cache perlu dibersihkan

#### 3. **Clear Cache**
```bash
php artisan config:clear
php artisan route:clear
php artisan cache:clear
php artisan view:clear
```

#### 4. **Cek Struktur Tabel**
```bash
php check-admin-table.php
```

Pastikan kolom berikut ada:
- `id_admin` (Primary Key)
- `nama`
- `email` (UNIQUE)
- `password`
- `remember_token`
- `created_at`
- `updated_at`

#### 5. **Reset Password Admin**
Jika lupa password, jalankan:
```bash
php artisan tinker
```

Lalu ketik:
```php
$admin = App\Models\Admin::where('email', 'admin@gmail.com')->first();
$admin->password = Hash::make('password');
$admin->save();
echo "Password berhasil direset!";
exit;
```

---

## 📊 Verifikasi Login Berhasil

Setelah login berhasil, Anda akan:

1. **Diarahkan ke Dashboard Admin**
   - URL: `http://127.0.0.1:8000/admin/dashboard`

2. **Melihat Informasi:**
   - Nama admin di navbar
   - Badge "ADMIN"
   - Statistik dashboard
   - Informasi akun

3. **Session Tersimpan:**
   - Anda tetap login meskipun refresh halaman
   - Bisa logout dengan tombol "Logout"

---

## 🛡️ Keamanan

### **Password:**
- Disimpan dalam bentuk hash (bcrypt)
- Tidak pernah disimpan dalam plaintext
- Minimal 8 karakter (recommended)

### **Session:**
- Session-based authentication
- CSRF protection aktif
- Session regenerate setelah login

### **Middleware:**
- `auth:admin` - Proteksi routes admin
- `guest:admin` - Redirect jika sudah login

---

## 📝 Membuat Admin Baru

### Via Tinker:
```bash
php artisan tinker
```

```php
App\Models\Admin::create([
    'nama' => 'Admin Baru',
    'email' => 'admin2@example.com',
    'password' => bcrypt('password123'),
]);
```

### Via Seeder:
Edit `database/seeders/DatabaseSeeder.php`:

```php
DB::table('admin')->insert([
    'nama' => 'Admin Baru',
    'email' => 'admin2@example.com',
    'password' => Hash::make('password123'),
    'created_at' => now(),
    'updated_at' => now(),
]);
```

Lalu jalankan:
```bash
php artisan db:seed
```

---

## 🔧 File Penting

### **Controller:**
- `app/Http/Controllers/Auth/AdminAuthController.php`

### **Model:**
- `app/Models/Admin.php`

### **Views:**
- `resources/views/admin/login.blade.php`
- `resources/views/admin/dashboard.blade.php`

### **Routes:**
- `routes/web.php` (prefix: `/admin`)

### **Config:**
- `config/auth.php` (guard: `admin`, provider: `admins`)

### **Migration:**
- `database/migrations/2025_11_04_000000_create_admin_table.php`

---

## 📞 Support

Jika masih ada masalah:

1. Jalankan debug script: `php debug-admin.php`
2. Cek log: `storage/logs/laravel.log`
3. Pastikan semua extension PHP aktif (pdo_pgsql, pgsql, mbstring)
4. Pastikan database PostgreSQL berjalan

---

**Terakhir diupdate:** 4 November 2025  
**Status:** ✅ Siap Digunakan
