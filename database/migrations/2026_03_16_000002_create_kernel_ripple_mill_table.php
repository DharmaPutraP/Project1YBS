<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('kernel_ripple_mill', function (Blueprint $table) {
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
            $table->decimal('berat_nut_utuh', 10, 4)->nullable();
            $table->decimal('berat_nut_pecah', 10, 4)->nullable();
            $table->decimal('sample_nut_utuh', 10, 6)->nullable();
            $table->decimal('sample_nut_pecah', 10, 6)->nullable();
            $table->decimal('efficiency', 10, 6)->nullable();
            $table->string('limit_operator')->default('gt');
            $table->decimal('limit_value', 10, 3)->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('office', 'idx_kernel_ripple_mill_office');
            $table->index(['kode', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kernel_ripple_mill');
    }
};