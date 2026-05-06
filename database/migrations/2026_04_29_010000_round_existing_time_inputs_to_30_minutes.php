<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $updates = [
            ['table' => 'FFA_Moisture', 'column' => 'jam'],
            ['table' => 'Spintest_cot', 'column' => 'jam'],
            ['table' => 'spintest_cst', 'column' => 'jam'],
            ['table' => 'spintest_feed_decanter', 'column' => 'jam'],
            ['table' => 'spintest_light_phase', 'column' => 'jam'],
            ['table' => 'analisa_usb', 'column' => 'jam'],
            ['table' => 'oil_foss', 'column' => 'waktu'],
        ];

        foreach ($updates as $item) {
            $table = $item['table'];
            $column = $item['column'];

            DB::statement(
                "UPDATE `{$table}`\n" .
                "SET `{$column}` = DATE_FORMAT(\n" .
                "    SEC_TO_TIME(FLOOR(TIME_TO_SEC(STR_TO_DATE(`{$column}`, '%H:%i')) / 1800) * 1800),\n" .
                "    '%H:%i'\n" .
                ")\n" .
                "WHERE `{$column}` IS NOT NULL\n" .
                "  AND `{$column}` <> ''\n" .
                "  AND `{$column}` REGEXP '^[0-2][0-9]:[0-5][0-9](:[0-5][0-9])?$'"
            );
        }
    }

    public function down(): void
    {
        // Data migration is irreversible because original minute values are lost after rounding.
    }
};
