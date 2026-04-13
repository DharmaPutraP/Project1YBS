<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RoleAndPermissionSeeder extends Seeder
{
    private array $defaultSampleBoyUsers = [
        [
            'name' => 'ARYA GAUTAMA S.',
            'username' => 'Arya',
            'office' => 'YBS',
            'password' => 'arya',
            'roles' => ['Sampel Boy Oil Losses', 'Sampel Boy Kernel Losses'],
        ],
        [
            'name' => 'DENNY SAPITRA',
            'username' => 'Denny',
            'office' => 'YBS',
            'password' => 'denny',
            'roles' => ['Sampel Boy Oil Losses', 'Sampel Boy Kernel Losses'],
        ],
        [
            'name' => 'ELIZER YOSUA',
            'username' => 'Elizer',
            'office' => 'YBS',
            'password' => 'elizer',
            'roles' => ['Sampel Boy Oil Losses', 'Sampel Boy Kernel Losses'],
        ],
        [
            'name' => 'OLOAN S.M. SIMANJUNTAK',
            'username' => 'Oloan',
            'office' => 'YBS',
            'password' => 'oloan',
            'roles' => ['Sampel Boy Oil Losses', 'Sampel Boy Kernel Losses'],
        ],
        [
            'name' => 'PATRISIUS CHARLOS SITUMORANG',
            'username' => 'Patrisius',
            'office' => 'YBS',
            'password' => 'patrisius',
            'roles' => ['Sampel Boy Oil Losses', 'Sampel Boy Kernel Losses'],
        ],
        [
            'name' => 'RAHMAT HIDAYAT',
            'username' => 'Dayat',
            'office' => 'YBS',
            'password' => 'dayat',
            'roles' => ['Sampel Boy Oil Losses', 'Sampel Boy Kernel Losses'],
        ],
        [
            'name' => 'Ringki Napola',
            'username' => 'Ringki',
            'office' => 'YBS',
            'password' => 'ringki',
            'roles' => ['Sampel Boy Oil Losses', 'Sampel Boy Kernel Losses'],
        ],
        [
            'name' => 'Dodi Irwan Sahputra Butar-butar',
            'username' => 'Dodi',
            'office' => 'SUN',
            'password' => 'dodi',
            'roles' => ['Sampel Boy Oil Losses', 'Sampel Boy Kernel Losses'],
        ],
        [
            'name' => 'Vega Prayoga',
            'username' => 'Vega',
            'office' => 'SUN',
            'password' => 'vega',
            'roles' => ['Sampel Boy Oil Losses', 'Sampel Boy Kernel Losses'],
        ],
        [
            'name' => 'Wahyu Rizki Maulana',
            'username' => 'Rizki',
            'office' => 'SUN',
            'password' => 'rizki',
            'roles' => ['Sampel Boy Oil Losses', 'Sampel Boy Kernel Losses'],
        ],
        [
            'name' => 'Andriansyah Lubis',
            'username' => 'Andriansyah',
            'office' => 'SUN',
            'password' => 'andriansyah',
            'roles' => ['Sampel Boy Oil Losses', 'Sampel Boy Kernel Losses'],
        ],
        [
            'name' => 'Pengarepen perangin-angin',
            'username' => 'Pengarepen',
            'office' => 'SUN',
            'password' => 'pengarepen',
            'roles' => ['Sampel Boy Oil Losses', 'Sampel Boy Kernel Losses'],
        ],
        [
            'name' => 'Dwika Raja Syahputra Purba',
            'username' => 'Purba',
            'office' => 'SUN',
            'password' => 'purba',
            'roles' => ['analis'],
        ],
        [
            'name' => 'Surya Baihaqi',
            'username' => 'Surya',
            'office' => 'SUN',
            'password' => 'surya',
            'roles' => ['analis'],
        ],
        [
            'name' => 'Silvyana Novira',
            'username' => 'Vira',
            'office' => 'YBS',
            'password' => 'vira',
            'roles' => ['PPIC'],
        ],

    ];

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

        'view informasi proses mesin',
        'create informasi proses mesin',
        'edit informasi proses mesin',
        'create jam proses mesin',
        'edit jam proses mesin',
        'view performance sampel boy'
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

            'view users',
            'view user activity log',

            'view oil losses',
            'view olwb',
            'export olwb reports',
            'view performance oil losses',
            'export performance reports oil losses',
            'view laporan oil losses',
            'export laporan oil losses',

            'view kernel losses',
            'view rekap kernel losses',
            'export rekap kernel losses',

            'view performance kernel losses',
            'export performance kernel losses',

            'view laporan kernel losses',
            'export laporan kernel losses',
            'view performance sampel boy',
            'view informasi proses mesin',
            'create informasi proses mesin',
            'edit informasi proses mesin',
            'create jam proses mesin',
            'edit jam proses mesin',

        ],

        'Sampel Boy Oil Losses' => [
            'view dashboard',
            'view oil losses',
            'create oil losses',
            'view olwb',
            'export olwb reports',
            'view laporan oil losses',
            'export laporan oil losses',
            'view informasi proses mesin',
        ],

        'Sampel Boy Kernel Losses' => [
            'view dashboard',

            'view kernel losses',
            'create kernel losses',
            'view laporan kernel losses',
            'export laporan kernel losses',
            'view rekap kernel losses',
            'export rekap kernel losses',
            'view informasi proses mesin',
            'create jam proses mesin',
            'edit jam proses mesin',
        ],

        'Analis' => [
            'view dashboard',
            'view oil losses',
            'create oil losses',
            'view olwb',
            'export olwb reports',
            'view laporan oil losses',
            'export laporan oil losses',

            'view kernel losses',
            'create kernel losses',

            'view rekap kernel losses',
            'export rekap kernel losses',

            'view performance kernel losses',
            'export performance kernel losses',

            'view laporan kernel losses',
            'export laporan kernel losses',

            'view informasi proses mesin',
            'create informasi proses mesin',
            'edit informasi proses mesin',
            'create jam proses mesin',
            'edit jam proses mesin',

            'view performance sampel boy'

        ],

        'Asisten Lab' => [
            'view dashboard',
            'view oil losses',
            'view olwb',
            'view performance oil losses',
            'view laporan oil losses',
            'view informasi proses mesin',
            'create jam proses mesin',
            'edit jam proses mesin',
            'view performance sampel boy',
            'view kernel losses',

            'view rekap kernel losses',
            'export rekap kernel losses',

            'view performance kernel losses',
            'export performance kernel losses',
        ],

        'Manager' => [
            'view dashboard',
            'view oil losses',
            'view olwb',
            'export olwb reports',
            'view performance oil losses',
            'export performance reports oil losses',

            'view laporan oil losses',
            'export laporan oil losses',

            'view kernel losses',
            'view rekap kernel losses',
            'export rekap kernel losses',

            'view performance kernel losses',
            'export performance kernel losses',

            'view laporan kernel losses',
            'export laporan kernel losses',
            'view informasi proses mesin',
            'view performance sampel boy',

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
                'password' => 'admin123', // Ganti password ini sebelum production!
            ]
        );

        // Jika user sudah ada, pastikan rolenya tetap Super Admin
        // syncRoles() juga berguna saat seseorang *dipindah* ke group lain:
        //   $user->syncRoles(['Mill Manager']);
        $adminUser->syncRoles(['Super Admin']);

        // ── 5. Buat akun default Sampel Boy + assign role ───────────────────
        $this->seedDefaultSampleBoyUsers();

        $this->command->info('✅  Roles, permissions, dan akun admin berhasil dibuat.');
        $this->command->table(
            ['Role', 'Jumlah Permission'],
            Role::with('permissions')->get()->map(fn($r) => [
                $r->name,
                $r->permissions->count(),
            ])->toArray()
        );
    }

    private function seedDefaultSampleBoyUsers(): void
    {
        foreach ($this->defaultSampleBoyUsers as $sampleUser) {
            $user = User::withTrashed()->firstOrNew([
                'username' => $sampleUser['username'],
            ]);

            if ($user->exists && method_exists($user, 'trashed') && $user->trashed()) {
                $user->restore();
            }

            $user->name = $sampleUser['name'];
            $user->office = $sampleUser['office'];
            $user->email = $user->email ?: null;
            $user->password = $sampleUser['password'];
            $user->save();

            $user->syncRoles($sampleUser['roles']);
        }
    }
}
