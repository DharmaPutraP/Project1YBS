<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     * 
     * ============================================================
     * MULTI-TENANCY IMPLEMENTATION - OFFICE COLUMN
     * ============================================================
     * 
     * Menambahkan kolom 'office' untuk multi-tenancy (YBS, SUN, SJN):
     * 
     * 1. USERS TABLE:
     *    - office NULLABLE DEFAULT NULL
     *    - Users dengan office (Sampel Boy, Operator): restricted to their office
     *    - Users tanpa office/NULL (PPIC, Admin, Manager): see ALL offices
     * 
     * 2. OIL_RECORDS & OIL_CALCULATIONS TABLES:
     *    - office NOT NULL (required dari user yang login)
     *    - Auto-filled dari Auth::user()->office saat create
     *    - Unique constraint: (office + kode + date) bukan (kode + date) global
     * 
     * 3. INDEXES FOR PERFORMANCE:
     *    - office column index untuk filter query cepat
     *    - Composite index (office, kode, created_at) untuk uniqueness check
     * 
     * ============================================================
     */
    public function up(): void
    {
        // ═══════════════════════════════════════════════════════════════
        // 1. ADD OFFICE TO USERS TABLE (NULLABLE)
        // ═══════════════════════════════════════════════════════════════
        Schema::table('users', function (Blueprint $table) {
            $table->enum('office', ['YBS', 'SUN', 'SJN'])
                ->nullable()
                ->default(null)
                ->after('username')
                ->comment('Office/PT tempat user bekerja. NULL = lihat semua office (PPIC, Admin, etc)');

            // Index untuk filter cepat
            $table->index('office', 'idx_users_office');
        });

        // ═══════════════════════════════════════════════════════════════
        // 2. ADD OFFICE TO OIL_RECORDS TABLE (NOT NULL)
        // ═══════════════════════════════════════════════════════════════
        Schema::table('oil_records', function (Blueprint $table) {
            $table->enum('office', ['YBS', 'SUN', 'SJN'])
                ->after('user_id')
                ->comment('Office/PT (auto dari user login, NOT NULL)');

            // Index untuk filter query cepat
            $table->index('office', 'idx_oil_records_office');

            // Composite index untuk query filtering & sorting
            $table->index(['office', 'created_at'], 'idx_oil_records_office_date');
        });

        // ═══════════════════════════════════════════════════════════════
        // 3. ADD OFFICE TO OIL_CALCULATIONS TABLE (NOT NULL)
        // ═══════════════════════════════════════════════════════════════
        Schema::table('oil_calculations', function (Blueprint $table) {
            $table->enum('office', ['YBS', 'SUN', 'SJN'])
                ->after('user_id')
                ->comment('Office/PT (auto dari user login, NOT NULL)');

            // Index untuk filter query cepat
            $table->index('office', 'idx_oil_calculations_office');

            // Composite index untuk uniqueness check (1 kode per office per day)
            // Dan juga untuk query filtering & sorting
            $table->index(['office', 'kode', 'created_at'], 'idx_oil_calculations_office_kode_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop in reverse order
        Schema::table('oil_calculations', function (Blueprint $table) {
            $table->dropIndex('idx_oil_calculations_office_kode_date');
            $table->dropIndex('idx_oil_calculations_office');
            $table->dropColumn('office');
        });

        Schema::table('oil_records', function (Blueprint $table) {
            $table->dropIndex('idx_oil_records_office_date');
            $table->dropIndex('idx_oil_records_office');
            $table->dropColumn('office');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex('idx_users_office');
            $table->dropColumn('office');
        });
    }
};
