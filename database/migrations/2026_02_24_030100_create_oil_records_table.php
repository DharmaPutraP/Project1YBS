<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     * 
     * Table untuk data NON-ANGKA yang bisa diinput berkali-kali dalam sehari
     * Menggunakan created_at untuk timestamp
     */
    public function up(): void
    {
        Schema::create('oil_records', function (Blueprint $table) {
            $table->id();

            // ── User ─────────────────────────────────────────────────────────────
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // ── Data Non-Angka (bisa banyak per hari) ───────────────────────
            $table->foreignId('oil_master_id')->nullable()->constrained('oil_master_data')->onDelete('set null');
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
            $table->index('kode');
            $table->index('created_at');
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('oil_records');
    }
};
