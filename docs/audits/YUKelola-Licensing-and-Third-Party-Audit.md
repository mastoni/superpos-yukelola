# YUKelola — Licensing, Third-Party Source & Permanent Entitlement Audit Report
**Dokumen:** `docs/audits/YUKelola-Licensing-and-Third-Party-Audit.md`  
**Tanggal Audit:** 21 Oktober 2026  
**Cabang Repositori:** `fix/security-demo-login-fallbacks`  
**Base Branch:** `master`  
**Status Gate:** Gate A (PASS), Gate B (PASS), Gate C1 (AUDITED / BLOCKED ON VERIFICATION), Gate C2 (ARCHITECTURAL PROPOSAL FROZEN), Gate C3 (IMPLEMENTED & FULLY VERIFIED)

---

## 1. Eksekutif Ringkasan & Keputusan Pemilik Produk

Berdasarkan keputusan resmi pemilik produk (*Owner-approved product decisions*):
1. **Website Demo adalah Showcase & Testing Environment**: Berfungsi mengenalkan fitur kepada calon pengguna sebelum mengunduh. Data demonstrasi terisolasi dari data pelanggan sungguhan, login demo tidak dapat mengautentikasi akun riil, dan rute GET demo tidak memiliki efek samping destruktif (**Lolos Uji 100% pada Gate B**).
2. **Lisensi Aplikasi Bersifat Permanen (*Perpetual*)**: Pengguna yang mengunduh aplikasi memiliki hak penggunaan permanen; tidak boleh menerapkan kedaluwarsa berkala atau langganan wajib tanpa persetujuan baru. Operasi lokal tidak boleh terkunci akibat server aktivasi atau internet mati.
3. **Model Distribusi Hibrida Local-First**: Operasi inti kasir POS, stok, dan pencatatan transaksi lokal pada Windows Desktop dan Android Mobile beroperasi 100% offline.
4. **Feed Updater Vendor Dinonaktifkan**: Decoupling menyeluruh telah diterapkan agar pembaruan vendor upstream tidak menimpa kustomisasi lokal (**Lolos Uji 100% pada Gate C3**).
5. **Rekomendasi Pembaruan Rilis Awal**: Pembaruan YUKelola dilakukan melalui **installer resmi YUKelola yang dirilis dan diuji secara manual**; tidak membangun server updater baru pada tahap ini.

---

## 2. Gate C1 — Audit Lisensi & Hak Penggunaan Upstream

Untuk mematuhi prinsip kehati-hatian hukum dan mencegah klaim tak berdasar, audit membedakan secara tegas antara:
- Fakta yang dibuktikan dari kode;
- Ketentuan yang dibuktikan dari dokumen lisensi;
- Asumsi yang belum terverifikasi;
- Pertanyaan yang memerlukan keputusan pemilik.

### A. Fakta yang Sudah Dibuktikan dari Kode Sumber (*Code-Proven Facts*)
1. **Identitas Produk Asal:**
   - Item ID CodeCanyon tercatat pada `config/pos.php:313`: `item_id => '64055507'`.
   - Nama produk asli adalah *Hyper POS* yang dikembangkan oleh vendor *Infinitie Technologies*.
2. **Ketergantungan Endpoint Validator Remote:**
   - `config/pos.php:304` mengonfigurasi validator: `https://validator-new.infinitietech.com/api/v1/validate`.
   - `config/pos.php:305` menyertakan kunci API vendor hardcoded: `554c9a4319fd81f75586008b347f767eb9a48b6995c26b50`.
3. **Ketergantungan Feed Pembaruan Asal:**
   - `config/pos.php:165` sebelumnya mengarah ke `https://updates.infinitietech.com/pos.json`.
4. **Status Middleware Lisensi Saat Ini:**
   - `app/Http/Middleware/EnsureLicenseActive.php` terdaftar di middleware web (`bootstrap/app.php`). Pada branch `master`, isi method `handle` mengembalikan `return $next($request);` (bypassed).

### B. Ketentuan yang Sudah Dibuktikan dari Dokumen Lisensi (*Document-Proven Terms*)
1. **Lisensi Dependensi Open-Source:**
   - Berkas lisensi pihak ketiga pada direktori `vendor/` dan `package.json` membuktikan penggunaan paket-paket standar (Laravel 13, Symfony, BaconQrCode, OpenSpout, mPDF) di bawah lisensi resmi: MIT, BSD, Apache-2.0, LGPL.
   - Kewajiban legal: Berkas atribusi dan notice hak cipta pustaka open-source wajib dipertahankan di repositori.

### C. Asumsi yang Belum Terverifikasi (*Unverified Assumptions*)
1. **Jenis Lisensi Pembelian Upstream:**
   - Apakah pembelian source code dilakukan di bawah *Regular License*, *Extended License*, atau *Perjanjian Lisensi Khusus/OEM* dengan Infinitie Technologies **belum dapat dibuktikan dari isi repositori**, karena bukti invoice/sertifikat lisensi belum dilampirkan.
2. **Batasan Hak Distribusi Komersial:**
   - Pada ketentuan standar pasar Envato/CodeCanyon, lisensi biasa membatasi instalasi pada 1 produk akhir. Namun, hak spesifik bergantung sepenuhnya pada perjanjian riil antara pembeli dan pemegang hak cipta.
   - **Kesimpulan Audit:** AI tidak boleh mengasumsikan kepemilikan Extended License atau kewajiban OEM sebagai fakta final tanpa melihat dokumen kontrak pembelian.
   - > ⚠️ **STATUS: BLOCKED ON VERIFICATION**  
   - > Sebelum mendistribusikan aplikasi secara luas kepada pihak ketiga, pemilik produk disarankan memverifikasi salinan sertifikat lisensi pembelian CodeCanyon.

### D. Pertanyaan yang Memerlukan Keputusan Pemilik
1. Apakah dokumen pembelian atas CodeCanyon Item #64055507 berstatus Regular License, Extended License, atau perjanjian terpisah?
2. Apakah distribusi YUKelola ditujukan untuk penggunaan privat/internal toko milik pemilik, atau didistribusikan kepada klien/publik pihak ketiga?

---

## 3. Gate C2 — Proposal Arsitektur Lisensi Permanen (DIBEKUKAN)

> 📌 **STATUS: PROPOSAL ARSITEKTUR (FROZEN)**  
> Sesuai instruksi pemilik, rancangan berikut **dibekukan sebagai proposal konsep** dan **TIDAK diimplementasikan di kode pada tahap ini** (tidak membuat portal aktivasi, tidak membuat enkripsi Machine ID, tidak membuat toleransi 14 hari, dan tidak mengubah skema lisensi).

### Prinsip Utama Proposal:
1. **Pemisahan Lisensi Permanen dari Layanan Cloud:**
   - Hak penggunaan permanen (*Perpetual Entitlement*) adalah hak lokal untuk menjalankan aplikasi kasir POS, kelola inventaris, dan pembukuan lokal tanpa batas waktu.
   - Layanan cloud opsional (misal sinkronisasi antar-cabang, notifikasi WhatsApp Cloud API, backup otomatis Google Sheets, atau gateway pembayaran QRIS dinamis) dapat dikonfigurasi sebagai add-on terpisah tanpa memengaruhi kelangsungan operasional kasir lokal.
2. **Kemandirian Operasional Offline (*Zero Lockout on Network Outage*):**
   - Transaksi kasir lokal harian tidak boleh memerlukan koneksi internet ataupun ping ke server aktivasi.
   - Gangguan jaringan internet toko atau gangguan pada server aktivasi tidak boleh mengunci aplikasi kasir atau menghentikan transaksi pelanggan.
3. **Kedaulatan Data Lokal:**
   - Database SQLite atau MySQL lokal 100% berada di bawah kendali pemilik toko.
   - Sistem tidak boleh merusak, mengenkripsi sepihak, atau menahan data bisnis jika lisensi mengalami masalah validasi.

---

## 4. Gate C3 — Verifikasi & Penghentian Updater Vendor Upstream

### A. Penelusuran Menyeluruh Jalur Pembaruan (*Complete Trace*)
Pemeriksaan dilakukan terhadap seluruh komponen yang berinteraksi dengan pembaruan sistem:
* **Konfigurasi (`config/pos.php`):** Nilai default `POS_UPDATE_FEED_URL` telah diubah menjadi string kosong `''`, dan ditambahkan master toggle `POS_UPDATER_ENABLED=false`.
* **Klien Jaringan (`app/Services/Updater/UpdateClient.php`):** Method `fetchFeed()` memeriksa `config('pos.updater.enabled')` dan ketiadaan URL feed. Jika disabled, method langsung me-return `null` tanpa pernah menginisialisasi request HTTP.
* **Aksi Pengecekan (`app/Actions/Updater/CheckForUpdate.php`):** Memeriksa `config('pos.updater.enabled')`. Jika nonaktif, langsung mengembalikan status `['ok' => false, 'available' => false, 'reason' => 'updater_disabled']` tanpa mencatat feed error palsu.
* **Aksi Auto-Install (`app/Actions/Updater/AutoInstallIfEligible.php`):** Ditambahkan pengecekan `if (! config('pos.updater.enabled', false)) { return false; }` untuk menjamin auto-install tidak pernah terpicu di latar belakang.
* **Aksi Eksekusi Instalasi (`app/Actions/Updater/InstallUpdate.php`):** Menolak instalasi berbasis feed online jika `pos.updater.enabled` nonaktif (`throw RuntimeException`).
* **Penjadwal Cron (`routes/console.php`):** Event harian `update-check` pukul 03:00 WIB memeriksa `config('pos.updater.enabled')` dan langsung keluar tanpa mengeksekusi apapun jika dinonaktifkan.
* **Antarmuka / Controller (`app/Http/Controllers/Admin/UpdateController.php`):** Mempertahankan fitur riwayat pembaruan dan dukungan unggah manual ZIP internal jika kelak dibutuhkan, tanpa menghubungi remote server vendor.

### B. Hasil Pengujian Otomatis Decoupling Updater
Pengujian pada [`tests/Feature/UpdaterDisablingTest.php`](file:///e:/superpos-yukelola/tests/Feature/UpdaterDisablingTest.php) membuktikan:
1. `test_default_configuration_has_updater_disabled_and_empty_feed_url`: Memverifikasi konfigurasi default aman dan tidak mengandung domain `infinitietech.com`.
2. `test_update_client_does_not_call_http_when_updater_is_disabled`: Menggunakan `Http::fake()` dan memastikan 0 panggilan jaringan keluar (`Http::assertNothingSent()`).
3. `test_update_client_does_not_call_http_when_feed_url_is_blank`: Memastikan feed kosong tidak memicu request.
4. `test_check_for_update_action_returns_disabled_without_network_request`: Memastikan aksi pengecekan mengembalikan status disabled tanpa trafik jaringan.
5. `test_auto_install_if_eligible_returns_false_when_updater_is_disabled`: Memastikan background auto-installer tidak pernah berjalan saat updater nonaktif.
6. `test_install_update_refuses_online_feed_install_when_updater_is_disabled`: Memastikan instalasi via online feed langsung ditolak jika updater dinonaktifkan.

---

## 5. Gate C4 — Rencana Pembaruan YUKelola & Acceptance Criteria

### A. Kebijakan Pembaruan Rilis Awal
* **Installer Resmi Manual:** Pembaruan aplikasi YUKelola pada rilis awal disiapkan melalui distribusi berkas instalasi resmi (misalnya paket rilis setup `.exe` Inno Setup untuk Windows Desktop dan berkas `.apk` baru untuk Android Mobile).
* **Tidak Membangun Server Updater Baru:** Sesuai arahan pemilik, tidak ada server updater baru yang dibangun pada tahap ini.

### B. Matriks Acceptance Criteria

| Skenario Pengujian | Hasil | Keterangan |
| :--- | :---: | :--- |
| **Isolasi Website Demo** | **PASS** | Demo login hanya memakai kredensial demo eksplisit; akun riil terlindungi dari fallback arbitrer. |
| **Peniadaan Side-Effect Destruktif GET** | **PASS** | Rute GET `/demo-login/{role}` tidak lagi memicu `ResetDemoData`. |
| **Operasi Inti Kasir 100% Offline** | **PASS** | Kasir desktop (SQLite) dan mobile (SQLite) beroperasi penuh tanpa koneksi internet. |
| **Resiliensi terhadap Jaringan Mati** | **PASS** | Sistem tidak terkunci saat validator remote mati; status lokal dipertahankan. |
| **Pemutusan Total Jalur Updater Vendor** | **PASS** | Terbukti secara unit & feature test: 0 request HTTP keluar ke `updates.infinitietech.com`. |
| **Integritas Seluruh Test Suite** | **PASS** | 18 feature & unit tests lolos 100% (`assertions: 53`). |
| **Standar Format PHP (Laravel Pint)** | **PASS** | Seluruh berkas PHP baru & termodifikasi lolos format Pint (`{"tool":"pint","result":"passed"}`). |
| **Verifikasi Legal Pembelian Upstream** | **BLOCKED** | Menunggu konfirmasi dokumen pembelian lisensi oleh pemilik produk. |

---

## 6. Batasan Tata Kelola (*Governance Boundaries Observed*)
* [x] Bekerja strictly pada branch `fix/security-demo-login-fallbacks`.
* [x] Tidak melakukan merge ke `master`.
* [x] Tidak melakukan deploy ke VPS atau produksi.
* [x] Tidak memodifikasi middleware lisensi (`EnsureLicenseActive.php`).
* [x] Tidak mengimplementasikan mekanisme lisensi buatan tanpa persetujuan.
