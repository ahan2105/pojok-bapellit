<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('chatbot_templates', function (Blueprint $table) {
            $table->id();
            $table->string('label');           // Label tombol/identitas
            $table->text('reply');             // Jawaban lengkap
            $table->json('keywords')->nullable(); // Array kata kunci
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chatbot_templates');
    }
};