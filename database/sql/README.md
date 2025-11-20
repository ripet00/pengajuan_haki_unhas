# Database SQL Files

Folder ini berisi file SQL tambahan yang diperlukan untuk fitur-fitur tertentu dalam aplikasi.

---

## 📄 File: `wilayah.sql`

### Deskripsi
File ini berisi data wilayah Indonesia lengkap (Provinsi, Kota/Kabupaten, Kecamatan, Kelurahan/Desa) yang diperlukan untuk **fitur Dynamic Wilayah Dropdown** pada form biodata.

### Ukuran File
~12 MB (berisi ribuan data wilayah seluruh Indonesia)

### Struktur Tabel
```sql
CREATE TABLE `wilayah` (
  `kode` varchar(13) NOT NULL,
  `nama` varchar(255) NOT NULL,
  PRIMARY KEY (`kode`)
);
```

### Kapan Harus Diimport?
**WAJIB diimport** sebelum menggunakan fitur:
- ✅ Form input biodata dengan dropdown wilayah
- ✅ Cascade dropdown Provinsi → Kota → Kecamatan → Kelurahan

### Cara Import

#### 1️⃣ Via Command Line (MySQL)
```bash
# Dari root project
mysql -u root -p pengajuan_haki_unhas < database/sql/wilayah.sql
```

#### 2️⃣ Via phpMyAdmin
1. Buka phpMyAdmin
2. Pilih database `pengajuan_haki_unhas`
3. Klik tab **"Import"**
4. Pilih file: `database/sql/wilayah.sql`
5. Klik **"Go"**
6. Tunggu hingga selesai (~30 detik - 2 menit tergantung server)

#### 3️⃣ Via MySQL Workbench
1. Open Connection ke database
2. File → Run SQL Script
3. Pilih `database/sql/wilayah.sql`
4. Execute

#### 4️⃣ Via Docker (jika menggunakan Docker)
```bash
docker exec -i mysql_container mysql -u root -p pengajuan_haki_unhas < database/sql/wilayah.sql
```

### Verifikasi Import Berhasil

Jalankan query berikut untuk memastikan data sudah masuk:

```sql
-- Cek total data
SELECT COUNT(*) AS total_wilayah FROM wilayah;
-- Expected: ribuan rows

-- Cek data provinsi (2 digit)
SELECT * FROM wilayah WHERE LENGTH(kode) = 2 ORDER BY nama LIMIT 10;
-- Expected: Provinsi seperti Aceh, Bali, DKI Jakarta, dst

-- Cek data kota (5 digit)
SELECT * FROM wilayah WHERE LENGTH(kode) = 5 ORDER BY nama LIMIT 10;
-- Expected: Kota/Kabupaten

-- Cek hierarki Sulawesi Selatan
SELECT * FROM wilayah WHERE kode LIKE '73%' ORDER BY kode;
-- Expected: Data Sulawesi Selatan lengkap
```

### Contoh Data

```
+-------+---------------------------+
| kode  | nama                      |
+-------+---------------------------+
| 73    | Sulawesi Selatan          | (Provinsi)
| 73.71 | Makassar                  | (Kota)
| 73.71.01 | Mariso                 | (Kecamatan)
| 73.71.01.1001 | Mariso            | (Kelurahan)
+-------+---------------------------+
```

---

## ⚠️ PENTING

### Jangan Import Ulang Jika:
- ❌ Tabel `wilayah` sudah ada dan terisi
- ❌ Aplikasi sudah jalan normal dengan dropdown wilayah

### Harus Import Ulang Jika:
- ✅ Fresh installation / setup baru
- ✅ Clone repository pertama kali
- ✅ Pindah ke server baru
- ✅ Database di-reset/drop

### Data Update
Data wilayah ini diambil dari:
- **Source**: Kementerian Dalam Negeri RI
- **Version**: 2025.7
- **Last Update**: November 2025

---

## 🔗 Referensi

Untuk dokumentasi lengkap tentang penggunaan fitur ini, lihat:
- [Dynamic Wilayah Dropdown Documentation](../docs/dynamic_wilayah_dropdown.md)

---

## 📝 Notes

- File ini **TIDAK AKAN** ter-track di Git jika terlalu besar (lihat `.gitignore`)
- Jika file tidak ada, download dari:
  - Repository release
  - Google Drive tim development
  - Minta ke team leader

---

## 🐛 Troubleshooting

**Problem**: Import timeout / gagal
- **Solusi**: Tingkatkan `max_execution_time` di `php.ini`
- **Atau**: Import via command line (lebih cepat)

**Problem**: Out of memory saat import
- **Solusi**: Tingkatkan `memory_limit` di `php.ini` minimal 512M
- **Atau**: Import via command line

**Problem**: Tabel sudah ada
- **Solusi**: Drop table dulu:
  ```sql
  DROP TABLE IF EXISTS wilayah;
  ```
  Kemudian import ulang.

---

## 📞 Contact

Jika ada masalah dengan import file ini, hubungi team development.
