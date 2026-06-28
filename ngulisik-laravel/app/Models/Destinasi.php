<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Destinasi extends Model
{
    protected $table = 'destinasi';

    protected $fillable = [
        'nama',
        'deskripsi',
        'harga',
        'gambar',
    ];

    // Relasi ke reservasi
    public function reservasi()
    {
        return $this->hasMany(Reservasi::class, 'destinasi_id');
    }

    // Helper: format harga ke Rupiah
    public function getHargaFormatAttribute(): string
    {
        return 'RP. ' . number_format($this->harga, 0, ',', '.');
    }
}
