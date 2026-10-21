# Panduan Pembuatan & Penggunaan Windows Installer SuperPOS

Dokumen ini menjelaskan arsitektur dan tata cara membuat serta menjalankan installer aplikasi **SuperPOS** untuk sistem operasi Windows (Windows 10 / Windows 11).

---

## 1. Arsitektur Desktop Windows

Aplikasi SuperPOS dikemas agar dapat beroperasi layaknya aplikasi desktop native di komputer kasir tanpa memerlukan browser biasa yang terbuka dengan address bar/tab:

1. **Jendela Aplikasi Native (Edge WebView2 / Chrome App Mode)**:
   - Menggunakan Microsoft Edge WebView2 bawaan Windows 10 & 11 atau Google Chrome dalam mode `--app=http://127.0.0.1:8765`.
   - Menghasilkan jendela aplikasi mandiri, bersih, tanpa address bar, tanpa tombol bookmark/ekstensi browser yang mengganggu kasir.
2. **Background Engine**:
   - Menjalankan server lokal mandiri pada port `8765`.
   - Menggunakan launcher VBScript (`build-windows/superpos-launcher.vbs`) sehingga jendela command prompt hitam (CMD) **tidak pernah muncul** ke hadapan kasir.
3. **Database Mandiri (SQLite)**:
   - Menggunakan database file lokal `database/database.sqlite` sehingga toko dapat beroperasi secara **100% offline** tanpa perlu memasang XAMPP/MySQL terpisah.
4. **Lifecycle Manager**:
   - Ketika kasir menutup jendela aplikasi SuperPOS, background server otomatis berhenti (`taskkill`) secara bersih sehingga tidak membebani memori RAM komputer.

---

## 2. Struktur Berkas Paket Windows

Folder `build-windows/` telah disiapkan dengan berkas-berkas otomasi:

| Berkas | Fungsi |
|---|---|
| `build-windows/start-superpos.bat` | Script startup utama: mengecek database, menyalakan background PHP server, dan membuka jendela desktop Edge/Chrome. |
| `build-windows/superpos-launcher.vbs` | Launcher senyap (silent runner) yang memanggil script batch tanpa jendela terminal hitam. |
| `build-windows/stop-superpos.bat` | Script pemutus background process saat aplikasi selesai digunakan. |
| `build-windows/.env.desktop` | Template file `.env` yang sudah disetel untuk offline standalone desktop (SQLite + production). |
| `build-windows/superpos-installer.iss` | Script kompilasi **Inno Setup** untuk merangkai semua berkas menjadi satu file setup executable (`SuperPOS_Setup_v1.1.4.exe`). |
| `build-windows/build-installer.ps1` | Script satu-klik PowerShell untuk mengompilasi frontend, memverifikasi dependensi, dan membuat file setup installer atau zip portable. |
| `Buka-SuperPOS.bat` | Shortcut langsung di root folder untuk mencoba menjalankan aplikasi secara desktop. |

---

## 3. Cara Membuat File Installer (`.exe`)

### Prasyarat:
- Komputer pengembang memiliki Node.js dan PHP.
- Unduh dan pasang compiler **Inno Setup 6** (gratis & open-source):
  👉 [https://jrsoftware.org/isdl.php](https://jrsoftware.org/isdl.php)

### Langkah Pembuatan:
1. Buka PowerShell di folder project:
   ```powershell
   cd e:\superpos-yukelola
   ```
2. Jalankan script build:
   ```powershell
   powershell -ExecutionPolicy Bypass -File build-windows\build-installer.ps1
   ```
3. Script akan otomatis:
   - Mengompilasi aset antarmuka Vite (`npm run build`).
   - Menyiapkan database SQLite dan view cache.
   - Memanggil `ISCC.exe` (Inno Setup Compiler).
4. Hasil installer akan tersimpan di:
   ```
   build-windows\output\SuperPOS_Setup_v1.1.4.exe
   ```

> **Catatan jika belum memasang Inno Setup**:
> Script secara otomatis akan membuat file **`SuperPOS_Windows_Portable_v1.1.4.zip`** di folder `output/` yang siap diekstrak dan langsung digunakan di komputer mana pun tanpa perlu instalasi tambahan.

---

## 4. Pengalaman Pengguna (Kasir & Pemilik Toko)

1. Pengguna membuka file `SuperPOS_Setup_v1.1.4.exe`.
2. Wizard instalasi berbahasa Indonesia memandu pemilihan lokasi instalasi (default: `C:\Program Files\SuperPOS` atau `%LOCALAPPDATA%\SuperPOS`).
3. Opsi membuat icon shortcut di Desktop dan Start Menu.
4. Klik **Selesai (Finish)**: Aplikasi langsung terbuka di jendela desktop modern dengan tema visual hijau BukuWarung (`#059669`) dan font Plus Jakarta Sans.
5. Tersedia uninstaller resmi di menu **Settings > Apps / Control Panel Windows** untuk menghapus aplikasi secara bersih jika diperlukan.
