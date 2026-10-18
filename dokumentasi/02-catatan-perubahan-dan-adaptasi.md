# 02. Catatan Perubahan & Adaptasi (Changelog)

Dokumen ini mencatat seluruh penyesuaian teknis dan fungsional yang dilakukan saat mentransformasikan basis kode asli menjadi **SuperPOS Yukelola**.

---

## 1. Lingkungan & Dependensi
- **Pembaruan Platform**: Berjalan pada **Laravel 13** dan **PHP 8.4** di lingkungan Windows (Laravel Herd).
- **Penambahan Paket Composer**:
  - `bacon/bacon-qr-code (^3.0)`: Pembuatan barcode 2D & QR code dinamis.
  - `mpdf/mpdf (^8.3)`: Ekspor laporan, faktur, dan bukti transaksi berformat PDF.
  - `openspout/openspout (^5.3)`: Impor dan ekspor data skala besar (Excel/CSV) hemat memori.
  - `picqer/php-barcode-generator (^3.2)`: Generator barcode 1D (EAN13, Code128, UPC).
  - Autoload `app/helpers.php` untuk fungsi global POS.
- **Penambahan Paket Frontend (NPM)**:
  - `@zxing/browser`: Pemindaian barcode langsung melalui kamera perangkat.
  - `alpinejs`: Reaktivitas antarmuka tanpa overhead framework berat.
  - `dexie`: IndexedDB offline storage untuk kasir dapat bertransaksi saat jaringan lokal terganggu.
  - `tom-select`, `sortablejs`, `flatpickr`, `qrcode`, `hugerte`.
  - Vite bundling 12 bundle stylesheet dan skrip terpisah (`npm run build`).

---

## 2. Penyesuaian Offline & Bypass Lisensi (Localhost Standalone)
Karena sistem dirancang untuk penggunaan lokal mandiri tanpa server VPS/internet:
1. **Konfigurasi .env**:
   ```env
   POS_LICENSE_REQUIRED=false
   POS_LICENSE_DEV_BYPASS=true
   POS_LICENSE_RECHECK=false
   POS_DEV_ROUTES=true
   ```
2. **Mekanisme Lisensi**:
   - Middleware `EnsureLicenseActive` membaca bendera `POS_LICENSE_DEV_BYPASS=true` sehingga tidak lagi memblokir sistem atau menghubungi server validator eksternal.
   - Pengecekan recheck latar belakang dimatikan (`POS_LICENSE_RECHECK=false`).

---

## 3. Adaptasi Data Default Indonesia (Seeders)
- **`LanguagesSeeder`**:
  - Menambahkan kode `id` (`Bahasa Indonesia`) sebagai bahasa bawaan sistem (`is_default = true`).
- **`CurrenciesSeeder`**:
  - Rupiah (`IDR`) dikonfigurasi dengan simbol `Rp`, desimal `0`, pemisah ribuan titik (`.`), dan desimal koma (`,`).
- **`DevBootstrapSeeder`**:
  - Disesuaikan untuk inisialisasi entitas:
    - Nama Perusahaan: `SuperPOS Yukelola`
    - Negara: `ID` (Indonesia)
    - Mata Uang Basis: `IDR`
    - Zona Waktu: `Asia/Jakarta` (WIB)
    - Toko / Cabang Awal: `Toko Utama` (Kode: `UTAMA`)
    - Alamat: `Jl. Jenderal Sudirman, Indonesia`
    - Akun Administrator: `admin@superpos.test` (Kata Sandi: `password`)

---

## 4. Lokalisasi Bahasa (Localization)
- Folder bahasa baru dibuat di `lang/id/`.
- Terjemahan modul inti:
  - **Autentikasi (`auth.php`)**: Pesan login, validasi, reset kata sandi, dan status akun.
  - **Navigasi Admin (`admin.php`)**: Seluruh menu sidebar (Operasional, Penjualan, Pelanggan, Produk, Inventaris, Akuntansi, Laporan).
  - **Antarmuka Kasir (`cashier.php`)**: Keranjang belanja, pembayaran, penahanan pesanan (hold), retur, pencarian produk, scan barcode, dan cetak struk.
