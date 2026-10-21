<?php

return [
    'title' => 'Cabang Toko',
    'sub' => 'Lokasi fisik dan cabang tempat bisnis Anda beroperasi. Stok barang, harga jual, dan penjualan dipantau per toko.',
    'crumb_parent' => 'Konfigurasi',

    'actions' => [
        'new' => 'Toko baru',
        'edit' => 'Edit',
        'save' => 'Simpan perubahan',
        'create' => 'Buat toko',
        'delete' => 'Hapus',
        'cancel' => 'Batal',
        'activate' => 'Aktifkan',
        'deactivate' => 'Nonaktifkan',
        'toggle_active' => 'Ubah status aktif',
        'switch' => 'Beralih',
        'switch_to' => 'Beralih ke :name',
        'set_default' => 'Jadikan toko utama (default)',
        'export' => 'Ekspor',
        'export_csv' => 'Ekspor CSV',
        'export_xlsx' => 'Ekspor Excel',
    ],

    'create' => [
        'title' => 'Tambah toko baru',
        'sub' => 'Tambahkan cabang atau lokasi baru ke perusahaan Anda.',
    ],
    'edit' => [
        'title' => 'Edit cabang toko',
        'sub' => 'Perbarui rincian identitas dan konfigurasi toko ini.',
    ],

    'form' => [
        'identity' => 'Identitas',
        'identity_sub' => 'Nama dan kode referensi toko.',
        'address' => 'Alamat',
        'address_sub' => 'Dicetak pada struk belanja dan laporan.',
        'localization' => 'Lokalisasi',
        'localization_sub' => 'Mata uang, zona waktu, dan aturan pembulatan untuk toko ini.',
        'behavior' => 'Operasional',
        'behavior_sub' => 'Pengaturan shift kasir, perpajakan, dan keaktifan toko.',
    ],

    'fields' => [
        'code' => 'Kode Toko',
        'code_placeholder' => 'UTAMA',
        'code_help' => 'Singkat dan unik. Muncul pada struk dan nomor transaksi penjualan.',
        'name' => 'Nama Toko',
        'name_placeholder' => 'Toko Utama',
        'phone' => 'Telepon',
        'email' => 'Email',
        'address_line1' => 'Alamat baris 1',
        'address_line2' => 'Alamat baris 2',
        'city' => 'Kota',
        'state' => 'Provinsi / Wilayah',
        'postal_code' => 'Kode pos',
        'country_code' => 'Negara',
        'country_code_placeholder' => 'ID',
        'currency' => 'Mata uang',
        'timezone' => 'Zona waktu',
        'locale' => 'Bahasa sistem',
        'locale_placeholder' => 'id',
        'rounding_mode' => 'Metode pembulatan',
        'enforce_shifts' => 'Wajibkan shift kasir',
        'enforce_shifts_help' => 'Kasir harus membuka shift sebelum dapat melayani transaksi penjualan di toko ini.',
        'discount_threshold' => 'Batas maksimal diskon tanpa persetujuan (%)',
        'discount_threshold_help' => 'Diskon di atas persentase ini membutuhkan izin khusus atau otorisasi manajer.',
        'tax_inclusive_pricing' => 'Harga jual sudah termasuk pajak',
        'tax_inclusive_pricing_help' => 'Harga barang yang dimasukkan untuk toko ini sudah mencakup nilai pajak.',
        'is_active' => 'Aktif',
        'is_active_help' => 'Toko nonaktif tetap menyimpan riwayat data lamanya, namun tidak menerima transaksi baru.',
        'is_default' => 'Toko utama (default)',
        'is_default_help' => 'Digunakan sebagai patokan harga saat tidak ada harga khusus toko, dan menjadi toko awal bagi staf baru. Hanya satu toko yang dapat menjadi toko utama.',
    ],

    'rounding' => [
        'half_up' => 'Bulatkan ke atas (setengah ke atas)',
        'half_even' => 'Bulatkan ke genap terdekat (metode bankir)',
        'nearest_05' => 'Bulatkan ke kelipatan 0.05 terdekat',
    ],

    'list' => [
        'title' => 'Semua cabang toko (:count)',
        'search_placeholder' => 'Cari toko…',
        'tip_active_on' => 'Aktif — klik untuk menonaktifkan',
        'tip_active_off' => 'Nonaktif — klik untuk mengaktifkan',
        'empty_title' => 'Belum ada cabang toko',
        'empty_sub' => 'Buat toko pertama Anda untuk mulai mengelola stok dan transaksi penjualan.',
        'filter_all' => 'Semua',
        'filter_active' => 'Aktif',
        'filter_inactive' => 'Nonaktif',
    ],

    'columns' => [
        'store' => 'Toko',
        'location' => 'Lokasi',
        'currency' => 'Mata Uang',
        'timezone' => 'Zona Waktu',
        'users' => 'Pengguna',
        'status' => 'Status',
        'actions' => 'Aksi',
    ],

    'badges' => [
        'current' => 'Toko Aktif Saat Ini',
        'active' => 'Aktif',
        'inactive' => 'Nonaktif',
        'default' => 'Utama (Default)',
    ],

    'delete' => [
        'title' => 'Hapus “:name”?',
        'message' => 'Toko akan dihapus secara soft-delete. Seluruh riwayat transaksi masa lalu tetap tersimpan utuh.',
    ],

    'flash' => [
        'created' => 'Toko “:name” berhasil dibuat.',
        'updated' => 'Toko “:name” berhasil diperbarui.',
        'deleted' => 'Toko “:name” berhasil dihapus.',
        'activated' => 'Toko “:name” diaktifkan.',
        'deactivated' => 'Toko “:name” dinonaktifkan.',
        'switched' => 'Beralih ke toko “:name”.',
        'default_set' => '“:name” sekarang menjadi toko utama.',
    ],

    'errors' => [
        'last_store' => 'Tidak dapat menghapus “:name” — perusahaan harus memiliki minimal satu cabang toko aktif.',
        'has_data' => 'Tidak dapat menghapus “:name” — memiliki data penjualan, stok, atau catatan transaksi. Nonaktifkan saja.',
        'last_active' => 'Tidak dapat menonaktifkan “:name” — minimal harus ada satu toko yang berstatus aktif.',
        'default_store' => 'Tidak dapat menonaktifkan “:name” — merupakan toko utama. Tetapkan toko lain sebagai toko utama terlebih dahulu.',
        'has_open_purchases' => 'Tidak dapat menonaktifkan “:name” — masih memiliki transaksi pembelian yang belum selesai. Selesaikan transaksi tersebut terlebih dahulu.',
    ],

    'switcher' => [
        'label' => 'Toko',
        'none' => 'Tidak ada toko',
        'heading' => 'Pilih Cabang Toko',
        'manage' => 'Kelola cabang toko',
    ],
];
