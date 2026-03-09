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
     */
    private array $permissions = [
        'view dashboard',

        'view users',                     // Lihat daftar pengguna
        'create users',                   // Tambah pengguna baru
        'edit users',                     // Edit data pengguna
        'delete users',                   // Hapus pengguna
        'reset user password',            // Reset password pengguna lain
        'view user activity log',         // Lihat log aktivitas pengguna

        'view oil losses',                       // Lihat halaman lab
        'create oil losses',             // Daftarkan sampel baru
        'edit oil losses',               // Edit info sampel
        'delete oil losses',             // Hapus sampel

        'view olwb',
        'export olwb reports',
        'view performance oil losses',
        'export performance reports oil losses',

        'view laporan oil losses',
        'export laporan oil losses',                 // Download/export laporan
    ];

    /**
     * Pemetaan role → permission.
     *
     * Catatan: Super Admin tidak perlu entry di sini karena ia mendapat
     * SEMUA permission lewat Permission::all() di bawah.
     */
    private array $rolePermissions = [

        // ══════════════════════════════════════════════════════════════════════
        // ── PPIC (Lab Supervisor) ───────────────────────────────────────
        // Mengawasi dan approve hasil oil losses, manage staff oil losses
        // ══════════════════════════════════════════════════════════════════════
        'PPIC' => [
            'view dashboard',

            'view users',
            'create users',
            'edit users',
            'delete users',
            'reset user password',
            'view user activity log',

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
        ],

        // ══════════════════════════════════════════════════════════════════════
        // ── sampel boy (Lab Staff) ──────────────────────────────────────────────
        // Input hasil lab, hanya bisa edit hasil sendiri
        // ══════════════════════════════════════════════════════════════════════
        'Sampel Boy' => [
            'view dashboard',
            'create oil losses',
            'view oil losses',
            'view laporan oil losses',
        ],

        'Asisten Lab' => [
            'view dashboard',
            'view oil losses',
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
            'view oil losses',
            'view olwb',
            'view performance oil losses',
            'view laporan oil losses',
        ],

        'Koor Sistem Informasi' => [
            'view dashboard',
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
