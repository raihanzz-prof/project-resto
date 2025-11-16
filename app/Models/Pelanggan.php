<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;

class Pelanggan extends Model
{
    use HasFactory;

    protected $table = 'pelanggans';
    protected $primaryKey = 'idpelanggan';
    protected $fillable = ['namapelanggan', 'jeniskelamin', 'nohp', 'alamat'];

    // Relasi ke Pesanan
    public function pesanans()
    {
        return $this->hasMany(Pesanan::class, 'idpelanggan');
    }
}
