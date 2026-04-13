<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('kernel_master_data', function (Blueprint $table) {
            $table->id();
            $table->enum('office', ['YBS', 'SUN', 'SJN'])->default('YBS');
            $table->string('kode');
            $table->string('nama_sample');
            $table->string('limit_operator'); // 'le' = ≤, 'gt' = >
            $table->decimal('limit_value', 10, 3);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['office', 'kode'], 'uq_kernel_master_data_office_kode');
            $table->index('office', 'idx_kernel_master_data_office');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kernel_master_data');
    }
};
