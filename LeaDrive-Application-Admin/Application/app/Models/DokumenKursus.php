<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DokumenKursus extends Model
{
    use HasFactory;

    protected $table = 'dokumen_kursus';
    protected $primaryKey = 'id';

    protected $keyType = 'int';
    public $incrementing = true;

    protected $fillable = [
        'ktp',
        'izin_usaha', // lowercase untuk match dengan database
        'sertif_instruktur',
        'dokumen_legal',
        'id_request',
    ];

    // Nonaktifkan timestamps karena tabel tidak punya created_at/updated_at
    public $timestamps = false;

    /**
     * Relasi ke tabel Request
     */
    public function request()
    {
        return $this->belongsTo(Request::class, 'id_request', 'id_request');
    }
}

