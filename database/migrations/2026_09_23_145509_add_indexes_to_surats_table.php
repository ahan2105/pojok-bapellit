<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('surats', function (Blueprint $table) {
            // Index untuk search dan sorting
            if (!Schema::hasIndex('surats', 'idx_surats_no_surat')) {
                $table->index('no_surat', 'idx_surats_no_surat');
            }
            
            if (!Schema::hasIndex('surats', 'idx_surats_no_indek')) {
                $table->index('no_indek', 'idx_surats_no_indek');
            }
            
            if (!Schema::hasIndex('surats', 'idx_surats_created_at')) {
                $table->index('created_at', 'idx_surats_created_at');
            }
            
            // Index composite untuk statistics
            if (!Schema::hasIndex('surats', 'idx_surats_created_month_year')) {
                $table->index(['created_at'], 'idx_surats_created_month_year');
            }
        });
    }

    public function down(): void
    {
        Schema::table('surats', function (Blueprint $table) {
            $table->dropIndex('idx_surats_no_surat');
            $table->dropIndex('idx_surats_no_indek');
            $table->dropIndex('idx_surats_created_at');
            $table->dropIndex('idx_surats_created_month_year');
        });
    }
};