<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'paket_kursus_id',
        'amount',
        'status',
        'payment_method',
        'payment_proof',
        'transaction_date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function paketKursus()
    {
        return $this->belongsTo(PaketKursus::class, 'paket_kursus_id', 'id_paket');
    }
}
