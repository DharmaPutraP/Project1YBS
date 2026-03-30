<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kernel_prosses', function (Blueprint $table) {
            $table->json('team_1_other_conditions')->nullable()->after('team_1_members');
            $table->json('team_2_other_conditions')->nullable()->after('team_2_members');
        });
    }

    public function down(): void
    {
        Schema::table('kernel_prosses', function (Blueprint $table) {
            $table->dropColumn(['team_1_other_conditions', 'team_2_other_conditions']);
        });
    }
};
