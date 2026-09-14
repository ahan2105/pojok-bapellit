<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('absensi_detail', function (Blueprint $table) {
            $table->id();
            
            // Relasi ke sesi
            $table->foreignId('absensi_sesi_id')
                  ->constrained('absensi_sesi')
                  ->cascadeOnDelete();
            
            // Relasi ke user (peserta)
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->cascadeOnDelete();
            
            // Status kehadiran: hadir / tidak (null = belum diabsen)
            $table->enum('status_kehadiran', ['hadir', 'tidak'])->nullable();
            
            // Keterangan — hanya diisi kalau "tidak hadir"
            $table->text('keterangan')->nullable();
            
            // Kapan diabsen
            $table->timestamp('waktu_absen')->nullable();
            
            $table->timestamps();

            // 1 user hanya boleh 1x per sesi
            $table->unique(['absensi_sesi_id', 'user_id'], 'unique_absensi_per_sesi');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('absensi_detail');
    }
};