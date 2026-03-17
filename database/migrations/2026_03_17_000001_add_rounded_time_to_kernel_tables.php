<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('kernel_calculations', function (Blueprint $table) {
            if (!Schema::hasColumn('kernel_calculations', 'rounded_time')) {
                $table->dateTime('rounded_time')->nullable()->after('office');
            }
        });

        Schema::table('kernel_dirt_moist_calculations', function (Blueprint $table) {
            if (!Schema::hasColumn('kernel_dirt_moist_calculations', 'rounded_time')) {
                $table->dateTime('rounded_time')->nullable()->after('office');
            }
        });

        Schema::table('kernel_qwt', function (Blueprint $table) {
            if (!Schema::hasColumn('kernel_qwt', 'rounded_time')) {
                $table->dateTime('rounded_time')->nullable()->after('office');
            }
        });

        Schema::table('kernel_ripple_mill', function (Blueprint $table) {
            if (!Schema::hasColumn('kernel_ripple_mill', 'rounded_time')) {
                $table->dateTime('rounded_time')->nullable()->after('office');
            }
        });

        Schema::table('kernel_destoner', function (Blueprint $table) {
            if (!Schema::hasColumn('kernel_destoner', 'rounded_time')) {
                $table->dateTime('rounded_time')->nullable()->after('office');
            }
        });
    }

    public function down(): void
    {
        Schema::table('kernel_calculations', function (Blueprint $table) {
            if (Schema::hasColumn('kernel_calculations', 'rounded_time')) {
                $table->dropColumn('rounded_time');
            }
        });

        Schema::table('kernel_dirt_moist_calculations', function (Blueprint $table) {
            if (Schema::hasColumn('kernel_dirt_moist_calculations', 'rounded_time')) {
                $table->dropColumn('rounded_time');
            }
        });

        Schema::table('kernel_qwt', function (Blueprint $table) {
            if (Schema::hasColumn('kernel_qwt', 'rounded_time')) {
                $table->dropColumn('rounded_time');
            }
        });

        Schema::table('kernel_ripple_mill', function (Blueprint $table) {
            if (Schema::hasColumn('kernel_ripple_mill', 'rounded_time')) {
                $table->dropColumn('rounded_time');
            }
        });

        Schema::table('kernel_destoner', function (Blueprint $table) {
            if (Schema::hasColumn('kernel_destoner', 'rounded_time')) {
                $table->dropColumn('rounded_time');
            }
        });
    }
};
