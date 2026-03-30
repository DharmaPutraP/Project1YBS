<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('kernel_dirt_moist_calculations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->enum('office', ['YBS', 'SUN', 'SJN'])->nullable();
            $table->dateTime('rounded_time')->nullable();
            $table->string('kode');
            $table->string('jenis')->nullable();
            $table->string('operator')->nullable();
            $table->string('sampel_boy')->nullable();
            $table->boolean('pengulangan')->default(false);
            $table->decimal('berat_sampel', 10, 4)->nullable();
            $table->decimal('berat_dirty', 10, 4)->nullable();
            $table->decimal('dirty_to_sampel', 10, 6)->nullable();
            $table->decimal('moist_percent', 10, 6)->nullable();
            $table->string('dirty_limit_operator')->default('le');
            $table->decimal('dirty_limit_value', 10, 3)->nullable();
            $table->string('moist_limit_operator')->nullable();
            $table->decimal('moist_limit_value', 10, 3)->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('office', 'idx_kernel_dirt_moist_office');
            $table->index(['kode', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kernel_dirt_moist_calculations');
    }
};
