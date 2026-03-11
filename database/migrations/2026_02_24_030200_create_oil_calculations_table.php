<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     * 
     * Table untuk data ANGKA yang HANYA BOLEH 1x PER HARI
     * Menggunakan created_at untuk timestamp (tanggal & jam input)
     * Unique constraint: 1 kode per hari (divalidasi di application level)
     */
    public function up(): void
    {
        Schema::create('oil_calculations', function (Blueprint $table) {
            $table->id();

            // ── User & Kode (UNIQUE per hari per kode via validation) ────────
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('oil_master_id')->nullable()->constrained('oil_master_data')->onDelete('set null');
            $table->string('kode')->comment('Kode sample (setiap kode hanya 1x per hari)');

            // ── Input Data Angka ─────────────────────────────────────────────
            $table->decimal('cawan_kosong', 12, 6)->nullable()->comment('Berat cawan kosong (gr)');
            $table->decimal('berat_basah', 12, 6)->nullable()->comment('Berat sample basah (gr)');
            $table->decimal('cawan_sample_kering', 12, 6)->nullable()->comment('Berat cawan + sample kering (gr)');
            $table->decimal('labu_kosong', 12, 6)->nullable()->comment('Berat labu kosong (gr)');
            $table->decimal('oil_labu', 12, 6)->nullable()->comment('Berat oil + labu (gr)');

            // ── Kalkulasi Otomatis ───────────────────────────────────────────
            $table->decimal('total_cawan_basah', 12, 6)->nullable()->comment('Cawan kosong + berat sampel basah');
            $table->decimal('sampel_setelah_oven', 12, 6)->nullable()->comment('Sample kering - cawan kosong');
            $table->decimal('minyak', 12, 6)->nullable()->comment('Oil labu - labu kosong');

            // ── Hasil Perhitungan (%) ────────────────────────────────────────
            $table->decimal('moist', 15, 6)->nullable()->comment('Moisture content (%)');
            $table->decimal('dmwm', 15, 6)->nullable()->comment('Dry Matter Wet Matter (%)');
            $table->decimal('olwb', 15, 6)->nullable()->comment('Oil on Wet Basis');
            $table->decimal('oldb', 15, 6)->nullable()->comment('Oil on Dry Basis');
            $table->decimal('oil_losses', 15, 6)->nullable()->comment('Oil Losses (%)');

            // ── Limit Values (dari master data) ─────────────────────────────
            $table->decimal('limitOLWB', 15, 6)->nullable()->comment('Limit OLWB');
            $table->decimal('limitOLDB', 15, 6)->nullable()->comment('Limit OLDB');
            $table->decimal('limitOL', 15, 6)->nullable()->comment('Limit Oil Losses');
            $table->decimal('persen', 15, 6)->nullable()->comment('Persen untuk kalkulasi');
            $table->decimal('persen4', 15, 6)->nullable()->comment('Persen 4');

            $table->timestamps();
            $table->softDeletes();

            // ── Indexes ──────────────────────────────────────────────────────
            // Note: Unique constraint (kode + tanggal) divalidasi di application level
            // karena menggunakan DATE(created_at) yang tidak bisa diindex langsung
            $table->index('kode');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('oil_calculations');
    }
};
