# Fitur Jenis Karya

## Overview
Fitur ini menambahkan kemampuan untuk mengategorisasi submissions berdasarkan jenis karya yang diajukan. Admin dapat mengelola daftar jenis karya, dan user harus memilih jenis karya saat mengajukan submission.

## Features Implemented

### 1. Database Schema
- **Table `jenis_karyas`**:
  - `id` (primary key)
  - `nama` (string) - nama jenis karya
  - `is_active` (boolean) - status aktif/nonaktif
  - `timestamps`

- **Foreign Key di `submissions`**:
  - `jenis_karya_id` (foreign key ke jenis_karyas.id)

### 2. Models & Relations
- **JenisKarya Model**: 
  - Fillable: nama, is_active
  - Relation: hasMany submissions
  - Scope: active() untuk jenis karya aktif

- **Submission Model**:
  - Ditambahkan jenis_karya_id ke fillable
  - Relation: belongsTo JenisKarya

### 3. Admin Management Features
- **Routes**: `admin/jenis-karyas` (resource routes)
- **Controller**: `Admin\JenisKaryaController`
- **Views**:
  - `admin/jenis-karyas/index.blade.php` - list dan manage jenis karya
  - `admin/jenis-karyas/create.blade.php` - tambah jenis karya baru
  - `admin/jenis-karyas/edit.blade.php` - edit jenis karya

#### Admin Features:
- ✅ View semua jenis karya dengan pagination
- ✅ Tambah jenis karya baru
- ✅ Edit nama dan status jenis karya
- ✅ Hapus jenis karya (jika tidak digunakan submissions)
- ✅ Toggle status aktif/nonaktif
- ✅ Melihat jumlah submissions per jenis karya
- ✅ Protection: tidak bisa hapus jenis karya yang sedang digunakan

### 4. User Submission Features
- **Form Submission**: Dropdown jenis karya (hanya menampilkan yang aktif)
- **Validation**: jenis_karya_id required dan harus exists di database
- **Display**: Menampilkan jenis karya di list submissions (user & admin)

### 5. Default Data
**Jenis Karya Default** (via JenisKaryaSeeder):
1. Buku
2. Buku Saku
3. Buku Panduan/Petunjuk
4. Modul
5. Booklet
6. Karya tulis
7. Artikel
8. Disertasi
9. Flyer
10. Poster
11. Leaflet
12. Alat peraga
13. Program komputer
14. Karya rekaman video

## UI/UX Features

### Admin Interface
- **Modern card-based design** dengan glass effects
- **Status badges** untuk aktif/nonaktif
- **Action buttons** dengan hover effects
- **Form validation** dengan error messages
- **Search dan pagination** 
- **Responsive design**

### User Interface
- **Dropdown selection** di form submission
- **Badge display** di list submissions
- **Konsistensi visual** dengan design existing

## Technical Implementation

### Files Modified/Created:
1. **Migrations**:
   - `create_jenis_karyas_table.php`
   - `add_jenis_karya_id_to_submissions_table.php`

2. **Models**:
   - `app/Models/JenisKarya.php` (new)
   - `app/Models/Submission.php` (updated)

3. **Controllers**:
   - `app/Http/Controllers/Admin/JenisKaryaController.php` (new)
   - `app/Http/Controllers/User/SubmissionController.php` (updated)
   - `app/Http/Controllers/Admin/SubmissionController.php` (updated)

4. **Requests**:
   - `app/Http/Requests/StoreSubmissionRequest.php` (updated)

5. **Views**:
   - `resources/views/admin/jenis-karyas/` (new directory)
   - `resources/views/user/submissions/create.blade.php` (updated)
   - `resources/views/user/submissions/index.blade.php` (updated)
   - `resources/views/admin/submissions/index.blade.php` (updated)
   - `resources/views/admin/partials/sidebar.blade.php` (updated)

6. **Routes**:
   - `routes/web.php` (updated dengan resource routes)

7. **Seeders**:
   - `database/seeders/JenisKaryaSeeder.php` (new)

## Setup Instructions

### 1. Run Migrations
```bash
php artisan migrate
```

### 2. Seed Default Data
```bash
php artisan db:seed --class=JenisKaryaSeeder
```

### 3. Clear Cache (Optional)
```bash
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

## Usage

### For Admin:
1. Login ke admin panel
2. Navigate ke "Jenis Karya" di sidebar
3. Manage jenis karya (create, edit, delete, toggle status)

### For Users:
1. Saat membuat submission baru
2. Pilih kategori (Universitas/Umum)
3. **Pilih jenis karya** dari dropdown
4. Lanjutkan dengan form submission

## Features Validation

### Admin Side:
- ✅ CRUD operations untuk jenis karya
- ✅ Status management (aktif/nonaktif)
- ✅ Usage tracking (berapa submissions pakai jenis karya ini)
- ✅ Protection dari delete jika masih digunakan
- ✅ Modern UI dengan proper feedback

### User Side:
- ✅ Dropdown selection dengan data real dari database
- ✅ Validation required
- ✅ Display di list submissions
- ✅ Hanya tampilkan jenis karya yang aktif

### Database:
- ✅ Proper relations dan foreign keys
- ✅ Migration rollback support
- ✅ Default data seeding

## Future Enhancements
- [ ] Bulk import jenis karya via CSV
- [ ] Analytics dashboard untuk jenis karya paling populer
- [ ] Auto-suggest jenis karya berdasarkan judul
- [ ] Export laporan per jenis karya