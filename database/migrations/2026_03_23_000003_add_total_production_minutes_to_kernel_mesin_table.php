<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kernel_mesin', function (Blueprint $table) {
            $table->unsignedInteger('total_production_minutes')->default(0)->after('production_end_time');
        });
    }

    public function down(): void
    {
        Schema::table('kernel_mesin', function (Blueprint $table) {
            $table->dropColumn('total_production_minutes');
        });
    }
};
