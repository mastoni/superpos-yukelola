# YUKelola — Licensing & Third-Party Source Audit Report
**Dokumen:** `dokumentasi/audits/YUKelola-Licensing-and-Third-Party-Audit.md`  
**Tanggal Audit:** 21 Oktober 2026  
**Cabang Repositori:** `fix/security-demo-login-fallbacks`  
**Status Audit:** Gate A, B, C, D Completed — Awaiting Owner Review on Gate C Policy  

---

## 1. Eksekutif Ringkasan

Audit ini membedah arsitektur otentikasi demo, dependensi vendor pihak ketiga (*upstream vendor* Infinitie Technologies / CodeCanyon item 64055507), dan mekanisme lisensi pada basis kode **YUKelola** (diadaptasi dari Hyper POS).

Fokus utama audit:
1. **Otentikasi Demo**: Menghilangkan celah eskalasi hak akses / fallback pengguna arbitrer dan operasi destruktif GET pada demo login (**Telah Diperbaiki & Lolos Uji 100% pada Gate B**).
2. **Kepatuhan Lisensi Upstream vs Entitlement YUKelola**: Memetakan ketergantungan remote server vendor, kunci API hardcoded, dan kebutuhan operasional offline kasir ritel Indonesia.

---

## 2. Gate A — Verifikasi Status Repositori

* **Base Branch:** `master` (HEAD: `3113144`)
* **Dedicated Work Branch:** `fix/security-demo-login-fallbacks`
* **Commit Terkini:** `11b617e` (`fix(security): restrict demo login to explicit demo accounts and cover boundaries`)
* **Status Worktree:** Bersih (*Clean*)
* **Status Pengujian Awal:** 
  - `php vendor/bin/phpunit`: 12 tests passed, 39 assertions.
  - `vendor/bin/pint --dirty --format agent`: Lolos validasi style PHP.

---

## 3. Gate B — Remediasi Keamanan Demo Authentication (Selesai)

### Temuan Awal (Vulnerabilities):
1. **Generic Fallback User**: Pada implementasi awal `DemoLoginController.php`, jika akun demo spesifik tidak ditemukan, query menggunakan fallback:
   ```php
   User::where('is_super_admin', false)->first() ?? User::first();
   ```
   Hal ini sangat berbahaya karena jika akun demo terhapus, sesi login demo dapat otomatis mengotentikasi akun pemilik toko riil (*store owner*) atau pengguna produksi pertama.
2. **Operasi Destruktif pada HTTP GET**: Pemanggilan `app(ResetDemoData::class)()` dilakukan langsung di dalam rute `GET /demo-login/{role}`. Hal ini melanggar kaidah HTTP (GET harus bersifat *idempotent* dan *safe*), berpotensi menjadi celah *Denial of Service* (DoS), serta dapat menghapus transaksi evaluator lain yang sedang mencoba demo secara bersamaan.
3. **Ketiadaan Validasi Status Akun & Role Integrity**: Tidak ada validasi apakah akun demo dalam status dinonaktifkan (`is_active = false`) atau apakah akun demo admin benar-benar memiliki hak `is_super_admin`.

### Remediasi yang Diimplementasikan:
1. **Strict Designated Demo Account Resolution**: Akun demo diambil secara deterministik dari `config('pos.demo.credentials')` (default: `admin@yukelola.id` dan `kasir@yukelola.id`). Fallback `User::first()` atau `User::where(...)` tanpa filter email telah dihapus 100%.
2. **Fail-Safe Missing Account Handling**: Jika akun demo tidak ditemukan atau dinonaktifkan (`! $user->is_active`), request langsung ditolak dengan pengalihan aman ke `/login` disertai pesan kesalahan yang jelas.
3. **Role Integrity Enforcement**: 
   - Admin demo wajib memiliki `is_super_admin === true`.
   - Kasir demo wajib memiliki `is_super_admin === false`.
4. **Pembersihan Operasi Destruktif**: Pemanggilan `ResetDemoData` pada HTTP GET telah dihapus total. Operasi reset demo kini hanya dapat dipicu melalui artisan command terjadwal (`pos:demo-reset`).
5. **Session Regeneration**: Menjalankan `$request->session()->regenerate()` dan mempertahankan penanda `is_demo_session = true`.

### Cakupan Pengujian Otomatis (`tests/Feature/DemoAuthenticationTest.php`):
- `test_demo_login_fails_when_demo_mode_is_disabled`: Memastikan request 404 jika `pos.demo.enabled = false`.
- `test_demo_cashier_authenticates_designated_account_only`: Memastikan hanya akun kasir yang ditentukan yang berhasil login.
- `test_demo_admin_authenticates_designated_account_only`: Memastikan hanya akun admin yang ditentukan yang berhasil login.
- `test_demo_login_fails_safely_when_designated_account_missing`: Gagal aman jika akun tidak ditemukan di database.
- `test_no_generic_user_fallback_exists_when_designated_account_missing`: Akun pelanggan/pemilik toko asli TIDAK PERNAH terotentikasi.
- `test_invalid_role_denies_authentication`: Role tidak dikenal (misal `/demo-login/hacker`) mengembalikan 404.
- `test_deactivated_demo_account_denies_authentication`: Akun demo nonaktif (`is_active = 0`) ditolak.
- `test_role_mismatch_prevents_unauthorized_elevation`: Akun admin palsu tanpa super admin ditolak.
- `test_public_get_login_does_not_execute_destructive_reset`: Verifikasi GET login aman tanpa efek samping reset.
- `test_existing_standard_login_behavior_remains_operational`: Verifikasi login standar email/password tetap berfungsi normal.

---

## 4. Gate C — Analisis & Rekomendasi Kebijakan Lisensi

### Pemisahan Empat Aspek Lisensi:
1. **Hak Cipta & Lisensi Kode Asal (Vendor Infinitie Technologies)**:
   - Basis kode asli berasal dari item CodeCanyon #64055507 (*Hyper POS*).
   - Bergantung pada endpoint remote `https://validator-new.infinitietech.com/api/v1/validate`.
   - Mengandung API Key vendor hardcoded: `554c9a4319fd81f75586008b347f767eb9a48b6995c26b50`.
2. **Entitlement / Aktivasi Produk YUKelola**:
   - Jika YUKelola didistribusikan ke klien/toko, YUKelola belum memiliki server lisensi mandiri.
   - Peringatan Kebijakan: Jangan menerapkan sistem kadaluarsa/blokir sepihak (*invented license policy*) tanpa persetujuan pemilik produk dan tanpa server validator resmi milik YUKelola.
3. **Perilaku Mode Demo (`pos_is_demo()`)**:
   - Berfungsi untuk eksplorasi publik, diatur melalui `POS_DEMO_MODE=true` di `.env`.
   - Membatasi modifikasi data sensitif melalui `PreventDemoDataDeletion`.
4. **Operasional Offline & Pemulihan Darurat (*Offline Resilience*)**:
   - Komputer kasir di ritel/F&B Indonesia sering mengalami pemadaman internet.
   - Sistem tidak boleh mengunci (*lockout*) kasir yang sedang melayani antrean transaksi hanya karena server lisensi remote tidak dapat dihubungi (*network timeout*).
   - Data historis pelanggan dan transaksi di database lokal tidak boleh dirusak atau dienkripsi secara sepihak oleh mekanisme lisensi.

### Status Implementasi Middleware `EnsureLicenseActive`:
- Pada branch `master`, `EnsureLicenseActive.php` sebelumnya di-bypass dengan `return $next($request);` agar sistem dapat berjalan di localhost tanpa koneksi ke server Infinitie Technologies.
- **Rekomendasi Kebijakan untuk Gate C**:
  Sebelum mengaktifkan kembali validasi lisensi ketat:
  1. Pemilik repositori wajib mengonfirmasi tipe lisensi CodeCanyon yang dimiliki (Standard vs Extended).
  2. Jika YUKelola akan menggunakan server lisensi sendiri, endpoint `POS_LICENSE_CHECK_URL` harus dialihkan dari `validator-new.infinitietech.com` ke server backend YUKelola.
  3. Mekanisme offline harus mengadopsi *Grace Period* / *Cached Token Validation* sehingga kasir tetap dapat bertransaksi offline minimal 30 hari tanpa internet.

---

## 5. Gate D — Audit Kode Pihak Ketiga & Branding (*Inventory*)

| Komponen / Berkas | Nilai / Kode Saat Ini | Klasifikasi | Rekomendasi Tindakan |
|:---|:---|:---:|:---|
| `config/pos.php:88` | `https://hyper-pos.eshopweb.store/documentation/` | **Aman Diubah** | Arahkan ke dokumentasi resmi YUKelola (`https://yukelola.id/docs` atau path lokal). |
| `config/pos.php:165` | `https://updates.infinitietech.com/pos.json` | **Perlu Review** | **PENTING:** Auto-updater ke server Infinitie Technologies harus dimatikan/diubah agar update otomatis vendor tidak menimpa kode modifikasi YUKelola. |
| `config/pos.php:182` | Public key Ed25519 `xvCWtfb...` | **Perlu Review** | Kunci publik untuk memverifikasi zip update vendor. Harus diganti jika YUKelola merilis update mandiri. |
| `config/pos.php:304` | `https://validator-new.infinitietech.com/api/v1/validate` | **Perlu Review** | Endpoint validasi purchase code CodeCanyon. Pertahankan jika validasi vendor diperlukan; ganti jika menggunakan lisensi internal. |
| `config/pos.php:305` | API Key `554c9a4...` | **Perlu Review** | Kunci API vendor Infinitie Technologies. Jangan disebarkan ke publik. |
| `config/pos.php:313` | Item ID `64055507` | **Hak Cipta Vendor** | ID produk CodeCanyon. Wajib dipertahankan dalam catatan kepatuhan lisensi. |
| `app/helpers.php:354-375` | Masking telepon & email demo | **Aman Diubah** | Sudah dinetralkan untuk mode produksi agar kasir dapat melihat data kontak asli pelanggan. |
| `resources/css/tokens.css` | Warna tema Emerald Green (`#059669`) | **Branding YUKelola** | Sah dan sesuai standar visual identitas baru. |

---

## 6. Keputusan yang Memerlukan Persetujuan Pemilik (*Action Items for Owner*)

1. **Model Distribusi YUKelola**:
   * *Opsi A (Internal / Hosted SaaS)*: YUKelola di-host di server cloud terpusat milik pemilik, toko kasir mengakses via web/desktop tanpa installer mandiri. Lisensi ditangani di layer database internal YUKelola.
   * *Opsi B (On-Premise Desktop Ritel Standalone)*: Aplikasi didistribusikan sebagai file `.exe` / installer mandiri ke komputer masing-masing toko. Memerlukan sistem lisensi serial key offline yang tidak bergantung pada server Infinitie Technologies.
2. **Penonaktifan Upstream Auto-Updater**:
   * Apakah setuju untuk mematikan `POS_UPDATE_FEED_URL` ke `updates.infinitietech.com` di `config/pos.php` agar update vendor tidak menimpa source code YUKelola yang sudah diadaptasi?
3. **Kebijakan Lisensi Middleware**:
   * Apakah `EnsureLicenseActive` akan diarahkan ke server API YUKelola (`api.yukelola.id`) atau tetap menggunakan offline-first policy (berbasis status terpasang di database lokal)?
