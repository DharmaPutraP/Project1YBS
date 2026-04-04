<?php

namespace Database\Seeders;

use App\Models\OilMasterData;
use Illuminate\Database\Seeder;

class OilMasterDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Seeder ini berisi master data untuk dropdown Kode dan Jenis
     */
    public function run(): void
    {
        $masterData = [
            // ═══════════════════════════════════════════════════════════════════
            // JUS & CONDENSAT (No specific limits)
            // ═══════════════════════════════════════════════════════════════════
            // ['kode' => 'JBP', 'column_name' => 'JUS BUNCH PRESS', 'jenis' => 'JUS', 'persen' => 0, 'persen4' => 0, 'pivot' => '', 'limitOLWB' => null, 'limitOLDB' => null, 'limitOL' => null, 'description' => 'Jus Bunch Press', 'is_active' => true],
            // ['kode' => 'CD', 'column_name' => 'CONDENSAT', 'jenis' => 'CONDENSAT', 'persen' => 0, 'persen4' => 0, 'pivot' => '', 'limitOLWB' => null, 'limitOLDB' => null, 'limitOL' => null, 'description' => 'Condensat', 'is_active' => true],

            // ═══════════════════════════════════════════════════════════════════
            // CLARIFIER TANK (COT)
            // ═══════════════════════════════════════════════════════════════════
            // ['kode' => 'COT IN', 'column_name' => 'COT INLET to COT 2', 'jenis' => 'COT', 'persen' => 0, 'persen4' => 0, 'pivot' => '', 'limitOLWB' => 45.00, 'limitOLDB' => null, 'limitOL' => null, 'description' => 'COT Inlet to COT 2, OLWB > 45', 'is_active' => true],
            // ['kode' => 'COT 2', 'column_name' => 'U/F COT 2', 'jenis' => 'COT', 'persen' => 0, 'persen4' => 0, 'pivot' => '', 'limitOLWB' => 8, 'limitOLDB' => null, 'limitOL' => null, 'description' => 'Under Flow COT 2, OLWB ≤ 8', 'is_active' => true],
            // ['kode' => 'CST', 'column_name' => 'U/F CST', 'jenis' => 'CST', 'persen' => 0, 'persen4' => 0, 'pivot' => '', 'limitOLWB' => 6, 'limitOLDB' => null, 'limitOL' => null, 'description' => 'Under Flow CST, OLWB ≤ 6', 'is_active' => true],

            // ═══════════════════════════════════════════════════════════════════
            // FEED DECANTER (FD1-4)
            // ═══════════════════════════════════════════════════════════════════
            ['kode' => 'FD1', 'column_name' => 'FEED DECANTER ALVA LAFAL 1', 'jenis' => 'FEED DECANTER', 'persen' => 0, 'persen4' => 0, 'pivot' => 'FEED 1', 'limitOLWB' => 6.00, 'limitOLDB' => null, 'limitOL' => 0.09, 'description' => 'Feed Decanter Alva Laval 1', 'is_active' => true],
            ['kode' => 'FD2', 'column_name' => 'FEED DECANTER IHI 2', 'jenis' => 'FEED DECANTER', 'persen' => 0, 'persen4' => 0, 'pivot' => 'FEED 2', 'limitOLWB' => 6.00, 'limitOLDB' => null, 'limitOL' => 0.09, 'description' => 'Feed Decanter IHI 2', 'is_active' => true],
            ['kode' => 'FD3', 'column_name' => 'FEED DECANTER ALVA LAFAL 2', 'jenis' => 'FEED DECANTER', 'persen' => 0, 'persen4' => 0, 'pivot' => 'FEED 3', 'limitOLWB' => 6.00, 'limitOLDB' => null, 'limitOL' => 0.09, 'description' => 'Feed Decanter Alfa Laval 2', 'is_active' => true],
            ['kode' => 'FD4', 'column_name' => 'FEED DECANTER FLOTTWEG', 'jenis' => 'FEED DECANTER', 'persen' => 0, 'persen4' => 0, 'pivot' => 'FEED 4', 'limitOLWB' => 6.00, 'limitOLDB' => null, 'limitOL' => 0.09, 'description' => 'Feed Decanter Flottweg', 'is_active' => true],

            // ═══════════════════════════════════════════════════════════════════
            // HEAVY PHASE (HP1-4)
            // ═══════════════════════════════════════════════════════════════════
            ['kode' => 'HP1', 'column_name' => 'HEAVY PHASE ALFA LAVAL 1', 'jenis' => 'HEAVY PHASE', 'persen' => 0, 'persen4' => 0, 'pivot' => 'HEAVY PHASE 1', 'limitOLWB' => 1.00, 'limitOLDB' => null, 'limitOL' => null, 'description' => 'Heavy Phase Alfa Laval 1', 'is_active' => true],
            ['kode' => 'HP2', 'column_name' => 'HEAVY PHASE IHI', 'jenis' => 'HEAVY PHASE', 'persen' => 0, 'persen4' => 0, 'pivot' => 'HEAVY PHASE 2', 'limitOLWB' => 1.00, 'limitOLDB' => null, 'limitOL' => null, 'description' => 'Heavy Phase IHI', 'is_active' => true],
            ['kode' => 'HP3', 'column_name' => 'HEAVY PHASE ALFA LAVAL 2', 'jenis' => 'HEAVY PHASE', 'persen' => 0, 'persen4' => 0, 'pivot' => 'HEAVY PHASE 3', 'limitOLWB' => 1.00, 'limitOLDB' => null, 'limitOL' => null, 'description' => 'Heavy Phase Alfa Laval 2', 'is_active' => true],
            ['kode' => 'HP4', 'column_name' => 'HEAVY PHASE FLOTTWEG', 'jenis' => 'HEAVY PHASE', 'persen' => 0, 'persen4' => 0, 'pivot' => 'HEAVY PHASE 4', 'limitOLWB' => 1.00, 'limitOLDB' => null, 'limitOL' => null, 'description' => 'Heavy Phase Flottweg', 'is_active' => true],

            // ═══════════════════════════════════════════════════════════════════
            // SOLID DECANTER (SD1-4)
            // ═══════════════════════════════════════════════════════════════════
            ['kode' => 'SD1', 'column_name' => 'SOLID ALFA LAVAL 1', 'jenis' => 'SOLID', 'persen' => 0.02, 'persen4' => 0, 'pivot' => 'SOLID 1', 'limitOLWB' => 2.50, 'limitOLDB' => null, 'limitOL' => 0.09, 'description' => 'Solid Alfa Laval 1', 'is_active' => true],
            ['kode' => 'SD2', 'column_name' => 'SOLID IHI', 'jenis' => 'SOLID', 'persen' => 0.02, 'persen4' => 0, 'pivot' => 'SOLID 2', 'limitOLWB' => 2.50, 'limitOLDB' => null, 'limitOL' => 0.09, 'description' => 'Solid IHI', 'is_active' => true],
            ['kode' => 'SD3', 'column_name' => 'SOLID ALFA LAVAL 2', 'jenis' => 'SOLID', 'persen' => 0.02, 'persen4' => 0, 'pivot' => 'SOLID 3', 'limitOLWB' => 2.50, 'limitOLDB' => null, 'limitOL' => 0.09, 'description' => 'Solid Alfa Laval 2', 'is_active' => true],
            ['kode' => 'SD4', 'column_name' => 'SOLID FLOTTWEG', 'jenis' => 'SOLID', 'persen' => 0.02, 'persen4' => 0, 'pivot' => 'SOLID 4', 'limitOLWB' => 2.50, 'limitOLDB' => null, 'limitOL' => 0.09, 'description' => 'Solid Flottweg', 'is_active' => true],

            // ═══════════════════════════════════════════════════════════════════
            // HEAVY PHASE CENTRIFUGE (HPL1-3)
            // ═══════════════════════════════════════════════════════════════════
            ['kode' => 'HPL1', 'column_name' => 'HEAVY PHASE CENTRIFUGE 1', 'jenis' => 'CENTRIFUGE', 'persen' => 0, 'persen4' => 0, 'pivot' => 'CENTRIFUGE 1', 'limitOLWB' => 1.00, 'limitOLDB' => null, 'limitOL' => null, 'description' => 'Heavy Phase Centrifuge 1', 'is_active' => true],
            ['kode' => 'HPL2', 'column_name' => 'HEAVY PHASE CENTRIFUGE 2', 'jenis' => 'CENTRIFUGE', 'persen' => 0, 'persen4' => 0, 'pivot' => 'CENTRIFUGE 2', 'limitOLWB' => 1.00, 'limitOLDB' => null, 'limitOL' => null, 'description' => 'Heavy Phase Centrifuge 2', 'is_active' => true],
            ['kode' => 'HPL3', 'column_name' => 'HEAVY PHASE CENTRIFUGE 3', 'jenis' => 'CENTRIFUGE', 'persen' => 0, 'persen4' => 0, 'pivot' => 'CENTRIFUGE 3', 'limitOLWB' => 1.00, 'limitOLDB' => null, 'limitOL' => null, 'description' => 'Heavy Phase Centrifuge 3', 'is_active' => true],

            // ═══════════════════════════════════════════════════════════════════
            // EFFLUENT & OTHERS
            // ═══════════════════════════════════════════════════════════════════
            // ['kode' => 'LP', 'column_name' => 'LIGHT PHASE', 'jenis' => 'LIGHT PHASE', 'persen' => 0, 'persen4' => 0, 'pivot' => '', 'limitOLWB' => null, 'limitOLDB' => null, 'limitOL' => null, 'description' => 'Light Phase', 'is_active' => true],
            ['kode' => 'FE', 'column_name' => 'FINAL EFFLUENT', 'jenis' => 'EFFLUENT', 'persen' => 0.5, 'persen4' => 0, 'pivot' => 'EFFLUENT', 'limitOLWB' => 0.70, 'limitOLDB' => null, 'limitOL' => 0.5, 'description' => 'Final Effluent', 'is_active' => true],
            // ['kode' => 'ST', 'column_name' => 'SLUDGE TANK', 'jenis' => 'SLUDGE', 'persen' => 0, 'persen4' => 0, 'pivot' => '', 'limitOLWB' => null, 'limitOLDB' => null, 'limitOL' => null, 'description' => 'Sludge Tank', 'is_active' => true],
            // ['kode' => 'JK1', 'column_name' => 'JANJANGAN KOSONG 1', 'jenis' => 'JANJANGAN', 'persen' => 0, 'persen4' => 0, 'pivot' => '', 'limitOLWB' => null, 'limitOLDB' => null, 'limitOL' => null, 'description' => 'Janjangan Kosong 1', 'is_active' => true],

            // ═══════════════════════════════════════════════════════════════════
            // FIBRE EX BUNCH PRESS (FBP1-5)
            // ═══════════════════════════════════════════════════════════════════
            ['kode' => 'FBP1', 'column_name' => 'FIBRE EX BUNCH PRESS 1', 'jenis' => 'BUNCH PRESS', 'persen' => 0.13, 'persen4' => 0, 'pivot' => 'BUNCH PRESS 1', 'limitOLWB' => 1.30, 'limitOLDB' => null, 'limitOL' => 0.75, 'description' => 'Fibre Ex Bunch Press 1', 'is_active' => true],
            ['kode' => 'FBP2', 'column_name' => 'FIBRE EX BUNCH PRESS 2', 'jenis' => 'BUNCH PRESS', 'persen' => 0.13, 'persen4' => 0, 'pivot' => 'BUNCH PRESS 2', 'limitOLWB' => 1.30, 'limitOLDB' => null, 'limitOL' => 0.75, 'description' => 'Fibre Ex Bunch Press 2', 'is_active' => true],
            ['kode' => 'FBP3', 'column_name' => 'FIBRE EX BUNCH PRESS 3', 'jenis' => 'BUNCH PRESS', 'persen' => 0.13, 'persen4' => 0, 'pivot' => 'BUNCH PRESS 3', 'limitOLWB' => 1.30, 'limitOLDB' => null, 'limitOL' => 0.75, 'description' => 'Fibre Ex Bunch Press 3', 'is_active' => true],
            ['kode' => 'FBP4', 'column_name' => 'FIBRE EX BUNCH PRESS 4', 'jenis' => 'BUNCH PRESS', 'persen' => 0.13, 'persen4' => 0, 'pivot' => 'BUNCH PRESS 4', 'limitOLWB' => 1.30, 'limitOLDB' => null, 'limitOL' => 0.75, 'description' => 'Fibre Ex Bunch Press 4', 'is_active' => true],
            ['kode' => 'FBP5', 'column_name' => 'FIBRE EX BUNCH PRESS 5', 'jenis' => 'BUNCH PRESS', 'persen' => 0.13, 'persen4' => 0, 'pivot' => 'BUNCH PRESS 5', 'limitOLWB' => 1.30, 'limitOLDB' => null, 'limitOL' => 0.20, 'description' => 'Fibre Ex Bunch Press 5', 'is_active' => true],

            // ═══════════════════════════════════════════════════════════════════
            // FIBRE PRESS (FP1-9)
            // ═══════════════════════════════════════════════════════════════════
            ['kode' => 'FP1', 'column_name' => 'FIBRE PRESS 1', 'jenis' => 'PRESS', 'persen' => 0.13, 'persen4' => 0, 'pivot' => 'PRESS 1', 'limitOLWB' => 3.80, 'limitOLDB' => 7.50, 'limitOL' => 0.50, 'description' => 'Fibre Press 1', 'is_active' => true],
            ['kode' => 'FP2', 'column_name' => 'FIBRE PRESS 2', 'jenis' => 'PRESS', 'persen' => 0.13, 'persen4' => 0, 'pivot' => 'PRESS 2', 'limitOLWB' => 3.80, 'limitOLDB' => 7.50, 'limitOL' => 0.50, 'description' => 'Fibre Press 2', 'is_active' => true],
            ['kode' => 'FP3', 'column_name' => 'FIBRE PRESS 3', 'jenis' => 'PRESS', 'persen' => 0.13, 'persen4' => 0, 'pivot' => 'PRESS 3', 'limitOLWB' => 3.80, 'limitOLDB' => 7.50, 'limitOL' => 0.50, 'description' => 'Fibre Press 3', 'is_active' => true],
            ['kode' => 'FP4', 'column_name' => 'FIBRE PRESS 4', 'jenis' => 'PRESS', 'persen' => 0.13, 'persen4' => 0, 'pivot' => 'PRESS 4', 'limitOLWB' => 3.80, 'limitOLDB' => 7.50, 'limitOL' => 0.50, 'description' => 'Fibre Press 4', 'is_active' => true],
            ['kode' => 'FP5', 'column_name' => 'FIBRE PRESS 5', 'jenis' => 'PRESS', 'persen' => 0.13, 'persen4' => 0, 'pivot' => 'PRESS 5', 'limitOLWB' => 3.80, 'limitOLDB' => 7.50, 'limitOL' => 0.50, 'description' => 'Fibre Press 5', 'is_active' => true],
            ['kode' => 'FP6', 'column_name' => 'FIBRE PRESS 6', 'jenis' => 'PRESS', 'persen' => 0.13, 'persen4' => 0, 'pivot' => 'PRESS 6', 'limitOLWB' => 3.80, 'limitOLDB' => 7.50, 'limitOL' => 0.50, 'description' => 'Fibre Press 6', 'is_active' => true],
            ['kode' => 'FP7', 'column_name' => 'FIBRE PRESS 7', 'jenis' => 'PRESS', 'persen' => 0.13, 'persen4' => 0, 'pivot' => 'PRESS 7', 'limitOLWB' => 3.80, 'limitOLDB' => 7.50, 'limitOL' => 0.50, 'description' => 'Fibre Press 7', 'is_active' => true],
            ['kode' => 'FP8', 'column_name' => 'FIBRE PRESS 8', 'jenis' => 'PRESS', 'persen' => 0.13, 'persen4' => 0, 'pivot' => 'PRESS 8', 'limitOLWB' => 3.80, 'limitOLDB' => 7.50, 'limitOL' => 0.50, 'description' => 'Fibre Press 8', 'is_active' => true],
            ['kode' => 'FP9', 'column_name' => 'FIBRE PRESS 9', 'jenis' => 'PRESS', 'persen' => 0.13, 'persen4' => 0, 'pivot' => 'PRESS 9', 'limitOLWB' => 3.80, 'limitOLDB' => 7.50, 'limitOL' => 0.50, 'description' => 'Fibre Press 9', 'is_active' => true],
        ];

        // Delete existing data (cannot truncate due to foreign key constraint)
        OilMasterData::query()->delete();

        foreach ($masterData as $data) {
            OilMasterData::create($data);
        }

        $this->command->info('✅ Lab master data seeded successfully!');
        $this->command->info('📊 Total records: ' . count($masterData));
        $this->command->info('📁 Data source: 02 Februari OIL LOSSES 26 Report - Vlookup.csv');
    }
}