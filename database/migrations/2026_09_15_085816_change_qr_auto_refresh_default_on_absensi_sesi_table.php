<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;   // ⭐ Tambahkan ini
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('absensi_sesi', function (Blueprint $table) {
            // Ubah default jadi false
            $table->boolean('qr_auto_refresh')->default(false)->change();
        });

        // ⭐ Update semua data existing jadi false
        DB::table('absensi_sesi')->update(['qr_auto_refresh' => false]);
    }

    public function down(): void
    {
        Schema::table('absensi_sesi', function (Blueprint $table) {
            $table->boolean('qr_auto_refresh')->default(true)->change();
        });
    }
};