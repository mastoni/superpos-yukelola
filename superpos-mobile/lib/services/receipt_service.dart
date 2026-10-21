import 'package:intl/intl.dart';
import 'package:pdf/pdf.dart';
import 'package:pdf/widgets.dart' as pw;
import 'package:printing/printing.dart';
import 'package:share_plus/share_plus.dart';
import '../models/sale_model.dart';
import '../models/store_settings_model.dart';

class ReceiptService {
  static final _currencyFormat = NumberFormat.currency(
    locale: 'id_ID',
    symbol: 'Rp ',
    decimalDigits: 0,
  );

  /// Cetak Struk ke Printer Thermal (58mm / 80mm) via Bluetooth / Android Print
  static Future<void> printReceipt(SaleModel sale, StoreSettingsModel store) async {
    final doc = pw.Document();

    doc.addPage(
      pw.Page(
        pageFormat: const PdfPageFormat(
          58 * PdfPageFormat.mm,
          double.infinity,
          marginAll: 4 * PdfPageFormat.mm,
        ),
        build: (pw.Context context) {
          return pw.Column(
            crossAxisAlignment: pw.CrossAxisAlignment.center,
            mainAxisSize: pw.MainAxisSize.min,
            children: [
              // Header Toko
              pw.Text(
                store.storeName,
                style: pw.TextStyle(fontWeight: pw.FontWeight.bold, fontSize: 13),
                textAlign: pw.TextAlign.center,
              ),
              if (store.address.isNotEmpty)
                pw.Text(
                  store.address,
                  style: const pw.TextStyle(fontSize: 8),
                  textAlign: pw.TextAlign.center,
                ),
              if (store.phone.isNotEmpty)
                pw.Text(
                  'Telp: ${store.phone}',
                  style: const pw.TextStyle(fontSize: 8),
                  textAlign: pw.TextAlign.center,
                ),
              pw.SizedBox(height: 3),
              pw.Text(
                '--------------------------------',
                style: const pw.TextStyle(fontSize: 8),
              ),

              // Info Faktur
              pw.Row(
                mainAxisAlignment: pw.MainAxisAlignment.spaceBetween,
                children: [
                  pw.Text('No: ${sale.invoiceNumber}', style: const pw.TextStyle(fontSize: 8)),
                  pw.Text(sale.paymentMethod, style: pw.TextStyle(fontSize: 8, fontWeight: pw.FontWeight.bold)),
                ],
              ),
              pw.Row(
                mainAxisAlignment: pw.MainAxisAlignment.spaceBetween,
                children: [
                  pw.Text('Tgl: ${_formatDateTime(sale.createdAt)}', style: const pw.TextStyle(fontSize: 7)),
                  pw.Text('Pelanggan: ${sale.customerName}', style: const pw.TextStyle(fontSize: 7)),
                ],
              ),
              pw.Text(
                '--------------------------------',
                style: const pw.TextStyle(fontSize: 8),
              ),

              // Daftar Item
              pw.Column(
                children: sale.items.map((item) {
                  return pw.Padding(
                    padding: const pw.EdgeInsets.symmetric(vertical: 1.5),
                    child: pw.Column(
                      crossAxisAlignment: pw.CrossAxisAlignment.start,
                      children: [
                        pw.Text(
                          item.productName,
                          style: pw.TextStyle(fontWeight: pw.FontWeight.bold, fontSize: 8),
                        ),
                        pw.Row(
                          mainAxisAlignment: pw.MainAxisAlignment.spaceBetween,
                          children: [
                            pw.Text(
                              '${item.qty} x ${_currencyFormat.format(item.unitPrice)}',
                              style: const pw.TextStyle(fontSize: 7.5),
                            ),
                            pw.Text(
                              _currencyFormat.format(item.subtotal),
                              style: const pw.TextStyle(fontSize: 7.5),
                            ),
                          ],
                        ),
                      ],
                    ),
                  );
                }).toList(),
              ),

              pw.Text(
                '--------------------------------',
                style: const pw.TextStyle(fontSize: 8),
              ),

              // Rincian Pembayaran
              _buildPdfSummaryRow('Subtotal', _currencyFormat.format(sale.subtotal)),
              if (sale.discount > 0)
                _buildPdfSummaryRow('Diskon', '-${_currencyFormat.format(sale.discount)}'),
              if (sale.tax > 0)
                _buildPdfSummaryRow('Pajak', _currencyFormat.format(sale.tax)),
              pw.SizedBox(height: 2),
              _buildPdfSummaryRow('TOTAL', _currencyFormat.format(sale.total), isBold: true, fontSize: 10),
              _buildPdfSummaryRow('Bayar (${sale.paymentMethod})', _currencyFormat.format(sale.paidAmount)),
              _buildPdfSummaryRow('Kembalian', _currencyFormat.format(sale.changeAmount)),

              pw.SizedBox(height: 4),
              pw.Text(
                '--------------------------------',
                style: const pw.TextStyle(fontSize: 8),
              ),

              // Footer Toko
              pw.SizedBox(height: 2),
              pw.Text(
                store.receiptFooter,
                style: const pw.TextStyle(fontSize: 7),
                textAlign: pw.TextAlign.center,
              ),
              pw.SizedBox(height: 3),
              pw.Text(
                'Powered by YUKelola',
                style: const pw.TextStyle(fontSize: 6, color: PdfColors.grey700),
                textAlign: pw.TextAlign.center,
              ),
            ],
          );
        },
      ),
    );

    await Printing.layoutPdf(
      onLayout: (PdfPageFormat format) async => doc.save(),
      name: 'Struk_${sale.invoiceNumber}.pdf',
    );
  }

  static pw.Widget _buildPdfSummaryRow(String label, String value, {bool isBold = false, double fontSize = 8}) {
    return pw.Padding(
      padding: const pw.EdgeInsets.symmetric(vertical: 0.5),
      child: pw.Row(
        mainAxisAlignment: pw.MainAxisAlignment.spaceBetween,
        children: [
          pw.Text(
            label,
            style: pw.TextStyle(
              fontSize: fontSize,
              fontWeight: isBold ? pw.FontWeight.bold : pw.FontWeight.normal,
            ),
          ),
          pw.Text(
            value,
            style: pw.TextStyle(
              fontSize: fontSize,
              fontWeight: isBold ? pw.FontWeight.bold : pw.FontWeight.normal,
            ),
          ),
        ],
      ),
    );
  }

  /// Bagikan Struk berupa Teks Format WhatsApp
  static Future<void> shareReceiptWhatsApp(SaleModel sale, StoreSettingsModel store) async {
    final buffer = StringBuffer();
    buffer.writeln('*${store.storeName.toUpperCase()}*');
    if (store.address.isNotEmpty) buffer.writeln(store.address);
    if (store.phone.isNotEmpty) buffer.writeln('Telp: ${store.phone}');
    buffer.writeln('==============================');
    buffer.writeln('No. Faktur: *${sale.invoiceNumber}*');
    buffer.writeln('Tanggal: ${_formatDateTime(sale.createdAt)}');
    buffer.writeln('Pelanggan: ${sale.customerName}');
    buffer.writeln('Metode: ${sale.paymentMethod}');
    buffer.writeln('------------------------------');

    for (var item in sale.items) {
      buffer.writeln(item.productName);
      buffer.writeln('  ${item.qty} x ${_currencyFormat.format(item.unitPrice)} = *${_currencyFormat.format(item.subtotal)}*');
    }

    buffer.writeln('------------------------------');
    buffer.writeln('Subtotal: ${_currencyFormat.format(sale.subtotal)}');
    if (sale.discount > 0) {
      buffer.writeln('Diskon: -${_currencyFormat.format(sale.discount)}');
    }
    if (sale.tax > 0) {
      buffer.writeln('Pajak: ${_currencyFormat.format(sale.tax)}');
    }
    buffer.writeln('*TOTAL: ${_currencyFormat.format(sale.total)}*');
    buffer.writeln('Bayar: ${_currencyFormat.format(sale.paidAmount)}');
    buffer.writeln('Kembali: ${_currencyFormat.format(sale.changeAmount)}');
    buffer.writeln('==============================');
    buffer.writeln(store.receiptFooter);

    // ignore: deprecated_member_use
    await Share.share(
      buffer.toString(),
      subject: 'Struk Belanja ${sale.invoiceNumber}',
    );
  }

  static String _formatDateTime(String iso) {
    try {
      final dt = DateTime.parse(iso);
      return '${dt.day.toString().padLeft(2, '0')}/${dt.month.toString().padLeft(2, '0')}/${dt.year} ${dt.hour.toString().padLeft(2, '0')}:${dt.minute.toString().padLeft(2, '0')}';
    } catch (_) {
      return iso;
    }
  }
}
