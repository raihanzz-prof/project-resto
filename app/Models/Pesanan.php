<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;


use Illuminate\Database\Eloquent\Model;

class Pesanan extends Model
{
    use HasFactory;
    protected $table = 'pesanans';
    protected $primaryKey = 'idpesanan';
    protected $fillable = ['idmenu', 'idpelanggan', 'jumlah', 'iduser', 'idmeja'];

    // Relasi ke Menu
    public function menu()
    {
        return $this->belongsTo(Menu::class, 'idmenu', 'idmenu');
    }

    // Relasi ke Pelanggan
    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'idpelanggan', 'idpelanggan');
    }

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class, 'iduser', );
    }

    public function meja()      
    {
        return $this->belongsTo(Meja::class, 'idmeja', 'idmeja'); 
    }

    // Relasi ke Transaksi
    public function transaksi()
    {
        return $this->hasOne(Transaksi::class, 'idpesanan', 'idpesanan');
    }
    public function details(){
        return $this->hasMany(detailPesanan::class, 'idpesanan', 'idpesanan');
    }
}
