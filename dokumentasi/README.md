# Dokumentasi Proyek SuperPOS Yukelola

Selamat datang di dokumentasi resmi adaptasi dan pengembangan **SuperPOS Yukelola**.
Proyek ini merupakan sistem Point of Sale (POS), Manajemen Inventaris, ERP, dan Akuntansi modern berbasis **Laravel 13** dan **PHP 8.4** yang diadaptasi dari basis kode Hyper POS v1.1.4 untuk penggunaan lingkungan lokal (**localhost** dengan **Laravel Herd**) dan disesuaikan ke Bahasa Indonesia.

---

## Daftar Isi Dokumentasi

1. [01. Arsitektur & Database](file:///e:/superpos-yukelola/dokumentasi/01-arsitektur-dan-database.md)
   - Konfigurasi MySQL (`superpos`, port 3306, user `root`).
   - Penjelasan 138 file migrasi database dan skema tabel utama.
2. [02. Catatan Perubahan & Adaptasi](file:///e:/superpos-yukelola/dokumentasi/02-catatan-perubahan-dan-adaptasi.md)
   - Log lengkap perubahan yang dilakukan dari source code asli ke SuperPOS.
   - Penyesuaian bypass lisensi/installer untuk lingkungan offline/localhost.
   - Integrasi helper, model, dan dependensi Composer & NPM.
3. [03. Panduan Menjalankan di Localhost (Laravel Herd)](file:///e:/superpos-yukelola/dokumentasi/03-panduan-instalasi-dan-menjalankan.md)
   - Langkah menjalankan di Herd (`herd link superpos-yukelola`).
   - Menjalankan secara mandiri dengan `php artisan serve` & `npm run dev`.
4. [04. Lokalisasi Bahasa Indonesia](file:///e:/superpos-yukelola/dokumentasi/04-lokalisasi-bahasa-indonesia.md)
   - Struktur kamus bahasa di `lang/id/`.
   - Pengaturan default locale (`APP_LOCALE=id`) dan mata uang default Rupiah (IDR / Rp).
