<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     * 
     * ============================================================
     * PERFORMANCE OPTIMIZATION - COMPOSITE INDEXES
     * ============================================================
     * 
     * Menambahkan composite indexes untuk meningkatkan performa query:
     * 
     * 1. oil_calculations (kode, created_at):
     *    - Query filtering by kode + date range
     *    - Uniqueness validation per kode per day
     *    - Reports yang filter by kode + date
     * 
     * 2. oil_records (kode, created_at):
     *    - Query filtering by kode + date range
     *    - Reports yang filter by kode + date
     * 
     * 3. oil_master_data (kode):
     *    - Frequent lookups by kode
     *    - JOIN operations dengan oil_calculations/oil_records
     * 
     * ============================================================
     */
    public function up(): void
    {
        Schema::table('oil_calculations', function (Blueprint $table) {
            // Composite index untuk query filtering by kode + date
            // Improves: WHERE kode = ? AND created_at BETWEEN ? AND ?
            $table->index(['kode', 'created_at'], 'idx_oil_calculations_kode_date');
        });

        Schema::table('oil_records', function (Blueprint $table) {
            // Composite index untuk query filtering by kode + date
            // Improves: WHERE kode = ? AND created_at BETWEEN ? AND ?
            $table->index(['kode', 'created_at'], 'idx_oil_records_kode_date');
        });

        Schema::table('oil_master_data', function (Blueprint $table) {
            // Index untuk frequent lookups dan JOINs
            // Improves: WHERE kode = ? dan JOIN conditions
            $table->index('kode', 'idx_oil_master_data_kode');

            // Index untuk is_active filtering
            // Improves: WHERE is_active = 1
            $table->index('is_active', 'idx_oil_master_data_is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('oil_calculations', function (Blueprint $table) {
            $table->dropIndex('idx_oil_calculations_kode_date');
        });

        Schema::table('oil_records', function (Blueprint $table) {
            $table->dropIndex('idx_oil_records_kode_date');
        });

        Schema::table('oil_master_data', function (Blueprint $table) {
            $table->dropIndex('idx_oil_master_data_kode');
            $table->dropIndex('idx_oil_master_data_is_active');
        });
    }
};
