import 'package:flutter/material.dart';
import 'package:intl/intl.dart';
import '../database/database_helper.dart';

class ReportsScreen extends StatefulWidget {
  const ReportsScreen({super.key});

  @override
  State<ReportsScreen> createState() => _ReportsScreenState();
}

class _ReportsScreenState extends State<ReportsScreen> {
  final currencyFormat = NumberFormat.currency(locale: 'id_ID', symbol: 'Rp ', decimalDigits: 0);

  bool _isLoading = true;
  int _selectedDays = 7;

  Map<String, dynamic> _summary = {};
  List<Map<String, dynamic>> _chartData = [];
  List<Map<String, dynamic>> _topProducts = [];
  List<Map<String, dynamic>> _paymentMethods = [];

  @override
  void initState() {
    super.initState();
    _loadReportData();
  }

  Future<void> _loadReportData() async {
    setState(() => _isLoading = true);

    final summary = await DatabaseHelper.instance.getTodayStats();
    final chart = await DatabaseHelper.instance.getSalesChartData(_selectedDays);
    final top = await DatabaseHelper.instance.getTopSellingProducts(limit: 5);
    final payments = await DatabaseHelper.instance.getPaymentMethodBreakdown();

    setState(() {
      _summary = summary;
      _chartData = chart;
      _topProducts = top;
      _paymentMethods = payments;
      _isLoading = false;
    });
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Laporan & Analitik', style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold)),
        actions: [
          IconButton(
            icon: const Icon(Icons.refresh),
            onPressed: _loadReportData,
            tooltip: 'Segarkan Laporan',
          ),
        ],
      ),
      body: _isLoading
          ? const Center(child: CircularProgressIndicator())
          : RefreshIndicator(
              onRefresh: _loadReportData,
              child: SingleChildScrollView(
                padding: const EdgeInsets.all(16),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    // Filter Hari (Pill Tab)
                    _buildRangeSelector(),
                    const SizedBox(height: 16),

                    // Financial Cards (Omset, Laba Kotor, Pengeluaran, Laba Bersih)
                    _buildFinancialSummary(),
                    const SizedBox(height: 20),

                    // Visual Bar Chart
                    _buildChartSection(),
                    const SizedBox(height: 20),

                    // Top 5 Produk Terlaris
                    _buildTopProductsSection(),
                    const SizedBox(height: 20),

                    // Metode Pembayaran Breakdown
                    _buildPaymentMethodsSection(),
                    const SizedBox(height: 24),
                  ],
                ),
              ),
            ),
    );
  }

  Widget _buildRangeSelector() {
    final ranges = [
      {'label': '7 Hari', 'days': 7},
      {'label': '14 Hari', 'days': 14},
      {'label': '30 Hari', 'days': 30},
    ];

    return Row(
      children: ranges.map((r) {
        final isSelected = _selectedDays == r['days'];
        return Padding(
          padding: const EdgeInsets.only(right: 8),
          child: ChoiceChip(
            label: Text(r['label'] as String),
            selected: isSelected,
            selectedColor: const Color(0xFF059669),
            labelStyle: TextStyle(
              color: isSelected ? Colors.white : const Color(0xFF0F172A),
              fontWeight: isSelected ? FontWeight.bold : FontWeight.normal,
              fontSize: 12,
            ),
            backgroundColor: Colors.white,
            shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(8)),
            side: BorderSide(color: isSelected ? const Color(0xFF059669) : const Color(0xFFE2E8F0)),
            onSelected: (val) {
              if (val) {
                setState(() => _selectedDays = r['days'] as int);
                _loadReportData();
              }
            },
          ),
        );
      }).toList(),
    );
  }

  Widget _buildFinancialSummary() {
    final revenue = _summary['total_revenue'] ?? 0.0;
    final grossProfit = _summary['gross_profit'] ?? 0.0;
    final expense = _summary['total_expense'] ?? 0.0;
    final netProfit = _summary['net_profit'] ?? 0.0;

    return Column(
      children: [
        Row(
          children: [
            _buildMetricCard(
              title: 'Total Omset Hari Ini',
              amount: currencyFormat.format(revenue),
              icon: Icons.trending_up,
              color: const Color(0xFF059669),
              subtext: '${_summary['total_transactions'] ?? 0} Transaksi Berhasil',
            ),
            const SizedBox(width: 12),
            _buildMetricCard(
              title: 'Laba Kotor (Gross)',
              amount: currencyFormat.format(grossProfit),
              icon: Icons.monetization_on_outlined,
              color: const Color(0xFF0284C7),
              subtext: 'Margin Penjualan',
            ),
          ],
        ),
        const SizedBox(height: 12),
        Row(
          children: [
            _buildMetricCard(
              title: 'Biaya Pengeluaran',
              amount: currencyFormat.format(expense),
              icon: Icons.receipt_long_outlined,
              color: const Color(0xFFDC2626),
              subtext: 'Beban Operasional',
            ),
            const SizedBox(width: 12),
            _buildMetricCard(
              title: 'Laba Bersih (Net)',
              amount: currencyFormat.format(netProfit),
              icon: Icons.account_balance_wallet_outlined,
              color: netProfit >= 0 ? const Color(0xFF10B981) : Colors.red,
              subtext: 'Laba Kotor - Beban',
            ),
          ],
        ),
      ],
    );
  }

  Widget _buildMetricCard({
    required String title,
    required String amount,
    required IconData icon,
    required Color color,
    required String subtext,
  }) {
    return Expanded(
      child: Container(
        padding: const EdgeInsets.all(14),
        decoration: BoxDecoration(
          color: Colors.white,
          borderRadius: BorderRadius.circular(12),
          border: Border.all(color: const Color(0xFFE2E8F0)),
          boxShadow: [
            BoxShadow(
              color: Colors.black.withOpacity(0.02),
              blurRadius: 4,
              offset: const Offset(0, 2),
            ),
          ],
        ),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Row(
              mainAxisAlignment: MainAxisAlignment.spaceBetween,
              children: [
                Expanded(
                  child: Text(
                    title,
                    style: const TextStyle(fontSize: 11, fontWeight: FontWeight.w600, color: Color(0xFF64748B)),
                    maxLines: 1,
                    overflow: TextOverflow.ellipsis,
                  ),
                ),
                Container(
                  padding: const EdgeInsets.all(6),
                  decoration: BoxDecoration(
                    color: color.withOpacity(0.1),
                    shape: BoxShape.circle,
                  ),
                  child: Icon(icon, size: 16, color: color),
                ),
              ],
            ),
            const SizedBox(height: 8),
            Text(
              amount,
              style: TextStyle(
                fontSize: 15,
                fontWeight: FontWeight.w800,
                color: const Color(0xFF0F172A),
              ),
              maxLines: 1,
              overflow: TextOverflow.ellipsis,
            ),
            const SizedBox(height: 4),
            Text(
              subtext,
              style: const TextStyle(fontSize: 10, color: Color(0xFF94A3B8)),
              maxLines: 1,
              overflow: TextOverflow.ellipsis,
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildChartSection() {
    return Container(
      padding: const EdgeInsets.all(16),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(12),
        border: Border.all(color: const Color(0xFFE2E8F0)),
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              const Text('Tren Penjualan Harian', style: TextStyle(fontSize: 15, fontWeight: FontWeight.bold, color: Color(0xFF0F172A))),
              Text('$_selectedDays Hari', style: const TextStyle(fontSize: 12, color: Color(0xFF64748B))),
            ],
          ),
          const SizedBox(height: 20),
          if (_chartData.isEmpty)
            const Center(
              child: Padding(
                padding: EdgeInsets.symmetric(vertical: 32),
                child: Text('Belum ada transaksi pada periode ini', style: TextStyle(color: Colors.black54, fontSize: 13)),
              ),
            )
          else
            _buildCustomBarChart(),
        ],
      ),
    );
  }

  Widget _buildCustomBarChart() {
    double maxRevenue = 1.0;
    for (var d in _chartData) {
      if ((d['revenue'] as double) > maxRevenue) {
        maxRevenue = d['revenue'] as double;
      }
    }

    return SizedBox(
      height: 180,
      child: Row(
        crossAxisAlignment: CrossAxisAlignment.end,
        children: _chartData.map((d) {
          final rev = d['revenue'] as double;
          final dateStr = d['date'] as String;
          final ratio = (rev / maxRevenue).clamp(0.05, 1.0);
          final shortDate = dateStr.length >= 5 ? dateStr.substring(5) : dateStr;

          return Expanded(
            child: Padding(
              padding: const EdgeInsets.symmetric(horizontal: 4),
              child: Column(
                mainAxisAlignment: MainAxisAlignment.end,
                children: [
                  if (rev > 0)
                    Text(
                      '${(rev / 1000).toStringAsFixed(0)}k',
                      style: const TextStyle(fontSize: 9, fontWeight: FontWeight.bold, color: Color(0xFF059669)),
                      maxLines: 1,
                    ),
                  const SizedBox(height: 4),
                  Container(
                    height: 120 * ratio,
                    decoration: BoxDecoration(
                      gradient: LinearGradient(
                        colors: [
                          const Color(0xFF059669),
                          const Color(0xFF34D399),
                        ],
                        begin: Alignment.bottomCenter,
                        end: Alignment.topCenter,
                      ),
                      borderRadius: BorderRadius.circular(6),
                    ),
                  ),
                  const SizedBox(height: 8),
                  Text(
                    shortDate,
                    style: const TextStyle(fontSize: 10, color: Color(0xFF64748B), fontWeight: FontWeight.w500),
                  ),
                ],
              ),
            ),
          );
        }).toList(),
      ),
    );
  }

  Widget _buildTopProductsSection() {
    return Container(
      padding: const EdgeInsets.all(16),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(12),
        border: Border.all(color: const Color(0xFFE2E8F0)),
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: const [
              Text('Top 5 Produk Terlaris', style: TextStyle(fontSize: 15, fontWeight: FontWeight.bold, color: Color(0xFF0F172A))),
              Icon(Icons.star_outline, size: 18, color: Color(0xFF059669)),
            ],
          ),
          const SizedBox(height: 14),
          if (_topProducts.isEmpty)
            const Padding(
              padding: EdgeInsets.symmetric(vertical: 16),
              child: Center(child: Text('Belum ada data penjualan produk', style: TextStyle(color: Colors.black54, fontSize: 13))),
            )
          else ...[
            Builder(
              builder: (context) {
                final maxQty = _topProducts.first['qty'] as int;
                return Column(
                  children: _topProducts.asMap().entries.map((entry) {
                    final idx = entry.key;
                    final item = entry.value;
                    final qty = item['qty'] as int;
                    final rev = item['revenue'] as double;
                    final progress = maxQty > 0 ? (qty / maxQty) : 0.0;

                    return Padding(
                      padding: const EdgeInsets.symmetric(vertical: 8),
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          Row(
                            children: [
                              Container(
                                width: 22,
                                height: 22,
                                alignment: Alignment.center,
                                decoration: BoxDecoration(
                                  color: idx == 0 ? const Color(0xFFFEF3C7) : const Color(0xFFF1F5F9),
                                  shape: BoxShape.circle,
                                ),
                                child: Text(
                                  '${idx + 1}',
                                  style: TextStyle(
                                    fontSize: 11,
                                    fontWeight: FontWeight.bold,
                                    color: idx == 0 ? Colors.amber.shade900 : const Color(0xFF475569),
                                  ),
                                ),
                              ),
                              const SizedBox(width: 8),
                              Expanded(
                                child: Text(
                                  item['name'] as String,
                                  style: const TextStyle(fontWeight: FontWeight.w600, fontSize: 13),
                                  maxLines: 1,
                                  overflow: TextOverflow.ellipsis,
                                ),
                              ),
                              Text(
                                '$qty Terjual',
                                style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 12, color: Color(0xFF059669)),
                              ),
                              const SizedBox(width: 10),
                              Text(
                                currencyFormat.format(rev),
                                style: const TextStyle(fontSize: 12, color: Color(0xFF64748B)),
                              ),
                            ],
                          ),
                          const SizedBox(height: 6),
                          ClipRRect(
                            borderRadius: BorderRadius.circular(4),
                            child: LinearProgressIndicator(
                              value: progress,
                              backgroundColor: const Color(0xFFF1F5F9),
                              valueColor: AlwaysStoppedAnimation<Color>(
                                idx == 0 ? const Color(0xFF059669) : const Color(0xFF10B981).withOpacity(0.7),
                              ),
                              minHeight: 6,
                            ),
                          ),
                        ],
                      ),
                    );
                  }).toList(),
                );
              },
            ),
          ],
        ],
      ),
    );
  }

  Widget _buildPaymentMethodsSection() {
    double totalAllPayments = 0.0;
    for (var p in _paymentMethods) {
      totalAllPayments += p['amount'] as double;
    }

    return Container(
      padding: const EdgeInsets.all(16),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(12),
        border: Border.all(color: const Color(0xFFE2E8F0)),
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: const [
              Text('Metode Pembayaran', style: TextStyle(fontSize: 15, fontWeight: FontWeight.bold, color: Color(0xFF0F172A))),
              Icon(Icons.payment_outlined, size: 18, color: Color(0xFF059669)),
            ],
          ),
          const SizedBox(height: 14),
          if (_paymentMethods.isEmpty)
            const Padding(
              padding: EdgeInsets.symmetric(vertical: 16),
              child: Center(child: Text('Belum ada data pembayaran', style: TextStyle(color: Colors.black54, fontSize: 13))),
            )
          else ...[
            Column(
              children: _paymentMethods.map((pm) {
                final method = pm['method'] as String;
                final amount = pm['amount'] as double;
                final count = pm['count'] as int;
                final percent = totalAllPayments > 0 ? (amount / totalAllPayments * 100) : 0.0;

                IconData mIcon = Icons.payments_outlined;
                Color mColor = const Color(0xFF059669);
                if (method.toUpperCase().contains('QRIS')) {
                  mIcon = Icons.qr_code_2;
                  mColor = const Color(0xFF0284C7);
                } else if (method.toUpperCase().contains('TRANSFER')) {
                  mIcon = Icons.account_balance;
                  mColor = const Color(0xFF7C3AED);
                } else if (method.toUpperCase().contains('DEBIT') || method.toUpperCase().contains('KARTU')) {
                  mIcon = Icons.credit_card;
                  mColor = const Color(0xFFD97706);
                }

                return Padding(
                  padding: const EdgeInsets.symmetric(vertical: 6),
                  child: Row(
                    children: [
                      Container(
                        padding: const EdgeInsets.all(8),
                        decoration: BoxDecoration(
                          color: mColor.withOpacity(0.1),
                          shape: BoxShape.circle,
                        ),
                        child: Icon(mIcon, size: 16, color: mColor),
                      ),
                      const SizedBox(width: 10),
                      Expanded(
                        child: Column(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: [
                            Text(method, style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 13)),
                            Text('$count transaksi • ${percent.toStringAsFixed(1)}%', style: const TextStyle(fontSize: 11, color: Color(0xFF64748B))),
                          ],
                        ),
                      ),
                      Text(
                        currencyFormat.format(amount),
                        style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 13, color: Color(0xFF0F172A)),
                      ),
                    ],
                  ),
                );
              }).toList(),
            ),
          ],
        ],
      ),
    );
  }
}
