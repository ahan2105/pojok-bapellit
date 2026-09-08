<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Menyimpan siapa yang booking (User/Admin)
            $table->foreignId('aula_id')->constrained('aulas')->onDelete('cascade'); // Aula yang dipilih
            $table->date('tanggal_booking');                     // Tanggal pilihan dari kalender UI
            $table->string('nama_penanggung_jawab');             // Nama Penanggung Jawab
            $table->string('keperluan');                         // Keperluan / Nama Acara
            $table->integer('jumlah_peserta');                   // Jumlah peserta
            $table->enum('sesi_waktu', ['pagi', 'siang', 'seharian']); // Pilihan sesi waktu
            $table->enum('status', ['pending', 'disetujui', 'ditolak'])->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
}; 