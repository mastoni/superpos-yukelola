<?php

return [
    'title' => 'Pembelian',
    'sub' => 'Pesanan pembelian kepada pemasok — catat apa yang Anda beli, berapa hutang Anda, dan barang apa yang sudah tiba.',
    'crumb_parent' => 'Pasokan',
    'new' => 'Pembelian baru',

    'list' => [
        'title' => 'Semua pembelian (:count)',
    ],

    'summary' => [
        'total' => 'Total pembelian',
        'count' => 'Pembelian',
        'paid' => 'Sudah dibayar',
        'outstanding' => 'Hutang belum lunas',
    ],

    'columns' => [
        'number' => 'Nomor',
        'supplier' => 'Pemasok',
        'store' => 'Toko penerima',
        'date' => 'Tanggal',
        'due_date' => 'Jatuh tempo',
        'grand_total' => 'Total',
        'balance' => 'Sisa Hutang',
        'status' => 'Status',
        'actions' => 'Aksi',
        'product' => 'Produk',
        'putaway' => 'Lokasi simpan',
        'qty' => 'Jml',
        'unit_cost' => 'Harga modal',
        'discount' => 'Diskon %',
        'tax_group' => 'Pajak',
        'line_total' => 'Subtotal',
    ],

    'validation_attributes' => [
        'supplier' => 'pemasok',
        'store' => 'toko',
        'purchase_date' => 'tanggal pembelian',
        'due_date' => 'tanggal jatuh tempo',
        'currency' => 'mata uang',
        'exchange_rate' => 'kurs terhadap mata uang dasar',
        'product' => 'produk',
        'quantity' => 'jumlah',
        'unit_cost' => 'harga modal satuan',
        'batch_selling_price' => 'harga jual batch',
        'batch_mrp' => 'HET batch',
        'discount' => 'diskon',
        'tax' => 'pajak',
    ],

    'status' => [
        'draft' => 'Draf',
        'submitted' => 'Diajukan',
        'received' => 'Diterima',
        'partially_paid' => 'Dibayar sebagian',
        'paid' => 'Lunas',
        'cancelled' => 'Dibatalkan',
    ],

    'filter' => [
        'status_all' => 'Semua status',
        'supplier_all' => 'Semua pemasok',
        'from' => 'Dari',
        'to' => 'Sampai',
        'search' => 'Cari no. pembelian, no. faktur, pemasok…',
    ],

    'empty_state' => [
        'title' => 'Belum ada pembelian',
        'sub' => 'Catat pembelian pertama Anda untuk mulai memantau hutang dan barang masuk.',
    ],

    'sections' => [
        'header' => 'Informasi pembelian',
        'header_sub' => 'Pemasok, tanggal, mata uang, dan referensi faktur.',
        'items' => 'Daftar Barang',
        'items_sub' => 'Baris barang dalam pembelian ini. Tambahkan produk untuk memulai.',
        'totals' => 'Total',
        'notes' => 'Catatan',
        'attachment' => 'Lampiran',
    ],

    'attachment' => [
        'label' => 'Lampiran faktur / nota',
        'help' => 'File PDF, gambar, atau dokumen — maksimal 10 MB.',
        'choose' => 'Pilih file',
        'current' => 'File saat ini',
        'download' => 'Unduh',
        'remove' => 'Hapus',
        'replace_hint' => 'Mengunggah file baru akan menggantikan file yang lama.',
        'none' => 'Tidak ada lampiran.',
        'uploaded_by' => 'Diunggah oleh :name',
    ],

    'fields' => [
        'supplier' => 'Pemasok',
        'store' => 'Toko penerima',
        'purchase_date' => 'Tanggal pembelian',
        'due_date' => 'Tanggal jatuh tempo',
        'due_date_help' => 'Otomatis terisi dari termin pembayaran pemasok; dapat diubah.',
        'supplier_invoice' => 'Nomor faktur / nota pemasok',
        'currency' => 'Mata uang',
        'exchange_rate' => 'Kurs ke :base',
        'exchange_rate_help' => 'Berapa unit :base yang setara dengan 1 unit mata uang pembelian.',
        'add_product' => 'Tambah produk',
        'add_product_placeholder' => 'Cari berdasarkan nama, SKU, atau barcode…',
        'notes' => 'Catatan',
    ],

    'picker' => [
        'searching' => 'Mencari…',
        'empty' => 'Tidak ada produk yang cocok. Coba kata kunci lain.',
    ],

    'scan' => [
        'placeholder' => 'Pindai barcode untuk menambah…',
        'camera' => 'Pindai dengan kamera',
        'camera_title' => 'Pindai barcode',
        'camera_close' => 'Tutup pemindai',
        'camera_hint' => 'Arahkan kamera ke barcode. Barang akan ditambahkan ke pesanan saat terdeteksi.',
        'empty' => 'Tidak ada barcode yang dimasukkan.',
        'not_found' => 'Produk dengan barcode :barcode tidak ditemukan.',
    ],

    'line' => [
        'unnamed_product' => '(belum ada produk dipilih)',
        'batch_chip' => 'Batch',
        'batch_number' => 'Nomor batch',
        'batch_number_placeholder' => 'misal: LOT-2026-04A',
        'manufacture_date' => 'Tanggal produksi',
        'expiry_date' => 'Tanggal kedaluwarsa',
        'batch_selling_price' => 'Harga jual batch',
        'batch_selling_price_help' => 'Hanya digunakan jika fitur penetapan harga per-batch diaktifkan.',
        'batch_mrp' => 'HET / MRP batch',
        'batch_mrp_help' => 'Harga eceran tertinggi yang tertera pada batch ini.',
        'mfg_short' => 'Prod',
        'exp_short' => 'Ked',

        'sells' => 'Harga Jual',
        'mrp' => 'HET',
        'target_markup' => 'Target :percent%',
        'markup_title' => 'Margin keuntungan pada modal ini',
        'markup_below' => 'Di bawah target margin :percent%',
        'markup_loss' => 'Harga modal ini sama atau melebihi harga jual (rugi)',
        'price_unset' => '—',
        'price_fx' => 'Harga disembunyikan — PO menggunakan :currency, sedangkan katalog harga dalam :base',
    ],

    'totals' => [
        'subtotal' => 'Subtotal',
        'discount' => 'Diskon',
        'tax' => 'Pajak',
        'grand' => 'Total keseluruhan',
        'paid' => 'Sudah dibayar',
        'balance' => 'Sisa hutang',
        'preview_note' => 'Sistem menghitung ulang dari baris barang saat disimpan — ini adalah pratinjau langsung.',
    ],

    'items_empty' => 'Belum ada barang. Pilih produk di atas untuk menambahkan baris pertama.',

    'actions' => [
        'discard' => 'Batalkan',
        'save' => 'Simpan perubahan',
        'save_draft' => 'Simpan sebagai draf',
        'view' => 'Lihat pembelian',
        'edit' => 'Edit',
        'delete' => 'Hapus',
        'receive' => 'Terima barang',
        'cancel' => 'Batalkan',
        'record_payment' => 'Catat pembayaran',
        'duplicate_line' => 'Duplikasi baris',
        'remove_line' => 'Hapus baris',
        'export' => 'Ekspor',
        'export_csv' => 'CSV',
        'export_xlsx' => 'Excel (XLSX)',
        'create_return' => 'Buat retur pembelian',
    ],

    'flash' => [
        'created' => 'Pembelian :number berhasil dibuat (draf).',
        'updated' => 'Pembelian :number berhasil diperbarui.',
        'deleted' => 'Pembelian :number berhasil dihapus.',
        'received' => 'Pembelian :number diterima. Stok fisik dan saldo hutang pemasok telah diperbarui.',
        'cancelled' => 'Pembelian :number berhasil dibatalkan.',
    ],

    'price_changes' => [
        'title' => 'Harga jual diperbarui otomatis (:count)',
        'sub' => 'Pengaturan "terapkan markup otomatis saat barang diterima" telah menaikkan harga SKU ini untuk menjaga persentase margin laba Anda.',
    ],

    'errors' => [
        'not_editable' => 'Pembelian berstatus :status dan sudah tidak dapat diedit. Gunakan alur penerimaan / pembatalan.',
        'not_deletable' => 'Pembelian berstatus :status dan sudah tidak dapat dihapus.',
        'not_receivable' => 'Pembelian berstatus :status dan sudah tidak dapat diterima lagi.',
        'not_cancellable' => 'Pembelian berstatus :status — batalkan melalui alur retur pembelian.',
        'variant_product_mismatch' => 'Varian yang dipilih tidak cocok dengan produk pada baris ini.',
        'batch_mrp_below_selling' => 'Baris :line — HET batch harus lebih besar dari atau sama dengan harga jual batch.',
    ],

    'confirm_delete' => [
        'title' => 'Hapus pembelian :number?',
        'message' => 'Ini akan menghapus draf pembelian secara soft-delete. Transaksi dapat dipulihkan jika diperlukan.',
        'confirm' => 'Hapus',
    ],

    'confirm_receive' => [
        'title' => 'Terima barang untuk pembelian :number?',
        'message' => 'Ini akan mencatat penambahan stok dan menambah saldo hutang kepada pemasok. Tindakan ini tidak dapat dibatalkan — untuk mengembalikannya, gunakan alur Retur Pembelian setelah barang tiba.',
        'confirm' => 'Terima barang',
    ],

    'receive' => [
        'apply_markup_checkbox' => 'Perbarui harga jual menggunakan persentase markup modal baru',
    ],

    'oversold' => [
        'warning' => ':count barang pada pesanan ini sebelumnya mengalami stok minus — penerimaan barang akan menutup kekurangan stok terlebih dahulu:',
    ],

    'confirm_cancel' => [
        'title' => 'Batalkan pembelian :number?',
        'message' => 'Ini akan menandai draf sebagai dibatalkan. Tidak ada perubahan pada stok maupun saldo hutang. Catatan tetap disimpan untuk audit.',
        'confirm' => 'Batalkan pembelian',
    ],

    'returns' => [
        'title' => 'Retur Pembelian',
        'sub' => 'Pengembalian barang kepada pemasok — stok fisik dan sisa tagihan hutang akan dipotong secara otomatis.',
        'new' => 'Retur baru',
        'list_title' => 'Daftar Retur (:count)',

        'status' => [
            'draft' => 'Draf',
            'posted' => 'Diposting',
        ],

        'filter' => [
            'status_all' => 'Semua status',
            'from' => 'Dari',
            'to' => 'Sampai',
            'search' => 'Cari no. retur, no. pembelian, pemasok…',
        ],

        'columns' => [
            'number' => 'Nomor retur',
            'purchase' => 'No. Pembelian',
            'supplier' => 'Pemasok',
            'date' => 'Tanggal',
            'total' => 'Total',
            'status' => 'Status',
            'received_qty' => 'Jml diterima',
        ],

        'fields' => [
            'return_date' => 'Tanggal retur',
            'notes' => 'Catatan',
            'notes_placeholder' => 'Alasan retur, resi ekspedisi, dll.',
            'quantity' => 'Jumlah retur',
            'restock' => 'Potong stok',
        ],

        'sections' => [
            'header' => 'Rincian retur',
            'items' => 'Barang yang diretur',
            'items_sub' => 'Tentukan jumlah untuk setiap baris. Biarkan 0 untuk melewati baris.',
            'totals' => 'Total',
        ],

        'totals' => [
            'subtotal' => 'Subtotal',
            'tax' => 'Pajak',
            'tax_note' => 'Pajak',
            'tax_server' => 'Dihitung saat disimpan',
            'grand' => 'Total keseluruhan',
            'refund' => 'Jumlah pengembalian dana',
        ],

        'actions' => [
            'create' => 'Buat retur',
            'discard' => 'Batalkan',
            'view' => 'Lihat retur',
            'view_purchase' => 'Lihat pembelian',
        ],

        'restock_yes' => 'Dipotong dari stok',
        'restock_no' => 'Dipertahankan di stok',

        'flash' => [
            'created' => 'Retur :number berhasil dibuat. Stok dan saldo pemasok telah diperbarui.',
        ],

        'errors' => [
            'not_returnable' => 'Pembelian :number tidak dapat diretur (status: :status).',
            'nothing_returnable' => 'Tidak ada barang yang tersisa untuk diretur pada pembelian :number — semua barang telah diretur penuh.',
            'no_qty_entered' => 'Masukkan jumlah retur minimal untuk satu baris barang.',
        ],

        'empty_state' => [
            'title' => 'Belum ada retur pembelian',
            'sub' => 'Retur akan muncul di sini saat barang dikembalikan kepada pemasok.',
        ],
    ],
];
