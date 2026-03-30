class Assignment {
  final int id;
  final String status;
  final DateTime? assignedAt;
  final Map<String, dynamic>? order;
  Assignment({required this.id, required this.status, this.assignedAt, this.order});
  factory Assignment.fromJson(Map<String, dynamic> j) {
    return Assignment(
      id: j['id'] as int,
      status: j['status'] as String,
      assignedAt: j['assigned_at'] != null ? DateTime.tryParse(j['assigned_at'].toString()) : null,
      order: j['order'] != null ? Map<String, dynamic>.from(j['order'] as Map) : null,
    );
  }
}
