<?php

return [
    'title' => 'Pembayaran Pelanggan',
    'sub' => 'Pelunasan piutang penjualan pelanggan. Satu pembayaran dapat melunasi beberapa transaksi; sisa dana yang belum dialokasikan akan disimpan sebagai kredit pelanggan untuk transaksi mendatang.',

    'summary' => [
        'total' => 'Total diterima',
        'count' => 'Pembayaran',
        'customers' => 'Pelanggan',
    ],
    'new' => 'Catat pembayaran',
    'new_sub' => 'Pilih pelanggan untuk melihat tagihan terbuka mereka, lalu bagi pembayaran ke tagihan tersebut atau gunakan Alokasi Otomatis.',
    'crumb_parent' => 'Pelanggan',

    'unallocated_credit' => 'Kredit pelanggan',

    'validation_attributes' => [
        'client_uuid' => 'identitas pembayaran',
    ],

    'list' => [
        'title' => 'Pembayaran',
    ],

    'empty_state' => [
        'title' => 'Belum ada pembayaran pelanggan',
        'sub' => 'Setelah Anda mencatat pembayaran, riwayatnya akan muncul di sini.',
    ],

    'filter' => [
        'customer_all' => 'Semua pelanggan',
        'from' => 'Dari',
        'to' => 'Sampai',
    ],

    'columns' => [
        'date' => 'Tanggal',
        'customer' => 'Pelanggan',
        'sale' => 'Penjualan',
        'method' => 'Metode',
        'reference' => 'Referensi',
        'amount' => 'Jumlah',
    ],

    'sections' => [
        'header' => 'Informasi pembayaran',
        'header_sub' => 'Siapa yang membayar, kapan, dan metode apa yang digunakan.',
        'allocations' => 'Alokasi Pembayaran',
        'allocations_sub' => 'Bagi pembayaran ke tagihan terbuka milik pelanggan.',
        'total' => 'Total',
        'notes' => 'Catatan',
    ],

    'fields' => [
        'customer' => 'Pelanggan',
        'due' => 'Jatuh tempo',
        'date' => 'Tanggal pembayaran',
        'method' => 'Metode pembayaran',
        'reference' => 'Referensi',
        'reference_help' => 'No. cek, mutasi bank, atau bukti pembayaran lainnya.',
        'notes' => 'Catatan',
        'recorded_by' => 'Dicatat oleh',
    ],

    'totals' => [
        'amount' => 'Jumlah yang dibayarkan',
        'credit' => 'Kredit pelanggan',
        'help' => 'Alokasikan penerimaan dengan Alokasi Otomatis atau tombol Lunasi di setiap baris. Sisanya akan menjadi kredit pelanggan.',
        'credit_help' => 'Jumlah yang belum dialokasikan ke tagihan menjadi kredit pelanggan untuk transaksi mendatang.',
    ],

    'allocations' => [
        'pick_customer' => 'Pilih pelanggan untuk melihat daftar tagihan terbuka mereka.',
        'loading' => 'Memuat tagihan terbuka…',
        'none' => 'Pelanggan ini tidak memiliki tagihan terbuka — semua penjualan telah lunas.',
        'auto_amount' => 'Jumlah yang dialokasikan',
        'auto' => 'Alokasi otomatis (FIFO)',
        'auto_help' => 'Mendistribusikan jumlah yang dimasukkan ke tagihan terlama terlebih dahulu. Anda dapat mengedit baris mana saja setelahnya.',
        'auto_leftover' => 'Tidak dapat dialokasikan — kelebihan bayar akan disimpan sebagai kredit pelanggan:',
        'clear' => 'Bersihkan',
        'pay_in_full' => 'Lunasi penuh',
        'columns' => [
            'number' => 'No. Penjualan',
            'date' => 'Tanggal Penjualan',
            'balance' => 'Sisa Tagihan',
            'amount' => 'Jumlah',
            'actions' => 'Aksi',
        ],
    ],

    'show' => [
        'title' => 'Pembayaran #:id',
        'details' => 'Rincian pembayaran',
        'sibling_allocations' => 'Alokasi lain dalam pembayaran ini',
        'sibling_allocations_sub' => 'Penjualan yang dilunasi melalui pengajuan pembayaran yang sama.',
    ],

    'actions' => [
        'record' => 'Catat pembayaran',
        'recording' => 'Mencatat…',
        'discard' => 'Batalkan',
    ],

    'flash' => [
        'created' => 'Pembayaran berhasil dicatat.',
    ],
];
