<?php

return [
    'title' => 'Alasan Pengembalian / Retur',
    'sub' => 'Daftar pilihan alasan retur yang dapat dipilih kasir saat memproses pengembalian dana. Digunakan pada laporan analisis retur barang.',
    'new' => 'Alasan baru',

    'list' => [
        'empty' => 'Belum ada alasan pengembalian.',
        'title' => 'Daftar Alasan',
    ],

    'columns' => [
        'code' => 'Kode',
        'name' => 'Nama',
        'sort_order' => 'Urutan',
        'default_restock' => 'Kembalikan stok bawaan',
        'actions' => 'Aksi',
    ],

    'badges' => [
        'active' => 'Aktif',
        'inactive' => 'Nonaktif',
        'restock_default' => 'Stok dikembalikan bawaan',
        'no_restock' => 'Stok TIDAK dikembalikan bawaan',
    ],

    'fields' => [
        'code' => 'Kode',
        'code_help' => 'Pengenal singkat yang stabil, misal: `rusak` atau `salah_barang`. Digunakan secara internal; kasir hanya melihat namanya.',
        'name' => 'Nama',
        'sort_order' => 'Urutan tampilan',
        'default_restock' => 'Kembalikan barang ke stok secara bawaan',
        'default_restock_help' => 'Jika alasan ini dipilih, tombol saklar kembalikan stok pada formulir retur akan aktif secara otomatis. Kasir tetap dapat mengubahnya per baris.',
        'requires_permission' => 'Memerlukan persetujuan manajer',
        'requires_permission_help' => 'Disediakan untuk alur kerja verifikasi / otorisasi manajer di masa mendatang.',
        'is_active' => 'Aktif',
    ],

    'editor' => [
        'empty_title' => 'Pilih alasan untuk diedit',
        'empty_sub' => 'Atau klik "Alasan baru" untuk menambahkan.',
        'new_title' => 'Alasan pengembalian baru',
        'new_sub' => 'Akan langsung muncul pada menu dropdown di formulir pengembalian dana kasir.',
        'edit_title' => 'Edit alasan',
        'edit_id' => 'ID',
        'edit_updated' => 'diperbarui',
    ],

    'actions' => [
        'create' => 'Buat alasan',
        'creating' => 'Membuat…',
        'save' => 'Simpan',
        'saving' => 'Menyimpan…',
        'discard' => 'Batalkan',
        'delete' => 'Hapus alasan',
        'delete_confirm' => 'Hapus alasan ini? Retur di masa lalu tetap menyimpan nama alasan pada bukti struk mereka.',
    ],

    'flash' => [
        'created' => 'Alasan ":name" berhasil dibuat.',
        'updated' => 'Alasan ":name" berhasil diperbarui.',
        'deleted' => 'Alasan ":name" berhasil dihapus.',
    ],

    'errors' => [
        'in_use' => 'Tidak dapat menghapus ":name" — sudah digunakan pada :count transaksi retur. Nonaktifkan saja jika tidak ingin digunakan lagi.',
    ],
];
