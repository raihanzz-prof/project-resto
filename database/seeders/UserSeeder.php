<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin
        User::create([
            'namauser' => 'Admin Utama',
            'email' => 'admin@restorehan.com',
            'password' => Hash::make('12345678'),
            'role' => 'admin',
        ]);

        // Waiter
        User::create([
            'namauser' => 'Waiter A',
            'email' => 'waiter@restorehan.com',
            'password' => Hash::make('12345678'),
            'role' => 'waiter',
        ]);

        // Kasir
        User::create([
            'namauser' => 'Kasir A',
            'email' => 'kasir@restorehan.com',
            'password' => Hash::make('12345678'),
            'role' => 'kasir',
        ]);

        // Owner
        User::create([
            'namauser' => 'Owner Restoran',
            'email' => 'owner@restorehan.com',
            'password' => Hash::make('12345678'),
            'role' => 'owner',
        ]);
    }
}
