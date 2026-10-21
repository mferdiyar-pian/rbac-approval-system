# RBAC Approval System

Aplikasi web untuk pengelolaan dan persetujuan transaksi dengan menerapkan **Role-Based Access Control (RBAC)** dan **Audit Trail**. Sistem ini dikembangkan menggunakan Laravel dan PostgreSQL untuk mendukung proses transaksi yang terstruktur, pembatasan hak akses, serta pencatatan aktivitas pengguna.

## Fitur Utama

- **Autentikasi Pengguna** — Login, logout, dan pengelolaan profil.
- **Role-Based Access Control (RBAC)** — Pengaturan hak akses berdasarkan role pengguna.
- **Manajemen Transaksi** — Pembuatan, pengajuan, pengeditan, dan penghapusan transaksi sesuai kewenangan.
- **Workflow Persetujuan** — Alur transaksi melalui Inputer, Checker, dan Approver.
- **Manajemen Pengguna** — Pengelolaan akun dan penetapan role oleh Superadmin atau Admin sesuai hak akses.
- **Audit Trail** — Pencatatan aktivitas penting, termasuk pembuatan, pemeriksaan, persetujuan, penolakan, dan perubahan data transaksi.

## Role Pengguna

- **Superadmin** — Pengelolaan sistem sesuai permission yang diberikan.
- **Admin** — Pengelolaan pengguna dan transaksi sesuai kewenangan.
- **User** — Role pengguna umum dengan akses sesuai permission.
- **Inputer** — Membuat dan mengajukan transaksi.
- **Checker** — Memeriksa atau menolak transaksi beserta alasannya.
- **Approver** — Memberikan persetujuan akhir atau menolak transaksi.

Hak akses setiap role ditentukan melalui konfigurasi permission pada aplikasi.

## Alur Persetujuan Transaksi

1. Inputer membuat draft transaksi.
2. Inputer mengajukan transaksi kepada Checker.
3. Checker memeriksa transaksi dan meloloskan atau menolaknya.
4. Jika lolos pemeriksaan, transaksi diteruskan kepada Approver.
5. Approver menyetujui atau menolak transaksi.
6. Aktivitas penting dicatat dalam Audit Trail.

## Konsep Segregation of Duties (SoD)

Sistem ini dirancang dengan mematuhi prinsip **Segregation of Duties (SoD)** / Pemisahan Kewenangan dalam arsitektur enterprise:
- **Dual Control / Four-Eyes Principle:** Pemisahan tegas antara pembuat (Maker/Inputer), pemeriksa (Checker), dan pemutus akhir (Approver).
- **Pencegahan Konflik Kepentingan:** Pembuat transaksi dilarang mengesahkan transaksinya sendiri (*no self-approval*).
- **Pemisahan Administratif & Operasional:** Admin/Superadmin mengelola akun dan konfigurasi sistem tanpa merangkap sebagai approver transaksi operasional harian.

Dokumentasi lengkap mengenai matriks peran dan mitigasi risiko dapat dibaca pada [Dokumentasi Segregation of Duties](docs/SEGREGATION_OF_DUTIES.md).

## Teknologi yang Digunakan

- **Backend:** Laravel, PHP
- **Database:** PostgreSQL
- **Autentikasi:** Laravel Breeze
- **Manajemen Hak Akses:** Spatie Laravel Permission
- **Frontend:** Blade, Tailwind CSS, Alpine.js
- **Build Frontend:** Vite

## Persyaratan Sistem

Pastikan perangkat sudah memiliki:

- PHP sesuai persyaratan versi Laravel dan dependensi project.
- Composer.
- PostgreSQL.
- Node.js dan npm.
- Git.

## Instalasi

### 1. Clone Repository

```bash
git clone https://github.com/mferdiyar-pian/rbac-approval-system.git
cd rbac-approval-system
```

### 2. Instal Dependensi

```bash
composer install
npm install
```

### 3. Konfigurasi Environment

Salin file `.env.example` menjadi `.env`.

Pada Windows Command Prompt:

```cmd
copy .env.example .env
```

Konfigurasikan nama database, username, dan password PostgreSQL pada file `.env`. Buat database terlebih dahulu melalui PostgreSQL.

### 4. Generate Application Key

```bash
php artisan key:generate
```

### 5. Migrasi dan Seeder

```bash
php artisan migrate --seed
```

Periksa konfigurasi seeder terlebih dahulu dan gunakan hanya untuk lingkungan pengembangan. Sesuaikan data akun serta permission dengan kebutuhan lokal.

### 6. Build Frontend

```bash
npm run build
```

### 7. Jalankan Aplikasi

```bash
php artisan serve
```

Buka alamat lokal yang ditampilkan oleh Laravel di terminal.

## Keamanan

- Jangan mengunggah file `.env` atau kredensial rahasia ke repository.
- Gunakan konfigurasi database dan password sendiri pada lingkungan lokal.
- Pastikan permission dan otorisasi diuji sebelum aplikasi digunakan di lingkungan produksi.

## Status Pengembangan

Project ini dikembangkan sebagai aplikasi pengelolaan transaksi dan persetujuan berbasis RBAC dengan pencatatan aktivitas melalui Audit Trail.