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

        // ══════════════════════════════════════════════════════════════════════
        // ── DASHBOARD ─────────────────────────────────────────────────────────
        // ══════════════════════════════════════════════════════════════════════
        'view dashboard',                 // Akses halaman dashboard
        'view dashboard analytics',       // Lihat grafik & statistik mendalam
        'export dashboard data',          // Export data dashboard ke Excel/PDF

        // ══════════════════════════════════════════════════════════════════════
        // ── MANAJEMEN PENGGUNA ────────────────────────────────────────────────
        // ══════════════════════════════════════════════════════════════════════
        'view users',                     // Lihat daftar pengguna
        'create users',                   // Tambah pengguna baru
        'edit users',                     // Edit data pengguna
        'delete users',                   // Hapus pengguna
        'reset user password',            // Reset password pengguna lain
        'view user activity log',         // Lihat log aktivitas pengguna

        // ── Role & Permission Management ──────────────────────────────────────
        'view roles',                     // Lihat daftar role
        'create roles',                   // Buat role baru
        'edit roles',                     // Edit role & permission
        'delete roles',                   // Hapus role
        'assign roles',                   // Assign role ke user

        // ══════════════════════════════════════════════════════════════════════
        // ── LABORATORIUM ──────────────────────────────────────────────────────
        // ══════════════════════════════════════════════════════════════════════
        'view oil',                       // Lihat halaman lab
        'view oil results',               // Lihat semua hasil lab
        'view own oil results',           // Hanya lihat hasil lab yang diinput sendiri
        'create oil results',             // Input hasil analisa lab baru
        'edit oil results',               // Edit hasil oil losses yang belum diapprove
        'edit own oil results',           // Hanya Edit hasil oil losses sendiri
        'delete oil results',             // Hapus hasil lab
        'approve oil results',            // Approve/finalisasi hasil oil losses
        'reject oil results',             // Reject hasil oil losses yang tidak valid
        'print oil certificate',          // Cetak sertifikat hasil oil losses
        'export oil data',                // Export data oil losses ke Excel/PDF

        // ── Sample Management (Sampel Lab) ────────────────────────────────────
        'view oil samples',               // Lihat daftar sampel
        'create oil samples',             // Daftarkan sampel baru
        'edit oil samples',               // Edit info sampel
        'delete oil samples',             // Hapus sampel

        // ══════════════════════════════════════════════════════════════════════
        // ── LAPORAN (REPORTS) ─────────────────────────────────────────────────
        // ══════════════════════════════════════════════════════════════════════
        'view reports',                   // Akses menu laporan
        'export reports',                 // Download/export laporan

        'input oil losses',
        'edit oil losses',
        'delete oil losses',
        'view oil losses',
        'view olwb',
        'view performance',
        'view laporan oil losses',
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
            'view dashboard analytics',
            'view oil',
            'view oil results',
            'create oil results',
            'edit oil results',
            'delete oil results',
            'view oil samples',
            'create oil samples',
            'edit oil samples',
            'view reports',
            'view users',                     // Lihat daftar pengguna
            'create users',                   // Tambah pengguna baru
            'edit users',                     // Edit data pengguna
            'delete users',                   // Hapus pengguna
            'reset user password',            // Reset password pengguna lain
            'view user activity log',         // Lihat log aktivitas pengguna

            'input oil losses',
            'edit oil losses',
            'delete oil losses',
            'view oil losses',
            'view olwb',
            'view performance',
            'view laporan oil losses',
        ],

        // ══════════════════════════════════════════════════════════════════════
        // ── sampel boy (Lab Staff) ──────────────────────────────────────────────
        // Input hasil lab, hanya bisa edit hasil sendiri
        // ══════════════════════════════════════════════════════════════════════
        'Sampel Boy' => [
            'view dashboard',
            'input oil losses',
            'view oil losses',
            'view reports',
        ],

        'Asisten Lab' => [
            'view dashboard',
            'view oil losses',
            'view olwb',
            'view performance',
            'view laporan oil losses',
            'view reports',
        ],

        'PCM' => [
            'view dashboard',
            'view oil losses',
            'view olwb',
            'view performance',
            'view laporan oil losses',
            'view reports',
        ],

        'Direksi' => [
            'view dashboard',
            'view oil losses',
            'view olwb',
            'view performance',
            'view laporan oil losses',
            'view reports',
        ],

        'Koor Sistem Informasi' => [
            'view dashboard',
            'view oil losses',
            'view olwb',
            'view performance',
            'view laporan oil losses',
            'view reports',
        ],

        // ══════════════════════════════════════════════════════════════════════
        // ── VIEWER / AUDITOR ──────────────────────────────────────────────────
        // Hanya bisa melihat, untuk keperluan audit atau monitoring
        // ══════════════════════════════════════════════════════════════════════
        'Viewer' => [
            'view dashboard',
            'view dashboard analytics',

            // View-only access
            'view oil',
            'view oil results',
            'view timbangan',
            'view production',
            'view inventory',
            'view vehicles',
            'view suppliers',

            // Reports
            'view reports',
            'view lab reports',
            'view timbangan reports',
            'view production reports',
            'export reports',

            'receive notifications',
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
