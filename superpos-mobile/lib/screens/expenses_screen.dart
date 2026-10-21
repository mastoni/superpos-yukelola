import 'package:flutter/material.dart';
import 'package:intl/intl.dart';
import 'package:uuid/uuid.dart';
import '../database/database_helper.dart';
import '../models/expense_model.dart';

class ExpensesScreen extends StatefulWidget {
  const ExpensesScreen({super.key});

  @override
  State<ExpensesScreen> createState() => _ExpensesScreenState();
}

class _ExpensesScreenState extends State<ExpensesScreen> {
  final _currency = NumberFormat.currency(locale: 'id_ID', symbol: 'Rp ', decimalDigits: 0);
  List<ExpenseModel> _expenses = [];
  bool _isLoading = true;

  @override
  void initState() {
    super.initState();
    _loadExpenses();
  }

  Future<void> _loadExpenses() async {
    setState(() => _isLoading = true);
    final list = await DatabaseHelper.instance.getAllExpenses();
    setState(() {
      _expenses = list;
      _isLoading = false;
    });
  }

  Future<void> _openAddExpenseModal() async {
    final titleCtrl = TextEditingController();
    final amountCtrl = TextEditingController();
    final noteCtrl = TextEditingController();
    String category = 'Operasional';

    final categories = ['Operasional', 'Gaji Karyawan', 'Sewa & Listrik', 'Perlengkapan Toko', 'Lain-lain'];

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
                    const Text('Catat Pengeluaran Baru', style: TextStyle(fontWeight: FontWeight.bold, fontSize: 16)),
                    IconButton(icon: const Icon(Icons.close), onPressed: () => Navigator.pop(ctx)),
                  ],
                ),
                const Divider(),
                TextField(
                  controller: titleCtrl,
                  decoration: const InputDecoration(labelText: 'Keperluan / Judul *', border: OutlineInputBorder()),
                ),
                const SizedBox(height: 12),
                TextField(
                  controller: amountCtrl,
                  keyboardType: TextInputType.number,
                  decoration: const InputDecoration(labelText: 'Jumlah Biaya (Rp) *', border: OutlineInputBorder()),
                ),
                const SizedBox(height: 12),
                DropdownButtonFormField<String>(
                  initialValue: category,
                  decoration: const InputDecoration(labelText: 'Kategori Pengeluaran', border: OutlineInputBorder()),
                  items: categories.map((c) => DropdownMenuItem(value: c, child: Text(c))).toList(),
                  onChanged: (val) {
                    if (val != null) setModalState(() => category = val);
                  },
                ),
                const SizedBox(height: 12),
                TextField(
                  controller: noteCtrl,
                  decoration: const InputDecoration(labelText: 'Catatan Tambahan', border: OutlineInputBorder()),
                ),
                const SizedBox(height: 16),
                SizedBox(
                  width: double.infinity,
                  child: ElevatedButton(
                    style: ElevatedButton.styleFrom(
                      backgroundColor: const Color(0xFF1E3A8A),
                      foregroundColor: Colors.white,
                      padding: const EdgeInsets.symmetric(vertical: 14),
                    ),
                    onPressed: () async {
                      if (titleCtrl.text.trim().isEmpty || amountCtrl.text.trim().isEmpty) {
                        return;
                      }

                      final amount = double.tryParse(amountCtrl.text.replaceAll(RegExp(r'[^0-9]'), '')) ?? 0.0;
                      final ex = ExpenseModel(
                        id: const Uuid().v4(),
                        title: titleCtrl.text.trim(),
                        amount: amount,
                        category: category,
                        note: noteCtrl.text.trim(),
                        createdAt: DateTime.now().toIso8601String(),
                      );

                      await DatabaseHelper.instance.insertExpense(ex);
                      if (ctx.mounted) Navigator.pop(ctx);
                      _loadExpenses();
                    },
                    child: const Text('Simpan Pengeluaran'),
                  ),
                ),
              ],
            ),
          ),
        ),
      ),
    );
  }

  double get _totalExpense => _expenses.fold(0.0, (sum, e) => sum + e.amount);

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Pengeluaran Toko'),
        backgroundColor: const Color(0xFF1E3A8A),
        foregroundColor: Colors.white,
        actions: [
          IconButton(
            icon: const Icon(Icons.add_circle_outline),
            onPressed: _openAddExpenseModal,
          ),
        ],
      ),
      body: Column(
        children: [
          // Total Pengeluaran Banner
          Container(
            width: double.infinity,
            margin: const EdgeInsets.all(12),
            padding: const EdgeInsets.all(16),
            decoration: BoxDecoration(
              color: Colors.red.shade50,
              borderRadius: BorderRadius.circular(12),
              border: Border.all(color: Colors.red.shade200),
            ),
            child: Row(
              mainAxisAlignment: MainAxisAlignment.spaceBetween,
              children: [
                Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    const Text('Total Pengeluaran Tercatat', style: TextStyle(color: Colors.red, fontSize: 12)),
                    const SizedBox(height: 4),
                    Text(
                      _currency.format(_totalExpense),
                      style: TextStyle(fontSize: 20, fontWeight: FontWeight.bold, color: Colors.red.shade900),
                    ),
                  ],
                ),
                Icon(Icons.money_off, size: 36, color: Colors.red.shade700),
              ],
            ),
          ),

          Expanded(
            child: _isLoading
                ? const Center(child: CircularProgressIndicator())
                : _expenses.isEmpty
                    ? Center(
                        child: Text('Belum ada catatan pengeluaran', style: TextStyle(color: Colors.grey.shade600)),
                      )
                    : ListView.separated(
                        padding: const EdgeInsets.symmetric(horizontal: 12),
                        itemCount: _expenses.length,
                        separatorBuilder: (_, _) => const SizedBox(height: 8),
                        itemBuilder: (ctx, index) {
                          final ex = _expenses[index];
                          return Card(
                            elevation: 1,
                            child: ListTile(
                              leading: CircleAvatar(
                                backgroundColor: Colors.red.shade50,
                                child: Icon(Icons.account_balance_wallet, color: Colors.red.shade800, size: 20),
                              ),
                              title: Text(ex.title, style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 13.5)),
                              subtitle: Text(
                                '${ex.category} ${ex.note.isNotEmpty ? '• ${ex.note}' : ''}',
                                style: TextStyle(color: Colors.grey.shade600, fontSize: 12),
                              ),
                              trailing: Row(
                                mainAxisSize: MainAxisSize.min,
                                children: [
                                  Text(
                                    _currency.format(ex.amount),
                                    style: TextStyle(fontWeight: FontWeight.bold, color: Colors.red.shade800, fontSize: 13.5),
                                  ),
                                  IconButton(
                                    icon: const Icon(Icons.delete_outline, color: Colors.grey, size: 18),
                                    onPressed: () async {
                                      await DatabaseHelper.instance.deleteExpense(ex.id);
                                      _loadExpenses();
                                    },
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
        backgroundColor: Colors.red.shade800,
        foregroundColor: Colors.white,
        onPressed: _openAddExpenseModal,
        child: const Icon(Icons.add),
      ),
    );
  }
}
