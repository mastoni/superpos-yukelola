<?php

return [
    'title' => 'Alasan Selisih Kas',
    'sub' => 'Daftar pilihan alasan yang dipilih kasir saat menutup shift kasir dengan selisih uang tunai. Shift yang ditutup menyimpan alasan ini pada laporan Z.',
    'new' => 'Alasan baru',

    'list' => [
        'title' => 'Daftar Alasan',
        'empty' => 'Belum ada alasan selisih kas.',
    ],

    'columns' => [
        'code' => 'Kode',
        'name' => 'Nama',
        'sort_order' => 'Urutan',
        'actions' => 'Aksi',
    ],

    'badges' => [
        'active' => 'Aktif',
        'inactive' => 'Nonaktif',
    ],

    'editor' => [
        'empty_title' => 'Pilih alasan',
        'empty_sub' => 'Pilih baris untuk mengedit, atau buat alasan baru.',
        'edit_title' => 'Edit alasan',
        'edit_id' => 'ID',
        'edit_updated' => 'diperbarui',
        'new_title' => 'Alasan baru',
        'new_sub' => 'Tambahkan alasan selisih kas baru ke daftar tutup shift.',
    ],

    'fields' => [
        'code' => 'Kode',
        'code_help' => 'Pengenal internal — singkat, huruf kecil dengan garis bawah, digunakan dalam laporan.',
        'name' => 'Nama tampilan',
        'sort_order' => 'Urutan tampilan',
        'is_active' => 'Aktif',
    ],

    'actions' => [
        'discard' => 'Batalkan',
        'save' => 'Simpan',
        'saving' => 'Menyimpan…',
        'create' => 'Buat',
        'creating' => 'Membuat…',
        'delete' => 'Hapus',
    ],

    'flash' => [
        'created' => ':name berhasil ditambahkan.',
        'updated' => ':name berhasil disimpan.',
        'deleted' => ':name berhasil dihapus.',
    ],

    'errors' => [
        'in_use' => ':name digunakan oleh :count shift kasir. Nonaktifkan saja alih-alih menghapusnya agar laporan Z masa lalu tetap akurat.',
    ],
];
