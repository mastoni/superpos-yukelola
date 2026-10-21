import 'dart:convert';
import '../database/database_helper.dart';
import '../models/store_settings_model.dart';

class SettingsService {
  static const String _keySettings = 'store_settings';

  static Future<StoreSettingsModel> getSettings() async {
    try {
      final jsonStr = await DatabaseHelper.instance.getSetting(_keySettings);
      if (jsonStr != null && jsonStr.isNotEmpty) {
        final map = jsonDecode(jsonStr);
        return StoreSettingsModel.fromMap(map);
      }
    } catch (_) {}
    return StoreSettingsModel();
  }

  static Future<void> saveSettings(StoreSettingsModel settings) async {
    try {
      final jsonStr = jsonEncode(settings.toMap());
      await DatabaseHelper.instance.setSetting(_keySettings, jsonStr);
    } catch (_) {}
  }

  static Future<void> updateLastSync(String lastSyncTime) async {
    final current = await getSettings();
    await saveSettings(current.copyWith(lastSyncAt: lastSyncTime));
  }
}
