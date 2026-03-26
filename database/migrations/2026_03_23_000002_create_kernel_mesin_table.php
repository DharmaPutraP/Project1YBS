<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kernel_mesin', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kernel_prosses_id')->constrained('kernel_prosses')->cascadeOnDelete();
            $table->string('machine_group', 120)->nullable();
            $table->string('machine_name', 150);
            $table->time('production_start_time');
            $table->time('production_end_time');
            $table->boolean('is_spare')->default(false);
            $table->timestamps();

            $table->index(['kernel_prosses_id', 'machine_name'], 'kernel_mesin_process_machine_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kernel_mesin');
    }
};
