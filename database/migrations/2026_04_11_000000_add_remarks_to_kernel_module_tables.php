<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kernel_calculations', function (Blueprint $table) {
            $table->text('remarks')->nullable()->after('pengulangan');
        });

        Schema::table('kernel_dirt_moist_calculations', function (Blueprint $table) {
            $table->text('remarks')->nullable()->after('pengulangan');
        });

        Schema::table('kernel_qwt', function (Blueprint $table) {
            $table->text('remarks')->nullable()->after('pengulangan');
        });

        Schema::table('kernel_ripple_mill', function (Blueprint $table) {
            $table->text('remarks')->nullable()->after('pengulangan');
        });

        Schema::table('kernel_destoner', function (Blueprint $table) {
            $table->text('remarks')->nullable()->after('pengulangan');
        });
    }

    public function down(): void
    {
        Schema::table('kernel_destoner', function (Blueprint $table) {
            $table->dropColumn('remarks');
        });

        Schema::table('kernel_ripple_mill', function (Blueprint $table) {
            $table->dropColumn('remarks');
        });

        Schema::table('kernel_qwt', function (Blueprint $table) {
            $table->dropColumn('remarks');
        });

        Schema::table('kernel_dirt_moist_calculations', function (Blueprint $table) {
            $table->dropColumn('remarks');
        });

        Schema::table('kernel_calculations', function (Blueprint $table) {
            $table->dropColumn('remarks');
        });
    }
};