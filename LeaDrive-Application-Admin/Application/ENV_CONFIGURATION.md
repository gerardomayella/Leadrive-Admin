# Konfigurasi Environment Variables (.env)

## Variabel Database Supabase (Sudah Ada)

Jika Anda sudah punya koneksi database Supabase, Anda mungkin sudah punya variabel berikut:

```env
DB_CONNECTION=pgsql
DB_HOST=db.xxxxx.supabase.co
DB_PORT=5432
DB_DATABASE=postgres
DB_USERNAME=postgres
DB_PASSWORD=your-password
# atau
DB_URL=postgresql://postgres:password@db.xxxxx.supabase.co:5432/postgres
```

## Variabel Supabase Storage (Tambahkan Ini)

### 1. SUPABASE_KEY (WAJIB)

Ini adalah **anon public key** atau **service_role key** dari project Supabase Anda.

```env
SUPABASE_KEY=eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...
```

**Cara mendapatkan:**

1. Buka Supabase Dashboard
2. Pergi ke **Settings** → **API**
3. Copy **anon public** key (untuk public access) atau **service_role** key (untuk full access)

### 2. SUPABASE_URL (OPSIONAL - Auto-detect)

Jika tidak di-set, sistem akan otomatis detect dari `DB_HOST` atau `DB_URL`.

```env
SUPABASE_URL=https://xxxxx.supabase.co
```

**Format:**

-   Jika `DB_HOST=db.xxxxx.supabase.co`, maka URL akan otomatis: `https://xxxxx.supabase.co`
-   Jika `DB_URL` berisi `@xxxxx.supabase.co`, maka URL akan otomatis: `https://xxxxx.supabase.co`

### 3. SUPABASE_STORAGE_URL (OPSIONAL - Auto-generate)

Jika tidak di-set, akan otomatis di-generate dari `SUPABASE_URL` dengan menambahkan `/storage/v1`.

```env
SUPABASE_STORAGE_URL=https://xxxxx.supabase.co/storage/v1
```

### 4. Nama Bucket (OPSIONAL - Default: kursus & instruktur)

Jika nama bucket Anda berbeda:

```env
SUPABASE_BUCKET_KURSUS=kursus
SUPABASE_BUCKET_INSTRUKTUR=instruktur
```

### 5. Max File Size (OPSIONAL - Default: 5MB)

```env
SUPABASE_MAX_FILE_SIZE=5242880
```

## Contoh Lengkap di .env

```env
# ============================================
# Database Supabase (Sudah Ada)
# ============================================
DB_CONNECTION=pgsql
DB_HOST=db.abcdefghijklmnop.supabase.co
DB_PORT=5432
DB_DATABASE=postgres
DB_USERNAME=postgres
DB_PASSWORD=your-secure-password

# Atau menggunakan DB_URL
# DB_URL=postgresql://postgres:password@db.abcdefghijklmnop.supabase.co:5432/postgres

# ============================================
# Supabase Storage (TAMBAHKAN INI)
# ============================================
# Wajib: Key untuk akses Supabase API
SUPABASE_KEY=eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImFiY2RlZmdoaWprbG1ub3AiLCJyb2xlIjoiYW5vbiIsImlhdCI6MTY0NTcyOTgwMiwiZXhwIjoxOTYxMzA1ODAyfQ.xxxxx

# Opsional: URL (auto-detect dari DB_HOST jika tidak di-set)
# SUPABASE_URL=https://abcdefghijklmnop.supabase.co

# Opsional: Storage URL (auto-generate dari SUPABASE_URL jika tidak di-set)
# SUPABASE_STORAGE_URL=https://abcdefghijklmnop.supabase.co/storage/v1

# Opsional: Nama bucket (default: kursus & instruktur)
# SUPABASE_BUCKET_KURSUS=kursus
# SUPABASE_BUCKET_INSTRUKTUR=instruktur

# Opsional: Max file size dalam bytes (default: 5242880 = 5MB)
# SUPABASE_MAX_FILE_SIZE=5242880
```

## Minimal Configuration (Paling Sedikit)

**Hanya perlu 1 variabel:**

```env
SUPABASE_KEY=your-supabase-anon-key
```

Sistem akan otomatis:

-   Extract URL dari `DB_HOST` (jika format `db.xxxxx.supabase.co`)
-   Generate storage URL dari URL
-   Gunakan default bucket names (kursus & instruktur)

## Catatan Penting

1. **SUPABASE_KEY** adalah satu-satunya variabel wajib
2. URL akan otomatis di-detect dari `DB_HOST` jika format Supabase
3. Pastikan bucket `kursus` dan `instruktur` sudah dibuat di Supabase Dashboard → Storage
4. Gunakan **anon public key** untuk public bucket atau **service_role key** untuk private bucket
5. File yang di-upload akan disimpan di struktur folder sesuai config

## Setup Bucket di Supabase

1. Buka Supabase Dashboard → **Storage**
2. Klik **New bucket**
3. Buat bucket: `kursus` (set sebagai Public atau Private)
4. Buat bucket: `instruktur` (set sebagai Public atau Private)
5. Folder akan dibuat otomatis saat upload pertama kali
