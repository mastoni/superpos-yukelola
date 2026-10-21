import 'package:flutter/material.dart';
import 'screens/main_shell_screen.dart';

void main() async {
  WidgetsFlutterBinding.ensureInitialized();
  runApp(const YUKelolaApp());
}

class YUKelolaApp extends StatelessWidget {
  const YUKelolaApp({super.key});

  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      title: 'YUKelola',
      debugShowCheckedModeBanner: false,
      theme: ThemeData(
        useMaterial3: true,
        scaffoldBackgroundColor: const Color(0xFFF8FAFC), // tokens.css: --bg-page
        colorScheme: ColorScheme.fromSeed(
          seedColor: const Color(0xFF059669), // tokens.css: --accent #059669
          primary: const Color(0xFF059669),
          secondary: const Color(0xFF0F172A), // tokens.css: --text-primary #0F172A
          surface: Colors.white,
        ),
        appBarTheme: const AppBarTheme(
          elevation: 0,
          centerTitle: false,
          backgroundColor: Color(0xFF0F172A),
          foregroundColor: Colors.white,
        ),
        cardTheme: CardThemeData(
          color: Colors.white,
          elevation: 1.5,
          shape: RoundedRectangleBorder(
            borderRadius: BorderRadius.circular(10),
            side: const BorderSide(color: Color(0xFFE2E8F0)),
          ),
        ),
      ),
      home: const MainShellScreen(),
    );
  }
}
