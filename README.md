# Sistem Pengajuan HKI UNHAS

Aplikasi berbasis web untuk pengelolaan pengajuan Hak Kekayaan Intelektual (HKI) di Universitas Hasanuddin.

## 📋 Deskripsi Project

Sistem ini memungkinkan dosen dan peneliti untuk mengajukan permohonan pendaftaran HKI (Hak Cipta, Paten, Merek, dll) secara online. Admin dapat mengelola submission, melakukan review biodata, dan memproses pengajuan.

---

## 🚀 Quick Start

### Prasyarat
- PHP >= 8.2
- Composer
- MySQL >= 5.7 atau MariaDB >= 10.3
- Node.js & NPM

### Instalasi

1. **Clone repository**
   ```bash
   git clone https://github.com/ripet00/pengajuan_haki_unhas.git
   cd pengajuan_haki_unhas
   ```

2. **Install dependencies**
   ```bash
   composer install
   npm install
   ```

3. **Setup environment**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Konfigurasi database**
   
   Edit file `.env`:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=pengajuan_haki_unhas
   DB_USERNAME=root
   DB_PASSWORD=
   ```

5. **⚠️ PENTING: Import database wilayah**
   
   Sebelum migration, **WAJIB** import data wilayah untuk fitur dropdown:
   ```bash
   mysql -u root -p pengajuan_haki_unhas < database/sql/wilayah.sql
   ```
   
   Atau via phpMyAdmin:
   - Import file: `database/sql/wilayah.sql`
   - Pilih database: `pengajuan_haki_unhas`
   
   📖 **Dokumentasi lengkap**: [database/sql/README.md](database/sql/README.md)

6. **Run migrations**
   ```bash
   php artisan migrate
   ```

7. **Build assets**
   ```bash
   npm run build
   # atau untuk development:
   npm run dev
   ```

8. **Jalankan server**
   ```bash
   php artisan serve
   ```

   Aplikasi akan berjalan di: `http://localhost:8000`

---

## 📚 Fitur Utama

### Untuk User (Dosen/Peneliti)
- ✅ Registrasi dan login
- ✅ Submit pengajuan HKI
- ✅ Input biodata pencipta/inventor dengan validasi lengkap
- ✅ **Dynamic wilayah dropdown** (Provinsi → Kota → Kecamatan → Kelurahan)
- ✅ Support WNA (Warga Negara Asing) dengan input manual
- ✅ Track status pengajuan
- ✅ Edit biodata jika ditolak admin

### Untuk Admin
- ✅ Review submission
- ✅ Review biodata dengan error flagging
- ✅ Approve/reject dengan komentar
- ✅ Dashboard statistik
- ✅ Manajemen user

---

## 🗄️ Database Setup (PENTING!)

### ⚠️ Data Wilayah Indonesia

Aplikasi ini **MEMERLUKAN** data wilayah Indonesia untuk fitur dynamic dropdown pada form biodata.

**Lokasi file**: `database/sql/wilayah.sql`

**Cara import**:
```bash
# Via command line
mysql -u root -p pengajuan_haki_unhas < database/sql/wilayah.sql

# Via Laravel (alternatif - jika ada seeder)
php artisan db:seed --class=WilayahSeeder
```

**Verifikasi**:
```sql
SELECT COUNT(*) FROM wilayah;
-- Harus return ribuan rows
```

📖 **Dokumentasi detail**: 
- [Database SQL Files](database/sql/README.md)
- [Dynamic Wilayah Dropdown](docs/dynamic_wilayah_dropdown.md)

---

## 📖 Dokumentasi Lengkap

Dokumentasi fitur-fitur tersedia di folder `docs/`:

| Dokumen | Deskripsi |
|---------|-----------|
| [dynamic_wilayah_dropdown.md](docs/dynamic_wilayah_dropdown.md) | Fitur cascade dropdown wilayah Indonesia |
| [user_header_visibility_fix.md](docs/user_header_visibility_fix.md) | Fix visibility header pada user dashboard |

---

## 🏗️ Struktur Project

```
pengajuan_haki_unhas/
├── app/
│   ├── Http/Controllers/
│   │   ├── Admin/          # Admin controllers
│   │   ├── User/           # User controllers
│   │   └── Api/            # API controllers (Wilayah, dll)
│   ├── Models/             # Eloquent models
│   └── ...
├── database/
│   ├── migrations/         # Database migrations
│   ├── seeders/           # Database seeders
│   └── sql/               # ⚠️ Additional SQL files (wilayah.sql)
├── docs/                  # 📚 Dokumentasi fitur
├── resources/
│   ├── views/
│   │   ├── admin/         # Admin views
│   │   └── user/          # User views
│   ├── css/
│   └── js/
├── routes/
│   └── web.php            # Routes definition
└── ...
```

---

## 🔧 Teknologi yang Digunakan

- **Framework**: Laravel 11
- **Database**: MySQL / MariaDB
- **Frontend**: 
  - Blade Templates
  - Tailwind CSS
  - Vanilla JavaScript (ES6+)
- **Authentication**: Laravel Breeze
- **Icons**: Font Awesome

---

## 🧪 Testing

```bash
# Run tests
php artisan test

# Run specific test
php artisan test --filter BiodataTest
```

---

## 📝 Development Notes

### Timezone
Aplikasi menggunakan timezone **Asia/Makassar (WITA)**.

Config: `config/app.php`
```php
'timezone' => 'Asia/Makassar',
```

### Validasi Form Biodata
Semua field biodata **WAJIB** diisi:
- NIK: Harus 16 digit angka
- Jenis Kelamin: Pria/Wanita
- Kewarganegaraan:
  - Indonesia → Dropdown wilayah otomatis
  - Asing → Input manual negara + wilayah

### API Endpoints
```
GET /users/api/wilayah/provinces
GET /users/api/wilayah/cities/{provinceCode}
GET /users/api/wilayah/districts/{cityCode}
GET /users/api/wilayah/villages/{districtCode}
```

---

## 🐛 Troubleshooting

### Dropdown wilayah kosong
**Solusi**: Pastikan database wilayah sudah diimport
```bash
mysql -u root -p pengajuan_haki_unhas < database/sql/wilayah.sql
```

### Migration error
**Solusi**: 
1. Drop database dan create ulang
2. Import wilayah.sql
3. Run migration lagi

### Asset tidak muncul
**Solusi**:
```bash
npm run build
php artisan storage:link
```

---

## 👥 Team

- **Developers**: 
  - Denzel Samuel Noah Simatupang
  - Javahirul Rifat Khaidir
- **Institution**: Universitas Hasanuddin
- **Year**: 2025

---

## 📄 License

Project ini menggunakan Laravel framework yang dilisensikan di bawah [MIT license](https://opensource.org/licenses/MIT).

---

## 🔗 Links

- [Laravel Documentation](https://laravel.com/docs)
- [Tailwind CSS](https://tailwindcss.com)

---

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
