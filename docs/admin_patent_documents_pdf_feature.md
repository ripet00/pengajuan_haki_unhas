# Fitur Admin: Lihat & Download Dokumen Paten PDF

## Ringkasan Fitur

Menambahkan tombol baru di halaman admin **Reports Paten** untuk melihat dan mendownload 4 file PDF dokumen paten yang diupload oleh user (Deskripsi, Klaim, Abstrak, Gambar).

## Lokasi Fitur

### 1. Tombol di Halaman Reports
- **URL**: `http://127.0.0.1:8000/admin/reports-paten`
- **Lokasi**: Di setiap card biodata, setelah section "Dokumen Permohonan"
- **Kondisi Muncul**: Tombol hanya muncul jika `application_document` sudah terbit

### 2. Halaman Detail Dokumen PDF
- **URL**: `http://127.0.0.1:8000/admin/reports-paten/{biodataPaten}/patent-documents`
- **Fungsi**: Menampilkan 4 file PDF dengan status upload dan tombol download

## File yang Dibuat/Diubah

### 1. View Baru
**File**: `resources/views/admin/reports-paten/show-patent-documents.blade.php`
- ✅ Header dengan info biodata dan judul paten
- ✅ Progress bar upload (0-100%)
- ✅ Grid 4 kartu dokumen (Deskripsi, Klaim, Abstrak, Gambar)
- ✅ Status indikator: Lengkap / Sebagian / Belum Ada
- ✅ Tombol download untuk setiap file yang tersedia
- ✅ Info box dengan penjelasan dokumen

### 2. View yang Diubah
**File**: `resources/views/admin/reports-paten/index.blade.php`

**Perubahan**: Menambahkan section baru setelah "Dokumen Permohonan"

```php
<!-- Upload Dokumen Paten PDF Section - NEW -->
@if($biodataPaten->application_document)
    <div class="bg-gradient-to-r from-purple-50 to-indigo-50 border-2...">
        // Status badge (Lengkap/Sebagian/Belum)
        // Checklist 4 file
        // Tombol "Lihat & Download Dokumen PDF"
        // Timestamp update terakhir
    </div>
@endif
```

**Fitur Tombol**:
- Badge status: 🟢 Lengkap / 🟡 Sebagian / ⚪ Belum
- Checklist 4 dokumen dengan ikon check/circle
- Tombol gradient purple-to-indigo
- Update timestamp (diffForHumans)

### 3. Routes Baru
**File**: `routes/web.php`

```php
// Patent Documents PDF routes (NEW)
Route::get('reports-paten/{biodataPaten}/patent-documents', 
    [ReportPatenController::class, 'showPatentDocuments'])
    ->name('admin.reports-paten.show-patent-documents');

Route::get('reports-paten/{biodataPaten}/patent-documents/{type}/download', 
    [ReportPatenController::class, 'downloadPatentDocument'])
    ->name('admin.reports-paten.download-patent-document');
```

### 4. Controller Methods Baru
**File**: `app/Http/Controllers/Admin/ReportPatenController.php`

**Method 1**: `showPatentDocuments(BiodataPaten $biodataPaten)`
- Menampilkan halaman detail 4 dokumen PDF
- Load relationships: submissionPaten, user, inventors
- Return view dengan data biodataPaten

**Method 2**: `downloadPatentDocument(BiodataPaten $biodataPaten, $type)`
- Download file PDF berdasarkan type (deskripsi/klaim/abstrak/gambar)
- Validasi type dan file existence
- Return file download response

## UI/UX Design

### 1. Tombol di Reports Index

#### Status Badge:
- **Lengkap** (hijau): 3 file wajib sudah ada
- **Sebagian** (kuning): Ada file tapi belum lengkap
- **Belum** (abu-abu): Tidak ada file sama sekali

#### Checklist Files:
```
✅ Deskripsi    ✅ Klaim
✅ Abstrak      ⚪ Gambar
```

### 2. Halaman Detail Dokumen

#### Header (Purple Gradient):
- Icon file-pdf
- Judul paten
- Info pengaju & kategori
- Badge status (Lengkap/Sebagian/Belum)

#### Progress Bar:
- Width: 0% - 100%
- Color: Purple gradient
- Formula: `(jumlah_file_wajib / 3) * 100`

#### Grid 4 Kartu:
1. **Deskripsi** (Hijau/Abu-abu) - WAJIB
2. **Klaim** (Hijau/Abu-abu) - WAJIB
3. **Abstrak** (Hijau/Abu-abu) - WAJIB
4. **Gambar** (Biru/Abu-abu) - OPSIONAL

**Jika File Ada**:
- Border hijau/biru
- Header hijau/biru dengan check icon
- Box info file (nama file)
- Tombol download hijau/biru

**Jika File Belum Ada**:
- Border abu-abu
- Header abu-abu dengan times icon
- Icon inbox besar
- Text "Belum diupload" / "Tidak ada file"

## Flow Admin

```
1. Admin buka halaman Reports Paten
   http://127.0.0.1:8000/admin/reports-paten
   
2. Admin lihat biodata yang dokumen permohonannya sudah terbit
   
3. Admin klik tombol "Lihat & Download Dokumen PDF"
   ↓
4. Redirect ke halaman detail dokumen paten
   http://127.0.0.1:8000/admin/reports-paten/{id}/patent-documents
   
5. Admin lihat status upload:
   - Progress bar (0%-100%)
   - 4 kartu dokumen
   
6. Admin download file yang diinginkan:
   - Klik tombol "Download [Nama] PDF"
   - File terdownload ke komputer
   
7. Admin kembali ke reports (tombol Back)
```

## Validasi & Security

### Authorization:
- ✅ Admin harus login
- ✅ Role: `admin_paten` atau `super_admin`
- ✅ Check admin session

### File Validation:
- ✅ Check file existence di storage
- ✅ Validate type (deskripsi/klaim/abstrak/gambar)
- ✅ Check storage path exists

### Error Handling:
- ✅ 403 jika admin tidak valid
- ✅ 404 jika type tidak valid
- ✅ Error message jika file tidak ditemukan

## Database Fields

Tidak ada perubahan database. Menggunakan field yang sudah ada:

**Table**: `biodata_paten`
- `deskripsi_pdf` (string, nullable)
- `klaim_pdf` (string, nullable)
- `abstrak_pdf` (string, nullable)
- `gambar_pdf` (string, nullable)
- `patent_documents_uploaded_at` (timestamp, nullable)

## Integration dengan Fitur Sebelumnya

### User Side:
User upload di: `/users/submissions-paten/{id}` (setelah dokumen terbit)

### Admin Side:
Admin view & download di: `/admin/reports-paten/{id}/patent-documents`

### Sinkronisasi:
- ✅ Real-time: Admin langsung bisa lihat file yang user upload
- ✅ Timestamp: Menampilkan kapan terakhir user update
- ✅ Progress: Otomatis update berdasarkan jumlah file

## Testing Checklist

### Tombol di Reports Index:
- [ ] Tombol hanya muncul jika dokumen permohonan sudah terbit
- [ ] Badge status benar (Lengkap/Sebagian/Belum)
- [ ] Checklist 4 file akurat
- [ ] Timestamp update terakhir benar
- [ ] Link mengarah ke halaman yang benar

### Halaman Detail Dokumen:
- [ ] Header menampilkan info yang benar
- [ ] Progress bar menghitung dengan benar (0-100%)
- [ ] Status badge sesuai kondisi file
- [ ] Kartu Deskripsi: tampilan & download benar
- [ ] Kartu Klaim: tampilan & download benar
- [ ] Kartu Abstrak: tampilan & download benar
- [ ] Kartu Gambar: tampilan & download benar
- [ ] Tombol back berfungsi

### Download Function:
- [ ] Download Deskripsi PDF berhasil
- [ ] Download Klaim PDF berhasil
- [ ] Download Abstrak PDF berhasil
- [ ] Download Gambar PDF berhasil
- [ ] Error handling jika file tidak ada
- [ ] Nama file download sesuai format

### Authorization:
- [ ] Admin paten bisa akses
- [ ] Super admin bisa akses
- [ ] Admin hakcipta tidak bisa akses
- [ ] Pendamping paten tidak bisa akses
- [ ] User biasa tidak bisa akses

## Screenshots Lokasi

### Tombol di Reports Index:
```
┌─────────────────────────────────────┐
│  Penyetoran Berkas                  │
│  ✅ Sudah Disetor                   │
├─────────────────────────────────────┤
│  Dokumen Permohonan                 │
│  ✅ Dokumen Sudah Terbit            │
│  [Lihat Dokumen]                    │
├─────────────────────────────────────┤
│  📄 Dokumen Paten PDF               │
│  ✅ Lengkap / 🟡 Sebagian          │
│                                     │
│  ✅ Deskripsi   ✅ Klaim           │
│  ✅ Abstrak     ⚪ Gambar           │
│                                     │
│  [Lihat & Download Dokumen PDF] ← TOMBOL BARU │
│  ⏱️ Update: 5 menit yang lalu      │
└─────────────────────────────────────┘
```

## Notes

1. **Posisi Tombol**: Diletakkan setelah section "Dokumen Permohonan" agar flow logis
2. **Color Scheme**: Purple/Indigo untuk konsisten dengan tema dokumen paten
3. **Responsive**: Menggunakan grid 2 kolom di desktop, 1 kolom di mobile
4. **Performance**: Load only when needed (tidak di index, hanya di detail page)
5. **User Friendly**: Clear indicators untuk file yang sudah/belum diupload

---

**Developer**: GitHub Copilot  
**Date**: February 4, 2026  
**Feature**: Admin View & Download Patent Documents PDF  
**Version**: 1.0
