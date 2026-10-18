# 03. Panduan Menjalankan di Localhost (Laravel Herd)

Panduan praktis untuk menjalankan dan mengoperasikan **SuperPOS Yukelola** di komputer lokal.

---

## 1. Menjalankan Menggunakan Laravel Herd

### A. Pastikan Herd Berjalan
1. Buka aplikasi **Laravel Herd Desktop** di Windows.
2. Pastikan servis PHP (PHP 8.4) dan MySQL (port 3306) dalam status **Running**.

### B. Menghubungkan Domain Lokal (.test)
Buka terminal PowerShell di folder proyek ini (`e:\superpos-yukelola`) dan jalankan:
```powershell
herd link superpos-yukelola
```
Setelah dihubungkan, Anda dapat mengakses sistem langsung melalui browser di:
👉 **`http://superpos-yukelola.test`**

---

## 2. Menjalankan Menggunakan PHP Artisan (Alternatif)
Jika Anda tidak menggunakan virtual host Herd, jalankan server pengembangan bawaan Laravel:
```powershell
php artisan serve
```
Dan buka browser di:
👉 **`http://127.0.0.1:8000`**

---

## 3. Kompilasi Aset Frontend (Vite)
- **Untuk Pengembangan Real-Time (Hot Reload)**:
  ```powershell
  npm run dev
  ```
- **Untuk Build Produksi**:
  ```powershell
  npm run build
  ```

---

## 4. Kredensial Masuk Awal (Default Login)
Setelah inisialisasi database melalui seeder, kredensial berikut siap digunakan:
- **URL Login**: `http://superpos-yukelola.test/login` (atau `http://127.0.0.1:8000/login`)
- **Email**: `admin@superpos.test`
- **Kata Sandi**: `password`
- **Peran**: Super Admin (Akses penuh ke Admin Backoffice & Kasir POS)

---

## 5. Perintah Rutin yang Sering Digunakan
- **Reset Database & Inisialisasi Ulang**:
  ```powershell
  php artisan migrate:fresh --seed --seeder=DevBootstrapSeeder
  ```
- **Membersihkan Cache Aplikasi**:
  ```powershell
  php artisan optimize:clear
  ```
