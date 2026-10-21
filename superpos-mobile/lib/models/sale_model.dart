import 'sale_item_model.dart';

class SaleModel {
  final String id;
  final String invoiceNumber;
  final String createdAt;
  final String customerName;
  final String customerPhone;
  final double subtotal;
  final double discount;
  final double tax;
  final double total;
  final double paidAmount;
  final double changeAmount;
  final String paymentMethod; // TUNAI, QRIS, TRANSFER
  final String status; // PAID, VOID
  final int isSynced; // 0 = belum, 1 = sudah di Google Sheets
  final List<SaleItemModel> items;

  SaleModel({
    required this.id,
    required this.invoiceNumber,
    required this.createdAt,
    this.customerName = 'Pelanggan Umum',
    this.customerPhone = '',
    required this.subtotal,
    this.discount = 0.0,
    this.tax = 0.0,
    required this.total,
    required this.paidAmount,
    required this.changeAmount,
    this.paymentMethod = 'TUNAI',
    this.status = 'PAID',
    this.isSynced = 0,
    this.items = const [],
  });

  Map<String, dynamic> toMap() {
    return {
      'id': id,
      'invoice_number': invoiceNumber,
      'created_at': createdAt,
      'customer_name': customerName,
      'customer_phone': customerPhone,
      'subtotal': subtotal,
      'discount': discount,
      'tax': tax,
      'total': total,
      'paid_amount': paidAmount,
      'change_amount': changeAmount,
      'payment_method': paymentMethod,
      'status': status,
      'is_synced': isSynced,
    };
  }

  Map<String, dynamic> toGoogleSheetMap() {
    return {
      'id': id,
      'invoiceNumber': invoiceNumber,
      'createdAt': createdAt,
      'customerName': customerName,
      'subtotal': subtotal,
      'discount': discount,
      'tax': tax,
      'total': total,
      'paidAmount': paidAmount,
      'changeAmount': changeAmount,
      'paymentMethod': paymentMethod,
      'status': status,
    };
  }

  factory SaleModel.fromMap(Map<String, dynamic> map, {List<SaleItemModel> items = const []}) {
    return SaleModel(
      id: map['id']?.toString() ?? '',
      invoiceNumber: map['invoice_number'] ?? '',
      createdAt: map['created_at'] ?? '',
      customerName: map['customer_name'] ?? 'Pelanggan Umum',
      customerPhone: map['customer_phone'] ?? '',
      subtotal: (map['subtotal'] as num?)?.toDouble() ?? 0.0,
      discount: (map['discount'] as num?)?.toDouble() ?? 0.0,
      tax: (map['tax'] as num?)?.toDouble() ?? 0.0,
      total: (map['total'] as num?)?.toDouble() ?? 0.0,
      paidAmount: (map['paid_amount'] as num?)?.toDouble() ?? 0.0,
      changeAmount: (map['change_amount'] as num?)?.toDouble() ?? 0.0,
      paymentMethod: map['payment_method'] ?? 'TUNAI',
      status: map['status'] ?? 'PAID',
      isSynced: (map['is_synced'] as num?)?.toInt() ?? 0,
      items: items,
    );
  }

  SaleModel copyWith({
    String? id,
    String? invoiceNumber,
    String? createdAt,
    String? customerName,
    String? customerPhone,
    double? subtotal,
    double? discount,
    double? tax,
    double? total,
    double? paidAmount,
    double? changeAmount,
    String? paymentMethod,
    String? status,
    int? isSynced,
    List<SaleItemModel>? items,
  }) {
    return SaleModel(
      id: id ?? this.id,
      invoiceNumber: invoiceNumber ?? this.invoiceNumber,
      createdAt: createdAt ?? this.createdAt,
      customerName: customerName ?? this.customerName,
      customerPhone: customerPhone ?? this.customerPhone,
      subtotal: subtotal ?? this.subtotal,
      discount: discount ?? this.discount,
      tax: tax ?? this.tax,
      total: total ?? this.total,
      paidAmount: paidAmount ?? this.paidAmount,
      changeAmount: changeAmount ?? this.changeAmount,
      paymentMethod: paymentMethod ?? this.paymentMethod,
      status: status ?? this.status,
      isSynced: isSynced ?? this.isSynced,
      items: items ?? this.items,
    );
  }
}
