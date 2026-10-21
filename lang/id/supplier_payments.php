<?php

return [
    'title' => 'Pembayaran Pemasok',
    'sub' => 'Pembayaran dan kredit pemasok yang diterapkan untuk tagihan pembelian yang belum lunas.',

    'summary' => [
        'total' => 'Total dibayar',
        'count' => 'Entri',
        'suppliers' => 'Pemasok',
    ],
    'crumb_parent' => 'Pasokan',
    'new' => 'Catat pembayaran',
    'new_sub' => 'Bayar satu atau beberapa tagihan pembelian pemasok. Alokasikan nominal ke tagihan pembelian secara manual atau otomatis (FIFO).',

    'list' => [
        'title' => 'Semua pembayaran (:count)',
        'unallocated_credit' => 'Kredit pemasok',
    ],

    'credit_application' => [
        'method' => 'Kredit pemasok',
        'note' => 'Kredit pemasok diterapkan otomatis ke :number.',
    ],

    'badges' => [
        'voided' => 'Dibatalkan',
    ],

    'void' => [
        'action' => 'Batalkan pembayaran',
        'title' => 'Rincian pembatalan',
        'confirm_title' => 'Batalkan pembayaran ini?',
        'confirm_body' => 'Mengembalikan dampak kas — pembelian terkait akan dibuka kembali dan hutang ke pemasok akan bertambah lagi. Baris akan tetap ada di riwayat dengan tanda dibatalkan.',
        'voided_at' => 'Dibatalkan pada',
        'voided_by' => 'Dibatalkan oleh',
        'reason' => 'Alasan',
    ],

    'columns' => [
        'date' => 'Tanggal',
        'supplier' => 'Pemasok',
        'purchase' => 'Pembelian',
        'method' => 'Metode',
        'reference' => 'Referensi',
        'amount' => 'Jumlah',
    ],

    'filter' => [
        'supplier_all' => 'Semua pemasok',
        'method_all' => 'Semua metode',
        'from' => 'Dari',
        'to' => 'Sampai',
        'search' => 'Cari referensi, pemasok, no. pembelian…',
    ],

    'empty_state' => [
        'title' => 'Belum ada pembayaran',
        'sub' => 'Catat pembayaran pertama Anda ke pemasok.',
    ],

    'sections' => [
        'header' => 'Rincian pembayaran',
        'header_sub' => 'Pemasok, tanggal, dan metode — informasi metadata pembayaran.',
        'allocations' => 'Alokasikan ke pembelian',
        'allocations_sub' => 'Tentukan berapa banyak pembayaran ini dialokasikan ke masing-masing pembelian terbuka.',
        'total' => 'Total',
        'notes' => 'Catatan',
    ],

    'fields' => [
        'supplier' => 'Pemasok',
        'store' => 'Toko',
        'date' => 'Tanggal pembayaran',
        'method' => 'Metode pembayaran',
        'supplier_due' => ':amount belum dibayar',
        'supplier_credit' => ':amount dalam kredit',
        'reference' => 'Referensi',
        'reference_help' => 'Nomor cek, ID transfer bank, referensi mutasi, dll. Harus unik — mengidentifikasi satu transaksi nyata.',
        'purchase' => 'Pembelian',
        'notes' => 'Catatan',
    ],

    'allocations' => [
        'pick_supplier' => 'Pilih pemasok terlebih dahulu untuk melihat tagihan pembelian mereka yang terbuka.',
        'loading' => 'Memuat pembelian terbuka…',
        'none' => 'Pemasok ini tidak memiliki tagihan pembelian terbuka. Semua PO telah lunas atau dibatalkan.',
        'auto_amount' => 'Jumlah yang dialokasikan',
        'auto' => 'Alokasi otomatis (FIFO)',
        'auto_help' => 'Mendistribusikan jumlah di atas ke tagihan PO terlama terlebih dahulu — setiap PO dilunasi penuh sebelum lanjut ke berikutnya.',
        'auto_leftover' => 'Jumlah tersisa (tidak ada PO terbuka lagi):',
        'clear' => 'Bersihkan semua',
        'pay_in_full' => 'Lunasi PO ini',
        'columns' => [
            'number' => 'Nomor',
            'date' => 'Tanggal',
            'balance' => 'Sisa Tagihan',
            'amount' => 'Bayar',
            'actions' => 'Aksi',
        ],
    ],

    'totals' => [
        'amount' => 'Total pembayaran',
        'help' => 'Jumlah alokasi per PO di sebelah kiri.',
        'credit' => 'Kredit belum dialokasikan',
        'credit_help' => 'Total yang diketik melebihi alokasi PO — sisa nominal akan menjadi saldo kredit pemasok (piutang ke pemasok).',
    ],

    'actions' => [
        'discard' => 'Batalkan',
        'record' => 'Catat pembayaran',
        'recording' => 'Mencatat…',
    ],

    'flash' => [
        'created' => 'Pembayaran dicatat. Saldo pemasok dan tagihan terkait berhasil diperbarui.',
        'voided' => 'Pembayaran dibatalkan. Tagihan pembelian terkait dibuka kembali dan saldo pemasok dipulihkan.',
    ],

    'errors' => [
        'reference_taken' => 'Referensi tersebut sudah digunakan pada pembayaran lain. Nomor cek, ID transaksi, dan referensi bank harus unik — periksa apakah pembayaran ini sudah pernah dicatat.',
        'credit_application_void' => 'Penerapan kredit pemasok otomatis tidak dapat dibatalkan sebagai pembayaran tunai.',
        'credit_already_applied' => 'Uang muka pemasok ini sudah digunakan pada pembelian dan tidak dapat dibatalkan sepenuhnya.',
    ],

    // Cash-drawer linkage — reasons stamped on the pay-out / reversing pay-in
    // when a cash supplier payment is made (or voided) during an open shift.
    'till' => [
        'pay_out_reason' => 'Pembayaran pemasok · :supplier',
        'void_pay_in_reason' => 'Pembatalan pembayaran pemasok',
        'from_till_note' => 'Pembayaran tunai saat shift kasir terbuka diambil dari laci kasir — ini mengurangi ekspektasi uang tunai di laci.',
        'cash_available' => 'Pembayaran tunai ini diambil dari laci kasir terbuka Anda, yang berisi',
        'over_drawer' => 'Nominal tersebut melebihi uang tunai yang ada di laci kasir. Kurangi jumlahnya, bayar dengan metode lain, atau catat kas masuk terlebih dahulu. Kas tersedia:',
    ],
];
