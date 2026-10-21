import 'dart:convert';
import 'package:flutter/material.dart';
import 'package:intl/intl.dart';
import 'package:uuid/uuid.dart';
import '../database/database_helper.dart';
import '../models/category_model.dart';
import '../models/customer_model.dart';
import '../models/product_model.dart';
import '../models/sale_model.dart';
import '../models/sale_item_model.dart';
import '../services/receipt_service.dart';
import '../services/settings_service.dart';
import 'barcode_scanner_screen.dart';

class PosCashierScreen extends StatefulWidget {
  const PosCashierScreen({super.key});

  @override
  State<PosCashierScreen> createState() => _PosCashierScreenState();
}

class CartItem {
  final ProductModel product;
  int qty;

  CartItem({required this.product, this.qty = 1});

  double get subtotal => product.sellPrice * qty;
  double get totalCost => product.costPrice * qty;

  Map<String, dynamic> toMap() {
    return {
      'product': product.toMap(),
      'qty': qty,
    };
  }

  factory CartItem.fromMap(Map<String, dynamic> map) {
    return CartItem(
      product: ProductModel.fromMap(map['product']),
      qty: (map['qty'] as num).toInt(),
    );
  }
}

class _PosCashierScreenState extends State<PosCashierScreen> {
  final _currency = NumberFormat.currency(locale: 'id_ID', symbol: 'Rp ', decimalDigits: 0);
  final _searchController = TextEditingController();

  List<ProductModel> _products = [];
  List<CategoryModel> _categories = [];
  List<CustomerModel> _customers = [];
  String _selectedCategory = 'Semua';
  CustomerModel? _selectedCustomer;
  bool _isLoading = true;

  // Keranjang Belanja
  final Map<String, CartItem> _cart = {};

  // Hold Carts Count
  int _holdCount = 0;

  @override
  void initState() {
    super.initState();
    _loadInitialData();
  }

  Future<void> _loadInitialData() async {
    setState(() => _isLoading = true);
    final cats = await DatabaseHelper.instance.getAllCategories();
    final prods = await DatabaseHelper.instance.getAllProducts(
      search: _searchController.text,
      category: _selectedCategory,
    );
    final custs = await DatabaseHelper.instance.getAllCustomers();
    final holds = await DatabaseHelper.instance.getHoldSales();

    setState(() {
      _categories = cats;
      _products = prods;
      _customers = custs;
      if (custs.isNotEmpty && _selectedCustomer == null) {
        _selectedCustomer = custs.first;
      }
      _holdCount = holds.length;
      _isLoading = false;
    });
  }

  Future<void> _refreshCatalog() async {
    final prods = await DatabaseHelper.instance.getAllProducts(
      search: _searchController.text,
      category: _selectedCategory,
    );
    setState(() {
      _products = prods;
    });
  }

  void _addToCart(ProductModel product) {
    if (product.stock <= 0) {
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(
          content: Text('Stok "${product.name}" habis!'),
          backgroundColor: Colors.red.shade700,
          duration: const Duration(seconds: 1),
        ),
      );
      return;
    }

    setState(() {
      if (_cart.containsKey(product.id)) {
        if (_cart[product.id]!.qty < product.stock) {
          _cart[product.id]!.qty++;
        } else {
          ScaffoldMessenger.of(context).showSnackBar(
            SnackBar(
              content: Text('Jumlah melebihi stok yang ada (${product.stock})'),
              duration: const Duration(seconds: 1),
            ),
          );
        }
      } else {
        _cart[product.id] = CartItem(product: product, qty: 1);
      }
    });
  }

  void _decreaseFromCart(ProductModel product) {
    setState(() {
      if (_cart.containsKey(product.id)) {
        if (_cart[product.id]!.qty > 1) {
          _cart[product.id]!.qty--;
        } else {
          _cart.remove(product.id);
        }
      }
    });
  }

  void _clearCart() {
    setState(() {
      _cart.clear();
    });
  }

  int get _totalCartItems => _cart.values.fold(0, (sum, item) => sum + item.qty);
  double get _cartSubtotal => _cart.values.fold(0.0, (sum, item) => sum + item.subtotal);

  // --- HOLD & RESUME SALE ---
  Future<void> _holdCurrentSale() async {
    if (_cart.isEmpty) return;

    final customerName = _selectedCustomer?.name ?? 'Pelanggan Umum';
    final itemsList = _cart.values.map((c) => c.toMap()).toList();

    await DatabaseHelper.instance.saveHoldSale(customerName, _cartSubtotal, itemsList);
    _clearCart();

    final holds = await DatabaseHelper.instance.getHoldSales();
    setState(() {
      _holdCount = holds.length;
    });

    if (mounted) {
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(
          backgroundColor: const Color(0xFF059669),
          content: Text('Pesanan untuk $customerName disimpan di antrean (Hold).'),
        ),
      );
    }
  }

  Future<void> _openHoldSalesModal() async {
    final holds = await DatabaseHelper.instance.getHoldSales();

    if (!mounted) return;

    showModalBottomSheet(
      context: context,
      shape: const RoundedRectangleBorder(borderRadius: BorderRadius.vertical(top: Radius.circular(20))),
      builder: (ctx) => StatefulBuilder(
        builder: (ctx, setModalState) {
          return Container(
            padding: const EdgeInsets.all(16),
            height: 400,
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Row(
                  mainAxisAlignment: MainAxisAlignment.spaceBetween,
                  children: [
                    Text('Antrean Pesanan Tertunda (${holds.length})', style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 16)),
                    IconButton(icon: const Icon(Icons.close), onPressed: () => Navigator.pop(ctx)),
                  ],
                ),
                const Divider(),
                Expanded(
                  child: holds.isEmpty
                      ? const Center(child: Text('Tidak ada pesanan yang ditunda.'))
                      : ListView.separated(
                          itemCount: holds.length,
                          separatorBuilder: (_, _) => const Divider(height: 1),
                          itemBuilder: (ctx, index) {
                            final h = holds[index];
                            final id = h['id'].toString();
                            final cust = h['customer_name'];
                            final total = (h['total'] as num).toDouble();
                            final itemsRaw = jsonDecode(h['items_json'] as String) as List;

                            return ListTile(
                              leading: const CircleAvatar(
                                backgroundColor: Color(0xFFFEF3C7),
                                child: Icon(Icons.pause_circle_filled, color: Color(0xFFD97706)),
                              ),
                              title: Text(cust, style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 14)),
                              subtitle: Text('${itemsRaw.length} jenis item • ${_currency.format(total)}'),
                              trailing: Row(
                                mainAxisSize: MainAxisSize.min,
                                children: [
                                  IconButton(
                                    icon: const Icon(Icons.play_circle_fill, color: Color(0xFF059669), size: 28),
                                    tooltip: 'Buka Kembali (Resume)',
                                    onPressed: () {
                                      _cart.clear();
                                      for (var itemMap in itemsRaw) {
                                        final cartItem = CartItem.fromMap(itemMap);
                                        _cart[cartItem.product.id] = cartItem;
                                      }
                                      DatabaseHelper.instance.deleteHoldSale(id);
                                      Navigator.pop(ctx);
                                      setState(() {
                                        _holdCount = holds.length - 1;
                                      });
                                    },
                                  ),
                                  IconButton(
                                    icon: const Icon(Icons.delete_outline, color: Colors.red, size: 22),
                                    onPressed: () async {
                                      await DatabaseHelper.instance.deleteHoldSale(id);
                                      holds.removeAt(index);
                                      setModalState(() {});
                                      setState(() {
                                        _holdCount = holds.length;
                                      });
                                    },
                                  ),
                                ],
                              ),
                            );
                          },
                        ),
                ),
              ],
            ),
          );
        },
      ),
    );
  }

  // --- CUSTOMER PICKER & CREATE ---
  Future<void> _openAddCustomerDialog() async {
    final nameCtrl = TextEditingController();
    final phoneCtrl = TextEditingController();
    final addressCtrl = TextEditingController();

    await showDialog(
      context: context,
      builder: (ctx) => AlertDialog(
        title: const Text('Tambah Pelanggan Baru'),
        content: SingleChildScrollView(
          child: Column(
            mainAxisSize: MainAxisSize.min,
            children: [
              TextField(controller: nameCtrl, decoration: const InputDecoration(labelText: 'Nama Lengkap *', border: OutlineInputBorder())),
              const SizedBox(height: 10),
              TextField(controller: phoneCtrl, keyboardType: TextInputType.phone, decoration: const InputDecoration(labelText: 'No. WhatsApp / HP', border: OutlineInputBorder())),
              const SizedBox(height: 10),
              TextField(controller: addressCtrl, decoration: const InputDecoration(labelText: 'Alamat', border: OutlineInputBorder())),
            ],
          ),
        ),
        actions: [
          TextButton(onPressed: () => Navigator.pop(ctx), child: const Text('Batal')),
          ElevatedButton(
            style: ElevatedButton.styleFrom(backgroundColor: const Color(0xFF059669), foregroundColor: Colors.white),
            onPressed: () async {
              if (nameCtrl.text.trim().isEmpty) return;
              final newCust = CustomerModel(
                id: const Uuid().v4(),
                name: nameCtrl.text.trim(),
                phone: phoneCtrl.text.trim(),
                address: addressCtrl.text.trim(),
              );
              await DatabaseHelper.instance.insertCustomer(newCust);
              final custs = await DatabaseHelper.instance.getAllCustomers();
              setState(() {
                _customers = custs;
                _selectedCustomer = newCust;
              });
              if (ctx.mounted) Navigator.pop(ctx);
            },
            child: const Text('Simpan'),
          ),
        ],
      ),
    );
  }

  Future<void> _scanBarcodeToAdd() async {
    final scanned = await Navigator.push<String>(
      context,
      MaterialPageRoute(builder: (_) => const BarcodeScannerScreen()),
    );

    if (scanned != null && scanned.isNotEmpty) {
      final product = await DatabaseHelper.instance.getProductBySku(scanned);
      if (product != null) {
        _addToCart(product);
        if (mounted) {
          ScaffoldMessenger.of(context).showSnackBar(
            SnackBar(
              content: Text('Ditambahkan: ${product.name}'),
              duration: const Duration(seconds: 1),
              backgroundColor: const Color(0xFF059669),
            ),
          );
        }
      } else {
        if (mounted) {
          ScaffoldMessenger.of(context).showSnackBar(
            SnackBar(
              content: Text('Produk dengan barcode "$scanned" tidak ditemukan.'),
              backgroundColor: Colors.orange.shade800,
            ),
          );
        }
      }
    }
  }

  void _openCheckoutDialog() {
    final discountCtrl = TextEditingController(text: '0');
    final paidCtrl = TextEditingController();
    String paymentMethod = 'TUNAI';

    final total = _cartSubtotal;
    paidCtrl.text = total.toStringAsFixed(0);

    showModalBottomSheet(
      context: context,
      isScrollControlled: true,
      shape: const RoundedRectangleBorder(borderRadius: BorderRadius.vertical(top: Radius.circular(20))),
      builder: (ctx) => StatefulBuilder(
        builder: (ctx, setCheckoutState) {
          final double discount = double.tryParse(discountCtrl.text.replaceAll(RegExp(r'[^0-9]'), '')) ?? 0.0;
          final double finalTotal = (total - discount) > 0 ? (total - discount) : 0.0;
          final double paid = double.tryParse(paidCtrl.text.replaceAll(RegExp(r'[^0-9]'), '')) ?? 0.0;
          final double change = (paid - finalTotal) > 0 ? (paid - finalTotal) : 0.0;

          return Padding(
            padding: EdgeInsets.only(
              left: 20,
              right: 20,
              top: 20,
              bottom: MediaQuery.of(ctx).viewInsets.bottom + 20,
            ),
            child: SingleChildScrollView(
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                mainAxisSize: MainAxisSize.min,
                children: [
                  Row(
                    mainAxisAlignment: MainAxisAlignment.spaceBetween,
                    children: [
                      Row(
                        children: [
                          const Icon(Icons.point_of_sale, color: Color(0xFF059669)),
                          const SizedBox(width: 8),
                          Text('Pembayaran • ${_selectedCustomer?.name ?? "Umum"}', style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 16)),
                        ],
                      ),
                      IconButton(icon: const Icon(Icons.close), onPressed: () => Navigator.pop(ctx)),
                    ],
                  ),
                  const Divider(),

                  // Metode Pembayaran Chips
                  const Text('Metode Pembayaran:', style: TextStyle(fontSize: 12.5, fontWeight: FontWeight.w600)),
                  const SizedBox(height: 6),
                  Row(
                    children: ['TUNAI', 'QRIS', 'TRANSFER', 'DEBIT'].map((method) {
                      final isSelected = paymentMethod == method;
                      return Padding(
                        padding: const EdgeInsets.only(right: 8),
                        child: ChoiceChip(
                          label: Text(method),
                          selected: isSelected,
                          selectedColor: const Color(0xFFD1FAE5),
                          labelStyle: TextStyle(
                            color: isSelected ? const Color(0xFF065F46) : Colors.black87,
                            fontWeight: isSelected ? FontWeight.bold : FontWeight.normal,
                          ),
                          onSelected: (sel) {
                            if (sel) {
                              setCheckoutState(() {
                                paymentMethod = method;
                                if (method != 'TUNAI') {
                                  paidCtrl.text = finalTotal.toStringAsFixed(0);
                                }
                              });
                            }
                          },
                        ),
                      );
                    }).toList(),
                  ),
                  const SizedBox(height: 12),

                  // Diskon
                  TextField(
                    controller: discountCtrl,
                    keyboardType: TextInputType.number,
                    decoration: const InputDecoration(
                      labelText: 'Potongan / Diskon (Rp)',
                      prefixIcon: Icon(Icons.discount_outlined),
                      border: OutlineInputBorder(),
                    ),
                    onChanged: (_) => setCheckoutState(() {}),
                  ),
                  const SizedBox(height: 12),

                  // Total Tagihan Banner
                  Container(
                    width: double.infinity,
                    padding: const EdgeInsets.all(14),
                    decoration: BoxDecoration(
                      color: const Color(0xFFF1F5F9),
                      borderRadius: BorderRadius.circular(10),
                      border: Border.all(color: const Color(0xFFE2E8F0)),
                    ),
                    child: Column(
                      children: [
                        const Text('TOTAL TAGIHAN', style: TextStyle(fontSize: 11, color: Colors.black54, letterSpacing: 1, fontWeight: FontWeight.bold)),
                        const SizedBox(height: 2),
                        Text(
                          _currency.format(finalTotal),
                          style: const TextStyle(fontSize: 26, fontWeight: FontWeight.bold, color: Color(0xFF0F172A)),
                        ),
                      ],
                    ),
                  ),
                  const SizedBox(height: 12),

                  // Input Uang Diterima (Khusus Tunai)
                  if (paymentMethod == 'TUNAI') ...[
                    TextField(
                      controller: paidCtrl,
                      keyboardType: TextInputType.number,
                      decoration: const InputDecoration(
                        labelText: 'Uang Diterima (Rp) *',
                        prefixIcon: Icon(Icons.payments_outlined),
                        border: OutlineInputBorder(),
                      ),
                      onChanged: (_) => setCheckoutState(() {}),
                    ),
                    const SizedBox(height: 8),

                    // Tombol Pecahan Cepat
                    Wrap(
                      spacing: 6,
                      runSpacing: 6,
                      children: [
                        ActionChip(
                          label: const Text('Uang Pas'),
                          onPressed: () {
                            setCheckoutState(() {
                              paidCtrl.text = finalTotal.toStringAsFixed(0);
                            });
                          },
                        ),
                        ...[10000, 20000, 50000, 100000].map((nominal) {
                          return ActionChip(
                            label: Text(_currency.format(nominal)),
                            onPressed: () {
                              setCheckoutState(() {
                                paidCtrl.text = nominal.toString();
                              });
                            },
                          );
                        }),
                      ],
                    ),
                    const SizedBox(height: 12),

                    // Kembalian
                    Container(
                      padding: const EdgeInsets.symmetric(horizontal: 14, vertical: 10),
                      decoration: BoxDecoration(
                        color: const Color(0xFFECFDF5),
                        borderRadius: BorderRadius.circular(8),
                        border: Border.all(color: const Color(0xFFA7F3D0)),
                      ),
                      child: Row(
                        mainAxisAlignment: MainAxisAlignment.spaceBetween,
                        children: [
                          const Text('Kembalian:', style: TextStyle(fontWeight: FontWeight.w600)),
                          Text(
                            _currency.format(change),
                            style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 17, color: Color(0xFF047857)),
                          ),
                        ],
                      ),
                    ),
                  ],

                  if (paymentMethod == 'QRIS') ...[
                    Container(
                      padding: const EdgeInsets.all(14),
                      decoration: BoxDecoration(
                        color: Colors.white,
                        borderRadius: BorderRadius.circular(12),
                        border: Border.all(color: const Color(0xFF0284C7).withOpacity(0.3)),
                        boxShadow: [
                          BoxShadow(
                            color: Colors.blue.withOpacity(0.04),
                            blurRadius: 8,
                            offset: const Offset(0, 2),
                          ),
                        ],
                      ),
                      child: Column(
                        children: [
                          Row(
                            mainAxisAlignment: MainAxisAlignment.center,
                            children: const [
                              Icon(Icons.qr_code_2, color: Color(0xFF0284C7), size: 22),
                              SizedBox(width: 8),
                              Text('PEMBAYARAN QRIS DINAMIS', style: TextStyle(fontWeight: FontWeight.bold, fontSize: 13, color: Color(0xFF0F172A))),
                            ],
                          ),
                          const SizedBox(height: 10),
                          Container(
                            padding: const EdgeInsets.all(12),
                            decoration: BoxDecoration(
                              color: const Color(0xFFF8FAFC),
                              borderRadius: BorderRadius.circular(10),
                              border: Border.all(color: const Color(0xFFE2E8F0)),
                            ),
                            child: Column(
                              children: [
                                const Icon(Icons.qr_code_scanner, size: 84, color: Color(0xFF0F172A)),
                                const SizedBox(height: 4),
                                Text(
                                  _currency.format(finalTotal),
                                  style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 17, color: Color(0xFF059669)),
                                ),
                                const SizedBox(height: 2),
                                const Text('Mendukung GoPay, OVO, Dana, ShopeePay, BCA, Mandiri, dll.', textAlign: TextAlign.center, style: TextStyle(fontSize: 10, color: Colors.black54)),
                              ],
                            ),
                          ),
                          const SizedBox(height: 6),
                          const Text('Pastikan notifikasi dana masuk telah terverifikasi sebelum simpan transaksi.', textAlign: TextAlign.center, style: TextStyle(fontSize: 11, color: Colors.black54)),
                        ],
                      ),
                    ),
                  ],

                  if (paymentMethod == 'TRANSFER') ...[
                    Container(
                      padding: const EdgeInsets.all(14),
                      decoration: BoxDecoration(
                        color: const Color(0xFFF5F3FF),
                        borderRadius: BorderRadius.circular(10),
                        border: Border.all(color: const Color(0xFFDDD6FE)),
                      ),
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          Row(
                            children: const [
                              Icon(Icons.account_balance, color: Color(0xFF7C3AED), size: 20),
                              SizedBox(width: 8),
                              Text('Transfer Bank', style: TextStyle(fontWeight: FontWeight.bold, fontSize: 13, color: Color(0xFF5B21B6))),
                            ],
                          ),
                          const SizedBox(height: 6),
                          const Text('Periksa mutasi rekening kasir atau cek resi bukti transfer pelanggan sebelum menyelesaikan pesanan.', style: TextStyle(fontSize: 11.5, color: Colors.black87)),
                        ],
                      ),
                    ),
                  ],

                  if (paymentMethod == 'DEBIT') ...[
                    Container(
                      padding: const EdgeInsets.all(14),
                      decoration: BoxDecoration(
                        color: const Color(0xFFFFFBEB),
                        borderRadius: BorderRadius.circular(10),
                        border: Border.all(color: const Color(0xFFFDE68A)),
                      ),
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          Row(
                            children: const [
                              Icon(Icons.credit_card, color: Color(0xFFD97706), size: 20),
                              SizedBox(width: 8),
                              Text('Kartu Debit / EDC', style: TextStyle(fontWeight: FontWeight.bold, fontSize: 13, color: Color(0xFF92400E))),
                            ],
                          ),
                          const SizedBox(height: 6),
                          const Text('Gesek atau insert kartu pada terminal EDC toko dan pastikan status transaksi SUCCESS tercetak.', style: TextStyle(fontSize: 11.5, color: Colors.black87)),
                        ],
                      ),
                    ),
                  ],

                  const SizedBox(height: 18),
                  SizedBox(
                    width: double.infinity,
                    child: ElevatedButton(
                      style: ElevatedButton.styleFrom(
                        backgroundColor: const Color(0xFF059669),
                        foregroundColor: Colors.white,
                        padding: const EdgeInsets.symmetric(vertical: 14),
                        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(10)),
                      ),
                      onPressed: paid < finalTotal
                          ? null
                          : () async {
                              final invoiceNo = await DatabaseHelper.instance.generateInvoiceNumber();
                              const uuid = Uuid();
                              final saleId = uuid.v4();

                              final saleItems = _cart.values.map((c) {
                                return SaleItemModel(
                                  id: uuid.v4(),
                                  saleId: saleId,
                                  productId: c.product.id,
                                  productName: c.product.name,
                                  qty: c.qty,
                                  unitPrice: c.product.sellPrice,
                                  costPrice: c.product.costPrice,
                                  subtotal: c.subtotal,
                                );
                              }).toList();

                              final newSale = SaleModel(
                                id: saleId,
                                invoiceNumber: invoiceNo,
                                createdAt: DateTime.now().toIso8601String(),
                                customerName: _selectedCustomer?.name ?? 'Pelanggan Umum',
                                customerPhone: _selectedCustomer?.phone ?? '',
                                subtotal: total,
                                discount: discount,
                                tax: 0,
                                total: finalTotal,
                                paidAmount: paid,
                                changeAmount: change,
                                paymentMethod: paymentMethod,
                                status: 'PAID',
                                items: saleItems,
                              );

                              await DatabaseHelper.instance.createSale(newSale, saleItems);

                              if (ctx.mounted) Navigator.pop(ctx);
                              _clearCart();
                              _refreshCatalog();

                              if (mounted) _showSuccessReceiptDialog(newSale);
                            },
                      child: const Text('Selesaikan Transaksi & Simpan', style: TextStyle(fontWeight: FontWeight.bold, fontSize: 15)),
                    ),
                  ),
                ],
              ),
            ),
          );
        },
      ),
    );
  }

  void _showSuccessReceiptDialog(SaleModel sale) {
    showDialog(
      context: context,
      barrierDismissible: false,
      builder: (ctx) => AlertDialog(
        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(16)),
        title: Column(
          children: [
            const Icon(Icons.check_circle, color: Color(0xFF10B981), size: 54),
            const SizedBox(height: 8),
            const Text('Transaksi Berhasil!', style: TextStyle(fontWeight: FontWeight.bold, fontSize: 18)),
            Text(sale.invoiceNumber, style: const TextStyle(fontSize: 12, color: Colors.grey)),
          ],
        ),
        content: Column(
          mainAxisSize: MainAxisSize.min,
          children: [
            Container(
              padding: const EdgeInsets.all(12),
              decoration: BoxDecoration(
                color: const Color(0xFFF8FAFC),
                borderRadius: BorderRadius.circular(8),
                border: Border.all(color: const Color(0xFFE2E8F0)),
              ),
              child: Column(
                children: [
                  Row(
                    mainAxisAlignment: MainAxisAlignment.spaceBetween,
                    children: [
                      const Text('Total Tagihan:'),
                      Text(_currency.format(sale.total), style: const TextStyle(fontWeight: FontWeight.bold)),
                    ],
                  ),
                  Row(
                    mainAxisAlignment: MainAxisAlignment.spaceBetween,
                    children: [
                      Text('Bayar (${sale.paymentMethod}):'),
                      Text(_currency.format(sale.paidAmount)),
                    ],
                  ),
                  const Divider(),
                  Row(
                    mainAxisAlignment: MainAxisAlignment.spaceBetween,
                    children: [
                      const Text('Kembalian:', style: TextStyle(fontWeight: FontWeight.bold)),
                      Text(
                        _currency.format(sale.changeAmount),
                        style: const TextStyle(fontWeight: FontWeight.bold, color: Color(0xFF059669), fontSize: 17),
                      ),
                    ],
                  ),
                ],
              ),
            ),
            const SizedBox(height: 14),
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
                    style: OutlinedButton.styleFrom(foregroundColor: const Color(0xFF059669)),
                    icon: const Icon(Icons.share, size: 18),
                    label: const Text('WhatsApp'),
                    onPressed: () async {
                      final store = await SettingsService.getSettings();
                      await ReceiptService.shareReceiptWhatsApp(sale, store);
                    },
                  ),
                ),
              ],
            ),
          ],
        ),
        actions: [
          ElevatedButton(
            style: ElevatedButton.styleFrom(
              backgroundColor: const Color(0xFF059669),
              foregroundColor: Colors.white,
              minimumSize: const Size(double.infinity, 45),
            ),
            onPressed: () => Navigator.pop(ctx),
            child: const Text('Transaksi Baru'),
          ),
        ],
      ),
    );
  }

  @override
  Widget build(BuildContext context) {
    return LayoutBuilder(
      builder: (context, constraints) {
        final isDesktopOrTablet = constraints.maxWidth >= 850;

        return Scaffold(
          backgroundColor: const Color(0xFFF8FAFC),
          appBar: AppBar(
            title: Row(
              children: [
                Container(
                  padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 4),
                  decoration: BoxDecoration(
                    color: const Color(0xFF059669),
                    borderRadius: BorderRadius.circular(6),
                  ),
                  child: const Text('POS', style: TextStyle(fontWeight: FontWeight.bold, fontSize: 12, color: Colors.white)),
                ),
                const SizedBox(width: 8),
                const Text('Kasir YUKelola', style: TextStyle(fontWeight: FontWeight.bold, fontSize: 16)),
              ],
            ),
            backgroundColor: const Color(0xFF0F172A), // Slate 900
            foregroundColor: Colors.white,
            actions: [
              // Tombol Antrean Hold
              Badge(
                label: Text('$_holdCount'),
                isLabelVisible: _holdCount > 0,
                child: IconButton(
                  icon: const Icon(Icons.pause_circle_outline),
                  tooltip: 'Antrean Ditahan (Hold)',
                  onPressed: _openHoldSalesModal,
                ),
              ),
              IconButton(
                icon: const Icon(Icons.qr_code_scanner),
                tooltip: 'Scan Barcode Kamera',
                onPressed: _scanBarcodeToAdd,
              ),
            ],
          ),
          body: isDesktopOrTablet ? _buildDesktopLayout() : _buildMobileLayout(),
          bottomSheet: isDesktopOrTablet ? null : _buildMobileBottomSheet(),
        );
      },
    );
  }

  // --- LAYOUT DESKTOP / WINDOWS / TABLET (2 KOLOM PERSIS SUPERPOS WEB) ---
  Widget _buildDesktopLayout() {
    return Row(
      children: [
        // Kolom Kiri: Katalog & Kategori Produk (62% Lebar Layar)
        Expanded(
          flex: 62,
          child: Column(
            children: [
              _buildSearchBarAndFilter(),
              Expanded(child: _buildProductGrid(crossAxisCount: 3)),
            ],
          ),
        ),

        // Garis Pembatas
        const VerticalDivider(width: 1, color: Color(0xFFE2E8F0)),

        // Kolom Kanan: Panel Keranjang & Pembayaran (38% Lebar Layar)
        Expanded(
          flex: 38,
          child: Container(
            color: Colors.white,
            child: Column(
              children: [
                _buildCustomerHeader(),
                const Divider(height: 1),
                Expanded(child: _buildCartItemList()),
                const Divider(height: 1),
                _buildBillingSummary(isDesktop: true),
              ],
            ),
          ),
        ),
      ],
    );
  }

  // --- LAYOUT HP / MOBILE ---
  Widget _buildMobileLayout() {
    return Column(
      children: [
        _buildCustomerHeader(),
        _buildSearchBarAndFilter(),
        Expanded(child: _buildProductGrid(crossAxisCount: 2)),
        const SizedBox(height: 70), // Spasi bottom sheet
      ],
    );
  }

  Widget _buildCustomerHeader() {
    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 14, vertical: 8),
      color: const Color(0xFFF1F5F9),
      child: Row(
        children: [
          const Icon(Icons.person, size: 18, color: Color(0xFF475569)),
          const SizedBox(width: 8),
          Expanded(
            child: DropdownButtonHideUnderline(
              child: DropdownButton<CustomerModel>(
                isDense: true,
                value: _selectedCustomer,
                items: _customers.map((c) {
                  return DropdownMenuItem(
                    value: c,
                    child: Text(
                      c.name,
                      style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 13, color: Color(0xFF0F172A)),
                    ),
                  );
                }).toList(),
                onChanged: (val) {
                  if (val != null) setState(() => _selectedCustomer = val);
                },
              ),
            ),
          ),
          IconButton(
            icon: const Icon(Icons.person_add_alt_1, size: 18, color: Color(0xFF059669)),
            tooltip: 'Tambah Pelanggan Baru',
            onPressed: _openAddCustomerDialog,
          ),
        ],
      ),
    );
  }

  Widget _buildSearchBarAndFilter() {
    return Column(
      children: [
        Padding(
          padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 8),
          child: TextField(
            controller: _searchController,
            decoration: InputDecoration(
              hintText: 'Cari produk YUKelola / scan SKU...',
              prefixIcon: const Icon(Icons.search, size: 20),
              suffixIcon: _searchController.text.isNotEmpty
                  ? IconButton(
                      icon: const Icon(Icons.clear, size: 18),
                      onPressed: () {
                        _searchController.clear();
                        _refreshCatalog();
                      },
                    )
                  : null,
              contentPadding: const EdgeInsets.symmetric(horizontal: 14, vertical: 8),
              border: OutlineInputBorder(borderRadius: BorderRadius.circular(8)),
            ),
            onChanged: (_) => _refreshCatalog(),
          ),
        ),
        SingleChildScrollView(
          scrollDirection: Axis.horizontal,
          padding: const EdgeInsets.symmetric(horizontal: 12),
          child: Row(
            children: [
              ChoiceChip(
                label: const Text('Semua'),
                selected: _selectedCategory == 'Semua',
                selectedColor: const Color(0xFFD1FAE5),
                onSelected: (sel) {
                  if (sel) {
                    setState(() => _selectedCategory = 'Semua');
                    _refreshCatalog();
                  }
                },
              ),
              const SizedBox(width: 6),
              ..._categories.map((c) {
                return Padding(
                  padding: const EdgeInsets.only(right: 6),
                  child: ChoiceChip(
                    label: Text(c.name),
                    selected: _selectedCategory == c.name,
                    selectedColor: const Color(0xFFD1FAE5),
                    onSelected: (sel) {
                      setState(() => _selectedCategory = sel ? c.name : 'Semua');
                      _refreshCatalog();
                    },
                  ),
                );
              }),
            ],
          ),
        ),
        const SizedBox(height: 6),
      ],
    );
  }

  Widget _buildProductGrid({required int crossAxisCount}) {
    if (_isLoading) return const Center(child: CircularProgressIndicator());
    if (_products.isEmpty) {
      return Center(
        child: Text('Produk tidak ditemukan', style: TextStyle(color: Colors.grey.shade600)),
      );
    }

    return GridView.builder(
      padding: const EdgeInsets.all(12),
      gridDelegate: SliverGridDelegateWithFixedCrossAxisCount(
        crossAxisCount: crossAxisCount,
        childAspectRatio: 0.95,
        crossAxisSpacing: 10,
        mainAxisSpacing: 10,
      ),
      itemCount: _products.length,
      itemBuilder: (context, index) {
        final prod = _products[index];
        final inCart = _cart[prod.id]?.qty ?? 0;
        final isOutOfStock = prod.stock <= 0;

        return InkWell(
          onTap: isOutOfStock ? null : () => _addToCart(prod),
          borderRadius: BorderRadius.circular(10),
          child: Container(
            decoration: BoxDecoration(
              color: Colors.white,
              borderRadius: BorderRadius.circular(10),
              border: Border.all(
                color: inCart > 0 ? const Color(0xFF059669) : const Color(0xFFE2E8F0),
                width: inCart > 0 ? 1.8 : 1,
              ),
              boxShadow: [
                BoxShadow(color: Colors.black.withValues(alpha: 0.03), blurRadius: 4, offset: const Offset(0, 1)),
              ],
            ),
            child: Stack(
              children: [
                Padding(
                  padding: const EdgeInsets.all(10),
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Container(
                        width: double.infinity,
                        height: 55,
                        decoration: BoxDecoration(
                          color: const Color(0xFFF8FAFC),
                          borderRadius: BorderRadius.circular(6),
                        ),
                        child: Icon(
                          Icons.inventory_2_outlined,
                          size: 28,
                          color: isOutOfStock ? Colors.grey : const Color(0xFF0F172A),
                        ),
                      ),
                      const SizedBox(height: 6),
                      Text(
                        prod.name,
                        maxLines: 2,
                        overflow: TextOverflow.ellipsis,
                        style: TextStyle(
                          fontWeight: FontWeight.bold,
                          fontSize: 12.5,
                          color: isOutOfStock ? Colors.grey : const Color(0xFF0F172A),
                        ),
                      ),
                      const Spacer(),
                      Text(
                        _currency.format(prod.sellPrice),
                        style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 13, color: Color(0xFF059669)),
                      ),
                      Text('Sisa: ${prod.stock} ${prod.unit}', style: TextStyle(fontSize: 10.5, color: isOutOfStock ? Colors.red : Colors.grey.shade600)),
                    ],
                  ),
                ),
                if (inCart > 0)
                  Positioned(
                    top: 6,
                    right: 6,
                    child: CircleAvatar(
                      radius: 11,
                      backgroundColor: const Color(0xFF059669),
                      child: Text('$inCart', style: const TextStyle(color: Colors.white, fontSize: 10, fontWeight: FontWeight.bold)),
                    ),
                  ),
              ],
            ),
          ),
        );
      },
    );
  }

  Widget _buildCartItemList() {
    if (_cart.isEmpty) {
      return Center(
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            Icon(Icons.shopping_cart_outlined, size: 48, color: Colors.grey.shade400),
            const SizedBox(height: 8),
            Text('Keranjang masih kosong', style: TextStyle(color: Colors.grey.shade600, fontSize: 13)),
          ],
        ),
      );
    }

    return ListView.separated(
      padding: const EdgeInsets.all(12),
      itemCount: _cart.length,
      separatorBuilder: (_, _) => const Divider(height: 1),
      itemBuilder: (ctx, index) {
        final item = _cart.values.elementAt(index);
        return Padding(
          padding: const EdgeInsets.symmetric(vertical: 6),
          child: Row(
            children: [
              Expanded(
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Text(item.product.name, style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 13)),
                    Text(
                      '${_currency.format(item.product.sellPrice)} / ${item.product.unit}',
                      style: TextStyle(color: Colors.grey.shade600, fontSize: 11),
                    ),
                  ],
                ),
              ),
              Row(
                children: [
                  IconButton(
                    icon: const Icon(Icons.remove_circle_outline, size: 20),
                    onPressed: () => _decreaseFromCart(item.product),
                  ),
                  Text('${item.qty}', style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 13)),
                  IconButton(
                    icon: const Icon(Icons.add_circle, color: Color(0xFF059669), size: 20),
                    onPressed: () => _addToCart(item.product),
                  ),
                ],
              ),
              const SizedBox(width: 8),
              Text(_currency.format(item.subtotal), style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 13)),
            ],
          ),
        );
      },
    );
  }

  Widget _buildBillingSummary({required bool isDesktop}) {
    return Container(
      padding: const EdgeInsets.all(14),
      color: const Color(0xFFF8FAFC),
      child: Column(
        children: [
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              Text('Jumlah Item: $_totalCartItems', style: const TextStyle(fontSize: 12, color: Colors.black54)),
              Row(
                children: [
                  if (_cart.isNotEmpty)
                    TextButton.icon(
                      icon: const Icon(Icons.pause, size: 16),
                      label: const Text('Hold'),
                      onPressed: _holdCurrentSale,
                    ),
                  if (_cart.isNotEmpty)
                    TextButton.icon(
                      style: TextButton.styleFrom(foregroundColor: Colors.red),
                      icon: const Icon(Icons.delete_outline, size: 16),
                      label: const Text('Batal'),
                      onPressed: _clearCart,
                    ),
                ],
              ),
            ],
          ),
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              const Text('TOTAL:', style: TextStyle(fontWeight: FontWeight.bold, fontSize: 16)),
              Text(_currency.format(_cartSubtotal), style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 20, color: Color(0xFF059669))),
            ],
          ),
          const SizedBox(height: 10),
          SizedBox(
            width: double.infinity,
            child: ElevatedButton.icon(
              style: ElevatedButton.styleFrom(
                backgroundColor: const Color(0xFF059669),
                foregroundColor: Colors.white,
                padding: const EdgeInsets.symmetric(vertical: 14),
                shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(8)),
              ),
              icon: const Icon(Icons.payment),
              label: const Text('Bayar Sekarang', style: TextStyle(fontSize: 15, fontWeight: FontWeight.bold)),
              onPressed: _cart.isEmpty ? null : _openCheckoutDialog,
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildMobileBottomSheet() {
    if (_cart.isEmpty) return const SizedBox.shrink();

    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 10),
      decoration: BoxDecoration(
        color: Colors.white,
        border: const Border(top: BorderSide(color: Color(0xFFE2E8F0))),
        boxShadow: [
          BoxShadow(color: Colors.black.withValues(alpha: 0.08), blurRadius: 10, offset: const Offset(0, -2)),
        ],
      ),
      child: SafeArea(
        child: Row(
          children: [
            Badge(
              label: Text('$_totalCartItems'),
              child: const Icon(Icons.shopping_cart, color: Color(0xFF059669), size: 28),
            ),
            const SizedBox(width: 14),
            Column(
              mainAxisSize: MainAxisSize.min,
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                const Text('Total Tagihan', style: TextStyle(fontSize: 11, color: Colors.grey)),
                Text(_currency.format(_cartSubtotal), style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 16, color: Color(0xFF0F172A))),
              ],
            ),
            const Spacer(),
            ElevatedButton(
              style: ElevatedButton.styleFrom(
                backgroundColor: const Color(0xFF059669),
                foregroundColor: Colors.white,
                padding: const EdgeInsets.symmetric(horizontal: 20, vertical: 12),
                shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(8)),
              ),
              onPressed: _openCheckoutDialog,
              child: const Row(
                children: [
                  Text('Bayar', style: TextStyle(fontWeight: FontWeight.bold)),
                  SizedBox(width: 4),
                  Icon(Icons.arrow_forward_ios, size: 14),
                ],
              ),
            ),
          ],
        ),
      ),
    );
  }
}
