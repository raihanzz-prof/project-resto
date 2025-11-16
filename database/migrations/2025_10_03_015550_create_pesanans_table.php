<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pesanans', function (Blueprint $table) {
            $table->id('idpesanan');
            $table->foreignid('idmeja')->constrained('meja', 'idmeja')->cascadeOnDelete();
            $table->foreignId('idpelanggan')->constrained('pelanggans', 'idpelanggan')->cascadeOnDelete();
            $table->foreignId('iduser')->constrained('users', 'iduser')->cascadeOnDelete();
            $table->timestamps();
        });
        Schema::create('detail_pesanans', function (Blueprint $table){
            $table->id();
            $table->foreignId('idpesanan')->constrained('pesanans', 'idpesanan')->cascadeOnDelete();
            $table->foreignId('idmenu')->constrained('menus', 'idmenu')->cascadeOnDelete();
            $table->integer('jumlah');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_pesanans');
        Schema::dropIfExists('pesanans');
    }
};
