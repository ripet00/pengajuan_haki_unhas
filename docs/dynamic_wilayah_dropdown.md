# Fitur Dynamic Wilayah Dropdown

## 📋 Deskripsi Fitur

Fitur ini memungkinkan user untuk memilih wilayah (Provinsi, Kota/Kabupaten, Kecamatan, Kelurahan) secara dinamis menggunakan dropdown yang saling terhubung (cascade dropdown) pada form input biodata.

### Fitur Utama:
- ✅ **Dynamic Dropdown Cascade**: Dropdown wilayah yang saling terhubung secara hierarki
- ✅ **Conditional Display**: Dropdown wilayah hanya muncul jika kewarganegaraan = "Indonesia"
- ✅ **Manual Input untuk WNA**: Text input manual untuk Warga Negara Asing
- ✅ **Custom Country Input**: WNA dapat menginput negara asalnya sendiri (contoh: Malaysia, Jerman, dll)
- ✅ **AJAX Loading**: Data wilayah dimuat secara asynchronous dari database

---

## 🗄️ Database Setup (WAJIB)

### ⚠️ PENTING: Import Database Wilayah

Sebelum menggunakan fitur ini, Anda **WAJIB** mengimport data wilayah ke database terlebih dahulu.

### Langkah-langkah:

#### 1. Lokasi File SQL
File `wilayah.sql` tersedia di:
```
database/sql/wilayah.sql
```

#### 2. Import Database

**Via Command Line (MySQL):**
```bash
mysql -u root -p pengajuan_haki_unhas < database/sql/wilayah.sql
```

**Via phpMyAdmin:**
1. Buka phpMyAdmin
2. Pilih database `pengajuan_haki_unhas`
3. Klik tab "Import"
4. Pilih file `database/sql/wilayah.sql`
5. Klik "Go"

**Via Laravel Artisan (alternatif):**
```bash
# Import menggunakan DB command
php artisan db:seed --class=WilayahSeeder
```

#### 3. Verifikasi Import

Setelah import, cek apakah tabel `wilayah` sudah terisi:

```sql
SELECT COUNT(*) FROM wilayah;
-- Harus mengembalikan ribuan rows (data provinsi, kota, kecamatan, kelurahan)

SELECT * FROM wilayah WHERE LENGTH(kode) = 2 LIMIT 5;
-- Harus menampilkan data provinsi
```

---

## 📊 Struktur Database

### Tabel: `wilayah`

| Kolom | Tipe | Deskripsi |
|-------|------|-----------|
| `kode` | VARCHAR(13) PRIMARY | Kode wilayah unik |
| `nama` | VARCHAR(255) | Nama wilayah |

### Hierarki Kode:
- **Provinsi**: 2 digit (contoh: `"11"` = Aceh)
- **Kota/Kabupaten**: 5 digit (contoh: `"11.01"` = Kab. Aceh Selatan)
- **Kecamatan**: 8 digit (contoh: `"11.01.01"` = Kec. Bakongan)
- **Kelurahan/Desa**: 13 digit (contoh: `"11.01.01.2001"` = Desa Keude Bakongan)

---

## 🏗️ Arsitektur Sistem

### 1. Model: `Wilayah.php`

**Lokasi:** `app/Models/Wilayah.php`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wilayah extends Model
{
    protected $table = 'wilayah';
    public $timestamps = false;
    protected $primaryKey = 'kode';
    public $incrementing = false;
    protected $keyType = 'string';
    
    // Methods untuk query wilayah berdasarkan hierarki
    public static function getProvinces() { ... }
    public static function getCitiesByProvince($provinceCode) { ... }
    public static function getDistrictsByCity($cityCode) { ... }
    public static function getVillagesByDistrict($districtCode) { ... }
}
```

### 2. API Controller: `WilayahController.php`

**Lokasi:** `app/Http/Controllers/Api/WilayahController.php`

Endpoints yang tersedia:

| Method | Endpoint | Deskripsi |
|--------|----------|-----------|
| GET | `/users/api/wilayah/provinces` | Ambil semua provinsi |
| GET | `/users/api/wilayah/cities/{provinceCode}` | Ambil kota berdasarkan provinsi |
| GET | `/users/api/wilayah/districts/{cityCode}` | Ambil kecamatan berdasarkan kota |
| GET | `/users/api/wilayah/villages/{districtCode}` | Ambil kelurahan berdasarkan kecamatan |

### 3. Routes

**Lokasi:** `routes/web.php`

```php
// API routes for wilayah dropdown
Route::prefix('users/api/wilayah')->name('api.wilayah.')->group(function () {
    Route::get('/provinces', [WilayahController::class, 'getProvinces'])->name('provinces');
    Route::get('/cities/{provinceCode}', [WilayahController::class, 'getCities'])->name('cities');
    Route::get('/districts/{cityCode}', [WilayahController::class, 'getDistricts'])->name('districts');
    Route::get('/villages/{districtCode}', [WilayahController::class, 'getVillages'])->name('villages');
});
```

---

## 💻 Frontend Implementation

### 1. Form Structure

**Lokasi:** `resources/views/user/biodata/create.blade.php`

#### Kewarganegaraan Field:
```html
<!-- Dropdown untuk memilih tipe kewarganegaraan -->
<select name="members[0][kewarganegaraan_type]" id="kewarganegaraan_type_0">
    <option value="Indonesia">Indonesia</option>
    <option value="Warga Negara Asing">Warga Negara Asing</option>
</select>

<!-- Text input untuk WNA (conditional) -->
<div id="kewarganegaraan_asing_div_0" style="display: none;">
    <input type="text" name="members[0][kewarganegaraan_asing]" 
           placeholder="Contoh: Malaysia, Singapura">
</div>

<!-- Hidden input untuk nilai final -->
<input type="hidden" name="members[0][kewarganegaraan]" 
       id="kewarganegaraan_final_0" value="Indonesia">
```

#### Wilayah Dropdowns (untuk WNI):
```html
<div id="wilayah_container_0" style="display: contents;">
    <!-- Provinsi -->
    <select name="members[0][provinsi]" id="provinsi_0" class="provinsi-select">
        <option value="">Pilih Provinsi</option>
    </select>
    
    <!-- Kota/Kabupaten -->
    <select name="members[0][kota_kabupaten]" id="kota_kabupaten_0">
        <option value="">Pilih Kota/Kabupaten</option>
    </select>
    
    <!-- Kecamatan -->
    <select name="members[0][kecamatan]" id="kecamatan_0">
        <option value="">Pilih Kecamatan</option>
    </select>
    
    <!-- Kelurahan -->
    <select name="members[0][kelurahan]" id="kelurahan_0">
        <option value="">Pilih Kelurahan</option>
    </select>
</div>
```

#### Manual Input (untuk WNA):
```html
<div id="non_wilayah_container_0" style="display: none;">
    <input type="text" name="members[0][provinsi_manual]" placeholder="Provinsi/State">
    <input type="text" name="members[0][kota_manual]" placeholder="Kota">
    <input type="text" name="members[0][kecamatan_manual]" placeholder="Kecamatan/District">
    <input type="text" name="members[0][kelurahan_manual]" placeholder="Kelurahan/Village">
</div>
```

### 2. JavaScript Functions

#### Load Provinces (on page load):
```javascript
function loadProvinces() {
    fetch('/users/api/wilayah/provinces')
        .then(response => response.json())
        .then(data => {
            window.provincesData = data;
            document.querySelectorAll('.provinsi-select').forEach(select => {
                populateSelect(select, data, 'Pilih Provinsi');
            });
        });
}
```

#### Cascade Dropdown Logic:
```javascript
// Provinsi change → Load Cities
provinsiSelect.addEventListener('change', function() {
    const provinceCode = this.options[this.selectedIndex].getAttribute('data-kode');
    fetch(`/users/api/wilayah/cities/${provinceCode}`)
        .then(response => response.json())
        .then(data => populateSelect(kotaSelect, data, 'Pilih Kota/Kabupaten'));
});

// Similar untuk Kota → Kecamatan dan Kecamatan → Kelurahan
```

#### Kewarganegaraan Change Handler:
```javascript
kewarganegaraanTypeSelect.addEventListener('change', function() {
    if (this.value === 'Indonesia') {
        // Show wilayah dropdowns, hide manual inputs
        wilayahContainer.style.display = 'contents';
        nonWilayahContainer.style.display = 'none';
        kewarganegaraanAsingDiv.style.display = 'none';
        kewarganegaraanFinal.value = 'Indonesia';
    } else {
        // Hide wilayah dropdowns, show manual inputs
        wilayahContainer.style.display = 'none';
        nonWilayahContainer.style.display = 'contents';
        kewarganegaraanAsingDiv.style.display = 'block';
    }
});
```

---

## 🔄 Data Flow

### Untuk WNI (Warga Negara Indonesia):

```
User pilih "Indonesia" 
    ↓
Wilayah dropdown muncul
    ↓
User pilih: Provinsi → Kota → Kecamatan → Kelurahan
    ↓
Data tersimpan di database:
- kewarganegaraan: "Indonesia"
- provinsi: "Sulawesi Selatan"
- kota_kabupaten: "Makassar"
- kecamatan: "Panakkukang"
- kelurahan: "Karuwisi"
```

### Untuk WNA (Warga Negara Asing):

```
User pilih "Warga Negara Asing"
    ↓
Text input negara muncul + Manual input wilayah
    ↓
User ketik: "Malaysia" + alamat lengkap manual
    ↓
Data tersimpan di database:
- kewarganegaraan: "Malaysia"
- provinsi: "Selangor" (input manual)
- kota_kabupaten: "Kuala Lumpur" (input manual)
- kecamatan: "Cheras" (input manual)
- kelurahan: "Taman Connaught" (input manual)
```

---

## 🧪 Testing

### Test Case 1: WNI dengan Dropdown
1. Buka form biodata
2. Pilih kewarganegaraan: "Indonesia"
3. Pastikan dropdown wilayah muncul
4. Pilih Provinsi → Kota akan terisi otomatis
5. Pilih Kota → Kecamatan akan terisi otomatis
6. Pilih Kecamatan → Kelurahan akan terisi otomatis
7. Submit form
8. Cek database: nilai harus tersimpan dengan benar

### Test Case 2: WNA dengan Manual Input
1. Buka form biodata
2. Pilih kewarganegaraan: "Warga Negara Asing"
3. Pastikan dropdown wilayah hilang, muncul text input
4. Ketik negara: "Jerman"
5. Isi alamat manual: Provinsi, Kota, Kecamatan, Kelurahan
6. Submit form
7. Cek database: kewarganegaraan = "Jerman", wilayah dari manual input

### Test Case 3: Multiple Members
1. Tambah 2-3 member
2. Member 1: WNI dengan dropdown
3. Member 2: WNA (Malaysia) dengan manual input
4. Submit form
5. Pastikan semua member tersimpan dengan benar

---

## 🐛 Troubleshooting

### Problem: Dropdown kosong / tidak ada data

**Solusi:**
1. Cek apakah database wilayah sudah diimport:
   ```sql
   SELECT COUNT(*) FROM wilayah;
   ```
2. Cek console browser untuk error AJAX
3. Pastikan routes API sudah terdaftar:
   ```bash
   php artisan route:list | grep wilayah
   ```

### Problem: Cascade dropdown tidak jalan

**Solusi:**
1. Buka Developer Console (F12)
2. Cek Network tab saat memilih dropdown
3. Pastikan response API mengembalikan JSON yang benar
4. Cek apakah `data-kode` attribute tersimpan di option

### Problem: Form validation error saat submit

**Solusi:**
1. Pastikan JavaScript handler mengisi hidden input dengan benar
2. Cek nilai `kewarganegaraan_final_0` sebelum submit
3. Untuk WNA, pastikan manual input terisi semua
4. Cek console untuk validation errors

---

## 📝 Backend Validation

**Lokasi:** `app/Http/Controllers/User/BiodataController.php`

```php
$validatedData = $request->validate([
    'members.*.kewarganegaraan' => 'required|string|max:100',
    'members.*.provinsi' => 'required|string|max:255',
    'members.*.kota_kabupaten' => 'required|string|max:255',
    'members.*.kecamatan' => 'required|string|max:255',
    'members.*.kelurahan' => 'required|string|max:255',
    
    // Helper fields (tidak disimpan ke DB)
    'members.*.kewarganegaraan_type' => 'nullable|string',
    'members.*.kewarganegaraan_asing' => 'nullable|string|max:100',
]);
```

---

## 📦 Dependencies

### PHP Packages:
- Laravel 11.x
- Eloquent ORM

### JavaScript:
- Vanilla JavaScript (ES6+)
- Fetch API
- Native DOM manipulation

### Database:
- MySQL 5.7+ / MariaDB 10.3+

---

## 🔐 Security Considerations

1. ✅ **SQL Injection Prevention**: Menggunakan Eloquent ORM
2. ✅ **XSS Protection**: Blade templating engine auto-escape
3. ✅ **CSRF Protection**: Laravel CSRF token
4. ✅ **Input Validation**: Backend validation untuk semua input
5. ✅ **API Rate Limiting**: Bisa ditambahkan jika diperlukan

---

## 🚀 Future Enhancements

Fitur yang bisa ditambahkan di masa depan:

1. **Caching**: Cache data provinsi untuk mengurangi query database
2. **Search/Autocomplete**: Tambah fitur search di dropdown untuk wilayah besar
3. **Lazy Loading**: Load data hanya saat dropdown dibuka
4. **Offline Support**: Simpan data wilayah di localStorage
5. **API Rate Limiting**: Batasi jumlah request per user

---

## 👥 Contributors

- **Developer**: Denzel
- **Date**: November 2025
- **Version**: 1.0.0

---

## 📞 Support

Jika ada pertanyaan atau issue terkait fitur ini:
1. Check dokumentasi ini terlebih dahulu
2. Cek troubleshooting section
3. Contact development team

---

## ⚠️ Breaking Changes

Jika melakukan perubahan pada fitur ini, pastikan:

1. ✅ Database wilayah tetap terimport
2. ✅ API routes tidak berubah
3. ✅ Field names di form tetap konsisten
4. ✅ Update dokumentasi ini jika ada perubahan

---

## 📜 License

Sesuai dengan license project Laravel.
