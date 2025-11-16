<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    protected $table = 'menus';
    protected $primaryKey = 'idmenu';
    protected $fillable = ['namamenu', 'harga'];

    // Relasi ke Pesanan
    public function pesanans()
    {
        return $this->hasMany(Pesanan::class, 'idmenu');
    }
}
