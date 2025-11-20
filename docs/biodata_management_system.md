# Fitur Biodata Management System

## Overview
Sistem manajemen biodata untuk aplikasi pengajuan HKI Universitas Hasanuddin yang memungkinkan pengguna untuk melengkapi data pencipta karya cipta setelah submission disetujui admin.

## Database Schema

### Tabel `biodatas`
```sql
- id (bigint, primary key, auto increment)
- submission_id (bigint, foreign key -> submissions.id)
- user_id (bigint, foreign key -> users.id)
- tempat_ciptaan (varchar 255) - Tempat karya cipta dibuat
- tanggal_ciptaan (date) - Tanggal karya cipta dibuat
- uraian_singkat (text) - Deskripsi singkat karya cipta
- status (enum: pending, approved, denied) - Status review admin
- rejection_reason (text, nullable) - Alasan penolakan dari admin
- reviewed_at (timestamp, nullable) - Waktu review
- reviewed_by (bigint, nullable, foreign key -> admins.id)
- created_at (timestamp)
- updated_at (timestamp)

Indexes:
- submission_id
- user_id
- status

Notes: 
- Title diambil dari tabel submissions (melalui relationship)
- Tidak ada field final_pdf_path karena menggunakan PDF dari submission
```

### Tabel `biodata_members`
```sql
- id (bigint, primary key, auto increment)
- biodata_id (bigint, foreign key -> biodatas.id)
- name (varchar 255) - Nama lengkap pencipta
- nik (varchar 20, nullable) - Nomor Induk Kependudukan
- pekerjaan (varchar 255, nullable) - Pekerjaan
- universitas (varchar 255, nullable) - Nama universitas
- fakultas (varchar 255, nullable) - Nama fakultas
- program_studi (varchar 255, nullable) - Program studi
- alamat (text, nullable) - Alamat lengkap
- kelurahan (varchar 255, nullable) - Kelurahan
- kecamatan (varchar 255, nullable) - Kecamatan
- kota_kabupaten (varchar 255, nullable) - Kota/Kabupaten
- provinsi (varchar 255, nullable) - Provinsi
- kode_pos (varchar 10, nullable) - Kode pos
- email (varchar 255, nullable) - Email
- nomor_hp (varchar 20, nullable) - Nomor HP
- kewarganegaraan (varchar 100, nullable, default: 'Indonesia')
- is_leader (boolean, default: false) - Apakah ketua pencipta
- created_at (timestamp)
- updated_at (timestamp)

Indexes:
- biodata_id
- is_leader
```

## Models & Relationships

### Model Biodata
```php
// Relationships
- belongsTo: submission (App\Models\Submission)
- belongsTo: user (App\Models\User)
- belongsTo: reviewedBy (App\Models\Admin) - nullable
- hasMany: members (App\Models\BiodataMember)

// Helper Methods
- canBeEdited(): boolean - Apakah biodata dapat diedit
- isPending(): boolean - Status pending
- isApproved(): boolean - Status approved
- isDenied(): boolean - Status denied

# Fillable Fields
- submission_id, user_id, tempat_ciptaan, tanggal_ciptaan
- uraian_singkat, status, rejection_reason, reviewed_at, reviewed_by

# Title Access
- Title accessed via relationship: $biodata->submission->title
- No direct title field in biodata table

// Casts
- tanggal_ciptaan: date
- reviewed_at: datetime
```

### Model BiodataMember
```php
// Relationships
- belongsTo: biodata (App\Models\Biodata)

// Fillable Fields
- biodata_id, name, nik, pekerjaan, universitas, fakultas
- program_studi, alamat, kelurahan, kecamatan, kota_kabupaten
- provinsi, kode_pos, email, nomor_hp, kewarganegaraan, is_leader

// Casts
- is_leader: boolean
```

### Enhanced Submission Model
```php
// New Relationship
- hasOne: biodata (App\Models\Biodata)
```

## Routes
```php
Route::prefix('users')->name('user.')->group(function () {
    Route::get('/submissions/{submission}/biodata/create', [BiodataController::class, 'create'])
        ->name('biodata.create');
    Route::post('/submissions/{submission}/biodata', [BiodataController::class, 'store'])
        ->name('biodata.store');
    Route::get('/submissions/{submission}/biodata/{biodata}', [BiodataController::class, 'show'])
        ->name('biodata.show');
});
```

## Controller Logic

### BiodataController
- **create()**: Menampilkan form create/edit biodata
  - Validasi ownership submission
  - Validasi status submission (harus approved)
  - Handle mode edit jika biodata sudah ada
  - Redirect ke view jika biodata sudah approved

- **store()**: Menyimpan/update biodata
  - Validasi comprehensive untuk semua fields
  - Support create dan update mode
  - Transaction handling untuk consistency
  - Maksimal 10 anggota pencipta
  - Member pertama otomatis menjadi ketua (is_leader = true)

- **show()**: Menampilkan detail biodata
  - Load relationships (members, reviewedBy)
  - Tampilkan status dan informasi review

## Business Rules

### Biodata Creation/Edit Rules
1. **Submission Requirement**: Hanya submission dengan status 'approved' yang dapat membuat biodata
2. **Ownership**: User hanya dapat mengelola biodata untuk submission miliknya
3. **Edit Permission**: Biodata dapat diedit jika status bukan 'approved'
4. **Member Limit**: Maksimal 10 anggota pencipta per biodata
5. **Leader Assignment**: Anggota pertama otomatis menjadi ketua
6. **Status Reset**: Saat edit biodata, status kembali ke 'pending' dan data review direset

### Status Workflow
```
pending -> approved (oleh admin)
pending -> denied (oleh admin dengan alasan)
denied -> pending (setelah user edit dan resubmit)
approved -> (tidak dapat diubah)
```

## UI Components

### Enhanced Submission Show Page
- **Biodata Status Section**: Menampilkan status biodata
- **Conditional Actions**: 
  - "Buat Biodata" jika belum ada dan submission approved
  - "Lihat Biodata" jika sudah ada
  - "Edit Biodata" jika ditolak atau pending
- **Floating Next Button**: Untuk submission approved yang belum ada biodata

### Biodata Create/Edit Form
- **Responsive Design**: Mobile-friendly dengan grid layout
- **Dynamic Member Management**: JavaScript untuk add/remove anggota
- **Form Validation**: Client dan server side validation
- **Progress Indicators**: Status info dan instruksi
- **Auto-fill**: Menggunakan data existing untuk edit mode

### Biodata Detail View
- **Comprehensive Display**: Semua informasi biodata dan anggota
- **Status Indicators**: Visual status dengan warna dan icon
- **Member Organization**: Informasi terorganisir dalam kategori
- **Action Buttons**: Sesuai permission dan status

## File Structure
```
app/
├── Http/Controllers/User/
│   └── BiodataController.php
├── Models/
│   ├── Biodata.php
│   ├── BiodataMember.php
│   └── Submission.php (enhanced)
database/migrations/
├── 2025_11_04_050236_create_biodatas_table.php
└── 2025_11_04_050237_create_biodata_members_table.php
resources/views/user/
├── biodata/
│   ├── create.blade.php
│   └── show.blade.php
└── submissions/
    └── show.blade.php (enhanced)
routes/
└── web.php (enhanced)
```

## Security Features
- **Authorization**: Middleware dan manual checks untuk ownership
- **CSRF Protection**: Token validation untuk semua form
- **Input Validation**: Comprehensive validation rules
- **SQL Injection Prevention**: Eloquent ORM dan parameter binding
- **XSS Prevention**: Blade template escaping

## Performance Considerations
- **Database Indexes**: Optimized untuk common queries
- **Eager Loading**: Load relationships untuk menghindari N+1
- **Transaction**: Atomic operations untuk data consistency
- **Caching**: Model relationship caching

## Testing Checklist
- [ ] Buat biodata baru untuk submission approved
- [ ] Edit biodata yang statusnya pending/denied
- [ ] Validasi tidak bisa edit biodata approved
- [ ] Test maksimal 10 anggota
- [ ] Test validation semua fields
- [ ] Test ownership validation
- [ ] Test responsive design
- [ ] Test JavaScript member management

## Future Enhancements
1. **File Upload**: Attachment dokumen pendukung
2. **Email Notifications**: Notifikasi status change
3. **Admin Review Interface**: Panel admin untuk review biodata
4. **Export PDF**: Generate biodata dalam format PDF
5. **Audit Trail**: Log semua perubahan biodata
6. **Bulk Operations**: Batch approval/rejection
7. **Member Import**: Import data anggota dari CSV/Excel