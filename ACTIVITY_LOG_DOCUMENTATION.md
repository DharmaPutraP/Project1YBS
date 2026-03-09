# 📋 Activity Logging System - Dokumentasi Lengkap

## ✅ Implementasi Berhasil!

Sistem **Activity Logging** telah berhasil diimplementasikan dengan best practice untuk audit trail yang comprehensive.

---

## 🎯 Fitur Yang Telah Diimplementasikan

### 1. **Database Structure** ✅

Table `activity_logs` dengan kolom:

- **User Info**: user_id, user_name (backup)
- **Activity**: action, model_type, model_id, description
- **Change Details**: old_values, new_values, metadata (JSON)
- **Request Info**: ip_address, user_agent, url, method
- **Timestamp**: created_at
- **Indexes**: Optimized untuk query cepat

### 2. **Model & Trait** ✅

- `ActivityLog` Model dengan helper methods:
    - `getActionBadgeColorAttribute()` - Badge color berdasarkan action
    - `getActionLabelAttribute()` - Label bahasa Indonesia
    - Scopes untuk filtering (byUser, byAction, byModelType, dateRange)

- `LogsActivity` Trait dengan methods:
    - `logCreate()` - Log create operation
    - `logUpdate()` - Log update dengan diff detection
    - `logDelete()` - Log delete operation
    - `logExport()` - Log export operation
    - `logLogin()` - Log user login
    - `logLogout()` - Log user logout

### 3. **Controller Implementation** ✅

**Logged Actions:**

| Controller         | Logged Operations                                                               |
| ------------------ | ------------------------------------------------------------------------------- |
| **OilController**  | Create oil losses, Update oil losses, Delete oil calculation, Delete oil record |
| **UserController** | Update user, Delete user                                                        |
| **AuthController** | Login, Logout, Register/Create user                                             |

### 4. **Views & UI** ✅

**Activity Logs Page** (`/activity-logs`):

- ✅ Filter by User, Action, Model Type, Date Range, Search
- ✅ Pagination (50 per page)
- ✅ Color-coded action badges
- ✅ Responsive table
- ✅ Detail view with full context

**Detail Page** (`/activity-logs/{id}`):

- ✅ Full information (user, action, timestamp, IP, etc)
- ✅ Side-by-side comparison (old vs new values)
- ✅ JSON pretty print for data changes
- ✅ Metadata/context display

### 5. **Routes** ✅

```php
GET  /activity-logs          → Index dengan filter
GET  /activity-logs/{id}     → Detail log
POST /activity-logs/cleanup  → Cleanup old logs (future feature)
```

Semua route protected dengan permission: `view user activity log`

### 6. **Sidebar Menu** ✅

Menu "Activity Log" ditambahkan di section **Administrasi** (hanya muncul untuk user yang punya permission).

---

## 🔐 Permission System

**Permission:** `view user activity log`

**Roles yang memiliki akses:**

- ✅ Super Admin (have access to all permissions)
- ✅ PPIC (assigned in seeder)

**Untuk memberikan akses ke role lain**, tambahkan permission ke `RoleAndPermissionSeeder.php`:

```php
'Role Name' => [
    // ... existing permissions
    'view user activity log',
],
```

---

## 📊 Yang Di-Log Secara Otomatis

### 🟢 Create Operations

- User register/create
- Input Oil Losses (Mode 1 & 2)
- **Data tersimpan**: New values (JSON)

### 🟡 Update Operations

- Update User (profile, role, password)
- Update Oil Losses data
- **Data tersimpan**: Old values + New values (JSON)

### 🔴 Delete Operations

- Delete User
- Delete Oil Calculation
- Delete Oil Record
- **Data tersimpan**: Old values sebelum dihapus (JSON)

### 🔵 Auth Operations

- Login (successful)
- Logout
- **Data tersimpan**: IP address, user agent, timestamp

### 🟣 Export Operations (Ready for implementation)

- Export Oil Losses
- Export OLWB
- Export Performance
- **Data tersimpan**: Filters used, export type

---

## 💡 Cara Menggunakan di Controller Lain

### 1. **Tambahkan Trait ke Controller**

```php
use App\Traits\LogsActivity;

class YourController extends Controller
{
    use LogsActivity;

    // ...
}
```

### 2. **Log Create Operation**

```php
public function store(Request $request)
{
    $model = YourModel::create($validated);

    // Log the creation
    $this->logCreate(
        $model,
        "Deskripsi custom: Data baru berhasil dibuat",
        ['additional_context' => 'optional']
    );

    return redirect()->back();
}
```

### 3. **Log Update Operation**

```php
public function update(Request $request, $id)
{
    $model = YourModel::findOrFail($id);

    // Simpan data lama sebelum update
    $oldData = $model->getAttributes();

    // Update model
    $model->update($validated);

    // Log the update
    $this->logUpdate(
        $model,
        $oldData,
        "Deskripsi: Data berhasil diupdate"
    );

    return redirect()->back();
}
```

### 4. **Log Delete Operation**

```php
public function destroy($id)
{
    $model = YourModel::findOrFail($id);

    // Log SEBELUM delete (supaya data masih ada)
    $this->logDelete(
        $model,
        "Deskripsi: Data berhasil dihapus"
    );

    $model->delete();

    return redirect()->back();
}
```

### 5. **Log Export Operation**

```php
public function export(Request $request)
{
    // ... export logic

    $this->logExport(
        'Data Reports',
        $request->only(['start_date', 'end_date', 'kode'])
    );

    return Excel::download(...);
}
```

### 6. **Log Custom Activity**

```php
$this->logActivity(
    action: 'approve',  // create, update, delete, approve, reject, etc
    description: 'Manager menyetujui laporan bulanan',
    model: $report,
    oldValues: ['status' => 'pending'],
    newValues: ['status' => 'approved'],
    metadata: ['approved_by' => Auth::user()->name]
);
```

---

## 📈 Performance & Optimization

### **Indexes yang Sudah Diterapkan:**

- ✅ `(user_id, created_at)` → Filter by user + date
- ✅ `(model_type, model_id)` → Track specific model
- ✅ `(action, created_at)` → Filter by action
- ✅ `created_at` → Date range queries

### **JSON Storage:**

- Old/new values disimpan sebagai JSON untuk flexibility
- Metadata bisa contain additional context apapun

### **Cleanup Strategy (Optional):**

Route `/activity-logs/cleanup` ready untuk:

- Hapus logs lebih dari X hari
- Archive ke cold storage
- Export ke file sebelum delete

---

## 🔍 Query Examples

### **Get logs for specific user:**

```php
ActivityLog::byUser($userId)->recent(30)->get();
```

### **Get all delete operations:**

```php
ActivityLog::byAction('delete')->orderBy('created_at', 'desc')->get();
```

### **Get logs for specific model:**

```php
ActivityLog::byModelType('App\\Models\\User')->get();
```

### **Get logs in date range:**

```php
ActivityLog::dateRange('2026-01-01', '2026-12-31')->get();
```

---

## ✅ Testing Checklist

### **Sudah Bisa Dicoba:**

1. ✅ Login → Check activity_logs table
2. ✅ Input Oil Losses → Check create log
3. ✅ Edit Oil Losses → Check update log with old/new values
4. ✅ Delete Oil Losses → Check delete log
5. ✅ Create User → Check create log
6. ✅ Update User → Check update log
7. ✅ Delete User → Check delete log
8. ✅ Logout → Check logout log
9. ✅ Akses `/activity-logs` → View all logs with filters
10. ✅ Klik detail log → See full context (old vs new)

### **Filter Testing:**

- ✅ Filter by User
- ✅ Filter by Action (create/update/delete)
- ✅ Filter by Date Range
- ✅ Search by description
- ✅ Model Type filter

---

## 🎨 UI Features

### **Badge Colors:**

- 🟢 **Green**: create, input
- 🟡 **Yellow**: update, edit
- 🔴 **Red**: delete, destroy
- 🔵 **Blue**: login
- ⚫ **Gray**: logout
- 🟣 **Indigo**: export

### **Responsive Design:**

- ✅ Mobile: Cards view dengan stacked layout
- ✅ Tablet: Table dengan horizontal scroll
- ✅ Desktop: Full table dengan proper width

---

## 🚀 Future Enhancements (Optional)

1. **Export Logs to Excel** - Export filtered logs
2. **Real-time Notifications** - Alert untuk critical actions
3. **Dashboard Widget** - Recent activities di dashboard
4. **Advanced Analytics** - Charts & graphs untuk activity trends
5. **Automatic Cleanup Job** - Cron job untuk hapus old logs
6. **Restore Capability** - Restore deleted data dari logs (risky!)

---

## ⚠️ Important Notes

### **Security:**

- ✅ Password tidak pernah di-log (excluded di trait)
- ✅ IP address & user agent tersimpan untuk security audit
- ✅ Semua logs memiliki user attribution

### **Data Retention:**

- No automatic deletion (manual cleanup via route)
- Consider implementing data retention policy (e.g., 1 year)
- Old logs can be archived to cold storage

### **Error Handling:**

- Logging errors tidak akan mengganggu application flow
- Failed logs akan di-log ke Laravel Log (`\Log::error()`)

---

## 📝 Summary

✅ **Sistem Activity Logging SUDAH LENGKAP & SIAP DIGUNAKAN!**

**Yang sudah terimplementasi:**

- ✅ Database table dengan proper indexes
- ✅ Model dengan helper methods
- ✅ Trait untuk mudah digunakan di controller manapun
- ✅ Logging di OilController, UserController, AuthController
- ✅ UI untuk view logs dengan filter lengkap
- ✅ Detail view dengan old/new comparison
- ✅ Sidebar menu dengan permission check
- ✅ Routes dengan proper protection

**Cara Testing:**

1. Login sebagai PPIC atau Super Admin
2. Buka menu "Activity Log" di sidebar
3. Lakukan beberapa aksi (input/edit/delete data)
4. Refresh halaman Activity Log
5. Lihat semua aktivitas tercatat dengan detail lengkap!

---

**Developed with ❤️ using Laravel Best Practices**
