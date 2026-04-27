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

        foreach ($tables as $tableName) {
            if (!Schema::hasColumn($tableName, 'office')) {
                Schema::table($tableName, function (Blueprint $table) {
                    $table->string('office', 10)->nullable()->after('created_by');
                });
            }
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

        foreach ($tables as $tableName) {
            if (Schema::hasColumn($tableName, 'office')) {
                Schema::table($tableName, function (Blueprint $table) {
                    $table->dropColumn('office');
                });
            }
        }
    }
};