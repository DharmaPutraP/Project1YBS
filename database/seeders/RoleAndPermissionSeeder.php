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
     * 
     * Pola Penamaan Permission: <action> <module>
     * Actions: view, create, edit, delete, approve, export, print
     * 
     * Strategy: Module-based permissions untuk scalability
     * - Oil Losses permissions (implemented)
     * - Future: Kernel Losses permissions (when needed)
     * - Future: Dispatch Losses permissions (when needed)
     */
    private array $permissions = [
        // ═══════════════════════════════════════════════════════════════
        // GENERAL / DASHBOARD
        // ═══════════════════════════════════════════════════════════════
        'view dashboard',

        // ═══════════════════════════════════════════════════════════════
        // USER MANAGEMENT MODULE
        // ═══════════════════════════════════════════════════════════════
        'view users',
        'create users',
        'edit users',
        'delete users',
        'reset user password',
        'view user activity log',

        // ═══════════════════════════════════════════════════════════════
        // OIL LOSSES MODULE
        // ═══════════════════════════════════════════════════════════════
        'view oil losses',                          // View oil losses data entry page
        'create oil losses',                        // Create new oil losses record
        'edit oil losses',                          // Edit existing oil losses record
        'delete oil losses',                        // Delete oil losses record

        'view olwb',                                // View OLWB (Oil Losses Work Book)
        'export olwb reports',                      // Export OLWB to Excel

        'view performance oil losses',              // View performance/bobot report
        'export performance reports oil losses',    // Export performance report

        'view laporan oil losses',                  // View comprehensive report
        'export laporan oil losses',                // Export comprehensive report

        'view kernel losses',
        'create kernel losses',
        'edit kernel losses',
        'delete kernel losses',

        'view rekap kernel losses',
        'export rekap kernel losses',

        'view performance kernel losses',
        'export performance kernel losses',

        'view laporan kernel losses',
        'export laporan kernel losses',
    ];
    private array $rolePermissions = [

        // ══════════════════════════════════════════════════════════════════════
        // ── PPIC (Production Planning & Inventory Control) ────────────────────
        // Supervisor untuk SEMUA module losses
        // Full access: view, create, edit, delete, export
        // office = NULL (bisa lihat semua office: YBS, SUN, SJN)
        // ══════════════════════════════════════════════════════════════════════
        'PPIC' => [
            'view dashboard',

            // User Management
            'view users',
            'view user activity log',

            // Oil Losses Module - FULL ACCESS
            'view oil losses',
            'create oil losses',
            'edit oil losses',
            'delete oil losses',
            'view olwb',
            'export olwb reports',
            'view performance oil losses',
            'export performance reports oil losses',
            'view laporan oil losses',
            'export laporan oil losses',

            'view kernel losses',
            'create kernel losses',
            'edit kernel losses',
            'delete kernel losses',

            'view rekap kernel losses',
            'export rekap kernel losses',

            'view performance kernel losses',
            'export performance kernel losses',

            'view laporan kernel losses',
            'export laporan kernel losses',

        ],

        'Sampel Boy Oil Losses' => [
            'view dashboard',
            'view oil losses',
            'create oil losses',
            'view laporan oil losses',

        ],

        'Analis' => [
            'view dashboard',
            'view oil losses',
            'create oil losses',
            'view laporan oil losses',

            'view kernel losses',
            'create kernel losses',
            'edit kernel losses',
            'delete kernel losses',

            'view rekap kernel losses',
            'export rekap kernel losses',

            'view performance kernel losses',
            'export performance kernel losses',

            'view laporan kernel losses',
            'export laporan kernel losses',
        ],

        'Sampel Boy Kernel Losses' => [
            'view dashboard',

            'view kernel losses',
            'create kernel losses',
            'edit kernel losses',
            'delete kernel losses',

            'view rekap kernel losses',
            'export rekap kernel losses',

            'view performance kernel losses',
            'export performance kernel losses',

            'view laporan kernel losses',
            'export laporan kernel losses',

        ],

        'Asisten Lab' => [
            'view dashboard',
            'view oil losses',
            'edit oil losses',
            'view olwb',
            'view performance oil losses',
            'view laporan oil losses',

        ],

        'PCM' => [
            'view dashboard',
            'view oil losses',
            'view olwb',
            'view performance oil losses',
            'view laporan oil losses',

        ],

        'Direksi' => [
            'view dashboard',

            // Oil Losses Module - View Only
            'view oil losses',
            'view olwb',
            'view performance oil losses',
            'view laporan oil losses',

        ],

        'Koor Sistem Informasi' => [
            'view dashboard',

            // Oil Losses Module - View Only
            'view oil losses',
            'view olwb',
            'view performance oil losses',
            'view laporan oil losses',

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
                'office' => null,  // NULL = can see ALL offices
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
