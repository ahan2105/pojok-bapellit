<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('absensi_sesi', function (Blueprint $table) {
            $table->id();
            
            // Info sesi
            $table->string('nama_sesi');                    // "Ice Breaking", "Rapat Koordinasi", dll
            $table->date('tanggal');                        // ⭐ tanggal absensi
            $table->string('lokasi')->nullable();           // opsional
            $table->text('catatan')->nullable();            // opsional
            
            // Flag
            $table->boolean('is_default')->default(false);  // ⭐ penanda sesi default (Ice Breaking)
            $table->boolean('is_locked')->default(false);   // untuk tombol "Kunci Absen"
            
            // Siapa yang buat
            $table->foreignId('created_by')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('absensi_sesi');
    }
};