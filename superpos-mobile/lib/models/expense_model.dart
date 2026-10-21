class ExpenseModel {
  final String id;
  final String title;
  final double amount;
  final String category;
  final String note;
  final String createdAt;
  final int isSynced;

  ExpenseModel({
    required this.id,
    required this.title,
    required this.amount,
    this.category = 'Operasional',
    this.note = '',
    required this.createdAt,
    this.isSynced = 0,
  });

  Map<String, dynamic> toMap() {
    return {
      'id': id,
      'title': title,
      'amount': amount,
      'category': category,
      'note': note,
      'created_at': createdAt,
      'is_synced': isSynced,
    };
  }

  Map<String, dynamic> toGoogleSheetMap() {
    return {
      'id': id,
      'title': title,
      'amount': amount,
      'category': category,
      'note': note,
      'createdAt': createdAt,
    };
  }

  factory ExpenseModel.fromMap(Map<String, dynamic> map) {
    return ExpenseModel(
      id: map['id']?.toString() ?? '',
      title: map['title'] ?? '',
      amount: (map['amount'] as num?)?.toDouble() ?? 0.0,
      category: map['category'] ?? 'Operasional',
      note: map['note'] ?? '',
      createdAt: map['created_at'] ?? '',
      isSynced: (map['is_synced'] as num?)?.toInt() ?? 0,
    );
  }
}
