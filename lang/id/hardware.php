<?php

return [
    'title' => 'Perangkat Keras (Hardware)',
    'sub' => 'Kelola printer struk, laci kasir (cash drawer), pemindai barcode, dan lakukan uji coba fungsi.',
    'crumb_parent' => 'Pengaturan',
    'modes' => [
        'webusb' => 'Printer Thermal (WebUSB)',
        'browser_print' => 'Cetak Browser (Dialog Cetak)',
        'network' => 'Printer Jaringan (LAN/Wi-Fi)',
        'none' => 'Dinonaktifkan',
        'unconfigured' => 'Belum Dikonfigurasi',
    ],
    'drawer' => [
        'via_printer' => 'Melalui Port Printer Struk (RJ11/RJ12)',
        'unconfigured' => 'Belum Dikonfigurasi',
    ],
    'no_terminal_title' => 'Belum Ada Terminal yang Dipilih',
    'no_terminal_sub' => 'Pengaturan perangkat keras terikat per terminal. <a href=":url">Atur terminal</a> untuk mengonfigurasi printer dan laci kasir.',
    'devices' => [
        'title' => 'Status Perangkat',
        'sub' => 'Status periferal yang terhubung pada browser ini.',
        'for_terminal' => 'Status untuk terminal “:name”.',
        'receipt_printer' => 'Printer Struk',
        'cash_drawer' => 'Laci Kasir (Cash Drawer)',
        'scanner' => 'Pemindai Barcode (USB)',
        'scanner_hint' => 'Scanner USB berfungsi seperti keyboard — scan ke input mana pun untuk mengetes.',
        'scanner_status' => 'Colok & Siap Scan',
        'camera' => 'Pemindai Kamera',
        'camera_hint' => 'Digunakan di layar kasir untuk perangkat tablet/ponsel tanpa scanner USB eksternal.',
        'available' => 'Tersedia',
        'unavailable' => 'Tidak Tersedia',
    ],
    'actions' => [
        'test_print' => 'Tes Cetak Struk',
        'test_drawer' => 'Tes Buka Laci Kasir',
    ],
    'recent' => [
        'title' => 'Riwayat Cetak (7 Hari Terakhir)',
        'total' => 'Total',
        'success' => 'Berhasil',
        'failed' => 'Gagal / Antrean',
        'last' => 'Cetak Terakhir',
    ],
    'test' => [
        'receipt_title' => 'TES CETAK PRINTER',
        'receipt_body' => 'Jika Anda dapat membaca tulisan ini, printer struk Anda berfungsi dengan baik.',
    ],
];
