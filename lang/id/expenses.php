<?php

return [
    'title' => 'Biaya & Pengeluaran',
    'sub' => 'Uang keluar operasional — sewa, listrik, air, perlengkapan toko, dan biaya rutin lainnya.',
    'crumb_parent' => 'Pembelian & Pemasok',
    'new' => 'Catat pengeluaran baru',

    'list' => [
        'title' => 'Daftar Pengeluaran',
    ],

    'summary' => [
        'count' => 'Jumlah transaksi',
        'total' => 'Total pengeluaran',
    ],

    'columns' => [
        'number' => 'Nomor',
        'date' => 'Tanggal',
        'category' => 'Kategori',
        'payment_method' => 'Dibayar via',
        'supplier' => 'Pemasok / Vendor',
        'amount' => 'Jumlah',
        'actions' => 'Aksi',
    ],

    'filter' => [
        'all_categories' => 'Semua kategori',
        'from' => 'Dari',
        'to' => 'Sampai',
        'search' => 'Cari nomor, referensi, catatan…',
    ],

    'empty_state' => [
        'title' => 'Belum ada pengeluaran',
        'sub' => 'Catat biaya pengeluaran pertama Anda untuk melacak arus kas keluar.',
    ],

    'sections' => [
        'details' => 'Rincian Pengeluaran',
        'details_sub' => 'Apa yang dibayarkan, kapan, dan menggunakan metode apa.',
    ],

    'fields' => [
        'date' => 'Tanggal',
        'category' => 'Kategori',
        'no_category' => '— Tanpa kategori —',
        'manage_categories' => 'Kelola kategori',
        'amount' => 'Jumlah',
        'tax_amount' => 'Pajak',
        'payment_method' => 'Dibayar via',
        'no_payment_method' => '— Tidak ditentukan —',
        'supplier' => 'Pemasok / Vendor',
        'no_supplier' => '— Tanpa pemasok —',
        'reference' => 'Referensi',
        'reference_help' => 'Nomor nota / kuitansi / faktur tagihan.',
        'description' => 'Catatan / Keterangan',
    ],

    'actions' => [
        'export' => 'Ekspor',
        'export_csv' => 'Ekspor sebagai CSV',
        'export_xlsx' => 'Ekspor sebagai Excel',
        'filter' => 'Filter',
        'delete' => 'Hapus pengeluaran',
        'discard' => 'Batalkan',
        'save' => 'Simpan perubahan',
        'create' => 'Catat pengeluaran',
    ],

    'confirm_delete' => [
        'title' => 'Hapus pengeluaran :number?',
        'message' => 'Ini akan menghapus catatan pengeluaran dari pembukuan. Tindakan ini tidak dapat dibatalkan.',
        'confirm' => 'Hapus',
    ],

    'flash' => [
        'created' => 'Pengeluaran :number berhasil dicatat.',
        'created_with_pay_out' => 'Pengeluaran :number dicatat. Uang sebesar :amount diambil dari kas laci toko Anda.',
        'updated' => 'Pengeluaran :number berhasil diperbarui.',
        'deleted' => 'Pengeluaran :number berhasil dihapus.',
    ],

    'till' => [
        'pay_out_reason' => 'Pengeluaran :number — :category',
        'correction_reason' => 'Pengeluaran :number diubah — uang kas dikembalikan ke laci',
        'uncategorised' => 'tanpa kategori',
        'notice' => 'Anda memiliki laci kasir terbuka di toko ini. Mencatat pengeluaran ini sebagai kas tunai akan mengambil uang langsung dari laci kasir, sehingga perkiraan kas akhir shift akan berkurang sesuai nominal pengeluaran.',
        'linked_pay_out' => 'Dibayar dari laci kasir',
        'over_drawer' => 'Nominal tersebut melebihi uang tunai yang ada di laci kasir Anda. Kurangi jumlahnya atau gunakan metode pembayaran lain. Kas tersedia:',
    ],
];
