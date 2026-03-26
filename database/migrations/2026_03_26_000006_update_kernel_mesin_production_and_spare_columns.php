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
            $table->decimal('total_produsction_hours', 8, 2)->unsigned()->default(0)->after('production_end_time');
            $table->boolean('is_spare_input')->default(false)->after('is_spare');
        });

        DB::table('kernel_mesin')
            ->select(['id', 'total_production_minutes', 'is_spare'])
            ->orderBy('id')
            ->chunkById(200, function ($rows): void {
                foreach ($rows as $row) {
                    $minutesOrHours = (int) ($row->total_production_minutes ?? 0);
                    $hours = $minutesOrHours <= 0
                        ? 0
                        : ($minutesOrHours <= 24 ? (float) $minutesOrHours : round($minutesOrHours / 60, 2));

                    DB::table('kernel_mesin')
                        ->where('id', $row->id)
                        ->update([
                            'total_produsction_hours' => $hours,
                            'is_spare_input' => (bool) $row->is_spare,
                        ]);
                }
            });

        Schema::table('kernel_mesin', function (Blueprint $table) {
            $table->dropColumn('total_production_minutes');
            $table->dropColumn('is_spare');
        });

        Schema::table('kernel_mesin', function (Blueprint $table) {
            $table->decimal('is_spare', 8, 2)->unsigned()->default(0)->after('total_produsction_hours');
        });

        DB::table('kernel_mesin')
            ->select(['id', 'is_spare_input', 'total_produsction_hours'])
            ->orderBy('id')
            ->chunkById(200, function ($rows): void {
                foreach ($rows as $row) {
                    $spareHours = (bool) $row->is_spare_input ? (float) ($row->total_produsction_hours ?? 0) : 0;

                    DB::table('kernel_mesin')
                        ->where('id', $row->id)
                        ->update([
                            'is_spare' => round($spareHours, 2),
                        ]);
                }
            });
    }

    public function down(): void
    {
        Schema::table('kernel_mesin', function (Blueprint $table) {
            $table->unsignedInteger('total_production_minutes')->default(0)->after('production_end_time');
            $table->boolean('legacy_is_spare')->default(false)->after('is_spare');
        });

        DB::table('kernel_mesin')
            ->select(['id', 'total_produsction_hours', 'is_spare_input'])
            ->orderBy('id')
            ->chunkById(200, function ($rows): void {
                foreach ($rows as $row) {
                    $hours = (float) ($row->total_produsction_hours ?? 0);

                    DB::table('kernel_mesin')
                        ->where('id', $row->id)
                        ->update([
                            'total_production_minutes' => (int) round(max($hours, 0) * 60),
                            'legacy_is_spare' => (bool) $row->is_spare_input,
                        ]);
                }
            });

        Schema::table('kernel_mesin', function (Blueprint $table) {
            $table->dropColumn('total_produsction_hours');
            $table->dropColumn('is_spare');
            $table->dropColumn('is_spare_input');
        });

        Schema::table('kernel_mesin', function (Blueprint $table) {
            $table->boolean('is_spare')->default(false)->after('total_production_minutes');
        });

        DB::table('kernel_mesin')
            ->select(['id', 'legacy_is_spare'])
            ->orderBy('id')
            ->chunkById(200, function ($rows): void {
                foreach ($rows as $row) {
                    DB::table('kernel_mesin')
                        ->where('id', $row->id)
                        ->update([
                            'is_spare' => (bool) $row->legacy_is_spare,
                        ]);
                }
            });

        Schema::table('kernel_mesin', function (Blueprint $table) {
            $table->dropColumn('legacy_is_spare');
        });
    }
};
