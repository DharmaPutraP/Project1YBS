<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Daftar semua permission yang tersedia dalam sistem,
     * dikelompokkan berdasarkan modul agar mudah dikelola.
     */
    private array $permissions = [

        // ── Manajemen Pengguna & Akses ──────────────────────────────────────
        'manage users',       // Tambah / ubah / hapus akun pengguna
        'manage roles',       // Assign atau cabut role dari pengguna

        // ── Dashboard ───────────────────────────────────────────────────────
        'view dashboard',

        // ── Laboratorium ────────────────────────────────────────────────────
        'view lab',           // Melihat halaman & data lab
        'input lab results',  // Memasukkan hasil analisa lab
        'approve lab results',// Menyetujui / finalisasi hasil lab

        // ── Timbangan (Weighbridge) ──────────────────────────────────────────
        'view timbangan',     // Melihat data timbangan
        'input timbangan',    // Memasukkan data timbangan

        // ── Laporan ─────────────────────────────────────────────────────────
        'view reports',       // Melihat laporan
        'export reports',     // Mengunduh / ekspor laporan

        // ── Pengaturan Sistem ────────────────────────────────────────────────
        'manage settings',    // Konfigurasi umum aplikasi
    ];

    /**
     * Pemetaan role → permission.
     *
     * Catatan: Super Admin tidak perlu entry di sini karena ia mendapat
     * SEMUA permission lewat Permission::all() di bawah.
     */
    private array $rolePermissions = [

        'Mill Manager' => [
            'view dashboard',
            'view lab',
            'approve lab results',
            'view timbangan',
            'view reports',
            'export reports',
        ],

        'Kepala Lab' => [
            'view dashboard',
            'view lab',
            'input lab results',
            'approve lab results',
            'view reports',
        ],

        'Staf Lab' => [
            'view dashboard',
            'view lab',
            'input lab results',
        ],

        'Admin Timbangan' => [
            'view dashboard',
            'view timbangan',
            'input timbangan',
            'view reports',
        ],
    ];

    public function run(): void
    {
        // Reset cache agar perubahan langsung aktif
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // ── 1. Buat semua Permission ─────────────────────────────────────────
        foreach ($this->permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // ── 2. Buat Role Super Admin & beri SEMUA permission ─────────────────
        $superAdmin = Role::firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
        $superAdmin->syncPermissions(Permission::all());

        // ── 3. Buat Role lain & assign permission sesuai peta di atas ────────
        foreach ($this->rolePermissions as $roleName => $permissions) {
            $role = Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
            $role->syncPermissions($permissions);
        }

        // ── 4. Buat akun default Super Admin ─────────────────────────────────
        $adminUser = User::firstOrCreate(
            ['username' => 'admin'],
            [
                'name' => 'Super Administrator',
                'email' => 'admin@ybs.local',
                'password' => Hash::make('admin123'), // Ganti password ini sebelum production!
            ]
        );

        // Jika user sudah ada, pastikan rolenya tetap Super Admin
        // syncRoles() juga berguna saat seseorang *dipindah* ke group lain:
        //   $user->syncRoles(['Mill Manager']);
        $adminUser->syncRoles(['Super Admin']);

        $this->command->info('✅  Roles, permissions, dan akun admin berhasil dibuat.');
        $this->command->table(
            ['Role', 'Jumlah Permission'],
            Role::with('permissions')->get()->map(fn($r) => [
                $r->name,
                $r->permissions->count(),
            ])->toArray()
        );
    }
}
