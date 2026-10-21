<?php

return [
    'title' => 'Golongan Obat & Regulasi',
    'sub' => 'Kode kepatuhan farmasi yang dicetak pada label obat (misal: Obat Bebas, Obat Keras, Terbatas, Narkotika / Psikotropika).',
    'crumb_parent' => 'Konfigurasi',

    'actions' => [
        'new' => 'Golongan baru',
        'save' => 'Simpan perubahan',
        'create' => 'Buat golongan',
        'discard' => 'Batalkan',
        'delete' => 'Hapus',
        'delete_confirm' => 'Hapus golongan obat ini? Jika masih ada produk yang menggunakan kode ini, penghapusan akan dicegah.',
        'close' => 'Tutup',
        'export' => 'Ekspor',
        'export_csv' => 'CSV',
        'export_xlsx' => 'Excel (XLSX)',
    ],

    'list' => [
        'title' => 'Semua Golongan',
        'empty_title' => 'Belum ada data golongan obat',
        'empty_sub' => 'Klik “Golongan baru” untuk menambahkan yang pertama.',
        'products' => 'produk',
    ],

    'row' => [
        'toggle_active' => 'Ubah status aktif',
    ],

    'editor' => [
        'empty_title' => 'Pilih golongan obat',
        'empty_sub' => 'Pilih baris untuk melihat rinciannya, atau klik “Golongan baru” untuk menambahkan.',
    ],

    'drawer' => [
        'edit_title' => 'Edit golongan obat',
        'new_title' => 'Golongan obat baru',
        'edit_id' => 'ID',
        'edit_updated' => 'diperbarui',
        'new_sub' => 'Tambahkan regulasi golongan obat yang berlaku di wilayah Anda.',
        'code' => 'Kode singkat',
        'code_placeholder' => 'misal: OTC, G, P, K',
        'code_hint' => 'Hingga 16 karakter — ditampilkan pada label produk & laporan.',
        'name' => 'Nama tampilan',
        'name_placeholder' => 'misal: Obat Keras (Daftar G)',
        'description' => 'Deskripsi',
        'description_placeholder' => 'Catatan ketentuan resep / kewenangan dispensasi obat.',
        'country_code' => 'Kode negara (ISO-2)',
        'country_code_placeholder' => 'ID, US, UK… (kosongkan untuk global)',
        'is_active' => 'Aktif (ditampilkan pada pemilih produk)',
    ],

    'errors' => [
        'title' => 'Silakan periksa bidang yang ditandai merah',
        'has_products' => 'Tidak dapat menghapus “:name” — masih ada :count produk yang menggunakan golongan ini. Pindahkan ke golongan lain terlebih dahulu.',
    ],

    'delete_dialog' => [
        'title' => 'Hapus “:name”?',
        'no_products' => 'Tidak ada produk yang menggunakan golongan ini. Aman untuk dihapus.',
        'has_products' => ':count produk terdaftar dengan golongan ini. Pilih golongan baru untuk memindahkannya.',
        'move_label' => 'Pindahkan produk ke…',
        'move_placeholder' => 'Cari golongan obat',
        'clear_hint' => 'Kosongkan jika ingin menghapus penandaan golongan obat dari produk-produk ini.',
        'confirm' => 'Hapus golongan obat',
        'cancel' => 'Batal',
    ],

    'actions_extra' => [
        'saving' => 'Menyimpan…',
        'creating' => 'Membuat…',
    ],

    'flash' => [
        'created' => 'Golongan obat “:name” berhasil dibuat.',
        'updated' => 'Golongan obat “:name” berhasil diperbarui.',
        'deleted' => 'Golongan obat “:name” berhasil dihapus.',
        'deleted_with_move' => '“:name” dihapus — :count produk dipindahkan ke “:target”.',
        'deleted_with_clear' => '“:name” dihapus — golongan obat dihapus dari :count produk.',
    ],
];
