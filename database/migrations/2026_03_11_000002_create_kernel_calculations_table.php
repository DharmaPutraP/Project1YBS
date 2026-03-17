<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kernel_calculations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->string('kode');
            $table->string('jenis')->nullable();
            $table->string('operator')->nullable();
            $table->string('sampel_boy')->nullable();
            $table->decimal('berat_sampel', 10, 4)->nullable();
            $table->decimal('nut_utuh_nut', 10, 4)->nullable();
            $table->decimal('nut_utuh_kernel', 10, 4)->nullable();
            $table->decimal('nut_pecah_nut', 10, 4)->nullable();
            $table->decimal('nut_pecah_kernel', 10, 4)->nullable();
            $table->decimal('kernel_utuh', 10, 4)->nullable();
            $table->decimal('kernel_pecah', 10, 4)->nullable();
            $table->decimal('kernel_to_sampel_nut_utuh', 10, 6)->nullable();
            $table->decimal('kernel_to_sampel_nut_pecah', 10, 6)->nullable();
            $table->decimal('kernel_utuh_to_sampel', 10, 6)->nullable();
            $table->decimal('kernel_pecah_to_sampel', 10, 6)->nullable();
            $table->decimal('kernel_losses', 10, 6)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kernel_calculations');
    }
};
