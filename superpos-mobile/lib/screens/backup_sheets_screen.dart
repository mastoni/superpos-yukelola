import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import '../models/store_settings_model.dart';
import '../services/google_sheets_service.dart';
import '../services/settings_service.dart';

class BackupSheetsScreen extends StatefulWidget {
  const BackupSheetsScreen({super.key});

  @override
  State<BackupSheetsScreen> createState() => _BackupSheetsScreenState();
}

class _BackupSheetsScreenState extends State<BackupSheetsScreen> {
  final _urlController = TextEditingController();
  final _storeNameController = TextEditingController();
  final _addressController = TextEditingController();
  final _phoneController = TextEditingController();
  final _footerController = TextEditingController();

  bool _isLoading = false;
  String _lastSyncAt = '-';
  String? _statusMessage;
  bool _isSuccessStatus = true;

  @override
  void initState() {
    super.initState();
    _loadSettings();
  }

  Future<void> _loadSettings() async {
    final s = await SettingsService.getSettings();
    setState(() {
      _urlController.text = s.googleScriptUrl;
      _storeNameController.text = s.storeName;
      _addressController.text = s.address;
      _phoneController.text = s.phone;
      _footerController.text = s.receiptFooter;
      _lastSyncAt = s.lastSyncAt;
    });
  }

  Future<void> _saveSettings() async {
    final s = StoreSettingsModel(
      storeName: _storeNameController.text.trim(),
      address: _addressController.text.trim(),
      phone: _phoneController.text.trim(),
      receiptFooter: _footerController.text.trim(),
      googleScriptUrl: _urlController.text.trim(),
      lastSyncAt: _lastSyncAt,
    );
    await SettingsService.saveSettings(s);
    if (mounted) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Pengaturan toko & Google Sheets tersimpan!')),
      );
    }
  }

  Future<void> _testConnection() async {
    final url = _urlController.text.trim();
    if (url.isEmpty) {
      _showFeedback(false, 'Harap isi URL Google Apps Script terlebih dahulu.');
      return;
    }

    setState(() => _isLoading = true);
    final res = await GoogleSheetsService.testConnection(url);
    setState(() {
      _isLoading = false;
      _isSuccessStatus = res['success'] ?? false;
      _statusMessage = res['message'];
    });

    if (res['success'] == true) {
      await _saveSettings();
    }
  }

  Future<void> _backupToSheets() async {
    final url = _urlController.text.trim();
    if (url.isEmpty) {
      _showFeedback(false, 'Harap isi URL Google Apps Script terlebih dahulu.');
      return;
    }

    setState(() => _isLoading = true);
    final res = await GoogleSheetsService.backupToSheets(url);
    setState(() {
      _isLoading = false;
      _isSuccessStatus = res['success'] ?? false;
      _statusMessage = res['message'];
      if (res['success'] == true) {
        _lastSyncAt = res['syncedAt'] ?? '-';
      }
    });

    if (mounted && res['success'] == true) {
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(
          backgroundColor: Colors.green.shade700,
          content: Text('${res['message']} (${res['productCount']} Produk, ${res['saleCount']} Transaksi)'),
        ),
      );
    }
  }

  Future<void> _restoreFromSheets() async {
    final url = _urlController.text.trim();
    if (url.isEmpty) {
      _showFeedback(false, 'Harap isi URL Google Apps Script terlebih dahulu.');
      return;
    }

    final confirm = await showDialog<bool>(
      context: context,
      builder: (ctx) => AlertDialog(
        title: const Text('Tarik Data dari Google Sheets?'),
        content: const Text(
          'Data produk & kategori di HP akan diperbarui mengikuti data yang ada di tab "Katalog_Produk" Google Sheets Anda.',
        ),
        actions: [
          TextButton(onPressed: () => Navigator.pop(ctx, false), child: const Text('Batal')),
          ElevatedButton(
            style: ElevatedButton.styleFrom(backgroundColor: const Color(0xFF1E3A8A)),
            onPressed: () => Navigator.pop(ctx, true),
            child: const Text('Ya, Tarik Data', style: TextStyle(color: Colors.white)),
          ),
        ],
      ),
    );

    if (confirm != true) return;

    setState(() => _isLoading = true);
    final res = await GoogleSheetsService.restoreProductsFromSheets(url);
    setState(() {
      _isLoading = false;
      _isSuccessStatus = res['success'] ?? false;
      _statusMessage = res['message'];
    });

    if (mounted && res['success'] == true) {
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(
          backgroundColor: Colors.green.shade700,
          content: Text(res['message']),
        ),
      );
    }
  }

  void _showFeedback(bool success, String msg) {
    setState(() {
      _isSuccessStatus = success;
      _statusMessage = msg;
    });
  }

  void _showTutorialDialog() {
    showDialog(
      context: context,
      builder: (ctx) => AlertDialog(
        title: const Row(
          children: [
            Icon(Icons.help_outline, color: Color(0xFF1E3A8A)),
            SizedBox(width: 8),
            Text('Panduan Setup 1 Menit', style: TextStyle(fontSize: 16, fontWeight: FontWeight.bold)),
          ],
        ),
        content: SingleChildScrollView(
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              const Text(
                'Ikuti 5 langkah mudah berikut untuk menghubungkan HP ke Google Sheets secara gratis tanpa biaya API:',
                style: TextStyle(fontSize: 13),
              ),
              const SizedBox(height: 12),
              _stepItem('1', 'Buka sheets.new di laptop/HP dan buat Spreadsheet baru.'),
              _stepItem('2', 'Buka menu Ekstensi (Extensions) > Apps Script.'),
              _stepItem('3', 'Hapus kode bawaan, lalu paste script YUKelola (klik tombol salin script di bawah).'),
              _stepItem('4', 'Klik tombol Terapkan (Deploy) > Penerapan Baru > Aplikasi Web. Pilih Jalankan sebagai: "Saya" dan Akses: "Siapa saja (Anyone)".'),
              _stepItem('5', 'Salin Web App URL yang berakhiran /exec dan tempel di form ini.'),
            ],
          ),
        ),
        actions: [
          TextButton.icon(
            icon: const Icon(Icons.copy, size: 16),
            label: const Text('Salin Kode Script'),
            onPressed: () {
              Navigator.pop(ctx);
              _showScriptCodeDialog();
            },
          ),
          ElevatedButton(
            onPressed: () => Navigator.pop(ctx),
            child: const Text('Tutup'),
          ),
        ],
      ),
    );
  }

  Widget _stepItem(String number, String text) {
    return Padding(
      padding: const EdgeInsets.symmetric(vertical: 4),
      child: Row(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          CircleAvatar(
            radius: 10,
            backgroundColor: const Color(0xFF1E3A8A),
            child: Text(number, style: const TextStyle(color: Colors.white, fontSize: 11)),
          ),
          const SizedBox(width: 8),
          Expanded(child: Text(text, style: const TextStyle(fontSize: 12.5))),
        ],
      ),
    );
  }

  void _showScriptCodeDialog() {
    const scriptCode = r'''/** YUKelola Apps Script **/
function doPost(e) {
  var lock = LockService.getScriptLock();
  lock.tryLock(30000);
  try {
    var payload = JSON.parse(e.postData.contents);
    var action = payload.action;
    var data = payload.data || {};
    var ss = SpreadsheetApp.getActiveSpreadsheet();

    if (action === 'ping') {
      return ContentService.createTextOutput(JSON.stringify({status: 'success', message: 'Koneksi Berhasil!', sheetName: ss.getName()})).setMimeType(ContentService.MimeType.JSON);
    }
    if (action === 'backup_all') {
      if (data.store) saveStoreInfo(ss, data.store);
      if (data.categories) saveCategories(ss, data.categories);
      if (data.products) saveProducts(ss, data.products);
      if (data.sales) saveSales(ss, data.sales);
      if (data.saleItems) saveSaleItems(ss, data.saleItems);
      if (data.expenses) saveExpenses(ss, data.expenses);
      return ContentService.createTextOutput(JSON.stringify({status: 'success', message: 'Backup berhasil disinkronkan!'})).setMimeType(ContentService.MimeType.JSON);
    }
    return ContentService.createTextOutput(JSON.stringify({status: 'error', message: 'Aksi tidak dikenal'})).setMimeType(ContentService.MimeType.JSON);
  } catch(err) {
    return ContentService.createTextOutput(JSON.stringify({status: 'error', message: err.toString()})).setMimeType(ContentService.MimeType.JSON);
  } finally {
    lock.releaseLock();
  }
}

function doGet(e) {
  try {
    var ss = SpreadsheetApp.getActiveSpreadsheet();
    var sheet = ss.getSheetByName('Katalog_Produk');
    if (!sheet) return ContentService.createTextOutput(JSON.stringify({status: 'success', products: [], categories: []})).setMimeType(ContentService.MimeType.JSON);
    var rows = sheet.getDataRange().getValues();
    var products = [];
    for (var i = 1; i < rows.length; i++) {
      var r = rows[i];
      if (r[0]) products.push({id: r[0], sku: r[1], name: r[2], category: r[3], costPrice: Number(r[4])||0, sellPrice: Number(r[5])||0, stock: Number(r[6])||0, unit: r[7]||'Pcs'});
    }
    var catSheet = ss.getSheetByName('Kategori');
    var categories = [];
    if (catSheet) {
      var cRows = catSheet.getDataRange().getValues();
      for (var j = 1; j < cRows.length; j++) {
        if (cRows[j][0]) categories.push({id: cRows[j][0], name: cRows[j][1]});
      }
    }
    return ContentService.createTextOutput(JSON.stringify({status: 'success', products: products, categories: categories})).setMimeType(ContentService.MimeType.JSON);
  } catch(err) {
    return ContentService.createTextOutput(JSON.stringify({status: 'error', message: err.toString()})).setMimeType(ContentService.MimeType.JSON);
  }
}

function saveStoreInfo(ss, store) {
  var s = getOrCreateSheet(ss, 'Info_Toko'); s.clear();
  s.appendRow(['Parameter', 'Nilai']);
  s.appendRow(['Nama Toko', store.name||'']);
  s.appendRow(['Alamat', store.address||'']);
  s.appendRow(['No. Telp', store.phone||'']);
  s.appendRow(['Footer Struk', store.footer||'']);
  s.appendRow(['Terakhir Sinkron', new Date().toLocaleString('id-ID')]);
}
function saveCategories(ss, list) {
  var s = getOrCreateSheet(ss, 'Kategori'); s.clear();
  s.appendRow(['ID', 'Nama Kategori']);
  for(var i=0; i<list.length; i++) s.appendRow([list[i].id, list[i].name]);
}
function saveProducts(ss, list) {
  var s = getOrCreateSheet(ss, 'Katalog_Produk'); s.clear();
  s.appendRow(['ID', 'SKU', 'Nama Produk', 'Kategori', 'Harga Modal', 'Harga Jual', 'Stok', 'Satuan', 'Tgl Update']);
  for(var i=0; i<list.length; i++) {
    var p = list[i];
    s.appendRow([p.id, p.sku||'-', p.name, p.category||'Umum', p.costPrice||0, p.sellPrice||0, p.stock||0, p.unit||'Pcs', p.updatedAt||new Date().toISOString()]);
  }
}
function saveSales(ss, list) {
  var s = getOrCreateSheet(ss, 'Riwayat_Penjualan');
  if(s.getLastRow() === 0) s.appendRow(['ID', 'No Faktur', 'Tanggal', 'Pelanggan', 'Metode Bayar', 'Subtotal', 'Diskon', 'Pajak', 'Total', 'Bayar', 'Kembalian', 'Status']);
  var exist = {}; var last = s.getLastRow();
  if(last > 1) { var ids = s.getRange(2,1,last-1,1).getValues(); for(var k=0; k<ids.length; k++) exist[ids[k][0]] = true; }
  for(var i=0; i<list.length; i++) {
    var sl = list[i];
    if(!exist[sl.id]) s.appendRow([sl.id, sl.invoiceNumber, sl.createdAt, sl.customerName||'Umum', sl.paymentMethod||'TUNAI', sl.subtotal||0, sl.discount||0, sl.tax||0, sl.total||0, sl.paidAmount||0, sl.changeAmount||0, sl.status||'PAID']);
  }
}
function saveSaleItems(ss, list) {
  var s = getOrCreateSheet(ss, 'Detail_Item_Penjualan');
  if(s.getLastRow() === 0) s.appendRow(['Item ID', 'Invoice ID', 'Nama Produk', 'Qty', 'Harga Satuan', 'Subtotal']);
  var exist = {}; var last = s.getLastRow();
  if(last > 1) { var ids = s.getRange(2,1,last-1,1).getValues(); for(var k=0; k<ids.length; k++) exist[ids[k][0]] = true; }
  for(var i=0; i<list.length; i++) {
    var it = list[i];
    if(!exist[it.id]) s.appendRow([it.id, it.saleId, it.productName, it.qty, it.unitPrice, it.subtotal]);
  }
}
function saveExpenses(ss, list) {
  var s = getOrCreateSheet(ss, 'Pengeluaran_Toko');
  if(s.getLastRow() === 0) s.appendRow(['ID', 'Tanggal', 'Judul', 'Kategori', 'Jumlah', 'Catatan']);
  var exist = {}; var last = s.getLastRow();
  if(last > 1) { var ids = s.getRange(2,1,last-1,1).getValues(); for(var k=0; k<ids.length; k++) exist[ids[k][0]] = true; }
  for(var i=0; i<list.length; i++) {
    var ex = list[i];
    if(!exist[ex.id]) s.appendRow([ex.id, ex.createdAt, ex.title, ex.category||'Operasional', ex.amount||0, ex.note||'']);
  }
}
function getOrCreateSheet(ss, name) {
  var sheet = ss.getSheetByName(name);
  if(!sheet) sheet = ss.insertSheet(name);
  return sheet;
}''';

    showDialog(
      context: context,
      builder: (ctx) => AlertDialog(
        title: const Text('Kode Google Apps Script', style: TextStyle(fontSize: 15, fontWeight: FontWeight.bold)),
        content: SizedBox(
          width: double.maxFinite,
          height: 350,
          child: SingleChildScrollView(
            child: SelectableText(
              scriptCode,
              style: const TextStyle(fontFamily: 'monospace', fontSize: 11),
            ),
          ),
        ),
        actions: [
          ElevatedButton.icon(
            icon: const Icon(Icons.copy, size: 16),
            label: const Text('Salin Seluruh Kode'),
            onPressed: () {
              Clipboard.setData(const ClipboardData(text: scriptCode));
              Navigator.pop(ctx);
              ScaffoldMessenger.of(context).showSnackBar(
                const SnackBar(content: Text('Kode script berhasil disalin ke clipboard!')),
              );
            },
          ),
          TextButton(onPressed: () => Navigator.pop(ctx), child: const Text('Tutup')),
        ],
      ),
    );
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Backup & Google Sheets'),
        backgroundColor: const Color(0xFF0F172A),
        foregroundColor: Colors.white,
        actions: [
          IconButton(
            icon: const Icon(Icons.help_outline),
            tooltip: 'Panduan Setup',
            onPressed: _showTutorialDialog,
          ),
        ],
      ),
      body: SingleChildScrollView(
        padding: const EdgeInsets.all(16),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            // Status Card
            Card(
              elevation: 2,
              shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
              color: const Color(0xFFF8FAFC),
              child: Padding(
                padding: const EdgeInsets.all(16),
                child: Column(
                  children: [
                    Row(
                      children: [
                        Container(
                          padding: const EdgeInsets.all(10),
                          decoration: BoxDecoration(
                            color: Colors.green.shade50,
                            shape: BoxShape.circle,
                          ),
                          child: Icon(Icons.table_chart, color: Colors.green.shade700, size: 28),
                        ),
                        const SizedBox(width: 14),
                        Expanded(
                          child: Column(
                            crossAxisAlignment: CrossAxisAlignment.start,
                            children: [
                              const Text('Sinkronisasi Google Sheets', style: TextStyle(fontWeight: FontWeight.bold, fontSize: 15)),
                              const SizedBox(height: 3),
                              Text('Terakhir Sinkron: $_lastSyncAt', style: TextStyle(color: Colors.grey.shade600, fontSize: 12)),
                            ],
                          ),
                        ),
                      ],
                    ),
                    if (_statusMessage != null) ...[
                      const Divider(height: 24),
                      Row(
                        children: [
                          Icon(
                            _isSuccessStatus ? Icons.check_circle : Icons.error,
                            color: _isSuccessStatus ? Colors.green : Colors.red,
                            size: 18,
                          ),
                          const SizedBox(width: 8),
                          Expanded(
                            child: Text(
                              _statusMessage!,
                              style: TextStyle(
                                fontSize: 12.5,
                                color: _isSuccessStatus ? Colors.green.shade800 : Colors.red.shade800,
                                fontWeight: FontWeight.w500,
                              ),
                            ),
                          ),
                        ],
                      ),
                    ],
                  ],
                ),
              ),
            ),
            const SizedBox(height: 20),

            // Form Konfigurasi URL Google Apps Script
            const Text('Koneksi Google Apps Script', style: TextStyle(fontWeight: FontWeight.bold, fontSize: 15)),
            const SizedBox(height: 6),
            const Text(
              'Masukkan Web App URL dari Google Apps Script untuk menyimpan backup database SQLite HP ke spreadsheet.',
              style: TextStyle(color: Colors.black54, fontSize: 12),
            ),
            const SizedBox(height: 10),
            TextField(
              controller: _urlController,
              decoration: InputDecoration(
                labelText: 'Google Apps Script URL (/exec)',
                hintText: 'https://script.google.com/macros/s/.../exec',
                prefixIcon: const Icon(Icons.link),
                border: OutlineInputBorder(borderRadius: BorderRadius.circular(10)),
                suffixIcon: IconButton(
                  icon: const Icon(Icons.content_paste),
                  onPressed: () async {
                    final data = await Clipboard.getData('text/plain');
                    if (data?.text != null) {
                      _urlController.text = data!.text!.trim();
                    }
                  },
                ),
              ),
            ),
            const SizedBox(height: 12),

            // Tombol Aksi Backup & Restore
            Row(
              children: [
                Expanded(
                  child: OutlinedButton.icon(
                    style: OutlinedButton.styleFrom(
                      padding: const EdgeInsets.symmetric(vertical: 12),
                      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(8)),
                    ),
                    icon: _isLoading
                        ? const SizedBox(width: 16, height: 16, child: CircularProgressIndicator(strokeWidth: 2))
                        : const Icon(Icons.wifi_tethering),
                    label: const Text('Uji Koneksi'),
                    onPressed: _isLoading ? null : _testConnection,
                  ),
                ),
                const SizedBox(width: 10),
                Expanded(
                  child: ElevatedButton.icon(
                    style: ElevatedButton.styleFrom(
                      backgroundColor: Colors.green.shade700,
                      foregroundColor: Colors.white,
                      padding: const EdgeInsets.symmetric(vertical: 12),
                      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(8)),
                    ),
                    icon: const Icon(Icons.cloud_upload),
                    label: const Text('Backup Sekarang'),
                    onPressed: _isLoading ? null : _backupToSheets,
                  ),
                ),
              ],
            ),
            const SizedBox(height: 10),
            SizedBox(
              width: double.infinity,
              child: OutlinedButton.icon(
                style: OutlinedButton.styleFrom(
                  foregroundColor: const Color(0xFF1E3A8A),
                  padding: const EdgeInsets.symmetric(vertical: 12),
                  shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(8)),
                ),
                icon: const Icon(Icons.cloud_download),
                label: const Text('Tarik Produk dari Google Sheets (Restore)'),
                onPressed: _isLoading ? null : _restoreFromSheets,
              ),
            ),

            const SizedBox(height: 28),
            const Divider(),
            const SizedBox(height: 14),

            // Profil Toko & Struk
            const Text('Profil Toko & Struk Belanja', style: TextStyle(fontWeight: FontWeight.bold, fontSize: 15)),
            const SizedBox(height: 10),
            TextField(
              controller: _storeNameController,
              decoration: InputDecoration(
                labelText: 'Nama Toko / Usaha',
                prefixIcon: const Icon(Icons.storefront),
                border: OutlineInputBorder(borderRadius: BorderRadius.circular(10)),
              ),
            ),
            const SizedBox(height: 12),
            TextField(
              controller: _addressController,
              decoration: InputDecoration(
                labelText: 'Alamat Toko',
                prefixIcon: const Icon(Icons.location_on),
                border: OutlineInputBorder(borderRadius: BorderRadius.circular(10)),
              ),
            ),
            const SizedBox(height: 12),
            TextField(
              controller: _phoneController,
              keyboardType: TextInputType.phone,
              decoration: InputDecoration(
                labelText: 'Nomor WhatsApp / Telepon',
                prefixIcon: const Icon(Icons.phone),
                border: OutlineInputBorder(borderRadius: BorderRadius.circular(10)),
              ),
            ),
            const SizedBox(height: 12),
            TextField(
              controller: _footerController,
              maxLines: 2,
              decoration: InputDecoration(
                labelText: 'Pesan Kaki Struk (Footer)',
                prefixIcon: const Icon(Icons.receipt_long),
                border: OutlineInputBorder(borderRadius: BorderRadius.circular(10)),
              ),
            ),
            const SizedBox(height: 16),
            SizedBox(
              width: double.infinity,
              child: ElevatedButton.icon(
                style: ElevatedButton.styleFrom(
                  backgroundColor: const Color(0xFF1E3A8A),
                  foregroundColor: Colors.white,
                  padding: const EdgeInsets.symmetric(vertical: 14),
                  shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(10)),
                ),
                icon: const Icon(Icons.save),
                label: const Text('Simpan Pengaturan Toko'),
                onPressed: _saveSettings,
              ),
            ),
            const SizedBox(height: 30),
          ],
        ),
      ),
    );
  }
}
