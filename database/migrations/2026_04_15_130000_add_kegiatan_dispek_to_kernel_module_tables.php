<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        $tables = [
            'kernel_calculations',
            'kernel_dirt_moist_calculations',
            'kernel_qwt',
            'kernel_ripple_mill',
            'kernel_destoner',
        ];

        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $blueprint) {
                $blueprint->boolean('kegiatan_dispek')->default(false)->after('rounded_time');
            });
        }
    }

    public function down(): void
    {
        $tables = [
            'kernel_calculations',
            'kernel_dirt_moist_calculations',
            'kernel_qwt',
            'kernel_ripple_mill',
            'kernel_destoner',
        ];

        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $blueprint) {
                $blueprint->dropColumn('kegiatan_dispek');
            });
        }
    }
};
