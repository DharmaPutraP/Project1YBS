<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kernel_destoner', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('kode', 20);
            $table->string('jenis', 50)->nullable();
            $table->string('operator', 255);
            $table->string('sampel_boy', 255);
            $table->decimal('berat_sampel', 10, 4)->comment('gram');
            $table->decimal('time', 10, 4)->comment('detik');
            $table->decimal('berat_nut', 10, 4);
            $table->decimal('berat_kernel', 10, 4);
            $table->decimal('konversi_kg', 10, 6)->comment('berat_sampel / 1000');
            $table->decimal('rasio_jam_kg', 10, 6)->comment('konversi_kg * 3600 / time');
            $table->decimal('persen_nut', 10, 6)->comment('berat_nut / berat_sampel * 100');
            $table->decimal('persen_kernel', 10, 6)->comment('berat_kernel / berat_sampel * 100');
            $table->decimal('total_losses_kernel', 10, 6)->comment('persen_kernel + persen_nut');
            $table->decimal('loss_kernel_jam', 10, 6)->comment('total_losses_kernel * rasio_jam_kg');
            $table->decimal('loss_kernel_tbs', 14, 8)->comment('loss_kernel_jam / 60000');
            $table->string('limit_operator', 10)->nullable();
            $table->decimal('limit_value', 10, 3)->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['kode', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kernel_destoner');
    }
};
