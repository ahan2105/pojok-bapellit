<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            // Index untuk filter status dan statistics
            if (!Schema::hasIndex('bookings', 'idx_bookings_status')) {
                $table->index('status', 'idx_bookings_status');
            }
            
            // Index untuk filter tanggal dan statistics
            if (!Schema::hasIndex('bookings', 'idx_bookings_tanggal_booking')) {
                $table->index('tanggal_booking', 'idx_bookings_tanggal_booking');
            }
            
            // Index composite untuk sorting
            if (!Schema::hasIndex('bookings', 'idx_bookings_created_at')) {
                $table->index('created_at', 'idx_bookings_created_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropIndex('idx_bookings_status');
            $table->dropIndex('idx_bookings_tanggal_booking');
            $table->dropIndex('idx_bookings_created_at');
        });
    }
};