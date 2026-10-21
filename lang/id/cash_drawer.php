<?php

return [
    'panel' => [
        'title' => 'Laci Kasir (Cash Drawer)',
        'sub' => 'Catat penambahan atau pengambilan uang tunai di tengah shift, atau catat pembukaan laci kas tanpa penjualan.',
    ],

    'entries' => [
        'title' => 'Mutasi Kas Laci',
        'sub' => 'Semua pencatatan kas masuk, kas keluar, dan pembukaan laci tanpa penjualan pada shift ini.',
    ],

    'types' => [
        'pay_in' => 'Kas Masuk',
        'pay_out' => 'Kas Keluar',
        'pay_supplier' => 'Bayar Pemasok',
        'drawer_open_no_sale' => 'Buka Laci Tanpa Penjualan',
    ],

    'categories' => [
        'expense' => 'Biaya Operasional (Kas Kecil)',
        'bank_deposit' => 'Setor ke Bank',
        'change_to_safe' => 'Pindah ke Brankas',
        'other' => 'Lainnya',
    ],

    'fields' => [
        'amount' => 'Jumlah Uang',
        'available' => 'Uang di Laci Kas',
        'reason' => 'Alasan / Keterangan',
        'category' => 'Tujuan Kas Keluar',
        'expense_category' => 'Kategori Pengeluaran',
        'expense_category_none' => 'Tanpa Kategori',
        'reference' => 'Referensi',
    ],

    'help' => [
        'pay_in_amount' => 'Uang tunai yang dimasukkan ke dalam laci kasir saat ini.',
        'pay_in_reason' => 'misal: "Tambah modal kembalian", "Isi ulang dari brankas".',
        'pay_out_amount' => 'Uang tunai yang diambil keluar dari laci kasir saat ini.',
        'pay_out_available' => 'Laci kasir berisi :amount. Pengambilan uang tidak boleh melebihi jumlah tersebut.',
        'pay_out_reason' => 'misal: "Beli ATK / lakban", "Setoran tunai ke bank".',
        'pay_out_category_expense' => 'Catatan pengeluaran otomatis dibuat sehingga muncul pada menu Pengeluaran dan Laporan Laba Rugi — bukan hanya di laci kas.',
        'pay_out_category_other' => 'Saldo laci kasir berkurang, namun tidak dicatat sebagai beban pengeluaran usaha.',
        'expense_category' => 'Ke akun beban apa biaya ini dialokasikan. Biarkan kosong jika belum pasti — Anda dapat mengaturnya nanti dari menu Pengeluaran.',
        'reference' => 'Nomor nota, kuitansi, atau bukti kas keluar opsional.',
        'drawer_open_reason' => 'misal: "Pelanggan menukar uang kecil". Dicatat untuk audit; tidak ada perpindahan uang.',
        'pay_supplier' => 'Bayar tagihan pemasok langsung dari kasir. Pembayaran tunai diambil dari kas laci ini dan mengurangi ekspektasi kas fisik; metode non-tunai tidak memengaruhi laci kas.',
    ],

    'till' => [
        'overflow_open' => 'Kas Masuk / Keluar',
        'title' => 'Kas Masuk / Kas Keluar',
        'cash_out' => 'Ambil Kas Keluar',
        'cash_in' => 'Masukkan Kas Masuk',
        'reason_required' => 'Jelaskan keperluan transaksi ini.',
    ],

    'actions' => [
        'record_pay_in' => 'Catat Kas Masuk',
        'record_pay_out' => 'Catat Kas Keluar',
        'record_drawer_open' => 'Buka Laci Kas',
        'pay_supplier' => 'Bayar Pemasok',
        'take_all' => 'Ambil Semua',
    ],

    'columns' => [
        'when' => 'Waktu',
        'type' => 'Tipe',
        'reason' => 'Keterangan',
        'by' => 'Petugas',
        'amount' => 'Jumlah',
    ],

    'flash' => [
        'pay_in_recorded' => 'Kas masuk berhasil dicatat.',
        'pay_out_recorded' => 'Kas keluar berhasil dicatat.',
        'pay_out_expense_recorded' => 'Kas keluar dicatat dan dibukukan sebagai biaya pengeluaran :number.',
        'drawer_opened_no_sale' => 'Pembukaan laci kas tanpa penjualan berhasil dicatat.',
        'recorded' => 'Entri laci kas berhasil dicatat.',
    ],

    'errors' => [
        'amount_must_be_positive' => 'Jumlah uang harus lebih besar dari nol.',
        'insufficient_cash' => 'Laci kas hanya memiliki saldo :available, sehingga Anda tidak dapat mengambil :requested. Catat kas masuk terlebih dahulu jika ada penambahan uang.',
        'over_drawer' => 'Jumlah tersebut melebihi uang tunai yang ada di laci kas.',
        'over_drawer_max' => 'Jumlah tersebut melebihi uang tunai yang ada di laci kas — jumlah maksimal yang dapat diambil saat ini adalah :available.',
        'no_cash_method' => 'Belum ada metode pembayaran tunai yang aktif, sehingga pengeluaran ini tidak dapat dicatat. Tambahkan di Pengaturan → Metode pembayaran.',
    ],
];
