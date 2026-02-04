# Fitur Auto-Save Draft Biodata

## Deskripsi Fitur
Fitur auto-save draft memungkinkan user untuk menyimpan progress pengisian form biodata secara otomatis dan manual. Draft dapat dimuat kembali saat user membuka halaman form, mencegah kehilangan data jika terjadi refresh atau keluar dari halaman.

## Tanggal Implementasi
4 Februari 2026

## Fitur Utama

### 1. Auto-Save (Otomatis)
- **Trigger**: Setiap kali user mengetik atau mengubah isi form
- **Debouncing**: 2 detik (menunggu 2 detik sejak input terakhir sebelum menyimpan)
- **Mode**: Silent (tidak menampilkan notifikasi)
- **Tujuan**: Menyimpan progress secara background tanpa mengganggu user

### 2. Manual Save (Tombol "Simpan Draft")
- **Trigger**: User mengklik floating button "Simpan Draft"
- **Lokasi**: Floating button di kanan bawah layar
- **Visual Feedback**: 
  - Loading state: "Menyimpan..." dengan spinner icon
  - Success state: "Tersimpan!" dengan check icon + pulse animation
  - Auto-reset ke state default setelah 2 detik
- **Notifikasi**: Menampilkan toast notification "✅ Draft berhasil disimpan!"

### 3. Load Draft (Otomatis)
- **Trigger**: Saat halaman form pertama kali dibuka
- **Proses**: 
  - Memuat data draft dari server
  - Mengisi semua field form dengan data yang tersimpan
  - Restore dropdown cascade wilayah (Provinsi → Kota → Kecamatan → Kelurahan)
  - Restore dropdown fakultas dan kewarganegaraan
  - Membuat jumlah form pencipta/inventor sesuai yang tersimpan
- **Notifikasi**: "✓ Draft berhasil dimuat!" jika ada draft tersimpan

### 4. Delete Draft (Otomatis)
- **Trigger**: Saat user submit form biodata final
- **Tujuan**: Membersihkan draft setelah biodata berhasil di-submit

## Halaman yang Mendukung Fitur

### 1. Biodata Hak Cipta
- **URL**: `/users/submissions/{submission}/biodata/create`
- **Form**: Data Pencipta (Ketua + Anggota)
- **Fields yang disimpan**:
  - Tempat Ciptaan
  - Tanggal Ciptaan
  - Uraian Singkat
  - Jumlah Pencipta (`member_count`)
  - Data setiap pencipta (nama, NIK, NPWP, jenis kelamin, pekerjaan, universitas, fakultas, program studi, alamat, wilayah, kode pos, email, nomor HP, kewarganegaraan)
  - Kode wilayah (untuk restore dropdown cascade)
  - Tipe fakultas (`fakultas_type` untuk dropdown/manual input)

### 2. Biodata Paten
- **URL**: `/users/submissions-paten/{submissionPaten}/biodata-paten/create`
- **Form**: Data Inventor (Ketua + Anggota)
- **Fields yang disimpan**:
  - Jumlah Inventor (`inventor_count`)
  - Data setiap inventor (nama, pekerjaan, universitas, fakultas, alamat, wilayah, kode pos, email, nomor HP, kewarganegaraan)
  - Kode wilayah (untuk restore dropdown cascade)
  - Tipe fakultas (`fakultas_type` untuk dropdown/manual input)

## Implementasi Teknis

### Database Schema

#### Tabel: `draft_biodatas`
```sql
CREATE TABLE draft_biodatas (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    submission_id BIGINT UNSIGNED NOT NULL,
    user_id BIGINT UNSIGNED NOT NULL,
    tempat_ciptaan VARCHAR(255),
    tanggal_ciptaan DATE,
    uraian_singkat TEXT,
    member_count INT DEFAULT 0,
    leader_data JSON,
    members_data JSON,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    FOREIGN KEY (submission_id) REFERENCES submissions(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    UNIQUE KEY (submission_id, user_id)
);
```

#### Tabel: `draft_biodata_patens`
```sql
CREATE TABLE draft_biodata_patens (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    submission_paten_id BIGINT UNSIGNED NOT NULL,
    user_id BIGINT UNSIGNED NOT NULL,
    inventor_count INT DEFAULT 0,
    leader_data JSON,
    inventors_data JSON,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    FOREIGN KEY (submission_paten_id) REFERENCES submission_patens(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    UNIQUE KEY (submission_paten_id, user_id)
);
```

**UNIQUE Constraint**: Memastikan setiap user hanya memiliki 1 draft per submission untuk menghindari duplikasi.

### Backend (Laravel)

#### Models
- **DraftBiodata** (`app/Models/DraftBiodata.php`)
  - Fillable: submission_id, user_id, tempat_ciptaan, tanggal_ciptaan, uraian_singkat, member_count, leader_data, members_data
  - Casts: leader_data & members_data → array, tanggal_ciptaan → date
  - Method: `getOrCreateDraft()`, `clearDraft()`

- **DraftBiodataPaten** (`app/Models/DraftBiodataPaten.php`)
  - Fillable: submission_paten_id, user_id, inventor_count, leader_data, inventors_data
  - Casts: leader_data & inventors_data → array
  - Method: `getOrCreateDraft()`, `clearDraft()`

#### Controllers

**BiodataController** (`app/Http/Controllers/User/BiodataController.php`):
```php
public function saveDraft(Request $request, Submission $submission)
{
    // Validate ownership
    // Get or create draft
    // Update draft data including member_count
    // Return JSON response with timestamp
}

public function loadDraft(Submission $submission)
{
    // Validate ownership
    // Get draft
    // Return JSON with data including member_count
}

public function store() {
    // ... existing logic ...
    // Delete draft after successful submit
    DraftBiodata::clearDraft($submission->id, Auth::id());
}
```

**BiodataPatenController** (`app/Http/Controllers/User/BiodataPatenController.php`):
- Mirror implementation seperti BiodataController
- Menangani `inventor_count` dan `inventors_data`

#### Routes
```php
// Draft Biodata Hak Cipta
Route::post('users/submissions/{submission}/biodata/draft/save', [BiodataController::class, 'saveDraft'])
    ->name('user.biodata.draft.save');
Route::get('users/submissions/{submission}/biodata/draft/load', [BiodataController::class, 'loadDraft'])
    ->name('user.biodata.draft.load');

// Draft Biodata Paten
Route::post('users/submissions-paten/{submissionPaten}/biodata-paten/draft/save', [BiodataPatenController::class, 'saveDraft'])
    ->name('user.biodata-paten.draft.save');
Route::get('users/submissions-paten/{submissionPaten}/biodata-paten/draft/load', [BiodataPatenController::class, 'loadDraft'])
    ->name('user.biodata-paten.draft.load');
```

### Frontend (JavaScript)

#### Fitur Utama

**1. Auto-Save dengan Debouncing**
```javascript
function setupAutoSave() {
    const form = document.getElementById('biodataForm');
    const inputs = form.querySelectorAll('input, textarea, select');
    
    inputs.forEach(input => {
        input.addEventListener('input', function() {
            clearTimeout(saveDraftTimeout);
            saveDraftTimeout = setTimeout(() => {
                saveDraft(false); // Silent save
            }, 2000);
        });
    });
}
```

**2. Save Draft dengan Data Lengkap**
```javascript
function saveDraft(showNotification = true) {
    const data = {
        member_count: memberIndices.size, // Jumlah form yang dibuat
        members: [],
        // ... fields lain
    };
    
    // Simpan data member + metadata (kode wilayah, fakultas_type)
    memberIndices.forEach(index => {
        const member = { /* field data */ };
        
        // Simpan kode wilayah untuk restore dropdown
        member.provinsi_kode = provinsiSelect.selectedOptions[0]?.getAttribute('data-kode');
        member.kota_kabupaten_kode = kotaSelect.selectedOptions[0]?.getAttribute('data-kode');
        member.kecamatan_kode = kecamatanSelect.selectedOptions[0]?.getAttribute('data-kode');
        member.kelurahan_kode = kelurahanSelect.selectedOptions[0]?.getAttribute('data-kode');
        
        // Simpan fakultas_type untuk restore dropdown fakultas
        member.fakultas_type = formData.get(`members[${index}][fakultas_type]`);
        
        data.members.push(member);
    });
    
    // AJAX request ke server
    fetch(saveUrl, { method: 'POST', body: JSON.stringify(data) })
        .then(/* handle response */);
}
```

**3. Load Draft dengan Restore Lengkap**
```javascript
async function loadDraft() {
    const result = await fetch(loadUrl).then(r => r.json());
    
    if (result.success && result.data) {
        const data = result.data;
        
        // 1. Buat form sesuai member_count (bukan members.length)
        for (let i = 0; i < data.member_count; i++) {
            if (i >= memberCount) addMember();
        }
        
        // 2. Fill data
        data.members.forEach((member, index) => {
            // Fill basic fields
            Object.keys(member).forEach(field => {
                if (!field.endsWith('_kode')) {
                    document.querySelector(`[name="members[${index}][${field}]"]`).value = member[field];
                }
            });
            
            // 3. Restore fakultas_type dropdown
            if (member.fakultas_type) {
                const fakultasTypeSelect = document.getElementById(`fakultas_type_${index}`);
                fakultasTypeSelect.value = member.fakultas_type;
                fakultasTypeSelect.dispatchEvent(new Event('change'));
            }
            
            // 4. Restore wilayah dropdowns (cascade)
            if (member.kewarganegaraan === 'Indonesia') {
                await restoreWilayahDropdowns(index, member);
            }
        });
    }
}
```

**4. Restore Wilayah Dropdowns (Cascade)**
```javascript
async function restoreWilayahDropdowns(index, member) {
    // 1. Set provinsi
    provinsiSelect.value = member.provinsi;
    const provinceCode = member.provinsi_kode;
    
    // 2. Selalu load kota jika provinsi terisi (bahkan jika kota belum dipilih)
    if (provinceCode && kotaSelect) {
        const cities = await fetch(`/api/wilayah/cities/${provinceCode}`).then(r => r.json());
        populateSelect(kotaSelect, cities);
        kotaSelect.disabled = false; // Enable dropdown
        
        // 3. Set kota jika ada
        if (member.kota_kabupaten) {
            kotaSelect.value = member.kota_kabupaten;
            const cityCode = member.kota_kabupaten_kode;
            
            // 4. Load kecamatan
            if (cityCode && kecamatanSelect) {
                const districts = await fetch(`/api/wilayah/districts/${cityCode}`).then(r => r.json());
                populateSelect(kecamatanSelect, districts);
                kecamatanSelect.disabled = false;
                
                // 5. Set kecamatan dan load kelurahan (sama seperti di atas)
                // ... dst
            }
        }
    }
}
```

**Keunggulan**: Dropdown berikutnya di-load dan di-enable bahkan jika belum ada data tersimpan, sehingga user tetap bisa klik dan pilih.

#### UI Components

**Floating Button**
```html
<button type="button" id="saveDraftBtn" onclick="saveDraftManually()" 
        class="draft-button bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-full">
    <i id="draftIcon" class="fas fa-save mr-2"></i>
    <span id="draftButtonText">Simpan Draft</span>
</button>

<style>
.draft-button {
    position: fixed;
    bottom: 30px;
    right: 30px;
    z-index: 1000;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
}
@keyframes pulse-success {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.05); }
}
</style>
```

**Toast Notification**
```javascript
function showNotificationMessage(message, type = 'info') {
    const colors = {
        success: 'bg-green-500',
        error: 'bg-red-500',
        info: 'bg-blue-500'
    };
    
    const notification = document.createElement('div');
    notification.className = `fixed top-20 right-4 ${colors[type]} text-white px-6 py-3 rounded-lg shadow-lg z-50 animate-fade-in`;
    notification.innerHTML = `
        <div class="flex items-center">
            <i class="fas fa-check-circle mr-2"></i>
            <span>${message}</span>
        </div>
    `;
    document.body.appendChild(notification);
    
    setTimeout(() => notification.remove(), 3000);
}
```

## Bug Fixes yang Telah Diselesaikan

### Bug 1: Syntax Error JavaScript
**Masalah**: Extra closing brace `}` di akhir fungsi `restoreWilayahDropdowns()`
**Solusi**: Menghapus kurung kurawal tambahan

### Bug 2: Jumlah Pencipta/Inventor Tidak Tersimpan dengan Benar
**Masalah**: 
- User membuat 1 pencipta, setelah refresh muncul 2 pencipta
- Sistem hanya menyimpan `members.length`, bukan jumlah form yang sebenarnya dibuat

**Solusi**:
- Menambahkan kolom `member_count` / `inventor_count` di database
- Menyimpan jumlah form yang dibuat (bukan hanya yang terisi)
- Load draft menggunakan `member_count` untuk membuat jumlah form yang tepat

### Bug 3: Dropdown Wilayah Tidak Bisa Diklik Setelah Reload
**Masalah**:
- User pilih Provinsi → Simpan Draft → Refresh → Dropdown Kota disabled (tidak bisa diklik)
- Dropdown hanya di-load jika semua data cascade tersedia

**Root Cause**:
- Sistem hanya menyimpan nama wilayah, tidak menyimpan kode wilayah
- Tanpa kode, sistem tidak bisa load dropdown cascade berikutnya
- Dropdown hanya di-enable jika data lengkap (provinsi + kota + kecamatan)

**Solusi**:
- Menyimpan kode wilayah: `provinsi_kode`, `kota_kabupaten_kode`, `kecamatan_kode`, `kelurahan_kode`
- Selalu load dan enable dropdown berikutnya jika level sebelumnya terisi
- Contoh: Jika provinsi terisi → load kota dan enable dropdown kota (bahkan jika kota belum dipilih)

### Bug 4: Dropdown Fakultas Tidak Tersimpan
**Masalah**:
- User pilih fakultas dari dropdown → Simpan Draft → Refresh → Dropdown fakultas kembali kosong
- Hanya field `fakultas` (hidden input final) yang tersimpan, tidak menyimpan `fakultas_type` (dropdown selection)

**Solusi**:
- Menyimpan `fakultas_type` ke draft
- Saat load draft, restore `fakultas_type` ke dropdown
- Trigger event `change` untuk mengaktifkan logika UI (show/hide manual input)

## Files Changed

### Migrations
- `database/migrations/2026_02_04_175527_create_draft_biodatas_table.php` (NEW)
- `database/migrations/2026_02_04_175632_create_draft_biodata_patens_table.php` (NEW)
- `database/migrations/2026_02_04_182646_add_count_columns_to_draft_tables.php` (NEW)

### Models
- `app/Models/DraftBiodata.php` (NEW)
- `app/Models/DraftBiodataPaten.php` (NEW)

### Controllers
- `app/Http/Controllers/User/BiodataController.php` (UPDATED - added saveDraft, loadDraft, clearDraft)
- `app/Http/Controllers/User/BiodataPatenController.php` (UPDATED - added saveDraft, loadDraft, clearDraft)

### Routes
- `routes/web.php` (UPDATED - added 4 draft API routes)

### Views
- `resources/views/user/biodata/create.blade.php` (UPDATED - added auto-save, floating button, restore logic)
- `resources/views/user/biodata-paten/create.blade.php` (UPDATED - added auto-save, floating button, restore logic)

## Testing Checklist

### Test Case 1: Auto-Save
- [x] Ketik di field → Tunggu 2 detik → Draft tersimpan otomatis (silent)
- [x] Refresh halaman → Data yang diketik masih ada

### Test Case 2: Manual Save
- [x] Klik "Simpan Draft" → Loading state muncul
- [x] Draft berhasil → Success state dengan pulse animation
- [x] Toast notification "✅ Draft berhasil disimpan!" muncul
- [x] Button reset ke state default setelah 2 detik

### Test Case 3: Jumlah Pencipta/Inventor
- [x] Buat 1 pencipta → Simpan Draft → Refresh → Tetap 1 pencipta
- [x] Buat 3 pencipta, isi semua → Simpan Draft → Refresh → Tetap 3 pencipta
- [x] Buat 5 pencipta, isi 2 saja → Simpan Draft → Refresh → Tetap 5 pencipta (3 kosong)

### Test Case 4: Dropdown Wilayah (WNI)
- [x] Pilih Provinsi → Simpan Draft → Refresh → Provinsi terisi, Kota bisa diklik
- [x] Pilih Provinsi → Kota → Simpan Draft → Refresh → Keduanya terisi, Kecamatan bisa diklik
- [x] Pilih sampai Kelurahan → Simpan Draft → Refresh → Semua terisi dengan benar
- [x] Ganti Provinsi setelah restore → Cascade bekerja normal

### Test Case 5: Dropdown Fakultas
- [x] Pilih "Fakultas Teknik" → Simpan Draft → Refresh → "Fakultas Teknik" tetap terpilih
- [x] Pilih "Isi Sendiri" → Input "Fakultas Baru" → Simpan Draft → Refresh → Tetap tersimpan

### Test Case 6: WNA (Warga Negara Asing)
- [x] Pilih "Warga Negara Asing" → Input manual wilayah → Simpan Draft → Refresh → Data tersimpan

### Test Case 7: Mixed Members
- [x] Pencipta 1: WNI dengan dropdown wilayah → Pencipta 2: WNA dengan input manual
- [x] Simpan Draft → Refresh → Kedua tipe tetap benar

### Test Case 8: Submit Final
- [x] Isi form → Simpan Draft → Submit biodata final → Draft terhapus otomatis

## Performance Considerations

### Database
- **JSON Storage**: Efficient untuk data dynamic (leader_data, members_data)
- **UNIQUE Constraint**: Prevent duplicate drafts (1 draft per user per submission)
- **CASCADE Delete**: Auto-cleanup saat submission/user dihapus
- **Index**: submission_id & user_id untuk query performance

### Frontend
- **Debouncing**: Mencegah request spam (2 detik delay)
- **Async/Await**: Non-blocking cascade API calls
- **Minimal Payload**: Hanya kirim field yang terisi

### Estimated Load
- Auto-save: Max 1 request per 2 detik per user
- Update query: ~5-10ms
- Payload size: ~7-15KB
- 100 concurrent users: ~50 req/s = <10% server capacity

## Kesimpulan

Fitur auto-save draft biodata berhasil diimplementasikan dengan fitur:
- ✅ Auto-save setiap 2 detik (debounced)
- ✅ Manual save dengan visual feedback lengkap
- ✅ Restore data lengkap termasuk dropdown cascade
- ✅ Jumlah pencipta/inventor tersimpan akurat
- ✅ Dropdown wilayah & fakultas berfungsi sempurna
- ✅ Support WNI & WNA
- ✅ Auto-cleanup setelah submit

Semua bug telah diperbaiki dan sistem berjalan dengan stabil.
