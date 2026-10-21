<?php

return [
    'title' => 'Log Sinkronisasi (Sync Log)',
    'sub' => 'Catatan lengkap transmisi kasir beserta data dan hasilnya. Penjualan saat offline akan muncul di sini setelah tersinkronisasi.',
    'crumb_parent' => 'Wawasan',
    'summary' => [
        'total' => 'Total Entri',
        'success' => 'Berhasil',
        'conflict' => 'Konflik',
        'failed' => 'Gagal',
    ],
    'list_title' => 'Daftar Entri',
    'tabs' => [
        'all' => 'Semua',
        'success' => 'Berhasil',
        'failed' => 'Gagal',
        'conflict' => 'Konflik',
    ],
    'columns' => [
        'synced_at' => 'Waktu Sinkron',
        'entity' => 'Entitas',
        'local_uuid' => 'UUID Lokal',
        'result' => 'Hasil',
        'user' => 'Kasir',
        'message' => 'Pesan / Status',
    ],
    'status' => [
        'success' => 'Berhasil',
        'failed' => 'Gagal',
        'conflict' => 'Konflik',
        'dismissed' => 'Diabaikan',
    ],
    'actions' => [
        'retry' => 'Coba Sinkron Ulang',
        'dismiss' => 'Abaikan',
        'undismiss' => 'Batal Abaikan',
        'view' => 'Lihat Rincian',
    ],
    'confirm_retry' => [
        'title' => 'Coba sinkronkan ulang entri ini?',
        'message' => 'Data tersimpan akan dikirimkan kembali ke server persis seperti yang dicatat di kasir. Aman diulang — penjualan yang sudah masuk tidak akan terduplikasi.',
        'confirm' => 'Sinkronkan Sekarang',
    ],
    'confirm_dismiss' => [
        'title' => 'Abaikan entri ini?',
        'message' => 'Gunakan ini jika transaksi tidak mungkin disinkronkan — misalnya stok benar-benar tidak ada atau transaksi sudah diinput manual. Data tetap disimpan untuk audit.',
        'confirm' => 'Abaikan',
    ],
    'flash' => [
        'retry_success' => 'Berhasil disinkronkan. Transaksi penjualan kini telah tercatat di server.',
        'retry_failed' => 'Sinkronisasi ulang ditolak server: :message',
        'dismissed' => 'Entri berhasil diabaikan.',
        'undismissed' => 'Entri dibuka kembali.',
    ],
    'retry_hint' => 'Sinkronisasi ulang akan memproses data atas nama kasir asli. Jika shift kasir tersebut telah ditutup, buka shift untuknya terlebih dahulu.',
    'filter' => [
        'from' => 'Dari Tanggal',
        'to' => 'Sampai Tanggal',
        'search' => 'Cari UUID atau pesan…',
    ],
    'fields' => [
        'entity' => 'Entitas',
        'user' => 'Kasir',
        'sale' => 'Penjualan',
        'grand_total' => 'Total Transaksi',
        'message' => 'Pesan',
        'payload' => 'Data Asli (Payload)',
        'resolved' => 'Diselesaikan',
        'resolved_by' => 'Diselesaikan Oleh',
    ],
    'columns_actions' => 'Aksi',
    'empty' => [
        'title' => 'Belum ada peristiwa sinkronisasi',
        'sub' => 'Setiap transaksi kasir akan tercatat di sini setelah tersinkronisasi (secara langsung maupun setelah offline).',
    ],
];
