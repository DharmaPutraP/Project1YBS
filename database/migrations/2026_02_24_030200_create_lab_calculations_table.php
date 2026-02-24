<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     * 
     * Table untuk data ANGKA yang HANYA BOLEH 1x PER HARI
     * Smart shifting: angka otomatis pindah ke record paling atas untuk tanggal tersebut
     */
    public function up(): void
    {
        Schema::create('lab_calculations', function (Blueprint $table) {
            $table->id();

            // ── User & Date & Kode (UNIQUE per hari per kode) ────────────────
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->date('analysis_date')->comment('Tanggal analisa');
            $table->foreignId('lab_master_id')->nullable()->constrained('lab_master_data')->onDelete('set null');
            $table->string('kode')->comment('Kode sample (setiap kode hanya 1x per hari)');

            // ── Input Data Angka ─────────────────────────────────────────────
            $table->decimal('cawan_kosong', 12, 6)->nullable()->comment('Berat cawan kosong (gr)');
            $table->decimal('berat_basah', 12, 6)->nullable()->comment('Berat sample basah (gr)');
            $table->decimal('cawan_sample_kering', 12, 6)->nullable()->comment('Berat cawan + sample kering (gr)');
            $table->decimal('labu_kosong', 12, 6)->nullable()->comment('Berat labu kosong (gr)');
            $table->decimal('oil_labu', 12, 6)->nullable()->comment('Berat oil + labu (gr)');

            // ── Kalkulasi Otomatis ───────────────────────────────────────────
            $table->decimal('total_cawan_basah', 12, 6)->nullable()->comment('Cawan kosong + berat basah');
            $table->decimal('sampel_setelah_oven', 12, 6)->nullable()->comment('Sample kering - cawan kosong');
            $table->decimal('minyak', 12, 6)->nullable()->comment('Oil labu - labu kosong');

            // ── Hasil Perhitungan (%) ────────────────────────────────────────
            $table->decimal('moist', 10, 6)->nullable()->comment('Moisture content (%)');
            $table->decimal('dmwm', 10, 6)->nullable()->comment('Dry Matter Wet Matter (%)');
            $table->decimal('olwb', 10, 6)->nullable()->comment('Oil on Wet Basis');
            $table->decimal('oldb', 10, 6)->nullable()->comment('Oil on Dry Basis');
            $table->decimal('oil_losses', 10, 6)->nullable()->comment('Oil Losses (%)');

            // ── Limit Values (dari master data) ─────────────────────────────
            $table->decimal('limit', 10, 6)->nullable()->comment('Limit 1');
            $table->decimal('limit2', 10, 6)->nullable()->comment('Limit 2');
            $table->decimal('limit3', 10, 6)->nullable()->comment('Limit 3');
            $table->decimal('persen', 10, 6)->nullable()->comment('Persen untuk kalkulasi');
            $table->decimal('persen4', 10, 6)->nullable()->comment('Persen 4');

            $table->timestamps();
            $table->softDeletes();

            // ── Indexes ──────────────────────────────────────────────────────
            $table->unique(['analysis_date', 'kode'], 'unique_date_kode');
            $table->index('analysis_date');
            $table->index('kode');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lab_calculations');
    }
};
