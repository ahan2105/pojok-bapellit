<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengaturan_surats', function (Blueprint $table) {
            $table->id();
            $table->string('kode_surat')->default('BAPELIT');    // Kode instansi
            $table->integer('nomor_terakhir')->default(0);        // Counter terakhir
            $table->string('format')->default('{nomor}/{kode}/{romawi}/{tahun}'); // Template format
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengaturan_surats');
    }
};