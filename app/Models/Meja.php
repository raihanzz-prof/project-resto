<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;

class Meja extends Model
{
    use HasFactory;

    protected $table = 'meja';
    protected $primaryKey = 'idmeja';

    protected $fillable = [
        'nomormeja',
        'status',
        'keterangan',
    ];

    // Contoh relasi ke Pesanan (kalau sudah tambahkan kolom idmeja di pesanan)
    // public function pesanans()
    // {
    //     return $this->hasMany(Pesanan::class, 'idmeja');
    // }
}
