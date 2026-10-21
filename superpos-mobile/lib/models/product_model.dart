class ProductModel {
  final String id;
  final String sku;
  final String name;
  final String category;
  final double costPrice;
  final double sellPrice;
  final int stock;
  final String unit;
  final String updatedAt;

  ProductModel({
    required this.id,
    required this.sku,
    required this.name,
    this.category = 'Umum',
    this.costPrice = 0.0,
    required this.sellPrice,
    this.stock = 0,
    this.unit = 'Pcs',
    required this.updatedAt,
  });

  Map<String, dynamic> toMap() {
    return {
      'id': id,
      'sku': sku,
      'name': name,
      'category': category,
      'cost_price': costPrice,
      'sell_price': sellPrice,
      'stock': stock,
      'unit': unit,
      'updated_at': updatedAt,
    };
  }

  Map<String, dynamic> toGoogleSheetMap() {
    return {
      'id': id,
      'sku': sku,
      'name': name,
      'category': category,
      'costPrice': costPrice,
      'sellPrice': sellPrice,
      'stock': stock,
      'unit': unit,
      'updatedAt': updatedAt,
    };
  }

  factory ProductModel.fromMap(Map<String, dynamic> map) {
    return ProductModel(
      id: map['id']?.toString() ?? '',
      sku: map['sku']?.toString() ?? '',
      name: map['name'] ?? '',
      category: map['category'] ?? 'Umum',
      costPrice: (map['cost_price'] as num?)?.toDouble() ?? 0.0,
      sellPrice: (map['sell_price'] as num?)?.toDouble() ?? 0.0,
      stock: (map['stock'] as num?)?.toInt() ?? 0,
      unit: map['unit'] ?? 'Pcs',
      updatedAt: map['updated_at'] ?? DateTime.now().toIso8601String(),
    );
  }

  factory ProductModel.fromGoogleSheet(Map<String, dynamic> map) {
    return ProductModel(
      id: map['id']?.toString() ?? '',
      sku: map['sku']?.toString() ?? '',
      name: map['name']?.toString() ?? '',
      category: map['category']?.toString() ?? 'Umum',
      costPrice: (map['costPrice'] as num?)?.toDouble() ?? 0.0,
      sellPrice: (map['sellPrice'] as num?)?.toDouble() ?? 0.0,
      stock: (map['stock'] as num?)?.toInt() ?? 0,
      unit: map['unit']?.toString() ?? 'Pcs',
      updatedAt: map['updatedAt']?.toString() ?? DateTime.now().toIso8601String(),
    );
  }

  ProductModel copyWith({
    String? id,
    String? sku,
    String? name,
    String? category,
    double? costPrice,
    double? sellPrice,
    int? stock,
    String? unit,
    String? updatedAt,
  }) {
    return ProductModel(
      id: id ?? this.id,
      sku: sku ?? this.sku,
      name: name ?? this.name,
      category: category ?? this.category,
      costPrice: costPrice ?? this.costPrice,
      sellPrice: sellPrice ?? this.sellPrice,
      stock: stock ?? this.stock,
      unit: unit ?? this.unit,
      updatedAt: updatedAt ?? this.updatedAt,
    );
  }
}
