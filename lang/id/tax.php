<?php

return [
    'title' => 'Pajak',
    'sub' => 'Komponen pajak satuan, grup pengelompokan pajak, serta penetapan pajak bawaan untuk produk baru.',

    'hub' => [
        'components' => [
            'title' => 'Komponen Pajak',
            'sub' => 'Tarif pajak tunggal (misal: PPN 11%, PB1 10%).',
        ],
        'groups' => [
            'title' => 'Grup Pajak',
            'sub' => 'Gabungan beberapa komponen pajak yang diterapkan bersamaan pada produk.',
            'default_hint' => 'Pajak bawaan untuk produk baru: :name',
        ],
    ],

    'components' => [
        'title' => 'Komponen Pajak',
        'sub' => 'Pajak satuan atomik. Beberapa komponen dapat digabungkan ke dalam satu grup pajak.',
        'new' => 'Komponen baru',

        'badges' => [
            'inactive' => 'Nonaktif',
        ],

        'list' => [
            'title' => 'Semua Komponen',
            'empty' => 'Belum ada komponen pajak. Buat komponen pertama untuk mulai menyusun grup pajak.',
        ],

        'columns' => [
            'code' => 'Kode',
            'name' => 'Nama',
            'rate' => 'Tarif (%)',
            'actions' => 'Aksi',
        ],

        'fields' => [
            'code' => 'Kode',
            'code_help' => 'Pengenal sistem — misal: ID_PPN_11, PB1_RESTO_10.',
            'name' => 'Nama Pajak',
            'name_placeholder' => 'misal: PPN, Pajak Restoran',
            'rate' => 'Tarif',
            'rate_help' => 'Persentase (%). Gunakan beberapa komponen jika terdapat pajak majemuk.',
            'is_active' => 'Aktif',
        ],

        'editor' => [
            'empty_title' => 'Pilih komponen',
            'empty_sub' => 'Pilih dari daftar, atau klik "Komponen baru" untuk menambahkan.',
            'edit_title' => 'Edit komponen pajak',
            'edit_id' => 'Mengedit komponen',
            'edit_updated' => 'diperbarui',
            'new_title' => 'Komponen pajak baru',
            'new_sub' => 'Tentukan tarif tunggal. Grup pajak nantinya dapat menggabungkan beberapa komponen.',
        ],

        'actions' => [
            'save' => 'Simpan perubahan',
            'saving' => 'Menyimpan…',
            'create' => 'Buat komponen',
            'creating' => 'Membuat…',
            'discard' => 'Batalkan',
            'delete' => 'Hapus',
            'export' => 'Ekspor',
            'export_csv' => 'CSV',
            'export_xlsx' => 'Excel (XLSX)',
        ],

        'flash' => [
            'created' => 'Komponen pajak ":name" berhasil dibuat.',
            'updated' => 'Komponen pajak ":name" berhasil disimpan.',
            'deleted' => 'Komponen pajak ":name" berhasil dihapus.',
        ],

        'errors' => [
            'in_use' => '":name" digunakan oleh :count grup pajak. Hapus dari grup tersebut terlebih dahulu sebelum menghapus komponen ini.',
        ],
    ],

    'classifications' => [
        'title' => 'Klasifikasi Pajak',
        'sub' => 'Kelola klasifikasi pelaporan yang tersedia untuk grup pajak.',

        'crumb_parent' => 'Pengaturan',

        'list' => [
            'title' => 'Semua Klasifikasi',
            'empty_title' => 'Belum ada klasifikasi',
            'empty_sub' => 'Tambahkan klasifikasi pertama Anda untuk memulai.',
        ],

        'editor' => [
            'empty_title' => 'Pilih klasifikasi',
            'empty_sub' => 'Pilih dari daftar, atau klik "Klasifikasi baru" untuk menambahkan.',
            'edit_title' => 'Edit klasifikasi',
            'edit_id' => 'Mengedit',
            'edit_updated' => 'diperbarui',
            'new_title' => 'Klasifikasi baru',
            'new_sub' => 'Klasifikasi digunakan dalam pengelompokan laporan SPT dan pajak.',
        ],

        'fields' => [
            'name' => 'Nama Klasifikasi',
            'name_placeholder' => 'misal: Kena Pajak (Taxable)',
            'slug' => 'Slug / Kode singkat',
            'slug_placeholder' => 'misal: kena_pajak',
            'slug_help' => 'Huruf kecil, angka, dan garis bawah saja. Tidak dapat diubah setelah digunakan.',
            'description' => 'Deskripsi',
            'description_placeholder' => 'Opsional — ditampilkan di editor grup pajak.',
            'sort_order' => 'Urutan tampilan',
            'is_active' => 'Aktif',
        ],

        'actions' => [
            'new' => 'Klasifikasi baru',
            'save' => 'Simpan perubahan',
            'create' => 'Buat klasifikasi',
            'discard' => 'Batalkan',
            'delete' => 'Hapus',
            'delete_confirm' => 'Ini akan menghapus klasifikasi secara permanen.',
            'export' => 'Ekspor',
            'export_csv' => 'CSV',
            'export_xlsx' => 'Excel (XLSX)',
        ],

        'actions_extra' => [
            'saving' => 'Menyimpan…',
            'creating' => 'Membuat…',
        ],

        'flash' => [
            'created' => 'Klasifikasi ":name" berhasil dibuat.',
            'updated' => 'Klasifikasi ":name" berhasil disimpan.',
            'deleted' => 'Klasifikasi ":name" berhasil dihapus.',
        ],

        'errors' => [
            'in_use' => '":name" digunakan oleh :count grup pajak. Pindahkan grup tersebut terlebih dahulu.',
        ],
    ],

    'groups' => [
        'title' => 'Grup Pajak',
        'sub' => 'Paket pajak yang diterapkan langsung pada produk. Tentukan komponen yang tergabung di dalamnya.',
        'new' => 'Grup baru',

        'list' => [
            'title' => 'Semua Grup Pajak',
            'empty' => 'Belum ada grup pajak. Buat grup pertama untuk disematkan pada produk katalog.',
            'filter_all' => 'Semua',
            'filter_active' => 'Aktif',
            'filter_inactive' => 'Nonaktif',
            'filter_status_label' => 'Status',
        ],

        'badges' => [
            'default' => 'Bawaan (Default)',
            'inactive' => 'Nonaktif',
        ],

        'columns' => [
            'code' => 'Kode',
            'name' => 'Nama',
            'classification' => 'Klasifikasi',
            'rate' => 'Tarif Gabungan',
            'usage' => 'Digunakan',
            'actions' => 'Aksi',
        ],

        'classifications' => [
            'taxable' => 'Kena Pajak',
            'nil_rated' => 'Tarif Nol (Nil-rated)',
            'zero_rated' => 'Ekspor / Bebas Pajak 0%',
            'exempt' => 'Dibebaskan dari Pajak',
            'composition' => 'Komposisi Khusus',
            'reverse_charge' => 'Pajak Balik (Reverse Charge)',
        ],

        'fields' => [
            'code' => 'Kode Grup',
            'name' => 'Nama Grup Pajak',
            'classification' => 'Klasifikasi Pelaporan',
            'classification_help' => 'Digunakan untuk laporan pajak. Gunakan Kena Pajak untuk PPN umum; Bebas/Nol akan menghasilkan perhitungan 0% namun dikelompokkan secara terpisah.',
            'components' => 'Komponen Penyusun',
            'components_help' => 'Pilih tarif komponen yang berpartisipasi. Urutan pemilihan akan dipertahankan pada rincian struk belanja.',
            'components_empty' => 'Belum ada komponen pajak. Buat terlebih dahulu di Pajak → Komponen Pajak.',
            'components_picker_placeholder' => 'Pilih satu atau beberapa komponen…',
            'rate_total_label' => 'Tarif Gabungan',
            'is_inclusive' => 'Harga jual sudah termasuk pajak',
            'is_inclusive_help' => 'Aktif = harga pada label produk sudah termasuk pajak. Nonaktif = pajak ditambahkan di atas harga saat di kasir.',
            'is_default' => 'Gunakan sebagai pajak bawaan untuk produk baru',
            'is_default_help' => 'Hanya satu grup pajak yang dapat menjadi bawaan — mengaktifkan ini akan menonaktifkan status bawaan pada grup lain.',
            'is_active' => 'Aktif',
        ],

        'editor' => [
            'empty_title' => 'Pilih grup pajak',
            'empty_sub' => 'Pilih dari daftar, atau klik "Grup baru" untuk menambahkan.',
            'edit_title' => 'Edit grup pajak',
            'edit_id' => 'Mengedit grup',
            'edit_updated' => 'diperbarui',
            'new_title' => 'Grup pajak baru',
            'new_sub' => 'Gabungkan satu atau lebih komponen. Tarif gabungan adalah total yang akan diterapkan pada transaksi.',
        ],

        'actions' => [
            'save' => 'Simpan perubahan',
            'saving' => 'Menyimpan…',
            'create' => 'Buat grup',
            'creating' => 'Membuat…',
            'discard' => 'Batalkan',
            'delete' => 'Hapus',
            'export' => 'Ekspor',
            'export_csv' => 'CSV',
            'export_xlsx' => 'Excel (XLSX)',
            'manage_classifications' => 'Kelola Klasifikasi',
        ],

        'flash' => [
            'created' => 'Grup pajak ":name" berhasil dibuat.',
            'updated' => 'Grup pajak ":name" berhasil disimpan.',
            'deleted' => 'Grup pajak ":name" berhasil dihapus.',
            'deleted_with_move' => 'Grup pajak ":name" dihapus. :count produk dipindahkan ke grup ":target".',
        ],

        'errors' => [
            'in_use' => '":name" digunakan oleh :count produk/kategori. Pilih grup pengganti sebelum menghapusnya.',
            'default_undeletable' => '":name" adalah grup pajak bawaan sistem dan tidak dapat dihapus.',
        ],

        'delete_dialog' => [
            'title' => 'Hapus grup pajak ":name"?',
            'has_referrers' => ':count barang terhubung ke grup ini. Pilih grup tujuan pemindahan.',
            'empty_confirm' => 'Tidak ada produk atau kategori yang menggunakan grup ini. Aman untuk dihapus.',
            'move_label' => 'Pindahkan produk ke…',
            'move_placeholder' => 'Cari grup pajak',
            'default_hint' => 'Kosongkan untuk mengembalikannya ke grup pajak bawaan ":name".',
            'cancel' => 'Batal',
            'confirm' => 'Hapus grup pajak',
        ],
    ],
];
