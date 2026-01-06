# Panduan Membuat Template Surat Pengalihan Invensi

## 📋 Overview
File ini berisi panduan lengkap untuk membuat template Word `surat_pengalihan_invensi.docx` yang akan digunakan oleh sistem untuk generate dokumen surat pengalihan hak invensi.

---

## 🎯 Struktur Template

### 1. **Header Dokumen**
Silakan buat header dokumen sesuai format surat resmi Universitas Hasanuddin atau instansi terkait.

### 2. **Data Umum Paten**

Gunakan variable berikut untuk data umum:

```
Judul Paten          : ${judul_paten}
Kategori Paten       : ${kategori_paten}
Tempat Invensi       : ${tempat_invensi}
Tanggal Invensi      : ${tanggal_invensi}
Tanggal Pengajuan    : ${tanggal_pengajuan}
Tanggal Download     : ${tanggal_download}
```

### 3. **Uraian Singkat**

```
Uraian Singkat Invensi:
${uraian_singkat}
```

---

## 👥 **BAGIAN PENTING: Data Inventor (Tabel)**

### Struktur Tabel yang Benar

Buat **TABEL** di Word dengan struktur berikut:

#### Layout Tabel: **4 Kolom × 4 Baris per Inventor**

```
┌────────────┬───────────────┬───┬──────────────────────────────────────┐
│ ${inventor_no} │ Nama      │ : │ ${inventor_name}                 │
├────────────┼───────────────┼───┼──────────────────────────────────────┤
│            │ Pekerjaan     │ : │ ${inventor_pekerjaan}            │
├────────────┼───────────────┼───┼──────────────────────────────────────┤
│            │ Alamat        │ : │ ${inventor_alamat}               │
├────────────┼───────────────┼───┼──────────────────────────────────────┤
│            │               │   │                                      │  ← Baris kosong
└────────────┴───────────────┴───┴──────────────────────────────────────┘
```

### Detail Kolom:

| Kolom | Lebar | Isi | Keterangan |
|-------|-------|-----|------------|
| 1 | 2 cm | `${inventor_no}` | Nomor inventor (akan diisi: 1., 2., 3.) |
| 2 | 3 cm | Label (Nama, Pekerjaan, Alamat) | Text statis |
| 3 | 0.5 cm | `:` | Pemisah |
| 4 | 10 cm | `${inventor_name}`, `${inventor_pekerjaan}`, `${inventor_alamat}` | Data dinamis |

### Cara Membuat di Microsoft Word:

1. **Insert Table**
   - Klik: Insert → Table
   - Pilih: 4 columns × 4 rows

2. **Set Lebar Kolom**
   - Kolom 1: 2 cm (untuk nomor)
   - Kolom 2: 3 cm (untuk label)
   - Kolom 3: 0.5 cm (untuk titik dua)
   - Kolom 4: 10 cm (untuk value)

3. **Merge Cells untuk Row 4**
   - Select semua cell di row 4
   - Right click → Merge Cells
   - Ini untuk spacing antar inventor

4. **Isi Template Variable**

   **Row 1 (Nama):**
   - Cell 1: `${inventor_no}`
   - Cell 2: `Nama`
   - Cell 3: `:`
   - Cell 4: `${inventor_name}`

   **Row 2 (Pekerjaan):**
   - Cell 1: (kosong)
   - Cell 2: `Pekerjaan`
   - Cell 3: `:`
   - Cell 4: `${inventor_pekerjaan}`

   **Row 3 (Alamat):**
   - Cell 1: (kosong)
   - Cell 2: `Alamat`
   - Cell 3: `:`
   - Cell 4: `${inventor_alamat}`

   **Row 4 (Blank Row):**
   - Merged cell: (kosong - untuk spacing)

5. **Format Text**
   - Font: Arial atau Times New Roman
   - Size: 11pt atau 12pt
   - Alignment: 
     - Kolom 1: Center (nomor)
     - Kolom 2-4: Left

6. **Border**
   - Option 1: No borders (untuk tampilan clean)
   - Option 2: Light gray borders (untuk clarity)

---

## 🔢 Cara Kerja Clone Row

### Proses Otomatis oleh PHPWord:

1. **Sistem Mendeteksi** row pertama dengan variable `${inventor_no}`
2. **Clone Row** tersebut sebanyak jumlah inventor
3. **Isi Data** dengan suffix numbering:
   - Inventor 1: `${inventor_no#1}`, `${inventor_name#1}`, dst
   - Inventor 2: `${inventor_no#2}`, `${inventor_name#2}`, dst
   - Inventor 3: `${inventor_no#3}`, `${inventor_name#3}`, dst

### Contoh Output (3 Inventor):

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

## 📝 Daftar Lengkap Variable Template

### A. Data Umum (Single Value)

| Variable | Output | Keterangan |
|----------|--------|------------|
| `${tanggal_download}` | 21 Desember 2025 | Tanggal user download |
| `${tanggal_pengajuan}` | 15 Desember 2025 | Tanggal create biodata |
| `${judul_paten}` | Sistem Deteksi Dini... | Judul paten |
| `${kategori_paten}` | Paten Sederhana | Kategori paten |
| `${tempat_invensi}` | Makassar | Tempat invensi |
| `${tanggal_invensi}` | 10 November 2025 | Tanggal invensi |
| `${uraian_singkat}` | Invensi ini adalah... | Uraian singkat |

### B. Data Inventor (Multiple Rows - di dalam Tabel)

**HALAMAN 1 - Data Inventor:**

| Variable | Sumber Database | Output Example |
|----------|-----------------|----------------|
| `${inventor_no}` | Auto-increment | `1.` atau `2.` |
| `${inventor_name}` | `biodata_paten_inventors.name` | Dr. Ahmad Hidayat |
| `${inventor_pekerjaan}` | `biodata_paten_inventors.pekerjaan` | Dosen Fakultas Teknik... |
| `${inventor_alamat}` | Gabungan 6 field | Jl. Perintis..., Kec. Tamalanrea,... |

**HALAMAN 2 - Signature Section:**

| Variable | Sumber Database | Output Example | Keterangan |
|----------|-----------------|----------------|------------|
| `${pejabat_nama}` | `config('hki.pejabat_pengalihan.nama')` | Asmi Citra Malina, S.Pi., M.Agr., Ph.D. | Nama pejabat UNHAS |
| `${pejabat_nip}` | `config('hki.pejabat_pengalihan.nip')` | NIP 197212282006042001 | NIP pejabat UNHAS |
| `${signature_inventor}` | `biodata_paten_inventors.name` | Dr. Ahmad Hidayat | Nama inventor (untuk tanda tangan) |
| `${materai}` | `config('hki.materai.text')` | MATERAI Rp10.000 | **Hanya inventor #1** |

#### Komposisi `${inventor_alamat}`:
```
{alamat}, {kelurahan}, Kec. {kecamatan}, {kota_kabupaten}, Provinsi {provinsi}, {kode_pos}
```

Contoh:
```
Jl. Perintis Kemerdekaan KM 10, Tamalanrea Indah, Kec. Tamalanrea, 
Kota Makassar, Provinsi Sulawesi Selatan, 90245
```

---

## ⚠️ PENTING: Hal yang HARUS Diperhatikan

### ✅ DO (Lakukan):

1. **Gunakan Tabel** untuk data inventor (bukan text biasa)
2. **4 kolom × 4 baris** per template row
3. **Merge row terakhir** untuk spacing
4. **Exact variable name** seperti dokumentasi (case-sensitive!)
5. **Save as .docx** (bukan .doc atau .pdf)
6. **Test template** sebelum upload ke production

### ❌ DON'T (Jangan):

1. ❌ Jangan merge cells di row 1-3 (akan break clone row)
2. ❌ Jangan gunakan nested tables
3. ❌ Jangan typo di variable name (`${inventor_nama}` ❌ salah!)
4. ❌ Jangan pakai spasi di variable (`${ inventor_name }` ❌ salah!)
5. ❌ Jangan save as .doc (harus .docx)
6. ❌ Jangan lupa baris kosong (row 4) untuk spacing

---

## 🧪 Testing Template

### Cara Test Template Sebelum Upload:

1. **Buat dummy data** (3-5 inventor)
2. **Test download** via sistem
3. **Check hasil**:
   - ✅ Semua inventor muncul
   - ✅ Nomor urut benar (1., 2., 3.)
   - ✅ Alamat lengkap dan terformat baik
   - ✅ Spacing antar inventor ada
   - ✅ Tidak ada variable yang belum terisi (`${...}`)

### Common Issues & Solutions:

| Issue | Penyebab | Solusi |
|-------|----------|--------|
| Row tidak ter-clone | Variable di luar tabel | Pindahkan ke dalam tabel |
| Data tumpang tindih | Merge cells di row template | Unmerge row 1-3 |
| Variable tidak terisi | Typo di nama variable | Cek exact spelling |
| Format berantakan | Width kolom tidak pas | Adjust lebar kolom |

---

## 📁 File Location

### Development:
```
public/templates/surat_pengalihan_invensi.docx
```

### Production (Upload ke):
```
/path/to/project/public/templates/surat_pengalihan_invensi.docx
```

### Permissions:
```bash
chmod 644 surat_pengalihan_invensi.docx
```

---

## 🎨 Template Design Tips

### 1. **Header Surat**
```
                    KEMENTERIAN PENDIDIKAN, KEBUDAYAAN, RISET, DAN TEKNOLOGI
                           UNIVERSITAS HASANUDDIN
              DIREKTORAT INOVASI DAN KEKAYAAN INTELEKTUAL
                  Jl. Perintis Kemerdekaan KM 10, Makassar 90245
                     Telp: (0411) 586200 | Email: hki@unhas.ac.id
```

### 2. **Title**
```
                        SURAT PENGALIHAN HAK INVENSI
```

### 3. **Opening**
```
Yang bertanda tangan di bawah ini, para inventor dari invensi "${judul_paten}", 
dengan ini menyatakan:
```

### 4. **Data Invensi**
```
1. Judul Invensi      : ${judul_paten}
2. Kategori           : ${kategori_paten}
3. Tempat Pembuatan   : ${tempat_invensi}
4. Tanggal Pembuatan  : ${tanggal_invensi}
```

### 5. **Uraian Singkat**
```
Uraian Singkat Invensi:
${uraian_singkat}
```

### 6. **Data Inventor (TABEL)**
```
Para Inventor:

[TABEL DI SINI - lihat struktur di atas]
```

### 7. **Pernyataan**
```
Dengan ini menyatakan bahwa kami sepakat untuk mengalihkan seluruh hak 
atas invensi tersebut kepada Universitas Hasanuddin untuk dikelola lebih lanjut 
sesuai dengan peraturan yang berlaku.
```

### 8. **Footer/Tanda Tangan**
```
Makassar, ${tanggal_download}


Mengetahui,                                  Para Inventor,
Direktur Inovasi dan HKI,


_________________________                    (Lihat lampiran)
NIP. 
```

---

## 📚 References

- PHPWord Documentation: https://phpoffice.github.io/PhpWord/
- Template Processor: https://phpoffice.github.io/PhpWord/usage/template.html
- Word Table Formatting: Microsoft Word Help

---

## ✅ Checklist Sebelum Upload

Sebelum upload template ke production, pastikan:

- [ ] File format: .docx (bukan .doc)
- [ ] Semua variable sesuai dokumentasi (exact match)
- [ ] Tabel 4 kolom × 4 baris untuk inventor
- [ ] Row 4 di-merge untuk spacing
- [ ] Tested dengan dummy data (3-5 inventor)
- [ ] Tidak ada variable yang typo
- [ ] Layout rapi dan professional
- [ ] Header/footer sesuai format resmi
- [ ] File size reasonable (< 1 MB)
- [ ] Permissions: 644

---

## 💡 Tips Pro

1. **Gunakan Styles** di Word untuk konsistensi formatting
2. **Set margins** yang cukup (2-3 cm)
3. **Line spacing** 1.5 atau Single sesuai kebutuhan
4. **Font professional**: Times New Roman, Arial, Calibri
5. **Test print** untuk memastikan layout OK
6. **Backup template** sebelum edit
7. **Version control** jika ada perubahan

---

**Good luck membuat template! 🚀**

Jika ada pertanyaan, hubungi development team.
