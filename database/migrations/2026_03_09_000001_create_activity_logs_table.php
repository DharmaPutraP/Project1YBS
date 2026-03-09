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
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();

            // ── User Information ──────────────────────────────────────────────
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('user_name')->nullable(); // Backup jika user dihapus

            // ── Activity Information ──────────────────────────────────────────
            $table->string('action', 50); // create, update, delete, login, logout, export, etc
            $table->string('model_type')->nullable(); // Model class name (User, OilCalculation, etc)
            $table->unsignedBigInteger('model_id')->nullable(); // ID dari record yang diubah
            $table->string('description'); // Human-readable description

            // ── Change Details ────────────────────────────────────────────────
            $table->json('old_values')->nullable(); // Data sebelum diubah (untuk update/delete)
            $table->json('new_values')->nullable(); // Data setelah diubah (untuk create/update)
            $table->json('metadata')->nullable(); // Additional context (filters, parameters, etc)

            // ── Request Information ───────────────────────────────────────────
            $table->string('ip_address', 45)->nullable(); // IPv4 or IPv6
            $table->text('user_agent')->nullable(); // Browser info
            $table->string('url')->nullable(); // Request URL
            $table->string('method', 10)->nullable(); // GET, POST, PUT, DELETE

            // ── Timestamp ─────────────────────────────────────────────────────
            $table->timestamp('created_at');

            // ── Indexes ───────────────────────────────────────────────────────
            $table->index(['user_id', 'created_at']);
            $table->index(['model_type', 'model_id']);
            $table->index(['action', 'created_at']);
            $table->index('created_at'); // For date filtering
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
