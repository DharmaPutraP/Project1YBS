<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     * 
     * Table ini menggantikan sheet "Vlookup" di Excel/Google Sheets
     */
    public function up(): void
    {
        Schema::create('oil_master_data', function (Blueprint $table) {
            $table->id();

            $table->enum('office', ['YBS', 'SUN', 'SJN'])->default('YBS')->comment('Office/PT');

            // ── Identifikasi Sample ──────────────────────────────────────────
            $table->string('kode')->comment('Kode sample (untuk dropdown & vlookup)');
            $table->string('column_name')->comment('Nama kolom/kategori');
            $table->string('jenis')->nullable()->comment('Jenis sample');

            // ── Parameter Kalkulasi (dari Apps Script) ──────────────────────
            $table->decimal('persen', 8, 4)->default(0)->nullable()->comment('Persen untuk kalkulasi oil losses');
            $table->decimal('persen4', 8, 4)->default(0)->nullable()->comment('Persen 4 untuk kalkulasi');
            $table->string('pivot')->nullable()->comment('Pivot value');

            // ── Limit Values (Threshold) ─────────────────────────────────────
            $table->decimal('limitOLWB', 8, 2)->default(0)->nullable()->comment('Limit OLWB');
            $table->decimal('limitOLDB', 8, 2)->default(0)->nullable()->comment('Limit OLDB');
            $table->decimal('limitOL', 8, 2)->default(0)->nullable()->comment('Limit Oil Losses');
            $table->enum('limit_operator', ['le', 'lt', 'ge', 'gt'])->default('le')->comment('Operator limit OLWB (le: <=, lt: <, ge: >=, gt: >)');

            // ── Additional Fields ────────────────────────────────────────────
            $table->text('description')->nullable()->comment('Deskripsi tambahan');
            $table->boolean('is_active')->default(true)->comment('Status aktif/tidak');

            $table->timestamps();

            // ── Indexes ──────────────────────────────────────────────────────
            $table->unique(['office', 'kode'], 'uq_oil_master_data_office_kode');
            $table->index('office', 'idx_oil_master_data_office');
            $table->index(['office', 'kode'], 'idx_oil_master_data_office_kode');
            $table->index('kode', 'idx_oil_master_data_kode');
            $table->index('is_active', 'idx_oil_master_data_is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('oil_master_data');
    }
};
