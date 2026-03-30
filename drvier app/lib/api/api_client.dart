import 'dart:convert';
import 'package:http/http.dart' as http;
import 'package:shared_preferences/shared_preferences.dart';

class ApiClient {
  String baseUrl;
  String? token;
  ApiClient({required this.baseUrl, this.token});

  String get _normalizedBaseUrl => baseUrl.replaceAll(RegExp(r'/+$'), '');

  Uri _uri(String path, {Map<String, String>? queryParameters}) {
    final root = Uri.parse(_normalizedBaseUrl);
    final p = path.startsWith('/') ? path : '/$path';
    return root.replace(path: p, queryParameters: queryParameters);
  }

  Map<String, String> _headers() {
    final h = {'Content-Type': 'application/json', 'Accept': 'application/json'};
    if (token != null && token!.isNotEmpty) {
      h['Authorization'] = 'Bearer $token';
    }
    return h;
  }

  Map<String, String> _formHeaders() {
    final h = {'Accept': 'application/json', 'Content-Type': 'application/x-www-form-urlencoded'};
    if (token != null && token!.isNotEmpty) {
      h['Authorization'] = 'Bearer $token';
    }
    return h;
  }

  dynamic _decodeJson(http.Response res) {
    final body = res.body;
    final trimmed = body.trimLeft();
    if (trimmed.isEmpty) return null;
    if (!trimmed.startsWith('{') && !trimmed.startsWith('[')) {
      throw Exception('Unexpected response (HTTP ${res.statusCode}). Check Server URL and API path.');
    }
    return jsonDecode(body);
  }

  Future<void> setToken(String? t) async {
    token = t;
    final prefs = await SharedPreferences.getInstance();
    if (t == null) {
      await prefs.remove('auth_token');
    } else {
      await prefs.setString('auth_token', t);
    }
  }

  Future<Map<String, dynamic>> login({required String username, required String password}) async {
    final identifier = username.trim();
    final isEmail = identifier.contains('@');
    Map<String, dynamic> parseError(Map<String, dynamic> data) {
      final msg = (data['message'] ?? 'Login failed').toString();
      final errors = data['errors'];
      if (errors is Map) {
        final pairs = errors.entries
            .map((e) => '${e.key}: ${(e.value is List ? (e.value as List).join(', ') : e.value)}')
            .join(' | ');
        throw Exception('$msg ($pairs)');
      }
      throw Exception(msg);
    }

    List<String> candidateEmails() {
      if (isEmail) return [identifier];
      final u = identifier.toLowerCase();
      final out = <String>['$u@drivers.local'];
      for (var i = 1; i <= 15; i++) {
        out.add('$u+$i@drivers.local');
      }
      return out;
    }

    try {
      final uri = _uri('/api/auth/login');
      final emails = candidateEmails();
      Map<String, dynamic>? lastData;
      for (final email in emails) {
        final res = await http.post(
          uri,
          headers: _formHeaders(),
          body: {'email': email, 'password': password, 'username': identifier},
        );
        final data = _decodeJson(res) as Map<String, dynamic>;
        lastData = data;
        if (res.statusCode == 200 && data['success'] == true) {
          final t = data['token'] as String;
          await setToken(t);
          return data;
        }
        final msg = (data['message'] ?? '').toString().toLowerCase();
        if (res.statusCode == 403 && (data['requires_verification'] == true || msg.contains('verify'))) {
          throw Exception('Please verify this account email before logging in.');
        }
        if (res.statusCode != 401 || !msg.contains('invalid')) {
          return parseError(data);
        }
      }
      if (lastData != null) {
        throw Exception('fallback_to_employee');
      }
      throw Exception('fallback_to_employee');
    } catch (e) {
      if (e is Exception && e.toString().contains('fallback_to_employee')) {
        // continue to employee login attempt
      } else if (e is Exception && e.toString().contains('Please verify')) {
        rethrow;
      } else if (e is! Exception) {
        // keep going
      } else {
        // keep going
      }
    }

    final uri = _uri('/api/employee/login');
    final res = await http.post(
      uri,
      headers: _formHeaders(),
      body: {'identifier': identifier, 'username': identifier, 'email': isEmail ? identifier : '${identifier.toLowerCase()}@drivers.local', 'password': password},
    );
    final data = _decodeJson(res) as Map<String, dynamic>;
    if (res.statusCode == 200 && data['success'] == true) {
      final t = data['token'] as String;
      await setToken(t);
      return data;
    }
    final msg = (data['message'] ?? '').toString();
    if (res.statusCode == 404 && msg.contains('api/employee/login')) {
      if (isEmail) {
        throw Exception('Invalid credentials. Check email/password.');
      }
      throw Exception(
        'Invalid credentials.\n'
        '- If you only login at /employee/login, the API /api/employee/login must be deployed to your server.\n'
        '- Or enter the driver email (not username) in this field.\n'
        '- Tried emails: ${candidateEmails().join(', ')}',
      );
    }
    return parseError(data);
  }

  Future<List<dynamic>> getAssignments({String? status}) async {
    final uri = _uri('/api/delivery/assignments', queryParameters: status != null ? {'status': status} : null);
    final res = await http.get(uri, headers: _headers());
    final data = _decodeJson(res) as Map<String, dynamic>;
    if (res.statusCode == 404) {
      final msg = (data['message'] ?? '').toString();
      if (msg.contains('api/delivery/assignments') || msg.toLowerCase().contains('could not be found')) {
        throw Exception('Server API not updated: missing /api/delivery/assignments');
      }
    }
    if (res.statusCode == 200 && data['success'] == true) {
      return (data['assignments'] as List<dynamic>);
    }
    throw Exception(data['message'] ?? 'Failed to load assignments');
  }

  Future<Map<String, dynamic>> pickup(int assignmentId, {String? notes}) async {
    final uri = _uri('/api/delivery/assignments/$assignmentId/pickup');
    final res = await http.post(uri, headers: _headers(), body: jsonEncode({'notes': notes}));
    final data = _decodeJson(res) as Map<String, dynamic>;
    if (res.statusCode == 200 && data['success'] == true) {
      return data;
    }
    throw Exception(data['message'] ?? 'Failed to update pickup');
  }

  Future<Map<String, dynamic>> inTransit(int assignmentId) async {
    final uri = _uri('/api/delivery/assignments/$assignmentId/in-transit');
    final res = await http.post(uri, headers: _headers());
    final data = _decodeJson(res) as Map<String, dynamic>;
    if (res.statusCode == 200 && data['success'] == true) {
      return data;
    }
    throw Exception(data['message'] ?? 'Failed to update in transit');
  }

  Future<Map<String, dynamic>> deliver(int assignmentId, {String? signature, String? notes}) async {
    final uri = _uri('/api/delivery/assignments/$assignmentId/deliver');
    final res = await http.post(uri, headers: _headers(), body: jsonEncode({'signature': signature, 'notes': notes}));
    final data = _decodeJson(res) as Map<String, dynamic>;
    if (res.statusCode == 200 && data['success'] == true) {
      return data;
    }
    throw Exception(data['message'] ?? 'Failed to deliver');
  }

  Future<Map<String, dynamic>> failed(int assignmentId, {required String reason, String? notes}) async {
    final uri = _uri('/api/delivery/assignments/$assignmentId/failed');
    final res = await http.post(uri, headers: _headers(), body: jsonEncode({'failure_reason': reason, 'notes': notes}));
    final data = _decodeJson(res) as Map<String, dynamic>;
    if (res.statusCode == 200 && data['success'] == true) {
      return data;
    }
    throw Exception(data['message'] ?? 'Failed to mark failed');
  }

  Future<Map<String, dynamic>> completeRoute({String? routeDate}) async {
    final uri = _uri('/api/delivery/routes/complete');
    final res = await http.post(uri, headers: _headers(), body: jsonEncode({'route_date': routeDate}));
    final data = _decodeJson(res) as Map<String, dynamic>;
    if (res.statusCode == 200 && data['success'] == true) {
      return data;
    }
    throw Exception(data['message'] ?? 'Failed to complete route');
  }
}
