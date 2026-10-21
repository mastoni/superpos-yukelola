import 'dart:convert';
import 'package:http/http.dart' as http;
import '../database/database_helper.dart';
import '../models/category_model.dart';
import '../models/product_model.dart';
import 'settings_service.dart';

class GoogleSheetsService {
  /// Test koneksi ke URL Google Apps Script
  static Future<Map<String, dynamic>> testConnection(String url) async {
    try {
      final cleanUrl = url.trim();
      if (cleanUrl.isEmpty) {
        return {'success': false, 'message': 'URL Google Apps Script belum diisi.'};
      }

      final uri = Uri.parse(cleanUrl);
      final response = await http.post(
        uri,
        headers: {'Content-Type': 'application/json'},
        body: jsonEncode({'action': 'ping'}),
      ).timeout(const Duration(seconds: 15));

      if (response.statusCode >= 200 && response.statusCode < 400) {
        final data = jsonDecode(response.body);
        return {
          'success': data['status'] == 'success',
          'message': data['message'] ?? 'Koneksi terhubung!',
          'sheetName': data['sheetName'] ?? '',
        };
      } else {
        return {
          'success': false,
          'message': 'HTTP Status ${response.statusCode}: Gagal menghubungi Google Sheets.',
        };
      }
    } catch (e) {
      return {
        'success': false,
        'message': 'Koneksi gagal: $e',
      };
    }
  }

  /// Backup semua data dari SQLite ke Google Sheets
  static Future<Map<String, dynamic>> backupToSheets(String url) async {
    try {
      final cleanUrl = url.trim();
      if (cleanUrl.isEmpty) {
        return {'success': false, 'message': 'URL Google Apps Script belum dikonfigurasi.'};
      }

      // Ambil seluruh data dari database internal SQLite
      final dbData = await DatabaseHelper.instance.getAllDataForBackup();
      final storeSettings = await SettingsService.getSettings();

      final payload = {
        'action': 'backup_all',
        'data': {
          'store': {
            'name': storeSettings.storeName,
            'address': storeSettings.address,
            'phone': storeSettings.phone,
            'footer': storeSettings.receiptFooter,
          },
          'categories': dbData['categories'],
          'products': dbData['products'],
          'sales': dbData['sales'],
          'saleItems': dbData['saleItems'],
          'expenses': dbData['expenses'],
        }
      };

      final uri = Uri.parse(cleanUrl);
      final response = await http.post(
        uri,
        headers: {'Content-Type': 'application/json'},
        body: jsonEncode(payload),
      ).timeout(const Duration(seconds: 40));

      if (response.statusCode >= 200 && response.statusCode < 400) {
        final resData = jsonDecode(response.body);
        if (resData['status'] == 'success') {
          final now = DateTime.now();
          final formattedDate = '${now.day.toString().padLeft(2, '0')}/${now.month.toString().padLeft(2, '0')}/${now.year} ${now.hour.toString().padLeft(2, '0')}:${now.minute.toString().padLeft(2, '0')}';
          await SettingsService.updateLastSync(formattedDate);

          return {
            'success': true,
            'message': 'Backup ke Google Sheets Berhasil!',
            'syncedAt': formattedDate,
            'productCount': (dbData['products'] as List).length,
            'saleCount': (dbData['sales'] as List).length,
          };
        } else {
          return {
            'success': false,
            'message': resData['message'] ?? 'Gagal memproses data di Google Sheets',
          };
        }
      } else {
        return {
          'success': false,
          'message': 'Gagal (HTTP ${response.statusCode}). Pastikan URL berakhiran /exec dan hak akses "Anyone".',
        };
      }
    } catch (e) {
      return {
        'success': false,
        'message': 'Kesalahan saat sinkronisasi: $e',
      };
    }
  }

  /// Restore produk dan kategori dari Google Sheets ke SQLite HP
  static Future<Map<String, dynamic>> restoreProductsFromSheets(String url) async {
    try {
      final cleanUrl = url.trim();
      if (cleanUrl.isEmpty) {
        return {'success': false, 'message': 'URL Google Apps Script belum dikonfigurasi.'};
      }

      final uri = Uri.parse('$cleanUrl?action=restore_products');
      final response = await http.get(uri).timeout(const Duration(seconds: 30));

      if (response.statusCode >= 200 && response.statusCode < 400) {
        final resData = jsonDecode(response.body);
        if (resData['status'] == 'success') {
          final productsRaw = (resData['products'] as List? ?? []);
          final categoriesRaw = (resData['categories'] as List? ?? []);

          final products = productsRaw.map((p) => ProductModel.fromGoogleSheet(p)).toList();
          final categories = categoriesRaw.map((c) => CategoryModel.fromMap(c)).toList();

          if (products.isEmpty && categories.isEmpty) {
            return {
              'success': true,
              'message': 'Tidak ada data produk yang ditemukan di sheet "Katalog_Produk".',
              'count': 0
            };
          }

          await DatabaseHelper.instance.restoreProductsAndCategories(products, categories);

          return {
            'success': true,
            'message': 'Berhasil memulihkan ${products.length} produk dan ${categories.length} kategori dari Google Sheets!',
            'count': products.length,
          };
        }
      }

      return {
        'success': false,
        'message': 'Gagal mengambil data dari Google Sheets (HTTP ${response.statusCode}).',
      };
    } catch (e) {
      return {
        'success': false,
        'message': 'Kesalahan restore: $e',
      };
    }
  }
}
