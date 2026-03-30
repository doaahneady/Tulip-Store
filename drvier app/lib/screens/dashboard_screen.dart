import 'package:flutter/material.dart';
import 'package:intl/intl.dart';
import 'package:provider/provider.dart';
import '../api/api_client.dart';
import '../models/assignment.dart';
import 'login_screen.dart';

class DashboardScreen extends StatefulWidget {
  const DashboardScreen({super.key});
  @override
  State<DashboardScreen> createState() => _DashboardScreenState();
}

class _DashboardScreenState extends State<DashboardScreen> {
  List<Assignment> _assignments = [];
  bool _loading = true;
  String? _statusFilter;
  String? _error;
  final _manualAssignmentId = TextEditingController();

  @override
  void initState() {
    super.initState();
    _load();
  }

  Future<void> _load() async {
    setState(() {
      _loading = true;
      _error = null;
    });
    try {
      final api = context.read<ApiClient>();
      final raw = await api.getAssignments(status: _statusFilter);
      setState(() {
        _assignments = raw.map((e) => Assignment.fromJson(Map<String, dynamic>.from(e as Map))).toList();
      });
    } catch (e) {
      setState(() {
        _error = e.toString();
      });
    } finally {
      setState(() {
        _loading = false;
      });
    }
  }

  Future<void> _action(String kind, Assignment a) async {
    try {
      final api = context.read<ApiClient>();
      if (kind == 'pickup') {
        await api.pickup(a.id);
      } else if (kind == 'in_transit') {
        await api.inTransit(a.id);
      } else if (kind == 'deliver') {
        await api.deliver(a.id);
      } else if (kind == 'failed') {
        final reason = await _askText('Failure reason');
        if (reason == null || reason.isEmpty) return;
        await api.failed(a.id, reason: reason);
      }
      await _load();
      if (mounted) {
        ScaffoldMessenger.of(context).showSnackBar(SnackBar(content: Text('Updated: ${a.id}')));
      }
    } catch (e) {
      if (mounted) {
        ScaffoldMessenger.of(context).showSnackBar(SnackBar(content: Text(e.toString())));
      }
    }
  }

  Future<String?> _askText(String label) async {
    final c = TextEditingController();
    return showDialog<String>(
      context: context,
      builder: (_) => AlertDialog(
        title: Text(label),
        content: TextField(controller: c, decoration: InputDecoration(hintText: label)),
        actions: [
          TextButton(onPressed: () => Navigator.pop(context), child: const Text('Cancel')),
          TextButton(onPressed: () => Navigator.pop(context, c.text.trim()), child: const Text('OK')),
        ],
      ),
    );
  }

  Future<void> _logout() async {
    final api = context.read<ApiClient>();
    await api.setToken(null);
    if (!mounted) return;
    Navigator.of(context).pushAndRemoveUntil(
      MaterialPageRoute<void>(builder: (_) => const LoginScreen()),
      (r) => false,
    );
  }

  @override
  Widget build(BuildContext context) {
    final df = DateFormat('y-MM-dd HH:mm');
    return Scaffold(
      appBar: AppBar(
        title: const Text('My Deliveries'),
        actions: [
          PopupMenuButton<String>(
            onSelected: (v) {
              setState(() {
                _statusFilter = v == 'all' ? null : v;
              });
              _load();
            },
            itemBuilder: (_) => const [
              PopupMenuItem(value: 'all', child: Text('All')),
              PopupMenuItem(value: 'assigned', child: Text('Assigned')),
              PopupMenuItem(value: 'picked_up', child: Text('Picked Up')),
              PopupMenuItem(value: 'in_transit', child: Text('In Transit')),
              PopupMenuItem(value: 'delivered', child: Text('Delivered')),
              PopupMenuItem(value: 'failed', child: Text('Failed')),
            ],
            icon: const Icon(Icons.filter_alt),
          ),
          IconButton(onPressed: _load, icon: const Icon(Icons.refresh)),
          IconButton(onPressed: _logout, icon: const Icon(Icons.logout)),
        ],
      ),
      body: _loading
          ? const Center(child: CircularProgressIndicator())
          : _error != null
              ? _buildErrorBody()
              : RefreshIndicator(
                  onRefresh: _load,
                  child: ListView.builder(
                    itemCount: _assignments.length,
                    itemBuilder: (_, i) {
                      final a = _assignments[i];
                      final o = a.order ?? {};
                      final title = o['order_number']?.toString() ?? 'Order ${a.id}';
                      final subtitle = [
                        o['recipient_name']?.toString(),
                        o['phone']?.toString(),
                        o['shipping_address']?.toString() ?? o['address_note']?.toString()
                      ].where((e) => e != null && e.toString().isNotEmpty).join(' • ');
                      return Card(
                        margin: const EdgeInsets.symmetric(horizontal: 12, vertical: 8),
                        child: Padding(
                          padding: const EdgeInsets.all(12),
                          child: Column(
                            crossAxisAlignment: CrossAxisAlignment.start,
                            children: [
                              Row(
                                mainAxisAlignment: MainAxisAlignment.spaceBetween,
                                children: [
                                  Expanded(child: Text(title, style: const TextStyle(fontSize: 16, fontWeight: FontWeight.w600))),
                                  Container(
                                    padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 4),
                                    decoration: BoxDecoration(
                                      color: Colors.blueGrey.shade50,
                                      borderRadius: BorderRadius.circular(8),
                                    ),
                                    child: Text(a.status),
                                  ),
                                ],
                              ),
                              const SizedBox(height: 6),
                              if (subtitle.isNotEmpty) Text(subtitle),
                              const SizedBox(height: 6),
                              if (a.assignedAt != null) Text('Assigned: ${df.format(a.assignedAt!)}', style: const TextStyle(fontSize: 12, color: Colors.grey)),
                              const SizedBox(height: 12),
                              Row(
                                children: [
                                  FilledButton.tonal(onPressed: () => _action('pickup', a), child: const Text('Picked Up')),
                                  const SizedBox(width: 8),
                                  FilledButton.tonal(onPressed: () => _action('in_transit', a), child: const Text('In Transit')),
                                  const SizedBox(width: 8),
                                  FilledButton(onPressed: () => _action('deliver', a), child: const Text('Delivered')),
                                  const SizedBox(width: 8),
                                  OutlinedButton(onPressed: () => _action('failed', a), child: const Text('Failed')),
                                ],
                              ),
                            ],
                          ),
                        ),
                      );
                    },
                  ),
                ),
      floatingActionButton: FloatingActionButton.extended(
        onPressed: () async {
          final api = context.read<ApiClient>();
          final messenger = ScaffoldMessenger.of(context);
          await api.completeRoute();
          if (!context.mounted) return;
          messenger.showSnackBar(const SnackBar(content: Text('Route completed')));
          _load();
        },
        label: const Text('Complete Route'),
        icon: const Icon(Icons.flag),
      ),
    );
  }

  Widget _buildErrorBody() {
    final msg = _error ?? '';
    if (msg.contains('Server API not updated: missing /api/delivery/assignments')) {
      return Padding(
        padding: const EdgeInsets.all(16),
        child: ListView(
          children: [
            const Text(
              'Orders list is not available on this server yet.',
              style: TextStyle(fontSize: 16, fontWeight: FontWeight.w600),
            ),
            const SizedBox(height: 8),
            const Text('You can still update an order if you have the Assignment ID.'),
            const SizedBox(height: 16),
            TextField(
              controller: _manualAssignmentId,
              decoration: const InputDecoration(
                labelText: 'Assignment ID',
                border: OutlineInputBorder(),
              ),
              keyboardType: TextInputType.number,
            ),
            const SizedBox(height: 12),
            Wrap(
              spacing: 8,
              runSpacing: 8,
              children: [
                FilledButton.tonal(onPressed: () => _manualAction('pickup'), child: const Text('Picked Up')),
                FilledButton.tonal(onPressed: () => _manualAction('in_transit'), child: const Text('In Transit')),
                FilledButton(onPressed: () => _manualAction('deliver'), child: const Text('Delivered')),
                OutlinedButton(onPressed: () => _manualAction('failed'), child: const Text('Failed')),
              ],
            ),
            const SizedBox(height: 16),
            SizedBox(
              width: double.infinity,
              child: OutlinedButton.icon(
                onPressed: _load,
                icon: const Icon(Icons.refresh),
                label: const Text('Retry loading orders'),
              ),
            ),
          ],
        ),
      );
    }
    return Center(child: Text(msg));
  }

  Future<void> _manualAction(String kind) async {
    final raw = _manualAssignmentId.text.trim();
    final id = int.tryParse(raw);
    if (id == null) {
      if (!mounted) return;
      ScaffoldMessenger.of(context).showSnackBar(const SnackBar(content: Text('Enter a valid Assignment ID')));
      return;
    }
    try {
      final api = context.read<ApiClient>();
      if (kind == 'pickup') {
        await api.pickup(id);
      } else if (kind == 'in_transit') {
        await api.inTransit(id);
      } else if (kind == 'deliver') {
        await api.deliver(id);
      } else if (kind == 'failed') {
        final reason = await _askText('Failure reason');
        if (reason == null || reason.isEmpty) return;
        await api.failed(id, reason: reason);
      }
      if (!mounted) return;
      ScaffoldMessenger.of(context).showSnackBar(const SnackBar(content: Text('Updated')));
    } catch (e) {
      if (!mounted) return;
      ScaffoldMessenger.of(context).showSnackBar(SnackBar(content: Text(e.toString())));
    }
  }
}
