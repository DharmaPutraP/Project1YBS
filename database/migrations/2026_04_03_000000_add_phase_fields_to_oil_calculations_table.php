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
        Schema::table('oil_calculations', function (Blueprint $table) {
            $table->enum('phase', ['initial', 'final', 'complete'])
                ->default('complete')
                ->after('kode')
                ->comment('Tahap input oil: awal, akhir, atau lengkap');
            $table->enum('status', ['partial', 'complete'])
                ->default('complete')
                ->after('phase')
                ->comment('Status batch oil loss');
            $table->foreignId('initial_user_id')
                ->nullable()
                ->after('status')
                ->constrained('users')
                ->nullOnDelete();
            $table->foreignId('final_user_id')
                ->nullable()
                ->after('initial_user_id')
                ->constrained('users')
                ->nullOnDelete();

            $table->index(['office', 'kode', 'status'], 'idx_oil_calculations_office_kode_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('oil_calculations', function (Blueprint $table) {
            $table->dropIndex('idx_oil_calculations_office_kode_status');
            $table->dropConstrainedForeignId('final_user_id');
            $table->dropConstrainedForeignId('initial_user_id');
            $table->dropColumn(['phase', 'status']);
        });
    }
};