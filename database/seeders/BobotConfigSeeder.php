<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BobotConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $configs = [
            [
                'jenis' => 'Press',
                'limit_100' => 3.5,
                'limit_90' => 3.81,
                'limit_80' => 4.1,
                'limit_70' => 4.4,
                'limit_60' => 4.7,
                'limit_50' => 5,
            ],
            [
                'jenis' => 'Bunch Press 1',
                'limit_100' => 1,
                'limit_90' => 1.31,
                'limit_80' => 1.61,
                'limit_70' => 1.91,
                'limit_60' => 2.21,
                'limit_50' => 2.26,
            ],
            [
                'jenis' => 'SOLID',
                'limit_100' => 2,
                'limit_90' => 2.41,
                'limit_80' => 2.81,
                'limit_70' => 3.21,
                'limit_60' => 3.61,
                'limit_50' => 4,
            ],
            [
                'jenis' => 'HEAVY PHASE',
                'limit_100' => 0.7,
                'limit_90' => 0.81,
                'limit_80' => 0.91,
                'limit_70' => 1.11,
                'limit_60' => 1.21,
                'limit_50' => 1.31,
            ],
            [
                'jenis' => 'Effluent',
                'limit_100' => 0.5,
                'limit_90' => 0.71,
                'limit_80' => 0.91,
                'limit_70' => 1.11,
                'limit_60' => 1.31,
                'limit_50' => 1.51,
            ],
            [
                'jenis' => 'FEED Decanter',
                'limit_100' => 6,
                'limit_90' => 8,
                'limit_80' => 10,
                'limit_70' => 12,
                'limit_60' => 14,
                'limit_50' => 16,
            ],
        ];

        DB::table('bobot_configs')->insert($configs);
    }
}
