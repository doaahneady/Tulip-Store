<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>لوحة خدمة العملاء - Tulip Store</title>
<link rel="stylesheet" href="/css/store.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.min.js"></script>
<style>
* {
    font-family: 'Cairo', sans-serif;
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
    min-height: 100vh;
    color: #1e293b;
}

.page-header {
    background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
    padding: 2rem;
    margin-top: 80px;
    box-shadow: 0 4px 20px rgba(59, 130, 246, 0.15);
}

.header-content {
    max-width: 1400px;
    margin: 0 auto;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.header-title h1 {
    color: white;
    font-size: 2.5rem;
    font-weight: 800;
    margin-bottom: 0.5rem;
}

.header-title p {
    color: rgba(255, 255, 255, 0.9);
    font-size: 1.1rem;
    font-weight: 500;
}

.header-actions {
    display: flex;
    gap: 1rem;
}

.btn-header {
    background: rgba(255, 255, 255, 0.15);
    color: white;
    padding: 0.8rem 1.5rem;
    border-radius: 12px;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s;
    border: 1px solid rgba(255, 255, 255, 0.2);
    display: flex;
    align-items: center;
    gap: 0.5rem;
    backdrop-filter: blur(10px);
}

.btn-header:hover {
    background: rgba(255, 255, 255, 0.25);
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
}

.container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 2rem;
}

/* Quick Actions */
.quick-actions {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
    margin-bottom: 2rem;
}

.quick-action {
    background: white;
    padding: 1.5rem;
    border-radius: 16px;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s;
    border: 1px solid #e2e8f0;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
}

.quick-action:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 25px rgba(59, 130, 246, 0.15);
    border-color: #3b82f6;
}

.quick-action i {
    font-size: 2rem;
    color: #3b82f6;
    margin-bottom: 1rem;
}

.quick-action span {
    font-weight: 700;
    color: #1e293b;
    font-size: 0.95rem;
}

/* Statistics Grid */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.stat-card {
    background: white;
    padding: 2rem;
    border-radius: 16px;
    border: 1px solid #e2e8f0;
    transition: all 0.3s;
    position: relative;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
}

.stat-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, #3b82f6, #8b5cf6);
}

.stat-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 40px rgba(59, 130, 246, 0.15);
    border-color: #3b82f6;
}

.stat-icon {
    width: 60px;
    height: 60px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    margin-bottom: 1rem;
    background: linear-gradient(135deg, #3b82f6, #8b5cf6);
    color: white;
    box-shadow: 0 4px 15px rgba(59, 130, 246, 0.3);
}

.stat-value {
    font-size: 2.5rem;
    font-weight: 800;
    color: #1e293b;
    margin: 0.5rem 0;
}

.stat-label {
    color: #64748b;
    font-size: 0.9rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.stat-trend {
    font-size: 0.85rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 0.4rem;
    padding: 0.3rem 0.8rem;
    border-radius: 20px;
    background: #f1f5f9;
    color: #64748b;
    width: fit-content;
}

.trend-up {
    background: #dcfce7;
    color: #16a34a;
}

.trend-down {
    background: #fee2e2;
    color: #dc2626;
}

.trend-neutral {
    background: #f3f4f6;
    color: #6b7280;
}

/* Content Cards */
.content-card {
    background: white;
    border-radius: 16px;
    border: 1px solid #e2e8f0;
    overflow: hidden;
    margin-bottom: 2rem;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
}

.card-header {
    background: #f8fafc;
    padding: 1.5rem 2rem;
    border-bottom: 1px solid #e2e8f0;
}

.card-title {
    font-size: 1.25rem;
    font-weight: 700;
    color: #1e293b;
    display: flex;
    align-items: center;
    gap: 0.8rem;
}

.card-title i {
    color: #3b82f6;
    font-size: 1.3rem;
}

/* Performance Grid */
.performance-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.performance-card {
    background: white;
    padding: 2rem;
    border-radius: 16px;
    border: 1px solid #e2e8f0;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    transition: all 0.3s;
}

.performance-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(59, 130, 246, 0.1);
}

.performance-card h3 {
    font-size: 1.1rem;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    gap: 0.6rem;
}

.performance-card h3 i {
    color: #3b82f6;
}

.metric-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.8rem 0;
    border-bottom: 1px solid #f1f5f9;
}

.metric-row:last-child {
    border-bottom: none;
}

.metric-label {
    color: #64748b;
    font-weight: 500;
    font-size: 0.9rem;
}

.metric-value {
    color: #1e293b;
    font-weight: 700;
    font-size: 1rem;
}

/* Rating Display */
.rating-display {
    text-align: center;
    margin: 1.5rem 0;
    padding: 1.5rem;
    background: #f8fafc;
    border-radius: 12px;
}

.rating-number {
    font-size: 3rem;
    font-weight: 800;
    color: #3b82f6;
    margin-bottom: 0.5rem;
}

.rating-stars {
    color: #fbbf24;
    font-size: 1.5rem;
    margin-bottom: 0.8rem;
}

.rating-count {
    color: #64748b;
    font-size: 0.9rem;
}

/* Progress Circle */
.progress-circle {
    width: 100px;
    height: 100px;
    position: relative;
    margin: 1rem auto;
}

.progress-circle svg {
    transform: rotate(-90deg);
}

.progress-circle-bg {
    fill: none;
    stroke: #e2e8f0;
    stroke-width: 8;
}

.progress-circle-fill {
    fill: none;
    stroke: #3b82f6;
    stroke-width: 8;
    stroke-linecap: round;
    transition: stroke-dashoffset 0.8s ease;
}

.progress-text {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    font-size: 1.2rem;
    font-weight: 700;
    color: #3b82f6;
}

/* Status Badges */
.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    padding: 0.4rem 0.8rem;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.3px;
}

.status-open {
    background: #fef3c7;
    color: #d97706;
}

.status-in_progress {
    background: #dbeafe;
    color: #2563eb;
}

.status-waiting_customer {
    background: #fee2e2;
    color: #dc2626;
}

.status-resolved {
    background: #dcfce7;
    color: #16a34a;
}

.status-closed {
    background: #f3f4f6;
    color: #6b7280;
}

.priority-low {
    background: #dbeafe;
    color: #2563eb;
}

.priority-medium {
    background: #fef3c7;
    color: #d97706;
}

.priority-high {
    background: #fee2e2;
    color: #dc2626;
}

.priority-urgent {
    background: #fce7f3;
    color: #be185d;
}

/* Tables */
table {
    width: 100%;
    border-collapse: collapse;
}

th {
    background: #f8fafc;
    padding: 1rem;
    text-align: right;
    font-weight: 700;
    color: #374151;
    font-size: 0.85rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    border-bottom: 1px solid #e2e8f0;
}

td {
    padding: 1rem;
    border-bottom: 1px solid #f1f5f9;
    color: #1e293b;
    font-weight: 500;
}

tbody tr {
    transition: all 0.2s;
}

tbody tr:hover {
    background: #f8fafc;
}

/* Action Buttons */
.action-btn {
    background: linear-gradient(135deg, #3b82f6, #8b5cf6);
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 8px;
    border: none;
    cursor: pointer;
    font-weight: 600;
    transition: all 0.3s;
    font-size: 0.8rem;
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
}

.action-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(59, 130, 246, 0.3);
}

/* Activity Items */
.activity-item {
    padding: 1.2rem;
    background: #f8fafc;
    border-radius: 12px;
    margin-bottom: 1rem;
    border-left: 4px solid #3b82f6;
    transition: all 0.3s;
    display: flex;
    align-items: center;
    gap: 1rem;
}

.activity-item:hover {
    background: #f1f5f9;
    transform: translateX(-4px);
}

.activity-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1rem;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
}

.activity-content {
    flex: 1;
}

.activity-title {
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 0.2rem;
    font-size: 0.9rem;
}

.activity-meta {
    color: #64748b;
    font-size: 0.8rem;
}

/* Chart Containers */
.chart-container {
    background: white;
    padding: 2rem;
    border-radius: 16px;
    border: 1px solid #e2e8f0;
    margin-bottom: 2rem;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
}

.chart-title {
    font-size: 1.2rem;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    gap: 0.6rem;
}

.chart-title i {
    color: #3b82f6;
}

/* Notification */
.notification {
    position: fixed;
    top: 100px;
    left: 50%;
    transform: translateX(-50%);
    background: #10b981;
    color: white;
    padding: 1rem 2rem;
    border-radius: 12px;
    box-shadow: 0 8px 25px rgba(16, 185, 129, 0.3);
    z-index: 10000;
    font-weight: 600;
    display: none;
    align-items: center;
    gap: 0.6rem;
}

.notification.error {
    background: #ef4444;
    box-shadow: 0 8px 25px rgba(239, 68, 68, 0.3);
}

.notification.show {
    display: flex;
    animation: slideDown 0.4s ease-out;
}

@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateX(-50%) translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateX(-50%) translateY(0);
    }
}

/* Responsive Design */
@media (max-width: 1024px) {
    .performance-grid {
        grid-template-columns: 1fr;
    }
    .stats-grid {
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    }
}

@media (max-width: 768px) {
    .header-content {
        flex-direction: column;
        text-align: center;
        gap: 1rem;
    }
    .stats-grid {
        grid-template-columns: 1fr;
    }
    .header-title h1 {
        font-size: 2rem;
    }
    .container {
        padding: 1rem;
    }
}
</style>
</head>
<body>
@include('components.navbar')

<section class="page-header">
<div class="header-content">
<div class="header-title">
<h1><i class="fas fa-headset"></i> لوحة خدمة العملاء</h1>
<p>إدارة احترافية شاملة لخدمة العملاء والدعم الفني</p>
</div>
<div class="header-actions">
<a href="/cs/tickets/create" class="btn-header">
<i class="fas fa-plus"></i> تذكرة جديدة
</a>
<a href="{{ route('admin.dashboard') }}" class="btn-header">
<i class="fas fa-arrow-right"></i> العودة
</a>
</div>
</div>
</section>

<div class="container">
<!-- Quick Actions -->
<div class="quick-actions">
<div class="quick-action" onclick="location.href='{{ route('cs.tickets.index', ['status' => 'open']) }}'">
<i class="fas fa-folder-open"></i>
<span>التذاكر المفتوحة</span>
</div>
<div class="quick-action" onclick="location.href='{{ route('cs.tickets.index', ['priority' => 'urgent']) }}'">
<i class="fas fa-exclamation-triangle"></i>
<span>التذاكر العاجلة</span>
</div>
<div class="quick-action" onclick="location.href='{{ route('cs.feedback.index') }}'">
<i class="fas fa-comments"></i>
<span>آراء العملاء</span>
</div>
<div class="quick-action" onclick="location.href='{{ route('cs.reports') }}'">
<i class="fas fa-chart-bar"></i>
<span>التقارير</span>
</div>
</div>

<!-- Statistics Grid -->
<div class="stats-grid">
<div class="stat-card">
<div class="stat-icon"><i class="fas fa-ticket-alt"></i></div>
<div class="stat-value">{{ $totalTickets }}</div>
<div class="stat-label">إجمالي التذاكر</div>
<div class="stat-trend trend-up">
<i class="fas fa-arrow-up"></i> +{{ $ticketsCreatedToday }} اليوم
</div>
</div>
<div class="stat-card">
<div class="stat-icon"><i class="fas fa-folder-open"></i></div>
<div class="stat-value">{{ $openTickets }}</div>
<div class="stat-label">التذاكر المفتوحة</div>
<div class="stat-trend trend-neutral">
<i class="fas fa-clock"></i> {{ $urgentTickets }} عاجل
</div>
</div>
<div class="stat-card">
<div class="stat-icon"><i class="fas fa-cogs"></i></div>
<div class="stat-value">{{ $inProgressTickets }}</div>
<div class="stat-label">قيد المعالجة</div>
<div class="stat-trend trend-up">
<i class="fas fa-arrow-up"></i> {{ $waitingCustomer }} انتظار العميل
</div>
</div>
<div class="stat-card">
<div class="stat-icon"><i class="fas fa-check-circle"></i></div>
<div class="stat-value">{{ $resolvedToday }}</div>
<div class="stat-label">تم حلها اليوم</div>
<div class="stat-trend trend-up">
<i class="fas fa-arrow-up"></i> {{ $resolvedThisWeek }} هذا الأسبوع
</div>
</div>
<div class="stat-card">
<div class="stat-icon"><i class="fas fa-clock"></i></div>
<div class="stat-value">{{ $avgFirstResponseTime }}</div>
<div class="stat-label">متوسط وقت الرد</div>
<div class="stat-trend trend-up">
<i class="fas fa-tachometer-alt"></i> {{ $responseRate }}% معدل الرد
</div>
</div>
<div class="stat-card">
<div class="stat-icon"><i class="fas fa-star"></i></div>
<div class="stat-value">{{ $avgSatisfaction }}/5</div>
<div class="stat-label">رضا العملاء</div>
<div class="stat-trend trend-up">
<i class="fas fa-heart"></i> {{ $satisfactionCount }} تقييم
</div>
</div>
</div>

<!-- Performance Metrics -->
<div class="performance-grid">
<div class="performance-card">
<h3><i class="fas fa-chart-line"></i> مقاييس الأداء</h3>
<div class="metric-row">
<span class="metric-label">معدل الحل</span>
<span class="metric-value">{{ $resolutionRate }}%</span>
</div>
<div class="metric-row">
<span class="metric-label">معدل الرد</span>
<span class="metric-value">{{ $responseRate }}%</span>
</div>
<div class="metric-row">
<span class="metric-label">متوسط وقت الحل</span>
<span class="metric-value">{{ $avgResolutionTime }}</span>
</div>
<div class="metric-row">
<span class="metric-label">تذاكر هذا الشهر</span>
<span class="metric-value">{{ $ticketsThisMonth }}</span>
</div>
<div class="metric-row">
<span class="metric-label">تم حلها هذا الشهر</span>
<span class="metric-value">{{ $resolvedThisMonth }}</span>
</div>
</div>

<div class="performance-card">
<h3><i class="fas fa-users"></i> رضا العملاء</h3>
<div class="rating-display">
<div class="rating-number">{{ $avgSatisfaction }}</div>
<div class="rating-stars">
@for($i = 1; $i <= 5; $i++)
<i class="fas fa-star{{ $i <= $avgSatisfaction ? '' : '-o' }}"></i>
@endfor
</div>
<div class="rating-count">بناءً على {{ $satisfactionCount }} تقييم</div>
</div>
<div class="progress-circle">
<svg width="100" height="100">
<circle class="progress-circle-bg" cx="50" cy="50" r="40"/>
<circle class="progress-circle-fill" cx="50" cy="50" r="40" 
stroke-dasharray="{{ 2 * pi() * 40 }}" 
stroke-dashoffset="{{ 2 * pi() * 40 * (1 - ($avgSatisfaction / 5)) }}"/>
</svg>
<div class="progress-text">{{ round($avgSatisfaction / 5 * 100) }}%</div>
</div>
</div>

<div class="performance-card">
<h3><i class="fas fa-comments"></i> آراء العملاء</h3>
<div class="metric-row">
<span class="metric-label">المراجعات المعلقة</span>
<span class="metric-value">{{ $pendingFeedback }}</span>
</div>
<div class="metric-row">
<span class="metric-label">متوسط التقييم</span>
<span class="metric-value">{{ $avgRating }}/5</span>
</div>
<div class="metric-row">
<span class="metric-label">آراء اليوم</span>
<span class="metric-value">{{ $feedbackReceivedToday }}</span>
</div>
<div class="metric-row">
<span class="metric-label">الردود اليوم</span>
<span class="metric-value">{{ $repliesCreatedToday }}</span>
</div>
</div>
</div>

<!-- Charts Section -->
<div class="chart-container">
<h3 class="chart-title"><i class="fas fa-chart-area"></i> إحصائيات التذاكر (آخر 7 أيام)</h3>
<div style="position: relative; height: 300px;">
<canvas id="ticketsChart"></canvas>
</div>
</div>

<div class="performance-grid">
<div class="chart-container">
<h3 class="chart-title"><i class="fas fa-chart-pie"></i> التذاكر حسب الحالة</h3>
<div style="position: relative; height: 300px;">
<canvas id="statusChart"></canvas>
</div>
</div>

<div class="chart-container">
<h3 class="chart-title"><i class="fas fa-chart-bar"></i> التذاكر حسب الأولوية</h3>
<div style="position: relative; height: 300px;">
<canvas id="priorityChart"></canvas>
</div>
</div>
</div>

<!-- Recent Tickets Table -->
<div class="content-card">
<div class="card-header">
<h3 class="card-title"><i class="fas fa-list"></i> التذاكر الحديثة</h3>
</div>
<div style="overflow-x: auto;">
<table>
<thead>
<tr>
<th>رقم التذكرة</th>
<th>الموضوع</th>
<th>العميل</th>
<th>الحالة</th>
<th>الأولوية</th>
<th>تاريخ الإنشاء</th>
<th>الإجراءات</th>
</tr>
</thead>
<tbody>
@foreach($recentTickets as $ticket)
<tr>
<td><strong>#{{ $ticket->id }}</strong></td>
<td>{{ $ticket->subject }}</td>
<td>{{ $ticket->user->name }}</td>
<td>
<span class="status-badge status-{{ $ticket->status }}">
<i class="fas fa-circle"></i>
{{ ucfirst(str_replace('_', ' ', $ticket->status)) }}
</span>
</td>
<td>
<span class="status-badge priority-{{ $ticket->priority }}">
<i class="fas fa-flag"></i>
{{ ucfirst($ticket->priority) }}
</span>
</td>
<td>{{ $ticket->created_at->diffForHumans() }}</td>
<td>
<button class="action-btn" onclick="location.href='{{ route('cs.tickets.show', $ticket->id) }}'">
<i class="fas fa-eye"></i> عرض
</button>
</td>
</tr>
@endforeach
</tbody>
</table>
</div>
</div>

<!-- Recent Activity -->
<div class="content-card">
<div class="card-header">
<h3 class="card-title"><i class="fas fa-history"></i> النشاط الحديث</h3>
</div>
<div style="padding: 1.5rem;">
@foreach($recentActivity as $activity)
<div class="activity-item">
<div class="activity-icon" style="background: {{ $activity['color'] }};">
<i class="fas fa-{{ $activity['icon'] }}"></i>
</div>
<div class="activity-content">
<div class="activity-title">{{ $activity['message'] }}</div>
<div class="activity-meta">{{ $activity['user'] }} • {{ \Carbon\Carbon::parse($activity['time'])->diffForHumans() }}</div>
</div>
</div>
@endforeach
</div>
</div>

<!-- CS Agents Performance -->
<div class="content-card">
<div class="card-header">
<h3 class="card-title"><i class="fas fa-users-cog"></i> أداء وكلاء خدمة العملاء</h3>
</div>
<div style="overflow-x: auto;">
<table>
<thead>
<tr>
<th>اسم الوكيل</th>
<th>إجمالي التذاكر</th>
<th>التذاكر المحلولة</th>
<th>معدل الحل</th>
<th>الحالة</th>
</tr>
</thead>
<tbody>
@foreach($agentPerformance as $agent)
<tr>
<td><strong>{{ $agent->name }}</strong></td>
<td>{{ $agent->assigned_tickets_count }}</td>
<td>{{ $agent->resolved_tickets }}</td>
<td>
@if($agent->assigned_tickets_count > 0)
{{ round(($agent->resolved_tickets / $agent->assigned_tickets_count) * 100) }}%
@else
0%
@endif
</td>
<td>
<span class="status-badge status-{{ $agent->is_online ? 'resolved' : 'closed' }}">
<i class="fas fa-circle"></i>
{{ $agent->is_online ? 'متصل' : 'غير متصل' }}
</span>
</td>
</tr>
@endforeach
</tbody>
</table>
</div>
</div>
</div>

<script>
// Notification System
function showNotification(message, type = 'success') {
    const notification = document.querySelector('.notification');
    if (notification) {
        notification.innerHTML = `<i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-triangle'}"></i> ${message}`;
        notification.className = `notification ${type} show`;
        setTimeout(() => {
            notification.classList.remove('show');
        }, 3000);
    }
}

// Charts Configuration
document.addEventListener('DOMContentLoaded', function() {
    if (typeof Chart === 'undefined') {
        console.error('Chart.js is not loaded');
        return;
    }

    // Set Chart.js defaults
    Chart.defaults.font.family = 'Cairo';
    Chart.defaults.color = '#64748b';

    // Timeline Chart
    const timelineChart = document.getElementById('ticketsChart');
    if (timelineChart) {
        try {
            const timelineData = @json($ticketsTimeline ?? []);
            const fallbackData = timelineData.length > 0 ? timelineData : [
                {date: 'لا توجد بيانات', count: 0, resolved: 0}
            ];

            new Chart(timelineChart, {
                type: 'line',
                data: {
                    labels: fallbackData.map(item => item.date || ''),
                    datasets: [{
                        label: 'التذاكر المنشأة',
                        data: fallbackData.map(item => item.count || 0),
                        borderColor: '#3b82f6',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: '#3b82f6',
                        pointBorderColor: '#ffffff',
                        pointBorderWidth: 2,
                        pointRadius: 4
                    }, {
                        label: 'التذاكر المحلولة',
                        data: fallbackData.map(item => item.resolved || 0),
                        borderColor: '#10b981',
                        backgroundColor: 'rgba(16, 185, 129, 0.1)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: '#10b981',
                        pointBorderColor: '#ffffff',
                        pointBorderWidth: 2,
                        pointRadius: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                usePointStyle: true,
                                padding: 20,
                                font: {
                                    size: 12,
                                    weight: '600'
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: '#f1f5f9'
                            }
                        },
                        x: {
                            grid: {
                                color: '#f8fafc'
                            }
                        }
                    }
                }
            });
        } catch (error) {
            console.error('Error creating timeline chart:', error);
        }
    }

    // Status Chart
    const statusChart = document.getElementById('statusChart');
    if (statusChart) {
        try {
            const statusData = @json($ticketsByStatus->pluck('count') ?? []);
            const statusLabels = @json($ticketsByStatus->pluck('status') ?? []);

            new Chart(statusChart, {
                type: 'doughnut',
                data: {
                    labels: statusLabels.map(status => {
                        const statusMap = {
                            'open': 'مفتوحة',
                            'in_progress': 'قيد المعالجة',
                            'waiting_customer': 'انتظار العميل',
                            'resolved': 'محلولة',
                            'closed': 'مغلقة'
                        };
                        return statusMap[status] || status;
                    }),
                    datasets: [{
                        data: statusData,
                        backgroundColor: [
                            '#fbbf24',
                            '#3b82f6',
                            '#ef4444',
                            '#10b981',
                            '#6b7280'
                        ],
                        borderWidth: 2,
                        borderColor: '#ffffff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                usePointStyle: true,
                                padding: 15,
                                font: {
                                    size: 11
                                }
                            }
                        }
                    }
                }
            });
        } catch (error) {
            console.error('Error creating status chart:', error);
        }
    }

    // Priority Chart
    const priorityChart = document.getElementById('priorityChart');
    if (priorityChart) {
        try {
            const priorityData = @json($ticketsByPriority->pluck('count') ?? []);
            const priorityLabels = @json($ticketsByPriority->pluck('priority') ?? []);

            new Chart(priorityChart, {
                type: 'bar',
                data: {
                    labels: priorityLabels.map(priority => {
                        const priorityMap = {
                            'low': 'منخفضة',
                            'medium': 'متوسطة',
                            'high': 'عالية',
                            'urgent': 'عاجلة'
                        };
                        return priorityMap[priority] || priority;
                    }),
                    datasets: [{
                        label: 'التذاكر',
                        data: priorityData,
                        backgroundColor: ['#3b82f6', '#fbbf24', '#ef4444', '#be185d'],
                        borderRadius: 6,
                        borderSkipped: false
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: '#f1f5f9'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
        } catch (error) {
            console.error('Error creating priority chart:', error);
        }
    }
});

// Auto-refresh data every 30 seconds
setInterval(function() {
    console.log('Auto-refreshing dashboard data...');
}, 30000);

// Quick Actions
function createNewTicket() {
    window.location.href = '{{ route("cs.tickets.create") }}';
}

function viewUrgentTickets() {
    window.location.href = '{{ route("cs.tickets.index", ["priority" => "urgent"]) }}';
}

function viewOpenTickets() {
    window.location.href = '{{ route("cs.tickets.index", ["status" => "open"]) }}';
}

function viewReports() {
    window.location.href = '{{ route("cs.reports") }}';
}

// Keyboard shortcuts
document.addEventListener('keydown', function(e) {
    if (e.ctrlKey || e.metaKey) {
        switch(e.key) {
            case 'n':
                e.preventDefault();
                createNewTicket();
                break;
            case 'u':
                e.preventDefault();
                viewUrgentTickets();
                break;
            case 'o':
                e.preventDefault();
                viewOpenTickets();
                break;
            case 'r':
                e.preventDefault();
                viewReports();
                break;
        }
    }
});
</script>

<!-- Notification Element -->
<div class="notification">
<i class="fas fa-check-circle"></i>
<span>تم تنفيذ العملية بنجاح</span>
</div>

</body>
</html>