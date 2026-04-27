<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('Oil_Foss') && !Schema::hasTable('oil_foss')) {
            Schema::rename('Oil_Foss', 'oil_foss');

            return;
        }

        if (!Schema::hasTable('oil_foss')) {
            Schema::create('oil_foss', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
                $table->date('tanggal');
                $table->string('waktu', 8);
                $table->string('operator', 10);
                $table->unsignedTinyInteger('shift');
                $table->string('machine_group', 20);
                $table->string('machine_name', 30);
                $table->decimal('moist', 8, 2)->nullable();
                $table->decimal('olwb', 8, 2)->nullable();
                $table->timestamps();

                $table->index(['tanggal', 'operator', 'shift']);
                $table->index(['tanggal', 'machine_name']);
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('oil_foss') && !Schema::hasTable('Oil_Foss')) {
            Schema::rename('oil_foss', 'Oil_Foss');

            return;
        }

        Schema::dropIfExists('oil_foss');
    }
};
