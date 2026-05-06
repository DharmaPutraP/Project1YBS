<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Use raw SQL to avoid requiring doctrine/dbal for simple length change
        DB::statement("ALTER TABLE `oil_foss` MODIFY `operator` VARCHAR(64) NOT NULL");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE `oil_foss` MODIFY `operator` VARCHAR(10) NOT NULL");
    }
};
