<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ===== TABEL 1: SURATS =====
        Schema::create('surats', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            
            // Nomor surat (auto-assign saat user submit)
            $table->string('no_surat')->unique();
            
            // === KOLOM SESUAI BUKU AGENDA ===
            $table->date('tanggal')->nullable();              // Tanggal
            $table->string('no_indek')->nullable();           // No / Indek
            $table->string('alamat_tujuan')->nullable();      // Alamat yang Dituju
            $table->text('isi_surat');                        // Isi Surat
            $table->integer('banyak_lampiran')->default(0);   // Banyak Lampiran
            $table->string('sifat_surat')->nullable();        // Sifat Surat
            $table->text('keterangan')->nullable();           // Keterangan
            
            // === KOLOM TAMBAHAN (untuk form user) ===
            $table->string('jenis_surat')->nullable();        // Jenis Surat (Surat Tugas, dll)
            $table->string('asal_surat')->nullable();         // Asal/Pengirim
            $table->string('file_surat');                     // File Lampiran (WAJIB)
            
            $table->timestamps();
        });

        // ===== TABEL 2: PENGATURAN_SURATS =====
        Schema::create('pengaturan_surats', function (Blueprint $table) {
            $table->id();
            $table->string('kode_surat')->default('BAPELIT');
            $table->integer('nomor_terakhir')->default(0);
            $table->string('format')->default('{nomor}');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengaturan_surats');
        Schema::dropIfExists('surats');
    }
};