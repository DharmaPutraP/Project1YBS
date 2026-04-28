<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('spintest_prosses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->string('office', 10)->nullable();

            $table->date('process_date');
            $table->string('input_team', 20)->nullable();

            $table->time('team_1_start_time');
            $table->time('team_1_end_time');
            $table->time('team_1_start_downtime')->nullable();
            $table->time('team_1_end_downtime')->nullable();
            $table->string('team_1_downtime')->nullable();
            $table->json('team_1_members')->nullable();
            $table->json('team_1_other_conditions')->nullable();

            $table->time('team_2_start_time');
            $table->time('team_2_end_time');
            $table->time('team_2_start_downtime')->nullable();
            $table->time('team_2_end_downtime')->nullable();
            $table->string('team_2_downtime')->nullable();
            $table->json('team_2_members')->nullable();
            $table->json('team_2_other_conditions')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index(['office', 'process_date']);
            $table->index(['process_date', 'office', 'input_team'], 'spintest_prosses_date_office_team_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('spintest_prosses');
    }
};
