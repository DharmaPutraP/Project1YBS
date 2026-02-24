# 📋 Dokumentasi Sistem Hak Akses (Permissions)

> Sistem YBS Management menggunakan **Spatie Laravel Permission** untuk mengelola hak akses berbasis Role & Permission.

---

## 🎯 Konsep Dasar

### 1. **Permission (Hak Akses)**

Permission adalah hak untuk melakukan suatu aksi tertentu dalam sistem.

- **Format**: `<action> <module>`
- **Contoh**: `view lab`, `create users`, `export reports`

### 2. **Role (Peran)**

Role adalah kumpulan permission yang dikelompokkan berdasarkan jabatan/tugas.

- **Contoh**: Super Admin, Mill Manager, Kepala Lab, Staf Lab

### 3. **User (Pengguna)**

Setiap user memiliki 1 atau lebih role, dan mewarisi semua permission dari role tersebut.

---

## 📊 Daftar Permission Lengkap

### 🏠 **DASHBOARD**

| Permission                 | Deskripsi                          |
| -------------------------- | ---------------------------------- |
| `view dashboard`           | Akses halaman dashboard            |
| `view dashboard analytics` | Lihat grafik & statistik mendalam  |
| `export dashboard data`    | Export data dashboard ke Excel/PDF |

---

### 👥 **MANAJEMEN PENGGUNA**

#### User Management

| Permission               | Deskripsi                    |
| ------------------------ | ---------------------------- |
| `view users`             | Lihat daftar pengguna        |
| `create users`           | Tambah pengguna baru         |
| `edit users`             | Edit data pengguna           |
| `delete users`           | Hapus pengguna               |
| `reset user password`    | Reset password pengguna lain |
| `view user activity log` | Lihat log aktivitas pengguna |

#### Role & Permission Management

| Permission     | Deskripsi              |
| -------------- | ---------------------- |
| `view roles`   | Lihat daftar role      |
| `create roles` | Buat role baru         |
| `edit roles`   | Edit role & permission |
| `delete roles` | Hapus role             |
| `assign roles` | Assign role ke user    |

---

### 🔬 **LABORATORIUM**

#### Lab Results

| Permission              | Deskripsi                                  |
| ----------------------- | ------------------------------------------ |
| `view lab`              | Lihat halaman lab                          |
| `view lab results`      | Lihat semua hasil lab                      |
| `view own lab results`  | Hanya lihat hasil lab yang diinput sendiri |
| `create lab results`    | Input hasil analisa lab baru               |
| `edit lab results`      | Edit hasil lab yang belum diapprove        |
| `edit own lab results`  | Hanya edit hasil lab sendiri               |
| `delete lab results`    | Hapus hasil lab                            |
| `approve lab results`   | Approve/finalisasi hasil lab               |
| `reject lab results`    | Reject hasil lab yang tidak valid          |
| `print lab certificate` | Cetak sertifikat hasil lab                 |
| `export lab data`       | Export data lab ke Excel/PDF               |

#### Lab Samples

| Permission           | Deskripsi             |
| -------------------- | --------------------- |
| `view lab samples`   | Lihat daftar sampel   |
| `create lab samples` | Daftarkan sampel baru |
| `edit lab samples`   | Edit info sampel      |
| `delete lab samples` | Hapus sampel          |

---

### ⚖️ **TIMBANGAN (WEIGHBRIDGE)**

#### Weighing Data

| Permission               | Deskripsi                         |
| ------------------------ | --------------------------------- |
| `view timbangan`         | Lihat data timbangan              |
| `create timbangan`       | Input data timbangan baru         |
| `edit timbangan`         | Edit data timbangan               |
| `edit own timbangan`     | Hanya edit data timbangan sendiri |
| `delete timbangan`       | Hapus data timbangan              |
| `verify timbangan`       | Verifikasi data timbangan         |
| `print timbangan ticket` | Cetak tiket timbangan             |
| `export timbangan data`  | Export data timbangan             |

#### Vehicle Management

| Permission        | Deskripsi                |
| ----------------- | ------------------------ |
| `view vehicles`   | Lihat data kendaraan     |
| `create vehicles` | Daftarkan kendaraan baru |
| `edit vehicles`   | Edit data kendaraan      |
| `delete vehicles` | Hapus kendaraan          |

#### Supplier Management

| Permission         | Deskripsi               |
| ------------------ | ----------------------- |
| `view suppliers`   | Lihat daftar supplier   |
| `create suppliers` | Daftarkan supplier baru |
| `edit suppliers`   | Edit data supplier      |
| `delete suppliers` | Hapus supplier          |

---

### 📈 **LAPORAN (REPORTS)**

| Permission                | Deskripsi                          |
| ------------------------- | ---------------------------------- |
| `view reports`            | Akses menu laporan                 |
| `view lab reports`        | Lihat laporan laboratorium         |
| `view timbangan reports`  | Lihat laporan timbangan            |
| `view production reports` | Lihat laporan produksi             |
| `view financial reports`  | Lihat laporan keuangan             |
| `export reports`          | Download/export laporan            |
| `print reports`           | Cetak laporan                      |
| `schedule reports`        | Jadwalkan laporan otomatis (email) |

---

### 📦 **INVENTORY & STOCK**

| Permission               | Deskripsi                     |
| ------------------------ | ----------------------------- |
| `view inventory`         | Lihat stok barang             |
| `create inventory`       | Tambah barang baru            |
| `edit inventory`         | Edit data barang              |
| `delete inventory`       | Hapus barang                  |
| `adjust inventory stock` | Sesuaikan stok (stock opname) |
| `view inventory reports` | Lihat laporan inventory       |

---

### 🏭 **PRODUKSI**

| Permission               | Deskripsi             |
| ------------------------ | --------------------- |
| `view production`        | Lihat data produksi   |
| `create production`      | Input data produksi   |
| `edit production`        | Edit data produksi    |
| `delete production`      | Hapus data produksi   |
| `approve production`     | Approve data produksi |
| `export production data` | Export data produksi  |

---

### ⚙️ **PENGATURAN SISTEM**

| Permission            | Deskripsi                           |
| --------------------- | ----------------------------------- |
| `view settings`       | Lihat pengaturan                    |
| `edit settings`       | Edit konfigurasi sistem             |
| `manage integrations` | Kelola integrasi (API, third-party) |
| `view system logs`    | Lihat log sistem                    |
| `clear cache`         | Clear cache aplikasi                |
| `view backup`         | Lihat daftar backup                 |
| `create backup`       | Buat backup database                |
| `restore backup`      | Restore dari backup                 |

---

### 🔔 **NOTIFIKASI**

| Permission                     | Deskripsi                  |
| ------------------------------ | -------------------------- |
| `receive notifications`        | Terima notifikasi sistem   |
| `manage notification settings` | Atur preferensi notifikasi |

---

## 👔 Daftar Role & Permission

### 1. 🔴 **Super Admin**

- **Akses**: SEMUA permission
- **Fungsi**: Administrator tertinggi, full control sistem
- **Total Permission**: 100+

---

### 2. 🔵 **Mill Manager** (Manajer Pabrik)

**Total: 50+ permissions**

✅ **Dashboard**: Full access dengan analytics  
✅ **Laboratorium**: View, create, approve, export  
✅ **Timbangan**: View, create, verify, export  
✅ **Produksi**: Full CRUD + approve  
✅ **Inventory**: View & reports  
✅ **Laporan**: Semua jenis laporan termasuk financial

❌ **User Management**: Tidak bisa  
❌ **System Settings**: Tidak bisa

---

### 3. 🟢 **Kepala Lab** (Lab Supervisor)

**Total: 25+ permissions**

✅ **Dashboard**: View dengan analytics  
✅ **Laboratorium**: Full CRUD, approve/reject, manage samples  
✅ **Laporan**: Lab reports

❌ **Timbangan**: Tidak bisa  
❌ **Produksi**: Tidak bisa  
❌ **User Management**: Tidak bisa

---

### 4. 🟡 **Staf Lab** (Lab Staff)

**Total: 10 permissions**

✅ **Dashboard**: View only  
✅ **Laboratorium**: View, create, edit **own results only**  
✅ **Sample**: Create & edit samples

❌ **Approve/Reject**: Tidak bisa  
❌ **Delete**: Tidak bisa  
❌ **Export**: Tidak bisa

---

### 5. 🟣 **Supervisor Timbangan** (Weighbridge Supervisor)

**Total: 30 permissions**

✅ **Dashboard**: View dengan analytics  
✅ **Timbangan**: Full CRUD + verify  
✅ **Vehicles & Suppliers**: Full CRUD  
✅ **Laporan**: Timbangan reports

❌ **Lab**: Tidak bisa  
❌ **Produksi**: Tidak bisa

---

### 6. 🟠 **Operator Timbangan** (Weighbridge Operator)

**Total: 8 permissions**

✅ **Dashboard**: View only  
✅ **Timbangan**: View, create, edit **own data only**, print ticket  
✅ **Vehicles & Suppliers**: View only

❌ **Delete**: Tidak bisa  
❌ **Verify**: Tidak bisa  
❌ **Export**: Tidak bisa

---

### 7. 🟤 **Production Manager**

**Total: 35 permissions**

✅ **Dashboard**: Full dengan analytics  
✅ **Produksi**: Full CRUD + approve  
✅ **Inventory**: Full CRUD + stock adjustment  
✅ **Timbangan & Lab**: View only  
✅ **Laporan**: Production, timbangan, inventory

---

### 8. 💰 **Finance Manager**

**Total: 20 permissions**

✅ **Dashboard**: View dengan analytics  
✅ **View-Only**: Timbangan, produksi, inventory, suppliers  
✅ **Laporan**: **Full access semua laporan** (focus: financial)  
✅ **Schedule Reports**: Bisa jadwalkan laporan otomatis

❌ **CRUD**: Tidak bisa edit operational data

---

### 9. 🔧 **Admin IT** (System Administrator)

**Total: 30 permissions**

✅ **User Management**: Full CRUD + reset password  
✅ **Role Management**: Full CRUD + assign roles  
✅ **System Settings**: Full access  
✅ **Backup & Restore**: Full control  
✅ **System Logs**: View system logs

❌ **Operational Data**: Tidak bisa akses lab, timbangan, produksi

---

### 10. 👁️ **Viewer** (Auditor)

**Total: 15 permissions**

✅ **Dashboard**: View dengan analytics  
✅ **View-Only**: Lab, timbangan, produksi, inventory  
✅ **Laporan**: View & export (kecuali financial)

❌ **CRUD**: Tidak bisa create/edit/delete apapun

---

## 💻 Cara Menggunakan Permission di Code

### 1. **Di Blade Template (View)**

#### Cek single permission:

```blade
@can('view lab')
    <a href="{{ route('lab.index') }}">Lihat Lab</a>
@endcan
```

#### Cek multiple permission (OR):

```blade
@canany(['create lab results', 'edit lab results'])
    <button>Manage Lab</button>
@endcanany
```

#### Cek semua permission (AND):

```blade
@if(auth()->user()->hasAllPermissions(['view lab', 'approve lab results']))
    <button>Approve</button>
@endif
```

---

### 2. **Di Controller**

#### Check permission:

```php
if (auth()->user()->can('create users')) {
    // User bisa create
}
```

#### Throw exception jika tidak punya permission:

```php
$this->authorize('delete users');
```

#### Check di constructor untuk seluruh controller:

```php
public function __construct()
{
    $this->middleware('permission:view lab');
}
```

---

### 3. **Di Routes (web.php)**

#### Single permission:

```php
Route::get('/lab', [LabController::class, 'index'])
    ->middleware('permission:view lab');
```

#### Multiple permissions (OR):

```php
Route::get('/admin', [AdminController::class, 'index'])
    ->middleware('permission:view users|view roles');
```

#### Multiple permissions (AND):

```php
Route::post('/approve', [LabController::class, 'approve'])
    ->middleware('permission:view lab,approve lab results');
```

---

### 4. **Di Model/Service**

```php
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasRoles;

    // ...
}

// Usage
$user->givePermissionTo('view lab');
$user->revokePermissionTo('delete lab results');
$user->hasPermissionTo('approve lab results'); // true/false
```

---

## 🔄 Cara Menambah Permission Baru

### 1. **Tambahkan di Seeder**

Edit `database/seeders/RoleAndPermissionSeeder.php`:

```php
private array $permissions = [
    // ... existing permissions

    // ── NEW MODULE ────────────────────────────────────────
    'view new_module',
    'create new_module',
    'edit new_module',
    'delete new_module',
];
```

### 2. **Assign ke Role yang sesuai**

```php
private array $rolePermissions = [
    'Mill Manager' => [
        // ... existing permissions
        'view new_module',
        'create new_module',
    ],
];
```

### 3. **Jalankan Seeder**

```bash
php artisan db:seed --class=RoleAndPermissionSeeder
```

### 4. **Gunakan di Routes**

```php
Route::get('/new-module', [NewModuleController::class, 'index'])
    ->middleware('permission:view new_module');
```

### 5. **Gunakan di View**

```blade
@can('view new_module')
    <x-sidebar-item href="{{ route('new-module.index') }}">
        New Module
    </x-sidebar-item>
@endcan
```

---

## 🛡️ Best Practices

### ✅ **DO (Lakukan)**

1. **Gunakan permission di semua routes yang sensitif**

    ```php
    Route::delete('/users/{id}', ...)
        ->middleware('permission:delete users');
    ```

2. **Check permission di controller untuk double security**

    ```php
    public function destroy($id)
    {
        $this->authorize('delete users');
        // ... logic
    }
    ```

3. **Gunakan naming convention yang konsisten**
    - Format: `<action> <module>`
    - Action: view, create, edit, delete, approve, export, print
    - Module: users, lab, timbangan, reports, dll

4. **Gunakan "own" untuk permission yang terbatas pada data sendiri**
    - `view own lab results` - hanya lihat milik sendiri
    - `edit own timbangan` - hanya edit data sendiri

### ❌ **DON'T (Hindari)**

1. **Jangan hardcode permission di banyak tempat**

    ```php
    // ❌ Bad
    if (auth()->user()->hasRole('Super Admin')) { ... }

    // ✅ Good
    if (auth()->user()->can('delete users')) { ... }
    ```

2. **Jangan buat permission terlalu spesifik**

    ```php
    // ❌ Bad
    'edit user name'
    'edit user email'
    'edit user phone'

    // ✅ Good
    'edit users'
    ```

3. **Jangan skip permission check di routes penting**

    ```php
    // ❌ Bad
    Route::delete('/users/{id}', [...]);

    // ✅ Good
    Route::delete('/users/{id}', [...])
        ->middleware('permission:delete users');
    ```

---

## 🚀 Command Berguna

### Clear Permission Cache

```bash
php artisan permission:cache-reset
```

### Lihat semua permissions

```bash
php artisan tinker
>>> Spatie\Permission\Models\Permission::all()->pluck('name')
```

### Lihat semua roles

```bash
php artisan tinker
>>> Spatie\Permission\Models\Role::with('permissions')->get()
```

### Cek permission user tertentu

```bash
php artisan tinker
>>> $user = User::find(1)
>>> $user->getAllPermissions()->pluck('name')
```

### Give permission ke user

```bash
php artisan tinker
>>> $user = User::find(1)
>>> $user->givePermissionTo('view lab')
```

---

## 📞 Support & Troubleshooting

### Issue: Permission tidak bekerja setelah update seeder

**Solution**: Clear cache permission

```bash
php artisan permission:cache-reset
php artisan cache:clear
```

### Issue: User tidak bisa akses meskipun punya permission

**Solution**:

1. Pastikan user sudah login
2. Check apakah permission benar-benar assigned ke role user tersebut
3. Clear permission cache

### Issue: Seeder error "permission already exists"

**Solution**: Seeder menggunakan `firstOrCreate()` jadi aman, tapi kalau error:

```bash
php artisan permission:cache-reset
php artisan db:seed --class=RoleAndPermissionSeeder
```

---

## 📝 Changelog

### Version 2.0 - February 2026

- ✅ Refactor permission dari "manage" ke CRUD (view, create, edit, delete)
- ✅ Tambah 100+ permission baru
- ✅ Tambah 6 role baru (Total: 10 roles)
- ✅ Tambah permission untuk: Production, Inventory, Vehicles, Suppliers
- ✅ Tambah "own" permissions untuk data ownership
- ✅ Tambah system settings & backup permissions

### Version 1.0 - Initial

- Basic permission system
- 5 basic roles
- 15 basic permissions

---

**© 2026 YBS Management System**  
_Built with Laravel & Spatie Permission_
