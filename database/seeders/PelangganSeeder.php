<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pelanggan;

class PelangganSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            ['namapelanggan' => 'Andi',   'jeniskelamin' => 1, 'nohp' => '081234567890', 'alamat' => 'Jl. Merpati No. 12'],
            ['namapelanggan' => 'Siti',   'jeniskelamin' => 0, 'nohp' => '081298765432', 'alamat' => 'Jl. Rajawali No. 3'],
            ['namapelanggan' => 'Budi',   'jeniskelamin' => 1, 'nohp' => '085712345678', 'alamat' => 'Jl. Kenari No. 45'],
            ['namapelanggan' => 'Rina',   'jeniskelamin' => 0, 'nohp' => '087812341234', 'alamat' => 'Jl. Melati No. 8'],
        ];

        foreach ($items as $row) {
            Pelanggan::firstOrCreate(
                ['namapelanggan' => $row['namapelanggan']],
                [
                    'jeniskelamin' => $row['jeniskelamin'],
                    'nohp'         => $row['nohp'],
                    'alamat'       => $row['alamat'],
                ]
            );
        }
    }
}
