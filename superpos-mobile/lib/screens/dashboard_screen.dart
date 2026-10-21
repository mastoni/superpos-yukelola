import 'package:flutter/material.dart';
import 'package:intl/intl.dart';
import '../database/database_helper.dart';
import '../models/product_model.dart';
import '../models/store_settings_model.dart';
import '../services/google_sheets_service.dart';
import '../services/settings_service.dart';
import 'backup_sheets_screen.dart';
import 'expenses_screen.dart';
import 'pos_cashier_screen.dart';
import 'products_screen.dart';
import 'transactions_screen.dart';
import 'shift_screen.dart';
import 'reports_screen.dart';

class DashboardScreen extends StatefulWidget {
  const DashboardScreen({super.key});

  @override
  State<DashboardScreen> createState() => _DashboardScreenState();
}

class _DashboardScreenState extends State<DashboardScreen> {
  final _currency = NumberFormat.currency(locale: 'id_ID', symbol: 'Rp ', decimalDigits: 0);

  StoreSettingsModel _store = StoreSettingsModel();
  Map<String, dynamic> _todayStats = {};
  List<ProductModel> _lowStockProducts = [];
  bool _isLoading = true;
  bool _isSyncing = false;

  @override
  void initState() {
    super.initState();
    _loadDashboardData();
  }

  Future<void> _loadDashboardData() async {
    setState(() => _isLoading = true);
    final store = await SettingsService.getSettings();
    final stats = await DatabaseHelper.instance.getTodayStats();
    final lowStock = await DatabaseHelper.instance.getLowStockProducts(threshold: 10);

    setState(() {
      _store = store;
      _todayStats = stats;
      _lowStockProducts = lowStock;
      _isLoading = false;
    });
  }

  Future<void> _quickSyncToSheets() async {
    if (_store.googleScriptUrl.isEmpty) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Atur URL Google Apps Script di menu Backup terlebih dahulu.')),
      );
      Navigator.push(context, MaterialPageRoute(builder: (_) => const BackupSheetsScreen()))
          .then((_) => _loadDashboardData());
      return;
    }

    setState(() => _isSyncing = true);
    final res = await GoogleSheetsService.backupToSheets(_store.googleScriptUrl);
    setState(() => _isSyncing = false);

    if (mounted) {
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(
          backgroundColor: res['success'] == true ? Colors.green.shade700 : Colors.red.shade700,
          content: Text(res['message']),
        ),
      );
      _loadDashboardData();
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: const Color(0xFFF8FAFC),
      appBar: AppBar(
        title: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text(_store.storeName, style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 17)),
            const Text('YUKelola (Offline & Cloud Sync)', style: TextStyle(fontSize: 11, color: Colors.white70)),
          ],
        ),
        backgroundColor: const Color(0xFF0F172A),
        foregroundColor: Colors.white,
        actions: [
          IconButton(
            icon: _isSyncing
                ? const SizedBox(width: 20, height: 20, child: CircularProgressIndicator(color: Colors.white, strokeWidth: 2))
                : const Icon(Icons.sync),
            tooltip: 'Sinkronkan ke Google Sheets',
            onPressed: _isSyncing ? null : _quickSyncToSheets,
          ),
          IconButton(
            icon: const Icon(Icons.settings),
            tooltip: 'Pengaturan & Backup',
            onPressed: () => Navigator.push(
              context,
              MaterialPageRoute(builder: (_) => const BackupSheetsScreen()),
            ).then((_) => _loadDashboardData()),
          ),
        ],
      ),
      body: _isLoading
          ? const Center(child: CircularProgressIndicator())
          : RefreshIndicator(
              onRefresh: _loadDashboardData,
              child: SingleChildScrollView(
                physics: const AlwaysScrollableScrollPhysics(),
                padding: const EdgeInsets.all(16),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    // Cloud Backup Banner
                    Container(
                      padding: const EdgeInsets.symmetric(horizontal: 14, vertical: 10),
                      decoration: BoxDecoration(
                        color: Colors.white,
                        borderRadius: BorderRadius.circular(12),
                        border: Border.all(color: Colors.blue.shade100),
                        boxShadow: [
                          BoxShadow(color: Colors.black.withValues(alpha: 0.03), blurRadius: 6, offset: const Offset(0, 2)),
                        ],
                      ),
                      child: Row(
                        children: [
                          Container(
                            padding: const EdgeInsets.all(8),
                            decoration: BoxDecoration(
                              color: Colors.green.shade50,
                              shape: BoxShape.circle,
                            ),
                            child: Icon(Icons.cloud_done, color: Colors.green.shade700, size: 22),
                          ),
                          const SizedBox(width: 12),
                          Expanded(
                            child: Column(
                              crossAxisAlignment: CrossAxisAlignment.start,
                              children: [
                                const Text('Google Sheets Backup', style: TextStyle(fontWeight: FontWeight.bold, fontSize: 13)),
                                Text('Terakhir: ${_store.lastSyncAt}', style: TextStyle(color: Colors.grey.shade600, fontSize: 11.5)),
                              ],
                            ),
                          ),
                          TextButton(
                            style: TextButton.styleFrom(
                              foregroundColor: const Color(0xFF1E3A8A),
                              padding: const EdgeInsets.symmetric(horizontal: 10),
                            ),
                            onPressed: _isSyncing ? null : _quickSyncToSheets,
                            child: const Text('Sinkron', style: TextStyle(fontWeight: FontWeight.bold, fontSize: 12)),
                          ),
                        ],
                      ),
                    ),
                    const SizedBox(height: 16),

                    // Ringkasan Hari Ini
                    const Text('Ringkasan Hari Ini', style: TextStyle(fontSize: 16, fontWeight: FontWeight.bold)),
                    const SizedBox(height: 10),

                    Row(
                      children: [
                        Expanded(
                          child: _buildMetricCard(
                            title: 'Omset Penjualan',
                            value: _currency.format(_todayStats['total_revenue'] ?? 0),
                            icon: Icons.payments,
                            iconColor: Colors.blue.shade800,
                            bgColor: Colors.blue.shade50,
                          ),
                        ),
                        const SizedBox(width: 12),
                        Expanded(
                          child: _buildMetricCard(
                            title: 'Jumlah Transaksi',
                            value: '${_todayStats['total_transactions'] ?? 0} Struk',
                            icon: Icons.receipt_long,
                            iconColor: Colors.purple.shade700,
                            bgColor: Colors.purple.shade50,
                          ),
                        ),
                      ],
                    ),
                    const SizedBox(height: 12),

                    Row(
                      children: [
                        Expanded(
                          child: _buildMetricCard(
                            title: 'Perkiraan Laba Kotor',
                            value: _currency.format(_todayStats['gross_profit'] ?? 0),
                            icon: Icons.trending_up,
                            iconColor: Colors.green.shade700,
                            bgColor: Colors.green.shade50,
                          ),
                        ),
                        const SizedBox(width: 12),
                        Expanded(
                          child: _buildMetricCard(
                            title: 'Pengeluaran',
                            value: _currency.format(_todayStats['total_expense'] ?? 0),
                            icon: Icons.money_off,
                            iconColor: Colors.red.shade700,
                            bgColor: Colors.red.shade50,
                          ),
                        ),
                      ],
                    ),

                    const SizedBox(height: 24),

                    // Menu Cepat YUKelola
                    const Text('Menu Utama', style: TextStyle(fontSize: 16, fontWeight: FontWeight.bold)),
                    const SizedBox(height: 12),

                    GridView.count(
                      crossAxisCount: 2,
                      shrinkWrap: true,
                      physics: const NeverScrollableScrollPhysics(),
                      crossAxisSpacing: 12,
                      mainAxisSpacing: 12,
                      childAspectRatio: 1.35,
                      children: [
                        _buildMenuCard(
                          title: 'Buka Kasir POS',
                          desc: 'Transaksi penjualan & cetak struk',
                          icon: Icons.point_of_sale,
                          color: const Color(0xFF059669),
                          onTap: () => Navigator.push(
                            context,
                            MaterialPageRoute(builder: (_) => const PosCashierScreen()),
                          ).then((_) => _loadDashboardData()),
                        ),
                        _buildMenuCard(
                          title: 'Shift & Kas Laci',
                          desc: 'Manajemen kasir & rekonsiliasi',
                          icon: Icons.account_balance_wallet,
                          color: const Color(0xFF0F172A),
                          onTap: () => Navigator.push(
                            context,
                            MaterialPageRoute(builder: (_) => const ShiftScreen()),
                          ).then((_) => _loadDashboardData()),
                        ),
                        _buildMenuCard(
                          title: 'Katalog & Stok',
                          desc: 'Kelola produk, harga & barcode',
                          icon: Icons.inventory_2,
                          color: Colors.teal.shade700,
                          onTap: () => Navigator.push(
                            context,
                            MaterialPageRoute(builder: (_) => const ProductsScreen()),
                          ).then((_) => _loadDashboardData()),
                        ),
                        _buildMenuCard(
                          title: 'Laporan & Analitik',
                          desc: 'Grafik omset harian & produk laris',
                          icon: Icons.bar_chart,
                          color: Colors.blue.shade700,
                          onTap: () => Navigator.push(
                            context,
                            MaterialPageRoute(builder: (_) => const ReportsScreen()),
                          ).then((_) => _loadDashboardData()),
                        ),
                        _buildMenuCard(
                          title: 'Riwayat Transaksi',
                          desc: 'Cetak ulang struk & batalkan nota',
                          icon: Icons.receipt_long,
                          color: Colors.indigo.shade700,
                          onTap: () => Navigator.push(
                            context,
                            MaterialPageRoute(builder: (_) => const TransactionsScreen()),
                          ).then((_) => _loadDashboardData()),
                        ),
                        _buildMenuCard(
                          title: 'Pengeluaran Kas',
                          desc: 'Catat biaya operasional toko',
                          icon: Icons.money_off,
                          color: Colors.orange.shade800,
                          onTap: () => Navigator.push(
                            context,
                            MaterialPageRoute(builder: (_) => const ExpensesScreen()),
                          ).then((_) => _loadDashboardData()),
                        ),
                      ],
                    ),

                    const SizedBox(height: 24),

                    // Low Stock Alert
                    if (_lowStockProducts.isNotEmpty) ...[
                      Row(
                        mainAxisAlignment: MainAxisAlignment.spaceBetween,
                        children: [
                          Row(
                            children: [
                              const Icon(Icons.warning_amber_rounded, color: Colors.amber, size: 22),
                              const SizedBox(width: 6),
                              Text('Peringatan Stok Menipis (${_lowStockProducts.length})', style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 15)),
                            ],
                          ),
                          TextButton(
                            onPressed: () => Navigator.push(
                              context,
                              MaterialPageRoute(builder: (_) => const ProductsScreen()),
                            ).then((_) => _loadDashboardData()),
                            child: const Text('Kelola'),
                          ),
                        ],
                      ),
                      const SizedBox(height: 6),
                      ListView.separated(
                        shrinkWrap: true,
                        physics: const NeverScrollableScrollPhysics(),
                        itemCount: _lowStockProducts.take(4).length,
                        separatorBuilder: (_, _) => const SizedBox(height: 6),
                        itemBuilder: (ctx, index) {
                          final p = _lowStockProducts[index];
                          return Container(
                            padding: const EdgeInsets.symmetric(horizontal: 14, vertical: 10),
                            decoration: BoxDecoration(
                              color: Colors.white,
                              borderRadius: BorderRadius.circular(10),
                              border: Border.all(color: Colors.amber.shade200),
                            ),
                            child: Row(
                              mainAxisAlignment: MainAxisAlignment.spaceBetween,
                              children: [
                                Expanded(
                                  child: Column(
                                    crossAxisAlignment: CrossAxisAlignment.start,
                                    children: [
                                      Text(p.name, style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 13)),
                                      Text('${p.category} • Barcode: ${p.sku}', style: TextStyle(color: Colors.grey.shade600, fontSize: 11)),
                                    ],
                                  ),
                                ),
                                Container(
                                  padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 4),
                                  decoration: BoxDecoration(
                                    color: Colors.red.shade50,
                                    borderRadius: BorderRadius.circular(6),
                                  ),
                                  child: Text(
                                    'Sisa: ${p.stock} ${p.unit}',
                                    style: TextStyle(fontWeight: FontWeight.bold, color: Colors.red.shade800, fontSize: 12),
                                  ),
                                ),
                              ],
                            ),
                          );
                        },
                      ),
                    ],

                    const SizedBox(height: 30),
                  ],
                ),
              ),
            ),
    );
  }

  Widget _buildMetricCard({
    required String title,
    required String value,
    required IconData icon,
    required Color iconColor,
    required Color bgColor,
  }) {
    return Container(
      padding: const EdgeInsets.all(14),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(12),
        boxShadow: [
          BoxShadow(color: Colors.black.withValues(alpha: 0.03), blurRadius: 6, offset: const Offset(0, 2)),
        ],
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              Text(title, style: TextStyle(fontSize: 11.5, color: Colors.grey.shade700, fontWeight: FontWeight.w500)),
              Container(
                padding: const EdgeInsets.all(6),
                decoration: BoxDecoration(color: bgColor, shape: BoxShape.circle),
                child: Icon(icon, color: iconColor, size: 18),
              ),
            ],
          ),
          const SizedBox(height: 6),
          Text(value, style: const TextStyle(fontSize: 15, fontWeight: FontWeight.bold)),
        ],
      ),
    );
  }

  Widget _buildMenuCard({
    required String title,
    required String desc,
    required IconData icon,
    required Color color,
    required VoidCallback onTap,
  }) {
    return InkWell(
      onTap: onTap,
      borderRadius: BorderRadius.circular(12),
      child: Container(
        padding: const EdgeInsets.all(14),
        decoration: BoxDecoration(
          color: Colors.white,
          borderRadius: BorderRadius.circular(12),
          border: Border.all(color: Colors.grey.shade200),
          boxShadow: [
            BoxShadow(color: Colors.black.withValues(alpha: 0.03), blurRadius: 6, offset: const Offset(0, 2)),
          ],
        ),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Container(
              padding: const EdgeInsets.all(8),
              decoration: BoxDecoration(
                color: color.withValues(alpha: 0.12),
                borderRadius: BorderRadius.circular(8),
              ),
              child: Icon(icon, color: color, size: 24),
            ),
            const Spacer(),
            Text(title, style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 13.5)),
            const SizedBox(height: 2),
            Text(
              desc,
              maxLines: 1,
              overflow: TextOverflow.ellipsis,
              style: TextStyle(fontSize: 10.5, color: Colors.grey.shade600),
            ),
          ],
        ),
      ),
    );
  }
}
