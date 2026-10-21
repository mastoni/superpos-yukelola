class StoreSettingsModel {
  final String storeName;
  final String address;
  final String phone;
  final String receiptFooter;
  final String googleScriptUrl;
  final String lastSyncAt;

  StoreSettingsModel({
    this.storeName = 'YUKelola Store',
    this.address = 'Jl. Bisnis Raya No. 12',
    this.phone = '081234567890',
    this.receiptFooter = 'Terima kasih atas kunjungan Anda!\nBarang yang sudah dibeli tidak dapat ditukar.',
    this.googleScriptUrl = '',
    this.lastSyncAt = '-',
  });

  Map<String, dynamic> toMap() {
    return {
      'storeName': storeName,
      'address': address,
      'phone': phone,
      'receiptFooter': receiptFooter,
      'googleScriptUrl': googleScriptUrl,
      'lastSyncAt': lastSyncAt,
    };
  }

  factory StoreSettingsModel.fromMap(Map<String, dynamic> map) {
    return StoreSettingsModel(
      storeName: map['storeName'] ?? 'YUKelola Store',
      address: map['address'] ?? 'Jl. Bisnis Raya No. 12',
      phone: map['phone'] ?? '081234567890',
      receiptFooter: map['receiptFooter'] ?? 'Terima kasih atas kunjungan Anda!\nBarang yang sudah dibeli tidak dapat ditukar.',
      googleScriptUrl: map['googleScriptUrl'] ?? '',
      lastSyncAt: map['lastSyncAt'] ?? '-',
    );
  }

  StoreSettingsModel copyWith({
    String? storeName,
    String? address,
    String? phone,
    String? receiptFooter,
    String? googleScriptUrl,
    String? lastSyncAt,
  }) {
    return StoreSettingsModel(
      storeName: storeName ?? this.storeName,
      address: address ?? this.address,
      phone: phone ?? this.phone,
      receiptFooter: receiptFooter ?? this.receiptFooter,
      googleScriptUrl: googleScriptUrl ?? this.googleScriptUrl,
      lastSyncAt: lastSyncAt ?? this.lastSyncAt,
    );
  }
}
