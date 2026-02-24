<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     * 
     * Table untuk data NON-ANGKA yang bisa diinput berkali-kali dalam sehari
     */
    public function up(): void
    {
        Schema::create('lab_records', function (Blueprint $table) {
            $table->id();

            // ── User & Timestamp ─────────────────────────────────────────────
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->date('analysis_date')->comment('Tanggal analisa');
            $table->time('analysis_time')->comment('Jam analisa');

            // ── Data Non-Angka (bisa banyak per hari) ───────────────────────
            $table->foreignId('lab_master_id')->nullable()->constrained('lab_master_data')->onDelete('set null');
            $table->string('kode')->comment('Kode sample (dari dropdown)');
            $table->string('column_name')->nullable()->comment('Auto-filled dari master');
            $table->string('pivot')->nullable()->comment('Auto-filled dari master');
            $table->string('jenis')->nullable()->comment('Jenis sample (dari dropdown)');
            $table->string('operator')->nullable()->comment('Nama operator');
            $table->string('sampel_boy')->nullable()->comment('Nama sample boy');
            $table->text('parameter_lain')->nullable()->comment('Parameter tambahan');

            $table->timestamps();
            $table->softDeletes();

            // ── Indexes ──────────────────────────────────────────────────────
            $table->index('analysis_date');
            $table->index(['analysis_date', 'kode']);
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lab_records');
    }
};
