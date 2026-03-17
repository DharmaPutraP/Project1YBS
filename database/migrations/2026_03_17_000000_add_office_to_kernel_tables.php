<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('kernel_calculations', function (Blueprint $table) {
            if (!Schema::hasColumn('kernel_calculations', 'office')) {
                $table->enum('office', ['YBS', 'SUN', 'SJN'])->nullable()->after('user_id');
                $table->index('office', 'idx_kernel_calculations_office');
            }
        });

        Schema::table('kernel_dirt_moist_calculations', function (Blueprint $table) {
            if (!Schema::hasColumn('kernel_dirt_moist_calculations', 'office')) {
                $table->enum('office', ['YBS', 'SUN', 'SJN'])->nullable()->after('user_id');
                $table->index('office', 'idx_kernel_dirt_moist_office');
            }
        });

        Schema::table('kernel_qwt', function (Blueprint $table) {
            if (!Schema::hasColumn('kernel_qwt', 'office')) {
                $table->enum('office', ['YBS', 'SUN', 'SJN'])->nullable()->after('user_id');
                $table->index('office', 'idx_kernel_qwt_office');
            }
        });

        Schema::table('kernel_ripple_mill', function (Blueprint $table) {
            if (!Schema::hasColumn('kernel_ripple_mill', 'office')) {
                $table->enum('office', ['YBS', 'SUN', 'SJN'])->nullable()->after('user_id');
                $table->index('office', 'idx_kernel_ripple_mill_office');
            }
        });

        Schema::table('kernel_destoner', function (Blueprint $table) {
            if (!Schema::hasColumn('kernel_destoner', 'office')) {
                $table->enum('office', ['YBS', 'SUN', 'SJN'])->nullable()->after('user_id');
                $table->index('office', 'idx_kernel_destoner_office');
            }
        });
    }

    public function down(): void
    {
        Schema::table('kernel_calculations', function (Blueprint $table) {
            if (Schema::hasColumn('kernel_calculations', 'office')) {
                $table->dropIndex('idx_kernel_calculations_office');
                $table->dropColumn('office');
            }
        });

        Schema::table('kernel_dirt_moist_calculations', function (Blueprint $table) {
            if (Schema::hasColumn('kernel_dirt_moist_calculations', 'office')) {
                $table->dropIndex('idx_kernel_dirt_moist_office');
                $table->dropColumn('office');
            }
        });

        Schema::table('kernel_qwt', function (Blueprint $table) {
            if (Schema::hasColumn('kernel_qwt', 'office')) {
                $table->dropIndex('idx_kernel_qwt_office');
                $table->dropColumn('office');
            }
        });

        Schema::table('kernel_ripple_mill', function (Blueprint $table) {
            if (Schema::hasColumn('kernel_ripple_mill', 'office')) {
                $table->dropIndex('idx_kernel_ripple_mill_office');
                $table->dropColumn('office');
            }
        });

        Schema::table('kernel_destoner', function (Blueprint $table) {
            if (Schema::hasColumn('kernel_destoner', 'office')) {
                $table->dropIndex('idx_kernel_destoner_office');
                $table->dropColumn('office');
            }
        });
    }
};
