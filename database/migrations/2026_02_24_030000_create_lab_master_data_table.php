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
        Schema::create('lab_master_data', function (Blueprint $table) {
            $table->id();

            // ── Identifikasi Sample ──────────────────────────────────────────
            $table->string('kode')->unique()->comment('Kode sample (untuk dropdown & vlookup)');
            $table->string('column_name')->comment('Nama kolom/kategori');
            $table->string('jenis')->nullable()->comment('Jenis sample');

            // ── Parameter Kalkulasi (dari Apps Script) ──────────────────────
            $table->decimal('persen', 8, 4)->default(0)->comment('Persen untuk kalkulasi oil losses');
            $table->decimal('persen4', 8, 4)->default(0)->comment('Persen 4 untuk kalkulasi');
            $table->string('pivot')->nullable()->comment('Pivot value');

            // ── Limit Values (Threshold) ─────────────────────────────────────
            $table->decimal('limit', 8, 2)->default(0)->comment('Limit 1 - threshold pertama');
            $table->decimal('limit2', 8, 2)->default(0)->comment('Limit 2 - threshold kedua');
            $table->decimal('limit3', 8, 2)->default(0)->comment('Limit 3 - threshold ketiga');

            // ── Additional Fields ────────────────────────────────────────────
            $table->text('description')->nullable()->comment('Deskripsi tambahan');
            $table->boolean('is_active')->default(true)->comment('Status aktif/tidak');

            $table->timestamps();

            // ── Indexes ──────────────────────────────────────────────────────
            $table->index('kode');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lab_master_data');
    }
};
