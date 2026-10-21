<?php

return [
    'title' => 'Kategori Biaya Pengeluaran',
    'sub' => 'Pengelompokan jenis pengeluaran operasional usaha Anda. Tambah, ubah nama, atau nonaktifkan kapan saja.',
    'new' => 'Kategori baru',

    'list' => [
        'title' => 'Daftar Kategori',
        'empty' => 'Belum ada kategori pengeluaran',
    ],

    'columns' => [
        'name' => 'Nama Kategori',
        'actions' => 'Aksi',
    ],

    'editor' => [
        'empty_title' => 'Pilih kategori',
        'empty_sub' => 'Pilih satu untuk diedit, atau tambahkan kategori pengeluaran baru.',
        'new_title' => 'Kategori baru',
        'edit_title' => 'Edit kategori',
    ],

    'fields' => [
        'name' => 'Nama Kategori',
        'is_active' => 'Aktif',
    ],

    'actions' => [
        'toggle_active' => 'Ubah status aktif',
        'delete' => 'Hapus',
        'discard' => 'Batalkan',
        'save' => 'Simpan perubahan',
        'saving' => 'Menyimpan…',
        'create' => 'Tambah kategori',
        'creating' => 'Menambahkan…',
    ],

    'flash' => [
        'created' => 'Kategori “:name” berhasil ditambahkan.',
        'updated' => 'Kategori “:name” berhasil diperbarui.',
        'deleted' => 'Kategori “:name” berhasil dihapus.',
    ],

    'errors' => [
        'in_use' => '“:name” digunakan oleh :count transaksi pengeluaran. Nonaktifkan saja alih-alih menghapusnya.',
    ],
];
