<?php

return [

    'title' => 'Profil & Preferensi',
    'sub' => 'Kelola detail akun Anda, toko default, dan kata sandi.',

    'demo' => [
        'locked' => 'Profil Anda tidak dapat diubah dalam mode demo.',
        'password_locked' => 'Kata sandi tidak dapat diubah dalam mode demo.',
        'locked_banner' => 'Mode demo: profil, preferensi, dan kata sandi Anda bersifat hanya-baca. Perubahan Anda tidak akan disimpan.',
    ],

    'sections' => [
        'profile' => 'Profil',
        'profile_sub' => 'Nama lengkap, kontak, dan foto profil Anda.',
        'preferences' => 'Preferensi',
        'preferences_sub' => 'Pengaturan preferensi antarmuka untuk akun Anda.',
        'password' => 'Kata Sandi',
        'password_sub' => 'Ubah kata sandi yang Anda gunakan untuk masuk.',
        'sessions' => 'Perangkat yang Masuk',
        'sessions_sub' => 'Daftar perangkat yang saat ini masuk ke akun Anda. Keluarkan perangkat lain jika Anda melihat aktivitas yang mencurigakan.',
    ],

    'fields' => [
        'avatar' => 'Foto Profil',
        'avatar_hint' => 'Gambar persegi lebih disarankan. Maksimal 8 MB.',
        'name' => 'Nama Lengkap',
        'email' => 'Email',
        'phone' => 'No. Telepon / WhatsApp',
        'language' => 'Bahasa',
        'language_system' => 'Default Sistem',
        'default_store' => 'Toko Default',
        'default_store_none' => 'Tanpa default (tanyakan setiap kali masuk)',
        'current_password' => 'Kata Sandi Saat Ini',
        'new_password' => 'Kata Sandi Baru',
        'confirm_password' => 'Konfirmasi Kata Sandi Baru',
    ],

    'actions' => [
        'save' => 'Simpan Perubahan',
        'change_password' => 'Ubah Kata Sandi',
        'sign_out_others' => 'Keluarkan Perangkat Lain',
    ],

    'flash' => [
        'updated' => 'Profil berhasil diperbarui.',
        'updated_email_pending' => 'Profil diperbarui. Silakan periksa :email dan buka tautan konfirmasi untuk menyelesaikan perubahan email — selama belum dikonfirmasi, Anda tetap masuk menggunakan email saat ini.',
        'password_updated' => 'Kata sandi berhasil diubah.',
    ],

    // Two-step email change
    'email_change' => [
        'pending' => 'Menunggu konfirmasi ke :email. Buka tautan yang kami kirimkan ke email tersebut untuk menyelesaikan; email Anda saat ini tetap berfungsi sampai dikonfirmasi.',
        'confirmed' => 'Alamat email Anda kini telah diubah ke :email.',
        'invalid' => 'Tautan konfirmasi tidak valid atau telah kedaluwarsa. Silakan ajukan ubah email kembali melalui profil.',
        'taken' => 'Alamat email tersebut telah digunakan oleh akun lain, perubahan dibatalkan.',
        'mail' => [
            'subject' => 'Konfirmasi Alamat Email Baru Anda — :app',
            'heading' => 'Konfirmasi Alamat Email Baru Anda',
            'body' => 'Halo :name — ada permintaan untuk mengubah alamat email akun Anda ke :email. Buka tautan di bawah ini untuk mengonfirmasinya. Sebelum dikonfirmasi, akun Anda tetap menggunakan alamat email saat ini.',
            'cta' => 'Konfirmasi Alamat Email Ini',
            'expiry' => 'Tautan ini akan kedaluwarsa dalam :hours jam.',
            'ignore' => 'Jika Anda tidak meminta perubahan ini, abaikan email ini — tidak ada yang diubah dan orang lain tidak dapat masuk dengan alamat ini.',
        ],
    ],

    // Active devices
    'sessions' => [
        'columns' => [
            'device' => 'Perangkat',
            'ip' => 'Alamat IP',
            'last_active' => 'Terakhir Aktif',
        ],
        'this_device' => 'Perangkat ini',
        'none' => 'Tidak ada perangkat lain yang masuk.',
        'device' => ':browser pada :platform',
        'unknown_device' => 'Perangkat tidak dikenal',
        'unknown_browser' => 'Browser tidak dikenal',
        'confirm_password' => 'Konfirmasi kata sandi Anda',
        'wrong_password' => 'Kata sandi yang Anda masukkan salah.',
        'revoked' => '{0}Tidak ada perangkat lain yang masuk.|{1}Berhasil mengeluarkan 1 perangkat lain.|[2,*]Berhasil mengeluarkan :count perangkat lain.',
        'driver_required' => 'Pelacakan perangkat memerlukan driver sesi database. Atur SESSION_DRIVER=database di file .env Anda untuk melihat dan mengelola perangkat yang masuk.',
    ],

    'errors' => [
        'store_not_accessible' => 'Anda tidak memiliki hak akses ke toko tersebut.',
        'locale_invalid' => 'Bahasa tersebut tidak tersedia.',
    ],

];
