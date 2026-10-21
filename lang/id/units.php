<?php

return [
    'title' => 'Satuan Unit',
    'sub' => 'Tentukan cara pengukuran produk — per buah (pcs), berat, atau volume.',
    'crumb_parent' => 'Katalog',

    'actions' => [
        'new' => 'Satuan baru',
        'save' => 'Simpan perubahan',
        'create' => 'Buat satuan',
        'discard' => 'Batalkan',
        'delete' => 'Hapus',
        'delete_confirm' => 'Hapus satuan ini? Jika masih digunakan oleh produk, penghapusan akan dicegah.',
        'close' => 'Tutup',
        'export' => 'Ekspor',
        'export_csv' => 'Unduh CSV',
        'export_xlsx' => 'Unduh Excel (XLSX)',
        'manage_categories' => 'Kategori Satuan',
    ],

    'list' => [
        'title' => 'Semua satuan',
        'products' => 'produk',
        'base' => 'Satuan dasar',
        'derived_of' => '1 :code = :factor :base_code',
        'empty_title' => 'Belum ada satuan',
        'empty_sub' => 'Klik “Satuan baru” untuk menambahkan satuan pertama.',
    ],

    'row' => [
        'toggle_active' => 'Alihkan status aktif',
    ],

    'editor' => [
        'empty_title' => 'Pilih satuan',
        'empty_sub' => 'Pilih baris untuk melihat detailnya, atau klik “Satuan baru” untuk menambahkan.',
    ],

    'drawer' => [
        'edit_title' => 'Ubah satuan',
        'new_title' => 'Satuan baru',
        'edit_id' => 'ID',
        'edit_updated' => 'diperbarui',
        'new_sub' => 'Tentukan bagaimana produk dihitung, ditimbang, atau diukur.',
        'code' => 'Kode singkat',
        'code_placeholder' => 'cth. kg, l, pcs',
        'code_hint' => 'Maksimal 16 karakter — ditampilkan pada pemilihan produk.',
        'name' => 'Nama satuan',
        'name_placeholder' => 'cth. Kilogram',
        'category' => 'Kategori pengukuran',
        'base_unit' => 'Satuan dasar',
        'base_unit_none' => '— Tidak ada (satuan ini ADALAH dasar)',
        'base_unit_hint' => 'Kosongkan jika ini adalah satuan dasar. Jika bukan, pilih satuan dasar konversinya.',
        'conversion_factor' => 'Faktor konversi',
        'conversion_factor_hint' => 'Berapa satuan dasar yang setara dengan 1 satuan ini (cth. 1 lusin = 12 pcs → faktor 12).',
        'is_active' => 'Aktif (ditampilkan pada pemilihan produk)',
        'category_inactive' => ':name (tidak aktif)',
    ],

    'categories' => [
        'count' => 'Hitungan (Pieces)',
        'weight' => 'Berat',
        'volume' => 'Volume',
        'length' => 'Panjang',
        'area' => 'Luas',
        'time' => 'Waktu',
    ],

    'errors' => [
        'title' => 'Silakan periksa kolom yang ditandai',
        'base_self' => 'Satuan tidak dapat menggunakan dirinya sendiri sebagai satuan dasar.',
        'category_unknown' => 'Pilih kategori pengukuran yang valid dari daftar.',
        'category_inactive' => '“:name” adalah kategori yang dinonaktifkan — pilih kategori yang aktif, atau aktifkan kembali di Kategori Satuan.',
        'has_products' => 'Tidak dapat menghapus “:name” — :count produk masih menggunakan satuan ini.',
        'has_derived_units' => 'Tidak dapat menghapus “:name” — :count satuan lain mengonversi ke satuan ini.',
    ],

    'actions_extra' => [
        'saving' => 'Menyimpan…',
        'creating' => 'Membuat…',
    ],

    'flash' => [
        'created' => 'Satuan “:name” berhasil dibuat.',
        'updated' => 'Satuan “:name” berhasil diperbarui.',
        'deleted' => 'Satuan “:name” berhasil dihapus.',
    ],
];
