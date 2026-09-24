<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            // Composite index optimal untuk query SSE:
            // WHERE user_id = ? AND id > ? ORDER BY id ASC
            if (!Schema::hasIndex('notifications', 'idx_notifications_user_id_id')) {
                $table->index(['user_id', 'id'], 'idx_notifications_user_id_id');
            }
            
            // Index untuk scope unread()
            if (!Schema::hasIndex('notifications', 'idx_notifications_read_at')) {
                $table->index('read_at', 'idx_notifications_read_at');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->dropIndex('idx_notifications_user_id_id');
            $table->dropIndex('idx_notifications_read_at');
        });
    }
};