<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaketKursus extends Model
{
    use HasFactory;

    protected $table = 'paket_kursus';
    protected $primaryKey = 'id_paket';

    protected $keyType = 'int';
    public $incrementing = true;

    protected $fillable = [
        'nama_paket',
        'harga',
        'durasi_jam',
        'deskripsi',
        'id_request',
    ];

    
}

