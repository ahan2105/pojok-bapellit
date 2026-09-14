<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // ⭐ Tambah kolom jabatan (kalau belum ada)
            if (!Schema::hasColumn('users', 'jabatan')) {
                $table->string('jabatan')->nullable()->after('bidang');
            }
            
            // ⭐ Tambah kolom golongan (kalau belum ada)
            if (!Schema::hasColumn('users', 'golongan')) {
                $table->string('golongan')->nullable()->after('jabatan');
            }
        });

        // ⭐ Ubah email jadi nullable (kalau belum nullable)
        // Ini TIDAK menghapus data, cuma ubah constraint
        Schema::table('users', function (Blueprint $table) {
            $table->string('email')->nullable()->change();
        });

        // ⭐ Ubah password jadi nullable (kalau belum nullable)
        Schema::table('users', function (Blueprint $table) {
            $table->string('password')->nullable()->change();
        });
    }

    public function down(): void
    {
        // Rollback: hapus 2 kolom baru aja
        // Data di kolom lain tetap aman
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'jabatan')) {
                $table->dropColumn('jabatan');
            }
            if (Schema::hasColumn('users', 'golongan')) {
                $table->dropColumn('golongan');
            }
        });
    }
};