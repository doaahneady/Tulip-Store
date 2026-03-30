import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import 'package:shared_preferences/shared_preferences.dart';
import '../api/api_client.dart';
import 'dashboard_screen.dart';

class LoginScreen extends StatefulWidget {
  const LoginScreen({super.key});
  @override
  State<LoginScreen> createState() => _LoginScreenState();
}

class _LoginScreenState extends State<LoginScreen> {
  final _username = TextEditingController();
  final _password = TextEditingController();
  final _server = TextEditingController();
  bool _loading = false;
  String? _error;

  @override
  void initState() {
    super.initState();
    _loadPrefs();
  }

  Future<void> _loadPrefs() async {
    final prefs = await SharedPreferences.getInstance();
    _server.text = prefs.getString('server_base_url') ?? 'https://tulip-os.com';
    _username.text = prefs.getString('last_username') ?? prefs.getString('last_email') ?? '';
    final token = prefs.getString('auth_token');
    if (token != null && mounted) {
      final api = context.read<ApiClient>();
      api.baseUrl = _server.text;
      await api.setToken(token);
      if (!mounted) return;
      Navigator.of(context).pushReplacement(MaterialPageRoute<void>(builder: (_) => const DashboardScreen()));
    }
  }

  Future<void> _login() async {
    setState(() {
      _loading = true;
      _error = null;
    });
    try {
      final api = context.read<ApiClient>();
      final navigator = Navigator.of(context);
      final prefs = await SharedPreferences.getInstance();
      api.baseUrl = _server.text.trim();
      await api.login(username: _username.text.trim(), password: _password.text);
      await prefs.setString('server_base_url', api.baseUrl);
      await prefs.setString('last_username', _username.text.trim());
      if (!mounted) return;
      navigator.pushReplacement(MaterialPageRoute<void>(builder: (_) => const DashboardScreen()));
    } catch (e) {
      setState(() {
        _error = e.toString();
      });
    } finally {
      if (mounted) {
        setState(() {
          _loading = false;
        });
      }
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: Center(
        child: SingleChildScrollView(
          padding: const EdgeInsets.all(24),
          child: ConstrainedBox(
            constraints: const BoxConstraints(maxWidth: 420),
            child: Column(
              mainAxisSize: MainAxisSize.min,
              children: [
                const Text('Tulip Driver', style: TextStyle(fontSize: 28, fontWeight: FontWeight.w700)),
                const SizedBox(height: 8),
                const Text('Sign in to view your deliveries'),
                const SizedBox(height: 24),
                TextField(
                  controller: _server,
                  decoration: const InputDecoration(labelText: 'Server URL', border: OutlineInputBorder()),
                  keyboardType: TextInputType.url,
                ),
                const SizedBox(height: 16),
                TextField(
                  controller: _username,
                  decoration: const InputDecoration(
                    labelText: 'Username or Email',
                    helperText: 'Example: Ahmad1 or ahmad@example.com',
                    border: OutlineInputBorder(),
                  ),
                  keyboardType: TextInputType.text,
                ),
                const SizedBox(height: 16),
                TextField(
                  controller: _password,
                  decoration: const InputDecoration(labelText: 'Password', border: OutlineInputBorder()),
                  obscureText: true,
                ),
                const SizedBox(height: 16),
                if (_error != null) Text(_error!, style: const TextStyle(color: Colors.red)),
                const SizedBox(height: 8),
                SizedBox(
                  width: double.infinity,
                  child: FilledButton(
                    onPressed: _loading ? null : _login,
                    child: _loading ? const SizedBox(width: 20, height: 20, child: CircularProgressIndicator(strokeWidth: 2)) : const Text('Login'),
                  ),
                ),
              ],
            ),
          ),
        ),
      ),
    );
  }
}
