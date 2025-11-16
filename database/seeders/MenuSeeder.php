<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Menu;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            ['namamenu' => 'Nasi Goreng',   'harga' => 22000],
            ['namamenu' => 'Mie Ayam',      'harga' => 18000],
            ['namamenu' => 'Ayam Bakar',    'harga' => 28000],
            ['namamenu' => 'Sate Ayam',     'harga' => 25000],
            ['namamenu' => 'Es Teh Manis',  'harga' => 6000],
            ['namamenu' => 'Es Jeruk',      'harga' => 8000],
        ];

        foreach ($items as $row) {
            Menu::firstOrCreate(
                ['namamenu' => $row['namamenu']],
                ['harga' => $row['harga']]
            );
        }
    }
}
