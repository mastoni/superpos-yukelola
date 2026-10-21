<?php

return [
    'title' => 'Bayar tagihan ini',
    'subtitle' => 'Pilih metode pembayaran yang Anda inginkan.',
    'secure' => 'Pembayaran aman',
    'you_are_paying' => 'Jumlah yang Anda bayar',
    'order_summary' => 'Ringkasan pesanan',
    'subtotal' => 'Subtotal',
    'discount' => 'Diskon',
    'tax_included' => 'Pajak (termasuk)',
    'line_tax' => 'Pajak :rate% · :amount',
    'total' => 'Total',
    'choose_method' => 'Pilih metode pembayaran',
    'already_paid' => 'Pembayaran ini telah selesai dilakukan.',
    'session_expired' => 'Tautan pembayaran telah kedaluwarsa atau dibatalkan. Minta kasir untuk membuat tautan baru.',
    'no_methods_configured' => 'Belum ada metode pembayaran online yang dikonfigurasi.',
    'auto_confirm' => 'Setelah pembayaran selesai, halaman ini akan melakukan konfirmasi otomatis. Anda dapat menutup halaman setelah melihat pesan berhasil.',
    'footer_safe' => 'Anda akan diarahkan ke bank atau penyedia pembayaran Anda untuk menyelesaikan transaksi dengan aman.',
    'hidden_for_currency' => 'Beberapa metode pembayaran disembunyikan karena tidak mendukung mata uang :currency: :names.',

    'capabilities' => [
        'stripe' => 'Kartu Debit/Kredit · Dompet Digital',
        'razorpay' => 'QRIS / UPI · Kartu · Internet Banking',
        'paystack' => 'Kartu · Transfer Bank · USSD',
        'flutterwave' => 'Kartu · Bank · Uang Elektronik',
        'mercado_pago' => 'Kartu · Dompet Digital · Transfer Bank',
    ],

    'invalid' => [
        'title' => 'Tautan pembayaran tidak ditemukan',
        'sub' => 'Tautan tidak valid, telah dibatalkan, atau telah kedaluwarsa. Silakan minta kasir untuk membuat tautan baru.',
    ],

    'return' => [
        'title' => 'Status pembayaran',
        'paid_title' => 'Pembayaran diterima',
        'paid_sub' => 'Terima kasih! Anda dapat mengambil struk belanja Anda — kasir telah menerima konfirmasi pembayaran.',
        'failed_title' => 'Pembayaran gagal diproses',
        'failed_sub' => 'Tidak ada dana yang dipotong. Anda dapat mencoba kembali di konter kasir.',
    ],
];
