# Relokasi Fitur Upload Dokumen Paten

## Ringkasan Perubahan

Fitur **"Tahap Terakhir: Upload Dokumen Paten"** telah dipindahkan dari halaman biodata paten ke halaman detail pengajuan paten. Progress bar juga telah diperbarui dari 6 tahap menjadi 7 tahap.

## Perubahan Detail

### 1. Lokasi Fitur Upload Dokumen Paten

#### SEBELUM:
- **Lokasi**: `http://127.0.0.1:8000/users/submissions-paten/1/biodata-paten/1`
- **File**: `resources/views/user/biodata-paten/show.blade.php`
- **Masalah**: Form upload berada bersamaan dengan download 2 file Word (Formulir & Surat Pengalihan)

#### SESUDAH:
- **Lokasi**: `http://127.0.0.1:8000/users/submissions-paten/1`
- **File**: `resources/views/user/submissions-paten/show.blade.php`
- **Keuntungan**: Form upload terpisah dan lebih mudah diakses dari halaman utama pengajuan

### 2. Progress Bar Pengajuan Paten

#### SEBELUM (6 Tahap):
1. Upload Dokumen (20%)
2. Review Format (40%)
3. Review Substansi (60%)
4. Upload Biodata (80%)
5. Setor Berkas (100%)
6. Dokumen Terbit (100%) ✅ SELESAI

#### SESUDAH (7 Tahap):
1. Upload Dokumen (16.67%)
2. Review Format (33.33%)
3. Review Substansi (50%)
4. Upload Biodata (66.67%)
5. Setor Berkas (83.33%)
6. Dokumen Terbit (100%)
7. **Upload Dokumen Paten** (100%) ✅ SELESAI

### 3. Files Modified

#### Views:
1. **`resources/views/user/submissions-paten/show.blade.php`**
   - ✅ Updated progress calculation (6 steps → 7 steps)
   - ✅ Added Step 7: "Upload Dokumen Paten" in progress tracker
   - ✅ Added upload form section after document issued
   - ✅ Form muncul setelah biodata approved dan dokumen permohonan terbit

2. **`resources/views/user/biodata-paten/show.blade.php`**
   - ✅ Removed upload dokumen paten section
   - ✅ Halaman ini sekarang hanya untuk download formulir & surat-surat

#### Controllers:
3. **`app/Http/Controllers/User/SubmissionPatenController.php`**
   - ✅ Added method: `uploadPatentDocuments(Request $request, SubmissionPaten $submissionPaten)`
   - ✅ Added method: `downloadPatentDocument(SubmissionPaten $submissionPaten, $type)`
   - ✅ Upload & download logic moved from BiodataPatenController

4. **`app/Http/Controllers/User/BiodataPatenController.php`**
   - ⚠️ Methods `uploadPatentDocuments` dan `downloadPatentDocument` masih ada (untuk backward compatibility)
   - ℹ️ Bisa dihapus nanti setelah testing selesai

#### Routes:
5. **`routes/web.php`**
   - ✅ Added route: `POST /submissions-paten/{submissionPaten}/upload-patent-documents`
   - ✅ Added route: `GET /submissions-paten/{submissionPaten}/download-patent-document/{type}`
   - ⚠️ Old routes di biodata-paten masih ada (untuk backward compatibility)

### 4. Tampilan Upload Form

Form upload tetap sama dengan sebelumnya:

#### 4 File PDF yang Dapat Diupload:
1. **Deskripsi** (Wajib) - Max 10MB
2. **Klaim** (Wajib) - Max 10MB
3. **Abstrak** (Wajib) - Max 10MB
4. **Gambar** (Opsional) - Max 10MB

#### Features:
- ✅ Progress bar upload (0% - 100%)
- ✅ Upload satu per satu atau sekaligus
- ✅ File yang sudah diupload bisa diganti
- ✅ Preview file yang sudah diupload
- ✅ Download file yang sudah diupload
- ✅ Timestamp upload terakhir

### 5. Kapan Form Upload Muncul?

Form upload dokumen paten akan muncul ketika:
1. ✅ Biodata status = **approved**
2. ✅ Dokumen permohonan paten sudah **terbit** (`application_document` tidak null)
3. ✅ User berada di halaman: `/users/submissions-paten/{id}`

### 6. Flow User Journey

```
1. User upload dokumen paten (.docx)
   ↓
2. Admin review format ✓
   ↓
3. Pendamping review substansi ✓
   ↓
4. User upload biodata inventor
   ↓
5. Admin review biodata ✓
   ↓
6. User download formulir & setor berkas ke kantor
   ↓
7. Admin tandai "berkas disetor" ✓
   ↓
8. Admin upload dokumen permohonan paten (PDF)
   ↓
9. **[TAHAP BARU]** User upload 4 dokumen PDF:
   - Deskripsi PDF
   - Klaim PDF
   - Abstrak PDF
   - Gambar PDF (optional)
   ↓
10. ✅ SELESAI
```

### 7. Route Changes Summary

#### New Routes:
```php
// Upload dokumen paten (moved to submissions-paten)
POST   /users/submissions-paten/{submissionPaten}/upload-patent-documents
GET    /users/submissions-paten/{submissionPaten}/download-patent-document/{type}
```

#### Old Routes (deprecated, akan dihapus):
```php
// Upload dokumen paten (old location - biodata-paten)
POST   /users/biodata-paten/{biodataPaten}/upload-patent-documents
GET    /users/biodata-paten/{biodataPaten}/download-patent-document/{type}
```

### 8. Database Schema

Tidak ada perubahan schema database. Field yang digunakan:

**Table: `biodata_paten`**
- `deskripsi_pdf` (string, nullable)
- `klaim_pdf` (string, nullable)
- `abstrak_pdf` (string, nullable)
- `gambar_pdf` (string, nullable)
- `patent_documents_uploaded_at` (timestamp, nullable)

### 9. Testing Checklist

- [ ] Progress bar menampilkan 7 tahapan dengan benar
- [ ] Step 7 "Upload Dokumen Paten" muncul setelah dokumen terbit
- [ ] Form upload muncul di `/users/submissions-paten/{id}`
- [ ] Form upload TIDAK muncul di `/users/submissions-paten/{id}/biodata-paten/{id}`
- [ ] Upload dokumen Deskripsi berhasil
- [ ] Upload dokumen Klaim berhasil
- [ ] Upload dokumen Abstrak berhasil
- [ ] Upload dokumen Gambar berhasil
- [ ] Download dokumen yang sudah diupload berhasil
- [ ] Progress bar update setelah upload
- [ ] Replace file yang sudah diupload berhasil
- [ ] Validasi file format (PDF only)
- [ ] Validasi file size (max 10MB)

### 10. Migration Notes

⚠️ **PENTING untuk Deployment:**

1. **Backward Compatibility**: Route lama di `biodata-paten` masih aktif untuk sementara
2. **Data Migration**: Tidak diperlukan migrasi data, semua data existing tetap valid
3. **User Notification**: Informasikan user bahwa lokasi upload berubah
4. **Documentation Update**: Update user manual jika ada

### 11. Rollback Plan

Jika terjadi masalah, rollback dapat dilakukan dengan:

1. Revert file `resources/views/user/submissions-paten/show.blade.php`
2. Revert file `resources/views/user/biodata-paten/show.blade.php`
3. Revert file `routes/web.php`
4. Revert file `app/Http/Controllers/User/SubmissionPatenController.php`

Atau gunakan git:
```bash
git log --oneline -5
git revert <commit-hash>
```

### 12. Future Improvements

1. **Remove old routes** dari `biodata-paten` setelah konfirmasi tidak ada masalah
2. **Remove old methods** dari `BiodataPatenController`
3. **Add notification** untuk reminder upload dokumen paten
4. **Add email notification** setelah dokumen permohonan terbit
5. **Add admin view** untuk monitoring upload dokumen paten

---

## Kesimpulan

Perubahan ini memisahkan fitur upload dokumen paten dari halaman biodata, menempatkannya di lokasi yang lebih logis yaitu di halaman detail pengajuan paten. Progress bar juga diperbarui untuk mencerminkan tahapan yang lebih akurat dengan total 7 tahapan.

**Developer:** GitHub Copilot  
**Date:** February 4, 2026  
**Version:** 1.0
