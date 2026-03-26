<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kernel_calculations', function (Blueprint $table) {
            $table->boolean('pengulangan')->default(false)->after('sampel_boy');
        });

        Schema::table('kernel_dirt_moist_calculations', function (Blueprint $table) {
            $table->boolean('pengulangan')->default(false)->after('sampel_boy');
        });

        Schema::table('kernel_qwt', function (Blueprint $table) {
            $table->boolean('pengulangan')->default(false)->after('sampel_boy');
        });

        Schema::table('kernel_ripple_mill', function (Blueprint $table) {
            $table->boolean('pengulangan')->default(false)->after('sampel_boy');
        });

        Schema::table('kernel_destoner', function (Blueprint $table) {
            $table->boolean('pengulangan')->default(false)->after('sampel_boy');
        });
    }

    public function down(): void
    {
        Schema::table('kernel_calculations', function (Blueprint $table) {
            $table->dropColumn('pengulangan');
        });

        Schema::table('kernel_dirt_moist_calculations', function (Blueprint $table) {
            $table->dropColumn('pengulangan');
        });

        Schema::table('kernel_qwt', function (Blueprint $table) {
            $table->dropColumn('pengulangan');
        });

        Schema::table('kernel_ripple_mill', function (Blueprint $table) {
            $table->dropColumn('pengulangan');
        });

        Schema::table('kernel_destoner', function (Blueprint $table) {
            $table->dropColumn('pengulangan');
        });
    }
};
