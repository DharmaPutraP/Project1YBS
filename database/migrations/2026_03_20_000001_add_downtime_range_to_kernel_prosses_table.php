<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kernel_prosses', function (Blueprint $table) {
            $table->time('team_1_start_downtime')->nullable()->after('team_1_end_time');
            $table->time('team_1_end_downtime')->nullable()->after('team_1_start_downtime');

            $table->time('team_2_start_downtime')->nullable()->after('team_2_end_time');
            $table->time('team_2_end_downtime')->nullable()->after('team_2_start_downtime');
        });
    }

    public function down(): void
    {
        Schema::table('kernel_prosses', function (Blueprint $table) {
            $table->dropColumn([
                'team_1_start_downtime',
                'team_1_end_downtime',
                'team_2_start_downtime',
                'team_2_end_downtime',
            ]);
        });
    }
};
