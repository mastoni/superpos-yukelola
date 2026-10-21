class SaleItemModel {
  final String id;
  final String saleId;
  final String productId;
  final String productName;
  final int qty;
  final double unitPrice;
  final double costPrice;
  final double subtotal;

  SaleItemModel({
    required this.id,
    required this.saleId,
    required this.productId,
    required this.productName,
    required this.qty,
    required this.unitPrice,
    this.costPrice = 0.0,
    required this.subtotal,
  });

  Map<String, dynamic> toMap() {
    return {
      'id': id,
      'sale_id': saleId,
      'product_id': productId,
      'product_name': productName,
      'qty': qty,
      'unit_price': unitPrice,
      'cost_price': costPrice,
      'subtotal': subtotal,
    };
  }

  Map<String, dynamic> toGoogleSheetMap() {
    return {
      'id': id,
      'saleId': saleId,
      'productName': productName,
      'qty': qty,
      'unitPrice': unitPrice,
      'subtotal': subtotal,
    };
  }

  factory SaleItemModel.fromMap(Map<String, dynamic> map) {
    return SaleItemModel(
      id: map['id']?.toString() ?? '',
      saleId: map['sale_id']?.toString() ?? '',
      productId: map['product_id']?.toString() ?? '',
      productName: map['product_name'] ?? '',
      qty: (map['qty'] as num?)?.toInt() ?? 1,
      unitPrice: (map['unit_price'] as num?)?.toDouble() ?? 0.0,
      costPrice: (map['cost_price'] as num?)?.toDouble() ?? 0.0,
      subtotal: (map['subtotal'] as num?)?.toDouble() ?? 0.0,
    );
  }
}
