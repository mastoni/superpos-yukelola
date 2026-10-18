# 04. Lokalisasi Bahasa Indonesia

Dokumen ini menjelaskan struktur bahasa, glosarium istilah POS, dan cara mengelola penerjemahan pada **SuperPOS Yukelola**.

---

## 1. Struktur Folder Bahasa
Penerjemahan teks aplikasi disimpan dalam direktori `lang/`:
- `lang/id/`: Paket terjemahan Bahasa Indonesia (bawaan aktif).
- `lang/en/`: Paket bahasa Inggris (sebagai fallback cadangan).

Pengaturan di file [.env](file:///e:/superpos-yukelola/.env):
```env
APP_LOCALE=id
APP_FALLBACK_LOCALE=en
APP_FAKER_LOCALE=id_ID
```

---

## 2. Glosarium Istilah POS & ERP Indonesia
Untuk menjaga konsistensi antarmuka pengguna, istilah teknis disesuaikan dengan praktik umum retail di Indonesia:

| Istilah Asli (Inggris) | Istilah Indonesia (SuperPOS) | Keterangan |
| :--- | :--- | :--- |
| **POS / Cashier** | **Kasir (POS)** | Antarmuka transaksi penjualan barang. |
| **Walk-in Customer** | **Pelanggan Umum** | Pelanggan tanpa akun terdaftar. |
| **Hold Order** | **Tahan Pesanan** | Menyimpan sementara transaksi yang belum selesai dibayar. |
| **Held Orders** | **Pesanan Ditahan** | Daftar transaksi yang ditunda untuk dilanjutkan kembali. |
| **Checkout** | **Bayar (Checkout)** | Menyelesaikan transaksi dan memilih metode bayar. |
| **Change** | **Kembalian** | Uang kembalian dari pembayaran tunai. |
| **Grand Due / Balance Due** | **Total Tagihan / Kurang Bayar** | Nilai sisa yang harus dibayar. |
| **Stock Takes** | **Stock Opname** | Pemeriksaan fisik persediaan vs catatan sistem. |
| **Stock Adjustments** | **Penyesuaian Stok** | Koreksi kuantitas barang karena rusak/hilang/penambahan. |
| **Stock Transfers** | **Transfer Stok Antar Toko** | Mutasi kirim/terima stok antar cabang. |
| **Chart of Accounts (COA)** | **Bagan Akun** | Daftar klasifikasi akun keuangan akuntansi. |
| **General Ledger** | **Buku Besar** | Catatan ringkasan setiap akun akuntansi. |
| **Trial Balance** | **Neraca Saldo** | Keseimbangan total debit dan kredit. |
| **Profit & Loss** | **Laba Rugi** | Laporan pendapatan dan beban operasional. |
| **Balance Sheet** | **Neraca Keuangan** | Laporan posisi aset, kewajiban, dan modal. |
| **Cash Drawer** | **Laci Kasir** | Tempat penyimpanan uang fisik kasir per shift. |
| **Shift Variance** | **Selisih Kas** | Perbedaan hitungan fisik kas laci vs sistem saat tutup shift. |

---

## 3. Menyesuaikan File Terjemahan Tambahan
Untuk memperluas atau mengubah kata-kata pada modul tertentu, cukup edit file terkait di dalam `lang/id/`:
- `lang/id/cashier.php`: Seluruh label transaksi kasir.
- `lang/id/admin.php`: Seluruh navigasi dan menu backoffice.
- `lang/id/products.php`: Form produk, varian, dan cetak barcode.
- `lang/id/customers.php`: Manajemen pelanggan dan batas kredit.
- `lang/id/suppliers.php`: Manajemen vendor dan order pembelian.
- `lang/id/settings.php`: Pengaturan umum dan profil perusahaan.
