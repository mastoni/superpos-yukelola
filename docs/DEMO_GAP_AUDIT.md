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

## 4. Implementasi & Resolusi (Gate B & Gate C)

### A. Gate B — Eliminasi Reset Destruktif & Sandbox Sesi Ephemeral
1. **Peniadaan `ResetDemoData` & `RunRestore`:**
   - Dihapus seluruh pemanggilan `ResetDemoData` dan `RunRestore` dari [`LoginController::destroy()`](file:///e:/superpos-yukelola/app/Http/Controllers/Auth/LoginController.php).
   - Logout tidak lagi mengeksekusi `php artisan down` ataupun `DROP TABLE`.
   - Logout membersihkan token sesi, melepaskan reservasi terminal, dan menghapus file sandbox SQLite ephemeral spesifik milik sesi yang bersangkutan via `DemoSandboxManager::destroySandbox($sandboxId)`.
2. **Arsitektur Sandbox SQLite Per-Sesi:**
   - Dibuat [`DemoSandboxManager`](file:///e:/superpos-yukelola/app/Services/Demo/DemoSandboxManager.php) dan [`EnsureDemoSandbox`](file:///e:/superpos-yukelola/app/Http/Middleware/EnsureDemoSandbox.php).
   - Sesi demo (`is_demo_session = true`) dialokasikan file sandbox SQLite terisolasi di `storage/framework/demo_sandboxes/sandbox_{uuid}.sqlite`.
   - Kegagalan inisialisasi sandbox bersifat *fail-closed* (HTTP 500) dan **tidak pernah jatuh tempo (failover) ke database produksi**.
   - Database utama / MySQL dan data pengguna superadmin asli sama sekali tidak tersentuh oleh pengunjung demo.

### B. Gate C — Penyempurnaan Demo Calon Pembeli (Buyer Experience)
1. **1-Click Interactive Demo Login Options:**
   - Pada [`resources/views/auth/login.blade.php`](file:///e:/superpos-yukelola/resources/views/auth/login.blade.php), ketika `pos_is_demo()` aktif, disajikan panel UI interaktif berbahasa Indonesia:
     - **Coba sebagai Admin Demo** (`/demo-login/admin`)
     - **Coba sebagai Kasir Demo** (`/demo-login/cashier`)
   - Otentikasi server-side mengecek akun terdaftar eksplisit (`config('pos.demo.credentials')`) tanpa mengekspos kredensial atau password di sisi client JavaScript/HTML.
   - Perilaku form login standar tetap utuh dan fungsional.
2. **Konfigurasi Lingkungan Aman (Safe Production Defaults):**
   - Di [`config/pos.php`](file:///e:/superpos-yukelola/config/pos.php), `POS_DEMO_MODE` secara default bernilai `false`. Mode demo hanya aktif jika `.env` secara eksplisit disetel `POS_DEMO_MODE=true`.
   - Variabel lingkungan didokumentasikan di [`.env.example`](file:///e:/superpos-yukelola/.env.example).
3. **Dataset Ritel Indonesia Deterministik & Realistis:**
   - Dibuat seeder [`IndonesianRetailDemoSeeder.php`](file:///e:/superpos-yukelola/database/seeders/IndonesianRetailDemoSeeder.php) yang diinjeksikan langsung saat pembentukan golden baseline template (`demo_baseline.sqlite`).
   - Berisi katalog FMCG ritel Indonesia (Beras Ramos, Minyak Sania, Gula Gulaku, Indomie Goreng, Kopi Kapal Api, Aqua, Pepsodent, Sunlight, Bango, Roma Kelapa, dsb.).
   - Dilengkapi 14 hari riwayat transaksi penjualan realistis dengan metode pembayaran multi-channel (Cash, QRIS, Bank Transfer) dan pemotongan stok otomatis, sehingga grafik omset dan KPI dashboard langsung hidup dan tidak kosong (0).
4. **Pembersihan Otomatis Sandbox Kadaluarsa (Lifecycle Hygiene):**
   - Didaftarkan command artisan `php artisan pos:demo-prune {--lifetime=120}` dan scheduler per jam pada [`routes/console.php`](file:///e:/superpos-yukelola/routes/console.php) untuk menghapus sandbox yang ditinggalkan tanpa menyentuh active session maupun template baseline.

---

## 5. Bukti Pengujian Otomatis

Seluruh 31 pengujian fitur dan unit (140 assertions) berhasil dijalankan:
- `DemoAuthenticationTest` (12 tests, 50 assertions): Membuktikan proteksi rute demo login, seleksi peran server-side, render UI satu klik, dan penolakan jika akun tidak ditemukan atau demo nonaktif.
- `DemoDataIsolationTest` (10 tests, 48 assertions): Membuktikan isolasi dua sesi bersamaan, independensi penghapusan CRUD dalam sandbox, logout non-destruktif, proteksi akun superadmin dari sandbox, ketersediaan dataset ritel Indonesia, pengurangan stok akibat transaksi, dan keamanan pruning file kadaluarsa.
- `UpdaterRemovalVerificationTest` (7 tests, 40 assertions): Membuktikan pelepasan updater vendor pihak ketiga secara permanen.
- `ExampleTest` (2 tests, 2 assertions).

---

## 6. Langkah-Langkah Mengaktifkan Mode Demo Secara Aman

Untuk menyajikan showcase demo YUKelola pada server staging / demo publik:
1. Pastikan server web / staging terpisah dari database produksi pelanggan.
2. Tambahkan pengaturan berikut pada file `.env`:
   ```env
   POS_DEMO_MODE=true
   POS_DEMO_ADMIN_NAME="Demo Super Admin YUKelola"
   POS_DEMO_ADMIN_EMAIL=admin@yukelola.id
   POS_DEMO_CASHIER_NAME="Demo Kasir YUKelola"
   POS_DEMO_CASHIER_EMAIL=kasir@yukelola.id
   ```
3. Pastikan direktori `storage/framework/` memiliki izin tulis (write permission) bagi proses PHP / webserver (`0775` atau owner `www-data`).
4. Jalankan `php artisan schedule:run` melalui cron server agar pembersihan sandbox kadaluarsa (`pos:demo-prune`) berjalan secara otomatis setiap jam.

---

## 7. Risiko yang Masih Tersisa

1. **Write Permission pada Server Baru:** Jika direktori `storage/framework` pada hosting tidak dapat ditulisi, pembuatan SQLite baseline akan gagal (*fail-closed* dengan HTTP 500). Hal ini sesuai rancangan keamanan agar tidak pernah menyusup ke database produksi.
2. **Kapasitas Penyimpanan Disk Sementara:** Jika ribuan pengunjung membuka demo secara bersamaan dan tidak logout, file SQLite sementara (~1MB per sesi) akan bertahan hingga 120 menit sebelum dibersihkan oleh `pos:demo-prune`. Disarankan menyediakan ruang disk minimal 2-5 GB pada server demo publik.
