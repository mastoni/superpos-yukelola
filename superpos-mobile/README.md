# SuperPOS Mobile (Android POS Offline-First & Google Sheets Backup)

Aplikasi Point of Sale (POS) Android mandiri berbasis **Flutter & SQLite (Database Internal HP)** yang dirancang dari sistem **SuperPOS Yukelola**. Aplikasi ini dapat berjalan 100% tanpa internet (offline) dan dilengkapi fitur **sinkronisasi & pencadangan otomatis/manual ke Google Sheets**.

---

## 🌟 Fitur Utama

1. **Database Internal HP (SQLite Offline-First)**
   - Semua data katalog produk, stok, riwayat penjualan, dan pengeluaran tersimpan aman di penyimpanan internal handphone.
   - Tetap dapat melayani transaksi kasir kapan saja tanpa bergantung pada sinyal internet.

2. **Kasir POS Cepat & Interaktif**
   - Pencarian produk instan & filter kategori.
   - Scan Barcode & QR Code langsung menggunakan kamera HP.
   - Keranjang belanja dinamis (tambah, kurangi qty, hapus item).
   - Metode pembayaran: **Tunai (Cash)** dengan kalkulator kembalian & tombol pecahan cepat (Uang Pas, Rp 10rb, 20rb, 50rb, 100rb), **QRIS**, dan **Transfer Bank**.
   - Input potongan / diskon per transaksi.

3. **Cetak & Bagikan Struk Belanja**
   - **Cetak Printer Thermal Bluetooth (58mm / 80mm)** via Android Print Service (ESC/POS).
   - **Bagikan Struk Digital via WhatsApp** secara rapi dengan 1 sentuhan.
   - Cetak ulang struk lama dari riwayat transaksi kapan saja.

4. **Katalog Produk & Manajemen Stok**
   - Tambah, edit, dan hapus produk.
   - Pemantauan stok menipis (*Low Stock Alert* <= 10).
   - Generator kode SKU / Barcode otomatis.

5. **Pencadangan & Pemulihan Google Sheets (Google Apps Script Web App)**
   - Backup seluruh data produk, transaksi, item penjualan, dan pengeluaran ke Google Spreadsheet.
   - Menggunakan Google Apps Script Web App: **Sangat mudah & praktis, tanpa ribet OAuth / Google Cloud Console**.
   - Fitur **Tarik Data (Restore)**: Jika berganti HP atau data tereset, Anda dapat menarik kembali katalog produk dari Google Sheets ke HP dengan 1 tombol.

6. **Pengeluaran Toko & Laporan Laba**
   - Pencatatan biaya operasional toko harian.
   - Perhitungan otomatis laba kotor (*Gross Profit*) dan laba bersih di dashboard.

---

## 🚀 Panduan Setup Google Sheets (1 Menit)

Agar aplikasi dapat mengirim backup ke Google Sheets Anda:

1. Buka [Google Sheets](https://sheets.new) di browser laptop atau HP dan buat Spreadsheet baru (misal dinamai: `SuperPOS Laporan & Backup`).
2. Di menu atas, pilih **Ekstensi (Extensions)** > **Apps Script**.
3. Hapus seluruh kode bawaan yang ada di editor `Code.gs`.
4. Buka file `superpos-mobile/google_apps_script/Code.gs` dari project ini, salin seluruh kodenya, dan paste ke editor Apps Script.
5. Klik tombol biru **Terapkan (Deploy)** di pojok kanan atas > pilih **Penerapan Baru (New Deployment)**.
6. Klik ikon gerigi jenis penerapan > pilih **Aplikasi Web (Web app)**.
7. Konfigurasikan:
   - **Deskripsi**: `SuperPOS Sync`
   - **Jalankan sebagai (Execute as)**: `Saya (Me)`
   - **Yang memiliki akses (Who has access)**: `Siapa saja (Anyone)` *(Wajib dipilih agar HP dapat mengirim data tanpa login Google)*.
8. Klik **Terapkan (Deploy)**, lalu berikan izin akses akun Google Anda (*Authorize access*).
9. Salin **URL Aplikasi Web (Web app URL)** yang berakhiran `/exec`.
10. Di aplikasi SuperPOS Mobile di HP Anda, buka menu **Backup & Google Sheets**, tempelkan URL tersebut, lalu tekan tombol **Uji Koneksi** dan **Backup Sekarang**!

---

## 📱 Cara Membangun APK (Build APK)

### Cara 1: Menggunakan Script Otomatis (Windows 1-Click)
Cukup klik ganda file:
```
superpos-mobile/build-apk.bat
```
Script akan otomatis mengunduh dependensi dan membuat file APK rilis.

### Cara 2: Menggunakan Terminal / Command Prompt
Buka terminal di folder `superpos-mobile`:
```bash
# 1. Unduh dependensi
flutter pub get

# 2. Build APK Release
flutter build apk --release
```

File APK siap install akan berada di:
```
superpos-mobile/build/app/outputs/flutter-apk/app-release.apk
```

---

## 🛠 Struktur Folder Project

```
superpos-mobile/
├── android/                   # Pengaturan Android native & manifest permissions
├── build-apk.bat              # Script 1-klik build APK release untuk Windows
├── google_apps_script/
│   └── Code.gs                # Script webhook Google Sheets (Apps Script)
└── lib/
    ├── database/
    │   └── database_helper.dart      # SQLite offline engine (CRUD, Relasi, Migration)
    ├── models/
    │   ├── category_model.dart       # Model Kategori
    │   ├── expense_model.dart        # Model Pengeluaran
    │   ├── product_model.dart        # Model Produk & Stok
    │   ├── sale_item_model.dart      # Model Item Penjualan
    │   ├── sale_model.dart           # Model Transaksi Penjualan
    │   └── store_settings_model.dart # Pengaturan Toko & Koneksi Sheets
    ├── screens/
    │   ├── backup_sheets_screen.dart # Layar Pengaturan & Sync Google Sheets
    │   ├── barcode_scanner_screen.dart # Scanner Barcode Kamera HP
    │   ├── dashboard_screen.dart     # Dashboard statistik & ringkasan omset
    │   ├── expenses_screen.dart      # Pencatatan biaya operasional
    │   ├── pos_cashier_screen.dart   # Layar Kasir interaktif & checkout
    │   ├── products_screen.dart      # Manajemen produk & stok
    │   └── transactions_screen.dart  # Riwayat transaksi & cetak ulang
    ├── services/
    │   ├── google_sheets_service.dart# HTTP Client sinkronisasi Google Sheets
    │   ├── receipt_service.dart      # Engine cetak Bluetooth thermal & kirim WA
    │   └── settings_service.dart     # Local storage pengaturan toko
    └── main.dart                     # Entry point aplikasi
```
