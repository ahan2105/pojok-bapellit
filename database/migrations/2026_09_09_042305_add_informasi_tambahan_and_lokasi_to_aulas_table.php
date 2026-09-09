<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('aulas', function (Blueprint $table) {
            $table->text('informasi_tambahan')->nullable()->after('deskripsi');
            $table->string('lokasi')->nullable()->after('informasi_tambahan');
        });
    }

    public function down(): void
    {
        Schema::table('aulas', function (Blueprint $table) {
            $table->dropColumn(['informasi_tambahan', 'lokasi']);
        });
    }
};