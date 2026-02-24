# 🔬 Oil Losses Calculation System

> Sistem perhitungan Oil Losses untuk monitoring efisiensi produksi CPO (Crude Palm Oil) dari TBS (Tandan Buah Segar).

---

## 📊 Rumus Perhitungan

Sistem ini menggunakan rumus industri standar untuk menghitung oil losses:

### 1. **OER (Oil Extraction Rate)**

```
OER = (CPO Produced ÷ TBS Weight) × 100%
```

- **Standard OER**: 22% (dapat dikonfigurasi di `OilLossService`)
- **Interpretasi**: Persentase minyak yang berhasil diekstrak dari TBS

### 2. **KER (Kernel Extraction Rate)**

```
KER = (Kernel Produced ÷ TBS Weight) × 100%
```

- **Standard KER**: 5%
- **Interpretasi**: Persentase kernel yang dihasilkan dari TBS

### 3. **Oil Losses (%)**

```
Oil Losses = Standard OER - Actual OER
```

- **Positif** = Ada losses (produksi di bawah standard)
- **Negatif** = Performa bagus (produksi melebihi standard)

### 4. **Total Losses (kg)**

```
Total Losses = (Oil Losses% ÷ 100) × TBS Weight
```

- Losses dalam satuan kilogram

---

## 🏗️ Struktur Database

### Tabel: `oil_losses`

| Field              | Type          | Deskripsi                         |
| ------------------ | ------------- | --------------------------------- |
| `id`               | bigint        | Primary key                       |
| `user_id`          | bigint        | FK ke users (yang input data)     |
| `analysis_date`    | date          | Tanggal analisa                   |
| `analysis_time`    | time          | Jam analisa                       |
| `tbs_weight`       | decimal(10,2) | Berat TBS dalam kg                |
| `moisture_content` | decimal(5,2)  | Kadar air (%) - opsional          |
| `ffa_content`      | decimal(5,2)  | Free Fatty Acid (%) - opsional    |
| `cpo_produced`     | decimal(10,2) | CPO yang dihasilkan (kg)          |
| `kernel_produced`  | decimal(10,2) | Kernel yang dihasilkan (kg)       |
| `oil_to_tbs`       | decimal(5,2)  | OER hasil perhitungan (%)         |
| `kernel_to_tbs`    | decimal(5,2)  | KER hasil perhitungan (%)         |
| `oil_losses`       | decimal(5,2)  | Oil losses hasil perhitungan (%)  |
| `total_losses`     | decimal(10,2) | Total losses dalam kg             |
| `batch_number`     | string        | Nomor batch produksi - opsional   |
| `notes`            | text          | Catatan tambahan                  |
| `status`           | enum          | draft/submitted/approved/rejected |
| `approved_by`      | bigint        | FK ke users (yang approve)        |
| `approved_at`      | timestamp     | Waktu approval                    |
| `created_at`       | timestamp     | -                                 |
| `updated_at`       | timestamp     | -                                 |
| `deleted_at`       | timestamp     | Soft delete                       |

**Indexes:**

- `(analysis_date, status)` - untuk filter cepat
- `user_id` - untuk query by user

---

## 📁 File Structure

```
app/
├── Models/
│   └── OilLoss.php                      # Model dengan relationships & scopes
├── Services/
│   └── OilLossService.php                # Business logic & kalkulasi
└── Http/
    └── Controllers/
        └── LabController.php             # CRUD operations

database/
└── migrations/
    └── 2026_02_24_023531_create_oil_losses_table.php

resources/
└── views/
    └── lab/
        ├── index.blade.php               # List data oil losses
        ├── create.blade.php              # Form input
        └── show.blade.php                # Detail view
```

---

## 🔧 OilLossService Methods

### Public Methods:

#### 1. `calculate(array $data): array`

Menghitung oil losses dari data input.

**Input:**

```php
[
    'tbs_weight' => 1000,      // kg
    'cpo_produced' => 220,     // kg
    'kernel_produced' => 50,   // kg (optional)
]
```

**Output:**

```php
[
    'oil_to_tbs' => 22.00,      // %
    'kernel_to_tbs' => 5.00,    // %
    'oil_losses' => 0.00,       // %
    'total_losses' => 0.00,     // kg
]
```

---

#### 2. `store(array $data, int $userId): OilLoss`

Simpan data oil loss dengan perhitungan otomatis.

**Features:**

- ✅ Auto-calculate losses
- ✅ Set status = 'submitted' (butuh approval)
- ✅ Database transaction
- ✅ Activity logging

**Usage:**

```php
$oilLossService = app(OilLossService::class);
$oilLoss = $oilLossService->store($validated, Auth::id());
```

---

#### 3. `update(OilLoss $oilLoss, array $data): OilLoss`

Update data dengan recalculate losses.

**Validation:**

- Hanya data dengan status `draft` atau `rejected` yang bisa diedit
- Data `approved` tidak bisa diedit

---

#### 4. `approve(OilLoss $oilLoss, int $approverId): OilLoss`

Approve oil loss record.

**Actions:**

- Set status = 'approved'
- Set approved_by & approved_at
- Log activity

---

#### 5. `reject(OilLoss $oilLoss, ?string $reason = null): OilLoss`

Reject oil loss record dengan alasan.

---

#### 6. `getPerformanceAnalysis(string $startDate, string $endDate): array`

Mendapatkan analisis performa untuk periode tertentu.

**Output:**

```php
[
    'total_records' => 150,
    'avg_oil_losses' => 1.25,      // %
    'avg_oer' => 20.75,            // %
    'total_tbs_processed' => 150000, // kg
    'total_cpo_produced' => 31125,   // kg
    'total_losses_kg' => 1875,       // kg
    'standard_oer' => 22,            // %
]
```

---

## 🚀 Cara Menggunakan

### 1. Input Data Oil Losses

Akses menu: **Lab → Input Data Baru**

**Form Input:**

- ✅ Tanggal & Jam analisa
- ✅ Berat TBS (wajib)
- ✅ CPO yang dihasilkan (wajib)
- ⚪ Kernel yang dihasilkan (opsional)
- ⚪ Kadar air & FFA (opsional)
- ⚪ Nomor batch (opsional)
- ⚪ Catatan (opsional)

**Setelah submit:**

- Sistem otomatis menghitung OER, KER, Oil Losses, dan Total Losses
- Status = "Submitted" (menunggu approval)
- Butuh permission: `create lab results`

---

### 2. Approval Workflow

**Roles yang terlibat:**

| Role         | Permission                                   | Aksi                      |
| ------------ | -------------------------------------------- | ------------------------- |
| Staf Lab     | `create lab results`, `edit own lab results` | Input & edit data sendiri |
| Kepala Lab   | `approve lab results`, `reject lab results`  | Approve/reject data       |
| Mill Manager | `approve lab results`                        | Approve data              |

**Status Flow:**

```
draft → submitted → approved
                 ↘ rejected → (bisa diedit lagi)
```

---

### 3. Melihat Data & Statistik

**Menu Lab menampilkan:**

- Total records
- Pending approval count
- Approved today count
- Tabel data dengan filter
- OER dengan color coding:
    - 🟢 Hijau = OER ≥ 22% (baik)
    - 🔴 Merah = OER < 22% (losses)

---

## 📊 Contoh Perhitungan

### Kasus 1: Produksi Normal

**Input:**

- TBS: 10,000 kg
- CPO: 2,200 kg
- Kernel: 500 kg

**Hasil:**

```
OER = (2,200 ÷ 10,000) × 100 = 22.00%
KER = (500 ÷ 10,000) × 100 = 5.00%
Oil Losses = 22% - 22% = 0.00%
Total Losses = 0 kg
```

✅ **Performa sesuai standard**

---

### Kasus 2: Ada Losses

**Input:**

- TBS: 10,000 kg
- CPO: 2,000 kg (kurang dari expected)
- Kernel: 480 kg

**Hasil:**

```
OER = (2,000 ÷ 10,000) × 100 = 20.00%
KER = (480 ÷ 10,000) × 100 = 4.80%
Oil Losses = 22% - 20% = 2.00%
Total Losses = (2÷100) × 10,000 = 200 kg
```

❌ **Ada losses 2%, setara 200 kg CPO**

---

### Kasus 3: Performa Tinggi

**Input:**

- TBS: 10,000 kg
- CPO: 2,350 kg (melebihi standard)
- Kernel: 520 kg

**Hasil:**

```
OER = (2,350 ÷ 10,000) × 100 = 23.50%
KER = (520 ÷ 10,000) × 100 = 5.20%
Oil Losses = 22% - 23.5% = -1.50%
Total Losses = -150 kg
```

✅ **Performa excellent! Produksi melebihi standard 1.5%**

---

## 🔐 Permission Requirements

| Aksi                     | Permission                                     |
| ------------------------ | ---------------------------------------------- |
| Lihat list oil losses    | `view lab` atau `view lab results`             |
| Lihat hanya data sendiri | `view own lab results`                         |
| Input data baru          | `create lab results`                           |
| Edit data                | `edit lab results` atau `edit own lab results` |
| Hapus data               | `delete lab results`                           |
| Approve data             | `approve lab results`                          |
| Reject data              | `reject lab results`                           |
| Export data              | `export lab data`                              |

---

## 🎯 Future Enhancements

### 1. **Export to Excel/PDF**

```php
// Already prepared in controller
public function export(Request $request)
{
    // TODO: Implement Excel/PDF export
}
```

### 2. **Dashboard Analytics**

- Graf trend OER per bulan
- Perbandingan losses antar periode
- Top performers (user dengan OER tertinggi)

### 3. **Alert System**

- Notifikasi jika oil losses > threshold tertentu
- Email report otomatis ke manager

### 4. **Batch Processing**

- Import data dari Excel
- Bulk approval

---

## 🧪 Testing

### Manual Test:

1. **Login sebagai Staf Lab**

    ```
    - Buka menu Lab
    - Klik "Input Data Baru"
    - Isi form dengan data test
    - Submit → Status harus "Submitted"
    ```

2. **Login sebagai Kepala Lab**

    ```
    - Buka menu Lab
    - Lihat data pending
    - Approve data → Status jadi "Approved"
    ```

3. **Validasi Perhitungan**

    ```
    TBS: 1000 kg
    CPO: 220 kg

    Expected:
    - OER = 22%
    - Losses = 0%
    ```

---

## 📞 Troubleshooting

### Issue: Permission denied

**Solution:**

```bash
php artisan permission:cache-reset
php artisan cache:clear
```

### Issue: Service tidak ditemukan

**Solution:**
Service sudah auto-loaded oleh Laravel. Pastikan namespace benar:

```php
use App\Services\OilLossService;
```

### Issue: Migration error

**Solution:**

```bash
php artisan migrate:fresh  # WARNING: Reset database
# atau
php artisan migrate:rollback
php artisan migrate
```

---

## 📚 Related Documentation

- [PERMISSIONS.md](PERMISSIONS.md) - Dokumentasi lengkap permission system
- [MIGRATION_GUIDE.md](MIGRATION_GUIDE.md) - Panduan migrasi permission

---

**© 2026 YBS Management System - Oil Losses Module**  
_Built with Laravel 12 & Love ❤️_
