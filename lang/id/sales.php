<?php

return [
    'channel_managed' => 'Kelola pembatalan dan pengembalian dana di saluran penjualan yang terhubung. Mesin POS akan mengimpor perubahan tersebut secara otomatis.',
    'title' => 'Riwayat Penjualan',
    'sub' => 'Semua transaksi penjualan yang telah selesai di seluruh toko Anda.',
    'walk_in' => 'Pelanggan Langsung',

    'actions' => [
        'open_cashier' => 'Buka POS Kasir',
        'print' => 'Cetak struk',
        'view_receipt' => 'Lihat halaman struk',
        'refund' => 'Proses retur / refund',
        'view_refund' => 'Lihat pengembalian',
        'void' => 'Batalkan penjualan (Void)',
    ],

    'confirm_void' => [
        'title' => 'Batalkan penjualan :number?',
        'message' => 'Stok barang akan dikembalikan ke inventaris; untuk penjualan kredit, saldo piutang pelanggan akan dikurangi sebesar porsi yang belum dibayar. Uang tunai yang telah diterima harus dikembalikan langsung dari laci kasir — pembatalan transaksi TIDAK otomatis mengembalikan uang tunai. Tindakan ini tidak dapat dibatalkan.',
        'confirm' => 'Batalkan transaksi (Void)',
        'cancel' => 'Batal',
    ],

    'list' => [
        'title' => 'Penjualan terbaru (:count)',
    ],

    'columns' => [
        'number' => 'Nomor',
        'date' => 'Tanggal',
        'store' => 'Toko',
        'customer' => 'Pelanggan',
        'cashier' => 'Kasir',
        'method' => 'Metode pembayaran',
        'status' => 'Status',
        'grand_total' => 'Total',
    ],

    'summary' => [
        'total' => 'Total penjualan',
        'transactions' => 'Transaksi',
        'items' => 'Barang terjual',
        'avg' => 'Rata-rata penjualan',
    ],

    'filter' => [
        'status_all' => 'Semua status',
        'method_all' => 'Semua metode',
        'from' => 'Dari',
        'to' => 'Sampai',
        'search' => 'Cari no. penjualan, pelanggan, kasir…',
        'customer' => 'Pelanggan',
        'customer_all' => 'Semua pelanggan',
        'customer_search' => 'Cari pelanggan…',
    ],

    'empty' => [
        'title' => 'Belum ada transaksi penjualan',
        'sub' => 'Buka modul kasir untuk melayani transaksi penjualan pertama Anda.',
    ],

    'statuses' => [
        'draft' => 'Draf',
        'held' => 'Ditahan',
        'placed' => 'Dipesan',
        'completed' => 'Selesai',
        'voided' => 'Dibatalkan (Void)',
        'partially_refunded' => 'Diretur sebagian',
        'refunded' => 'Diretur penuh',
    ],

    'sections' => [
        'items' => 'Barang',
        'payments' => 'Pembayaran',
        'totals' => 'Total',
        'customer' => 'Pelanggan',
        'notes' => 'Catatan',
        'refunds' => 'Pengembalian dana untuk penjualan ini',
        'record_payment' => 'Catat pembayaran piutang',
    ],

    'gateway_refund' => [
        'reversed' => 'Dibatalkan pada gateway pembayaran',
        'reversed_short' => 'Dibatalkan',
        'failed' => 'Gagal membatalkan di gateway pembayaran',
        'failed_short' => 'Gagal dibatalkan',
        'retry' => 'Coba batalkan lagi',
        'retry_failed' => 'Tidak dapat membatalkan tagihan pembayaran. Silakan coba lagi.',
    ],

    'items' => [
        'product' => 'Produk',
        'qty' => 'Jml',
        'unit_price' => 'Harga satuan',
        'tax' => 'Pajak',
        'line_total' => 'Subtotal',
    ],

    'payments' => [
        'method' => 'Metode',
        'reference' => 'Referensi',
        'paid_at' => 'Waktu bayar',
        'tendered' => 'Uang diterima',
        'amount' => 'Jumlah',
    ],

    'totals' => [
        'subtotal' => 'Subtotal',
        'discount' => 'Diskon',
        'tax' => 'Pajak',
        'tax_already_included' => 'Pajak (sudah termasuk dalam harga)',
        'charges' => 'Biaya tambahan lainnya',
        'tax_incl_suffix' => '(termasuk pajak)',
        'grand' => 'Total belanja',
        'paid' => 'Total dibayar',
        'change' => 'Kembalian',
        'balance_due' => 'Sisa tagihan',
    ],

    'payment_form' => [
        'sub' => 'Lunasi sisa tagihan piutang pada penjualan ini.',
        'method' => 'Metode',
        'amount' => 'Jumlah',
        'amount_help' => 'Maksimal :balance.',
        'reference' => 'Referensi (opsional)',
        'submit' => 'Catat pembayaran',
    ],

    'customer' => [
        'outstanding_total' => 'Total piutang keseluruhan pelanggan ini',
    ],

    'payment_methods' => [
        'cash' => 'Tunai',
        'card_manual' => 'Kartu Debit / QRIS (pencatatan manual)',
        'stripe' => 'Stripe (Kartu)',
        'razorpay' => 'Razorpay',
        'paystack' => 'Paystack',
        'flutterwave' => 'Flutterwave',
        'mercado_pago' => 'Mercado Pago',
        'stripe_default_line' => 'Penjualan POS',
        'razorpay_default_line' => 'Penjualan POS',
        'paystack_default_line' => 'Penjualan POS',
        'flutterwave_default_line' => 'Penjualan POS',
        'mercado_pago_default_line' => 'Penjualan POS',
    ],

    'flash' => [
        'completed' => 'Penjualan :number berhasil diselesaikan.',
        'payment_recorded' => 'Pembayaran berhasil dicatat.',
        'voided' => 'Penjualan :number telah dibatalkan (void). Stok barang dikembalikan ke inventaris.',
    ],

    'receipt' => [
        'title' => 'Struk :number',
        'back' => 'Kembali',
        'print' => 'Cetak',
        'customer' => 'Pelanggan',
        'cashier' => 'Kasir',
        'gstin' => 'NPWP / GSTIN',
        'sku' => 'SKU',
        'hsn' => 'HSN',
        'hsn_summary' => 'Ringkasan HSN',
        'hsn_col_code' => 'Kode HSN',
        'hsn_col_taxable' => 'Dasar Pengenaan Pajak',
        'hsn_col_tax' => 'Pajak',
        'subtotal' => 'Subtotal',
        'discount' => 'Diskon',
        'points_redeemed' => 'Poin ditukarkan (:points)',
        'points_earned' => 'Poin didapat',
        'points_balance' => 'Saldo poin',
        'tax' => 'Pajak',
        'tax_already_included' => 'Pajak (sudah termasuk)',
        'charges' => 'Biaya lainnya',
        'rounding' => 'Pembulatan',
        'grand_total' => 'Total',
        'tendered' => 'Tunai Diterima',
        'change' => 'Kembalian',
        'qr_caption' => 'Pindai untuk melihat',
        'provisional_note' => 'Struk sementara — nomor resmi akan ditetapkan setelah transaksi sinkron online.',
    ],

    'flash_held' => [
        'parked' => 'Pesanan berhasil ditahan sebagai :number.',
        'resumed' => 'Pesanan tertahan :number dilanjutkan.',
        'voided' => 'Pesanan tertahan :number dihapus.',
    ],

    'create' => [
        'title' => 'Penjualan baru (Back-office)',
        'sub' => 'Buat transaksi penjualan langsung dari panel admin, tanpa membuka layar kasir.',
        'shift_note' => 'Penjualan back-office tidak memerlukan shift kasir yang terbuka. Uang tunai yang diterima di sini tidak akan muncul pada hitungan laci kasir atau laporan Z — catat langsung di kasir jika perlu dihitung dalam shift kasir.',

        'sections' => [
            'header' => 'Rincian Penjualan',
            'header_sub' => 'Lokasi toko dan waktu transaksi berlangsung.',
            'items' => 'Daftar Barang',
            'items_sub' => 'Harga diambil dari katalog produk. Gunakan kolom diskon jika ada potongan harga.',
            'totals' => 'Total',
            'payment' => 'Pembayaran',
            'payment_sub' => 'Tambahkan metode pembayaran yang digunakan, atau kosongkan untuk memasukkannya ke tagihan piutang pelanggan.',
            'notes' => 'Catatan',
        ],

        'fields' => [
            'store' => 'Toko',
            'customer' => 'Pelanggan',
            'customer_help' => 'Kosongkan untuk pelanggan umum (walk-in). Wajib dipilih jika penjualan belum lunas.',
            'walk_in' => 'Pelanggan langsung',
            'sale_date' => 'Tanggal penjualan',
            'items' => 'barang',
            'product' => 'produk',
            'quantity' => 'jumlah',
            'discount' => 'diskon',
            'payment_method' => 'metode pembayaran',
            'amount' => 'jumlah',
            'reference' => 'referensi',
            'add_product' => 'Tambah produk',
            'add_product_placeholder' => 'Cari berdasarkan nama, SKU, atau barcode…',
            'notes' => 'Catatan',
        ],

        'columns' => [
            'product' => 'Produk',
            'quantity' => 'Jml',
            'unit_price' => 'Harga satuan',
            'discount' => 'Diskon %',
            'line_total' => 'Subtotal',
        ],

        'picker' => [
            'searching' => 'Mencari…',
            'empty' => 'Tidak ada produk yang cocok.',
        ],

        'scan' => [
            'placeholder' => 'Pindai barcode…',
            'camera_hint' => 'Arahkan kamera ke barcode produk.',
            'camera_title' => 'Pindai dengan kamera',
            'camera_label' => 'Pindai dengan kamera',
            'empty' => 'Pindai barcode terlebih dahulu.',
            'not_found' => 'Tidak ada produk yang cocok dengan barcode ":barcode".',
        ],

        'totals' => [
            'subtotal' => 'Subtotal',
            'discount' => 'Diskon',
            'tax' => 'Pajak',
            'grand' => 'Total keseluruhan',
            'paid' => 'Sudah dibayar',
            'balance' => 'Sisa tagihan',
            'preview_note' => 'Sistem menghitung ulang saat disimpan — ini adalah pratinjau langsung.',
        ],

        'payment' => [
            'add' => 'Tambah pembayaran',
            'remove' => 'Hapus pembayaran',
            'unpaid' => 'Biarkan belum dibayar (tagihkan ke akun piutang pelanggan)',
            'unpaid_help' => 'Seluruh nominal tagihan akan dicatat ke saldo piutang pelanggan.',
            'remaining' => 'Sisa belum dibayar',
            'change' => 'Uang kembalian',
        ],

        'items_empty' => 'Belum ada barang — cari produk di atas untuk menambahkan baris pertama.',

        'actions' => [
            'discard' => 'Batalkan',
            'save' => 'Selesaikan penjualan',
            'remove_line' => 'Hapus baris',
        ],

        'errors' => [
            'store_required' => 'Pilih toko yang aktif terlebih dahulu.',
            'variant_product_mismatch' => 'Varian tersebut tidak termasuk dalam produk yang dipilih.',
        ],
    ],

    'errors' => [
        'insufficient_stock' => 'Stok tidak mencukupi untuk ":name". Tersedia: :available, diminta: :requested.',
        'expired_batch_sale' => 'Tidak dapat menjual batch ":batch" dari ":name" karena telah kedaluwarsa (:expiry). Memerlukan otorisasi manajer.',
        'invalid_batch' => 'Batch yang dipilih untuk ":name" tidak tersedia, milik item/toko lain, atau stoknya tidak mencukupi.',
        'discount_not_allowed' => 'Anda tidak memiliki hak akses untuk memberikan diskon.',
        'discount_above_threshold' => 'Diskon ini (:percent%) melebihi batas maksimal :threshold% dan memerlukan otorisasi manajer.',
        'loyalty' => [
            'no_customer' => 'Pilih pelanggan terlebih dahulu sebelum menukarkan poin loyalitas.',
            'disabled' => 'Program loyalitas sedang dinonaktifkan.',
            'unavailable' => 'Poin loyalitas tidak dapat ditukarkan. Saldo tersedia: :balance.',
            'not_applied' => 'Poin yang ditukarkan belum dipotong dari total belanja. Silakan terapkan ulang.',
        ],
        'discount_approval' => [
            'invalid' => 'Email dan kata sandi manajer tidak cocok.',
            'inactive' => 'Akun manajer tersebut sedang tidak aktif.',
            'not_authorized' => 'Pengguna tersebut tidak memiliki izin untuk mengotorisasi diskon di atas batas.',
            'approved' => 'Diskon disetujui oleh :name.',
        ],
        'empty_cart' => 'Keranjang belanja kosong — tambahkan produk terlebih dahulu.',
        'refund_empty' => 'Pilih minimal satu baris barang untuk diretur.',
        'refund_additional_negative' => 'Jumlah pengembalian dana tambahan tidak boleh bernilai negatif.',
        'refund_qty_zero' => 'Jumlah retur untuk ":name" harus lebih dari nol.',
        'refund_qty_exceeds' => '":name" — hanya :remaining yang dapat diretur, bukan :requested.',
        'already_refunded' => 'Penjualan ini sudah diretur secara penuh sebelumnya.',
        'credit_requires_customer' => 'Pilih pelanggan sebelum memproses penjualan kredit. Pembelian walk-in wajib dibayar lunas.',
        'credit_limit_exceeded' => 'Pelanggan :name akan memiliki hutang :new yang melebihi batas limit kredit mereka (:limit). Terima uang muka atau naikkan limit kredit pelanggan.',
        'overpay_balance' => 'Pembayaran sebesar :paid melebihi sisa tagihan :balance.',
        'sale_not_open_for_payment' => 'Penjualan ini sudah tidak memiliki sisa tagihan.',
        'not_voidable' => [
            'status_held' => 'Pesanan tertahan dapat dilanjutkan atau dihapus — tidak perlu dibatalkan (void).',
            'status_draft' => 'Penjualan draf tidak dapat di-void. Hapus keranjang saja.',
            'status_voided' => 'Penjualan ini sudah dibatalkan (void) sebelumnya.',
            'status_partially_refunded' => 'Penjualan ini sudah memiliki pengembalian dana. Gunakan alur retur untuk membatalkan barang yang tersisa.',
            'status_refunded' => 'Penjualan ini sudah diretur penuh — pembatalan void tidak berlaku.',
            'has_returns' => 'Penjualan ini memiliki transaksi retur. Gunakan alur retur alih-alih void.',
            'shift_closed' => 'Shift tempat penjualan ini dicatat telah ditutup. Membatalkannya akan mengubah laporan Z — proses pengembalian dana (refund) sebagai gantinya.',
            'has_settlement' => 'Penjualan kredit ini sudah pernah menerima pelunasan piutang. Membatalkannya akan membuat pembayaran terpisah tanpa tagihan — proses retur sebagai gantinya.',
        ],
    ],

    'discount_audit' => [
        'approved_by' => 'Disetujui oleh :name',
    ],

    'refund' => [
        'not_refundable' => 'Penjualan ini tidak dalam status yang dapat diretur.',
        'title' => 'Pengembalian Dana / Retur Penjualan',
        'sub' => 'Pilih barang dan jumlah yang ingin diretur. Barang otomatis dikembalikan ke stok; matikan jika barang rusak / cacat.',
        'reason' => 'Alasan retur',
        'reason_placeholder' => 'Pilih alasan retur',
        'reason_required' => 'Silakan pilih alasan pengembalian dana.',
        'method' => 'Kembalikan dana via',
        'method_placeholder' => 'Gunakan metode pembayaran asal',
        'method_not_on_sale' => 'Pengembalian dana harus menggunakan salah satu metode pembayaran dari transaksi penjualan asli.',
        'restock' => 'Kembalikan barang ke stok',
        'restock_help' => 'Aktif secara bawaan — nonaktifkan jika barang rusak / pecah / BS.',
        'notes' => 'Catatan (opsional)',
        'lines' => 'Daftar Barang',
        'line_qty' => 'Jumlah retur',
        'line_remaining' => 'dari :n dapat diretur',
        'restock_inherit' => 'ikuti bawaan',
        'restock_yes' => 'kembalikan ke stok',
        'restock_no' => 'JANGAN kembalikan ke stok',
        'line_restock' => 'Kembalikan baris ini ke stok',
        'totals_subtotal' => 'Subtotal retur',
        'totals_tax' => 'Pajak retur',
        'totals_grand' => 'Total pengembalian dana',
        'cancel' => 'Batal',
        'submit' => 'Proses pengembalian dana',
        'flash' => [
            'created' => 'Pengembalian dana :number berhasil diproses.',
        ],
    ],
];
