<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Instruktur extends Model
{
    use HasFactory;

    // Nama tabel di Supabase
    protected $table = 'instruktur';

    // Primary key yang digunakan
    protected $primaryKey = 'id_instruktur';

    // Jika primary key bukan auto increment integer default 'id'
    public $incrementing = true;

    // Karena tipe id kamu int8 (bigint), ubah keyType jadi 'int'
    protected $keyType = 'int';

    // Kolom yang bisa diisi lewat mass assignment
    protected $fillable = [
        'nama',
        'email',
        'password',
        'nomor_sim',
        'status_aktif',
        'id_kursus',
        'foto_profil',
    ];

    // Jika tabel kamu tidak punya kolom created_at dan updated_at
    public $timestamps = false;
}
