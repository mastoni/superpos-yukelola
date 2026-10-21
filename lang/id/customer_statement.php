<?php

return [
    'title' => 'Rekening Koran — :name',
    'heading' => 'Rekening koran pelanggan',
    'sub' => 'Log riwayat transaksi berdasarkan tanggal + saldo berjalan untuk :from → :to. Cetak atau kirim via email dari bilah alat.',
    'crumb' => 'Rekening Koran',

    'list_title' => 'Riwayat Transaksi',

    'filter' => [
        'from' => 'Dari',
        'to' => 'Sampai',
    ],

    'kpis' => [
        'opening' => 'Saldo awal',
        'debits' => 'Debit (penjualan)',
        'credits' => 'Kredit (pembayaran + pengembalian)',
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
        'sale' => 'Penjualan',
        'payment' => 'Pembayaran',
        'credit_transfer' => 'Transfer kredit',
        'refund' => 'Pengembalian Dana',
        'void' => 'Dibatalkan (Void)',
    ],

    'empty' => [
        'title' => 'Tidak ada aktivitas pada periode ini',
        'sub' => 'Coba perluas rentang tanggal, atau pelanggan ini belum memiliki transaksi apa pun.',
    ],

    'actions' => [
        'print' => 'Cetak',
        'email' => 'Kirim rekening koran via email',
        'cancel' => 'Batal',
        'close' => 'Tutup',
    ],

    'confirm_email' => [
        'title' => 'Kirim rekening koran ke :name?',
        'message' => 'Salinan akan dikirimkan ke :email. Pelanggan akan melihat tampilan yang persis dengan pratinjau cetak.',
    ],

    'errors' => [
        'no_email' => 'Pelanggan ini tidak memiliki alamat email. Tambahkan email ke profil pelanggan terlebih dahulu.',
        'send_failed' => 'Gagal mengirim email: :error',
    ],

    'flash' => [
        'sent' => 'Rekening koran berhasil dikirim ke :email.',
    ],

    'email' => [
        'subject' => 'Rekening koran: :from → :to',
    ],

    'print' => [
        'billed_to' => 'Ditagihkan Kepada',
        'issued' => 'Diterbitkan',
        'period' => 'Periode',
        'closing_label' => 'Saldo Akhir',
        'please_pay' => 'Jumlah jatuh tempo: :amount. Harap segera melakukan pembayaran.',
        'footer_note' => 'Jika ada pertanyaan terkait rekening koran ini, silakan hubungi kami.',
    ],
];
