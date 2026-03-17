<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KernelBobotConfigSeeder extends Seeder
{
    public function run(): void
    {
        $configs = [
            [
                'jenis'     => 'Claybath',
                'direction' => 'asc',
                'limit_100' => 0,
                'limit_90'  => 0.51,
                'limit_80'  => 1.10,
                'limit_70'  => 1.51,
                'limit_60'  => 2.10,
                'limit_50'  => 2.51,
            ],
            [
                'jenis'     => 'Fibercyclone',
                'direction' => 'asc',
                'limit_100' => 0,
                'limit_90'  => 1.11,
                'limit_80'  => 1.41,
                'limit_70'  => 1.71,
                'limit_60'  => 2.10,
                'limit_50'  => 2.51,
            ],
            [
                'jenis'     => 'LTDS',
                'direction' => 'asc',
                'limit_100' => 0,
                'limit_90'  => 0.51,
                'limit_80'  => 1.10,
                'limit_70'  => 1.51,
                'limit_60'  => 2.10,
                'limit_50'  => 2.51,
            ],
            [
                'jenis'     => 'Inlet Kernel Silo',
                'direction' => 'asc',
                'limit_100' => 6.80,
                'limit_90'  => 7.10,
                'limit_80'  => 7.40,
                'limit_70'  => 7.70,
                'limit_60'  => 8.00,
                'limit_50'  => 9.00,
            ],
            [
                'jenis'     => 'Outlet Kernel Silo',
                'direction' => 'asc',
                'limit_100' => 7.50,
                'limit_90'  => 7.80,
                'limit_80'  => 8.00,
                'limit_70'  => 9.00,
                'limit_60'  => 10.00,
                'limit_50'  => 20.00,
            ],
            [
                'jenis'     => 'Ripple Mill',
                'direction' => 'desc',
                'limit_100' => 99.00,
                'limit_90'  => 98.00,
                'limit_80'  => 97.00,
                'limit_70'  => 96.00,
                'limit_60'  => 95.00,
                'limit_50'  => 94.00,
            ],
            [
                'jenis'     => 'CaCo3',
                'direction' => 'asc',
                'limit_100' => 0,
                'limit_90'  => 2.00,
                'limit_80'  => 2.50,
                'limit_70'  => 3.00,
                'limit_60'  => 3.50,
                'limit_50'  => 4.00,
            ],
            [
                'jenis'     => 'Press',
                'direction' => 'asc',
                'limit_100' => 0,
                'limit_90'  => 10.10,
                'limit_80'  => 15.00,
                'limit_70'  => 20.00,
                'limit_60'  => 25.00,
                'limit_50'  => 35.10,
            ],
        ];

        DB::table('kernel_bobot_configs')
            ->whereIn('jenis', ['Outlet Kernel Silo Moist', 'Press Moist'])
            ->delete();

        DB::table('kernel_bobot_configs')->upsert(
            $configs,
            ['jenis'],
            ['direction', 'limit_100', 'limit_90', 'limit_80', 'limit_70', 'limit_60', 'limit_50']
        );
    }
}
