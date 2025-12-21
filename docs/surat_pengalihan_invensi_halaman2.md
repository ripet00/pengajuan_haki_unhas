# Template Surat Pengalihan Invensi - Halaman 2 (Signature Section)

## 📋 Overview
Dokumentasi untuk template Word halaman 2 yang berisi section tanda tangan untuk pejabat UNHAS dan para inventor.

---

## 🎯 Struktur Template Halaman 2

### Layout Signature Section:

```
Makassar, ${tanggal_download}

UNTUK DAN ATAS NAMA
Direktorat Inovasi dan KI UNHAS,          INVENTOR,




${pejabat_nama}
${pejabat_nip}



        ${materai}
(${signature_inventor})
```

---

## 📝 Daftar Variable Template

### 1. **Tanggal Download**
```
${tanggal_download}
```
- **Output:** `21 Desember 2025`
- **Sumber:** Tanggal saat user download dokumen
- **Format:** Bahasa Indonesia (d MMMM Y)

---

### 2. **Data Pejabat UNHAS** (Tidak Berulang)

#### `${pejabat_nama}`
- **Output:** `Asmi Citra Malina, S.Pi., M.Agr., Ph.D.`
- **Sumber:** `config('hki.pejabat_pengalihan.nama')`
- **Keterangan:** Nama lengkap pejabat yang menandatangani atas nama UNHAS

#### `${pejabat_nip}`
- **Output:** `NIP 197212282006042001`
- **Sumber:** `config('hki.pejabat_pengalihan.nip')`
- **Keterangan:** Nomor Induk Pegawai pejabat UNHAS

**Update Config di:** `config/hki.php`
```php
'pejabat_pengalihan' => [
    'nama' => 'Asmi Citra Malina, S.Pi., M.Agr., Ph.D.',
    'nip' => 'NIP 197212282006042001',
],
```

---

### 3. **Data Inventor** (Berulang - Clone Row)

#### `${signature_inventor}`
- **Output:** `Dr. Ahmad Hidayat` (nama inventor)
- **Sumber:** `biodata_paten_inventors.name`
- **Keterangan:** Nama inventor untuk ditampilkan di bawah tanda tangan
- **Berulang:** ✅ Ya, sebanyak jumlah inventor

#### `${materai}`
- **Output:** `MATERAI Rp10.000` (hanya inventor #1)
- **Output:** ` ` (kosong untuk inventor #2, #3, dst)
- **Sumber:** `config('hki.materai.text')`
- **Keterangan:** Text materai hanya muncul di inventor pertama
- **Logic:** `if ($num === 1) then show, else empty`

---

## 🔧 Cara Membuat Template di Word

### Step-by-Step:

#### 1. **Setup Header**
```
Makassar, ${tanggal_download}
```

#### 2. **Judul Section**
```
UNTUK DAN ATAS NAMA
Direktorat Inovasi dan KI UNHAS,          INVENTOR,
```

#### 3. **Tanda Tangan Pejabat** (Kolom Kiri)
```
${pejabat_nama}
${pejabat_nip}
```

**Catatan:** Ini hanya 1 kali (tidak berulang)

#### 4. **Tanda Tangan Inventor** (Kolom Kanan - TABEL)

**PENTING:** Gunakan **TABEL 1 kolom** untuk clone row!

```
┌────────────────────────────────┐
│        ${materai}              │  ← Row 1 (Template untuk clone)
│    (${signature_inventor})     │
└────────────────────────────────┘
```

**Struktur Cell:**
- Line 1: `${materai}` (dengan spacing/tab sesuai kebutuhan)
- Line 2: `(${signature_inventor})` (dalam kurung)

---

## 🎨 Contoh Output

### Input: 3 Inventor

**Kolom Kiri (Pejabat):**
```
Asmi Citra Malina, S.Pi., M.Agr., Ph.D.
NIP 197212282006042001
```

**Kolom Kanan (Inventor):**
```
        MATERAI Rp10.000
(Dr. Ahmad Hidayat)


        
(Prof. Siti Nurjanah)


        
(Ir. Budi Santoso)
```

**Penjelasan:**
- Inventor #1: Ada text "MATERAI Rp10.000"
- Inventor #2 & #3: Kosong (hanya spacing)

---

## 🔄 Clone Row Configuration

### PHPWord Code Logic:

```php
// Clone row sebanyak jumlah inventor
$templateProcessor->cloneRow('signature_inventor', $inventorCount);

// Loop setiap inventor
foreach ($allInventors as $index => $inventor) {
    $num = $index + 1;
    
    // Set signature
    $templateProcessor->setValue("signature_inventor#$num", $inventor->name);
    
    // Materai hanya untuk inventor pertama
    if ($num === 1) {
        $templateProcessor->setValue("materai#$num", 'MATERAI Rp10.000');
    } else {
        $templateProcessor->setValue("materai#$num", '');
    }
}
```

---

## ⚙️ Configuration Management

### File: `config/hki.php`

```php
return [
    // Pejabat yang menandatangani
    'pejabat_pengalihan' => [
        'nama' => 'Asmi Citra Malina, S.Pi., M.Agr., Ph.D.',
        'nip' => 'NIP 197212282006042001',
        'jabatan' => 'Direktur Inovasi dan Kekayaan Intelektual',
    ],

    // Text materai
    'materai' => [
        'text' => 'MATERAI Rp10.000',
    ],
];
```

### Cara Update Pejabat:

**Jika ada pergantian pejabat**, cukup edit file config, **TIDAK PERLU** edit template Word!

1. Buka `config/hki.php`
2. Update nama & NIP pejabat baru
3. Save file
4. Clear config cache (jika di production):
   ```bash
   php artisan config:clear
   ```

---

## ⚠️ Important Notes

### 1. **Materai Rules**
- ✅ Hanya inventor **PERTAMA** yang punya text materai
- ✅ Inventor ke-2 dst **KOSONG** (hanya spacing)
- ✅ Text materai bisa diubah di config: `hki.materai.text`

### 2. **Clone Row Location**
- ✅ Variable `${signature_inventor}` harus di dalam **TABEL**
- ✅ Variable `${materai}` harus di **row yang sama** dengan `${signature_inventor}`
- ❌ Jangan gunakan nested table
- ❌ Jangan merge cells di template row

### 3. **Spacing & Layout**
- Gunakan **spacing** atau **tab** untuk mengatur posisi materai
- Gunakan **line breaks** untuk jarak antar inventor
- Test dengan 1-5 inventor untuk memastikan layout OK

---

## 🧪 Testing Checklist

Sebelum upload template ke production:

- [ ] Test dengan 1 inventor (materai muncul)
- [ ] Test dengan 3 inventor (materai hanya di #1)
- [ ] Test dengan 5 inventor (semua signature muncul)
- [ ] Spacing & alignment sudah OK
- [ ] Nama pejabat tampil dengan benar
- [ ] NIP pejabat tampil dengan benar
- [ ] Tanggal download format Indonesia

---

## 📂 File Locations

### Template File:
```
public/templates/surat_pengalihan_invensi.docx
```

### Config File:
```
config/hki.php
```

### Controller:
```
app/Http/Controllers/User/BiodataPatenController.php
Method: downloadSuratPengalihan()
```

---

## 🎯 Summary

**Variables Halaman 2:**
1. `${tanggal_download}` - Tanggal download (single)
2. `${pejabat_nama}` - Nama pejabat UNHAS (single, dari config)
3. `${pejabat_nip}` - NIP pejabat UNHAS (single, dari config)
4. `${signature_inventor}` - Nama inventor (multiple, clone row)
5. `${materai}` - Text materai (multiple, hanya #1 terisi)

**Config Management:**
- Data pejabat di `config/hki.php`
- Easy update tanpa edit template Word
- Flexible untuk pergantian pejabat

**Clone Row:**
- Variable di dalam tabel Word
- PHPWord clone otomatis sebanyak inventor
- Materai hanya inventor pertama

---

**Good luck! 🚀**
