<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('analisa_usb', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->date('tanggal');
            $table->string('jam', 8);
            $table->unsignedTinyInteger('shift');
            $table->unsignedTinyInteger('no_rebusan');
            $table->decimal('diamati_jlh_janjang', 12, 2)->default(0);
            $table->decimal('lolos_jlh_janjang', 12, 2)->default(0);
            $table->decimal('persen_usb', 8, 2)->default(0);
            $table->timestamps();

            $table->index(['tanggal', 'jam']);
            $table->index(['tanggal', 'no_rebusan']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('analisa_usb');
    }
};
