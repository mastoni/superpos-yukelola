class ShiftModel {
  final String id;
  final String cashierName;
  final String startTime;
  final String? endTime;
  final double startingCash;
  final double totalCashSales;
  final double totalNonCashSales;
  final double totalCashIn;
  final double totalCashOut;
  final double expectedCash;
  final double? actualCash;
  final double? difference;
  final String status; // 'OPEN', 'CLOSED'
  final String note;

  ShiftModel({
    required this.id,
    required this.cashierName,
    required this.startTime,
    this.endTime,
    required this.startingCash,
    this.totalCashSales = 0.0,
    this.totalNonCashSales = 0.0,
    this.totalCashIn = 0.0,
    this.totalCashOut = 0.0,
    required this.expectedCash,
    this.actualCash,
    this.difference,
    this.status = 'OPEN',
    this.note = '',
  });

  Map<String, dynamic> toMap() {
    return {
      'id': id,
      'cashier_name': cashierName,
      'start_time': startTime,
      'end_time': endTime,
      'starting_cash': startingCash,
      'total_cash_sales': totalCashSales,
      'total_non_cash_sales': totalNonCashSales,
      'total_cash_in': totalCashIn,
      'total_cash_out': totalCashOut,
      'expected_cash': expectedCash,
      'actual_cash': actualCash,
      'difference': difference,
      'status': status,
      'note': note,
    };
  }

  factory ShiftModel.fromMap(Map<String, dynamic> map) {
    return ShiftModel(
      id: map['id']?.toString() ?? '',
      cashierName: map['cashier_name'] ?? 'Kasir Utama',
      startTime: map['start_time'] ?? '',
      endTime: map['end_time'],
      startingCash: (map['starting_cash'] as num?)?.toDouble() ?? 0.0,
      totalCashSales: (map['total_cash_sales'] as num?)?.toDouble() ?? 0.0,
      totalNonCashSales: (map['total_non_cash_sales'] as num?)?.toDouble() ?? 0.0,
      totalCashIn: (map['total_cash_in'] as num?)?.toDouble() ?? 0.0,
      totalCashOut: (map['total_cash_out'] as num?)?.toDouble() ?? 0.0,
      expectedCash: (map['expected_cash'] as num?)?.toDouble() ?? 0.0,
      actualCash: (map['actual_cash'] as num?)?.toDouble(),
      difference: (map['difference'] as num?)?.toDouble(),
      status: map['status'] ?? 'OPEN',
      note: map['note'] ?? '',
    );
  }
}
