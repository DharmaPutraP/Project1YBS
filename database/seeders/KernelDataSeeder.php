<?php

namespace Database\Seeders;

use App\Models\KernelMasterData;
use Illuminate\Database\Seeder;

class KernelDataSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            // ── Fibre Cyclone ──────────────────────────────────────────────────
            ['kode' => 'FC1', 'nama_sample' => 'FIBRE CYCLONE1', 'limit_operator' => 'le', 'limit_value' => 1.450],
            ['kode' => 'FC2', 'nama_sample' => 'FIBRE CYCLONE2', 'limit_operator' => 'le', 'limit_value' => 1.450],

            // ── LTDS ───────────────────────────────────────────────────────────
            ['kode' => 'L1', 'nama_sample' => 'LTDS 1', 'limit_operator' => 'le', 'limit_value' => 1.000],
            ['kode' => 'L2', 'nama_sample' => 'LTDS 2', 'limit_operator' => 'le', 'limit_value' => 1.000],
            ['kode' => 'L3', 'nama_sample' => 'LTDS 3', 'limit_operator' => 'le', 'limit_value' => 1.000],
            ['kode' => 'L4', 'nama_sample' => 'LTDS 4', 'limit_operator' => 'le', 'limit_value' => 1.000],

            // ── Claybath Wet Shell ─────────────────────────────────────────────
            ['kode' => 'CWS', 'nama_sample' => 'CLAYBATH WET SHELL 1', 'limit_operator' => 'le', 'limit_value' => 1.000],
            ['kode' => 'CWS2', 'nama_sample' => 'CLAYBATH WET SHELL 2', 'limit_operator' => 'le', 'limit_value' => 1.000],
            ['kode' => 'CWS3', 'nama_sample' => 'CLAYBATH WET SHELL 3', 'limit_operator' => 'le', 'limit_value' => 1.000],

            // ── Inlet Kernel Silo ──────────────────────────────────────────────
            ['kode' => 'IN1', 'nama_sample' => 'INLET KERNEL SILO 1', 'limit_operator' => 'le', 'limit_value' => 8.000],
            ['kode' => 'IN2', 'nama_sample' => 'INLET KERNEL SILO 2', 'limit_operator' => 'le', 'limit_value' => 8.000],
            ['kode' => 'IN3', 'nama_sample' => 'INLET KERNEL SILO 3', 'limit_operator' => 'le', 'limit_value' => 8.000],
            ['kode' => 'IN4', 'nama_sample' => 'INLET KERNEL SILO 4', 'limit_operator' => 'le', 'limit_value' => 8.000],
            ['kode' => 'IN5', 'nama_sample' => 'INLET KERNEL SILO 5', 'limit_operator' => 'le', 'limit_value' => 8.000],

            // ── Outlet Kernel Silo ─────────────────────────────────────────────
            ['kode' => 'OUT1', 'nama_sample' => 'OUTLET KERNEL SILO 1 TO BUNKER', 'limit_operator' => 'le', 'limit_value' => 8.000],
            ['kode' => 'OUT2', 'nama_sample' => 'OUTLET KERNEL SILO 2 TO BUNKER', 'limit_operator' => 'le', 'limit_value' => 8.000],
            ['kode' => 'OUT3', 'nama_sample' => 'OUTLET KERNEL SILO 3 TO BUNKER', 'limit_operator' => 'le', 'limit_value' => 8.000],
            ['kode' => 'OUT4', 'nama_sample' => 'OUTLET KERNEL SILO 4 TO BUNKER', 'limit_operator' => 'le', 'limit_value' => 8.000],
            ['kode' => 'OUT5', 'nama_sample' => 'OUTLET KERNEL SILO 5 TO BUNKER', 'limit_operator' => 'le', 'limit_value' => 8.000],

            // ── Press ──────────────────────────────────────────────────────────
            ['kode' => 'P1', 'nama_sample' => 'PRESS 1', 'limit_operator' => 'le', 'limit_value' => 15.000],
            ['kode' => 'P2', 'nama_sample' => 'PRESS 2', 'limit_operator' => 'le', 'limit_value' => 15.000],
            ['kode' => 'P3', 'nama_sample' => 'PRESS 3', 'limit_operator' => 'le', 'limit_value' => 15.000],
            ['kode' => 'P4', 'nama_sample' => 'PRESS 4', 'limit_operator' => 'le', 'limit_value' => 15.000],
            ['kode' => 'P5', 'nama_sample' => 'PRESS 5', 'limit_operator' => 'le', 'limit_value' => 15.000],
            ['kode' => 'P6', 'nama_sample' => 'PRESS 6', 'limit_operator' => 'le', 'limit_value' => 15.000],
            ['kode' => 'P7', 'nama_sample' => 'PRESS 7', 'limit_operator' => 'le', 'limit_value' => 15.000],
            ['kode' => 'P8', 'nama_sample' => 'PRESS 8', 'limit_operator' => 'le', 'limit_value' => 15.000],
            ['kode' => 'P9', 'nama_sample' => 'PRESS 9', 'limit_operator' => 'le', 'limit_value' => 15.000],

            // ── Ripple Mill ────────────────────────────────────────────────────
            ['kode' => 'R1', 'nama_sample' => 'Ripple Mill No. 1', 'limit_operator' => 'gt', 'limit_value' => 98.000],
            ['kode' => 'R2', 'nama_sample' => 'Ripple Mill No. 2', 'limit_operator' => 'gt', 'limit_value' => 98.000],
            ['kode' => 'R3', 'nama_sample' => 'Ripple Mill No. 3', 'limit_operator' => 'gt', 'limit_value' => 98.000],
            ['kode' => 'R4', 'nama_sample' => 'Ripple Mill No. 4', 'limit_operator' => 'gt', 'limit_value' => 98.000],
            ['kode' => 'R5', 'nama_sample' => 'Ripple Mill No. 5', 'limit_operator' => 'gt', 'limit_value' => 98.000],
            ['kode' => 'R6', 'nama_sample' => 'Ripple Mill No. 6', 'limit_operator' => 'gt', 'limit_value' => 98.000],
            ['kode' => 'R7', 'nama_sample' => 'Ripple Mill No. 7', 'limit_operator' => 'gt', 'limit_value' => 98.000],

            // ── Destoner ───────────────────────────────────────────────────────
            ['kode' => 'D1', 'nama_sample' => 'DESTONER 1', 'limit_operator' => 'le', 'limit_value' => 0.003],
            ['kode' => 'D2', 'nama_sample' => 'DESTONER 2', 'limit_operator' => 'le', 'limit_value' => 0.003],
        ];

        foreach ($data as $row) {
            KernelMasterData::updateOrCreate(
                ['kode' => $row['kode']],
                array_merge($row, ['is_active' => true])
            );
        }
    }
}
