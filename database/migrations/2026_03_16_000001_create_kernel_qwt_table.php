<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('kernel_qwt', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->enum('office', ['YBS', 'SUN', 'SJN'])->nullable();
            $table->dateTime('rounded_time')->nullable();
            $table->string('kode');
            $table->string('jenis')->nullable();
            $table->string('operator')->nullable();
            $table->string('sampel_boy')->nullable();
            $table->boolean('pengulangan')->default(false);
            $table->decimal('sampel_setelah_kuarter', 10, 4)->nullable();
            $table->decimal('berat_nut_utuh', 10, 4)->nullable();
            $table->decimal('berat_nut_pecah', 10, 4)->nullable();
            $table->decimal('berat_kernel_utuh', 10, 4)->nullable();
            $table->decimal('berat_kernel_pecah', 10, 4)->nullable();
            $table->decimal('berat_cangkang', 10, 4)->nullable();
            $table->decimal('berat_batu', 10, 4)->nullable();
            $table->decimal('berat_fiber', 10, 6)->nullable();
            $table->decimal('berat_broken_nut', 10, 6)->nullable();
            $table->decimal('total_berat_nut', 10, 6)->nullable();
            $table->decimal('bn_tn', 10, 6)->nullable();
            $table->decimal('moisture', 10, 6)->nullable();
            $table->decimal('ampere_screw', 10, 4)->nullable();
            $table->decimal('tekanan_hydraulic', 10, 4)->nullable();
            $table->decimal('kecepatan_screw', 10, 4)->nullable();
            $table->string('bn_tn_limit_operator')->default('le');
            $table->decimal('bn_tn_limit_value', 10, 3)->nullable();
            $table->string('moist_limit_operator')->default('le');
            $table->decimal('moist_limit_value', 10, 3)->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('office', 'idx_kernel_qwt_office');
            $table->index(['kode', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kernel_qwt');
    }
};