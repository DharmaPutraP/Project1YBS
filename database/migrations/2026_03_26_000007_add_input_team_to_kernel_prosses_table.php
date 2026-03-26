<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kernel_prosses', function (Blueprint $table) {
            $table->string('input_team', 20)->nullable()->after('process_date');
            $table->index(['process_date', 'office', 'input_team'], 'kernel_prosses_date_office_team_idx');
        });
    }

    public function down(): void
    {
        Schema::table('kernel_prosses', function (Blueprint $table) {
            $table->dropIndex('kernel_prosses_date_office_team_idx');
            $table->dropColumn('input_team');
        });
    }
};
