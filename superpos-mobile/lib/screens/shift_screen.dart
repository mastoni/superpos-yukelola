import 'package:flutter/material.dart';
import 'package:intl/intl.dart';
import '../database/database_helper.dart';
import '../models/shift_model.dart';
import '../models/cash_movement_model.dart';

class ShiftScreen extends StatefulWidget {
  const ShiftScreen({super.key});

  @override
  State<ShiftScreen> createState() => _ShiftScreenState();
}

class _ShiftScreenState extends State<ShiftScreen> {
  final currencyFormat = NumberFormat.currency(locale: 'id_ID', symbol: 'Rp ', decimalDigits: 0);
  final dateFormat = DateFormat('dd MMM yyyy, HH:mm');

  ShiftModel? _activeShift;
  List<CashMovementModel> _movements = [];
  List<ShiftModel> _pastShifts = [];
  bool _isLoading = true;

  @override
  void initState() {
    super.initState();
    _loadShiftData();
  }

  Future<void> _loadShiftData() async {
    setState(() => _isLoading = true);
    final active = await DatabaseHelper.instance.getActiveShift();
    List<CashMovementModel> movements = [];
    if (active != null) {
      movements = await DatabaseHelper.instance.getCashMovements(active.id);
    }
    final past = await DatabaseHelper.instance.getRecentShifts(limit: 5);

    setState(() {
      _activeShift = active;
      _movements = movements;
      _pastShifts = past.where((s) => s.status == 'CLOSED').toList();
      _isLoading = false;
    });
  }

  // Buka Shift Baru
  void _showOpenShiftDialog() {
    final cashierController = TextEditingController(text: 'Kasir 1');
    final cashController = TextEditingController(text: '100000');

    showDialog(
      context: context,
      builder: (ctx) => AlertDialog(
        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(16)),
        title: Row(
          children: const [
            Icon(Icons.storefront, color: Color(0xFF059669)),
            SizedBox(width: 8),
            Text('Buka Shift Kasir Baru', style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold)),
          ],
        ),
        content: Column(
          mainAxisSize: MainAxisSize.min,
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            const Text('Masukkan nama kasir dan modal uang tunai awal di dalam laci uang (cash drawer):', style: TextStyle(fontSize: 13, color: Colors.black54)),
            const SizedBox(height: 16),
            TextField(
              controller: cashierController,
              decoration: InputDecoration(
                labelText: 'Nama Kasir',
                prefixIcon: const Icon(Icons.person_outline),
                border: OutlineInputBorder(borderRadius: BorderRadius.circular(10)),
              ),
            ),
            const SizedBox(height: 12),
            TextField(
              controller: cashController,
              keyboardType: TextInputType.number,
              decoration: InputDecoration(
                labelText: 'Modal Awal (Kas Laci)',
                prefixText: 'Rp ',
                prefixIcon: const Icon(Icons.account_balance_wallet_outlined),
                border: OutlineInputBorder(borderRadius: BorderRadius.circular(10)),
              ),
            ),
          ],
        ),
        actions: [
          TextButton(
            onPressed: () => Navigator.pop(ctx),
            child: const Text('Batal'),
          ),
          ElevatedButton(
            style: ElevatedButton.styleFrom(
              backgroundColor: const Color(0xFF059669),
              foregroundColor: Colors.white,
              shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(8)),
            ),
            onPressed: () async {
              final name = cashierController.text.trim();
              final cash = double.tryParse(cashController.text.replaceAll(RegExp(r'[^0-9]'), '')) ?? 0.0;
              Navigator.pop(ctx);
              await DatabaseHelper.instance.openShift(name, cash);
              _loadShiftData();
              if (mounted) {
                ScaffoldMessenger.of(context).showSnackBar(
                  const SnackBar(content: Text('Shift kasir berhasil dibuka!')),
                );
              }
            },
            child: const Text('Buka Shift'),
          ),
        ],
      ),
    );
  }

  // Kas Masuk / Keluar (Petty Cash)
  void _showCashMovementDialog(String type) {
    if (_activeShift == null) return;
    final isIncome = type == 'IN';
    final amountController = TextEditingController();
    final reasonController = TextEditingController();

    showDialog(
      context: context,
      builder: (ctx) => AlertDialog(
        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(16)),
        title: Row(
          children: [
            Icon(isIncome ? Icons.arrow_downward : Icons.arrow_upward, color: isIncome ? const Color(0xFF059669) : const Color(0xFFE11D48)),
            const SizedBox(width: 8),
            Text(isIncome ? 'Kas Masuk (Tambah Kas)' : 'Kas Keluar (Ambil Kas)', style: const TextStyle(fontSize: 18, fontWeight: FontWeight.bold)),
          ],
        ),
        content: Column(
          mainAxisSize: MainAxisSize.min,
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text(
              isIncome 
                ? 'Tambahkan uang tunai ke dalam laci (misal: tambah modal receh kembalian).'
                : 'Catat pengeluaran tunai langsung dari laci kasir (misal: beli lakban, bensin kurir, galon).',
              style: const TextStyle(fontSize: 13, color: Colors.black54),
            ),
            const SizedBox(height: 16),
            TextField(
              controller: amountController,
              keyboardType: TextInputType.number,
              autofocus: true,
              decoration: InputDecoration(
                labelText: 'Jumlah Uang',
                prefixText: 'Rp ',
                border: OutlineInputBorder(borderRadius: BorderRadius.circular(10)),
              ),
            ),
            const SizedBox(height: 12),
            TextField(
              controller: reasonController,
              decoration: InputDecoration(
                labelText: 'Keterangan / Alasan',
                hintText: isIncome ? 'Contoh: Tambah modal receh' : 'Contoh: Beli es batu / kantong kresek',
                border: OutlineInputBorder(borderRadius: BorderRadius.circular(10)),
              ),
            ),
          ],
        ),
        actions: [
          TextButton(
            onPressed: () => Navigator.pop(ctx),
            child: const Text('Batal'),
          ),
          ElevatedButton(
            style: ElevatedButton.styleFrom(
              backgroundColor: isIncome ? const Color(0xFF059669) : Colors.red[700],
              foregroundColor: Colors.white,
              shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(8)),
            ),
            onPressed: () async {
              final amount = double.tryParse(amountController.text.replaceAll(RegExp(r'[^0-9]'), '')) ?? 0.0;
              final reason = reasonController.text.trim();
              if (amount <= 0 || reason.isEmpty) return;

              Navigator.pop(ctx);
              await DatabaseHelper.instance.addCashMovement(_activeShift!.id, type, amount, reason);
              _loadShiftData();
              if (mounted) {
                ScaffoldMessenger.of(context).showSnackBar(
                  SnackBar(content: Text('Berhasil mencatat ${isIncome ? 'Kas Masuk' : 'Kas Keluar'}!')),
                );
              }
            },
            child: const Text('Simpan'),
          ),
        ],
      ),
    );
  }

  // Tutup Shift (Reconciliation)
  void _showCloseShiftDialog() {
    if (_activeShift == null) return;
    final countedCashController = TextEditingController(text: _activeShift!.expectedCash.toStringAsFixed(0));
    final noteController = TextEditingController();

    showDialog(
      context: context,
      builder: (ctx) => StatefulBuilder(
        builder: (context, setDialogState) {
          final counted = double.tryParse(countedCashController.text.replaceAll(RegExp(r'[^0-9]'), '')) ?? 0.0;
          final diff = counted - _activeShift!.expectedCash;

          return AlertDialog(
            shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(16)),
            title: Row(
              children: const [
                Icon(Icons.lock_clock, color: Color(0xFF0F172A)),
                SizedBox(width: 8),
                Text('Tutup Shift Kasir', style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold)),
              ],
            ),
            content: SingleChildScrollView(
              child: Column(
                mainAxisSize: MainAxisSize.min,
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Container(
                    padding: const EdgeInsets.all(12),
                    decoration: BoxDecoration(
                      color: const Color(0xFFF1F5F9),
                      borderRadius: BorderRadius.circular(10),
                    ),
                    child: Column(
                      children: [
                        _buildDialogRow('Kas Awal:', currencyFormat.format(_activeShift!.startingCash)),
                        _buildDialogRow('Penjualan Tunai (+):', currencyFormat.format(_activeShift!.totalCashSales)),
                        _buildDialogRow('Kas Masuk (+):', currencyFormat.format(_activeShift!.totalCashIn)),
                        _buildDialogRow('Kas Keluar (-):', currencyFormat.format(_activeShift!.totalCashOut)),
                        const Divider(height: 16),
                        _buildDialogRow(
                          'Estimasi Kas di Laci:',
                          currencyFormat.format(_activeShift!.expectedCash),
                          isBold: true,
                          color: const Color(0xFF059669),
                        ),
                      ],
                    ),
                  ),
                  const SizedBox(height: 16),
                  const Text('Hitung Uang Fisik di Laci:', style: TextStyle(fontWeight: FontWeight.bold)),
                  const SizedBox(height: 6),
                  TextField(
                    controller: countedCashController,
                    keyboardType: TextInputType.number,
                    onChanged: (_) => setDialogState(() {}),
                    decoration: InputDecoration(
                      prefixText: 'Rp ',
                      border: OutlineInputBorder(borderRadius: BorderRadius.circular(10)),
                    ),
                  ),
                  const SizedBox(height: 10),
                  // Indikator Selisih
                  Container(
                    padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 8),
                    decoration: BoxDecoration(
                      color: diff == 0 ? Colors.green.shade50 : (diff < 0 ? Colors.red.shade50 : Colors.blue.shade50),
                      borderRadius: BorderRadius.circular(8),
                      border: Border.all(color: diff == 0 ? Colors.green.shade200 : (diff < 0 ? Colors.red.shade200 : Colors.blue.shade200)),
                    ),
                    child: Row(
                      mainAxisAlignment: MainAxisAlignment.spaceBetween,
                      children: [
                        Text(
                          diff == 0 ? 'Kas Pas (Tepat)' : (diff < 0 ? 'Selisih Kurang' : 'Selisih Lebih'),
                          style: TextStyle(
                            fontWeight: FontWeight.bold,
                            color: diff == 0 ? Colors.green.shade800 : (diff < 0 ? Colors.red.shade800 : Colors.blue.shade800),
                          ),
                        ),
                        Text(
                          currencyFormat.format(diff),
                          style: TextStyle(
                            fontWeight: FontWeight.bold,
                            color: diff == 0 ? Colors.green.shade800 : (diff < 0 ? Colors.red.shade800 : Colors.blue.shade800),
                          ),
                        ),
                      ],
                    ),
                  ),
                  const SizedBox(height: 12),
                  TextField(
                    controller: noteController,
                    decoration: InputDecoration(
                      labelText: 'Catatan Penutupan',
                      hintText: 'Opsional: keterangan selisih kas...',
                      border: OutlineInputBorder(borderRadius: BorderRadius.circular(10)),
                    ),
                  ),
                ],
              ),
            ),
            actions: [
              TextButton(
                onPressed: () => Navigator.pop(ctx),
                child: const Text('Batal'),
              ),
              ElevatedButton(
                style: ElevatedButton.styleFrom(
                  backgroundColor: const Color(0xFF0F172A),
                  foregroundColor: Colors.white,
                  shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(8)),
                ),
                onPressed: () async {
                  Navigator.pop(ctx);
                  await DatabaseHelper.instance.closeShift(
                    _activeShift!.id,
                    counted,
                    noteController.text.trim(),
                  );
                  _loadShiftData();
                  if (mounted) {
                    ScaffoldMessenger.of(context).showSnackBar(
                      const SnackBar(content: Text('Shift kasir berhasil ditutup!')),
                    );
                  }
                },
                child: const Text('Tutup & Finalisasi'),
              ),
            ],
          );
        },
      ),
    );
  }

  Widget _buildDialogRow(String label, String value, {bool isBold = false, Color? color}) {
    return Padding(
      padding: const EdgeInsets.symmetric(vertical: 2),
      child: Row(
        mainAxisAlignment: MainAxisAlignment.spaceBetween,
        children: [
          Text(label, style: TextStyle(fontSize: 13, fontWeight: isBold ? FontWeight.bold : FontWeight.normal)),
          Text(value, style: TextStyle(fontSize: 13, fontWeight: isBold ? FontWeight.bold : FontWeight.normal, color: color)),
        ],
      ),
    );
  }

  @override
  Widget build(BuildContext context) {
    if (_isLoading) {
      return const Scaffold(body: Center(child: CircularProgressIndicator()));
    }

    return Scaffold(
      appBar: AppBar(
        title: const Text('Manajemen Shift & Kas Kasir', style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold)),
        actions: [
          IconButton(
            icon: const Icon(Icons.refresh),
            onPressed: _loadShiftData,
            tooltip: 'Segarkan Data',
          ),
        ],
      ),
      body: SingleChildScrollView(
        padding: const EdgeInsets.all(16),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            // Status Banner
            _buildActiveShiftCard(),
            const SizedBox(height: 20),

            // Riwayat Kas Masuk/Keluar Selama Shift
            if (_activeShift != null) ...[
              Row(
                mainAxisAlignment: MainAxisAlignment.spaceBetween,
                children: [
                  const Text('Aktivitas Kas Laci (Petty Cash)', style: TextStyle(fontSize: 16, fontWeight: FontWeight.bold, color: Color(0xFF0F172A))),
                  Text('${_movements.length} Transaksi', style: const TextStyle(fontSize: 12, color: Colors.black54)),
                ],
              ),
              const SizedBox(height: 10),
              _buildCashMovementsList(),
              const SizedBox(height: 24),
            ],

            // Riwayat Shift Terdahulu
            const Text('Riwayat Shift Sebelumnya', style: TextStyle(fontSize: 16, fontWeight: FontWeight.bold, color: Color(0xFF0F172A))),
            const SizedBox(height: 10),
            _buildPastShiftsList(),
          ],
        ),
      ),
    );
  }

  Widget _buildActiveShiftCard() {
    if (_activeShift == null) {
      return Card(
        color: Colors.amber.shade50,
        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(14), side: BorderSide(color: Colors.amber.shade300)),
        child: Padding(
          padding: const EdgeInsets.all(20),
          child: Column(
            children: [
              Icon(Icons.storefront_outlined, size: 48, color: Colors.amber.shade800),
              const SizedBox(height: 12),
              const Text('Tidak Ada Shift yang Aktif', style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold)),
              const SizedBox(height: 6),
              const Text('Buka shift kasir terlebih dahulu untuk mencatat modal laci awal dan merekam penjualan.', textAlign: TextAlign.center, style: TextStyle(fontSize: 13, color: Colors.black54)),
              const SizedBox(height: 16),
              ElevatedButton.icon(
                style: ElevatedButton.styleFrom(
                  backgroundColor: const Color(0xFF059669),
                  foregroundColor: Colors.white,
                  padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 12),
                  shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(10)),
                ),
                onPressed: _showOpenShiftDialog,
                icon: const Icon(Icons.add),
                label: const Text('Buka Shift Sekarang', style: TextStyle(fontWeight: FontWeight.bold)),
              ),
            ],
          ),
        ),
      );
    }

    final s = _activeShift!;
    DateTime? startTime;
    try {
      startTime = DateTime.parse(s.startTime);
    } catch (_) {}

    return Card(
      elevation: 2,
      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(14)),
      child: Column(
        children: [
          // Header Card
          Container(
            padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 12),
            decoration: const BoxDecoration(
              color: Color(0xFF0F172A),
              borderRadius: BorderRadius.vertical(top: Radius.circular(14)),
            ),
            child: Row(
              mainAxisAlignment: MainAxisAlignment.spaceBetween,
              children: [
                Row(
                  children: [
                    Container(
                      width: 10,
                      height: 10,
                      decoration: const BoxDecoration(color: Color(0xFF10B981), shape: BoxShape.circle),
                    ),
                    const SizedBox(width: 8),
                    Text('SHIFT AKTIF: ${s.cashierName.toUpperCase()}', style: const TextStyle(color: Colors.white, fontWeight: FontWeight.bold, fontSize: 14)),
                  ],
                ),
                Text(
                  startTime != null ? dateFormat.format(startTime) : '',
                  style: const TextStyle(color: Colors.white70, fontSize: 12),
                ),
              ],
            ),
          ),

          // Total Laci Cash Highlight
          Container(
            padding: const EdgeInsets.all(16),
            color: const Color(0xFFF8FAFC),
            child: Column(
              children: [
                const Text('ESTIMASI TOTAL KAS DI LACI (DRAWER)', style: TextStyle(fontSize: 11, fontWeight: FontWeight.bold, color: Colors.black54, letterSpacing: 0.5)),
                const SizedBox(height: 4),
                Text(
                  currencyFormat.format(s.expectedCash),
                  style: const TextStyle(fontSize: 28, fontWeight: FontWeight.w900, color: Color(0xFF059669)),
                ),
                const SizedBox(height: 14),
                // Grid rincian
                Row(
                  children: [
                    _buildStatItem('Modal Awal', currencyFormat.format(s.startingCash)),
                    _buildStatItem('Penjualan Tunai', currencyFormat.format(s.totalCashSales), color: const Color(0xFF059669)),
                  ],
                ),
                const SizedBox(height: 8),
                Row(
                  children: [
                    _buildStatItem('Kas Masuk (+)', currencyFormat.format(s.totalCashIn), color: Colors.blue[700]),
                    _buildStatItem('Kas Keluar (-)', currencyFormat.format(s.totalCashOut), color: Colors.red[700]),
                  ],
                ),
                const SizedBox(height: 8),
                Row(
                  children: [
                    _buildStatItem('Non-Tunai (QRIS/Trf)', currencyFormat.format(s.totalNonCashSales), color: Colors.indigo[700]),
                    _buildStatItem('Total Penjualan', currencyFormat.format(s.totalCashSales + s.totalNonCashSales), isBold: true),
                  ],
                ),
              ],
            ),
          ),

          // Action Buttons
          Padding(
            padding: const EdgeInsets.all(12),
            child: Row(
              children: [
                Expanded(
                  child: OutlinedButton.icon(
                    style: OutlinedButton.styleFrom(
                      foregroundColor: const Color(0xFF059669),
                      side: const BorderSide(color: Color(0xFF059669)),
                      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(8)),
                    ),
                    onPressed: () => _showCashMovementDialog('IN'),
                    icon: const Icon(Icons.arrow_downward, size: 16),
                    label: const Text('Kas Masuk'),
                  ),
                ),
                const SizedBox(width: 8),
                Expanded(
                  child: OutlinedButton.icon(
                    style: OutlinedButton.styleFrom(
                      foregroundColor: Colors.red[700],
                      side: BorderSide(color: Colors.red.shade300),
                      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(8)),
                    ),
                    onPressed: () => _showCashMovementDialog('OUT'),
                    icon: const Icon(Icons.arrow_upward, size: 16),
                    label: const Text('Kas Keluar'),
                  ),
                ),
                const SizedBox(width: 8),
                Expanded(
                  child: ElevatedButton.icon(
                    style: ElevatedButton.styleFrom(
                      backgroundColor: const Color(0xFF0F172A),
                      foregroundColor: Colors.white,
                      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(8)),
                    ),
                    onPressed: _showCloseShiftDialog,
                    icon: const Icon(Icons.lock_clock, size: 16),
                    label: const Text('Tutup Shift'),
                  ),
                ),
              ],
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildStatItem(String label, String value, {Color? color, bool isBold = false}) {
    return Expanded(
      child: Container(
        padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 8),
        margin: const EdgeInsets.symmetric(horizontal: 4),
        decoration: BoxDecoration(
          color: Colors.white,
          borderRadius: BorderRadius.circular(8),
          border: Border.all(color: const Color(0xFFE2E8F0)),
        ),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text(label, style: const TextStyle(fontSize: 11, color: Colors.black54)),
            const SizedBox(height: 2),
            Text(
              value,
              style: TextStyle(
                fontSize: 13,
                fontWeight: isBold ? FontWeight.bold : FontWeight.w600,
                color: color ?? const Color(0xFF0F172A),
              ),
              maxLines: 1,
              overflow: TextOverflow.ellipsis,
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildCashMovementsList() {
    if (_movements.isEmpty) {
      return Card(
        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(10)),
        child: const Padding(
          padding: EdgeInsets.all(16),
          child: Center(
            child: Text('Belum ada transaksi kas masuk atau keluar pada shift ini.', style: TextStyle(fontSize: 13, color: Colors.black54)),
          ),
        ),
      );
    }

    return Card(
      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(10)),
      child: ListView.separated(
        shrinkWrap: true,
        physics: const NeverScrollableScrollPhysics(),
        itemCount: _movements.length,
        separatorBuilder: (_, __) => const Divider(height: 1),
        itemBuilder: (context, index) {
          final m = _movements[index];
          final isIncome = m.type == 'IN';
          DateTime? date;
          try {
            date = DateTime.parse(m.createdAt);
          } catch (_) {}

          return ListTile(
            dense: true,
            leading: CircleAvatar(
              radius: 16,
              backgroundColor: isIncome ? const Color(0xFF059669).withOpacity(0.15) : const Color(0xFFE11D48).withOpacity(0.15),
              child: Icon(
                isIncome ? Icons.arrow_downward : Icons.arrow_upward,
                size: 16,
                color: isIncome ? const Color(0xFF059669) : const Color(0xFFE11D48),
              ),
            ),
            title: Text(m.reason, style: const TextStyle(fontWeight: FontWeight.w600, fontSize: 13)),
            subtitle: Text(date != null ? DateFormat('HH:mm').format(date) : '', style: const TextStyle(fontSize: 11)),
            trailing: Text(
              '${isIncome ? '+' : '-'} ${currencyFormat.format(m.amount)}',
              style: TextStyle(
                fontWeight: FontWeight.bold,
                fontSize: 13,
                color: isIncome ? const Color(0xFF059669) : const Color(0xFFE11D48),
              ),
            ),
          );
        },
      ),
    );
  }

  Widget _buildPastShiftsList() {
    if (_pastShifts.isEmpty) {
      return Card(
        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(10)),
        child: const Padding(
          padding: EdgeInsets.all(16),
          child: Center(
            child: Text('Belum ada riwayat shift yang ditutup.', style: TextStyle(fontSize: 13, color: Colors.black54)),
          ),
        ),
      );
    }

    return ListView.builder(
      shrinkWrap: true,
      physics: const NeverScrollableScrollPhysics(),
      itemCount: _pastShifts.length,
      itemBuilder: (context, index) {
        final shift = _pastShifts[index];
        DateTime? sTime;
        DateTime? eTime;
        try {
          sTime = DateTime.parse(shift.startTime);
          if (shift.endTime != null) eTime = DateTime.parse(shift.endTime!);
        } catch (_) {}

        final diff = shift.difference ?? 0.0;

        return Card(
          margin: const EdgeInsets.only(bottom: 10),
          shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(10)),
          child: ExpansionTile(
            title: Text('Shift ${shift.cashierName}', style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 14)),
            subtitle: Text(
              '${sTime != null ? dateFormat.format(sTime) : ''} - ${eTime != null ? DateFormat('HH:mm').format(eTime) : ''}',
              style: const TextStyle(fontSize: 12, color: Colors.black54),
            ),
            trailing: Column(
              mainAxisAlignment: MainAxisAlignment.center,
              crossAxisAlignment: CrossAxisAlignment.end,
              children: [
                Text(
                  currencyFormat.format(shift.actualCash ?? shift.expectedCash),
                  style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 13),
                ),
                Text(
                  diff == 0 ? 'Kas Pas' : (diff < 0 ? 'Kurang: ${currencyFormat.format(diff.abs())}' : 'Lebih: ${currencyFormat.format(diff)}'),
                  style: TextStyle(
                    fontSize: 11,
                    fontWeight: FontWeight.bold,
                    color: diff == 0 ? Colors.green.shade700 : (diff < 0 ? Colors.red.shade700 : Colors.blue.shade700),
                  ),
                ),
              ],
            ),
            children: [
              Padding(
                padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 8),
                child: Column(
                  children: [
                    _buildDialogRow('Modal Awal:', currencyFormat.format(shift.startingCash)),
                    _buildDialogRow('Penjualan Tunai:', currencyFormat.format(shift.totalCashSales)),
                    _buildDialogRow('Penjualan Non-Tunai:', currencyFormat.format(shift.totalNonCashSales)),
                    _buildDialogRow('Kas Masuk:', currencyFormat.format(shift.totalCashIn)),
                    _buildDialogRow('Kas Keluar:', currencyFormat.format(shift.totalCashOut)),
                    _buildDialogRow('Estimasi Laci:', currencyFormat.format(shift.expectedCash), isBold: true),
                    _buildDialogRow('Fisik Terhitung:', currencyFormat.format(shift.actualCash ?? 0.0), isBold: true, color: const Color(0xFF059669)),
                    if (shift.note.isNotEmpty) ...[
                      const SizedBox(height: 6),
                      Align(
                        alignment: Alignment.centerLeft,
                        child: Text('Catatan: ${shift.note}', style: const TextStyle(fontSize: 12, fontStyle: FontStyle.italic)),
                      ),
                    ],
                  ],
                ),
              ),
            ],
          ),
        );
      },
    );
  }
}
