<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('oil_records', function (Blueprint $table) {
            $table->date('tanggal_sampel')->nullable()->after('kode');
            $table->index('tanggal_sampel');
            $table->index(['office', 'kode', 'tanggal_sampel'], 'idx_oil_records_office_kode_sample_date');
        });

        Schema::table('oil_calculations', function (Blueprint $table) {
            $table->date('tanggal_sampel')->nullable()->after('kode');
            $table->index('tanggal_sampel');
            $table->index(['office', 'kode', 'tanggal_sampel'], 'idx_oil_calculations_office_kode_sample_date');
        });

        DB::statement("UPDATE oil_records SET tanggal_sampel = DATE(DATE_SUB(created_at, INTERVAL 1 DAY)) WHERE tanggal_sampel IS NULL");
        DB::statement("UPDATE oil_calculations SET tanggal_sampel = DATE(DATE_SUB(created_at, INTERVAL 1 DAY)) WHERE tanggal_sampel IS NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('oil_records', function (Blueprint $table) {
            $table->dropIndex('idx_oil_records_office_kode_sample_date');
            $table->dropIndex(['tanggal_sampel']);
            $table->dropColumn('tanggal_sampel');
        });

        Schema::table('oil_calculations', function (Blueprint $table) {
            $table->dropIndex('idx_oil_calculations_office_kode_sample_date');
            $table->dropIndex(['tanggal_sampel']);
            $table->dropColumn('tanggal_sampel');
        });
    }
};