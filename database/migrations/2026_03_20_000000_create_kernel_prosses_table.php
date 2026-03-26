<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kernel_prosses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->string('office', 10)->nullable();

            $table->date('process_date');

            $table->time('team_1_start_time');
            $table->time('team_1_end_time');
            $table->string('team_1_downtime')->nullable();
            $table->json('team_1_members')->nullable();

            $table->time('team_2_start_time');
            $table->time('team_2_end_time');
            $table->string('team_2_downtime')->nullable();
            $table->json('team_2_members')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index(['office', 'process_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kernel_prosses');
    }
};
