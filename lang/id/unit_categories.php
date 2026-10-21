<?php

return [
    'title' => 'Kategori Satuan',
    'sub' => 'Kelompokkan jenis pengukuran satuan produk Anda (jumlah, berat, volume…).',

    'crumb_parent' => 'Produk',

    'list' => [
        'title' => 'Kategori Pengukuran',
        'empty_title' => 'Belum ada kategori satuan',
        'empty_sub' => 'Tambahkan kategori untuk mengelompokkan satuan pengukuran Anda.',
    ],

    'editor' => [
        'empty_title' => 'Pilih kategori',
        'empty_sub' => 'Pilih kategori dari daftar untuk mengubahnya, atau buat kategori baru.',
        'edit_title' => 'Ubah kategori',
        'edit_id' => 'ID',
        'edit_updated' => 'diperbarui',
        'new_title' => 'Kategori baru',
        'new_sub' => 'Lengkapi data kategori satuan lalu simpan.',
    ],

    'fields' => [
        'name' => 'Nama Kategori',
        'name_placeholder' => 'cth. Berat',
        'slug' => 'Slug (ID Sistem)',
        'slug_placeholder' => 'cth. berat',
        'slug_help' => 'Digunakan oleh sistem. Hanya huruf, angka, dan strip (-). Tidak dapat diubah setelah memiliki satuan.',
        'sort_order' => 'Nomor Urutan',
        'is_active' => 'Aktif (ditampilkan pada pemilihan satuan)',
    ],

    'actions' => [
        'new' => 'Kategori Baru',
        'save' => 'Simpan',
        'create' => 'Buat',
        'discard' => 'Batalkan',
        'delete' => 'Hapus',
        'export' => 'Ekspor',
        'export_csv' => 'Ekspor CSV',
        'export_xlsx' => 'Ekspor Excel',
        'delete_confirm' => 'Tindakan ini akan menghapus kategori ini secara permanen. Jika masih ada satuan di dalamnya, Anda harus memindahkannya terlebih dahulu.',
    ],

    'actions_extra' => [
        'saving' => 'Menyimpan…',
        'creating' => 'Membuat…',
    ],

    'flash' => [
        'created' => 'Kategori ":name" berhasil dibuat.',
        'updated' => 'Kategori ":name" berhasil diperbarui.',
        'deleted' => 'Kategori ":name" berhasil dihapus.',
    ],

    'errors' => [
        'in_use' => 'Tidak dapat menghapus ":name" — :count satuan masih menggunakan kategori ini.',
    ],

    'badges' => [
        'inactive' => 'Tidak Aktif',
    ],
];
