# Sistem Informasi Manajemen Aset dan BHP Laboratorium (E-Procurement)

Aplikasi Capstone ini dirancang untuk melakukan digitalisasi, pengajuan pengadaan, pemeliharaan, serta pelacakan siklus hidup aset tetap dan Barang Habis Pakai (BHP) di laboratorium. Aplikasi ini menggunakan arsitektur terpisah dengan **Node.js (Express)** sebagai Backend API dan **Laravel** sebagai Frontend Antarmuka.

---

## Prasyarat Sistem
Sebelum menjalankan aplikasi, pastikan perangkat Anda telah terinstal:
- Node.js (Versi 16 atau terbaru)
- PHP (Versi 8.1 atau terbaru)
- Composer
- MySQL Server / XAMPP

---

## Langkah Pengaturan & Instalasi

### 1. Pengaturan Basis Data
1. Buka MySQL Server Anda (melalui XAMPP atau MySQL CLI).
2. Buat sebuah database baru dengan nama sesuai konfigurasi Anda.
3. Impor file skema database **`database_schema.sql`** yang tersedia di root repository ini ke dalam database baru tersebut.

### 2. Pengaturan Backend (Node.js)
1. Buka terminal atau CMD pada folder utama (root) repository ini.
2. Pastikan file `server.js` sudah terkonfigurasi dengan detail koneksi database MySQL Anda (host, user, password, dan nama database).
3. Jalankan perintah berikut untuk menginstal dependensi backend:
   ```bash
   npm install