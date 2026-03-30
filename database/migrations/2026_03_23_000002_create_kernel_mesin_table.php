<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('kernel_mesin', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kernel_prosses_id')->constrained('kernel_prosses')->cascadeOnDelete();
            $table->string('team_name', 10)->nullable();
            $table->string('machine_group', 120)->nullable();
            $table->string('machine_name', 150);
            $table->time('production_start_time');
            $table->time('production_end_time');
            $table->decimal('total_produsction_hours', 8, 2)->unsigned()->default(0);
            $table->decimal('is_spare', 8, 2)->unsigned()->default(0);
            $table->boolean('is_spare_input')->default(false);
            $table->timestamps();

            $table->index(['kernel_prosses_id', 'machine_name'], 'kernel_mesin_process_machine_idx');
            $table->index(['kernel_prosses_id', 'team_name'], 'kernel_mesin_process_team_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kernel_mesin');
    }
};
