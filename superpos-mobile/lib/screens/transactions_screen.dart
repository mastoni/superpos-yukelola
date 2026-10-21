import 'package:flutter/material.dart';
import 'package:intl/intl.dart';
import '../database/database_helper.dart';
import '../models/sale_model.dart';
import '../services/receipt_service.dart';
import '../services/settings_service.dart';

class TransactionsScreen extends StatefulWidget {
  const TransactionsScreen({super.key});

  @override
  State<TransactionsScreen> createState() => _TransactionsScreenState();
}

class _TransactionsScreenState extends State<TransactionsScreen> {
  final _currency = NumberFormat.currency(locale: 'id_ID', symbol: 'Rp ', decimalDigits: 0);
  List<SaleModel> _sales = [];
  bool _isLoading = true;

  @override
  void initState() {
    super.initState();
    _loadSales();
  }

  Future<void> _loadSales() async {
    setState(() => _isLoading = true);
    final sales = await DatabaseHelper.instance.getAllSales(limit: 100);
    setState(() {
      _sales = sales;
      _isLoading = false;
    });
  }

  void _showDetailDialog(SaleModel sale) {
    showModalBottomSheet(
      context: context,
      isScrollControlled: true,
      shape: const RoundedRectangleBorder(borderRadius: BorderRadius.vertical(top: Radius.circular(20))),
      builder: (ctx) => Container(
        height: MediaQuery.of(context).size.height * 0.75,
        padding: const EdgeInsets.all(16),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Row(
              mainAxisAlignment: MainAxisAlignment.spaceBetween,
              children: [
                Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Text(sale.invoiceNumber, style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 16)),
                    Text(
                      _formatDateTime(sale.createdAt),
                      style: TextStyle(color: Colors.grey.shade600, fontSize: 12),
                    ),
                  ],
                ),
                Container(
                  padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 4),
                  decoration: BoxDecoration(
                    color: sale.status == 'VOID' ? Colors.red.shade50 : Colors.green.shade50,
                    borderRadius: BorderRadius.circular(12),
                    border: Border.all(color: sale.status == 'VOID' ? Colors.red : Colors.green),
                  ),
                  child: Text(
                    sale.status,
                    style: TextStyle(
                      color: sale.status == 'VOID' ? Colors.red.shade800 : Colors.green.shade800,
                      fontWeight: FontWeight.bold,
                      fontSize: 11,
                    ),
                  ),
                ),
              ],
            ),
            const Divider(),
            const Text('Item Pembelian:', style: TextStyle(fontWeight: FontWeight.bold, fontSize: 13)),
            const SizedBox(height: 6),
            Expanded(
              child: ListView.separated(
                itemCount: sale.items.length,
                separatorBuilder: (_, _) => const Divider(height: 1),
                itemBuilder: (ctx, index) {
                  final it = sale.items[index];
                  return Padding(
                    padding: const EdgeInsets.symmetric(vertical: 6),
                    child: Row(
                      mainAxisAlignment: MainAxisAlignment.spaceBetween,
                      children: [
                        Expanded(
                          child: Column(
                            crossAxisAlignment: CrossAxisAlignment.start,
                            children: [
                              Text(it.productName, style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 13)),
                              Text(
                                '${it.qty} x ${_currency.format(it.unitPrice)}',
                                style: TextStyle(color: Colors.grey.shade600, fontSize: 11.5),
                              ),
                            ],
                          ),
                        ),
                        Text(_currency.format(it.subtotal), style: const TextStyle(fontWeight: FontWeight.bold)),
                      ],
                    ),
                  );
                },
              ),
            ),
            const Divider(),
            _buildDetailRow('Pelanggan:', sale.customerName),
            _buildDetailRow('Metode Pembayaran:', sale.paymentMethod),
            _buildDetailRow('Subtotal:', _currency.format(sale.subtotal)),
            if (sale.discount > 0) _buildDetailRow('Diskon:', '-${_currency.format(sale.discount)}'),
            _buildDetailRow('Total Tagihan:', _currency.format(sale.total), isBold: true, fontSize: 15),
            _buildDetailRow('Uang Diterima:', _currency.format(sale.paidAmount)),
            _buildDetailRow('Kembalian:', _currency.format(sale.changeAmount)),
            const SizedBox(height: 12),

            Row(
              children: [
                Expanded(
                  child: OutlinedButton.icon(
                    icon: const Icon(Icons.print, size: 18),
                    label: const Text('Cetak'),
                    onPressed: () async {
                      final store = await SettingsService.getSettings();
                      await ReceiptService.printReceipt(sale, store);
                    },
                  ),
                ),
                const SizedBox(width: 8),
                Expanded(
                  child: OutlinedButton.icon(
                    style: OutlinedButton.styleFrom(foregroundColor: Colors.green.shade800),
                    icon: const Icon(Icons.share, size: 18),
                    label: const Text('Kirim WA'),
                    onPressed: () async {
                      final store = await SettingsService.getSettings();
                      await ReceiptService.shareReceiptWhatsApp(sale, store);
                    },
                  ),
                ),
                if (sale.status != 'VOID') ...[
                  const SizedBox(width: 8),
                  IconButton(
                    tooltip: 'Batalkan / Void Transaksi',
                    icon: const Icon(Icons.cancel_outlined, color: Colors.red),
                    onPressed: () async {
                      final confirm = await showDialog<bool>(
                        context: context,
                        builder: (c) => AlertDialog(
                          title: const Text('Batalkan Transaksi?'),
                          content: const Text('Stok produk akan dikembalikan dan transaksi berstatus VOID.'),
                          actions: [
                            TextButton(onPressed: () => Navigator.pop(c, false), child: const Text('Kembali')),
                            TextButton(
                              style: TextButton.styleFrom(foregroundColor: Colors.red),
                              onPressed: () => Navigator.pop(c, true),
                              child: const Text('Ya, Batalkan'),
                            ),
                          ],
                        ),
                      );

                      if (confirm == true) {
                        await DatabaseHelper.instance.voidSale(sale.id);
                        if (ctx.mounted) Navigator.pop(ctx);
                        _loadSales();
                      }
                    },
                  ),
                ],
              ],
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildDetailRow(String label, String value, {bool isBold = false, double fontSize = 12.5}) {
    return Padding(
      padding: const EdgeInsets.symmetric(vertical: 2),
      child: Row(
        mainAxisAlignment: MainAxisAlignment.spaceBetween,
        children: [
          Text(label, style: TextStyle(fontSize: fontSize, color: Colors.black87)),
          Text(
            value,
            style: TextStyle(
              fontSize: fontSize,
              fontWeight: isBold ? FontWeight.bold : FontWeight.w500,
              color: isBold ? const Color(0xFF1E3A8A) : Colors.black87,
            ),
          ),
        ],
      ),
    );
  }

  String _formatDateTime(String iso) {
    try {
      final dt = DateTime.parse(iso);
      return '${dt.day.toString().padLeft(2, '0')}/${dt.month.toString().padLeft(2, '0')}/${dt.year} ${dt.hour.toString().padLeft(2, '0')}:${dt.minute.toString().padLeft(2, '0')}';
    } catch (_) {
      return iso;
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Riwayat Penjualan'),
        backgroundColor: const Color(0xFF0F172A),
        foregroundColor: Colors.white,
      ),
      body: _isLoading
          ? const Center(child: CircularProgressIndicator())
          : _sales.isEmpty
              ? Center(
                  child: Column(
                    mainAxisAlignment: MainAxisAlignment.center,
                    children: [
                      Icon(Icons.receipt_long_outlined, size: 64, color: Colors.grey.shade400),
                      const SizedBox(height: 12),
                      Text(
                        'Belum ada transaksi penjualan',
                        style: TextStyle(color: Colors.grey.shade600, fontSize: 15),
                      ),
                    ],
                  ),
                )
              : RefreshIndicator(
                  onRefresh: _loadSales,
                  child: ListView.separated(
                    padding: const EdgeInsets.all(12),
                    itemCount: _sales.length,
                    separatorBuilder: (_, _) => const SizedBox(height: 8),
                    itemBuilder: (context, index) {
                      final sale = _sales[index];
                      final isVoid = sale.status == 'VOID';

                      return Card(
                        elevation: 1.5,
                        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(10)),
                        child: ListTile(
                          onTap: () => _showDetailDialog(sale),
                          leading: CircleAvatar(
                            backgroundColor: isVoid ? Colors.red.shade100 : const Color(0xFFEFF6FF),
                            child: Icon(
                              Icons.receipt,
                              color: isVoid ? Colors.red : const Color(0xFF1E3A8A),
                            ),
                          ),
                          title: Row(
                            mainAxisAlignment: MainAxisAlignment.spaceBetween,
                            children: [
                              Text(sale.invoiceNumber, style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 13.5)),
                              Text(
                                _currency.format(sale.total),
                                style: TextStyle(
                                  fontWeight: FontWeight.bold,
                                  fontSize: 14,
                                  color: isVoid ? Colors.grey : const Color(0xFF1E3A8A),
                                  decoration: isVoid ? TextDecoration.lineThrough : null,
                                ),
                              ),
                            ],
                          ),
                          subtitle: Column(
                            crossAxisAlignment: CrossAxisAlignment.start,
                            children: [
                              Text(
                                '${_formatDateTime(sale.createdAt)} • ${sale.customerName}',
                                style: TextStyle(fontSize: 11.5, color: Colors.grey.shade600),
                              ),
                              Row(
                                children: [
                                  Text(
                                    '${sale.paymentMethod} • ${sale.items.length} item',
                                    style: const TextStyle(fontSize: 11, fontWeight: FontWeight.w500),
                                  ),
                                  if (isVoid) ...[
                                    const SizedBox(width: 8),
                                    Container(
                                      padding: const EdgeInsets.symmetric(horizontal: 6, vertical: 1),
                                      decoration: BoxDecoration(
                                        color: Colors.red.shade50,
                                        borderRadius: BorderRadius.circular(4),
                                      ),
                                      child: const Text('VOID', style: TextStyle(color: Colors.red, fontSize: 10, fontWeight: FontWeight.bold)),
                                    ),
                                  ],
                                ],
                              ),
                            ],
                          ),
                          trailing: const Icon(Icons.arrow_forward_ios, size: 14, color: Colors.grey),
                        ),
                      );
                    },
                  ),
                ),
    );
  }
}
