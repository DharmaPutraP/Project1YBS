<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('kernel_bobot_configs', function (Blueprint $table) {
            $table->id();
            $table->string('jenis')->unique();
            $table->enum('direction', ['asc', 'desc'])->default('asc');
            $table->decimal('limit_100', 8, 2);
            $table->decimal('limit_90', 8, 2);
            $table->decimal('limit_80', 8, 2);
            $table->decimal('limit_70', 8, 2);
            $table->decimal('limit_60', 8, 2);
            $table->decimal('limit_50', 8, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kernel_bobot_configs');
    }
};
