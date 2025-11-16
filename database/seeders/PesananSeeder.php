<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Menu;
use App\Models\Pelanggan;
use App\Models\Pesanan;

class PesananSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil user waiter (dibuat oleh UserSeeder sebelumnya)
        $waiter = User::where('role', 'waiter')->first();

        if (!$waiter) {
            $this->command->warn('Tidak ada user role=waiter. Jalankan UserSeeder dulu.');
            return;
        }

        // Ambil beberapa menu & pelanggan
        $nasiGoreng = Menu::firstWhere('namamenu', 'Nasi Goreng');
        $mieAyam    = Menu::firstWhere('namamenu', 'Mie Ayam');
        $ayamBakar  = Menu::firstWhere('namamenu', 'Ayam Bakar');

        $andi = Pelanggan::firstWhere('namapelanggan', 'Andi');
        $siti = Pelanggan::firstWhere('namapelanggan', 'Siti');
        $budi = Pelanggan::firstWhere('namapelanggan', 'Budi');

        // Safety check sederhana
        if (!$nasiGoreng || !$mieAyam || !$ayamBakar || !$andi || !$siti || !$budi) {
            $this->command->warn('Menu/Pelanggan belum lengkap. Jalankan MenuSeeder & PelangganSeeder.');
            return;
        }

        $data = [
            ['idmenu' => $nasiGoreng->idmenu, 'idpelanggan' => $andi->idpelanggan, 'jumlah' => 2, 'iduser' => $waiter->iduser],
            ['idmenu' => $mieAyam->idmenu,    'idpelanggan' => $siti->idpelanggan, 'jumlah' => 1, 'iduser' => $waiter->iduser],
            ['idmenu' => $ayamBakar->idmenu,  'idpelanggan' => $budi->idpelanggan, 'jumlah' => 3, 'iduser' => $waiter->iduser],
        ];

        foreach ($data as $row) {
            Pesanan::create($row);
        }
    }
}
