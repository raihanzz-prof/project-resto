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
        Schema::create('meja', function (Blueprint $table) {
            $table->id('idmeja');
            $table->string('nomormeja')->unique();                 // contoh: M01, M02
            $table->enum('status', ['kosong','terisi','booking'])   // status realtime meja
                  ->default('kosong');
            $table->string('keterangan')->nullable();               // catatan opsional
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meja');
    }
};
