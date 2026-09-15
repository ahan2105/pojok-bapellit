<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('absensi_sesi', function (Blueprint $table) {
            // Token unik untuk QR Code
            if (!Schema::hasColumn('absensi_sesi', 'token_qr')) {
                $table->string('token_qr', 64)->nullable()->unique()->after('is_locked');
            }
            // Kapan token terakhir di-generate
            if (!Schema::hasColumn('absensi_sesi', 'token_generated_at')) {
                $table->timestamp('token_generated_at')->nullable()->after('token_qr');
            }
            // Berapa detik QR berlaku
            if (!Schema::hasColumn('absensi_sesi', 'qr_lifetime_seconds')) {
                $table->integer('qr_lifetime_seconds')->default(60)->after('token_generated_at');
            }
            // Aktif/nonaktif auto-refresh
            if (!Schema::hasColumn('absensi_sesi', 'qr_auto_refresh')) {
                $table->boolean('qr_auto_refresh')->default(true)->after('qr_lifetime_seconds');
            }
        });
    }

    public function down(): void
    {
        Schema::table('absensi_sesi', function (Blueprint $table) {
            $table->dropColumn([
                'token_qr',
                'token_generated_at',
                'qr_lifetime_seconds',
                'qr_auto_refresh',
            ]);
        });
    }
};