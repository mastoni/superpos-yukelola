<?php

return [
    'title' => 'Shift Kasir',
    'sub' => 'Buka shift untuk mulai melayani transaksi penjualan. Tutup shift untuk rekonsiliasi dan menghitung saldo laci kas.',

    'list' => [
        'title' => 'Daftar Shift (:count)',
    ],

    'empty' => [
        'title' => 'Belum ada shift kasir',
        'sub' => 'Buka shift pertama Anda untuk mengaktifkan laci kasir.',
    ],

    'columns' => [
        'number' => 'No. Shift',
        'cashier' => 'Kasir',
        'terminal' => 'Terminal',
        'opened' => 'Dibuka',
        'closed' => 'Ditutup',
        'sales_total' => 'Total Penjualan',
        'variance' => 'Selisih Kas',
        'status' => 'Status',
    ],

    'statuses' => [
        'open' => 'Terbuka',
        'closed' => 'Ditutup',
        'closed_with_variance' => 'Ditutup dengan selisih',
    ],

    'summary' => [
        'total' => 'Total shift',
        'open' => 'Sedang terbuka',
        'variance' => 'Terdapat selisih',
    ],

    'open' => [
        'title' => 'Buka Shift Kasir',
        'sub' => 'Hitung modal uang tunai awal di laci kasir dan masukkan totalnya di bawah ini.',
    ],

    'close' => [
        'title' => 'Tutup Shift Kasir',
        'force_banner' => 'Anda menutup paksa shift milik :name. Nama mereka tetap tercatat pada shift; Anda dicatat sebagai pihak yang menutupnya.',
    ],

    'sections' => [
        'sales_summary' => 'Ringkasan Penjualan',
        'payments_received' => 'Pembayaran Diterima',
        'cash_drawer' => 'Laci Kasir',
        'x_report' => 'Laporan X (Langsung / Realtime)',
        'x_report_sub' => 'Total sementara shift saat ini — belum ditutup.',
        'z_report' => 'Laporan Z',
        'z_report_sub' => 'Laporan resmi saat shift resmi ditutup.',
        'details' => 'Rincian',
    ],

    'totals' => [
        'sales_count' => 'Jumlah transaksi',
        'sales_total' => 'Total penjualan',
        'tax_total' => 'Pajak',
        'discount_total' => 'Total diskon',
        'refunds_count' => 'Jumlah retur',
        'refunds_total' => 'Total retur',
        'opening_cash' => 'Modal awal laci kas',
        'cash_sales' => 'Penjualan tunai',
        'cash_refunds' => 'Pengembalian tunai (retur)',
        'pay_ins' => 'Kas masuk',
        'pay_outs' => 'Kas keluar',
        'supplier_payments_heading' => 'Pembayaran pemasok',
        'supplier_unknown' => 'Pemasok tidak dikenal',
        'expected_cash' => 'Ekspektasi saldo kas sistem',
        'counted_cash' => 'Fisik kas aktual terhitung',
        'variance' => 'Selisih kas',
    ],

    'fields' => [
        'terminal' => 'Terminal POS',
        'terminal_placeholder' => 'Pilih terminal…',
        'terminal_help' => 'Mesin kasir tempat shift ini berjalan. Satu terminal hanya dapat memiliki satu shift aktif dalam satu waktu.',
        'opening_cash' => 'Jumlah modal uang awal fisik',
        'opening_cash_help' => 'Berapa banyak uang tunai yang ada di laci kasir saat membuka toko.',
        'closing_cash_counted' => 'Jumlah kas fisik saat tutup shift',
        'expected_cash' => 'Ekspektasi kas sistem',
        'variance' => 'Selisih',
        'variance_reason' => 'Alasan selisih kas',
        'variance_notes' => 'Catatan selisih',
        'notes' => 'Catatan',
        'cashier' => 'Kasir',
        'store' => 'Toko',
        'opened' => 'Dibuka pada',
        'closed' => 'Ditutup pada',
        'force_closed_by' => 'Ditutup paksa oleh',
        'shift_id' => 'Shift',
        'shift_id_value' => 'Shift :n',
        'variance_reason_manage' => 'Kelola alasan selisih kas',
    ],

    'variance_reasons' => [
        'none' => '— pilih alasan —',
        'miscount' => 'Salah hitung kembalian',
        'theft_suspected' => 'Dugaan kehilangan / kecurangan',
        'change_dispute' => 'Selisih uang kembalian pelanggan',
        'unaccounted_pay_out' => 'Pengeluaran kas belum tercatat',
        'other' => 'Lainnya',
    ],

    'actions' => [
        'open' => 'Buka shift',
        'start_shift' => 'Mulai shift',
        'close_shift' => 'Tutup shift',
        'cancel' => 'Batal',
        'view_active' => 'Lihat shift aktif',
        'view_x_report' => 'Laporan X',
        'print' => 'Cetak',
        'print_z_report' => 'Cetak Laporan Z',
        'force_close' => 'Tutup paksa',
    ],

    'stale' => [
        'label' => ':hours jam aktif',
        'tooltip' => 'Shift ini telah terbuka dalam waktu lama — kasir mungkin lupa menutup shift saat pulang. Manajer dapat menutup paksa shift ini.',
    ],

    'logout_warning' => [
        'title' => 'Anda masih memiliki shift yang terbuka',
        'message' => 'Shift :number masih aktif. Keluar dari aplikasi (logout) tidak menutup shift kasir — tutup shift terlebih dahulu untuk rekonsiliasi kas, atau manajer harus menutup paksa nanti.',
        'logout_anyway' => 'Tetap keluar aplikasi',
    ],

    'denom' => [
        'toggle' => 'Hitung berdasarkan pecahan uang',
        'total' => 'Total terhitung',
        'use_total' => 'Gunakan total ini',
    ],

    'flash' => [
        'opened' => 'Shift :number berhasil dibuka.',
        'closed' => 'Shift :number berhasil ditutup.',
    ],

    'errors' => [
        'already_open' => 'Anda sudah memiliki shift kasir yang aktif (:number). Tutup shift tersebut sebelum membuka shift baru.',
        'not_open' => 'Shift ini tidak dalam status terbuka.',
        'variance_reason_required' => 'Alasan selisih kas wajib diisi jika selisih kas melebihi batas toleransi.',
        'no_store_selected' => 'Pilih cabang toko terlebih dahulu sebelum membuka shift kasir.',
        'shift_required' => 'Buka shift kasir terlebih dahulu sebelum melayani penjualan di toko ini.',
        'terminal_busy' => 'Kasir lain sedang menggunakan shift aktif di terminal ini. Mereka harus menutup shiftnya terlebih dahulu.',
        'terminal_required' => 'Pilih terminal kasir untuk membuka shift.',
        'terminal_invalid' => 'Terminal tersebut tidak terdaftar pada toko ini.',
    ],

    'gate' => [
        'title' => 'Buka Shift Kasir Anda',
        'sub' => 'Hitung modal uang tunai di laci kas Anda untuk mulai melayani penjualan.',
        'starting' => 'Membuka…',
        'sell_without' => 'Jual tanpa shift',
        'terminal_title' => 'Pilih Terminal Anda',
        'terminal_sub' => 'Pilih terminal mesin kasir yang digunakan perangkat ini sebelum memulai.',
        'terminal_label' => 'Terminal',
        'terminal_continue' => 'Lanjutkan',
        'leave' => 'Kembali ke dasbor',
    ],
];
