<?php

return [
    'title' => 'Merek & Brand',
    'sub' => 'Kelola produsen dan merek produk Anda untuk laporan dan filter yang lebih rapi.',
    'crumb_parent' => 'Katalog',

    'actions' => [
        'new' => 'Merek baru',
        'save' => 'Simpan perubahan',
        'create' => 'Buat merek',
        'discard' => 'Batalkan',
        'delete' => 'Hapus',
        'delete_confirm' => 'Hapus merek ini? Produk di dalamnya akan dipindahkan ke merek default kecuali Anda memilih merek lain.',
        'close' => 'Tutup',
        'export' => 'Ekspor',
        'export_csv' => 'Unduh CSV',
        'export_xlsx' => 'Unduh Excel (XLSX)',
        'import' => 'Impor',
        'bulk_logos' => 'Unggah logo massal',
    ],

    // CSV / XLSX import wizard
    'import' => [
        'title' => 'Impor merek',
        'sub' => 'Unggah file CSV atau Excel untuk menambahkan atau memperbarui merek secara massal. File logo dapat diunggah secara terpisah pada langkah berikutnya.',
        'back' => 'Kembali ke merek',
        'cancel' => 'Batal',

        'sample_title' => 'Gunakan file contoh',
        'sample_sub' => 'Unduh file template contoh dengan format kolom yang benar. Ganti contoh data dengan merek Anda, lalu unggah di bawah ini.',
        'sample_csv' => 'Unduh contoh (CSV)',
        'sample_xlsx' => 'Unduh contoh (Excel)',

        'guide_title' => 'Panduan pengisian',
        'guide_required' => 'Kolom wajib: Nama. Deskripsi, Logo, dan Status Aktif bersifat opsional.',
        'guide_identity' => 'Nama adalah penentu merek: nama yang sudah ada akan memperbarui merek tersebut, nama baru akan membuat merek baru.',
        'guide_logo' => 'Kolom logo: masukkan NAMA FILE gambar di sini (cth. acme.png) — bukan gambar langsung. Setelah impor, gunakan fitur “Unggah logo massal” untuk mencocokkan gambar secara otomatis.',
        'guide_bool' => 'Status aktif menerima Ya/Tidak, Yes/No, 1/0. Kosong dianggap Ya.',
        'guide_extra' => 'Anda dapat menambah, menghapus, atau mengatur urutan kolom — Anda dapat memetakan kolom sebelum data disimpan.',

        'upload_label' => 'Pilih file Anda',
        'upload_hint' => 'CSV atau Excel (.xlsx), maksimal 5 MB.',
        'upload_button' => 'Unggah dan lanjutkan',

        'review_title' => 'Tinjau kolom',
        'review_sub' => 'Pastikan kolom spreadsheet cocok dengan data merek di sistem.',
        'col_header' => 'Kolom Anda',
        'col_maps_to' => 'Dipetakan ke',
        'ignore_column' => 'Abaikan kolom ini',
        'preview_table' => 'Pratinjau (menampilkan :count baris pertama)',
        'no_data' => 'Tidak ada baris data dalam file.',
        'commit_button' => 'Impor merek',

        'fields' => [
            'name' => 'Nama Merek',
            'description' => 'Deskripsi',
            'logo' => 'Logo (nama file atau URL)',
            'logo_url' => 'URL Logo (diunduh otomatis)',
            'is_active' => 'Aktif',
        ],

        'done_title' => 'Impor selesai',
        'done_sub' => ':created dibuat · :updated diperbarui · :skipped dilewati',
        'done_back' => 'Kembali ke merek',
        'count_created' => 'Dibuat',
        'count_updated' => 'Diperbarui',
        'count_skipped' => 'Dilewati',
        'errors_heading' => 'Baris yang dilewati',
        'error_row' => 'Baris :row',

        'logos_title' => 'Sekarang tambahkan logo',
        'logos_sub' => ':count merek mencantumkan nama file logo. Masukkan folder gambar tersebut untuk mencocokkan logo secara otomatis.',
        'logos_button' => 'Unggah logo sekarang',

        'errors' => [
            'file_required' => 'Pilih file CSV atau Excel yang ingin diimpor.',
            'bad_type' => 'Format file tidak didukung. Unggah file .csv atau .xlsx.',
            'unreadable' => 'File tidak dapat dibaca (:error). Pastikan file dapat dibuka di Excel.',
            'not_a_sheet' => 'File tidak memiliki kolom header yang valid. Unggah file .csv atau .xlsx yang benar.',
            'session_expired' => 'File yang diunggah sudah kedaluwarsa. Silakan unggah kembali.',
            'name_required' => 'Petakan salah satu kolom ke “Nama” — merek dicocokkan berdasarkan nama.',
            'row_failed' => 'Baris :row gagal disimpan: :reason',
            'schema_outdated' => 'Struktur database belum diperbarui. Jalankan update database dan coba lagi.',
            'duplicate' => 'merek dengan nama ini sudah ada.',
            'generic' => 'baris tidak dapat disimpan. Hubungi administrator sistem.',
        ],
    ],

    // Bulk logo uploader
    'bulk_logos' => [
        'title' => 'Unggah logo massal',
        'sub' => 'Pasang logo ke banyak merek sekaligus — unggah sekumpulan file gambar atau masukkan tautan URL gambar.',
        'back' => 'Kembali ke merek',
        'how_title' => 'Cara kerja',
        'how_1' => 'Pada impor merek Anda, isi kolom Logo dengan nama file gambar (cth. acme.png).',
        'how_2' => 'Tarik file logo Anda ke area di bawah. Sistem akan mencocokkan file berdasarkan nama file tersebut.',
        'how_3' => 'Logo diunggah bertahap, sehingga aman di server mana pun.',
        'pending_hint' => ':count merek telah menentukan nama file logo namun belum memiliki gambar.',
        'no_pending' => 'Tidak ada merek yang sedang menunggu logo.',
        'drop_title' => 'Tarik file logo ke sini',
        'drop_hint' => 'atau klik untuk memilih file · JPG, PNG, atau WEBP · maks :size MB per file',
        'matching' => 'Mencocokkan nama file…',
        'summary' => ':matched cocok · :unmatched tidak cocok',
        'matched_title' => 'Cocok (:count)',
        'unmatched_title' => 'Tidak Cocok (:count)',
        'reason_no_match' => 'Tidak ada merek yang mencantumkan nama file ini',
        'reason_duplicate' => 'Lebih dari satu merek mencantumkan nama file ini',
        'has_image_note' => 'sudah memiliki logo — akan ditimpa',
        'upload_button' => 'Unggah :count logo',
        'uploading' => 'Mengunggah… :done dari :total',
        'done_summary' => ':stored logo berhasil dipasang.',
        'clear' => 'Bersihkan',
        'start_over' => 'Unggah lagi',
        'unmatched_download' => 'Unduh daftar file tidak cocok',
        'nothing_matched' => 'Tidak ada file yang cocok dengan merek. Periksa kesamaan nama file dengan data impor Anda.',
        'error_generic' => 'Beberapa logo gagal diunggah. Lihat daftar di atas.',

        'tab_files' => 'Unggah file gambar',
        'tab_urls' => 'Ambil dari URL tautan',

        'urls' => [
            'title' => 'Ambil logo dari tautan URL',
            'how_title' => 'Cara kerja',
            'how_1' => 'Cantumkan tautan pada kolom Logo saat impor — atau tempel daftar nama merek dan URL di bawah.',
            'how_2' => 'Sistem akan memeriksa tautan, mengunduh gambar, dan memasangnya ke merek. Hanya mendukung JPG, PNG, dan WEBP via HTTPS.',
            'how_3' => 'Logo diunduh bertahap secara aman. Biarkan tab ini terbuka saat proses berjalan.',

            'source_pending' => 'Menunggu dari impor',
            'source_paste' => 'Tempel daftar',

            'pending_hint' => ':count merek memiliki tautan logo yang siap diunduh.',
            'no_pending' => 'Tidak ada merek yang menunggu unduhan logo saat ini.',
            'load_pending' => 'Tampilkan daftar',

            'paste_label' => 'Satu merek per baris: nama merek, diikuti URL gambar',
            'paste_hint' => 'Pisahkan dengan koma atau tab — Anda dapat menyalin dua kolom langsung dari spreadsheet.',
            'paste_placeholder' => "Coca-Cola, https://cdn.example.com/coca-cola.png\nPepsi, https://cdn.example.com/pepsi.png",
            'paste_button' => 'Periksa URL ini',
            'paste_empty' => 'Tempel setidaknya satu nama merek dan URL terlebih dahulu.',

            'matching' => 'Memeriksa URL…',
            'summary' => ':matched siap · :unmatched dilewati',
            'matched_title' => 'Siap diunduh (:count)',
            'unmatched_title' => 'Dilewati (:count)',
            'nothing_matched' => 'Tidak ada yang dapat diunduh. Pastikan nama merek cocok dengan data merek Anda.',
            'has_image_note' => 'sudah memiliki logo — akan ditimpa',

            'fetch_button' => 'Unduh :count logo',
            'fetching' => 'Mengunduh… :done dari :total',
            'done_summary' => ':fetched logo berhasil dipasang.',
            'clear' => 'Bersihkan',
            'start_over' => 'Mulai ulang',
            'failed_download' => 'Unduh daftar gagal',
            'unmatched_download' => 'Unduh daftar dilewati',

            'reason_bad_format' => 'Baris membutuhkan nama merek dan URL',
            'reason_bad_url' => 'Bukan tautan http/https yang valid',
            'reason_no_match' => 'Merek dengan nama ini tidak ditemukan',
            'reason_duplicate' => 'Merek ini tercantum lebih dari sekali',

            'err_blocked_url' => 'Tautan ditolak — harus berupa alamat https:// di internet publik',
            'err_too_many_redirects' => 'Terlalu banyak pengalihan (redirect)',
            'err_unreachable' => 'Tidak dapat terhubung ke server tautan',
            'err_http_error' => 'Tautan tidak mengembalikan file gambar (404 atau sejenisnya)',
            'err_too_large' => 'Ukuran gambar terlalu besar',
            'err_not_an_image' => 'Bukan file gambar JPG, PNG, atau WEBP',
            'err_store_failed' => 'Gagal menyimpan file gambar ke disk',
            'err_not_found' => 'Merek dengan nama ini tidak ditemukan',
            'err_generic' => 'Gagal mengunduh gambar',

            'blocked_title' => 'Gambar tidak dapat diunduh',
            'blocked_body' => 'Beberapa tautan pertama tidak dapat diakses. Sebagian server hosting memblokir koneksi keluar. Hubungi penyedia hosting atau gunakan tab unggah file gambar.',
            'blocked_continue' => 'Tetap coba tautan lainnya',
            'error_generic' => 'Beberapa logo gagal diunduh. Lihat daftar di atas.',
        ],
    ],

    'list' => [
        'title' => 'Semua merek',
        'products' => 'produk',
        'empty_title' => 'Belum ada merek',
        'empty_sub' => 'Klik “Merek baru” untuk menambahkan merek pertama.',
    ],

    'row' => [
        'toggle_active' => 'Alihkan status aktif',
    ],

    'editor' => [
        'empty_title' => 'Pilih merek',
        'empty_sub' => 'Pilih baris untuk melihat detailnya, atau klik “Merek baru” untuk menambahkan.',
    ],

    'drawer' => [
        'edit_title' => 'Ubah merek',
        'new_title' => 'Merek baru',
        'edit_id' => 'ID',
        'edit_updated' => 'diperbarui',
        'new_sub' => 'Kelompokkan produk berdasarkan produsen atau merek untuk laporan dan filter yang lebih rapi.',
        'name' => 'Nama merek',
        'name_placeholder' => 'cth. Unilever',
        'logo' => 'Logo',
        'description' => 'Deskripsi',
        'description_placeholder' => 'Catatan opsional mengenai merek ini',
        'is_active' => 'Aktif (ditampilkan pada pemilihan produk)',
        'stats_products' => 'Total Produk',
        'stats_revenue' => 'Pendapatan · 30 hari',
    ],

    'errors' => [
        'title' => 'Silakan periksa kolom yang ditandai',
        'default_protected' => '“:name” adalah merek default dan tidak dapat dihapus.',
        'replacement_is_self' => 'Tidak dapat memindahkan produk ke merek yang sedang dihapus.',
        'replacement_not_found' => 'Merek pengganti tidak ditemukan.',
    ],

    'actions_extra' => [
        'saving' => 'Menyimpan…',
        'creating' => 'Membuat…',
    ],

    'flash' => [
        'created' => 'Merek “:name” berhasil dibuat.',
        'updated' => 'Merek “:name” berhasil diperbarui.',
        'deleted' => 'Merek “:name” berhasil dihapus.',
        'deleted_with_move' => 'Merek “:name” dihapus dan :count produk dipindahkan ke “:target”.',
    ],

    'delete_dialog' => [
        'title' => 'Hapus “:name”?',
        'no_products' => 'Merek ini tidak memiliki produk. Aman untuk dihapus.',
        'has_products' => ':count produk terhubung ke merek ini. Pilih merek tujuan pemindahan produk.',
        'move_label' => 'Pindahkan produk ke…',
        'move_placeholder' => 'Cari merek',
        'default_hint' => 'Default akan dialihkan ke “:name” jika dibiarkan kosong.',
        'confirm' => 'Hapus merek',
        'cancel' => 'Batal',
    ],
];
