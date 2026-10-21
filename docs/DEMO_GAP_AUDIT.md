# AUDIT KESENJANGAN FITUR & KEAMANAN DEMO YUKELOLA (GATE A)

**Repository:** `https://github.com/mastoni/superpos-yukelola`<br>
**Branch:** `fix/security-demo-login-fallbacks`<br>
**Tanggal Audit:** 21 Oktober 2026<br>
**Status Evaluasi:** Gate A Audit Selesai — Menuju Implementasi Gate B & C

---

## 1. Eksekutif Ringkasan

YUKelola adalah aplikasi Point of Sale (POS) dan manajemen inventaris berbasis *local-first hybrid* yang memiliki aplikasi desktop Windows (.exe) dan Android (.apk). Website publik YUKelola berfungsi sebagai **media demo interaktif bagi calon pembeli** untuk mengevaluasi fitur, kelengkapan alur bisnis kasir, manajemen stok, dan laporan keuangan sebelum mengunduh atau membeli lisensi.

Berdasarkan audit menyeluruh terhadap kode sumber, controller, route, model, skema database, dan antarmuka, aplikasi YUKelola memiliki fondasi modul bisnis yang sangat matang (akuntansi double-entry, multi-pembayaran, FEFO batch, stok opname, dan laporan lengkap). Namun, terdapat **kesenjangan kritis pada lapisan arsitektur demo, isolasi data, dan kemudahan akses pengunjung** yang harus diselesaikan agar demo layak diakses publik.

---

## 2. Inventarisasi dan Status Fitur (12 Area Modul)

| No | Area Modul | Status | Bukti Teknis & Temuan | Dampak Calon Pembeli | Prioritas |
|:--:|:---|:---:|:---|:---|:---:|
| **1** | **Beranda / Dashboard** | **PARTIAL** | Controller [`DashboardController.php`](file:///e:/superpos-yukelola/app/Http/Controllers/Admin/DashboardController.php) memiliki agregasi KPI lengkap (Omset, Transaksi, Average Basket, Gross Profit, Tren 14 Hari). Namun, grafik bergantung pada data transaksi historis. Jika demo dijalankan tanpa seeding data realistis, grafik kosong (0). | Calon pembeli melihat dashboard yang tampak tidak hidup / tidak meyakinkan. | P1 |
| **2** | **POS / Kasir & Transaksi** | **PASS** | Controller [`SaleController.php`](file:///e:/superpos-yukelola/app/Http/Controllers/Admin/SaleController.php) & [`SaleCreateController.php`](file:///e:/superpos-yukelola/app/Http/Controllers/Admin/SaleCreateController.php). Mendukung pencarian produk, scan barcode, keranjang, diskon bertingkat, PPN/pajak, multi-metode pembayaran (Cash, Kartu, QRIS), Hold & Resume transaksi, serta Shift Kasir. | Alur kasir sangat realistis, cepat, dan sesuai kebutuhan ritel/grosir Indonesia. | P0 |
| **3** | **Produk, Kategori & Barcode** | **PASS** | [`ProductController.php`](file:///e:/superpos-yukelola/app/Http/Controllers/Admin/ProductController.php), [`CategoryController.php`](file:///e:/superpos-yukelola/app/Http/Controllers/Admin/CategoryController.php), [`ProductBatchController.php`](file:///e:/superpos-yukelola/app/Http/Controllers/Admin/ProductBatchController.php), [`LabelController.php`](file:///e:/superpos-yukelola/app/Http/Controllers/Admin/LabelController.php). Mendukung varian barang, harga beli vs jual, satuan unit, FEFO batch expiry date, dan cetak label barcode. | Calon pembeli dapat menguji katalog produk yang fleksibel. | P1 |
| **4** | **Persediaan & Stok Opname** | **PASS** | [`StockMovementController.php`](file:///e:/superpos-yukelola/app/Http/Controllers/Admin/StockMovementController.php), [`StockAdjustmentController.php`](file:///e:/superpos-yukelola/app/Http/Controllers/Admin/StockAdjustmentController.php), [`StockTakeController.php`](file:///e:/superpos-yukelola/app/Http/Controllers/Admin/StockTakeController.php), [`StockTransferController.php`](file:///e:/superpos-yukelola/app/Http/Controllers/Admin/StockTransferController.php). Mutasi stok tercatat otomatis per transaksi; terdapat alur stok opname fisik vs sistem. | Menunjukkan keandalan pengawasan persediaan tanpa selisih gaib. | P1 |
| **5** | **Pembelian & Supplier** | **PASS** | [`PurchaseController.php`](file:///e:/superpos-yukelola/app/Http/Controllers/Admin/PurchaseController.php), [`SupplierController.php`](file:///e:/superpos-yukelola/app/Http/Controllers/Admin/SupplierController.php). Purchase Order (PO), penerimaan barang otomatis menambah stok, dan retur pembelian. | Membuktikan integrasi supply chain toko dengan supplier. | P2 |
| **6** | **Kas Masuk/Keluar & Petty Cash** | **PASS** | [`ExpenseController.php`](file:///e:/superpos-yukelola/app/Http/Controllers/Admin/ExpenseController.php), [`ShiftController.php`](file:///e:/superpos-yukelola/app/Http/Controllers/Admin/ShiftController.php). Pengeluaran operasional toko dapat dibayarkan langsung dari laci kas (*till payout*) dengan rekonsiliasi saat tutup shift kasir. | Pengendalian uang kas harian toko transparan dan akurat. | P1 |
| **7** | **Piutang Pelanggan** | **PASS** | [`CustomerPaymentController.php`](file:///e:/superpos-yukelola/app/Http/Controllers/Admin/CustomerPaymentController.php), [`AgedReceivablesReportController.php`](file:///e:/superpos-yukelola/app/Http/Controllers/Admin/AgedReceivablesReportController.php). Pencatatan penjualan tempo/kredit, kartu piutang pelanggan (*statement*), dan analisis umur piutang. | Fitur bernilai tinggi bagi toko kelontong/distributor yang mengizinkan bon/tempo. | P1 |
| **8** | **Hutang Supplier** | **PASS** | [`SupplierPaymentController.php`](file:///e:/superpos-yukelola/app/Http/Controllers/Admin/SupplierPaymentController.php). Pembayaran bertahap atas faktur pembelian belum lunas, kartu hutang supplier. | Pemilik toko dapat melacak jatuh tempo pembayaran dagang. | P2 |
| **9** | **Laporan & Akuntansi** | **PASS** | [`ReportsHubController.php`](file:///e:/superpos-yukelola/app/Http/Controllers/Admin/ReportsHubController.php), Laba Rugi (*P&L*), Arus Kas (*Cash Flow*), Neraca (*Balance Sheet*), Neraca Saldo (*Trial Balance*). Terhubung ke sistem akuntansi double-entry (`journal_entries`). | Nilai jual utama YUKelola: POS modern yang memiliki pembukuan akuntansi standar. | P1 |
| **10** | **Pengaturan Usaha & Keamanan** | **PASS** | [`CompanyProfileController.php`](file:///e:/superpos-yukelola/app/Http/Controllers/Admin/CompanyProfileController.php), [`UserController.php`](file:///e:/superpos-yukelola/app/Http/Controllers/Admin/UserController.php), [`RoleController.php`](file:///e:/superpos-yukelola/app/Http/Controllers/Admin/RoleController.php). Manajemen multi-outlet, role, PIN kasir, dan proteksi aksi destruktif via `pos_demo_locked()`. | Pengaturan toko rapi dan aman dari vandalisme akun sensitif. | P1 |
| **11** | **Pencetakan & Struk** | **PASS** | [`PrintController.php`](file:///e:/superpos-yukelola/app/Http/Controllers/Admin/PrintController.php), [`PublicReceiptController.php`](file:///e:/superpos-yukelola/app/Http/Controllers/Admin/PublicReceiptController.php). Format struk kasir 58mm / 80mm / A4, tautan struk online publik via QR code. | Hasil cetak struk profesional dan siap pakai pada printer thermal. | P2 |
| **12** | **Autentikasi & Isolasi Demo** | **FAIL** | 1. Logout demo memanggil `ResetDemoData` yang mengeksekusi `RunRestore` (maintenance mode + drop database tables).<br>2. Halaman login belum menampilkan tombol 1-Click Demo Login.<br>3. Konfigurasi `pos.demo.enabled` dan `pos.demo.credentials` belum tersambung fleksibel ke `.env`. | **KRITIS:** Pengunjung A yang logout akan mematikan sesi Pengunjung B (crash/maintenance). Pengunjung baru bingung login karena tidak ada tombol demo. | **P0 (Kritis)** |

---

## 3. Rincian Temuan Kritis (Root Cause Analysis)

### A. Masalah Concurrency Collision pada Logout Demo
Pada [`app/Http/Controllers/Auth/LoginController.php`](file:///e:/superpos-yukelola/app/Http/Controllers/Auth/LoginController.php) baris 137–143:
```php
if ($wasDemo && pos_is_demo()) {
    try {
        app(ResetDemoData::class)();
    } catch (\Throwable) {
        // Ignore if reset fails
    }
}
```
Ketika pengunjung demo menekan tombol "Logout", `ResetDemoData` memanggil `RunRestore`:
- Menjalankan `php artisan down` (aplikasi masuk maintenance mode).
- Melakukan `DROP TABLE` pada seluruh database aktif.
- Mengekstrak ZIP SQL backup lama.
- Menjalankan `php artisan up`.

**Dampak:**
Jika ada 5 calon pembeli yang sedang mencoba demo secara bersamaan, saat salah satu pembeli logout, 4 pembeli lainnya tiba-tiba mendapati aplikasi *error 503 Maintenance* atau kehilangan transaksi yang sedang mereka coba! Jika database yang digunakan adalah database utama (misal MySQL lokal/server), data permanen administrator berisiko terhapus.

### B. Ketiadaan UI Quick Login pada Form Masuk
Halaman [`resources/views/auth/login.blade.php`](file:///e:/superpos-yukelola/resources/views/auth/login.blade.php) mendefinisikan helper Alpine `fillCredentials(role, email, password)`, namun tidak ada kartu atau tombol pada HTML yang memanggil fungsi ini atau mengarahkan ke `route('demo.login', 'admin')` dan `route('demo.login', 'cashier')`. Calon pembeli yang membuka halaman login dipaksa menebak kredensial.

### C. Konfigurasi Kredensial Demo yang Kaku
Di [`config/pos.php`](file:///e:/superpos-yukelola/config/pos.php), array `'demo'` memiliki `'enabled' => false` dan `'credentials' => []` secara default tanpa membaca environment variable, sehingga jika file `.env` tidak secara manual dikonfigurasikan dengan array kredensial, route `demo.login` menolak login dengan error *"Akun demo tidak ditemukan"*.

---

## 4. Rekomendasi Solusi & Rencana Aksi (Action Plan)

### A. Gate B — Keamanan dan Isolasi Data Demo (Solusi Sandbox / Non-Destructive Reset)
1. **Hentikan Panggilan Destruktif pada Logout:**
   - Hapus pemanggilan `ResetDemoData` dan `RunRestore` dari alur `LoginController::destroy()`.
   - Logout demo harus berperilaku bersih: melepaskan reservasi terminal kasir (`TerminalReservation`), menghapus sesi pengguna, menginvalidasi token CSRF, dan mengarahkan kembali ke halaman login tanpa pernah menyentuh skema database atau mengaktifkan status maintenance.
2. **Pencegahan Penghapusan Data Super Admin:**
   - Pastikan akun demo kasir dan admin demo terkunci dalam sandbox atau dilarang menghapus akun pengguna superadmin asli, file backup sistem, atau lisensi.
3. **Mekanisme Sandbox Sesi (Per-Session Ephemeral Isolation):**
   - Rancang middleware penyedia koneksi database terisolasi untuk sesi demo, atau gunakan mekanisme penandaan data demo (*tenant identifier / session tagging*) sehingga operasi tulis/hapus pengunjung demo hanya memanipulasi data sesi mereka sendiri.

### B. Gate C — Improvisasi & Penyelesaian Gap Demo
1. **Penyempurnaan Halaman Login Demo:**
   - Tambahkan banner dan 2 tombol interaktif 1-Click Login pada [`resources/views/auth/login.blade.php`](file:///e:/superpos-yukelola/resources/views/auth/login.blade.php):
     - **"Masuk sebagai Admin Demo"** (`admin@yukelola.id`)
     - **"Masuk sebagai Kasir Demo"** (`kasir@yukelola.id`)
   - Tampilkan informasi bahwa ini adalah akun evaluasi fitur YUKelola.
2. **Sinkronisasi Konfigurasi `config/pos.php`:**
   - Pasang default kredensial akun demo (`admin@yukelola.id` dan `kasir@yukelola.id`) di `config/pos.php` yang membaca `env('POS_DEMO_MODE')`.
3. **Penyediaan Baseline Data Ritel Indonesia Realistis:**
   - Pastikan database demo memiliki data awal yang kaya:
     - Profil toko: "Minimarket YUKelola Mart"
     - Kategori: Makanan & Minuman, Kebutuhan Rumah Tangga, ATK, Obat-obatan.
     - Produk nyata: Indomie Goreng, Minyak Goreng Bimoli 2L, Beras Ramos 5kg, Kopi Kapal Api, Teh Pucuk Harum, dsb.
     - Transaksi historis 14 hari terakhir agar grafik dashboard langsung tampil memukau dan informatif.
     - 1 Shift kasir terbuka agar pengunjung kasir bisa langsung mencoba memindai barcode dan melakukan checkout tanpa terhalang popup error shift.

---

## 5. Matriks Prioritas Pelaksanaan

| Gate | Target Pekerjaan | Output / Verifikasi | Status |
|:---:|:---|:---|:---:|
| **Gate A** | Audit inventaris, call-site, skema database, dan alur modul | Dokumen `docs/DEMO_GAP_AUDIT.md` | **SELESAI (PASS)** |
| **Gate B** | Eliminasi logout destruktif `ResetDemoData`, isolasi sesi demo aman | `DemoDataIsolationTest.php` lulus, tidak ada maintenance saat logout | **SIAP DIKERJAKAN** |
| **Gate C** | Tombol 1-click login di UI, konfigurasi demo otomatis, baseline data ritel | UI login interaktif, dashboard berdata realistis | **MENUNGGU GATE B** |
| **Gate D** | Validasi menyeluruh, regresi modul POS & auth, Pint formatting | Seluruh PHPUnit test suite passing | **MENUNGGU GATE C** |
| **Gate E** | Commit terpisah & laporan akhir ke pengguna | Commit log rapi & laporan komprehensif | **TAHAP AKHIR** |
