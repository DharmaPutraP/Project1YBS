<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('kernel_dirt_moist_calculations')
            ->where(function ($query) {
                $query->where('kode', 'like', 'IN%')
                    ->orWhere('kode', 'like', 'OUT%');
            })
            ->update([
                'moist_limit_operator' => 'le',
                'moist_limit_value' => 6.000,
            ]);
    }

    public function down(): void
    {
        DB::table('kernel_dirt_moist_calculations')
            ->where(function ($query) {
                $query->where('kode', 'like', 'IN%')
                    ->orWhere('kode', 'like', 'OUT%');
            })
            ->where('kode', 'like', 'OUT%')
            ->update([
                'moist_limit_operator' => 'le',
                'moist_limit_value' => 7.000,
            ]);

        DB::table('kernel_dirt_moist_calculations')
            ->where('kode', 'like', 'IN%')
            ->update([
                'moist_limit_operator' => null,
                'moist_limit_value' => null,
            ]);
    }
};
