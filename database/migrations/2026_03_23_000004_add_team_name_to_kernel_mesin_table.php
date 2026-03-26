<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kernel_mesin', function (Blueprint $table) {
            $table->string('team_name', 10)->nullable()->after('kernel_prosses_id');
            $table->index(['kernel_prosses_id', 'team_name'], 'kernel_mesin_process_team_idx');
        });

        DB::table('kernel_mesin')
            ->whereNull('team_name')
            ->update(['team_name' => 'Tim 1']);
    }

    public function down(): void
    {
        Schema::table('kernel_mesin', function (Blueprint $table) {
            $table->dropIndex('kernel_mesin_process_team_idx');
            $table->dropColumn('team_name');
        });
    }
};
