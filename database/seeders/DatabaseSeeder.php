<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Jalankan seeder role & permission beserta akun admin default terlebih dahulu.
        // Urutan ini penting: user lain yang mungkin dibuat oleh seeder berikutnya
        // sudah bisa langsung di-assign role karena role-nya sudah tersedia.
        $this->call([
            RoleAndPermissionSeeder::class,
            OilMasterDataSeeder::class,
            BobotConfigSeeder::class,
            KernelDataSeeder::class,
        ]);
    }
}

