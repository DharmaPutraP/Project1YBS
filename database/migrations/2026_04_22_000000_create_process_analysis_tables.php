<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('FFA_Moisture', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->date('tanggal');
            $table->string('jam', 8);
            $table->string('moisture', 100);
            $table->decimal('bst1_ffa', 12, 2);
            $table->decimal('bst2_ffa', 12, 2);
            $table->decimal('bst3_ffa', 12, 2);
            $table->decimal('impurities', 12, 2);
            $table->timestamps();
        });

        Schema::create('Spintest_cot', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->date('tanggal');
            $table->string('jam', 8);
            $table->decimal('oil', 12, 2);
            $table->decimal('emulsi', 12, 2);
            $table->decimal('air', 12, 2);
            $table->decimal('nos', 12, 2);
            $table->timestamps();
        });

        Schema::create('spintest_cst', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->date('tanggal');
            $table->string('jam', 8);
            $table->string('machine_name', 50);
            $table->decimal('oil', 12, 2);
            $table->decimal('emulsi', 12, 2);
            $table->decimal('air', 12, 2);
            $table->decimal('nos', 12, 2);
            $table->timestamps();
        });

        Schema::create('spintest_feed_decanter', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->date('tanggal');
            $table->string('jam', 8);
            $table->string('machine_name', 50);
            $table->decimal('oil', 12, 2);
            $table->decimal('emulsi', 12, 2);
            $table->decimal('air', 12, 2);
            $table->decimal('nos', 12, 2);
            $table->timestamps();
        });

        Schema::create('spintest_light_phase', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->date('tanggal');
            $table->string('jam', 8);
            $table->string('machine_name', 50);
            $table->decimal('oil', 12, 2);
            $table->decimal('emulsi', 12, 2);
            $table->decimal('air', 12, 2);
            $table->decimal('nos', 12, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('spintest_light_phase');
        Schema::dropIfExists('spintest_feed_decanter');
        Schema::dropIfExists('spintest_cst');
        Schema::dropIfExists('Spintest_cot');
        Schema::dropIfExists('FFA_Moisture');
    }
};