# 01. Arsitektur & Database SuperPOS

Dokumen ini menjelaskan struktur arsitektur sistem dan skema database MySQL pada proyek **SuperPOS Yukelola**.

---

## 1. Konfigurasi Lingkungan Database
- **DBMS**: MySQL 8.x / MariaDB
- **Host**: `127.0.0.1` (Port: `3306`)
- **Nama Database**: `superpos`
- **Pengguna**: `root`
- **Kata Sandi**: `06juli1975`
- **Karakter Set**: `utf8mb4` dengan collation `utf8mb4_unicode_ci`

File konfigurasi di [.env](file:///e:/superpos-yukelola/.env):
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=superpos
DB_USERNAME=root
DB_PASSWORD=06juli1975
```

---

## 2. Struktur Migrasi (138 File Migrasi)
Proyek ini mengintegrasikan seluruh 138 skema migrasi tabel yang mencakup ekosistem POS dan ERP lengkap. Migrasi dieksekusi secara kronologis:

### A. Core Authentication & Tenant
- `0001_01_01_000000_create_users_table.php`: Tabel `users`, `password_reset_tokens`, dan `sessions` dengan field tambahan POS (`phone`, `is_super_admin`, `is_active`, `default_store_id`, `preferences`).
- `2026_05_21_000001_create_auth_and_permissions.php`: Tabel `roles`, `permissions`, `role_permissions`, `user_roles`.
- `2026_05_21_000003_create_company_and_stores.php`: Tabel `company` (pengaturan profil toko/entitas bisnis) dan `stores` (multi-outlet/cabang).

### B. Keuangan & Mata Uang
- `2026_05_21_000002_create_currencies_and_exchange_rates.php`: Tabel mata uang dan kurs valuta asing (mendukung Rupiah `IDR` / `Rp`).
- `2026_05_21_000004_create_accounting_core.php`: Akun buku besar (`chart_of_accounts`), periode akuntansi, dan pemetaan akun bisnis.
- `2026_05_21_000013_create_journal.php`: Entri jurnal akuntansi berpasangan (double-entry bookkeeping).
- `2026_05_21_000005_create_taxes.php`: Komponen pajak (PPN/PPH) dan klasifikasi pajak barang.
- `2026_05_21_000006_create_payment_methods.php`: Metode pembayaran kas, transfer, kartu, dan QRIS.

### C. Katalog & Manajemen Produk
- `2026_05_21_000007_create_catalog.php`: Tabel `categories`, `brands`, `units`, `products`, `product_variants`, `product_store_prices`.
- `2026_05_21_000008_create_inventory.php`: Manajemen stok per toko, penyesuaian stok (`stock_adjustments`), mutasi barang, batch nomor & tanggal kedaluwarsa produk.
- `2026_06_11_000002_create_stock_takes.php`: Fitur Stock Opname (rekonsiliasi stok fisik vs sistem).

### D. Penjualan & Kasir (POS)
- `2026_05_21_000011_create_shifts_and_drawer.php`: Manajemen sesi shift kerja kasir (`shifts`) dan arus kas laci (`cash_drawer_entries`).
- `2026_05_21_000012_create_sales.php`: Tabel transaksi penjualan (`sales`), rincian item (`sale_items`), pembayaran penjualan (`sale_payments`), retur (`sale_returns`), dan diskon pesanan.
- `2026_06_13_000001_create_pos_payment_sessions.php`: Sesi transaksi pembayaran POS real-time.
- `2026_07_08_000001_add_kiosk_config_to_terminals.php`: Konfigurasi stasiun kiosk mandiri (Self-Ordering Kiosk).

### E. Pelanggan & Pemasok (CRM & Procurement)
- `2026_05_21_000009_create_customers.php`: Data pelanggan, histori kredit, dan poin loyalitas.
- `2026_05_21_000010_create_suppliers_and_purchases.php`: Data vendor/supplier, order pembelian (PO), penerimaan barang, dan utang usaha.

### F. Fitur Pendukung ERP
- `2026_05_21_000014_create_expenses.php`: Pencatatan beban/pengeluaran operasional toko.
- `2026_05_21_000015_create_receipts.php`: Format struk belanja dan templat cetak.
- `2026_05_21_000018_create_backups.php`: Manajemen pencadangan database.
- `2026_08_17_000001_create_commerce_integration_tables.php`: Saluran penjualan eksternal / e-commerce sync.
- `2026_08_19_000001_create_quotations.php`: Pembuatan surat penawaran harga formal kepada pelanggan.
