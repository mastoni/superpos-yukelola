import 'package:flutter/material.dart';
import 'dashboard_screen.dart';
import 'pos_cashier_screen.dart';
import 'products_screen.dart';
import 'shift_screen.dart';
import 'reports_screen.dart';

class MainShellScreen extends StatefulWidget {
  final int initialIndex;
  const MainShellScreen({super.key, this.initialIndex = 0});

  @override
  State<MainShellScreen> createState() => _MainShellScreenState();
}

class _MainShellScreenState extends State<MainShellScreen> {
  late int _currentIndex;

  final List<Widget> _screens = const [
    DashboardScreen(),
    PosCashierScreen(),
    ProductsScreen(),
    ShiftScreen(),
    ReportsScreen(),
  ];

  @override
  void initState() {
    super.initState();
    _currentIndex = widget.initialIndex;
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: IndexedStack(
        index: _currentIndex,
        children: _screens,
      ),
      bottomNavigationBar: Container(
        decoration: BoxDecoration(
          color: Colors.white,
          boxShadow: [
            BoxShadow(
              color: Colors.black.withOpacity(0.06),
              blurRadius: 10,
              offset: const Offset(0, -2),
            ),
          ],
          border: const Border(
            top: BorderSide(color: Color(0xFFE2E8F0), width: 1),
          ),
        ),
        child: SafeArea(
          child: NavigationBar(
            height: 65,
            elevation: 0,
            backgroundColor: Colors.white,
            selectedIndex: _currentIndex,
            indicatorColor: const Color(0xFF059669).withOpacity(0.12),
            onDestinationSelected: (index) {
              setState(() => _currentIndex = index);
            },
            destinations: const [
              NavigationDestination(
                icon: Icon(Icons.dashboard_outlined, color: Color(0xFF64748B)),
                selectedIcon: Icon(Icons.dashboard, color: Color(0xFF059669)),
                label: 'Dashboard',
              ),
              NavigationDestination(
                icon: Icon(Icons.point_of_sale_outlined, color: Color(0xFF64748B)),
                selectedIcon: Icon(Icons.point_of_sale, color: Color(0xFF059669)),
                label: 'Kasir POS',
              ),
              NavigationDestination(
                icon: Icon(Icons.inventory_2_outlined, color: Color(0xFF64748B)),
                selectedIcon: Icon(Icons.inventory_2, color: Color(0xFF059669)),
                label: 'Katalog',
              ),
              NavigationDestination(
                icon: Icon(Icons.account_balance_wallet_outlined, color: Color(0xFF64748B)),
                selectedIcon: Icon(Icons.account_balance_wallet, color: Color(0xFF059669)),
                label: 'Laci Kas',
              ),
              NavigationDestination(
                icon: Icon(Icons.bar_chart_outlined, color: Color(0xFF64748B)),
                selectedIcon: Icon(Icons.bar_chart, color: Color(0xFF059669)),
                label: 'Analitik',
              ),
            ],
          ),
        ),
      ),
    );
  }
}
