/**
 * SuperPOS Mobile - Google Apps Script Web App Endpoint
 * 
 * CARA PEMASANGAN DI GOOGLE SHEETS:
 * 1. Buat Spreadsheet baru di Google Sheets (misal: "SuperPOS Backup & Laporan").
 * 2. Klik menu "Ekstensi" (Extensions) > "Apps Script".
 * 3. Hapus kode default, paste seluruh kode ini ke dalam editor `Code.gs`.
 * 4. Klik tombol "Terapkan" (Deploy) > "Penerapan Baru" (New Deployment).
 * 5. Pilih jenis: "Aplikasi Web" (Web app).
 * 6. Set:
 *    - Jalankan sebagai (Execute as): "Saya" (Me)
 *    - Yang memiliki akses (Who has access): "Siapa saja" (Anyone) -> Ini penting agar aplikasi HP dapat mengirim data tanpa login akun Google.
 * 7. Klik "Terapkan" (Deploy), lalu izinkan hak akses (Authorize access).
 * 8. Salin URL Aplikasi Web (Web App URL) yang berakhiran `/exec`.
 * 9. Tempelkan URL tersebut ke Pengaturan Google Sheets di aplikasi SuperPOS Mobile!
 */

function doPost(e) {
  var lock = LockService.getScriptLock();
  lock.tryLock(30000);

  try {
    var rawData = e.postData.contents;
    var payload = JSON.parse(rawData);
    var action = payload.action; // 'ping', 'backup_all', 'sync_sales', 'sync_products', 'sync_expenses'
    var data = payload.data || {};
    var ss = SpreadsheetApp.getActiveSpreadsheet();

    if (action === 'ping') {
      return responseJson({
        status: 'success',
        message: 'Koneksi ke Google Sheets SuperPOS Berhasil!',
        sheetName: ss.getName(),
        serverTime: new Date().toISOString()
      });
    }

    if (action === 'backup_all') {
      // 1. Simpan Info Toko & Metadata
      if (data.store) {
        saveStoreInfo(ss, data.store);
      }

      // 2. Simpan Kategori
      if (data.categories && Array.isArray(data.categories)) {
        saveCategories(ss, data.categories);
      }

      // 3. Simpan Produk
      if (data.products && Array.isArray(data.products)) {
        saveProducts(ss, data.products);
      }

      // 4. Simpan Transaksi Penjualan
      if (data.sales && Array.isArray(data.sales)) {
        saveSales(ss, data.sales);
      }

      // 5. Simpan Item Penjualan
      if (data.saleItems && Array.isArray(data.saleItems)) {
        saveSaleItems(ss, data.saleItems);
      }

      // 6. Simpan Pengeluaran
      if (data.expenses && Array.isArray(data.expenses)) {
        saveExpenses(ss, data.expenses);
      }

      return responseJson({
        status: 'success',
        message: 'Backup semua data ke Google Sheets berhasil!',
        syncedAt: new Date().toISOString()
      });
    }

    return responseJson({
      status: 'error',
      message: 'Aksi tidak dikenal: ' + action
    });

  } catch (err) {
    return responseJson({
      status: 'error',
      message: err.toString()
    });
  } finally {
    lock.releaseLock();
  }
}

function doGet(e) {
  try {
    var action = e.parameter.action || 'ping';
    var ss = SpreadsheetApp.getActiveSpreadsheet();

    if (action === 'ping') {
      return responseJson({
        status: 'success',
        message: 'Google Apps Script SuperPOS Aktif!',
        spreadsheetName: ss.getName()
      });
    }

    if (action === 'restore_products') {
      var sheet = ss.getSheetByName('Katalog_Produk');
      if (!sheet) {
        return responseJson({ status: 'success', products: [], categories: [] });
      }

      var rows = sheet.getDataRange().getValues();
      var products = [];
      for (var i = 1; i < rows.length; i++) {
        var r = rows[i];
        if (r[0]) {
          products.push({
            id: r[0],
            sku: r[1],
            name: r[2],
            category: r[3],
            costPrice: Number(r[4]) || 0,
            sellPrice: Number(r[5]) || 0,
            stock: Number(r[6]) || 0,
            unit: r[7] || 'Pcs'
          });
        }
      }

      // Tarik Kategori
      var catSheet = ss.getSheetByName('Kategori');
      var categories = [];
      if (catSheet) {
        var catRows = catSheet.getDataRange().getValues();
        for (var j = 1; j < catRows.length; j++) {
          if (catRows[j][0]) {
            categories.push({
              id: catRows[j][0],
              name: catRows[j][1]
            });
          }
        }
      }

      return responseJson({
        status: 'success',
        products: products,
        categories: categories
      });
    }

    return responseJson({
      status: 'error',
      message: 'Aksi GET tidak didukung'
    });
  } catch (err) {
    return responseJson({
      status: 'error',
      message: err.toString()
    });
  }
}

function saveStoreInfo(ss, store) {
  var sheet = getOrCreateSheet(ss, 'Info_Toko');
  sheet.clear();
  sheet.appendRow(['Parameter', 'Nilai Terakhir']);
  sheet.appendRow(['Nama Toko', store.name || '']);
  sheet.appendRow(['Alamat', store.address || '']);
  sheet.appendRow(['No. Telepon / WhatsApp', store.phone || '']);
  sheet.appendRow(['Catatan Struk', store.footer || '']);
  sheet.appendRow(['Terakhir Disinkron', new Date().toLocaleString('id-ID')]);
  formatHeader(sheet, 2);
}

function saveCategories(ss, categories) {
  var sheet = getOrCreateSheet(ss, 'Kategori');
  sheet.clear();
  sheet.appendRow(['ID Kategori', 'Nama Kategori']);
  for (var i = 0; i < categories.length; i++) {
    sheet.appendRow([categories[i].id, categories[i].name]);
  }
  formatHeader(sheet, 2);
}

function saveProducts(ss, products) {
  var sheet = getOrCreateSheet(ss, 'Katalog_Produk');
  sheet.clear();
  sheet.appendRow(['ID', 'Barcode / SKU', 'Nama Produk', 'Kategori', 'Harga Modal (Rp)', 'Harga Jual (Rp)', 'Stok', 'Satuan', 'Terakhir Update']);
  for (var i = 0; i < products.length; i++) {
    var p = products[i];
    sheet.appendRow([
      p.id,
      p.sku || '-',
      p.name,
      p.category || 'Umum',
      p.costPrice || 0,
      p.sellPrice || 0,
      p.stock || 0,
      p.unit || 'Pcs',
      p.updatedAt || new Date().toISOString()
    ]);
  }
  formatHeader(sheet, 9);
}

function saveSales(ss, sales) {
  var sheet = getOrCreateSheet(ss, 'Riwayat_Penjualan');
  // Jika sheet baru dibuat, buat header
  if (sheet.getLastRow() === 0) {
    sheet.appendRow(['Invoice ID', 'No Faktur', 'Tanggal Transaksi', 'Pelanggan', 'Metode Bayar', 'Subtotal', 'Diskon', 'Pajak', 'Total Akhir', 'Diterima', 'Kembalian', 'Status']);
    formatHeader(sheet, 12);
  }

  // Cek ID yang sudah ada agar tidak duplikat
  var existingIds = {};
  var lastRow = sheet.getLastRow();
  if (lastRow > 1) {
    var idRange = sheet.getRange(2, 1, lastRow - 1, 1).getValues();
    for (var k = 0; k < idRange.length; k++) {
      existingIds[idRange[k][0]] = true;
    }
  }

  for (var i = 0; i < sales.length; i++) {
    var s = sales[i];
    if (!existingIds[s.id]) {
      sheet.appendRow([
        s.id,
        s.invoiceNumber,
        s.createdAt,
        s.customerName || 'Pelanggan Umum',
        s.paymentMethod || 'TUNAI',
        s.subtotal || 0,
        s.discount || 0,
        s.tax || 0,
        s.total || 0,
        s.paidAmount || 0,
        s.changeAmount || 0,
        s.status || 'PAID'
      ]);
    }
  }
}

function saveSaleItems(ss, items) {
  var sheet = getOrCreateSheet(ss, 'Detail_Item_Penjualan');
  if (sheet.getLastRow() === 0) {
    sheet.appendRow(['Item ID', 'Invoice ID', 'Nama Produk', 'Qty', 'Harga Satuan', 'Subtotal']);
    formatHeader(sheet, 6);
  }

  var existingIds = {};
  var lastRow = sheet.getLastRow();
  if (lastRow > 1) {
    var idRange = sheet.getRange(2, 1, lastRow - 1, 1).getValues();
    for (var k = 0; k < idRange.length; k++) {
      existingIds[idRange[k][0]] = true;
    }
  }

  for (var i = 0; i < items.length; i++) {
    var it = items[i];
    if (!existingIds[it.id]) {
      sheet.appendRow([
        it.id,
        it.saleId,
        it.productName,
        it.qty,
        it.unitPrice,
        it.subtotal
      ]);
    }
  }
}

function saveExpenses(ss, expenses) {
  var sheet = getOrCreateSheet(ss, 'Pengeluaran_Toko');
  if (sheet.getLastRow() === 0) {
    sheet.appendRow(['ID', 'Tanggal', 'Judul Pengeluaran', 'Kategori', 'Jumlah (Rp)', 'Catatan']);
    formatHeader(sheet, 6);
  }

  var existingIds = {};
  var lastRow = sheet.getLastRow();
  if (lastRow > 1) {
    var idRange = sheet.getRange(2, 1, lastRow - 1, 1).getValues();
    for (var k = 0; k < idRange.length; k++) {
      existingIds[idRange[k][0]] = true;
    }
  }

  for (var i = 0; i < expenses.length; i++) {
    var ex = expenses[i];
    if (!existingIds[ex.id]) {
      sheet.appendRow([
        ex.id,
        ex.createdAt,
        ex.title,
        ex.category || 'Operasional',
        ex.amount || 0,
        ex.note || ''
      ]);
    }
  }
}

function getOrCreateSheet(ss, name) {
  var sheet = ss.getSheetByName(name);
  if (!sheet) {
    sheet = ss.insertSheet(name);
  }
  return sheet;
}

function formatHeader(sheet, cols) {
  var header = sheet.getRange(1, 1, 1, cols);
  header.setBackground('#1E3A8A'); // Indigo / Navy
  header.setFontColor('#FFFFFF');
  header.setFontWeight('bold');
  header.setHorizontalAlignment('center');
  sheet.autoResizeColumns(1, cols);
}

function responseJson(obj) {
  return ContentService.createTextOutput(JSON.stringify(obj))
    .setMimeType(ContentService.MimeType.JSON);
}
