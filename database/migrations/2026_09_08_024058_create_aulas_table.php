<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('aulas', function (Blueprint $table) {
            $table->id();
            $table->string('nama');                    // Nama Aula (cth: Aula Wiradadaha)
            $table->integer('kapasitas');              // Kapasitas orang (cth: 100)
            $table->text('deskripsi')->nullable();     // Deskripsi lengkap aula
            $table->json('foto')->nullable();          // Menyimpan array path foto (maksimal 3 foto)
            $table->json('fasilitas')->nullable();     // Menyimpan array daftar fasilitas (cth: ['Proyektor', 'AC'])
            $table->boolean('status_aktif')->default(true); // Status: true = Bisa dibooking, false = Tidak bisa
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('aulas');
    }
};