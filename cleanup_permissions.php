<?php

/**
 * ═══════════════════════════════════════════════════════════════════════
 * Script untuk membersihkan Role dan Permission yang tidak digunakan
 * ═══════════════════════════════════════════════════════════════════════
 * 
 * Jalankan dengan: php cleanup_permissions.php
 * 
 * Script ini akan:
 * 1. Menghapus semua Permission yang TIDAK ada di RoleAndPermissionSeeder
 * 2. Menghapus semua Role yang TIDAK ada di RoleAndPermissionSeeder
 * 3. Menjalankan seeder ulang untuk sync permission
 */

require __DIR__ . '/vendor/autoload.php';

use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "\n";
echo "╔═══════════════════════════════════════════════════════════════════╗\n";
echo "║  CLEANUP ROLES & PERMISSIONS YANG TIDAK DIGUNAKAN                ║\n";
echo "╚═══════════════════════════════════════════════════════════════════╝\n\n";

// ─────────────────────────────────────────────────────────────────────────────
// Daftar Permission & Role yang HARUS ADA (dari seeder)
// ─────────────────────────────────────────────────────────────────────────────

$allowedPermissions = [
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
    'export laporan oil losses',
];

$allowedRoles = [
    'Super Admin',
    'PPIC',
    'Sampel Boy',
    'Asisten Lab',
    'PCM',
    'Direksi',
    'Koor Sistem Informasi',
];

// ─────────────────────────────────────────────────────────────────────────────
// Delete PERMISSION yang tidak ada di list
// ─────────────────────────────────────────────────────────────────────────────

echo "🔍 Mencari Permission yang tidak digunakan...\n";

$allPermissions = Permission::all();
$deletedPermissions = [];

foreach ($allPermissions as $permission) {
    if (!in_array($permission->name, $allowedPermissions)) {
        $deletedPermissions[] = $permission->name;

        // Hapus dari pivot table dulu
        DB::table('role_has_permissions')->where('permission_id', $permission->id)->delete();
        DB::table('model_has_permissions')->where('permission_id', $permission->id)->delete();

        // Hapus permission
        $permission->delete();
    }
}

if (count($deletedPermissions) > 0) {
    echo "❌ Menghapus " . count($deletedPermissions) . " permission:\n";
    foreach ($deletedPermissions as $name) {
        echo "   - {$name}\n";
    }
} else {
    echo "✅ Tidak ada permission yang perlu dihapus.\n";
}

echo "\n";

// ─────────────────────────────────────────────────────────────────────────────
// Delete ROLE yang tidak ada di list (kecuali yang punya user)
// ─────────────────────────────────────────────────────────────────────────────

echo "🔍 Mencari Role yang tidak digunakan...\n";

$allRoles = Role::all();
$deletedRoles = [];

foreach ($allRoles as $role) {
    if (!in_array($role->name, $allowedRoles)) {
        // Cek apakah ada user dengan role ini
        $userCount = DB::table('model_has_roles')
            ->where('role_id', $role->id)
            ->count();

        if ($userCount > 0) {
            echo "⚠️  SKIP: Role '{$role->name}' masih digunakan oleh {$userCount} user(s)\n";
            continue;
        }

        $deletedRoles[] = $role->name;

        // Hapus dari pivot table
        DB::table('role_has_permissions')->where('role_id', $role->id)->delete();

        // Hapus role
        $role->delete();
    }
}

if (count($deletedRoles) > 0) {
    echo "❌ Menghapus " . count($deletedRoles) . " role:\n";
    foreach ($deletedRoles as $name) {
        echo "   - {$name}\n";
    }
} else {
    echo "✅ Tidak ada role yang perlu dihapus.\n";
}

echo "\n";
echo "═══════════════════════════════════════════════════════════════════\n";
echo "✅ CLEANUP SELESAI!\n";
echo "═══════════════════════════════════════════════════════════════════\n";
echo "\n";
echo "📌 Langkah selanjutnya:\n";
echo "   php artisan db:seed --class=RoleAndPermissionSeeder\n";
echo "\n";
