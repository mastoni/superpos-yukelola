import 'dart:convert';
import 'dart:io';
import 'package:sqflite/sqflite.dart';
import 'package:sqflite_common_ffi/sqflite_ffi.dart';
import 'package:path/path.dart';
import 'package:uuid/uuid.dart';
import '../models/category_model.dart';
import '../models/customer_model.dart';
import '../models/product_model.dart';
import '../models/sale_model.dart';
import '../models/sale_item_model.dart';
import '../models/expense_model.dart';
import '../models/shift_model.dart';
import '../models/cash_movement_model.dart';

class DatabaseHelper {
  static final DatabaseHelper instance = DatabaseHelper._init();
  static Database? _database;

  DatabaseHelper._init();

  Future<Database> get database async {
    if (_database != null) return _database!;
    _database = await _initDB('yukelola_offline.db');
    return _database!;
  }

  Future<Database> _initDB(String filePath) async {
    // Inisialisasi FFI jika dijalankan di Windows atau Linux Desktop
    if (Platform.isWindows || Platform.isLinux) {
      sqfliteFfiInit();
      databaseFactory = databaseFactoryFfi;
    }

    final dbPath = await getDatabasesPath();
    final path = join(dbPath, filePath);

    return await openDatabase(
      path,
      version: 3,
      onCreate: _createDB,
      onUpgrade: _onUpgrade,
    );
  }

  Future _createDB(Database db, int version) async {
    await db.execute('''
      CREATE TABLE categories (
        id TEXT PRIMARY KEY,
        name TEXT NOT NULL
      )
    ''');

    await db.execute('''
      CREATE TABLE customers (
        id TEXT PRIMARY KEY,
        name TEXT NOT NULL,
        phone TEXT,
        email TEXT,
        address TEXT
      )
    ''');

    await db.execute('''
      CREATE TABLE products (
        id TEXT PRIMARY KEY,
        sku TEXT,
        name TEXT NOT NULL,
        category TEXT NOT NULL,
        cost_price REAL NOT NULL DEFAULT 0.0,
        sell_price REAL NOT NULL,
        stock INTEGER NOT NULL DEFAULT 0,
        unit TEXT NOT NULL DEFAULT 'Pcs',
        updated_at TEXT NOT NULL
      )
    ''');

    await db.execute('''
      CREATE TABLE sales (
        id TEXT PRIMARY KEY,
        invoice_number TEXT NOT NULL,
        created_at TEXT NOT NULL,
        customer_name TEXT,
        customer_phone TEXT,
        subtotal REAL NOT NULL,
        discount REAL NOT NULL DEFAULT 0.0,
        tax REAL NOT NULL DEFAULT 0.0,
        total REAL NOT NULL,
        paid_amount REAL NOT NULL,
        change_amount REAL NOT NULL,
        payment_method TEXT NOT NULL DEFAULT 'TUNAI',
        status TEXT NOT NULL DEFAULT 'PAID',
        is_synced INTEGER NOT NULL DEFAULT 0
      )
    ''');

    await db.execute('''
      CREATE TABLE sale_items (
        id TEXT PRIMARY KEY,
        sale_id TEXT NOT NULL,
        product_id TEXT NOT NULL,
        product_name TEXT NOT NULL,
        qty INTEGER NOT NULL,
        unit_price REAL NOT NULL,
        cost_price REAL NOT NULL DEFAULT 0.0,
        subtotal REAL NOT NULL,
        FOREIGN KEY (sale_id) REFERENCES sales (id) ON DELETE CASCADE
      )
    ''');

    await db.execute('''
      CREATE TABLE hold_sales (
        id TEXT PRIMARY KEY,
        customer_name TEXT NOT NULL,
        created_at TEXT NOT NULL,
        total REAL NOT NULL,
        items_json TEXT NOT NULL
      )
    ''');

    await db.execute('''
      CREATE TABLE expenses (
        id TEXT PRIMARY KEY,
        title TEXT NOT NULL,
        amount REAL NOT NULL,
        category TEXT NOT NULL,
        note TEXT,
        created_at TEXT NOT NULL,
        is_synced INTEGER NOT NULL DEFAULT 0
      )
    ''');

    await db.execute('''
      CREATE TABLE IF NOT EXISTS settings (
        key TEXT PRIMARY KEY,
        value TEXT NOT NULL
      )
    ''');

    await db.execute('''
      CREATE TABLE IF NOT EXISTS shifts (
        id TEXT PRIMARY KEY,
        cashier_name TEXT NOT NULL,
        start_time TEXT NOT NULL,
        end_time TEXT,
        starting_cash REAL NOT NULL DEFAULT 0.0,
        total_cash_sales REAL NOT NULL DEFAULT 0.0,
        total_non_cash_sales REAL NOT NULL DEFAULT 0.0,
        total_cash_in REAL NOT NULL DEFAULT 0.0,
        total_cash_out REAL NOT NULL DEFAULT 0.0,
        expected_cash REAL NOT NULL DEFAULT 0.0,
        actual_cash REAL,
        difference REAL,
        status TEXT NOT NULL DEFAULT 'OPEN',
        note TEXT NOT NULL DEFAULT ''
      )
    ''');

    await db.execute('''
      CREATE TABLE IF NOT EXISTS cash_movements (
        id TEXT PRIMARY KEY,
        shift_id TEXT NOT NULL,
        type TEXT NOT NULL,
        amount REAL NOT NULL,
        reason TEXT NOT NULL,
        created_at TEXT NOT NULL
      )
    ''');

    // Seed data awal
    await _seedInitialData(db);
  }

  Future _onUpgrade(Database db, int oldVersion, int newVersion) async {
    await db.execute('''
      CREATE TABLE IF NOT EXISTS settings (
        key TEXT PRIMARY KEY,
        value TEXT NOT NULL
      )
    ''');

    await db.execute('''
      CREATE TABLE IF NOT EXISTS shifts (
        id TEXT PRIMARY KEY,
        cashier_name TEXT NOT NULL,
        start_time TEXT NOT NULL,
        end_time TEXT,
        starting_cash REAL NOT NULL DEFAULT 0.0,
        total_cash_sales REAL NOT NULL DEFAULT 0.0,
        total_non_cash_sales REAL NOT NULL DEFAULT 0.0,
        total_cash_in REAL NOT NULL DEFAULT 0.0,
        total_cash_out REAL NOT NULL DEFAULT 0.0,
        expected_cash REAL NOT NULL DEFAULT 0.0,
        actual_cash REAL,
        difference REAL,
        status TEXT NOT NULL DEFAULT 'OPEN',
        note TEXT NOT NULL DEFAULT ''
      )
    ''');

    await db.execute('''
      CREATE TABLE IF NOT EXISTS cash_movements (
        id TEXT PRIMARY KEY,
        shift_id TEXT NOT NULL,
        type TEXT NOT NULL,
        amount REAL NOT NULL,
        reason TEXT NOT NULL,
        created_at TEXT NOT NULL
      )
    ''');

    if (oldVersion < 2) {
      await db.execute('''
        CREATE TABLE IF NOT EXISTS customers (
          id TEXT PRIMARY KEY,
          name TEXT NOT NULL,
          phone TEXT,
          email TEXT,
          address TEXT
        )
      ''');

      await db.execute('''
        CREATE TABLE IF NOT EXISTS hold_sales (
          id TEXT PRIMARY KEY,
          customer_name TEXT NOT NULL,
          created_at TEXT NOT NULL,
          total REAL NOT NULL,
          items_json TEXT NOT NULL
        )
      ''');

      // Seed customer awal
      final uuid = const Uuid();
      await db.insert('customers', {
        'id': uuid.v4(),
        'name': 'Pelanggan Umum',
        'phone': '',
        'email': '',
        'address': '',
      });
      await db.insert('customers', {
        'id': uuid.v4(),
        'name': 'Warung Bu Siti',
        'phone': '081234567891',
        'email': '',
        'address': 'Jl. Kenanga No. 5',
      });
    }
  }

  Future _seedInitialData(Database db) async {
    const uuid = Uuid();
    final now = DateTime.now().toIso8601String();

    // Default Customers
    final initialCustomers = [
      CustomerModel(id: uuid.v4(), name: 'Pelanggan Umum', phone: ''),
      CustomerModel(id: uuid.v4(), name: 'Toko Berkah Mandiri', phone: '081298765432', address: 'Pasar Baru Blok A'),
      CustomerModel(id: uuid.v4(), name: 'Ibu Siti Khadijah', phone: '085712345678', address: 'Jl. Merpati 12'),
    ];
    for (var cust in initialCustomers) {
      await db.insert('customers', cust.toMap());
    }

    // Default Categories
    final catList = [
      'Makanan & Minuman',
      'Sembako & Kebutuhan Pokok',
      'Camilan & Snack',
      'Elektronik & Aksesoris',
      'Alat Tulis Kantor',
      'Umum'
    ];

    for (var cat in catList) {
      await db.insert('categories', {
        'id': uuid.v4(),
        'name': cat,
      });
    }

    // Default Products
    final initialProducts = [
      ProductModel(
        id: uuid.v4(),
        sku: '899100110001',
        name: 'Kopi Arabika Tubruk 250gr',
        category: 'Makanan & Minuman',
        costPrice: 28000,
        sellPrice: 38000,
        stock: 45,
        unit: 'Bks',
        updatedAt: now,
      ),
      ProductModel(
        id: uuid.v4(),
        sku: '899100110002',
        name: 'Teh Celup Melati Kotak',
        category: 'Makanan & Minuman',
        costPrice: 6500,
        sellPrice: 9500,
        stock: 60,
        unit: 'Kotak',
        updatedAt: now,
      ),
      ProductModel(
        id: uuid.v4(),
        sku: '899100110003',
        name: 'Minyak Goreng Sawit 1L',
        category: 'Sembako & Kebutuhan Pokok',
        costPrice: 15500,
        sellPrice: 18500,
        stock: 32,
        unit: 'Pouch',
        updatedAt: now,
      ),
      ProductModel(
        id: uuid.v4(),
        sku: '899100110004',
        name: 'Beras Premium Ramos 5kg',
        category: 'Sembako & Kebutuhan Pokok',
        costPrice: 68000,
        sellPrice: 77000,
        stock: 20,
        unit: 'Karung',
        updatedAt: now,
      ),
      ProductModel(
        id: uuid.v4(),
        sku: '899100110005',
        name: 'Keripik Singkong Balado 150gr',
        category: 'Camilan & Snack',
        costPrice: 9000,
        sellPrice: 13500,
        stock: 8,
        unit: 'Bks',
        updatedAt: now,
      ),
      ProductModel(
        id: uuid.v4(),
        sku: '899100110006',
        name: 'Kabel Data Fast Charging Type-C',
        category: 'Elektronik & Aksesoris',
        costPrice: 14000,
        sellPrice: 28000,
        stock: 15,
        unit: 'Pcs',
        updatedAt: now,
      ),
    ];

    for (var prod in initialProducts) {
      await db.insert('products', prod.toMap());
    }
  }

  // --- PELANGGAN (CUSTOMERS) ---
  Future<List<CustomerModel>> getAllCustomers({String? search}) async {
    final db = await instance.database;
    String? whereClause;
    List<dynamic>? whereArgs;

    if (search != null && search.trim().isNotEmpty) {
      whereClause = 'name LIKE ? OR phone LIKE ?';
      whereArgs = ['%${search.trim()}%', '%${search.trim()}%'];
    }

    final result = await db.query(
      'customers',
      where: whereClause,
      whereArgs: whereArgs,
      orderBy: 'name ASC',
    );
    return result.map((json) => CustomerModel.fromMap(json)).toList();
  }

  Future<void> insertCustomer(CustomerModel customer) async {
    final db = await instance.database;
    await db.insert('customers', customer.toMap(), conflictAlgorithm: ConflictAlgorithm.replace);
  }

  // --- HOLD SALES (SIMPAN SEMENTARA / ANTREAN) ---
  Future<void> saveHoldSale(String customerName, double total, List<Map<String, dynamic>> items) async {
    final db = await instance.database;
    const uuid = Uuid();
    await db.insert('hold_sales', {
      'id': uuid.v4(),
      'customer_name': customerName,
      'created_at': DateTime.now().toIso8601String(),
      'total': total,
      'items_json': jsonEncode(items),
    });
  }

  Future<List<Map<String, dynamic>>> getHoldSales() async {
    final db = await instance.database;
    return await db.query('hold_sales', orderBy: 'created_at DESC');
  }

  Future<void> deleteHoldSale(String id) async {
    final db = await instance.database;
    await db.delete('hold_sales', where: 'id = ?', whereArgs: [id]);
  }

  // --- KATEGORI ---
  Future<List<CategoryModel>> getAllCategories() async {
    final db = await instance.database;
    final result = await db.query('categories', orderBy: 'name ASC');
    return result.map((json) => CategoryModel.fromMap(json)).toList();
  }

  Future<void> insertCategory(CategoryModel category) async {
    final db = await instance.database;
    await db.insert('categories', category.toMap(), conflictAlgorithm: ConflictAlgorithm.replace);
  }

  Future<void> deleteCategory(String id) async {
    final db = await instance.database;
    await db.delete('categories', where: 'id = ?', whereArgs: [id]);
  }

  // --- PRODUK ---
  Future<List<ProductModel>> getAllProducts({String? search, String? category}) async {
    final db = await instance.database;
    String whereClause = '';
    List<dynamic> whereArgs = [];

    if (search != null && search.trim().isNotEmpty) {
      whereClause += '(name LIKE ? OR sku LIKE ?)';
      whereArgs.add('%${search.trim()}%');
      whereArgs.add('%${search.trim()}%');
    }

    if (category != null && category != 'Semua' && category.isNotEmpty) {
      if (whereClause.isNotEmpty) whereClause += ' AND ';
      whereClause += 'category = ?';
      whereArgs.add(category);
    }

    final result = await db.query(
      'products',
      where: whereClause.isEmpty ? null : whereClause,
      whereArgs: whereArgs.isEmpty ? null : whereArgs,
      orderBy: 'name ASC',
    );

    return result.map((json) => ProductModel.fromMap(json)).toList();
  }

  Future<ProductModel?> getProductBySku(String sku) async {
    final db = await instance.database;
    final result = await db.query(
      'products',
      where: 'sku = ?',
      whereArgs: [sku],
      limit: 1,
    );
    if (result.isNotEmpty) {
      return ProductModel.fromMap(result.first);
    }
    return null;
  }

  Future<void> insertProduct(ProductModel product) async {
    final db = await instance.database;
    await db.insert('products', product.toMap(), conflictAlgorithm: ConflictAlgorithm.replace);
  }

  Future<void> updateProduct(ProductModel product) async {
    final db = await instance.database;
    await db.update(
      'products',
      product.toMap(),
      where: 'id = ?',
      whereArgs: [product.id],
    );
  }

  Future<void> deleteProduct(String id) async {
    final db = await instance.database;
    await db.delete('products', where: 'id = ?', whereArgs: [id]);
  }

  Future<List<ProductModel>> getLowStockProducts({int threshold = 10}) async {
    final db = await instance.database;
    final result = await db.query(
      'products',
      where: 'stock <= ?',
      whereArgs: [threshold],
      orderBy: 'stock ASC',
    );
    return result.map((json) => ProductModel.fromMap(json)).toList();
  }

  // --- TRANSAKSI PENJUALAN ---
  Future<String> generateInvoiceNumber() async {
    final now = DateTime.now();
    final datePrefix = '${now.year}${now.month.toString().padLeft(2, '0')}${now.day.toString().padLeft(2, '0')}';
    final db = await instance.database;
    final result = await db.rawQuery(
      "SELECT COUNT(*) as count FROM sales WHERE invoice_number LIKE 'INV-$datePrefix%'"
    );
    final count = Sqflite.firstIntValue(result) ?? 0;
    final sequence = (count + 1).toString().padLeft(4, '0');
    return 'INV-$datePrefix-$sequence';
  }

  Future<void> createSale(SaleModel sale, List<SaleItemModel> items) async {
    final db = await instance.database;
    await db.transaction((txn) async {
      await txn.insert('sales', sale.toMap());

      for (var item in items) {
        await txn.insert('sale_items', item.toMap());

        // Kurangi stok produk secara real-time
        await txn.rawUpdate('''
          UPDATE products 
          SET stock = stock - ?, updated_at = ?
          WHERE id = ?
        ''', [item.qty, DateTime.now().toIso8601String(), item.productId]);
      }
    });
  }

  Future<List<SaleModel>> getAllSales({int limit = 50}) async {
    final db = await instance.database;
    final result = await db.query(
      'sales',
      orderBy: 'created_at DESC',
      limit: limit,
    );

    List<SaleModel> sales = [];
    for (var s in result) {
      final saleId = s['id'].toString();
      final itemsResult = await db.query(
        'sale_items',
        where: 'sale_id = ?',
        whereArgs: [saleId],
      );
      final items = itemsResult.map((i) => SaleItemModel.fromMap(i)).toList();
      sales.add(SaleModel.fromMap(s, items: items));
    }
    return sales;
  }

  Future<void> voidSale(String saleId) async {
    final db = await instance.database;
    await db.transaction((txn) async {
      final items = await txn.query(
        'sale_items',
        where: 'sale_id = ?',
        whereArgs: [saleId],
      );

      for (var it in items) {
        final qty = (it['qty'] as num).toInt();
        final prodId = it['product_id'].toString();
        await txn.rawUpdate('''
          UPDATE products 
          SET stock = stock + ?, updated_at = ?
          WHERE id = ?
        ''', [qty, DateTime.now().toIso8601String(), prodId]);
      }

      await txn.update(
        'sales',
        {'status': 'VOID'},
        where: 'id = ?',
        whereArgs: [saleId],
      );
    });
  }

  // --- STATISTIK DASHBOARD ---
  Future<Map<String, dynamic>> getTodayStats() async {
    final db = await instance.database;
    final today = DateTime.now();
    final todayStr = '${today.year}-${today.month.toString().padLeft(2, '0')}-${today.day.toString().padLeft(2, '0')}';

    final salesToday = await db.rawQuery('''
      SELECT 
        COUNT(*) as total_transaksi,
        COALESCE(SUM(total), 0) as total_omset
      FROM sales 
      WHERE created_at LIKE '$todayStr%' AND status = 'PAID'
    ''');

    final profitResult = await db.rawQuery('''
      SELECT 
        COALESCE(SUM(si.qty * (si.unit_price - si.cost_price)), 0) as gross_profit
      FROM sale_items si
      JOIN sales s ON si.sale_id = s.id
      WHERE s.created_at LIKE '$todayStr%' AND s.status = 'PAID'
    ''');

    final expenseResult = await db.rawQuery('''
      SELECT COALESCE(SUM(amount), 0) as total_expense
      FROM expenses
      WHERE created_at LIKE '$todayStr%'
    ''');

    final totalTransaksi = Sqflite.firstIntValue(salesToday) ?? 0;
    final totalOmset = (salesToday.first['total_omset'] as num?)?.toDouble() ?? 0.0;
    final grossProfit = (profitResult.first['gross_profit'] as num?)?.toDouble() ?? 0.0;
    final totalExpense = (expenseResult.first['total_expense'] as num?)?.toDouble() ?? 0.0;

    return {
      'total_transactions': totalTransaksi,
      'total_revenue': totalOmset,
      'gross_profit': grossProfit,
      'total_expense': totalExpense,
      'net_profit': grossProfit - totalExpense,
    };
  }

  // --- EXPENSES (PENGELUARAN) ---
  Future<List<ExpenseModel>> getAllExpenses() async {
    final db = await instance.database;
    final result = await db.query('expenses', orderBy: 'created_at DESC');
    return result.map((json) => ExpenseModel.fromMap(json)).toList();
  }

  Future<void> insertExpense(ExpenseModel expense) async {
    final db = await instance.database;
    await db.insert('expenses', expense.toMap());
  }

  Future<void> deleteExpense(String id) async {
    final db = await instance.database;
    await db.delete('expenses', where: 'id = ?', whereArgs: [id]);
  }

  // --- QUERY KHUSUS GOOGLE SHEETS SYNC ---
  Future<Map<String, dynamic>> getAllDataForBackup() async {
    final categories = await getAllCategories();
    final products = await getAllProducts();
    final sales = await getAllSales(limit: 500);
    final expenses = await getAllExpenses();

    List<SaleItemModel> allSaleItems = [];
    for (var s in sales) {
      allSaleItems.addAll(s.items);
    }

    return {
      'categories': categories.map((c) => c.toMap()).toList(),
      'products': products.map((p) => p.toGoogleSheetMap()).toList(),
      'sales': sales.map((s) => s.toGoogleSheetMap()).toList(),
      'saleItems': allSaleItems.map((si) => si.toGoogleSheetMap()).toList(),
      'expenses': expenses.map((e) => e.toGoogleSheetMap()).toList(),
    };
  }

  Future<void> restoreProductsAndCategories(List<ProductModel> products, List<CategoryModel> categories) async {
    final db = await instance.database;
    await db.transaction((txn) async {
      for (var cat in categories) {
        await txn.insert('categories', cat.toMap(), conflictAlgorithm: ConflictAlgorithm.replace);
      }
      for (var prod in products) {
        await txn.insert('products', prod.toMap(), conflictAlgorithm: ConflictAlgorithm.replace);
      }
    });
  }

  // --- PENGATURAN TOKO (SETTINGS DI SQLITE) ---
  Future<String?> getSetting(String key) async {
    final db = await instance.database;
    final res = await db.query('settings', where: 'key = ?', whereArgs: [key], limit: 1);
    if (res.isNotEmpty) return res.first['value'] as String?;
    return null;
  }

  Future<void> setSetting(String key, String value) async {
    final db = await instance.database;
    await db.insert(
      'settings',
      {'key': key, 'value': value},
      conflictAlgorithm: ConflictAlgorithm.replace,
    );
  }

  // ==========================================
  // MANAJEMEN SHIFT & KAS KASIR (CASH DRAWER)
  // ==========================================

  Future<ShiftModel?> getActiveShift() async {
    final db = await instance.database;
    final res = await db.query(
      'shifts',
      where: 'status = ?',
      whereArgs: ['OPEN'],
      orderBy: 'start_time DESC',
      limit: 1,
    );

    if (res.isEmpty) return null;
    final rawShift = ShiftModel.fromMap(res.first);

    // Hitung real-time sales & movements selama shift ini
    final salesRes = await db.rawQuery('''
      SELECT 
        SUM(CASE WHEN payment_method = 'TUNAI' THEN total ELSE 0 END) as cash_sales,
        SUM(CASE WHEN payment_method != 'TUNAI' THEN total ELSE 0 END) as non_cash_sales
      FROM sales
      WHERE created_at >= ?
    ''', [rawShift.startTime]);

    final movementsRes = await db.rawQuery('''
      SELECT 
        SUM(CASE WHEN type = 'IN' THEN amount ELSE 0 END) as cash_in,
        SUM(CASE WHEN type = 'OUT' THEN amount ELSE 0 END) as cash_out
      FROM cash_movements
      WHERE shift_id = ?
    ''', [rawShift.id]);

    final cashSales = (salesRes.first['cash_sales'] as num?)?.toDouble() ?? 0.0;
    final nonCashSales = (salesRes.first['non_cash_sales'] as num?)?.toDouble() ?? 0.0;
    final cashIn = (movementsRes.first['cash_in'] as num?)?.toDouble() ?? 0.0;
    final cashOut = (movementsRes.first['cash_out'] as num?)?.toDouble() ?? 0.0;
    final expected = rawShift.startingCash + cashSales + cashIn - cashOut;

    return ShiftModel(
      id: rawShift.id,
      cashierName: rawShift.cashierName,
      startTime: rawShift.startTime,
      startingCash: rawShift.startingCash,
      totalCashSales: cashSales,
      totalNonCashSales: nonCashSales,
      totalCashIn: cashIn,
      totalCashOut: cashOut,
      expectedCash: expected,
      status: 'OPEN',
      note: rawShift.note,
    );
  }

  Future<ShiftModel> openShift(String cashierName, double startingCash) async {
    final db = await instance.database;
    final id = const Uuid().v4();
    final now = DateTime.now().toIso8601String();

    final shift = ShiftModel(
      id: id,
      cashierName: cashierName.isEmpty ? 'Kasir Utama' : cashierName,
      startTime: now,
      startingCash: startingCash,
      expectedCash: startingCash,
      status: 'OPEN',
    );

    await db.insert('shifts', shift.toMap());
    return shift;
  }

  Future<void> closeShift(String shiftId, double actualCash, String note) async {
    final db = await instance.database;
    final active = await getActiveShift();
    final now = DateTime.now().toIso8601String();

    final expected = active?.expectedCash ?? 0.0;
    final diff = actualCash - expected;

    await db.update(
      'shifts',
      {
        'end_time': now,
        'total_cash_sales': active?.totalCashSales ?? 0.0,
        'total_non_cash_sales': active?.totalNonCashSales ?? 0.0,
        'total_cash_in': active?.totalCashIn ?? 0.0,
        'total_cash_out': active?.totalCashOut ?? 0.0,
        'expected_cash': expected,
        'actual_cash': actualCash,
        'difference': diff,
        'status': 'CLOSED',
        'note': note,
      },
      where: 'id = ?',
      whereArgs: [shiftId],
    );
  }

  Future<void> addCashMovement(String shiftId, String type, double amount, String reason) async {
    final db = await instance.database;
    final movement = CashMovementModel(
      id: const Uuid().v4(),
      shiftId: shiftId,
      type: type,
      amount: amount,
      reason: reason,
      createdAt: DateTime.now().toIso8601String(),
    );
    await db.insert('cash_movements', movement.toMap());
  }

  Future<List<CashMovementModel>> getCashMovements(String shiftId) async {
    final db = await instance.database;
    final res = await db.query(
      'cash_movements',
      where: 'shift_id = ?',
      whereArgs: [shiftId],
      orderBy: 'created_at DESC',
    );
    return res.map((m) => CashMovementModel.fromMap(m)).toList();
  }

  Future<List<ShiftModel>> getRecentShifts({int limit = 10}) async {
    final db = await instance.database;
    final res = await db.query('shifts', orderBy: 'start_time DESC', limit: limit);
    return res.map((m) => ShiftModel.fromMap(m)).toList();
  }

  // ==========================================
  // ANALITIK & LAPORAN GRAFIK POS MODERN
  // ==========================================

  Future<List<Map<String, dynamic>>> getSalesChartData(int days) async {
    final db = await instance.database;
    final startDate = DateTime.now().subtract(Duration(days: days - 1));
    final startDateStr = "${startDate.year}-${startDate.month.toString().padLeft(2, '0')}-${startDate.day.toString().padLeft(2, '0')}";

    final res = await db.rawQuery('''
      SELECT 
        SUBSTR(created_at, 1, 10) as date,
        SUM(total) as revenue,
        COUNT(id) as count
      FROM sales
      WHERE created_at >= ?
      GROUP BY SUBSTR(created_at, 1, 10)
      ORDER BY date ASC
    ''', [startDateStr]);

    return res.map((row) => {
      'date': row['date'] as String,
      'revenue': (row['revenue'] as num?)?.toDouble() ?? 0.0,
      'count': (row['count'] as num?)?.toInt() ?? 0,
    }).toList();
  }

  Future<List<Map<String, dynamic>>> getTopSellingProducts({int limit = 5}) async {
    final db = await instance.database;
    final res = await db.rawQuery('''
      SELECT 
        product_name,
        SUM(qty) as total_qty,
        SUM(subtotal) as total_revenue
      FROM sale_items
      GROUP BY product_name
      ORDER BY total_qty DESC
      LIMIT ?
    ''', [limit]);

    return res.map((row) => {
      'name': row['product_name'] as String,
      'qty': (row['total_qty'] as num?)?.toInt() ?? 0,
      'revenue': (row['total_revenue'] as num?)?.toDouble() ?? 0.0,
    }).toList();
  }

  Future<List<Map<String, dynamic>>> getPaymentMethodBreakdown() async {
    final db = await instance.database;
    final res = await db.rawQuery('''
      SELECT 
        payment_method,
        COUNT(id) as count,
        SUM(total) as total_amount
      FROM sales
      GROUP BY payment_method
      ORDER BY total_amount DESC
    ''');

    return res.map((row) => {
      'method': row['payment_method'] as String,
      'count': (row['count'] as num?)?.toInt() ?? 0,
      'amount': (row['total_amount'] as num?)?.toDouble() ?? 0.0,
    }).toList();
  }
}
