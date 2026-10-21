<?php

return [
    // Payables mirror of `customer_statement.php`. The sign convention is
    // inverted: a purchase CREDITS the supplier (we owe more), a payment
    // or return DEBITS them (we owe less).
    'kpis' => [
        'opening' => 'Saldo awal',
        'credits' => 'Kredit (pembelian)',
        'debits' => 'Debit (pembayaran + retur)',
        'closing' => 'Saldo akhir',
    ],

    'columns' => [
        'date' => 'Tanggal',
        'type' => 'Tipe',
        'reference' => 'Referensi',
        'debit' => 'Debit',
        'credit' => 'Kredit',
        'running' => 'Saldo',
    ],

    'types' => [
        'opening' => 'Saldo Awal',
        'purchase' => 'Pembelian',
        'payment' => 'Pembayaran',
        'return' => 'Retur',
        // One payment allocated across several POs collapses to one line.
        'split' => 'Untuk :count pembelian',
        'unallocated' => 'Deposit / Akun',
    ],

    'empty' => [
        'title' => 'Tidak ada aktivitas pada periode ini',
        'sub' => 'Coba perluas rentang tanggal, atau pemasok ini belum memiliki transaksi apa pun.',
    ],
];
