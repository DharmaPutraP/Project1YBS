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
        'view lab',                       // Lihat halaman lab
        'view lab results',               // Lihat semua hasil lab
        'view own lab results',           // Hanya lihat hasil lab yang diinput sendiri
        'create lab results',             // Input hasil analisa lab baru
        'edit lab results',               // Edit hasil lab yang belum diapprove
        'edit own lab results',           // Hanya edit hasil lab sendiri
        'delete lab results',             // Hapus hasil lab
        'approve lab results',            // Approve/finalisasi hasil lab
        'reject lab results',             // Reject hasil lab yang tidak valid
        'print lab certificate',          // Cetak sertifikat hasil lab
        'export lab data',                // Export data lab ke Excel/PDF

        // ── Sample Management (Sampel Lab) ────────────────────────────────────
        'view lab samples',               // Lihat daftar sampel
        'create lab samples',             // Daftarkan sampel baru
        'edit lab samples',               // Edit info sampel
        'delete lab samples',             // Hapus sampel

        // ══════════════════════════════════════════════════════════════════════
        // ── TIMBANGAN (WEIGHBRIDGE) ───────────────────────────────────────────
        // ══════════════════════════════════════════════════════════════════════
        'view timbangan',                 // Lihat data timbangan
        'create timbangan',               // Input data timbangan baru
        'edit timbangan',                 // Edit data timbangan
        'edit own timbangan',             // Hanya edit data timbangan sendiri
        'delete timbangan',               // Hapus data timbangan
        'verify timbangan',               // Verifikasi data timbangan
        'print timbangan ticket',         // Cetak tiket timbangan
        'export timbangan data',          // Export data timbangan

        // ── Truck/Vehicle Management ──────────────────────────────────────────
        'view vehicles',                  // Lihat data kendaraan
        'create vehicles',                // Daftarkan kendaraan baru
        'edit vehicles',                  // Edit data kendaraan
        'delete vehicles',                // Hapus kendaraan

        // ── Supplier/Vendor Management ────────────────────────────────────────
        'view suppliers',                 // Lihat daftar supplier
        'create suppliers',               // Daftarkan supplier baru
        'edit suppliers',                 // Edit data supplier
        'delete suppliers',               // Hapus supplier

        // ══════════════════════════════════════════════════════════════════════
        // ── LAPORAN (REPORTS) ─────────────────────────────────────────────────
        // ══════════════════════════════════════════════════════════════════════
        'view reports',                   // Akses menu laporan
        'view lab reports',               // Lihat laporan laboratorium
        'view timbangan reports',         // Lihat laporan timbangan
        'view production reports',        // Lihat laporan produksi
        'view financial reports',         // Lihat laporan keuangan/finance
        'export reports',                 // Download/export laporan
        'print reports',                  // Cetak laporan
        'schedule reports',               // Jadwalkan laporan otomatis (email)

        // ══════════════════════════════════════════════════════════════════════
        // ── INVENTORY & STOCK ─────────────────────────────────────────────────
        // ══════════════════════════════════════════════════════════════════════
        'view inventory',                 // Lihat stok barang
        'create inventory',               // Tambah barang baru
        'edit inventory',                 // Edit data barang
        'delete inventory',               // Hapus barang
        'adjust inventory stock',         // Sesuaikan stok (stock opname)
        'view inventory reports',         // Lihat laporan inventory

        // ══════════════════════════════════════════════════════════════════════
        // ── PRODUKSI ──────────────────────────────────────────────────────────
        // ══════════════════════════════════════════════════════════════════════
        'view production',                // Lihat data produksi
        'create production',              // Input data produksi
        'edit production',                // Edit data produksi
        'delete production',              // Hapus data produksi
        'approve production',             // Approve data produksi
        'export production data',         // Export data produksi

        // ══════════════════════════════════════════════════════════════════════
        // ── PENGATURAN SISTEM ─────────────────────────────────────────────────
        // ══════════════════════════════════════════════════════════════════════
        'view settings',                  // Lihat pengaturan
        'edit settings',                  // Edit konfigurasi sistem
        'manage integrations',            // Kelola integrasi (API, third-party)
        'view system logs',               // Lihat log sistem
        'clear cache',                    // Clear cache aplikasi
        'view backup',                    // Lihat daftar backup
        'create backup',                  // Buat backup database
        'restore backup',                 // Restore dari backup

        // ══════════════════════════════════════════════════════════════════════
        // ── NOTIFIKASI ────────────────────────────────────────────────────────
        // ══════════════════════════════════════════════════════════════════════
        'receive notifications',          // Terima notifikasi sistem
        'manage notification settings',   // Atur preferensi notifikasi
    ];

    /**
     * Pemetaan role → permission.
     *
     * Catatan: Super Admin tidak perlu entry di sini karena ia mendapat
     * SEMUA permission lewat Permission::all() di bawah.
     */
    private array $rolePermissions = [

        // ══════════════════════════════════════════════════════════════════════
        // ── MILL MANAGER (Manajer Pabrik) ─────────────────────────────────────
        // Memiliki akses penuh kecuali system settings & user management
        // ══════════════════════════════════════════════════════════════════════
        'Mill Manager' => [
            // Dashboard
            'view dashboard',
            'view dashboard analytics',
            'export dashboard data',

            // Laboratorium - Full Access
            'view lab',
            'view lab results',
            'create lab results',
            'edit lab results',
            'approve lab results',
            'reject lab results',
            'print lab certificate',
            'export lab data',
            'view lab samples',
            'create lab samples',

            // Timbangan - Full Access
            'view timbangan',
            'create timbangan',
            'edit timbangan',
            'verify timbangan',
            'print timbangan ticket',
            'export timbangan data',
            'view vehicles',
            'view suppliers',

            // Produksi - Full Access
            'view production',
            'create production',
            'edit production',
            'approve production',
            'export production data',

            // Inventory
            'view inventory',
            'view inventory reports',

            // Laporan - Full Access
            'view reports',
            'view lab reports',
            'view timbangan reports',
            'view production reports',
            'view financial reports',
            'export reports',
            'print reports',

            // Notifikasi
            'receive notifications',
            'manage notification settings',
        ],

        // ══════════════════════════════════════════════════════════════════════
        // ── KEPALA LAB (Lab Supervisor) ───────────────────────────────────────
        // Mengawasi dan approve hasil lab, manage staff lab
        // ══════════════════════════════════════════════════════════════════════
        'Kepala Lab' => [
            'view dashboard',
            'view dashboard analytics',

            // Lab - supervisory level
            'view lab',
            'view lab results',
            'create lab results',
            'edit lab results',
            'delete lab results',
            'approve lab results',
            'reject lab results',
            'print lab certificate',
            'export lab data',

            // Sample management
            'view lab samples',
            'create lab samples',
            'edit lab samples',
            'delete lab samples',

            // Reports
            'view reports',
            'view lab reports',
            'export reports',
            'print reports',

            'receive notifications',
            'manage notification settings',
        ],

        // ══════════════════════════════════════════════════════════════════════
        // ── STAF LAB (Lab Staff) ──────────────────────────────────────────────
        // Input hasil lab, hanya bisa edit hasil sendiri
        // ══════════════════════════════════════════════════════════════════════
        'Staf Lab' => [
            'view dashboard',

            // Lab - operational level (own work only)
            'view lab',
            'view lab results',
            'view own lab results',
            'create lab results',
            'edit own lab results',
            'print lab certificate',

            // Sample
            'view lab samples',
            'create lab samples',
            'edit lab samples',

            'receive notifications',
        ],

        // ══════════════════════════════════════════════════════════════════════
        // ── SUPERVISOR TIMBANGAN (Weighbridge Supervisor) ─────────────────────
        // Mengawasi timbangan, verify data, manage vehicles & suppliers
        // ══════════════════════════════════════════════════════════════════════
        'Supervisor Timbangan' => [
            'view dashboard',
            'view dashboard analytics',

            // Timbangan - supervisory
            'view timbangan',
            'create timbangan',
            'edit timbangan',
            'delete timbangan',
            'verify timbangan',
            'print timbangan ticket',
            'export timbangan data',

            // Master data
            'view vehicles',
            'create vehicles',
            'edit vehicles',
            'delete vehicles',
            'view suppliers',
            'create suppliers',
            'edit suppliers',
            'delete suppliers',

            // Reports
            'view reports',
            'view timbangan reports',
            'export reports',
            'print reports',

            'receive notifications',
            'manage notification settings',
        ],

        // ══════════════════════════════════════════════════════════════════════
        // ── OPERATOR TIMBANGAN (Weighbridge Operator) ─────────────────────────
        // Input data timbangan, hanya edit data sendiri
        // ══════════════════════════════════════════════════════════════════════
        'Operator Timbangan' => [
            'view dashboard',

            // Timbangan - operational
            'view timbangan',
            'create timbangan',
            'edit own timbangan',
            'print timbangan ticket',

            // View master data
            'view vehicles',
            'view suppliers',

            'receive notifications',
        ],

        // ══════════════════════════════════════════════════════════════════════
        // ── PRODUCTION MANAGER ────────────────────────────────────────────────
        // Kelola produksi, inventory, dan laporan produksi
        // ══════════════════════════════════════════════════════════════════════
        'Production Manager' => [
            'view dashboard',
            'view dashboard analytics',
            'export dashboard data',

            // Production
            'view production',
            'create production',
            'edit production',
            'delete production',
            'approve production',
            'export production data',

            // Inventory
            'view inventory',
            'create inventory',
            'edit inventory',
            'adjust inventory stock',
            'view inventory reports',

            // View related data
            'view timbangan',
            'view lab results',

            // Reports
            'view reports',
            'view production reports',
            'view timbangan reports',
            'export reports',
            'print reports',

            'receive notifications',
            'manage notification settings',
        ],

        // ══════════════════════════════════════════════════════════════════════
        // ── FINANCE MANAGER ───────────────────────────────────────────────────
        // Akses laporan keuangan dan data untuk perhitungan
        // ══════════════════════════════════════════════════════════════════════
        'Finance Manager' => [
            'view dashboard',
            'view dashboard analytics',

            // View-only untuk verifikasi
            'view timbangan',
            'view production',
            'view inventory',
            'view suppliers',

            // Reports - finance focus
            'view reports',
            'view financial reports',
            'view production reports',
            'view timbangan reports',
            'view inventory reports',
            'export reports',
            'print reports',
            'schedule reports',

            'receive notifications',
            'manage notification settings',
        ],

        // ══════════════════════════════════════════════════════════════════════
        // ── ADMIN IT / SYSTEM ADMINISTRATOR ───────────────────────────────────
        // Kelola user, roles, dan pengaturan sistem (tapi bukan operational data)
        // ══════════════════════════════════════════════════════════════════════
        'Admin IT' => [
            'view dashboard',

            // User Management
            'view users',
            'create users',
            'edit users',
            'delete users',
            'reset user password',
            'view user activity log',

            // Role Management
            'view roles',
            'create roles',
            'edit roles',
            'delete roles',
            'assign roles',

            // System Settings
            'view settings',
            'edit settings',
            'manage integrations',
            'view system logs',
            'clear cache',
            'view backup',
            'create backup',
            'restore backup',

            'receive notifications',
            'manage notification settings',
        ],

        // ══════════════════════════════════════════════════════════════════════
        // ── VIEWER / AUDITOR ──────────────────────────────────────────────────
        // Hanya bisa melihat, untuk keperluan audit atau monitoring
        // ══════════════════════════════════════════════════════════════════════
        'Viewer' => [
            'view dashboard',
            'view dashboard analytics',

            // View-only access
            'view lab',
            'view lab results',
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
