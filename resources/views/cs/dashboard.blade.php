<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>لوحة خدمة العملاء - Tulip Store</title>
<link rel="stylesheet" href="/css/store.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800&display=swap" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<style>
*{font-family:'Cairo',sans-serif;margin:0;padding:0;box-sizing:border-box}
body{background:#f0f4f8;min-height:100vh}
.dashboard-container{max-width:1800px;margin:0 auto;padding:2rem}
.hero{background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);padding:2.5rem;text-align:center;margin-top:80px;position:relative;overflow:hidden;box-shadow:0 10px 40px rgba(102,126,234,0.3)}
.hero::before{content:'';position:absolute;top:0;left:0;right:0;bottom:0;background:url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");opacity:0.3}
.hero h1{font-size:2.8rem;font-weight:800;color:#fff;margin-bottom:0.5rem;position:relative;z-index:1}
.hero p{color:#e8e8ff;font-size:1.2rem;position:relative;z-index:1}
.section-title{font-size:1.6rem;font-weight:700;color:#1a1a1a;margin:2.5rem 0 1.5rem 0;display:flex;align-items:center;gap:0.8rem}
.section-title i{color:#667eea;font-size:1.8rem}
.stats-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(240px,1fr));gap:1.5rem;margin-bottom:2rem}
.stat-card{background:#fff;padding:2rem;border-radius:15px;box-shadow:0 4px 15px rgba(0,0,0,0.08);transition:all 0.3s;position:relative;overflow:hidden;border-top:4px solid #667eea}
.stat-card:hover{transform:translateY(-8px);box-shadow:0 12px 35px rgba(102,126,234,0.2)}
.stat-icon{width:60px;height:60px;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:1.8rem;margin-bottom:1rem;background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);color:#fff;box-shadow:0 4px 15px rgba(102,126,234,0.3)}
.stat-value{font-size:2.5rem;font-weight:800;margin:0.5rem 0;color:#1a1a1a}
.stat-label{color:#666;font-size:1rem;font-weight:600}
.stat-trend{font-size:0.85rem;margin-top:0.5rem;font-weight:600}
.trend-up{color:#22c55e}
.trend-down{color:#ef4444}
.system-card{background:#fff;padding:2.5rem;border-radius:18px;box-shadow:0 4px 15px rgba(0,0,0,0.08);margin-bottom:2rem;border-left:5px solid #667eea}
.system-card h3{color:#1a1a1a;font-size:1.4rem;margin-bottom:1.5rem;display:flex;align-items:center;gap:0.8rem;font-weight:700}
.system-card h3 i{color:#667eea;font-size:1.5rem}
table{width:100%;border-collapse:collapse}
thead{background:#f8f9fa}
th{padding:1.2rem;text-align:right;font-weight:700;color:#667eea;font-size:1rem;border-bottom:3px solid #667eea}
td{padding:1.2rem;border-bottom:1px solid #f0f4f8;color:#333;font-weight:500}
tbody tr:hover{background:#f8f9fa}
.status-badge{display:inline-block;padding:0.4rem 1rem;border-radius:20px;font-size:0.85rem;font-weight:700}
.status-open{background:#fff3cd;color:#856404}
.status-in_progress{background:#cfe2ff;color:#084298}
.status-waiting_customer{background:#f8d7da;color:#721c24}
.status-resolved{background:#d1e7dd;color:#0f5132}
.status-closed{background:#e2e3e5;color:#41464b}
.priority-low{background:#d1ecf1;color:#0c5460}
.priority-medium{background:#fff3cd;color:#856404}
.priority-high{background:#f8d7da;color:#721c24}
.priority-urgent{background:#dc3545;color:#fff}
.chart-card{background:#fff;padding:2.5rem;border-radius:18px;box-shadow:0 4px 15px rgba(0,0,0,0.08);margin-bottom:2rem}
.chart-title{color:#1a1a1a;font-size:1.4rem;margin-bottom:1.5rem;display:flex;align-items:center;gap:0.8rem;font-weight:700}
.chart-title i{color:#667eea;font-size:1.5rem}
.action-btn{background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);color:#fff;padding:0.6rem 1.5rem;border-radius:10px;border:none;cursor:pointer;font-weight:700;transition:all 0.3s;font-size:0.9rem}
.action-btn:hover{transform:translateY(-2px);box-shadow:0 6px 20px rgba(102,126,234,0.4)}
.badge-count{background:#667eea;color:#fff;padding:0.3rem 0.8rem;border-radius:20px;font-size:0.85rem;font-weight:700;margin-right:0.5rem}
.rating-stars{color:#fbbf24;font-size:1.2rem}
.activity-item{padding:1.2rem;background:#f8f9fa;border-radius:12px;margin-bottom:1rem;border-left:4px solid;transition:all 0.2s;display:flex;align-items:center;gap:1rem}
.activity-item:hover{background:#e8f4f8;transform:translateX(-5px)}
.activity-icon{width:45px;height:45px;border-radius:50%;display:flex;align-items:center;justify-content:center;color:#fff;font-size:1.2rem}
.progress-ring{width:120px;height:120px;position:relative}
.progress-ring svg{transform:rotate(-90deg)}
.progress-ring-circle{fill:none;stroke-width:8}
.progress-ring-bg{stroke:#e5e7eb}
.progress-ring-fill{stroke:#667eea;stroke-linecap:round;transition:stroke-dashoffset 0.5s}
.progress-text{position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);font-size:1.8rem;font-weight:800;color:#667eea}
</style>
</head>
<body>
@include('components.navbar')

<div class="hero">
<h1><i class="fas fa-headset"></i> لوحة خدمة العملاء</h1>
<p>إدارة احترافية شاملة لخدمة العملاء والدعم الفني</p>
</div>

<div class="dashboard-container">

<!-- Overview Stats -->
<h2 class="section-title"><i class="fas fa-chart-line"></i> نظرة عامة</h2>
<div class="stats-grid">
<div class="stat-card">
<div class="stat-icon"><i class="fas fa-ticket-alt"></i></div>
<div class="stat-label">إجمالي التذاكر</div>
<div class="stat-value">{{ $totalTickets }}</div>
<div class="stat-trend trend-up"><i class="fas fa-arrow-up"></i> +{{ $ticketsCreatedToday }} اليوم</div>
</div>
<div class="stat-card">
<div class="stat-icon"><i class="fas fa-folder-open"></i></div>
<div class="stat-label">تذاكر مفتوحة</div>
<div class="stat-value">{{ $openTickets }}</div>
<div class="stat-trend" style="color:#856404"><i class="fas fa-exclamation-circle"></i> تحتاج معالجة</div>
</div>
<div class="stat-card">
<div class="stat-icon"><i class="fas fa-spinner"></i></div>
<div class="stat-label">قيد المعالجة</div>
<div class="stat-value">{{ $inProgressTickets }}</div>
<div class="stat-trend" style="color:#084298"><i class="fas fa-sync"></i> جاري العمل</div>
</div>
<div class="stat-card">
<div class="stat-icon"><i class="fas fa-check-circle"></i></div>
<div class="stat-label">تم حلها اليوم</div>
<div class="stat-value">{{ $resolvedToday }}</div>
<div class="stat-trend trend-up"><i class="fas fa-check"></i> ممتاز</div>
</div>
<div class="stat-card">
<div class="stat-icon"><i class="fas fa-exclamation-triangle"></i></div>
<div class="stat-label">تذاكر عاجلة</div>
<div class="stat-value">{{ $urgentTickets }}</div>
<div class="stat-trend trend-down"><i class="fas fa-bolt"></i> أولوية قصوى</div>
</div>
<div class="stat-card">
<div class="stat-icon"><i class="fas fa-user-clock"></i></div>
<div class="stat-label">بانتظار العميل</div>
<div class="stat-value">{{ $waitingCustomer }}</div>
<div class="stat-trend" style="color:#721c24"><i class="fas fa-hourglass-half"></i> معلق</div>
</div>
</div>

<!-- Performance Metrics -->
<h2 class="section-title"><i class="fas fa-tachometer-alt"></i> مقاييس الأداء</h2>
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(300px,1fr));gap:2rem;margin-bottom:2rem">
<div class="system-card">
<h3><i class="fas fa-clock"></i> أوقات الاستجابة</h3>
<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem">
<div>
<div style="color:#666;font-size:0.9rem;margin-bottom:0.5rem">متوسط الرد الأول</div>
<div style="font-size:2rem;font-weight:800;color:#667eea">{{ $avgFirstResponseTime }}</div>
</div>
<div class="progress-ring">
<svg width="120" height="120">
<circle class="progress-ring-circle progress-ring-bg" cx="60" cy="60" r="52"></circle>
<circle class="progress-ring-circle progress-ring-fill" cx="60" cy="60" r="52" 
style="stroke-dasharray:327;stroke-dashoffset:{{ 327 - (327 * $responseRate / 100) }}"></circle>
</svg>
<div class="progress-text">{{ $responseRate }}%</div>
</div>
</div>
<div style="display:flex;justify-content:space-between;padding-top:1rem;border-top:2px solid #f0f4f8">
<div>
<div style="color:#666;font-size:0.9rem;margin-bottom:0.5rem">متوسط وقت الحل</div>
<div style="font-size:1.5rem;font-weight:700;color:#764ba2">{{ $avgResolutionTime }}</div>
</div>
<div>
<div style="color:#666;font-size:0.9rem;margin-bottom:0.5rem">معدل الحل</div>
<div style="font-size:1.5rem;font-weight:700;color:#22c55e">{{ $resolutionRate }}%</div>
</div>
</div>
</div>

<div class="system-card">
<h3><i class="fas fa-smile"></i> رضا العملاء</h3>
<div style="text-align:center;margin-bottom:1.5rem">
<div style="font-size:3.5rem;font-weight:800;color:#667eea;margin-bottom:0.5rem">{{ $avgSatisfaction }}<span style="font-size:2rem">/5</span></div>
<div class="rating-stars" style="font-size:2rem;margin-bottom:1rem">
@for($i = 1; $i <= 5; $i++)
<i class="fas fa-star{{ $i <= $avgSatisfaction ? '' : '-o' }}"></i>
@endfor
</div>
<div style="color:#666;font-size:1rem">بناءً على {{ $satisfactionCount }} تقييم</div>
</div>
<div style="background:#f8f9fa;padding:1rem;border-radius:10px">
@foreach($satisfactionDistribution as $rating)
<div style="display:flex;align-items:center;gap:1rem;margin-bottom:0.8rem">
<div style="width:60px;font-weight:700;color:#667eea">{{ $rating->satisfaction_rating }} <i class="fas fa-star" style="font-size:0.8rem;color:#fbbf24"></i></div>
<div style="flex:1;background:#e5e7eb;height:8px;border-radius:10px;overflow:hidden">
<div style="background:#667eea;height:100%;width:{{ ($rating->count / $satisfactionCount) * 100 }}%;border-radius:10px"></div>
</div>
<div style="width:50px;text-align:left;font-weight:600">{{ $rating->count }}</div>
</div>
@endforeach
</div>
</div>

<div class="system-card">
<h3><i class="fas fa-calendar-week"></i> إحصائيات الفترة</h3>
<div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem">
<div style="background:#f8f9fa;padding:1.5rem;border-radius:12px;text-align:center">
<div style="color:#667eea;font-size:2.5rem;font-weight:800;margin-bottom:0.5rem">{{ $ticketsThisWeek }}</div>
<div style="color:#666;font-weight:600">تذاكر هذا الأسبوع</div>
<div style="color:#22c55e;font-size:0.9rem;margin-top:0.5rem;font-weight:600">
<i class="fas fa-check-circle"></i> {{ $resolvedThisWeek }} محلولة
</div>
</div>
<div style="background:#f8f9fa;padding:1.5rem;border-radius:12px;text-align:center">
<div style="color:#764ba2;font-size:2.5rem;font-weight:800;margin-bottom:0.5rem">{{ $ticketsThisMonth }}</div>
<div style="color:#666;font-weight:600">تذاكر هذا الشهر</div>
<div style="color:#22c55e;font-size:0.9rem;margin-top:0.5rem;font-weight:600">
<i class="fas fa-check-circle"></i> {{ $resolvedThisMonth }} محلولة
</div>
</div>
</div>
<div style="margin-top:1.5rem;padding:1rem;background:#e8f4f8;border-radius:10px;border-left:4px solid #667eea">
<div style="display:flex;justify-content:space-between;align-items:center">
<div>
<div style="font-weight:700;color:#1a1a1a;margin-bottom:0.3rem">نشاط اليوم</div>
<div style="color:#666;font-size:0.9rem">{{ $ticketsCreatedToday }} تذكرة • {{ $repliesCreatedToday }} رد • {{ $feedbackReceivedToday }} رأي</div>
</div>
<i class="fas fa-chart-line" style="font-size:2rem;color:#667eea"></i>
</div>
</div>
</div>
</div>

<!-- Charts Section -->
<div style="display:grid;grid-template-columns:2fr 1fr;gap:2rem;margin-bottom:2rem">
<div class="chart-card">
<h3 class="chart-title"><i class="fas fa-chart-area"></i> التذاكر والحلول - آخر 7 أيام</h3>
<canvas id="ticketsChart"></canvas>
</div>
<div class="chart-card">
<h3 class="chart-title"><i class="fas fa-chart-pie"></i> التوزيع حسب الحالة</h3>
<canvas id="statusChart"></canvas>
</div>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:2rem;margin-bottom:2rem">
<div class="chart-card">
<h3 class="chart-title"><i class="fas fa-chart-bar"></i> التوزيع حسب الأولوية</h3>
<canvas id="priorityChart"></canvas>
</div>
<div class="chart-card">
<h3 class="chart-title"><i class="fas fa-chart-doughnut"></i> التوزيع حسب الفئة</h3>
<canvas id="categoryChart"></canvas>
</div>
</div>

<!-- Recent Activity -->
<h2 class="section-title"><i class="fas fa-history"></i> النشاط الأخير</h2>
<div class="system-card">
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(400px,1fr));gap:1rem">
@foreach($recentActivity as $activity)
<div class="activity-item" style="border-left-color:{{ $activity['color'] }}">
<div class="activity-icon" style="background:{{ $activity['color'] }}">
<i class="fas fa-{{ $activity['icon'] }}"></i>
</div>
<div style="flex:1">
<div style="font-weight:700;color:#1a1a1a;margin-bottom:0.3rem">{{ $activity['message'] }}</div>
<div style="color:#666;font-size:0.9rem">{{ $activity['user'] }} • {{ $activity['time']->diffForHumans() }}</div>
</div>
</div>
@endforeach
</div>
</div>

<!-- Recent Tickets -->
<h2 class="section-title"><i class="fas fa-ticket-alt"></i> أحدث التذاكر</h2>
<div class="system-card">
<table>
<thead>
<tr>
<th>رقم التذكرة</th>
<th>العميل</th>
<th>الموضوع</th>
<th>الفئة</th>
<th>الأولوية</th>
<th>الحالة</th>
<th>الوكيل المعين</th>
<th>التاريخ</th>
<th>الإجراءات</th>
</tr>
</thead>
<tbody>
@foreach($recentTickets as $ticket)
<tr>
<td><strong style="color:#667eea">{{ $ticket->ticket_number }}</strong></td>
<td>{{ $ticket->user->name }}</td>
<td>{{ Str::limit($ticket->subject, 40) }}</td>
<td><span class="badge-count">{{ $ticket->category }}</span></td>
<td><span class="status-badge priority-{{ $ticket->priority }}">{{ $ticket->priority }}</span></td>
<td><span class="status-badge status-{{ $ticket->status }}">{{ $ticket->status }}</span></td>
<td>{{ $ticket->assignedAgent->name ?? 'غير معين' }}</td>
<td>{{ $ticket->created_at->diffForHumans() }}</td>
<td>
<button class="action-btn" onclick="viewTicket({{ $ticket->id }})">
<i class="fas fa-eye"></i> عرض
</button>
</td>
</tr>
@endforeach
</tbody>
</table>
</div>

<!-- Top Customers -->
<h2 class="section-title"><i class="fas fa-users"></i> أكثر العملاء نشاطاً</h2>
<div class="system-card">
<table>
<thead>
<tr>
<th>العميل</th>
<th>البريد الإلكتروني</th>
<th>عدد التذاكر</th>
<th>النشاط</th>
</tr>
</thead>
<tbody>
@foreach($topCustomers as $customer)
<tr>
<td><strong>{{ $customer->user->name }}</strong></td>
<td>{{ $customer->user->email }}</td>
<td><span class="badge-count">{{ $customer->ticket_count }}</span></td>
<td>
<div style="background:#e5e7eb;height:8px;border-radius:10px;overflow:hidden;width:150px">
<div style="background:#667eea;height:100%;width:{{ min(($customer->ticket_count / $topCustomers->first()->ticket_count) * 100, 100) }}%;border-radius:10px"></div>
</div>
</td>
</tr>
@endforeach
</tbody>
</table>
</div>

<!-- Customer Feedback -->
<h2 class="section-title"><i class="fas fa-comments"></i> آراء العملاء</h2>
<div class="stats-grid">
<div class="stat-card">
<div class="stat-icon"><i class="fas fa-inbox"></i></div>
<div class="stat-label">آراء قيد المراجعة</div>
<div class="stat-value">{{ $pendingFeedback }}</div>
</div>
<div class="stat-card">
<div class="stat-icon"><i class="fas fa-star"></i></div>
<div class="stat-label">متوسط التقييم</div>
<div class="stat-value">{{ $avgRating }}<span style="font-size:1.5rem">/5</span></div>
</div>
<div class="stat-card">
<div class="stat-icon"><i class="fas fa-thumbs-up"></i></div>
<div class="stat-label">إطراءات</div>
<div class="stat-value">{{ $feedbackByType->where('type', 'compliment')->first()->count ?? 0 }}</div>
</div>
<div class="stat-card">
<div class="stat-icon"><i class="fas fa-exclamation-circle"></i></div>
<div class="stat-label">شكاوى</div>
<div class="stat-value">{{ $feedbackByType->where('type', 'complaint')->first()->count ?? 0 }}</div>
</div>
</div>

<div class="system-card">
<h3><i class="fas fa-comment-dots"></i> أحدث الآراء</h3>
<table>
<thead>
<tr>
<th>العميل</th>
<th>النوع</th>
<th>التقييم</th>
<th>الرسالة</th>
<th>الحالة</th>
<th>التاريخ</th>
<th>الإجراءات</th>
</tr>
</thead>
<tbody>
@foreach($recentFeedback as $feedback)
<tr>
<td><strong>{{ $feedback->user->name }}</strong></td>
<td><span class="badge-count">{{ $feedback->type }}</span></td>
<td>
@if($feedback->rating)
<div class="rating-stars">
@for($i = 1; $i <= 5; $i++)
<i class="fas fa-star{{ $i <= $feedback->rating ? '' : '-o' }}"></i>
@endfor
</div>
@else
-
@endif
</td>
<td>{{ Str::limit($feedback->message, 50) }}</td>
<td><span class="status-badge status-{{ $feedback->status }}">{{ $feedback->status }}</span></td>
<td>{{ $feedback->created_at->diffForHumans() }}</td>
<td>
<button class="action-btn" onclick="viewFeedback({{ $feedback->id }})">
<i class="fas fa-eye"></i> عرض
</button>
</td>
</tr>
@endforeach
</tbody>
</table>
</div>

<!-- Agent Performance -->
<h2 class="section-title"><i class="fas fa-users-cog"></i> أداء فريق الدعم</h2>
<div class="system-card">
<table>
<thead>
<tr>
<th>الوكيل</th>
<th>التذاكر المعينة</th>
<th>التذاكر المحلولة</th>
<th>معدل الحل</th>
<th>الأداء</th>
</tr>
</thead>
<tbody>
@foreach($agentPerformance as $agent)
<tr>
<td><strong>{{ $agent->name }}</strong></td>
<td><span class="badge-count">{{ $agent->assigned_tickets_count }}</span></td>
<td><span class="badge-count">{{ $agent->resolved_tickets }}</span></td>
<td>
@if($agent->assigned_tickets_count > 0)
<strong style="color:#667eea;font-size:1.2rem">{{ round(($agent->resolved_tickets / $agent->assigned_tickets_count) * 100) }}%</strong>
@else
-
@endif
</td>
<td>
@if($agent->assigned_tickets_count > 0)
<div style="background:#e5e7eb;height:10px;border-radius:10px;overflow:hidden;width:200px">
<div style="background:linear-gradient(90deg,#667eea,#764ba2);height:100%;width:{{ round(($agent->resolved_tickets / $agent->assigned_tickets_count) * 100) }}%;border-radius:10px"></div>
</div>
@endif
</td>
</tr>
@endforeach
</tbody>
</table>
</div>

</div>

<script>
// Tickets Timeline Chart
const ticketsCtx = document.getElementById('ticketsChart').getContext('2d');
new Chart(ticketsCtx, {
    type: 'line',
    data: {
        labels: {!! json_encode(array_column($ticketsTimeline, 'date')) !!},
        datasets: [{
            label: 'تذاكر جديدة',
            data: {!! json_encode(array_column($ticketsTimeline, 'count')) !!},
            borderColor: '#667eea',
            backgroundColor: 'rgba(102, 126, 234, 0.1)',
            tension: 0.4,
            fill: true,
            borderWidth: 3,
            pointRadius: 5,
            pointHoverRadius: 7,
            pointBackgroundColor: '#667eea'
        }, {
            label: 'تذاكر محلولة',
            data: {!! json_encode(array_column($ticketsTimeline, 'resolved')) !!},
            borderColor: '#22c55e',
            backgroundColor: 'rgba(34, 197, 94, 0.1)',
            tension: 0.4,
            fill: true,
            borderWidth: 3,
            pointRadius: 5,
            pointHoverRadius: 7,
            pointBackgroundColor: '#22c55e'
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'bottom',
                labels: {
                    font: {family: 'Cairo', size: 12},
                    padding: 15
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                grid: {color: 'rgba(0,0,0,0.05)'}
            },
            x: {
                grid: {display: false}
            }
        }
    }
});

// Status Chart
const statusCtx = document.getElementById('statusChart').getContext('2d');
new Chart(statusCtx, {
    type: 'doughnut',
    data: {
        labels: {!! json_encode($ticketsByStatus->pluck('status')) !!},
        datasets: [{
            data: {!! json_encode($ticketsByStatus->pluck('count')) !!},
            backgroundColor: ['#fbbf24', '#3b82f6', '#ef4444', '#22c55e', '#6b7280'],
            borderWidth: 3,
            borderColor: '#fff'
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'bottom',
                labels: {
                    font: {family: 'Cairo', size: 11},
                    padding: 10
                }
            }
        }
    }
});

// Priority Chart
const priorityCtx = document.getElementById('priorityChart').getContext('2d');
new Chart(priorityCtx, {
    type: 'bar',
    data: {
        labels: {!! json_encode($ticketsByPriority->pluck('priority')) !!},
        datasets: [{
            label: 'عدد التذاكر',
            data: {!! json_encode($ticketsByPriority->pluck('count')) !!},
            backgroundColor: ['#3b82f6', '#fbbf24', '#ef4444', '#dc3545'],
            borderWidth: 0,
            borderRadius: 8
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {display: false}
        },
        scales: {
            y: {
                beginAtZero: true,
                grid: {color: 'rgba(0,0,0,0.05)'}
            },
            x: {
                grid: {display: false}
            }
        }
    }
});

// Category Chart
const categoryCtx = document.getElementById('categoryChart').getContext('2d');
new Chart(categoryCtx, {
    type: 'doughnut',
    data: {
        labels: {!! json_encode($ticketsByCategory->pluck('category')) !!},
        datasets: [{
            data: {!! json_encode($ticketsByCategory->pluck('count')) !!},
            backgroundColor: ['#667eea', '#764ba2', '#3b82f6', '#22c55e', '#fbbf24', '#ef4444'],
            borderWidth: 3,
            borderColor: '#fff'
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'bottom',
                labels: {
                    font: {family: 'Cairo', size: 11},
                    padding: 10
                }
            }
        }
    }
});

function viewTicket(ticketId) {
    window.location.href = '/cs/tickets/' + ticketId;
}

function viewFeedback(feedbackId) {
    alert('عرض الرأي #' + feedbackId + '\n\nسيتم تطوير صفحة تفاصيل الآراء قريباً');
}
</script>
</body>
</html>
