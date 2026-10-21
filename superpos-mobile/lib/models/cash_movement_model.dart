class CashMovementModel {
  final String id;
  final String shiftId;
  final String type; // 'IN' (Kas Masuk) atau 'OUT' (Kas Keluar)
  final double amount;
  final String reason;
  final String createdAt;

  CashMovementModel({
    required this.id,
    required this.shiftId,
    required this.type,
    required this.amount,
    required this.reason,
    required this.createdAt,
  });

  Map<String, dynamic> toMap() {
    return {
      'id': id,
      'shift_id': shiftId,
      'type': type,
      'amount': amount,
      'reason': reason,
      'created_at': createdAt,
    };
  }

  factory CashMovementModel.fromMap(Map<String, dynamic> map) {
    return CashMovementModel(
      id: map['id']?.toString() ?? '',
      shiftId: map['shift_id']?.toString() ?? '',
      type: map['type'] ?? 'IN',
      amount: (map['amount'] as num?)?.toDouble() ?? 0.0,
      reason: map['reason'] ?? '',
      createdAt: map['created_at'] ?? '',
    );
  }
}
