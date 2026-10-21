<?php

return [
    'nav' => [
        'section' => 'Akuntansi',
    ],

    'cancel' => 'Batal',

    'journal' => [
        'title' => 'Jurnal Umum',
        'sub' => 'Setiap entri yang telah diposting — buku harian akuntansi. Dihasilkan otomatis dari transaksi operasional, ditambah entri penyesuaian manual.',
        'list_title' => 'Daftar Entri Jurnal',
        'new_entry' => 'Entri jurnal baru',
        'create_sub' => 'Posting entri jurnal manual — untuk penyesuaian, akrual, penyusutan aset, transaksi pemilik / prive, dan koreksi pembukuan.',

        'filter' => [
            'source' => 'Sumber Transaksi',
            'all_sources' => 'Semua sumber',
            'search' => 'Cari nomor atau deskripsi…',
        ],

        'columns' => [
            'date' => 'Tanggal',
            'number' => 'No. Entri',
            'source' => 'Sumber',
            'description' => 'Deskripsi',
            'amount' => 'Jumlah',
            'account' => 'Akun Perkiraan',
            'line_description' => 'Keterangan Baris',
            'debit' => 'Debit',
            'credit' => 'Kredit',
        ],

        'fields' => [
            'date' => 'Tanggal',
            'description' => 'Deskripsi Entri',
            'description_placeholder' => 'misal: Beban penyusutan aset bulanan',
            'select_account' => 'Pilih akun perkiraan…',
        ],

        'add_line' => 'Tambah baris',
        'remove_line' => 'Hapus baris',
        'off_by' => 'Selisih',
        'post' => 'Posting entri',
        'cancel' => 'Batal',

        'posted' => 'Entri jurnal berhasil diposting.',
        'reversed' => 'Entri jurnal berhasil dibalik (reverse).',

        'reverse' => 'Balik Entri (Reverse)',
        'reverse_confirm' => 'Posting entri pembalik untuk membatalkan entri jurnal ini? Tindakan ini tidak dapat diurungkan.',
        'reversed_by' => 'Entri ini telah dibalik oleh',
        'reversal_of' => 'Ini merupakan entri pembalik dari',
        'posted_by' => 'Diposting oleh :user pada :at',

        'period_locked' => 'Tanggal tersebut berada pada periode akuntansi yang telah dikunci.',
        'already_reversed' => 'Entri jurnal ini sudah dibalik sebelumnya.',
        'not_reversible' => 'Entri ini tidak dapat dibalik — dibuat otomatis dari pembatalan transaksi penjualan (void).',
        'not_posted' => 'Entri ini masih berupa draf, sehingga tidak ada yang perlu dibalik.',

        'empty' => [
            'title' => 'Tidak ada entri jurnal pada periode ini',
            'sub' => 'Sesuaikan rentang tanggal, atau posting entri manual untuk memulai.',
        ],

        'errors' => [
            'line_one_side' => 'Setiap baris akun hanya boleh memiliki nilai Debit atau Kredit, tidak boleh keduanya.',
            'empty' => 'Masukkan minimal satu sisi Debit dan satu sisi Kredit.',
            'unbalanced' => 'Jumlah Debit (:debit) harus sama dengan Kredit (:credit) / seimbang.',
        ],
    ],

    'periods' => [
        'title' => 'Periode Akuntansi & Fiskal',
        'sub' => 'Tahun buku akuntansi Anda beserta periode bulanannya. Kunci periode untuk membekukan pencatatan entri; tutup tahun buku untuk memindahkan laba ke laba ditahan.',
        'year' => 'Tahun Fiskal',
        'period' => 'Periode',
        'range' => 'Rentang Tanggal',
        'status' => 'Status',
        'open' => 'Terbuka',
        'locked' => 'Terkunci',
        'closed' => 'Ditutup',
        'current' => 'Periode Berjalan',
        'lock' => 'Kunci',
        'unlock' => 'Buka Kunci',
        'lock_confirm' => 'Kunci periode :period? Entri baru bertanggal pada periode ini akan ditolak sampai kunci dibuka kembali.',
        'unlock_confirm' => 'Buka kunci periode :period agar entri koreksi dapat diposting ke dalamnya?',

        'close_year' => 'Tutup Tahun Buku',
        'close_year_confirm' => 'Tutup tahun buku :year? Ini akan memposting jurnal penutup, memindahkan laba bersih ke Laba Ditahan, dan mengunci tahun ini secara permanen. Pastikan semua periode bulanan telah dikunci.',
        'year_closed_badge' => 'Ditutup pada :at',
        'all_locked_hint' => 'Semua periode bulanan telah terkunci — siap untuk tutup tahun buku.',
        'lock_all_hint' => 'Kunci semua periode bulanan sebelum melakukan penutupan tahun buku.',

        'errors' => [
            'year_closed' => 'Periode ini termasuk dalam tahun buku yang sudah ditutup dan tidak dapat dibuka kembali.',
        ],

        'empty' => [
            'title' => 'Belum ada tahun fiskal',
            'sub' => 'Tahun buku pertama Anda akan otomatis dibuat begitu entri jurnal pertama diposting.',
        ],
    ],

    'close' => [
        'zero_expense' => 'Menutup akun beban ke Laba Ditahan',
        'zero_income' => 'Menutup akun pendapatan ke Laba Ditahan',
        'retained' => 'Hasil laba/rugi bersih ke Laba Ditahan',
        'description' => 'Penutupan akhir tahun buku — :year',

        'errors' => [
            'already_closed' => 'Tahun buku ini sudah ditutup sebelumnya.',
            'not_locked' => 'Kunci seluruh periode bulanan dalam tahun buku sebelum melakukan penutupan.',
        ],
    ],

    'lines' => [
        'payment' => 'Pembayaran diterima',
        'receivable' => 'Piutang usaha',
        'discount' => 'Potongan penjualan',
        'sales_revenue' => 'Pendapatan penjualan',
        'tax_output' => 'Pajak keluaran (PPN)',
        'tax_input' => 'Pajak masukan',
        'cogs' => 'Harga pokok penjualan (HPP)',
        'inventory' => 'Persediaan barang dagang',
        'payable' => 'Hutang usaha',
        'expense' => 'Biaya / beban operasional',
        'customer_advance' => 'Uang muka pelanggan',
        'supplier_advance' => 'Uang muka ke pemasok',
        'sales_return' => 'Retur penjualan',
        'refund' => 'Pengembalian dana',
        'store_credit' => 'Saldo kredit toko',
        'cash' => 'Kas tunai',
        'cash_overage' => 'Selisih kas lebih',
        'cash_shortage' => 'Selisih kas kurang',
        'cash_drawer_counterpart' => 'Kas laci / perantara kas',
        'reconciliation_offset' => 'Penyeimbang rekonsiliasi piutang',
        'shrinkage' => 'Penyusutan / kehilangan stok',
        'adjustment_income' => 'Pendapatan penyesuaian inventaris',
    ],

    'posting_failure' => [
        'title' => 'Entri jurnal gagal diposting',
        'body' => 'Proses :poster gagal untuk transaksi :reference sehingga pembukuan belum mencatat entri tersebut. Sistem akan mencoba kembali secara otomatis; jika tetap gagal, periksa pemetaan akun perkiraan dan pastikan periode belum dikunci. Pesan kesalahan: :error',
    ],

    'reconciliation' => [
        'notification_title' => 'Piutang usaha memerlukan rekonsiliasi',
        'notification_body' => 'Toko :store memiliki selisih akun kontrol piutang sebesar :amount. Tinjau Neraca dan jalankan perintah rekonsiliasi piutang.',
        'columns' => ['store' => 'Toko', 'subledger' => 'Penjualan Terbuka', 'ledger' => 'Buku Besar Piutang', 'drift' => 'Selisih', 'result' => 'Hasil'],
        'adjusted' => 'Disesuaikan :number',
        'review' => 'Perlu Ditinjau',
        'ok' => 'Cocok / Normal',
        'warning' => 'Ditemukan selisih. Periksa data, lalu jalankan dengan opsi --fix untuk memposting jurnal penyesuaian kontrol.',
    ],

    'store_credit_repair' => [
        'columns' => [
            'store' => 'Toko',
            'conversions' => 'Konversi belum tercatat',
            'legacy_balance' => 'Saldo kewajiban lama',
            'result' => 'Hasil',
        ],
        'fixed' => 'Konversi diposting: :conversions; kewajiban direklasifikasi: :reclassified',
        'yes' => 'ya',
        'no' => 'tidak',
        'review' => 'Perlu Ditinjau',
        'ok' => 'Cocok',
        'warning' => 'Ditemukan selisih akuntansi kredit toko yang dapat diperbaiki. Tinjau kembali, lalu jalankan dengan opsi --fix.',
    ],

    'descriptions' => [
        'sale' => 'Penjualan :number',
        'purchase' => 'Pembelian :number',
        'expense' => 'Pengeluaran :number',
        'customer_payment' => 'Pembayaran dari :name',
        'supplier_payment' => 'Pembayaran kepada :name',
        'sale_void' => 'Pembatalan transaksi penjualan :number',
        'sale_return' => 'Retur penjualan :number',
        'purchase_return' => 'Retur pembelian ke pemasok :number',
        'stock_adjustment' => 'Penyesuaian stok :number',
        'shift_variance' => 'Selisih kas shift :number',
        'cash_drawer' => ':type — :reason',
        'ar_reconciliation' => 'Rekonsiliasi piutang usaha untuk toko :store',
        'customer_credit_conversion' => 'Konversi piutang lama ke kredit pelanggan untuk :customer',
        'store_credit_reconciliation' => 'Rekonsiliasi kewajiban kredit toko untuk :store',
        'reversal' => 'Pembalik entri jurnal :number',
    ],

    'types' => [
        'asset' => 'Aset / Harta',
        'liability' => 'Kewajiban / Hutang',
        'equity' => 'Ekuitas / Modal',
        'income' => 'Pendapatan',
        'expense' => 'Beban / Biaya',
    ],

    'chart' => [
        'title' => 'Bagan Akun (Chart of Accounts)',
        'sub' => 'Seluruh akun perkiraan buku besar yang dikelompokkan secara rapi. Tambahkan akun baru, ubah nama, atau buka buku besar pembantu setiap akun.',
        'new' => 'Akun baru',
        'system' => 'Sistem',
        'system_hint' => 'Akun bawaan sistem — digunakan untuk pencatatan transaksi otomatis. Tidak dapat dihapus.',
        'mapped' => 'Dipetakan',
        'mapped_hint' => 'Pemetaan transaksi operasional bisnis terhubung ke akun ini.',
        'show_gl' => 'Buku Besar',

        'fields' => [
            'code' => 'Nomor / Kode Akun',
            'code_help' => 'Nomor unik pendek, misal: 1110, 4100, 5100.',
            'code_locked_help' => 'Akun ini sudah memiliki riwayat entri yang diposting, sehingga kode akun dikunci.',
            'name' => 'Nama Akun',
            'group' => 'Grup Akun',
            'group_help' => 'Akun akan mewarisi tipe pelaporan (aset, pendapatan, dll.) dari grup ini.',
            'select_group' => 'Pilih grup akun…',
            'is_active' => 'Aktif — tersedia saat memposting transaksi',
        ],

        'editor' => [
            'empty_title' => 'Belum ada akun yang dipilih',
            'empty_sub' => 'Pilih akun dari bagan di sebelah kiri untuk mengedit, atau buat akun baru.',
            'new_title' => 'Akun perkiraan baru',
            'new_sub' => 'Buat akun perkiraan buku besar kustom.',
            'edit_title' => 'Edit akun perkiraan',
            'code_label' => 'Kode Akun',
            'system_note' => 'Ini adalah akun perkiraan bawaan sistem. Anda dapat mengubah nama atau grupnya, namun tidak dapat menghapusnya.',
        ],

        'actions' => [
            'save' => 'Simpan akun',
            'create' => 'Buat akun',
            'discard' => 'Batalkan',
            'delete' => 'Hapus',
        ],

        'flash' => [
            'created' => 'Akun perkiraan “:name” berhasil dibuat.',
            'updated' => 'Akun perkiraan “:name” berhasil diperbarui.',
            'deleted' => 'Akun perkiraan “:name” berhasil dihapus.',
        ],

        'errors' => [
            'code_format' => 'Kode hanya boleh memuat huruf, angka, titik, strip (-), dan garis bawah (_).',
            'code_locked' => 'Kode akun ini tidak dapat diubah karena sudah memiliki riwayat entri jurnal yang diposting.',
            'delete_system' => 'Akun bawaan sistem tidak dapat dihapus.',
            'delete_in_use' => 'Akun ini sudah memiliki entri jurnal dan tidak dapat dihapus.',
            'delete_mapped' => 'Akun ini terhubung ke pemetaan transaksi operasional — ubah pemetaan terlebih dahulu.',
            'deactivate_mapped' => 'Akun ini terhubung ke pemetaan transaksi operasional dan tidak dapat dinonaktifkan.',
            'deactivate_balance' => 'Akun bawaan yang masih memiliki saldo tidak dapat dinonaktifkan.',
        ],

        'empty' => [
            'title' => 'Belum ada akun perkiraan',
            'sub' => 'Bagan akun bawaan akan otomatis diisi saat instalasi.',
        ],

        'groups' => [
            'new' => 'Grup baru',
            'edit_hint' => 'Edit grup akun',

            'editor' => [
                'new_title' => 'Grup akun baru',
                'new_sub' => 'Tambahkan sub-grup di bawah grup yang sudah ada.',
                'edit_title' => 'Edit grup akun',
            ],

            'fields' => [
                'name' => 'Nama Grup',
                'parent' => 'Grup Induk',
                'select_parent' => 'Pilih grup induk…',
                'parent_help' => 'Grup mewarisi tipe pelaporan (aset, kewajiban, dll.) dari grup induknya.',
            ],

            'actions' => [
                'create' => 'Buat grup',
                'save' => 'Simpan grup',
                'delete' => 'Hapus',
                'discard' => 'Batalkan',
            ],

            'system_note' => 'Grup bawaan sistem. Anda dapat mengubah namanya, namun tidak dapat dipindahkan atau dihapus.',

            'flash' => [
                'created' => 'Grup akun “:name” berhasil dibuat.',
                'updated' => 'Grup akun “:name” berhasil diperbarui.',
                'deleted' => 'Grup akun “:name” berhasil dihapus.',
            ],

            'errors' => [
                'system_move' => 'Grup bawaan sistem tidak dapat dipindahkan.',
                'root_move' => 'Grup tingkat teratas tidak dapat dipindahkan.',
                'cross_type' => 'Grup hanya dapat dipindahkan ke bawah grup lain yang memiliki tipe pelaporan sama.',
                'cycle' => 'Grup tidak dapat dipindahkan ke bawah dirinya sendiri.',
                'delete_system' => 'Grup bawaan sistem tidak dapat dihapus.',
                'delete_has_children' => 'Pindahkan atau hapus sub-grup di bawahnya terlebih dahulu.',
                'delete_has_accounts' => 'Pindahkan atau hapus akun perkiraan di bawah grup ini terlebih dahulu.',
            ],
        ],
    ],

    'opening' => [
        'title' => 'Saldo Awal Pembukuan',
        'sub' => 'Masukkan saldo awal Anda saat bermigrasi dari sistem akuntansi lain. Setiap akun diposting pada sisi alaminya; selisihnya akan diserap otomatis oleh Ekuitas Saldo Awal.',
        'description' => 'Saldo awal pembukuan',
        'line' => 'Saldo awal',
        'equity' => 'Ekuitas saldo awal',

        'sections' => [
            'asset' => 'Aset',
            'liability' => 'Kewajiban',
            'equity' => 'Ekuitas',
        ],

        'date_label' => 'Tanggal Neraca Saldo Awal',
        'dr' => 'Dbt',
        'cr' => 'Krd',

        'columns' => [
            'account' => 'Akun Perkiraan',
            'side' => 'Posisi Alami',
            'amount' => 'Jumlah Saldo',
        ],

        'debit_total' => 'Total Debit',
        'credit_total' => 'Total Kredit',
        'plug_label' => 'Ekuitas Saldo Awal',
        'plug_credit' => 'kredit',
        'plug_debit' => 'debit',
        'balanced' => 'Seimbang (Balance)',
        'plug_hint' => 'Jika seluruh neraca saldo awal dimasukkan lengkap, status akan bertuliskan “Seimbang”. Sisa nominal yang belum seimbang akan dialokasikan ke Ekuitas Saldo Awal.',

        'post' => 'Posting saldo awal',
        'locked_notice' => 'Transaksi operasional riil sudah pernah diposting ke sistem, sehingga saldo awal tidak dapat diubah lagi. Gunakan entri jurnal penyesuaian manual sebagai gantinya.',

        'flash' => [
            'posted' => 'Saldo awal pembukuan berhasil diposting.',
        ],

        'errors' => [
            'locked' => 'Saldo awal tidak dapat diubah setelah ada transaksi riil yang tercatat.',
        ],
    ],

    'mappings' => [
        'title' => 'Pemetaan Akun Bisnis',
        'sub' => 'Tentukan akun perkiraan buku besar mana yang digunakan untuk setiap pencatatan transaksi otomatis. Pengaturan bawaan sudah sesuai untuk sebagian besar toko — ubah hanya jika diminta oleh konsultan akuntan Anda.',
        'read_only' => 'Anda tidak memiliki hak akses untuk mengubah pemetaan akun ini.',
        'account' => 'Akun Perkiraan',
        'save' => 'Simpan pemetaan',

        'flash' => [
            'saved' => 'Pemetaan akun bisnis berhasil disimpan.',
        ],

        'errors' => [
            'wrong_type' => 'Peristiwa transaksi ini harus dipetakan ke akun bertipe :expected.',
        ],

        'keys' => [
            'sales_revenue' => ['label' => 'Pendapatan penjualan',             'help' => 'Dikreditkan pada setiap transaksi penjualan barang.'],
            'service_revenue' => ['label' => 'Pendapatan jasa',                 'help' => 'Dikreditkan pada penjualan produk jenis layanan / jasa.'],
            'sales_returns' => ['label' => 'Retur penjualan',                 'help' => 'Didebit saat ada pengembalian barang dari pelanggan.'],
            'sales_discounts' => ['label' => 'Potongan penjualan',               'help' => 'Didebit untuk diskon di tingkat penjualan.'],
            'inventory' => ['label' => 'Persediaan barang',             'help' => 'Didebit saat pembelian, dikreditkan saat barang terjual.'],
            'tax_output' => ['label' => 'Pajak keluaran (PPN)',          'help' => 'Pajak yang dipungut dari pelanggan, kewajiban ke kantor pajak.'],
            'tax_input' => ['label' => 'Pajak masukan',                  'help' => 'Pajak yang dibayarkan saat pembelian yang dapat dikreditkan.'],
            'cogs' => ['label' => 'Harga pokok penjualan (HPP)',    'help' => 'Didebit sebesar harga modal barang yang terjual.'],
            'inventory_wastage' => ['label' => 'Kerusakan / barang BS',         'help' => 'Didebit saat barang dihapusbukukan karena rusak.'],
            'inventory_shrinkage' => ['label' => 'Penyusutan / kehilangan stok',   'help' => 'Didebit pada penyesuaian stok berkurang.'],
            'inventory_adjustment_income' => ['label' => 'Pendapatan penyesuaian stok',   'help' => 'Dikreditkan pada penyesuaian stok bertambah.'],
            'accounts_receivable_customers' => ['label' => 'Piutang usaha pelanggan',      'help' => 'Didebit pada penjualan kredit bayar-nanti oleh pelanggan.'],
            'accounts_payable_suppliers' => ['label' => 'Hutang usaha pemasok',         'help' => 'Dikreditkan pada transaksi pembelian barang yang belum lunas.'],
            'customer_advances' => ['label' => 'Uang muka / deposit pelanggan', 'help' => 'Kelebihan bayar atau deposit yang disimpan sebagai kredit pelanggan.'],
            'advances_to_suppliers' => ['label' => 'Uang muka ke pemasok',         'help' => 'Pembayaran uang muka pembelian kepada pemasok.'],
            'store_credit_liability' => ['label' => 'Kewajiban kredit toko (lama)', 'help' => 'Akun kredit retur lama yang dipertahankan untuk reklasifikasi historis.'],
            'cash_overage' => ['label' => 'Selisih kas lebih',             'help' => 'Dikreditkan saat fisik kas di laci kasir lebih dari sistem.'],
            'cash_shortage' => ['label' => 'Selisih kas kurang',            'help' => 'Didebit saat fisik kas di laci kasir kurang dari sistem.'],
            'rounding_adjustment_income' => ['label' => 'Laba pembulatan',               'help' => 'Dikreditkan jika ada pembulatan yang menguntungkan.'],
            'rounding_adjustment_expense' => ['label' => 'Beban pembulatan',              'help' => 'Didebit jika ada pembulatan yang mengurangi total kas.'],
            'payment_gateway_fees' => ['label' => 'Biaya gateway pembayaran',       'help' => 'Didebit saat biaya transaksi gateway pembayaran dicatat.'],
            'cheque_in_hand' => ['label' => 'Cek belum dicairkan',          'help' => 'Didebit saat menerima cek, dikreditkan saat dana masuk bank.'],
            'cash_drawer_cash' => ['label' => 'Kas di laci kasir',             'help' => 'Akun kas yang dipengaruhi oleh kas masuk/keluar laci.'],
            'cash_drawer_transfer' => ['label' => 'Akun transfer kas laci',        'help' => 'Akun bank atau brankas yang digunakan untuk transfer laci kasir.'],
            'cash_drawer_suspense' => ['label' => 'Akun perantara kas laci',       'help' => 'Akun sementara untuk pengeluaran kas tanpa kategori.'],
            'receivable_reconciliation_offset' => ['label' => 'Offset rekonsiliasi piutang',   'help' => 'Penyeimbang ekuitas yang hanya digunakan saat koreksi selisih piutang historis.'],
        ],
    ],
];
