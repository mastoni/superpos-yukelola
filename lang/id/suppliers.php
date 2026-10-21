<?php

return [
    'title' => 'Pemasok',
    'sub' => 'Pihak dan bisnis tempat Anda membeli barang — identitas, kontak, pajak, dan hutang usaha.',
    'crumb_parent' => 'Pasokan',
    'new' => 'Pemasok baru',

    'columns' => [
        'code' => 'Kode',
        'name' => 'Nama',
        'contact_person' => 'Kontak person',
        'phone' => 'Telepon',
        'city' => 'Kota',
        'outstanding' => 'Hutang Belum Lunas',
        'status' => 'Status',
        'actions' => 'Aksi',
    ],

    'summary' => [
        'total' => 'Pemasok',
        'payable' => 'Total hutang usaha',
        'active' => 'Aktif',
    ],

    'list' => [
        'title' => 'Semua pemasok (:count)',
        'of' => 'dari',
    ],

    'badges' => [
        'active' => 'Aktif',
        'inactive' => 'Nonaktif',
    ],

    'filter' => [
        'status_active' => 'Aktif',
        'status_inactive' => 'Nonaktif',
        'status_all' => 'Semua',
        'search' => 'Cari nama, kode, kontak, telepon, email…',
    ],

    'empty_state' => [
        'title' => 'Belum ada pemasok',
        'sub' => 'Tambahkan pemasok pertama Anda untuk mulai melacak pembelian dan hutang dagang.',
    ],

    'sections' => [
        'profile' => 'Profil',
        'profile_sub' => 'Identitas dan rincian kontak.',
        'tax' => 'Pajak & legalitas',
        'tax_sub' => 'Identitas pajak yang digunakan pada faktur pembelian.',
        'address' => 'Alamat utama',
        'address_sub' => 'Digunakan pada pesanan pembelian dan rekening koran pemasok.',
        'terms' => 'Ketentuan Pembayaran',
        'terms_sub' => 'Mata uang default dan jangka waktu pembayaran.',
        'notes' => 'Catatan',
        'notes_sub' => 'Catatan internal, hanya terlihat oleh staf.',
    ],

    'fields' => [
        'name' => 'Nama',
        'code' => 'Kode',
        'code_help' => 'Biarkan kosong untuk pembuatan otomatis (S-000123).',
        'business_name' => 'Nama bisnis / perusahaan',
        'contact_person' => 'Nama kontak person',
        'phone' => 'Telepon',
        'email' => 'Email',
        'gstin' => 'NPWP / GSTIN',
        'gstin_placeholder' => '15–16 karakter',
        'pan' => 'NIK / Nomor Identitas',
        'pan_placeholder' => 'Nomor identitas pajak / resmi',
        'tax_reg' => 'Nomor pokok wajib pajak (NPWP)',
        'default_currency' => 'Mata uang default',
        'default_currency_help' => 'Mata uang yang digunakan pada faktur pemasok ini. Standarnya mengikuti mata uang dasar toko.',
        'payment_terms_days' => 'Jangka waktu pembayaran (hari)',
        'payment_terms_days_help' => 'Jatuh tempo (misal: 30 = Net 30 hari). Digunakan untuk menghitung tanggal jatuh tempo pembelian baru.',
        'days' => 'hari',
        'address_line1' => 'Alamat baris 1',
        'address_line2' => 'Alamat baris 2',
        'city' => 'Kota',
        'state' => 'Provinsi / Wilayah',
        'postal_code' => 'Kode pos',
        'country' => 'Negara',
        'notes' => 'Catatan',
        'is_active' => 'Aktif',
    ],

    'tabs' => [
        'overview' => 'Ringkasan',
        'purchases' => 'Pembelian',
        'returns' => 'Retur',
        'statement' => 'Rekening Koran',
        'payments' => 'Pembayaran',
    ],

    'open_purchases' => [
        'title' => 'Pembelian belum lunas',
        'sub' => ':count pembelian dengan sisa hutang — terlama di awal. Klik baris mana saja untuk membuka transaksi pembelian.',
        'purchase' => 'No. Pembelian',
        'date' => 'Tanggal',
        'due' => 'Jatuh tempo',
        'age' => 'Umur piutang',
        'balance' => 'Sisa hutang',
    ],

    'purchases' => [
        'list_title' => 'Pembelian',
        'number' => 'No. Pembelian',
        'invoice' => 'Faktur pemasok',
        'date' => 'Tanggal',
        'store' => 'Toko',
        'total' => 'Total',
        'paid' => 'Dibayar',
        'balance' => 'Sisa Tagihan',
        'status' => 'Status',
        'empty_title' => 'Belum ada pembelian',
        'empty_sub' => 'Pesanan pembelian yang dibuat untuk pemasok ini akan muncul di sini.',
        'capped' => 'Menampilkan :count transaksi terbaru. Buka menu Pembelian untuk daftar lengkap.',
        'view_all' => 'Semua pembelian',
        'search' => 'Cari no. pembelian…',
    ],

    'returns' => [
        'title' => 'Retur Pembelian',
        'number' => 'No. Retur',
        'date' => 'Tanggal',
        'against' => 'Dari pembelian',
        'amount' => 'Jumlah',
        'empty_title' => 'Belum ada retur',
        'empty_sub' => 'Barang yang dikembalikan ke pemasok ini akan muncul di sini.',
        'capped' => 'Menampilkan :count transaksi terbaru. Buka Retur Pembelian untuk daftar lengkap.',
        'view_all' => 'Semua retur',
        'search' => 'Cari no. retur…',
    ],

    'payments' => [
        'title' => 'Pembayaran keluar',
        'sub' => 'Uang yang dibayarkan ke pemasok ini, termasuk uang muka dan kredit yang diterapkan ke tagihan pembelian.',
        'date' => 'Tanggal',
        'reference' => 'No. Pembelian',
        'method' => 'Metode',
        'amount' => 'Jumlah',
        'note' => 'Referensi / Catatan',
        'unallocated' => 'Deposit / Akun',
        'voided' => 'Dibatalkan',
        'empty_title' => 'Belum ada pembayaran',
        'empty_sub' => 'Pembayaran yang dilakukan ke pemasok ini akan muncul di sini.',
        'capped' => 'Menampilkan :count transaksi terbaru. Buka Pembayaran Pemasok untuk daftar lengkap.',
        'view_all' => 'Semua pembayaran',
        'search' => 'Cari no. pembelian…',
        'record' => 'Catat pembayaran',
    ],

    'statement_tab' => [
        'title' => 'Rekening koran pemasok',
        'sub' => ':from sampai :to — saldo berjalan atas kewajiban kita kepada pemasok ini.',
    ],

    'kpis' => [
        'lifetime_spend' => 'Total belanja seumur hidup',
        'lifetime_sub' => ':count pembelian, setelah dikurangi retur',
        'outstanding' => 'Hutang belum lunas',
        'outstanding_sub' => 'Kredit pada akun',
        'last_order' => 'Pesanan terakhir',
        'last_order_none' => 'Belum pernah',
        'since' => 'Pemasok sejak',
        'terms' => 'Ketentuan pembayaran',
    ],

    'actions' => [
        'export' => 'Ekspor',
        'export_csv' => 'CSV',
        'export_xlsx' => 'Excel (XLSX)',
        'discard' => 'Batalkan',
        'save' => 'Simpan perubahan',
        'create' => 'Buat pemasok',
        'edit' => 'Edit',
        'view' => 'Lihat profil',
        'delete' => 'Hapus',
        'toggle_active' => 'Ubah status aktif',
        'tip_active_on' => 'Aktif — klik untuk menonaktifkan.',
        'tip_active_off' => 'Nonaktif — klik untuk mengaktifkan.',
    ],

    'flash' => [
        'created' => 'Pemasok ":name" berhasil dibuat.',
        'updated' => 'Pemasok ":name" berhasil diperbarui.',
        'deleted' => 'Pemasok ":name" berhasil dihapus.',
        'activated' => 'Pemasok ":name" diaktifkan.',
        'deactivated' => 'Pemasok ":name" dinonaktifkan.',
    ],

    'errors' => [
        'has_balance' => 'Tidak dapat dihapus — pemasok memiliki sisa saldo hutang sebesar :amount. Lunasi saldo terlebih dahulu atau nonaktifkan saja.',
        'has_open_purchases' => 'Tidak dapat menghapus “:name” — masih terdapat :count pembelian belum lunas. Selesaikan pembelian terlebih dahulu.',
    ],

    'confirm_delete' => [
        'title' => 'Hapus pemasok ":name"?',
        'message' => 'Ini akan menghapus pemasok secara soft-delete. Riwayat transaksi pembelian masa lalu tetap tersimpan utuh.',
        'confirm' => 'Hapus',
    ],
];
