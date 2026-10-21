import 'package:flutter_test/flutter_test.dart';
import 'package:superpos_mobile/main.dart';

void main() {
  testWidgets('SuperPOS App smoke test', (WidgetTester tester) async {
    await tester.pumpWidget(const SuperPosApp());
    expect(find.byType(SuperPosApp), findsOneWidget);
  });
}
