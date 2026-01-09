# Admin Accounts - Role Based Access Control

Sistem admin sekarang menggunakan 3 level role dengan permission yang berbeda-beda:

## 1. Super Admin
**Akses:** Akses penuh ke seluruh sistem
- **NIP:** `admin123`
- **Password:** `password123`
- **Phone:** `089876543210`

**Permissions:**
- ✅ Dashboard
- ✅ Kelola User
- ✅ Kelola Admin (Create, Edit, Delete)
- ✅ Jenis Karya (CRUD)
- ✅ Hak Cipta (Pengajuan & Biodata)
- ✅ Paten (Pengajuan & Biodata)

## 2. Admin Paten
**Akses:** Mengelola Paten saja
- **NIP:** `paten001`
- **Password:** `paten123`
- **Phone:** `081234567891`

**Permissions:**
- ✅ Dashboard
- ✅ Kelola User
- ❌ Kelola Admin
- ❌ Jenis Karya
- ❌ Hak Cipta
- ✅ Paten (Pengajuan & Biodata)

## 3. Admin Hak Cipta
**Akses:** Mengelola Hak Cipta dan Jenis Karya
- **NIP:** `hakcipta001`
- **Password:** `hakcipta123`
- **Phone:** `081234567892`

**Permissions:**
- ✅ Dashboard
- ✅ Kelola User
- ❌ Kelola Admin
- ✅ Jenis Karya (CRUD)
- ✅ Hak Cipta (Pengajuan & Biodata)
- ❌ Paten

---

## Role Matrix

| Feature | Super Admin | Admin Paten | Admin Hak Cipta |
|---------|:-----------:|:-----------:|:---------------:|
| Dashboard | ✅ | ✅ | ✅ |
| Kelola User | ✅ | ✅ | ✅ |
| Kelola Admin | ✅ | ❌ | ❌ |
| Nonaktifkan/Aktifkan Admin | ✅ | ❌ | ❌ |
| Jenis Karya | ✅ | ❌ | ✅ |
| Hak Cipta | ✅ | ❌ | ✅ |
| Paten | ✅ | ✅ | ❌ |

---

## Technical Implementation

### Database
- Kolom `role` ditambahkan ke tabel `admins`
- Type: `ENUM('super_admin', 'admin_paten', 'admin_hakcipta')`
- Default: `super_admin`
- Kolom `is_active` ditambahkan ke tabel `admins`
- Type: `BOOLEAN`
- Default: `true`

### Model Methods
- `isSuperAdmin()` - Check if super admin
- `canAccessHakCipta()` - Check if can access Hak Cipta
- `canAccessPaten()` - Check if can access Paten
- `canAccessJenisKarya()` - Check if can access Jenis Karya
- `canManageAdmins()` - Check if can manage admins
- `canAccessUserManagement()` - Check if can access user management

### Middleware
- `CheckAdminRole` middleware untuk proteksi route
- Usage: `->middleware('admin.role:admin_paten')`
- Super admin selalu memiliki akses ke semua route

### Route Protection
```php
// Hanya super_admin
Route::middleware('admin.role:super_admin')->group(function () {
    // Kelola admin routes
});

// super_admin, admin_hakcipta
Route::middleware('admin.role:admin_hakcipta')->group(function () {
    // Hak Cipta & Jenis Karya routes
});

// super_admin, admin_paten
Route::middleware('admin.role:admin_paten')->group(function () {
    // Paten routes
});
```

### UI
- Sidebar menu item muncul/hilang berdasarkan permission
- Header menampilkan role badge dengan warna berbeda:
  - Super Admin: Purple
  - Admin HKI: Blue
  - Admin Paten: Green
  - Admin Hak Cipta: Orange
- Tabel kelola admin menampilkan role dan status setiap admin
- Form create admin memiliki dropdown untuk memilih role
- Super Admin dapat menonaktifkan/mengaktifkan admin lain (tidak bisa menonaktifkan diri sendiri)
- Admin yang dinonaktifkan otomatis logout dan tidak bisa login lagi sampai diaktifkan kembali

---

## Admin Status Management

### Fitur Nonaktifkan/Aktifkan Admin
Super Admin dapat menonaktifkan atau mengaktifkan kembali admin lain:

**Fitur:**
- ✅ Tombol toggle status (Aktif/Nonaktif) di halaman Kelola Admin
- ✅ Super Admin tidak bisa menonaktifkan diri sendiri
- ✅ Admin yang nonaktif otomatis logout dari sistem
- ✅ Admin yang nonaktif tidak bisa login kembali
- ✅ Status ditampilkan dengan badge berwarna:
  - Hijau: Aktif
  - Merah: Nonaktif

**Keamanan:**
- Middleware `AdminAuthMiddleware` dan `CheckAdminRole` melakukan pengecekan status aktif
- Jika admin dinonaktifkan, session langsung dihapus
- Admin yang nonaktif diarahkan ke halaman login dengan pesan error

---

## Migration History
- **2025_12_29_000001_add_role_to_admins_table.php** - Menambahkan kolom role
- **2025_12_29_000002_add_is_active_to_admins_table.php** - Menambahkan kolom is_active
