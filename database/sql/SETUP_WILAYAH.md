# Setup Database Wilayah

## ⚠️ LANGKAH WAJIB untuk Developer Baru

Jika Anda baru clone repository ini, **WAJIB** melakukan langkah berikut:

---

## 📥 Step 1: Copy File wilayah.sql

File `wilayah.sql` terlalu besar untuk di-upload ke GitHub, jadi Anda perlu copy manual:

### Lokasi Target:
```
database/sql/wilayah.sql
```

### Sumber File:
Dapatkan file dari salah satu sumber berikut:

1. **Google Drive Tim** (link akan disediakan oleh team leader)
2. **Developer lain** yang sudah punya
3. **Download dari source**: 
   - Website: https://github.com/cahyadsn/wilayah
   - Version: 2025.7

### Cara Copy (Windows):

```powershell
# Jika file sudah ada di lokasi tertentu, misalnya D:\Downloads\
Copy-Item "D:\Downloads\wilayah.sql" -Destination "database\sql\wilayah.sql"
```

### Cara Copy (Linux/Mac):

```bash
# Jika file sudah ada di lokasi tertentu
cp ~/Downloads/wilayah.sql database/sql/wilayah.sql
```

---

## 📊 Step 2: Import ke Database

Setelah file tersedia di `database/sql/wilayah.sql`, import ke database:

### Via Command Line (Recommended):

```bash
# Pastikan database sudah dibuat
mysql -u root -p -e "CREATE DATABASE IF NOT EXISTS pengajuan_haki_unhas"

# Import file wilayah
mysql -u root -p pengajuan_haki_unhas < database/sql/wilayah.sql
```

### Via phpMyAdmin:

1. Buka phpMyAdmin
2. Pilih database `pengajuan_haki_unhas`
3. Tab "Import"
4. Choose file: `database/sql/wilayah.sql`
5. Click "Go"
6. Tunggu ~30 detik - 2 menit

---

## ✅ Step 3: Verifikasi

Jalankan query ini untuk memastikan import berhasil:

```sql
-- Cek total data
SELECT COUNT(*) FROM wilayah;
-- Expected: ribuan rows (>80.000 rows)

-- Cek sample provinsi
SELECT * FROM wilayah WHERE LENGTH(kode) = 2 LIMIT 5;
-- Expected: List provinsi seperti Aceh, Bali, dll

-- Cek sample untuk Sulawesi Selatan
SELECT * FROM wilayah WHERE kode LIKE '73%' LIMIT 10;
-- Expected: Data Sulawesi Selatan
```

Jika semua query di atas mengembalikan data, berarti **SUKSES!** ✅

---

## 🚀 Step 4: Lanjutkan Setup

Setelah database wilayah berhasil diimport, lanjutkan dengan:

```bash
# Run migrations
php artisan migrate

# Build assets
npm run build

# Run server
php artisan serve
```

---

## 🐛 Troubleshooting

### Problem: File tidak ada

**Solusi**: 
- Hubungi team leader untuk mendapatkan file
- Atau download dari: https://github.com/cahyadsn/wilayah

### Problem: Import timeout

**Solusi**:
1. Tingkatkan `max_execution_time` di `php.ini`
2. Atau gunakan command line (lebih cepat dari phpMyAdmin)

### Problem: Out of memory

**Solusi**:
1. Tingkatkan `memory_limit` di `php.ini` menjadi minimal 512M
2. Atau gunakan command line

### Problem: Tabel wilayah sudah ada

**Solusi**:
```sql
DROP TABLE IF EXISTS wilayah;
```
Lalu import ulang.

---

## 📞 Butuh Bantuan?

Jika masih ada masalah, hubungi:
- Team Leader
- Developer senior
- Check dokumentasi lengkap: [docs/dynamic_wilayah_dropdown.md](../docs/dynamic_wilayah_dropdown.md)

---

## ℹ️ Informasi File

- **Nama**: wilayah.sql
- **Ukuran**: ~12 MB
- **Jumlah rows**: ~80.000+ (seluruh Indonesia)
- **Source**: Kemendagri RI via cahyadsn/wilayah
- **Version**: 2025.7
- **Last Update**: November 2025

---

## 🎯 Mengapa File Ini Penting?

File ini berisi data lengkap wilayah Indonesia yang digunakan untuk:
- ✅ Dropdown dinamis Provinsi → Kota → Kecamatan → Kelurahan
- ✅ Form biodata pencipta/inventor
- ✅ Validasi alamat lengkap

**Tanpa file ini**, fitur dropdown wilayah **TIDAK AKAN BERFUNGSI**!
