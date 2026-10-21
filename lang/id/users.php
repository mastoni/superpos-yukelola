<?php

return [
    'title' => 'Pengguna & Staf',
    'sub' => 'Daftar akun yang dapat masuk ke sistem POS dan panel admin. Berikan peran untuk masing-masing cabang toko.',
    'crumb_parent' => 'Konfigurasi',

    'actions' => [
        'new' => 'Pengguna baru',
        'edit' => 'Edit',
        'save' => 'Simpan perubahan',
        'create' => 'Buat pengguna',
        'delete' => 'Hapus',
        'cancel' => 'Batal',
        'discard' => 'Batalkan',
        'activate' => 'Aktifkan',
        'deactivate' => 'Nonaktifkan',
        'force_logout' => 'Keluarkan dari semua perangkat',
        'add_store' => 'Tambah akses toko',
        'remove' => 'Hapus',
        'export' => 'Ekspor',
        'export_csv' => 'CSV',
        'export_xlsx' => 'Excel (XLSX)',
    ],

    'create' => ['title' => 'Pengguna baru', 'sub' => 'Buat akun staf baru dan berikan hak akses toko.'],
    'edit' => ['title' => 'Edit pengguna', 'sub' => 'Perbarui profil pengguna, akses toko, dan status akun.'],

    'form' => [
        'profile' => 'Profil Pengguna',
        'profile_sub' => 'Identitas nama dan kontak pengguna.',
        'access' => 'Akses Cabang Toko',
        'access_sub' => 'Toko mana saja tempat mereka bertugas, beserta peran masing-masing.',
        'security' => 'Keamanan',
        'security_sub' => 'Kredensial masuk dan status keaktifan akun.',
    ],

    'fields' => [
        'name' => 'Nama lengkap',
        'name_ph' => 'Nama lengkap staf',
        'email' => 'Email',
        'email_ph' => 'nama@contoh.com',
        'phone' => 'Telepon',
        'locale' => 'Bahasa sistem',
        'locale_ph' => 'id',
        'password' => 'Kata sandi',
        'password_ph' => 'Minimal 8 karakter',
        'password_edit_help' => 'Biarkan kosong jika tidak ingin mengubah kata sandi.',
        'password_mode' => 'Metode kata sandi awal',
        'password_mode_set' => 'Tentukan kata sandi sekarang',
        'password_mode_link' => 'Kirim tautan aktivasi email (kata sandi acak sementara)',
        'is_active' => 'Aktif',
        'is_active_help' => 'Pengguna yang nonaktif tidak dapat masuk ke sistem.',
        'is_super_admin' => 'Super Admin',
        'is_super_admin_help' => 'Memiliki akses penuh ke seluruh fitur dan pengaturan tanpa batas. Berikan dengan sangat hati-hati.',
        'store' => 'Toko',
        'role' => 'Peran',
    ],

    'list' => [
        'title' => 'Semua pengguna (:count)',
        'search_ph' => 'Cari pengguna…',
        'no_stores' => 'Belum ada akses toko',
        'empty_title' => 'Belum ada pengguna',
        'empty_sub' => 'Buat akun pengguna pertama Anda untuk memberikan akses sistem kepada staf.',
    ],

    'columns' => [
        'user' => 'Pengguna',
        'access' => 'Akses Toko',
        'status' => 'Status',
        'actions' => 'Aksi',
    ],

    'badges' => [
        'super_admin' => 'Super Admin',
        'active' => 'Aktif',
        'inactive' => 'Nonaktif',
        'you' => 'Anda',
    ],

    'delete' => [
        'title' => 'Hapus “:name”?',
        'message' => 'Akun akan dihapus dan tidak dapat masuk lagi. Riwayat transaksi masa lalu tetap tersimpan utuh.',
    ],

    'force_logout' => [
        'title' => 'Keluarkan “:name” dari seluruh perangkat?',
        'message' => 'Pengguna ini akan dikeluarkan dan harus masuk (login) kembali di semua browser/perangkat. Akun tetap aktif dan kata sandi tidak berubah.',
    ],

    'flash' => [
        'created' => 'Pengguna “:name” berhasil dibuat.',
        'created_with_link' => 'Pengguna “:name” berhasil dibuat — tautan aktivasi telah dikirimkan via email untuk membuat kata sandi.',
        'created_link_failed' => 'Pengguna “:name” dibuat, namun tautan email gagal dikirim. Periksa Pengaturan → Email, lalu gunakan fitur “Lupa kata sandi”, atau edit langsung kata sandi pengguna.',
        'updated' => 'Pengguna “:name” berhasil diperbarui.',
        'deleted' => 'Pengguna “:name” berhasil dihapus.',
        'activated' => 'Pengguna “:name” diaktifkan.',
        'deactivated' => 'Pengguna “:name” dinonaktifkan.',
        'force_logged_out' => '{0}“:name” tidak sedang aktif di perangkat mana pun.|{1}Berhasil mengeluarkan “:name” dari 1 perangkat.|[2,*]Berhasil mengeluarkan “:name” dari :count perangkat.',
    ],

    'errors' => [
        'self' => 'Anda tidak dapat menonaktifkan atau menghapus akun Anda sendiri.',
        'last_super_admin' => 'Tidak dapat menghapus “:name” — merupakan satu-satunya super admin aktif yang tersisa.',
        'sessions_unsupported' => 'Fitur keluar paksa memerlukan driver sesi database. Atur SESSION_DRIVER=database pada file .env Anda.',
    ],

    'validation' => [
        'stores_required' => 'Tetapkan pengguna minimal ke satu cabang toko.',
        'store_required' => 'Silakan pilih toko pada setiap baris akses.',
        'role_required' => 'Silakan pilih peran pada setiap baris akses.',
        'role_beyond_own_privileges' => 'Anda tidak dapat memberikan peran “:role” — peran ini memiliki izin yang tidak Anda miliki sendiri.',
    ],
];
