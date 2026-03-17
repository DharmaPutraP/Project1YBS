<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kernel_master_data', function (Blueprint $table) {
            $table->id();
            $table->string('kode')->unique();
            $table->string('nama_sample');
            $table->string('limit_operator'); // 'le' = ≤, 'gt' = >
            $table->decimal('limit_value', 10, 3);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kernel_master_data');
    }
};
