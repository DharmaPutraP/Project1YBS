<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('oil_losses', function (Blueprint $table) {
            $table->id();

            // ── User & Timestamp Info ──────────────────────────────
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->date('analysis_date');
            $table->time('analysis_time');

            // ── Input Data (Raw Material) ───────────────────────────
            $table->decimal('tbs_weight', 10, 2)->comment('Berat TBS (kg)');
            $table->decimal('moisture_content', 5, 2)->nullable()->comment('Kadar air (%)');
            $table->decimal('ffa_content', 5, 2)->nullable()->comment('Free Fatty Acid (%)');

            // ── Process Data ────────────────────────────────────────
            $table->decimal('cpo_produced', 10, 2)->comment('CPO yang dihasilkan (kg)');
            $table->decimal('kernel_produced', 10, 2)->nullable()->comment('Kernel yang dihasilkan (kg)');

            // ── Calculated Losses ───────────────────────────────────
            $table->decimal('oil_to_tbs', 5, 2)->nullable()->comment('Oil to TBS ratio (%)');
            $table->decimal('kernel_to_tbs', 5, 2)->nullable()->comment('Kernel to TBS ratio (%)');
            $table->decimal('oil_losses', 5, 2)->nullable()->comment('Oil Losses (%)');
            $table->decimal('total_losses', 10, 2)->nullable()->comment('Total Losses (kg)');

            // ── Additional Data (for future expansion) ──────────────
            $table->string('batch_number')->nullable()->comment('Nomor batch produksi');
            $table->text('notes')->nullable()->comment('Catatan tambahan');

            // ── Status & Approval ───────────────────────────────────
            $table->enum('status', ['draft', 'submitted', 'approved', 'rejected'])->default('draft');
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('approved_at')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // ── Indexes ─────────────────────────────────────────────
            $table->index(['analysis_date', 'status']);
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('oil_losses');
    }
};
