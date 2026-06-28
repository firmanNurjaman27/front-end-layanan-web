<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservasi extends Model
{
    protected $table = 'reservasi';

    protected $fillable = [
        'nama',
        'alamat',
        'no_whatsapp',
        'tanggal',
        'destinasi_id',
        'pj',
        'jumlah',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    // Relasi ke destinasi
    public function destinasi()
    {
        return $this->belongsTo(Destinasi::class, 'destinasi_id');
    }
}
