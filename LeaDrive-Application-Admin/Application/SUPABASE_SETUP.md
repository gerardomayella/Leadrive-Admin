# Konfigurasi Supabase Storage

## Variabel yang perlu ditambahkan di `.env`

Karena Anda sudah punya koneksi database Supabase, Anda hanya perlu menambahkan **2 variabel** untuk Supabase Storage:

### 1. SUPABASE_KEY (Wajib)

Ini adalah **anon key** atau **service role key** dari project Supabase Anda.

**Cara mendapatkan:**

1. Buka project Supabase di dashboard
2. Pergi ke **Settings** → **API**
3. Copy **anon public** key (untuk public access) atau **service_role** key (untuk full access)

```env
SUPABASE_KEY=eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...
```

### 2. SUPABASE_URL (Opsional - jika berbeda dari DB_URL)

Jika URL Supabase Anda berbeda dari `DB_URL` yang sudah ada, tambahkan ini. Jika sama, bisa di-skip.

**Format URL Supabase:**

```
https://[project-id].supabase.co
```

```env
SUPABASE_URL=https://your-project-id.supabase.co
```

### 3. SUPABASE_STORAGE_URL (Opsional)

Jika tidak di-set, akan otomatis di-generate dari `SUPABASE_URL` atau `DB_URL` dengan menambahkan `/storage/v1`.

```env
SUPABASE_STORAGE_URL=https://your-project-id.supabase.co/storage/v1
```

### 4. Bucket Names (Opsional - sudah ada default)

Jika nama bucket Anda berbeda, bisa di-customize:

```env
SUPABASE_BUCKET_KURSUS=kursus
SUPABASE_BUCKET_INSTRUKTUR=instruktur
```

## Contoh lengkap di `.env`

```env
# Database Supabase (sudah ada)
DB_CONNECTION=pgsql
DB_HOST=db.your-project-id.supabase.co
DB_PORT=5432
DB_DATABASE=postgres
DB_USERNAME=postgres
DB_PASSWORD=your-password
DB_URL=postgresql://postgres:password@db.your-project-id.supabase.co:5432/postgres

# Supabase Storage (tambahkan ini)
SUPABASE_URL=https://your-project-id.supabase.co
SUPABASE_KEY=eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...
# SUPABASE_STORAGE_URL akan otomatis: https://your-project-id.supabase.co/storage/v1
```

## Catatan Penting

1. **SUPABASE_KEY** adalah yang paling penting - tanpa ini upload tidak akan bekerja
2. Jika `DB_URL` sudah berisi URL Supabase project Anda, maka `SUPABASE_URL` bisa di-skip
3. Service akan otomatis extract URL dari `DB_URL` jika `SUPABASE_URL` tidak di-set
4. Pastikan bucket `kursus` dan `instruktur` sudah dibuat di Supabase Storage dashboard
5. Pastikan bucket sudah di-set sebagai **public** atau gunakan **service_role key** untuk upload

## Setup Bucket di Supabase Dashboard

1. Buka Supabase Dashboard → **Storage**
2. Klik **New bucket**
3. Buat bucket dengan nama: `kursus`
4. Buat bucket dengan nama: `instruktur`
5. Set bucket sebagai **Public** (jika ingin akses langsung) atau **Private** (jika menggunakan service_role key)
