# Sistem Informasi Akademik (SIA)

Aplikasi web untuk mengelola data akademik dan keuangan universitas secara aman, dibangun dengan Laravel 13 + Svelte + Inertia.js.

---

## Tech Stack

| Layer    | Teknologi                 |
| -------- | ------------------------- |
| Backend  | Laravel 13                |
| Frontend | Svelte 5 + Inertia.js     |
| Database | MySQL 8+                  |
| Auth     | Laravel Fortify           |
| ORM      | Eloquent                  |
| Hashing  | bcrypt (via Laravel Hash) |
| Logging  | Audit Log + Monolog       |
| Backup   | mysqldump via Artisan     |

---

## Fitur Utama

### Administrator

- CRUD Mahasiswa (dengan pembuatan akun otomatis)
- CRUD Dosen (dengan pembuatan akun otomatis)
- CRUD Mata Kuliah
- Manajemen Tagihan Keuangan
- Manajemen User & Role
- Melihat Audit Log seluruh aktivitas sistem

### Dosen

- Input dan update nilai mahasiswa
- Finalisasi nilai (nilai terkunci setelah difinalisasi)

### Mahasiswa

- Pengambilan dan pembatalan KRS (dengan validasi maksimum SKS)
- Melihat rekap nilai beserta IPK otomatis
- Melihat riwayat tagihan keuangan

---

## Keamanan Database

| Mekanisme                 | Implementasi                                                                            |
| ------------------------- | --------------------------------------------------------------------------------------- |
| Password hashing          | `Hash::make()` bcrypt                                                                   |
| SQL Injection protection  | Eloquent Query Builder + Prepared Statement                                             |
| CSRF protection           | Laravel default middleware                                                              |
| Role-based access         | Middleware `EnsureRole`, Policy per resource                                            |
| Audit trail (aplikasi)    | `AuditLogService` pada setiap CRUD                                                      |
| Audit trail (database)    | Trigger MySQL pada tabel `nilai` dan `keuangan`                                         |
| Validasi finalisasi nilai | Stored Procedure `sp_input_nilai`                                                       |
| Pembatasan tampilan data  | View MySQL (`view_nilai_mahasiswa`, `view_keuangan_mahasiswa`, `view_mahasiswa_profil`) |
| Backup otomatis           | `php artisan db:backup` terjadwal setiap pukul 02:00                                    |

---

## Instalasi

### Prasyarat

- PHP 8.3+
- MySQL 8.0+
- Node.js 20+
- Composer

### Langkah Setup

**1. Clone dan install dependensi**

```bash
git clone <repo-url>
cd siakad
composer install
npm install
```

**2. Konfigurasi environment**

```bash
cp .env.example .env
php artisan key:generate
```

Edit `.env` dan sesuaikan konfigurasi database:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=siakad
DB_USERNAME=root
DB_PASSWORD=your_password
```

**3. Buat database**

```sql
CREATE DATABASE siakad CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

**4. Jalankan migrasi dan seeder**

```bash
php artisan migrate
php artisan db:seed
```

**5. Build frontend dan jalankan server**

```bash
npm run build
php artisan serve
```

Untuk mode development dengan hot reload:

```bash
npm run dev
# terminal lain:
php artisan serve
```

---

## Akun Default (Setelah Seeding)

| Role          | Email                     | Password   |
| ------------- | ------------------------- | ---------- |
| Administrator | `admin@siakad.ac.id`      | `password` |
| Dosen         | `dosen1@siakad.ac.id`     | `password` |
| Mahasiswa     | `mahasiswa1@siakad.ac.id` | `password` |

> Tersedia 10 akun dosen (`dosen1` s/d `dosen10`) dan 20 akun mahasiswa (`mahasiswa1` s/d `mahasiswa20`).

---

## Backup Database

Backup manual:

```bash
php artisan db:backup
```

File backup tersimpan di `storage/app/backups/`. Backup otomatis berjalan setiap hari pukul 02:00 via Laravel Scheduler.

Untuk mengaktifkan scheduler, tambahkan cron job berikut:

```
* * * * * cd /path/to/siakad && php artisan schedule:run >> /dev/null 2>&1
```

---

## Struktur Database

```
roles
└── users
    ├── mahasiswa
    │   ├── krs ──── mata_kuliah
    │   ├── nilai ── mata_kuliah, dosen
    │   └── keuangan
    └── dosen
        └── nilai

audit_logs (mencatat semua aktivitas)
```

### Database Objects

| Objek            | Nama                        | Fungsi                                     |
| ---------------- | --------------------------- | ------------------------------------------ |
| View             | `view_mahasiswa_profil`     | Data profil mahasiswa                      |
| View             | `view_nilai_mahasiswa`      | Nilai dengan detail MK dan dosen           |
| View             | `view_keuangan_mahasiswa`   | Tagihan dengan nama mahasiswa              |
| Stored Procedure | `sp_input_nilai`            | Input nilai dengan validasi finalisasi     |
| Trigger          | `trg_audit_nilai_update`    | Audit otomatis perubahan nilai             |
| Trigger          | `trg_audit_keuangan_update` | Audit otomatis perubahan status pembayaran |

---

## Roles & Hak Akses

| Fitur            | Administrator | Dosen | Mahasiswa |
| ---------------- | :-----------: | :---: | :-------: |
| CRUD Mahasiswa   |      ✅       |  ❌   |    ❌     |
| CRUD Dosen       |      ✅       |  ❌   |    ❌     |
| CRUD Mata Kuliah |      ✅       |  ❌   |    ❌     |
| Kelola Keuangan  |      ✅       |  ❌   |    ❌     |
| Kelola User      |      ✅       |  ❌   |    ❌     |
| Audit Log        |      ✅       |  ❌   |    ❌     |
| Input Nilai      |      ❌       |  ✅   |    ❌     |
| Finalisasi Nilai |      ❌       |  ✅   |    ❌     |
| Lihat KRS        |      ❌       |  ❌   |    ✅     |
| Ambil/Batal KRS  |      ❌       |  ❌   |    ✅     |
| Lihat Nilai      |      ❌       |  ❌   |    ✅     |
| Lihat Keuangan   |      ❌       |  ❌   |    ✅     |

---

## Lisensi

MIT
