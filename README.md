# Sistem Informasi Manajemen Aset dan BHP Laboratorium

Aplikasi ini adalah sistem E-Procurement dan digitalisasi aset laboratorium.

## Prasyarat
- Node.js (v16+)
- PHP (v8.1+) & Composer
- MySQL/MariaDB

## Cara Menjalankan Aplikasi

### 1. Database
- Buat database baru di MySQL dengan nama `capstone2_db`.
- Impor file `capstone2_db.sql` yang ada di root folder ini ke dalam database tersebut.

### 2. Backend (Node.js)
1. Buka terminal di folder root.
2. Jalankan: `npm install`
3. Jalankan server: `npm start`
   (Aplikasi berjalan di port 3000)

### 3. Frontend (Laravel)
1. Buka terminal baru, masuk ke folder frontend: `cd capstone2-frontend`
2. Jalankan: `composer install`
3. Salin env: `cp .env.example .env`
4. Generate key: `php artisan key:generate`
5. Jalankan server: `php artisan serve`
   (Akses di http://127.0.0.1:8000)

## Hak Akses (RBAC)
Sistem ini menggunakan 5 peran pengguna: Administrator, Kepala Laboratorium, Kaprodi, Staf Administrasi, dan Staf Laboratorium. Setiap peran memiliki dashboard dan hak akses yang berbeda sesuai dengan siklus hidup barang.