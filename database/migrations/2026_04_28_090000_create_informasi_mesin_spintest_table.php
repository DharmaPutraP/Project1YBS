<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('informasi_mesin_spintest', function (Blueprint $table) {
            $table->id();
            $table->foreignId('spintest_prosses_id')->constrained('spintest_prosses')->onDelete('cascade');
            $table->string('team_name', 20)->nullable();
            $table->string('machine_group', 120)->nullable();
            $table->string('machine_name', 150)->nullable();
            $table->time('production_start_time')->nullable();
            $table->time('production_end_time')->nullable();
            $table->double('total_production_hours')->nullable();
            $table->boolean('is_spare_input')->default(false);
            $table->double('is_spare')->nullable();
            $table->timestamps();
            $table->index(['spintest_prosses_id', 'team_name']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('informasi_mesin_spintest');
    }
};
