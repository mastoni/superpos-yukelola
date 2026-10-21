<?php

return [
    'title' => 'Informasi Sistem & Versi',
    'sub' => 'Informasi versi instalasi lokal dan panduan pembaruan resmi YUKelola.',
    'sections' => [
        'status' => 'Informasi Versi & Lingkungan',
        'status_sub' => 'Rincian versi aplikasi yang sedang aktif di perangkat ini.',
        'distribution' => 'Distribusi & Pembaruan Resmi',
        'distribution_sub' => 'Kebijakan pembaruan dan saluran distribusi resmi YUKelola.',
    ],
    'fields' => [
        'current_version' => 'Versi Aplikasi Saat Ini',
        'app_name' => 'Nama Aplikasi',
        'php_version' => 'Versi PHP Runtime',
        'laravel_version' => 'Versi Framework',
        'database_driver' => 'Driver Basis Data',
        'channel' => 'Saluran Distribusi',
        'channel_value' => 'Rilis Resmi Mandiri (Standalone Installer)',
        'distribution_note' => 'Pembaruan aplikasi YUKelola didistribusikan secara resmi melalui paket instalasi manual (installer Windows Desktop .exe dan berkas .apk Android). Sistem tidak menggunakan feed pembaruan otomatis daring atau penimpaan berkas in-place demi menjaga stabilitas dan kedaulatan data lokal toko Anda.',
    ],
];
