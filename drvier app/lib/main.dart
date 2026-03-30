import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import 'package:shared_preferences/shared_preferences.dart';
import 'api/api_client.dart';
import 'screens/login_screen.dart';

Future<void> main() async {
  WidgetsFlutterBinding.ensureInitialized();
  final prefs = await SharedPreferences.getInstance();
  final base = prefs.getString('server_base_url') ?? 'http://10.0.2.2:8000';
  final token = prefs.getString('auth_token');
  runApp(App(baseUrl: base, token: token));
}

class App extends StatelessWidget {
  final String baseUrl;
  final String? token;
  const App({super.key, required this.baseUrl, this.token});
  @override
  Widget build(BuildContext context) {
    return Provider<ApiClient>(
      create: (_) => ApiClient(baseUrl: baseUrl, token: token),
      child: MaterialApp(
        debugShowCheckedModeBanner: false,
        theme: ThemeData(
          colorScheme: ColorScheme.fromSeed(seedColor: const Color(0xFF6C5CE7)),
          useMaterial3: true,
        ),
        home: const LoginScreen(),
      ),
    );
  }
}
