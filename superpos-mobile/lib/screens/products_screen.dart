import 'package:flutter/material.dart';
import 'package:intl/intl.dart';
import 'package:uuid/uuid.dart';
import '../database/database_helper.dart';
import '../models/category_model.dart';
import '../models/product_model.dart';
import 'barcode_scanner_screen.dart';

class ProductsScreen extends StatefulWidget {
  const ProductsScreen({super.key});

  @override
  State<ProductsScreen> createState() => _ProductsScreenState();
}

class _ProductsScreenState extends State<ProductsScreen> {
  final _currency = NumberFormat.currency(locale: 'id_ID', symbol: 'Rp ', decimalDigits: 0);
  final _searchController = TextEditingController();

  List<ProductModel> _products = [];
  List<CategoryModel> _categories = [];
  String _selectedCategory = 'Semua';
  bool _isLoading = true;

  @override
  void initState() {
    super.initState();
    _loadData();
  }

  Future<void> _loadData() async {
    setState(() => _isLoading = true);
    final cats = await DatabaseHelper.instance.getAllCategories();
    final prods = await DatabaseHelper.instance.getAllProducts(
      search: _searchController.text,
      category: _selectedCategory,
    );
    setState(() {
      _categories = cats;
      _products = prods;
      _isLoading = false;
    });
  }

  Future<void> _openProductForm({ProductModel? product}) async {
    final isEdit = product != null;
    final nameCtrl = TextEditingController(text: product?.name ?? '');
    final skuCtrl = TextEditingController(text: product?.sku ?? '');
    final costCtrl = TextEditingController(text: product != null ? product.costPrice.toStringAsFixed(0) : '0');
    final sellCtrl = TextEditingController(text: product != null ? product.sellPrice.toStringAsFixed(0) : '');
    final stockCtrl = TextEditingController(text: product != null ? product.stock.toString() : '10');
    final unitCtrl = TextEditingController(text: product?.unit ?? 'Pcs');
    String selectedCat = product?.category ?? (_categories.isNotEmpty ? _categories.first.name : 'Umum');

    await showModalBottomSheet(
      context: context,
      isScrollControlled: true,
      shape: const RoundedRectangleBorder(borderRadius: BorderRadius.vertical(top: Radius.circular(20))),
      builder: (ctx) => StatefulBuilder(
        builder: (ctx, setModalState) => Padding(
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
                    Text(
                      isEdit ? 'Edit Produk' : 'Tambah Produk Baru',
                      style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 17),
                    ),
                    IconButton(icon: const Icon(Icons.close), onPressed: () => Navigator.pop(ctx)),
                  ],
                ),
                const Divider(),
                const SizedBox(height: 8),
                TextField(
                  controller: nameCtrl,
                  decoration: const InputDecoration(
                    labelText: 'Nama Produk *',
                    border: OutlineInputBorder(),
                  ),
                ),
                const SizedBox(height: 12),
                Row(
                  children: [
                    Expanded(
                      child: TextField(
                        controller: skuCtrl,
                        decoration: const InputDecoration(
                          labelText: 'Barcode / SKU',
                          border: OutlineInputBorder(),
                        ),
                      ),
                    ),
                    const SizedBox(width: 8),
                    IconButton.filled(
                      style: IconButton.styleFrom(backgroundColor: const Color(0xFF059669)),
                      icon: const Icon(Icons.qr_code_scanner),
                      tooltip: 'Scan Barcode',
                      onPressed: () async {
                        final scanned = await Navigator.push<String>(
                          context,
                          MaterialPageRoute(builder: (_) => const BarcodeScannerScreen()),
                        );
                        if (scanned != null && scanned.isNotEmpty) {
                          setModalState(() {
                            skuCtrl.text = scanned;
                          });
                        }
                      },
                    ),
                  ],
                ),
                const SizedBox(height: 12),
                DropdownButtonFormField<String>(
                  initialValue: _categories.any((c) => c.name == selectedCat) ? selectedCat : (_categories.isNotEmpty ? _categories.first.name : 'Umum'),
                  decoration: const InputDecoration(labelText: 'Kategori', border: OutlineInputBorder()),
                  items: _categories.map((c) => DropdownMenuItem(value: c.name, child: Text(c.name))).toList(),
                  onChanged: (val) {
                    if (val != null) setModalState(() => selectedCat = val);
                  },
                ),
                const SizedBox(height: 12),
                Row(
                  children: [
                    Expanded(
                      child: TextField(
                        controller: costCtrl,
                        keyboardType: TextInputType.number,
                        decoration: const InputDecoration(
                          labelText: 'Harga Modal (Rp)',
                          border: OutlineInputBorder(),
                        ),
                      ),
                    ),
                    const SizedBox(width: 10),
                    Expanded(
                      child: TextField(
                        controller: sellCtrl,
                        keyboardType: TextInputType.number,
                        decoration: const InputDecoration(
                          labelText: 'Harga Jual (Rp) *',
                          border: OutlineInputBorder(),
                        ),
                      ),
                    ),
                  ],
                ),
                const SizedBox(height: 12),
                Row(
                  children: [
                    Expanded(
                      child: TextField(
                        controller: stockCtrl,
                        keyboardType: TextInputType.number,
                        decoration: const InputDecoration(
                          labelText: 'Stok Tersedia',
                          border: OutlineInputBorder(),
                        ),
                      ),
                    ),
                    const SizedBox(width: 10),
                    Expanded(
                      child: TextField(
                        controller: unitCtrl,
                        decoration: const InputDecoration(
                          labelText: 'Satuan (Pcs, Box, dll)',
                          border: OutlineInputBorder(),
                        ),
                      ),
                    ),
                  ],
                ),
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
                    onPressed: () async {
                      if (nameCtrl.text.trim().isEmpty || sellCtrl.text.trim().isEmpty) {
                        ScaffoldMessenger.of(context).showSnackBar(
                          const SnackBar(content: Text('Nama dan Harga Jual wajib diisi!')),
                        );
                        return;
                      }

                      final newProd = ProductModel(
                        id: product?.id ?? const Uuid().v4(),
                        sku: skuCtrl.text.trim().isEmpty ? DateTime.now().millisecondsSinceEpoch.toString().substring(5) : skuCtrl.text.trim(),
                        name: nameCtrl.text.trim(),
                        category: selectedCat,
                        costPrice: double.tryParse(costCtrl.text.replaceAll(RegExp(r'[^0-9]'), '')) ?? 0.0,
                        sellPrice: double.tryParse(sellCtrl.text.replaceAll(RegExp(r'[^0-9]'), '')) ?? 0.0,
                        stock: int.tryParse(stockCtrl.text.replaceAll(RegExp(r'[^0-9]'), '')) ?? 0,
                        unit: unitCtrl.text.trim().isEmpty ? 'Pcs' : unitCtrl.text.trim(),
                        updatedAt: DateTime.now().toIso8601String(),
                      );

                      if (isEdit) {
                        await DatabaseHelper.instance.updateProduct(newProd);
                      } else {
                        await DatabaseHelper.instance.insertProduct(newProd);
                      }

                      if (ctx.mounted) Navigator.pop(ctx);
                      _loadData();
                    },
                    child: Text(isEdit ? 'Simpan Perubahan' : 'Tambahkan Produk'),
                  ),
                ),
              ],
            ),
          ),
        ),
      ),
    );
  }

  Future<void> _deleteProduct(ProductModel product) async {
    final confirm = await showDialog<bool>(
      context: context,
      builder: (ctx) => AlertDialog(
        title: const Text('Hapus Produk?'),
        content: Text('Apakah Anda yakin ingin menghapus "${product.name}"?'),
        actions: [
          TextButton(onPressed: () => Navigator.pop(ctx, false), child: const Text('Batal')),
          TextButton(
            style: TextButton.styleFrom(foregroundColor: Colors.red),
            onPressed: () => Navigator.pop(ctx, true),
            child: const Text('Hapus'),
          ),
        ],
      ),
    );

    if (confirm == true) {
      await DatabaseHelper.instance.deleteProduct(product.id);
      _loadData();
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Katalog & Stok Barang'),
        backgroundColor: const Color(0xFF0F172A),
        foregroundColor: Colors.white,
        actions: [
          IconButton(
            icon: const Icon(Icons.add_circle_outline),
            tooltip: 'Tambah Produk',
            onPressed: () => _openProductForm(),
          ),
        ],
      ),
      body: Column(
        children: [
          // Bar Pencarian & Scan
          Padding(
            padding: const EdgeInsets.all(12),
            child: Row(
              children: [
                Expanded(
                  child: TextField(
                    controller: _searchController,
                    decoration: InputDecoration(
                      hintText: 'Cari nama produk / barcode...',
                      prefixIcon: const Icon(Icons.search),
                      suffixIcon: _searchController.text.isNotEmpty
                          ? IconButton(
                              icon: const Icon(Icons.clear),
                              onPressed: () {
                                _searchController.clear();
                                _loadData();
                              },
                            )
                          : null,
                      contentPadding: const EdgeInsets.symmetric(horizontal: 14, vertical: 10),
                      border: OutlineInputBorder(borderRadius: BorderRadius.circular(10)),
                    ),
                    onChanged: (_) => _loadData(),
                  ),
                ),
                const SizedBox(width: 8),
                IconButton.filled(
                  style: IconButton.styleFrom(backgroundColor: const Color(0xFF059669)),
                  icon: const Icon(Icons.qr_code_scanner),
                  onPressed: () async {
                    final scanned = await Navigator.push<String>(
                      context,
                      MaterialPageRoute(builder: (_) => const BarcodeScannerScreen()),
                    );
                    if (scanned != null && scanned.isNotEmpty) {
                      _searchController.text = scanned;
                      _loadData();
                    }
                  },
                ),
              ],
            ),
          ),

          // Filter Kategori Chips
          SingleChildScrollView(
            scrollDirection: Axis.horizontal,
            padding: const EdgeInsets.symmetric(horizontal: 12),
            child: Row(
              children: [
                ChoiceChip(
                  label: const Text('Semua'),
                  selected: _selectedCategory == 'Semua',
                  onSelected: (sel) {
                    if (sel) {
                      setState(() => _selectedCategory = 'Semua');
                      _loadData();
                    }
                  },
                ),
                const SizedBox(width: 8),
                ..._categories.map((c) {
                  return Padding(
                    padding: const EdgeInsets.only(right: 8),
                    child: ChoiceChip(
                      label: Text(c.name),
                      selected: _selectedCategory == c.name,
                      onSelected: (sel) {
                        setState(() => _selectedCategory = sel ? c.name : 'Semua');
                        _loadData();
                      },
                    ),
                  );
                }),
              ],
            ),
          ),
          const SizedBox(height: 8),

          // List Produk
          Expanded(
            child: _isLoading
                ? const Center(child: CircularProgressIndicator())
                : _products.isEmpty
                    ? Center(
                        child: Column(
                          mainAxisAlignment: MainAxisAlignment.center,
                          children: [
                            Icon(Icons.inventory_2_outlined, size: 64, color: Colors.grey.shade400),
                            const SizedBox(height: 12),
                            Text(
                              'Belum ada produk ditemukan',
                              style: TextStyle(color: Colors.grey.shade600, fontSize: 15),
                            ),
                            const SizedBox(height: 12),
                            ElevatedButton.icon(
                              style: ElevatedButton.styleFrom(backgroundColor: const Color(0xFF1E3A8A)),
                              icon: const Icon(Icons.add, color: Colors.white),
                              label: const Text('Tambah Produk Pertama', style: TextStyle(color: Colors.white)),
                              onPressed: () => _openProductForm(),
                            ),
                          ],
                        ),
                      )
                    : ListView.separated(
                        padding: const EdgeInsets.all(12),
                        itemCount: _products.length,
                        separatorBuilder: (_, _) => const SizedBox(height: 8),
                        itemBuilder: (context, index) {
                          final prod = _products[index];
                          final isLowStock = prod.stock <= 10;

                          return Card(
                            elevation: 1.5,
                            shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(10)),
                            child: ListTile(
                              leading: CircleAvatar(
                                backgroundColor: isLowStock ? Colors.amber.shade100 : const Color(0xFFE2E8F0),
                                child: Icon(
                                  Icons.fastfood,
                                  color: isLowStock ? Colors.orange.shade800 : const Color(0xFF1E3A8A),
                                ),
                              ),
                              title: Text(
                                prod.name,
                                style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 14),
                              ),
                              subtitle: Column(
                                crossAxisAlignment: CrossAxisAlignment.start,
                                children: [
                                  Text(
                                    '${prod.category} • Barcode: ${prod.sku}',
                                    style: TextStyle(color: Colors.grey.shade600, fontSize: 11.5),
                                  ),
                                  const SizedBox(height: 2),
                                  Text(
                                    _currency.format(prod.sellPrice),
                                    style: const TextStyle(
                                      color: Color(0xFF1E3A8A),
                                      fontWeight: FontWeight.bold,
                                      fontSize: 13,
                                    ),
                                  ),
                                ],
                              ),
                              trailing: Column(
                                mainAxisAlignment: MainAxisAlignment.center,
                                crossAxisAlignment: CrossAxisAlignment.end,
                                children: [
                                  Container(
                                    padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 3),
                                    decoration: BoxDecoration(
                                      color: isLowStock ? Colors.red.shade50 : Colors.green.shade50,
                                      borderRadius: BorderRadius.circular(12),
                                      border: Border.all(
                                        color: isLowStock ? Colors.red.shade300 : Colors.green.shade300,
                                      ),
                                    ),
                                    child: Text(
                                      'Stok: ${prod.stock} ${prod.unit}',
                                      style: TextStyle(
                                        fontSize: 11,
                                        fontWeight: FontWeight.bold,
                                        color: isLowStock ? Colors.red.shade700 : Colors.green.shade700,
                                      ),
                                    ),
                                  ),
                                  const SizedBox(height: 4),
                                  Row(
                                    mainAxisSize: MainAxisSize.min,
                                    children: [
                                      InkWell(
                                        onTap: () => _openProductForm(product: prod),
                                        child: const Padding(
                                          padding: EdgeInsets.all(4.0),
                                          child: Icon(Icons.edit, size: 18, color: Colors.blueGrey),
                                        ),
                                      ),
                                      const SizedBox(width: 8),
                                      InkWell(
                                        onTap: () => _deleteProduct(prod),
                                        child: const Padding(
                                          padding: EdgeInsets.all(4.0),
                                          child: Icon(Icons.delete_outline, size: 18, color: Colors.red),
                                        ),
                                      ),
                                    ],
                                  ),
                                ],
                              ),
                            ),
                          );
                        },
                      ),
          ),
        ],
      ),
      floatingActionButton: FloatingActionButton(
        backgroundColor: const Color(0xFF059669),
        foregroundColor: Colors.white,
        onPressed: () => _openProductForm(),
        child: const Icon(Icons.add),
      ),
    );
  }
}
