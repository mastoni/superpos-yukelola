<?php

return [
    'title' => 'Cetak Label Barcode',
    'sub' => 'Buat lembar label produk siap cetak yang dilengkapi dengan barcode.',
    'crumb_parent' => 'Produk',

    'products' => [
        'title' => 'Daftar Produk',
        'sub' => 'Pilih produk yang ingin dicetak labelnya dan tentukan jumlahnya.',
        'add' => 'Tambah produk',
        'search_placeholder' => 'Cari produk berdasarkan nama, SKU, atau barcode',
        'qty' => 'Jml',
        'remove' => 'Hapus',
        'empty' => 'Belum ada produk yang ditambahkan. Gunakan pencarian di atas untuk menambahkannya.',
    ],

    'options' => [
        'title' => 'Pilihan Lembar Cetak',
        'layout' => 'Ukuran kertas / label',
        'layout_help' => 'Pilih lembar A4 untuk printer laser / inkjet biasa, atau ukuran roll untuk printer label thermal khusus.',
        'group_sheet' => 'Lembar A4 (Laser / Inkjet)',
        'group_roll' => 'Roll Label (Printer Thermal)',
        'fields' => 'Informasi pada setiap label',
        'field_name' => 'Nama produk',
        'field_sku' => 'SKU',
        'field_price' => 'Harga jual',
        'field_barcode' => 'Barcode',
        'field_location' => 'Lokasi rak',
        'summary' => ':count label',
        'generate' => 'Buat lembar cetak',
        'generate_help' => 'Membuka halaman siap cetak di tab baru browser Anda.',
    ],

    'sheet' => [
        'title' => 'Label Produk',
        'back' => 'Kembali',
        'print' => 'Cetak',
        'count' => ':count label',
        'empty' => 'Tidak ada label untuk dicetak.',
    ],
];
