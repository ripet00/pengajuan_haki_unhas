# Fitur Download Surat Pengalihan Invensi (Transfer of Invention Letter)

## 📋 Deskripsi
Fitur ini memungkinkan user untuk mengunduh dokumen **Surat Pengalihan Invensi** dalam format Word (.docx) yang telah diisi otomatis dengan data inventor dari biodata paten yang telah di-ACC oleh admin.

## 🎯 Tujuan
Menghasilkan dokumen formal surat pengalihan hak invensi yang berisi data lengkap para inventor dengan format yang terstandarisasi menggunakan template Word.

---

## 🔧 Implementasi Teknis

### 1. Template Word Parameter

Template menggunakan **TemplateProcessor** dari PHPWord dengan struktur tabel 4 kolom x 4 baris per inventor:

```
${inventor_no}   Nama        :   ${inventor_name}
                 Pekerjaan   :   ${inventor_pekerjaan}
                 Alamat      :   ${inventor_alamat}
                 
```

#### Parameter yang Digunakan:

| Variable | Sumber Data | Deskripsi |
|----------|-------------|-----------|
| `${inventor_no}` | Auto-increment | Nomor urut inventor (1., 2., 3., dst) |
| `${inventor_name}` | `biodata_paten_inventors.name` | Nama lengkap inventor |
| `${inventor_pekerjaan}` | `biodata_paten_inventors.pekerjaan` | Pekerjaan/jabatan inventor |
| `${inventor_alamat}` | Gabungan beberapa field | Alamat lengkap inventor |

#### Komposisi Alamat Lengkap:
Field `${inventor_alamat}` digabung dari:
- `alamat` (alamat jalan)
- `kelurahan`
- `kecamatan` (dengan prefix "Kec.")
- `kota_kabupaten`
- `provinsi` (dengan prefix "Provinsi")
- `kode_pos`

Format: `Jl. Perintis, Tamalanrea Indah, Kec. Tamalanrea, Kota Makassar, Provinsi Sulawesi Selatan, 90245`

---

### 2. Controller Method

#### File: `app/Http/Controllers/User/BiodataPatenController.php`

```php
public function downloadSuratPengalihan(BiodataPaten $biodataPaten)
```

#### Alur Proses:

1. **Authorization Check**
   - Hanya user pemilik yang bisa download
   - Menggunakan `Auth::id() === $biodataPaten->submissionPaten->user_id`

2. **Status Check**
   - Biodata harus sudah di-ACC (`status === 'approved'`)
   - Jika belum ACC, redirect dengan error message

3. **Load Template**
   - Path: `public/templates/surat_pengalihan_invensi.docx`
   - Error jika template tidak ditemukan

4. **Load Data**
   - Load relationships: `inventors`, `submissionPaten`
   - Set data umum (tanggal, judul paten, dll)

5. **Clone Row untuk Inventors**
   - Menggunakan `$templateProcessor->cloneRow('inventor_no', $inventorCount)`
   - Clone row sebanyak jumlah inventor

6. **Fill Data per Inventor**
   - Loop setiap inventor
   - Set data dengan suffix `#1`, `#2`, dst
   - Set juga tanpa suffix untuk inventor pertama (fallback)

7. **Save & Download**
   - Save ke: `storage/app/public/generated_documents/`
   - Filename: `Surat_Pengalihan_Invensi_{id}_{timestamp}.docx`
   - Auto-delete setelah download

---

### 3. Routing

#### File: `routes/web.php`

```php
Route::get('biodata-paten/{biodataPaten}/download-surat-pengalihan', 
    [BiodataPatenController::class, 'downloadSuratPengalihan'])
    ->name('user.biodata-paten.download-surat-pengalihan');
```

**Middleware**: `auth` (harus login sebagai user)

---

## 📊 Database Schema

### Tabel: `biodata_paten_inventors`

| Field | Type | Nullable | Deskripsi |
|-------|------|----------|-----------|
| id | bigint | No | Primary key |
| biodata_paten_id | bigint | No | Foreign key ke biodata_patens |
| name | varchar | No | Nama inventor |
| pekerjaan | varchar | Yes | Pekerjaan/jabatan |
| alamat | text | Yes | Alamat jalan |
| kelurahan | varchar | Yes | Kelurahan/desa |
| kecamatan | varchar | Yes | Kecamatan |
| kota_kabupaten | varchar | Yes | Kota/kabupaten |
| provinsi | varchar | Yes | Provinsi |
| kode_pos | varchar | Yes | Kode pos |
| nik | varchar | Yes | NIK |
| npwp | varchar | Yes | NPWP |
| email | varchar | Yes | Email |
| nomor_hp | varchar | Yes | Nomor HP |
| is_leader | boolean | No | Flag leader inventor |

---

## 🎨 Cara Menggunakan Template

### A. Template Variable (Single Value)

Untuk data yang tidak berulang:
```
${tanggal_download}
${judul_paten}
${kategori_paten}
```

### B. Clone Row (Multiple Rows)

Untuk data inventor yang berulang, gunakan di dalam **TABEL WORD**:

**Struktur Template:**
```
┌────────────┬───────────────┬───┬─────────────────────┐
│ ${inventor_no} │ Nama      │ : │ ${inventor_name}    │
├────────────┼───────────────┼───┼─────────────────────┤
│            │ Pekerjaan     │ : │ ${inventor_pekerjaan}│
├────────────┼───────────────┼───┼─────────────────────┤
│            │ Alamat        │ : │ ${inventor_alamat}  │
├────────────┼───────────────┼───┼─────────────────────┤
│            │               │   │                     │ (blank row)
└────────────┴───────────────┴───┴─────────────────────┘
```

**Penjelasan:**
- PHPWord akan mendeteksi row pertama sebagai template
- Row akan di-clone sebanyak jumlah inventor
- Data diisi menggunakan suffix `#1`, `#2`, `#3`, dst
- Kolom 1: Nomor inventor
- Kolom 2-4: Label dan value (Nama, Pekerjaan, Alamat)

---

## 🔐 Security & Validation

### 1. Authorization
- ✅ Hanya user pemilik biodata yang bisa download
- ✅ Menggunakan route model binding + manual check

### 2. Status Validation
- ✅ Biodata harus sudah di-ACC (`approved`)
- ✅ Error message jika belum ACC

### 3. File Security
- ✅ File disimpan di `storage/app/public/` (tidak accessible langsung)
- ✅ File otomatis dihapus setelah download
- ✅ Timestamp di filename untuk uniqueness

### 4. Data Sanitization
- ✅ Semua data di-escape oleh PHPWord
- ✅ Fallback ke `-` untuk data kosong
- ✅ Filter alamat kosong sebelum join

---

## 📝 Error Handling

### 1. Template Not Found
```php
if (!file_exists($templatePath)) {
    return back()->with('error', 'Template Surat Pengalihan Invensi tidak ditemukan...');
}
```

### 2. PHPWord Exception
```php
try {
    $templateProcessor->cloneRow('inventor_no', $inventorCount);
} catch (\Exception $e) {
    Log::warning("cloneRow failed: " . $e->getMessage());
}
```

### 3. General Exception
```php
catch (\Exception $e) {
    Log::error('Error generating Surat Pengalihan Invensi...', [...]);
    return back()->with('error', 'Terjadi kesalahan...');
}
```

---

## 📋 Logging

System mencatat aktivitas penting:

### 1. Processing Start
```php
Log::info('Processing Surat Pengalihan Invensi', [
    'biodata_paten_id' => $biodataPaten->id,
    'inventor_count' => $inventorCount,
    'inventors' => $allInventors->pluck('name')->toArray()
]);
```

### 2. Clone Success
```php
Log::info("Cloned row 'inventor_no' for {$inventorCount} inventors");
```

### 3. Generation Success
```php
Log::info('Surat Pengalihan Invensi generated successfully', [
    'biodata_paten_id' => $biodataPaten->id,
    'file_name' => $fileName,
    'output_path' => $outputPath
]);
```

### 4. Error
```php
Log::error('Error generating Surat Pengalihan Invensi: ...', [
    'biodata_paten_id' => $biodataPaten->id,
    'trace' => $e->getTraceAsString()
]);
```

---

## 🧪 Testing Checklist

### Functional Testing
- [ ] Download berhasil dengan 1 inventor
- [ ] Download berhasil dengan multiple inventors (2-5)
- [ ] Alamat lengkap tergabung dengan benar
- [ ] Nomor inventor urut dengan benar (1., 2., 3.)
- [ ] Data kosong di-replace dengan `-`
- [ ] File ter-download dengan nama yang benar
- [ ] File terhapus otomatis setelah download

### Authorization Testing
- [ ] User lain tidak bisa download biodata orang lain (403 error)
- [ ] Guest tidak bisa akses (redirect ke login)

### Status Testing
- [ ] Biodata `pending` tidak bisa download
- [ ] Biodata `denied` tidak bisa download
- [ ] Biodata `approved` bisa download

### Error Testing
- [ ] Template tidak ada: error message muncul
- [ ] Biodata tanpa inventor: fallback ke `-`
- [ ] Exception handling: log error + user-friendly message

---

## 🎯 Contoh Output

### Input Data (3 Inventors):

**Inventor 1:**
- Nama: Dr. Ahmad Hidayat
- Pekerjaan: Dosen Fakultas Teknik Universitas Hasanuddin
- Alamat: Jl. Perintis Kemerdekaan KM 10, Tamalanrea Indah, Kec. Tamalanrea, Kota Makassar, Provinsi Sulawesi Selatan, 90245

**Inventor 2:**
- Nama: Prof. Siti Nurjanah
- Pekerjaan: Dosen Fakultas MIPA Universitas Hasanuddin
- Alamat: Jl. Pendidikan No. 5, Tamalanrea, Kec. Tamalanrea, Kota Makassar, Provinsi Sulawesi Selatan, 90245

**Inventor 3:**
- Nama: Ir. Budi Santoso
- Pekerjaan: Peneliti LIPI
- Alamat: Jl. Raya Bogor KM 46, Cibinong, Kec. Cibinong, Kota Bogor, Provinsi Jawa Barat, 16911

### Output di Word:

```
1.  Nama        : Dr. Ahmad Hidayat
    Pekerjaan   : Dosen Fakultas Teknik Universitas Hasanuddin
    Alamat      : Jl. Perintis Kemerdekaan KM 10, Tamalanrea Indah, Kec. Tamalanrea, 
                  Kota Makassar, Provinsi Sulawesi Selatan, 90245

2.  Nama        : Prof. Siti Nurjanah
    Pekerjaan   : Dosen Fakultas MIPA Universitas Hasanuddin
    Alamat      : Jl. Pendidikan No. 5, Tamalanrea, Kec. Tamalanrea, 
                  Kota Makassar, Provinsi Sulawesi Selatan, 90245

3.  Nama        : Ir. Budi Santoso
    Pekerjaan   : Peneliti LIPI
    Alamat      : Jl. Raya Bogor KM 46, Cibinong, Kec. Cibinong, 
                  Kota Bogor, Provinsi Jawa Barat, 16911
```

---

## 📁 File Structure

```
pengajuan_haki_unhas/
├── app/
│   └── Http/
│       └── Controllers/
│           └── User/
│               └── BiodataPatenController.php  ← Method downloadSuratPengalihan()
├── public/
│   └── templates/
│       └── surat_pengalihan_invensi.docx      ← Template Word
├── storage/
│   └── app/
│       └── public/
│           └── generated_documents/            ← Output folder (auto-created)
├── routes/
│   └── web.php                                 ← Route definition
└── docs/
    └── surat_pengalihan_invensi_feature.md    ← This file
```

---

## 🚀 Deployment Checklist

### Pre-deployment
- [ ] Upload template `surat_pengalihan_invensi.docx` ke `public/templates/`
- [ ] Pastikan folder `storage/app/public/generated_documents/` writable
- [ ] Test dengan data dummy

### Post-deployment
- [ ] Verify template path di production
- [ ] Check storage permissions (775 atau 755)
- [ ] Monitor logs untuk error
- [ ] Test download dari UI user

---

## 🔄 Update & Maintenance

### Jika Template Berubah:
1. Edit file `surat_pengalihan_invensi.docx` di `public/templates/`
2. Pastikan variable name tetap sama: `${inventor_no}`, `${inventor_name}`, dll
3. Test download ulang

### Jika Perlu Field Baru:
1. Tambahkan field di database (migration)
2. Update model `BiodataPatenInventor` (fillable)
3. Update controller: tambah `setValue()` untuk field baru
4. Update template Word: tambah `${new_field}`
5. Update dokumentasi ini

---

## 📞 Support & Troubleshooting

### Common Issues:

**1. Template not found**
- ✅ Check: `public/templates/surat_pengalihan_invensi.docx` exists
- ✅ Check: file permissions readable

**2. Clone row tidak bekerja**
- ✅ Pastikan variable di dalam **tabel Word**
- ✅ Variable harus di row yang sama (1 row template)
- ✅ Tidak ada merge cells di row template

**3. Alamat tidak lengkap**
- ✅ Pastikan data inventor terisi lengkap di database
- ✅ Check: `filter()` akan remove null/empty values

**4. File download corrupt**
- ✅ Pastikan template Word valid (buka dulu di Word)
- ✅ Check: tidak ada file lain yang lock template
- ✅ Check: storage folder writable

---

## 📚 Referensi

- **PHPWord Documentation**: https://phpoffice.github.io/PhpWord/
- **TemplateProcessor**: https://phpoffice.github.io/PhpWord/usage/template.html
- **Laravel File Storage**: https://laravel.com/docs/filesystem
- **Carbon DateTime**: https://carbon.nesbot.com/docs/

---

## ✅ Changelog

| Date | Version | Changes | Author |
|------|---------|---------|--------|
| 2025-12-21 | 1.0.0 | Initial implementation | Development Team |

---

## 📝 Notes

- Template menggunakan **cloneRow()** bukan **cloneBlock()** karena data di dalam tabel
- Suffix `#1`, `#2`, `#3` ditambahkan otomatis oleh PHPWord saat clone row
- File output otomatis dihapus setelah download untuk menghemat storage
- Timezone setting: `Asia/Makassar` (WITA)
- Locale: `id` (Bahasa Indonesia)

---

**END OF DOCUMENTATION**
