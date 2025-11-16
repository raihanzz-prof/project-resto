<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class detailPesanan extends Model
{
    protected $table = 'detail_pesanans';
    protected $fillable = [
        'idpesanan',
        'idmenu',
        'jumlah'
    ];
    public function pesanan(){
        return $this->belongsTo(Pesanan::class, 'idpesanan');
    }
    public function menu(){
        return $this->belongsTo(Menu::class, 'idmenu');
    }
}
