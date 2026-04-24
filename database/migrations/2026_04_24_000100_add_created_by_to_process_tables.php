<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $tables = [
            'FFA_Moisture',
            'Spintest_cot',
            'spintest_cst',
            'spintest_feed_decanter',
            'spintest_light_phase',
            'analisa_usb',
            'oil_foss',
        ];

        foreach ($tables as $table) {
            if (!Schema::hasTable($table) || Schema::hasColumn($table, 'created_by')) {
                continue;
            }

            Schema::table($table, function (Blueprint $table): void {
                $table->string('created_by', 150)->nullable()->after('user_id');
            });
        }
    }

    public function down(): void
    {
        $tables = [
            'FFA_Moisture',
            'Spintest_cot',
            'spintest_cst',
            'spintest_feed_decanter',
            'spintest_light_phase',
            'analisa_usb',
            'oil_foss',
        ];

        foreach ($tables as $table) {
            if (!Schema::hasTable($table) || !Schema::hasColumn($table, 'created_by')) {
                continue;
            }

            Schema::table($table, function (Blueprint $table): void {
                $table->dropColumn('created_by');
            });
        }
    }
};
