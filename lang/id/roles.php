<?php

return [
    'title' => 'Peran Pengguna (Role)',
    'sub' => 'Tentukan apa saja yang dapat dilakukan oleh setiap peran. Berikan peran kepada pengguna per cabang toko.',
    'crumb_parent' => 'Konfigurasi',

    'actions' => [
        'new' => 'Peran baru',
        'edit' => 'Edit',
        'save' => 'Simpan perubahan',
        'create' => 'Buat peran',
        'delete' => 'Hapus',
        'cancel' => 'Batal',
        'export' => 'Ekspor',
        'export_csv' => 'CSV',
        'export_xlsx' => 'Excel (XLSX)',
    ],

    'create' => ['title' => 'Peran baru', 'sub' => 'Pilih hak akses izin yang diberikan untuk peran ini.'],
    'edit' => ['title' => 'Edit peran', 'sub' => 'Sesuaikan hak akses izin untuk peran ini.'],

    'fields' => [
        'name' => 'Nama peran',
        'name_ph' => 'misal: Supervisor Kasir, Kasir Toko, Pengelola Stok',
        'description' => 'Deskripsi',
        'description_ph' => 'Apa tanggung jawab utama peran ini?',
    ],

    'list' => [
        'title' => 'Semua peran (:count)',
        'search_ph' => 'Cari peran…',
        'empty_title' => 'Belum ada peran pengguna',
        'empty_sub' => 'Buat peran baru untuk mulai mengatur hak akses staf Anda.',
    ],

    'columns' => [
        'role' => 'Peran',
        'permissions' => 'Hak Akses (Izin)',
        'users' => 'Pengguna',
        'actions' => 'Aksi',
    ],

    'badges' => [
        'system' => 'Sistem',
    ],

    'matrix' => [
        'select_all' => 'Pilih semua',
        'selected_count' => ':count dipilih',
        'dangerous' => 'Tindakan Sensitif',
        'dangerous_hint' => 'Izin-izin ini dapat memengaruhi penagihan, keamanan, atau integritas data sistem. Berikan dengan hati-hati.',
        'group_count' => ':selected / :total',
    ],

    'delete' => [
        'title' => 'Hapus “:name”?',
        'message' => 'Peran akan dihapus secara permanen. Tindakan ini tidak dapat dibatalkan.',
    ],

    'flash' => [
        'created' => 'Peran “:name” berhasil dibuat.',
        'updated' => 'Peran “:name” berhasil diperbarui.',
        'deleted' => 'Peran “:name” berhasil dihapus.',
    ],

    'errors' => [
        'assigned' => 'Tidak dapat menghapus “:name” — masih ada :count pengguna yang memiliki peran ini. Pindahkan mereka ke peran lain terlebih dahulu.',
    ],

    'validation' => [
        'beyond_own_privileges' => 'Anda hanya dapat memberikan hak akses yang Anda miliki sendiri. Hapus: :permissions',
    ],
];
