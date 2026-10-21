<?php

return [
    'title' => 'Batch & Kedaluwarsa',
    'sub' => 'Semua batch produk yang tercatat dalam inventaris. Filter berdasarkan toko, produk, atau status masa kedaluwarsa.',
    'crumb_parent' => 'Inventaris',
    'list_title' => 'Daftar Batch',

    'summary' => [
        'total' => 'Total batch',
        'expiring_soon' => 'Segera kedaluwarsa',
        'expired' => 'Kedaluwarsa',
    ],

    'tabs' => [
        'all' => 'Semua',
        'live' => 'Aktif',
        'expiring_soon' => 'Segera kedaluwarsa',
        'expired' => 'Kedaluwarsa',
        'archived' => 'Diarsipkan',
    ],

    'columns' => [
        'product' => 'Produk',
        'store' => 'Toko',
        'location' => 'Lokasi',
        'batch' => 'Nomor batch',
        'mfg' => 'Tanggal Produksi',
        'expiry' => 'Kedaluwarsa',
        'on_hand' => 'Stok Tersedia',
        'cost_price' => 'Harga modal',
        'selling_price' => 'Harga jual',
        'mrp' => 'HET',
        'status' => 'Status',
        'actions' => 'Aksi',
    ],

    'filter' => [
        'store_all' => 'Semua toko',
        'window' => 'Jangka Waktu',
        'days' => 'Dalam :n hari',
        'search' => 'Cari no. batch, nama produk, atau SKU…',
    ],

    'status' => [
        'live' => 'Aktif',
        'expiring_soon' => 'Segera kedaluwarsa',
        'expired' => 'Kedaluwarsa',
        'archived' => 'Diarsipkan',
    ],

    'expiry' => [
        'today' => '(hari ini)',
        'in_n_days' => '(dalam :n hari)',
        'expired_n_days' => '(:n hari yang lalu)',
    ],

    'empty' => [
        'title' => 'Tidak ada batch yang cocok',
        'sub' => 'Terima barang pembelian dengan nomor batch untuk mengisi daftar ini.',
    ],

    'actions' => [
        'export' => 'Ekspor',
        'export_csv' => 'CSV (.csv)',
        'export_xlsx' => 'Excel (.xlsx)',
        'archive' => 'Arsipkan',
        'restore' => 'Pulihkan',
    ],

    'confirm_archive' => [
        'title' => 'Arsipkan batch :batch?',
        'message' => 'Batch akan disembunyikan dari daftar ini dan pemilih batch di kasir. Riwayat transaksi masa lalu tetap tersimpan utuh, dan penerimaan stok dengan nomor batch ini nantinya akan mengaktifkannya kembali.',
    ],

    'flash' => [
        'archived' => 'Batch :batch berhasil diarsipkan. Temukan kembali di tab Diarsipkan.',
        'restored' => 'Batch :batch berhasil dipulihkan.',
        'not_empty' => 'Batch :batch masih memiliki sisa stok fisik, sehingga tidak dapat diarsipkan. Sesuaikan stok barang menjadi nol terlebih dahulu.',
    ],
];
