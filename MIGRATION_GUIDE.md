# 🔄 Migration Guide - Update Permission System

> Panduan step-by-step untuk meng-update sistem permission dari versi lama ke versi baru.

---

## 📋 Perubahan Utama

### Permission yang Diubah:

| Versi Lama (v1.0)   | Versi Baru (v2.0)                                                          | Alasan             |
| ------------------- | -------------------------------------------------------------------------- | ------------------ |
| `manage users`      | `view users`, `create users`, `edit users`, `delete users`                 | Pemisahan CRUD     |
| `manage roles`      | `view roles`, `create roles`, `edit roles`, `delete roles`, `assign roles` | Pemisahan CRUD     |
| `input lab results` | `create lab results`                                                       | Konsistensi naming |
| `input timbangan`   | `create timbangan`                                                         | Konsistensi naming |
| `manage settings`   | `view settings`, `edit settings`                                           | Pemisahan CRUD     |

### Permission Baru yang Ditambahkan:

Total **100+ permission baru** untuk modul:

- ✅ Production (Produksi)
- ✅ Inventory (Persediaan)
- ✅ Vehicles (Kendaraan)
- ✅ Suppliers (Pemasok)
- ✅ Dashboard Analytics
- ✅ System Administration (Backup, Logs, Cache)
- ✅ Notifications

### Role Baru:

- ✅ Supervisor Timbangan
- ✅ Operator Timbangan
- ✅ Production Manager
- ✅ Finance Manager
- ✅ Admin IT
- ✅ Viewer (Auditor)

---

## 🚀 Langkah-Langkah Migrasi

### Step 1: Backup Database

**PENTING!** Backup dulu sebelum update:

```bash
# Backup database
php artisan db:backup

# Atau manual export via phpMyAdmin/MySQL
mysqldump -u root -p ybs_database > backup_before_migration.sql
```

---

### Step 2: Update Seeder

File `database/seeders/RoleAndPermissionSeeder.php` sudah di-update otomatis.

✅ Sudah termasuk semua permission baru  
✅ Sudah termasuk semua role baru  
✅ Sudah update mapping role → permission

---

### Step 3: Update Routes

File `routes/web.php` sudah di-update dengan middleware permission baru.

**Yang berubah:**

```php
// ❌ LAMA
Route::get('/users', [UserController::class, 'index'])
    ->name('users.index');

// ✅ BARU
Route::get('/users', [UserController::class, 'index'])
    ->name('users.index')
    ->middleware('permission:view users');
```

---

### Step 4: Update Sidebar

File `resources/views/components/sidebar.blade.php` sudah di-update.

**Yang berubah:**

```blade
{{-- ❌ LAMA --}}
@can('manage users')
    <x-sidebar-item>Kelola Pengguna</x-sidebar-item>
@endcan

{{-- ✅ BARU --}}
@can('view users')
    <x-sidebar-item>Kelola Pengguna</x-sidebar-item>
@endcan
```

---

### Step 5: Jalankan Migrasi & Seeder

```bash
# 1. Clear cache permission lama
php artisan permission:cache-reset

# 2. Clear all cache
php artisan cache:clear

# 3. Jalankan seeder untuk update permission
php artisan db:seed --class=RoleAndPermissionSeeder
```

**Output yang diharapkan:**

```
✅  Roles, permissions, dan akun admin berhasil dibuat.
┌──────────────────────┬──────────────────────┐
│ Role                 │ Jumlah Permission    │
├──────────────────────┼──────────────────────┤
│ Super Admin          │ 120                  │
│ Mill Manager         │ 50                   │
│ Kepala Lab           │ 25                   │
│ Staf Lab             │ 10                   │
│ Supervisor Timbangan │ 30                   │
│ Operator Timbangan   │ 8                    │
│ Production Manager   │ 35                   │
│ Finance Manager      │ 20                   │
│ Admin IT             │ 30                   │
│ Viewer               │ 15                   │
└──────────────────────┴──────────────────────┘
```

---

### Step 6: Update Controllers (Jika Ada Custom Permission Check)

Jika di controller Anda ada hardcode permission check, update manual:

#### UserController.php

```php
// ❌ LAMA
public function index()
{
    $this->authorize('manage users');
    // ...
}

// ✅ BARU
public function index()
{
    $this->authorize('view users');
    // ...
}
```

#### LabController.php

```php
// ❌ LAMA
public function store()
{
    $this->authorize('input lab results');
    // ...
}

// ✅ BARU
public function store()
{
    $this->authorize('create lab results');
    // ...
}
```

---

### Step 7: Update Blade Templates (Custom Views)

Cari semua file `.blade.php` yang menggunakan permission lama:

#### Search & Replace:

```bash
# Di VS Code: Ctrl+Shift+F untuk find in files
# Cari dan replace:

"manage users"       → "view users" (untuk view)
                       "create users" (untuk create form)
                       "edit users" (untuk edit form)

"input lab results"  → "create lab results"
"input timbangan"    → "create timbangan"
"manage settings"    → "view settings" atau "edit settings"
```

**Contoh perubahan di view:**

```blade
{{-- ❌ LAMA --}}
@can('manage users')
    <button>Tambah User</button>
@endcan

{{-- ✅ BARU --}}
@can('create users')
    <button>Tambah User</button>
@endcan
```

---

### Step 8: Testing

#### 1. Test Login sebagai Super Admin

```
Username: admin
Password: admin123
```

**Expected:** Bisa akses semua menu

#### 2. Test Create User Baru dengan Role Berbeda

```bash
php artisan tinker
```

```php
// Create test user - Staf Lab
$user = User::create([
    'name' => 'Test Staf Lab',
    'username' => 'staflab',
    'password' => bcrypt('password'),
]);
$user->assignRole('Staf Lab');

// Create test user - Mill Manager
$user2 = User::create([
    'name' => 'Test Manager',
    'username' => 'manager',
    'password' => bcrypt('password'),
]);
$user2->assignRole('Mill Manager');
```

#### 3. Test Permission Check

```bash
php artisan tinker
```

```php
$user = User::where('username', 'staflab')->first();

// Should return TRUE
$user->can('view lab');
$user->can('create lab results');

// Should return FALSE
$user->can('approve lab results');
$user->can('delete lab results');
$user->can('view users');
```

#### 4. Test Sidebar Visibility

Login sebagai masing-masing role dan pastikan:

- ✅ Menu yang tidak ada permission-nya **tidak muncul**
- ✅ Menu yang ada permission-nya **muncul**

---

## 🔧 Troubleshooting

### Issue 1: "Permission not found"

**Error:**

```
Spatie\Permission\Exceptions\PermissionDoesNotExist
There is no permission named `view users`
```

**Solution:**

```bash
php artisan permission:cache-reset
php artisan db:seed --class=RoleAndPermissionSeeder
php artisan cache:clear
```

---

### Issue 2: User yang sudah ada tidak bisa akses apapun

**Cause:** User punya role lama dengan permission lama yang sudah tidak ada.

**Solution 1 - Re-assign Role:**

```bash
php artisan tinker
```

```php
$user = User::find(1);
$user->syncRoles(['Mill Manager']); // Re-assign role
```

**Solution 2 - Re-seed All Permissions:**

```bash
php artisan db:seed --class=RoleAndPermissionSeeder
```

---

### Issue 3: Sidebar masih pakai permission lama

**Solution:** Clear view cache

```bash
php artisan view:clear
php artisan cache:clear
```

---

### Issue 4: Route masih bisa diakses meskipun tidak punya permission

**Cause:** Route tidak ada middleware permission

**Solution:** Pastikan semua route punya middleware, contoh:

```php
Route::get('/users', [UserController::class, 'index'])
    ->middleware('permission:view users');
```

---

## 📊 Verification Checklist

Setelah migrasi, cek:

- [ ] ✅ Seeder berhasil jalan tanpa error
- [ ] ✅ Permission cache sudah di-reset
- [ ] ✅ Login sebagai Super Admin → bisa akses semua
- [ ] ✅ Login sebagai role lain → menu terbatas sesuai permission
- [ ] ✅ Routes tidak bisa diakses tanpa permission
- [ ] ✅ Sidebar hanya menampilkan menu sesuai permission
- [ ] ✅ User lama masih bisa login dan punya akses yang benar
- [ ] ✅ Tidak ada error "Permission not found"

---

## 🎯 Rollback (Jika Ada Masalah)

Jika migrasi gagal dan ingin rollback:

### 1. Restore Database Backup

```bash
mysql -u root -p ybs_database < backup_before_migration.sql
```

### 2. Kembalikan File Lama (Git)

```bash
git checkout HEAD~1 database/seeders/RoleAndPermissionSeeder.php
git checkout HEAD~1 routes/web.php
git checkout HEAD~1 resources/views/components/sidebar.blade.php
```

### 3. Clear Cache

```bash
php artisan permission:cache-reset
php artisan cache:clear
php artisan view:clear
```

---

## 🎓 Training untuk Tim

Setelah migrasi selesai, edukasi tim tentang:

1. **Perbedaan Role Baru**
    - Apa saja role baru yang ditambahkan
    - Masing-masing role punya akses apa

2. **Cara Request Permission Baru**
    - Jika butuh akses tambahan, hubungi Admin IT
    - Admin IT assign manual via:
        ```php
        $user->givePermissionTo('nama_permission');
        ```

3. **Dokumentasi**
    - Baca `PERMISSIONS.md` untuk detail lengkap
    - Lihat tabel mapping role → permission

---

## 📞 Support

Jika ada kendala selama migrasi:

1. Check log Laravel: `storage/logs/laravel.log`
2. Check dokumentasi lengkap: `PERMISSIONS.md`
3. Run diagnostic:
    ```bash
    php artisan tinker
    >>> Permission::count()  // Should be 120+
    >>> Role::count()        // Should be 10
    ```

---

**Good luck dengan migrasi! 🚀**

_Last updated: February 23, 2026_
