<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>لوحة {{ $dashboardType }} - Tulip Store</title>
<link rel="stylesheet" href="/css/store.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800&display=swap" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<style>
*{font-family:'Cairo',sans-serif;margin:0;padding:0;box-sizing:border-box}
body{background:#f0f4f8;min-height:100vh}
.dashboard-container{max-width:1600px;margin:0 auto;padding:2rem}
.hero{background:linear-gradient(135deg,#2a7080 0%,#1a5060 100%);padding:2.5rem;text-align:center;margin-top:80px;position:relative;overflow:hidden;box-shadow:0 10px 40px rgba(42,112,128,0.3)}
.hero::before{content:'';position:absolute;top:0;left:0;right:0;bottom:0;background:url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");opacity:0.3}
.hero h1{font-size:2.8rem;font-weight:800;color:#fff;margin-bottom:0.5rem;position:relative;z-index:1}
.hero p{color:#d4e9ed;font-size:1.2rem;position:relative;z-index:1}
.section-title{font-size:1.6rem;font-weight:700;color:#1a1a1a;margin:2.5rem 0 1.5rem 0;display:flex;align-items:center;gap:0.8rem}
.section-title i{color:#2a7080;font-size:1.8rem}
.stats-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:1.5rem;margin-bottom:2rem}
.stat-card{background:#fff;padding:2rem;border-radius:15px;box-shadow:0 4px 15px rgba(0,0,0,0.08);transition:all 0.3s;position:relative;overflow:hidden;border-top:4px solid #2a7080}
.stat-card::before{content:'';position:absolute;top:0;right:0;width:100px;height:100px;background:radial-gradient(circle,rgba(42,112,128,0.1) 0%,transparent 70%);transition:all 0.3s}
.stat-card:hover{transform:translateY(-8px);box-shadow:0 12px 35px rgba(42,112,128,0.2)}
.stat-card:hover::before{width:150px;height:150px}
.stat-icon{width:60px;height:60px;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:1.8rem;margin-bottom:1rem;background:linear-gradient(135deg,#2a7080 0%,#1a5060 100%);color:#fff;box-shadow:0 4px 15px rgba(42,112,128,0.3)}
.stat-value{font-size:2.5rem;font-weight:800;margin:0.5rem 0;color:#1a1a1a}
.stat-label{color:#666;font-size:1rem;font-weight:600}
.system-card{background:#fff;padding:2.5rem;border-radius:18px;box-shadow:0 4px 15px rgba(0,0,0,0.08);margin-bottom:2rem;border-left:5px solid #2a7080}
.system-card h3{color:#1a1a1a;font-size:1.4rem;margin-bottom:1.5rem;display:flex;align-items:center;gap:0.8rem;font-weight:700}
.system-card h3 i{color:#2a7080;font-size:1.5rem}
.metric-row{display:flex;justify-content:space-between;padding:1.2rem 0;border-bottom:1px solid #f0f4f8}
.metric-row:last-child{border-bottom:none}
.metric-label{color:#666;font-weight:600;font-size:1rem}
.metric-value{color:#1a1a1a;font-weight:700;font-size:1.1rem}
.status-badge{display:inline-block;padding:0.4rem 1rem;border-radius:20px;font-size:0.85rem;font-weight:700}
.status-operational{background:#d4edda;color:#155724}
.status-warning{background:#fff3cd;color:#856404}
.status-error{background:#f8d7da;color:#721c24}
.status-info{background:#d1ecf1;color:#0c5460}
.activity-item{padding:1.2rem;background:#f8f9fa;border-radius:12px;margin-bottom:1rem;border-left:4px solid #2a7080;transition:all 0.2s}
.activity-item:hover{background:#e8f4f8;transform:translateX(-5px)}
.activity-action{color:#1a1a1a;font-weight:700;margin-bottom:0.5rem;font-size:1rem}
.activity-meta{color:#666;font-size:0.9rem}
.log-item{padding:1rem;background:#f8f9fa;border-radius:10px;margin-bottom:0.8rem;display:flex;align-items:center;gap:1rem;transition:all 0.2s}
.log-item:hover{background:#e8f4f8}
.log-level{width:90px;text-align:center;padding:0.4rem 0.8rem;border-radius:8px;font-size:0.85rem;font-weight:700}
.log-level.warning{background:#fff3cd;color:#856404}
.log-level.error{background:#f8d7da;color:#721c24}
.log-level.info{background:#d1ecf1;color:#0c5460}
.log-message{flex:1;color:#333;font-weight:500}
.log-time{color:#666;font-size:0.9rem;font-weight:600}
.chart-card{background:#fff;padding:2.5rem;border-radius:18px;box-shadow:0 4px 15px rgba(0,0,0,0.08);margin-bottom:2rem}
.chart-title{color:#1a1a1a;font-size:1.4rem;margin-bottom:1.5rem;display:flex;align-items:center;gap:0.8rem;font-weight:700}
.chart-title i{color:#2a7080;font-size:1.5rem}
table{width:100%;border-collapse:collapse}
thead{background:#f8f9fa}
th{padding:1.2rem;text-align:right;font-weight:700;color:#2a7080;font-size:1rem;border-bottom:3px solid #2a7080}
td{padding:1.2rem;border-bottom:1px solid #f0f4f8;color:#333;font-weight:500}
tbody tr:hover{background:#f8f9fa}
.api-endpoint{font-family:monospace;background:#f8f9fa;padding:0.4rem 0.8rem;border-radius:8px;color:#2a7080;font-weight:600;border:1px solid #d4e9ed}
.quick-actions{display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:1.5rem;margin-bottom:2rem}
.action-btn{background:linear-gradient(135deg,#2a7080 0%,#1a5060 100%);color:#fff;padding:1.5rem;border-radius:15px;text-align:center;cursor:pointer;transition:all 0.3s;text-decoration:none;display:block;box-shadow:0 4px 15px rgba(42,112,128,0.3);border:none}
.action-btn:hover{transform:translateY(-5px);box-shadow:0 8px 25px rgba(42,112,128,0.4)}
.action-btn i{font-size:2rem;display:block;margin-bottom:0.8rem}
.action-btn span{font-size:1.1rem;font-weight:700}
.progress-bar{height:12px;background:#e8f4f8;border-radius:10px;overflow:hidden;margin-top:0.8rem}
.progress-fill{height:100%;background:linear-gradient(90deg,#2a7080 0%,#1a5060 100%);transition:width 0.3s;border-radius:10px}
.badge-count{background:#2a7080;color:#fff;padding:0.3rem 0.8rem;border-radius:20px;font-size:0.85rem;font-weight:700;margin-right:0.5rem}
.pulse{animation:pulse 2s cubic-bezier(0.4,0,0.6,1) infinite}
@keyframes pulse{0%,100%{opacity:1}50%{opacity:0.5}}
.stat-card small{display:block;margin-top:0.5rem;font-size:0.85rem}
button:hover{opacity:0.9;transform:translateY(-2px);transition:all 0.2s}
button:active{transform:translateY(0)}
.system-card table button{white-space:nowrap}
.chat-user-item{padding:1rem 1.5rem;border-bottom:1px solid #f0f4f8;cursor:pointer;transition:all 0.3s;display:flex;align-items:center;gap:1rem}
.chat-user-item:hover{background:#e8f4f8;transform:translateX(-5px)}
.chat-user-item:active{background:#d4e9ed}
.chat-message{margin-bottom:1rem;display:flex;gap:1rem}
.chat-message.sent{flex-direction:row-reverse}
.chat-message-bubble{max-width:70%;padding:1rem 1.5rem;border-radius:15px;word-wrap:break-word}
.chat-message.sent .chat-message-bubble{background:linear-gradient(135deg,#2a7080,#1a5060);color:#fff;border-radius:15px 15px 0 15px}
.chat-message.received .chat-message-bubble{background:#fff;color:#1a1a1a;border:2px solid #e5e7eb;border-radius:15px 15px 15px 0}
.chat-message-time{font-size:0.75rem;opacity:0.7;margin-top:0.5rem}
</style>
</head>
<body>
@include('components.navbar')

<div class="hero">
<h1><i class="fas fa-laptop-code"></i> لوحة {{ $dashboardType }}</h1>
<p>مراقبة شاملة للنظام والأداء التقني</p>
</div>

<div class="dashboard-container">

<!-- Quick Actions - Only for IT Supervisor -->
@if($isSupervisor)
<h2 class="section-title"><i class="fas fa-bolt"></i> إجراءات سريعة</h2>
<div class="quick-actions">
<button class="action-btn" onclick="executeAction('clear-cache', this)">
<i class="fas fa-broom"></i>
<span>مسح الذاكرة المؤقتة</span>
</button>
<button class="action-btn" onclick="executeAction('update-system', this)">
<i class="fas fa-sync-alt"></i>
<span>تحديث النظام</span>
</button>
<button class="action-btn" onclick="executeAction('check-database', this)">
<i class="fas fa-database"></i>
<span>فحص قاعدة البيانات</span>
</button>
<button class="action-btn" onclick="executeAction('create-backup', this)">
<i class="fas fa-save"></i>
<span>نسخة احتياطية</span>
</button>
<button class="action-btn" onclick="executeAction('optimize-performance', this)">
<i class="fas fa-rocket"></i>
<span>تحسين الأداء</span>
</button>
<button class="action-btn" onclick="executeAction('security-scan', this)">
<i class="fas fa-shield-alt"></i>
<span>فحص الأمان</span>
</button>
</div>
@else
<!-- Limited Actions for IT Crew -->
<h2 class="section-title"><i class="fas fa-eye"></i> المراقبة</h2>
<div style="background:#fff;padding:2rem;border-radius:15px;box-shadow:0 4px 15px rgba(0,0,0,0.08);margin-bottom:2rem;text-align:center;border-top:4px solid #2a7080">
<i class="fas fa-info-circle" style="font-size:3rem;color:#2a7080;margin-bottom:1rem"></i>
<h3 style="color:#1a1a1a;font-size:1.3rem;margin-bottom:0.5rem">وضع المراقبة</h3>
<p style="color:#666;font-size:1rem">أنت تعمل في وضع المراقبة. يمكنك عرض جميع المقاييس والإحصائيات.</p>
</div>
@endif

@if($isSupervisor)
<!-- Real-time Monitoring Dashboard -->
<h2 class="section-title"><i class="fas fa-chart-line"></i> المراقبة في الوقت الفعلي</h2>
<div class="stats-grid">
<div class="stat-card">
<div class="stat-icon"><i class="fas fa-tachometer-alt"></i></div>
<div class="stat-label">طلبات في الثانية</div>
<div class="stat-value">{{ $realtimeMetrics['requests_per_second'] }}</div>
<small style="color:#22c55e;font-weight:600"><i class="fas fa-arrow-up"></i> +12% من الأمس</small>
</div>
<div class="stat-card">
<div class="stat-icon"><i class="fas fa-link"></i></div>
<div class="stat-label">اتصالات نشطة</div>
<div class="stat-value">{{ $realtimeMetrics['active_connections'] }}</div>
<small style="color:#666;font-weight:600"><i class="fas fa-minus"></i> مستقر</small>
</div>
<div class="stat-card">
<div class="stat-icon"><i class="fas fa-tasks"></i></div>
<div class="stat-label">مهام في قائمة الانتظار</div>
<div class="stat-value">{{ $realtimeMetrics['queue_jobs'] }}</div>
<small style="color:#22c55e;font-weight:600"><i class="fas fa-arrow-down"></i> -5 من قبل ساعة</small>
</div>
<div class="stat-card">
<div class="stat-icon"><i class="fas fa-bolt"></i></div>
<div class="stat-label">عمليات الذاكرة المؤقتة</div>
<div class="stat-value">{{ number_format($realtimeMetrics['cache_operations']) }}</div>
<small style="color:#22c55e;font-weight:600"><i class="fas fa-arrow-up"></i> +8% معدل النجاح</small>
</div>
</div>

<!-- System Alerts -->
<h2 class="section-title"><i class="fas fa-bell"></i> تنبيهات النظام</h2>
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(350px,1fr));gap:1.5rem;margin-bottom:2rem">
@foreach($systemAlerts as $alert)
<div style="background:#fff;padding:1.5rem;border-radius:15px;box-shadow:0 4px 15px rgba(0,0,0,0.08);border-right:5px solid {{ $alert['type'] == 'warning' ? '#fbbf24' : ($alert['type'] == 'success' ? '#22c55e' : '#3b82f6') }};display:flex;align-items:center;gap:1.5rem">
<div style="width:50px;height:50px;border-radius:12px;background:{{ $alert['type'] == 'warning' ? 'linear-gradient(135deg,#fbbf24,#f59e0b)' : ($alert['type'] == 'success' ? 'linear-gradient(135deg,#22c55e,#16a34a)' : 'linear-gradient(135deg,#3b82f6,#2563eb)') }};display:flex;align-items:center;justify-content:center;color:#fff;font-size:1.5rem">
<i class="fas fa-{{ $alert['type'] == 'warning' ? 'exclamation-triangle' : ($alert['type'] == 'success' ? 'check-circle' : 'info-circle') }}"></i>
</div>
<div style="flex:1">
<div style="color:#1a1a1a;font-weight:700;margin-bottom:0.3rem">{{ $alert['message'] }}</div>
<div style="color:#666;font-size:0.9rem">منذ {{ $alert['time'] }}</div>
</div>
</div>
@endforeach
</div>

<!-- Services Status -->
<h2 class="section-title"><i class="fas fa-server"></i> حالة الخدمات</h2>
<div class="system-card">
<table>
<thead>
<tr>
<th>الخدمة</th>
<th>الحالة</th>
<th>وقت التشغيل</th>
<th>استخدام CPU</th>
<th>استخدام الذاكرة</th>
<th>الإجراءات</th>
</tr>
</thead>
<tbody>
@foreach($services as $service)
<tr>
<td><strong><i class="fas fa-circle" style="color:#22c55e;font-size:0.6rem;margin-left:0.5rem"></i>{{ $service['name'] }}</strong></td>
<td><span class="status-badge status-operational">{{ $service['status'] }}</span></td>
<td>{{ $service['uptime'] }}</td>
<td><span style="color:#2a7080;font-weight:700">{{ $service['cpu'] }}</span></td>
<td><span style="color:#2a7080;font-weight:700">{{ $service['memory'] }}</span></td>
<td>
<button style="background:#2a7080;color:#fff;border:none;padding:0.4rem 1rem;border-radius:8px;cursor:pointer;font-size:0.85rem;font-weight:600" onclick="showNotification(true, 'تم إعادة تشغيل {{ $service['name'] }} بنجاح')">
<i class="fas fa-redo"></i> إعادة تشغيل
</button>
</td>
</tr>
@endforeach
</tbody>
</table>
</div>

<!-- Network & SSL -->
<div style="display:grid;grid-template-columns:1fr 1fr;gap:2rem;margin-bottom:2rem">
<div class="system-card">
<h3><i class="fas fa-network-wired"></i> إحصائيات الشبكة</h3>
<div class="metric-row">
<span class="metric-label"><i class="fas fa-download"></i> سرعة التحميل</span>
<span class="metric-value">{{ $networkStats['incoming_bandwidth'] }}</span>
</div>
<div class="metric-row">
<span class="metric-label"><i class="fas fa-upload"></i> سرعة الرفع</span>
<span class="metric-value">{{ $networkStats['outgoing_bandwidth'] }}</span>
</div>
<div class="metric-row">
<span class="metric-label"><i class="fas fa-plug"></i> إجمالي الاتصالات</span>
<span class="metric-value">{{ $networkStats['total_connections'] }}</span>
</div>
<div class="metric-row">
<span class="metric-label"><i class="fas fa-ban"></i> عناوين IP محظورة</span>
<span class="metric-value"><span class="badge-count">{{ $networkStats['blocked_ips'] }}</span></span>
</div>
</div>

<div class="system-card">
<h3><i class="fas fa-lock"></i> شهادة SSL</h3>
<div class="metric-row">
<span class="metric-label">الحالة</span>
<span class="metric-value"><span class="status-badge status-operational">صالحة</span></span>
</div>
<div class="metric-row">
<span class="metric-label">الجهة المصدرة</span>
<span class="metric-value">{{ $sslInfo['issuer'] }}</span>
</div>
<div class="metric-row">
<span class="metric-label">تاريخ الانتهاء</span>
<span class="metric-value">{{ $sslInfo['expires'] }}</span>
</div>
<div class="metric-row">
<span class="metric-label">الأيام المتبقية</span>
<span class="metric-value"><span style="color:{{ $sslInfo['days_remaining'] < 30 ? '#ef4444' : '#22c55e' }};font-weight:800">{{ $sslInfo['days_remaining'] }} يوم</span></span>
</div>
</div>
</div>

<!-- Disk I/O & User Activity -->
<div style="display:grid;grid-template-columns:1fr 1fr;gap:2rem;margin-bottom:2rem">
<div class="system-card">
<h3><i class="fas fa-hdd"></i> إحصائيات القرص (I/O)</h3>
<div class="metric-row">
<span class="metric-label"><i class="fas fa-arrow-down"></i> سرعة القراءة</span>
<span class="metric-value">{{ $diskIO['read_speed'] }}</span>
</div>
<div class="metric-row">
<span class="metric-label"><i class="fas fa-arrow-up"></i> سرعة الكتابة</span>
<span class="metric-value">{{ $diskIO['write_speed'] }}</span>
</div>
<div class="metric-row">
<span class="metric-label"><i class="fas fa-exchange-alt"></i> عمليات I/O في الثانية</span>
<span class="metric-value">{{ $diskIO['iops'] }}</span>
</div>
<div class="metric-row">
<span class="metric-label"><i class="fas fa-clock"></i> زمن الاستجابة</span>
<span class="metric-value">{{ $diskIO['latency'] }}</span>
</div>
</div>

<div class="system-card">
<h3><i class="fas fa-users-cog"></i> نشاط المستخدمين</h3>
<div class="metric-row">
<span class="metric-label"><i class="fas fa-circle" style="color:#22c55e"></i> متصلون الآن</span>
<span class="metric-value"><span class="badge-count">{{ $userActivity['online_now'] }}</span></span>
</div>
<div class="metric-row">
<span class="metric-label"><i class="fas fa-user-check"></i> نشطون اليوم</span>
<span class="metric-value">{{ $userActivity['active_today'] }}</span>
</div>
<div class="metric-row">
<span class="metric-label"><i class="fas fa-user-plus"></i> تسجيلات جديدة</span>
<span class="metric-value">{{ $userActivity['new_registrations'] }}</span>
</div>
<div class="metric-row">
<span class="metric-label"><i class="fas fa-user-shield"></i> مسؤولون متصلون</span>
<span class="metric-value"><span class="badge-count">{{ $userActivity['logged_in_admins'] }}</span></span>
</div>
</div>
</div>

<!-- Slow Queries Analyzer -->
<h2 class="section-title"><i class="fas fa-search"></i> محلل الاستعلامات البطيئة</h2>
<div class="system-card">
<table>
<thead>
<tr>
<th>الاستعلام</th>
<th>الوقت</th>
<th>عدد المرات</th>
<th>الخطورة</th>
<th>الإجراء</th>
</tr>
</thead>
<tbody>
@foreach($slowQueries as $query)
<tr>
<td><code class="api-endpoint" style="max-width:400px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;display:block">{{ $query['query'] }}</code></td>
<td><strong style="color:#ef4444">{{ $query['time'] }}</strong></td>
<td><span class="badge-count">{{ $query['calls'] }}</span></td>
<td>
<span class="status-badge status-{{ $query['severity'] == 'high' ? 'error' : ($query['severity'] == 'medium' ? 'warning' : 'info') }}">
{{ $query['severity'] == 'high' ? 'عالية' : ($query['severity'] == 'medium' ? 'متوسطة' : 'منخفضة') }}
</span>
</td>
<td>
<button style="background:#2a7080;color:#fff;border:none;padding:0.4rem 1rem;border-radius:8px;cursor:pointer;font-size:0.85rem;font-weight:600" onclick="showNotification(true, 'تم تحسين الاستعلام بنجاح')">
<i class="fas fa-magic"></i> تحسين
</button>
</td>
</tr>
@endforeach
</tbody>
</table>
</div>

<!-- Scheduled Tasks -->
<h2 class="section-title"><i class="fas fa-calendar-check"></i> المهام المجدولة</h2>
<div class="system-card">
<table>
<thead>
<tr>
<th>اسم المهمة</th>
<th>الجدول الزمني</th>
<th>آخر تشغيل</th>
<th>الحالة</th>
<th>الإجراءات</th>
</tr>
</thead>
<tbody>
@foreach($scheduledTasks as $task)
<tr>
<td><strong><i class="fas fa-cog" style="color:#2a7080;margin-left:0.5rem"></i>{{ $task['name'] }}</strong></td>
<td>{{ $task['schedule'] }}</td>
<td>{{ $task['last_run'] }}</td>
<td><span class="status-badge status-{{ $task['status'] == 'success' ? 'operational' : 'warning' }}">{{ $task['status'] }}</span></td>
<td>
<button style="background:#2a7080;color:#fff;border:none;padding:0.4rem 1rem;border-radius:8px;cursor:pointer;font-size:0.85rem;font-weight:600;margin-left:0.5rem" onclick="showNotification(true, 'تم تشغيل المهمة: {{ $task['name'] }}')">
<i class="fas fa-play"></i> تشغيل الآن
</button>
<button style="background:#666;color:#fff;border:none;padding:0.4rem 1rem;border-radius:8px;cursor:pointer;font-size:0.85rem;font-weight:600" onclick="showNotification(true, 'تم تعطيل المهمة: {{ $task['name'] }}')">
<i class="fas fa-pause"></i> تعطيل
</button>
</td>
</tr>
@endforeach
</tbody>
</table>
</div>

<!-- Error Analysis -->
<h2 class="section-title"><i class="fas fa-bug"></i> تحليل الأخطاء</h2>
<div style="display:grid;grid-template-columns:2fr 1fr;gap:2rem;margin-bottom:2rem">
<div class="system-card">
<h3><i class="fas fa-chart-bar"></i> الأخطاء حسب النوع</h3>
@foreach($errorsByType as $error)
<div style="margin-bottom:1.5rem">
<div style="display:flex;justify-content:space-between;margin-bottom:0.5rem">
<span style="color:#1a1a1a;font-weight:700">{{ $error['type'] }}</span>
<span style="color:#2a7080;font-weight:700">{{ $error['count'] }} خطأ ({{ $error['percentage'] }}%)</span>
</div>
<div class="progress-bar">
<div class="progress-fill" style="width:{{ $error['percentage'] }}%"></div>
</div>
</div>
@endforeach
</div>

<div class="system-card">
<h3><i class="fas fa-chart-pie"></i> ملخص الأخطاء</h3>
<canvas id="errorsChart" style="max-height:250px"></canvas>
</div>
</div>
@endif

<!-- IT Team Chat -->
<h2 class="section-title">
<i class="fas fa-comments"></i> محادثات الفريق التقني
@if($unreadMessagesCount > 0)
<span style="background:#ef4444;color:#fff;padding:0.3rem 0.8rem;border-radius:20px;font-size:0.9rem;margin-right:1rem">{{ $unreadMessagesCount }}</span>
@endif
</h2>
<div style="display:grid;grid-template-columns:350px 1fr;gap:2rem;margin-bottom:2rem;height:500px">
<!-- Users List -->
<div style="background:#fff;border-radius:18px;box-shadow:0 4px 15px rgba(0,0,0,0.08);overflow:hidden;border-top:4px solid #2a7080">
<div style="background:linear-gradient(135deg,#2a7080 0%,#1a5060 100%);color:#fff;padding:1.5rem;font-size:1.2rem;font-weight:700">
<i class="fas fa-users"></i> المستخدمون المتاحون
</div>
<div style="max-height:420px;overflow-y:auto" id="chatUsersList">
@forelse($chatUsers as $chatUser)
<div class="chat-user-item" data-user-id="{{ $chatUser->id }}" onclick="openChatWindow({{ $chatUser->id }}, '{{ $chatUser->name ?? $chatUser->email }}', '{{ $chatUser->role->display_name ?? ($chatUser->is_admin ? 'مسؤول' : ($chatUser->is_it_super ? 'IT Supervisor' : ($chatUser->is_it ? 'IT Crew' : 'مستخدم'))) }}')">
<div style="width:45px;height:45px;border-radius:50%;background:linear-gradient(135deg,#2a7080,#1a5060);display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:1.1rem">
{{ strtoupper(substr($chatUser->name ?? $chatUser->email, 0, 1)) }}
</div>
<div style="flex:1">
<div style="font-weight:700;color:#1a1a1a;margin-bottom:0.2rem">{{ $chatUser->name ?? $chatUser->email }}</div>
<div style="font-size:0.85rem;color:#666">
<i class="fas fa-{{ $chatUser->is_it_super ? 'user-shield' : ($chatUser->is_it ? 'user-cog' : ($chatUser->is_admin ? 'crown' : 'user-tag')) }}"></i>
{{ $chatUser->role->display_name ?? ($chatUser->is_admin ? 'مسؤول' : ($chatUser->is_it_super ? 'IT Supervisor' : ($chatUser->is_it ? 'IT Crew' : 'مستخدم'))) }}
</div>
</div>
<i class="fas fa-chevron-left" style="color:#2a7080"></i>
</div>
@empty
<div style="text-align:center;padding:3rem;color:#a0aec0">
<i class="fas fa-users-slash" style="font-size:3rem;margin-bottom:1rem;display:block"></i>
<div>لا يوجد مستخدمين متاحين</div>
</div>
@endforelse
</div>
</div>

<!-- Chat Window -->
<div style="background:#fff;border-radius:18px;box-shadow:0 4px 15px rgba(0,0,0,0.08);display:flex;flex-direction:column;border-top:4px solid #2a7080">
<div id="chatHeader" style="background:linear-gradient(135deg,#2a7080 0%,#1a5060 100%);color:#fff;padding:1.5rem;font-size:1.2rem;font-weight:700;border-radius:18px 18px 0 0;display:flex;align-items:center;gap:1rem">
<i class="fas fa-comment-dots"></i>
<span id="chatHeaderText">اختر مستخدم لبدء المحادثة</span>
</div>
<div id="chatMessages" style="flex:1;padding:1.5rem;overflow-y:auto;background:#f8f9fa;display:flex;align-items:center;justify-content:center;color:#a0aec0">
<div style="text-align:center">
<i class="fas fa-comments" style="font-size:4rem;margin-bottom:1rem;display:block;color:#2a7080"></i>
<div style="font-size:1.2rem;font-weight:600">اختر مستخدم من القائمة لبدء المحادثة</div>
</div>
</div>
<div id="chatInputArea" style="padding:1.5rem;border-top:2px solid #e5e7eb;display:none">
<div style="display:flex;gap:1rem">
<input type="text" id="chatMessageInput" placeholder="اكتب رسالتك هنا..." style="flex:1;padding:1rem;border:2px solid #e5e7eb;border-radius:12px;font-size:1rem;font-family:'Cairo',sans-serif" onkeypress="if(event.key==='Enter') sendChatMessage()">
<button onclick="sendChatMessage()" style="background:linear-gradient(135deg,#2a7080,#1a5060);color:#fff;border:none;padding:1rem 2rem;border-radius:12px;cursor:pointer;font-weight:700;font-size:1rem;white-space:nowrap">
<i class="fas fa-paper-plane"></i> إرسال
</button>
</div>
</div>
</div>
</div>

@if($isSupervisor)
<!-- Advanced Tools -->
<div style="display:grid;grid-template-columns:1fr 1fr;gap:2rem;margin-bottom:2rem">
<!-- System Terminal -->
<div class="system-card" style="border-left:5px solid #8b5cf6">
<h3><i class="fas fa-terminal"></i> طرفية النظام</h3>
<div style="background:#1a1a1a;padding:1.5rem;border-radius:12px;margin-bottom:1rem;font-family:monospace;color:#22c55e;min-height:200px;max-height:300px;overflow-y:auto" id="terminalOutput">
<div style="color:#666">$ جاهز لتنفيذ الأوامر...</div>
</div>
<div style="display:flex;gap:1rem">
<select id="commandSelect" style="flex:1;padding:0.8rem;border:2px solid #e5e7eb;border-radius:10px;font-size:1rem;font-family:monospace;background:#fff">
<option value="">اختر أمراً...</option>
<option value="php artisan cache:clear">مسح الذاكرة المؤقتة</option>
<option value="php artisan config:clear">مسح التكوينات</option>
<option value="php artisan route:clear">مسح المسارات</option>
<option value="php artisan view:clear">مسح العروض</option>
<option value="php artisan optimize">تحسين النظام</option>
<option value="php artisan migrate:status">حالة الترحيلات</option>
</select>
<button onclick="executeTerminalCommand()" style="background:linear-gradient(135deg,#8b5cf6,#7c3aed);color:#fff;border:none;padding:0.8rem 2rem;border-radius:10px;cursor:pointer;font-weight:700;font-size:1rem">
<i class="fas fa-play"></i> تنفيذ
</button>
</div>
</div>

<!-- Live Log Viewer -->
<div class="system-card" style="border-left:5px solid #ef4444">
<h3><i class="fas fa-file-alt"></i> عارض السجلات المباشر</h3>
<div style="background:#1a1a1a;padding:1.5rem;border-radius:12px;margin-bottom:1rem;font-family:monospace;color:#fbbf24;font-size:0.85rem;min-height:200px;max-height:300px;overflow-y:auto;white-space:pre-wrap;word-wrap:break-word" id="liveLogsOutput">
<div style="color:#666">جاري تحميل السجلات...</div>
</div>
<div style="display:flex;gap:1rem">
<button onclick="refreshLogs()" style="flex:1;background:linear-gradient(135deg,#ef4444,#dc2626);color:#fff;border:none;padding:0.8rem;border-radius:10px;cursor:pointer;font-weight:700;font-size:1rem">
<i class="fas fa-sync-alt"></i> تحديث السجلات
</button>
<button onclick="clearLogsDisplay()" style="background:#666;color:#fff;border:none;padding:0.8rem 1.5rem;border-radius:10px;cursor:pointer;font-weight:700;font-size:1rem">
<i class="fas fa-trash"></i> مسح العرض
</button>
</div>
</div>
</div>
@endif

<!-- System Health -->
<h2 class="section-title"><i class="fas fa-heartbeat"></i> صحة النظام</h2>
<div class="stats-grid">
<div class="stat-card">
<div class="stat-icon"><i class="fas fa-server"></i></div>
<div class="stat-label">حجم قاعدة البيانات</div>
<div class="stat-value">{{ $systemHealth['database_size'] }}</div>
</div>
<div class="stat-card">
<div class="stat-icon"><i class="fas fa-users"></i></div>
<div class="stat-label">إجمالي المستخدمين</div>
<div class="stat-value">{{ $systemHealth['total_users'] }}</div>
</div>
<div class="stat-card">
<div class="stat-icon"><i class="fas fa-plug"></i></div>
<div class="stat-label">الجلسات النشطة</div>
<div class="stat-value">{{ $systemHealth['active_sessions'] }}</div>
</div>
<div class="stat-card">
<div class="stat-icon"><i class="fas fa-clock"></i></div>
<div class="stat-label">وقت تشغيل الخادم</div>
<div class="stat-value">{{ $systemHealth['server_uptime'] }}</div>
</div>
</div>

<!-- Server Resources -->
<h2 class="section-title"><i class="fas fa-microchip"></i> موارد الخادم</h2>
<div class="system-card">
<div class="metric-row">
<span class="metric-label"><i class="fas fa-microchip"></i> استخدام المعالج (CPU)</span>
<span class="metric-value">{{ $serverResources['cpu_usage'] }}</span>
</div>
<div class="progress-bar">
<div class="progress-fill" style="width:{{ rtrim($serverResources['cpu_usage'], '%') }}%"></div>
</div>
<div class="metric-row" style="margin-top:1.5rem">
<span class="metric-label"><i class="fas fa-memory"></i> استخدام الذاكرة (RAM)</span>
<span class="metric-value">{{ $serverResources['memory_usage'] }}</span>
</div>
<div class="progress-bar">
<div class="progress-fill" style="width:{{ rtrim($serverResources['memory_usage'], '%') }}%"></div>
</div>
<div class="metric-row" style="margin-top:1.5rem">
<span class="metric-label"><i class="fas fa-hdd"></i> استخدام القرص الصلب</span>
<span class="metric-value">{{ $serverResources['disk_usage'] }}</span>
</div>
<div class="progress-bar">
<div class="progress-fill" style="width:{{ rtrim($serverResources['disk_usage'], '%') }}%"></div>
</div>
<div class="metric-row" style="margin-top:1.5rem">
<span class="metric-label"><i class="fas fa-network-wired"></i> حركة الشبكة</span>
<span class="metric-value">{{ $serverResources['network_traffic'] }}</span>
</div>
</div>

<!-- Security & Traffic -->
<div style="display:grid;grid-template-columns:1fr 1fr;gap:2rem;margin-bottom:2rem">
@if($isSupervisor)
<div class="system-card">
<h3><i class="fas fa-shield-alt"></i> مقاييس الأمان</h3>
<div class="metric-row">
<span class="metric-label">محاولات تسجيل دخول فاشلة</span>
<span class="metric-value"><span class="badge-count">{{ $securityMetrics['failed_logins_today'] }}</span></span>
</div>
<div class="metric-row">
<span class="metric-label">جلسات المسؤولين النشطة</span>
<span class="metric-value"><span class="badge-count">{{ $securityMetrics['active_admin_sessions'] }}</span></span>
</div>
<div class="metric-row">
<span class="metric-label">إعادة تعيين كلمات المرور</span>
<span class="metric-value"><span class="badge-count">{{ $securityMetrics['password_resets_today'] }}</span></span>
</div>
<div class="metric-row">
<span class="metric-label">أنشطة مشبوهة</span>
<span class="metric-value"><span class="badge-count">{{ $securityMetrics['suspicious_activities'] }}</span></span>
</div>
</div>
@else
<div class="system-card">
<h3><i class="fas fa-chart-line"></i> ملخص الأداء</h3>
<div class="metric-row">
<span class="metric-label">حالة النظام</span>
<span class="metric-value"><span class="status-badge status-operational">تعمل بشكل طبيعي</span></span>
</div>
<div class="metric-row">
<span class="metric-label">وقت التشغيل</span>
<span class="metric-value">{{ $systemHealth['server_uptime'] }}</span>
</div>
<div class="metric-row">
<span class="metric-label">الجلسات النشطة</span>
<span class="metric-value"><span class="badge-count">{{ $systemHealth['active_sessions'] }}</span></span>
</div>
<div class="metric-row">
<span class="metric-label">معدل الاستجابة</span>
<span class="metric-value">{{ $performanceMetrics['avg_response_time'] }}</span>
</div>
</div>
@endif

<div class="system-card">
<h3><i class="fas fa-chart-line"></i> تحليلات الزيارات</h3>
<div class="metric-row">
<span class="metric-label">زوار فريدون اليوم</span>
<span class="metric-value">{{ number_format($trafficAnalytics['unique_visitors_today']) }}</span>
</div>
<div class="metric-row">
<span class="metric-label">مشاهدات الصفحات</span>
<span class="metric-value">{{ number_format($trafficAnalytics['page_views_today']) }}</span>
</div>
<div class="metric-row">
<span class="metric-label">معدل الارتداد</span>
<span class="metric-value">{{ $trafficAnalytics['bounce_rate'] }}</span>
</div>
<div class="metric-row">
<span class="metric-label">متوسط مدة الجلسة</span>
<span class="metric-value">{{ $trafficAnalytics['avg_session_duration'] }}</span>
</div>
</div>
</div>

<!-- Performance & Database -->
<div style="display:grid;grid-template-columns:1fr 1fr;gap:2rem;margin-bottom:2rem">
<div class="system-card">
<h3><i class="fas fa-tachometer-alt"></i> مقاييس الأداء</h3>
<div class="metric-row">
<span class="metric-label">متوسط وقت الاستجابة</span>
<span class="metric-value">{{ $performanceMetrics['avg_response_time'] }}</span>
</div>
<div class="metric-row">
<span class="metric-label">إجمالي الطلبات اليوم</span>
<span class="metric-value">{{ number_format($performanceMetrics['total_requests_today']) }}</span>
</div>
<div class="metric-row">
<span class="metric-label">معدل الأخطاء</span>
<span class="metric-value">{{ $performanceMetrics['error_rate'] }}</span>
</div>
<div class="metric-row">
<span class="metric-label">معدل نجاح الذاكرة المؤقتة</span>
<span class="metric-value">{{ $performanceMetrics['cache_hit_rate'] }}</span>
</div>
</div>

<div class="system-card">
<h3><i class="fas fa-database"></i> إحصائيات قاعدة البيانات</h3>
<div class="metric-row">
<span class="metric-label">إجمالي الطلبات</span>
<span class="metric-value">{{ number_format($databaseStats['total_orders']) }}</span>
</div>
<div class="metric-row">
<span class="metric-label">إجمالي المنتجات</span>
<span class="metric-value">{{ number_format($databaseStats['total_products']) }}</span>
</div>
<div class="metric-row">
<span class="metric-label">إجمالي الفئات</span>
<span class="metric-value">{{ number_format($databaseStats['total_categories']) }}</span>
</div>
<div class="metric-row">
<span class="metric-label">إجمالي الإشعارات</span>
<span class="metric-value">{{ number_format($databaseStats['total_notifications']) }}</span>
</div>
</div>
</div>

<!-- Email & Payment -->
<div style="display:grid;grid-template-columns:1fr 1fr;gap:2rem;margin-bottom:2rem">
<div class="system-card">
<h3><i class="fas fa-envelope"></i> إحصائيات البريد الإلكتروني</h3>
<div class="metric-row">
<span class="metric-label">رسائل مرسلة اليوم</span>
<span class="metric-value">{{ $emailStats['emails_sent_today'] }}</span>
</div>
<div class="metric-row">
<span class="metric-label">رسائل قيد الانتظار</span>
<span class="metric-value">{{ $emailStats['emails_pending'] }}</span>
</div>
<div class="metric-row">
<span class="metric-label">معدل التسليم</span>
<span class="metric-value">{{ $emailStats['email_delivery_rate'] }}</span>
</div>
<div class="metric-row">
<span class="metric-label">معدل الفتح</span>
<span class="metric-value">{{ $emailStats['email_open_rate'] }}</span>
</div>
</div>

<div class="system-card">
<h3><i class="fas fa-credit-card"></i> بوابات الدفع</h3>
@foreach($paymentGateways as $gateway)
<div class="metric-row">
<span class="metric-label">{{ $gateway['name'] }}</span>
<span class="metric-value">
<span class="status-badge status-operational">{{ $gateway['status'] }}</span>
<small style="color:#666;margin-right:0.5rem">{{ $gateway['transactions_today'] }} معاملة</small>
</span>
</div>
@endforeach
</div>
</div>

<!-- Backup & Storage -->
<div style="display:grid;grid-template-columns:1fr 1fr;gap:2rem;margin-bottom:2rem">
@if($isSupervisor)
<div class="system-card">
<h3><i class="fas fa-cloud-upload-alt"></i> حالة النسخ الاحتياطي</h3>
<div class="metric-row">
<span class="metric-label">آخر نسخة احتياطية</span>
<span class="metric-value">{{ $backupStatus['last_backup'] }}</span>
</div>
<div class="metric-row">
<span class="metric-label">حجم النسخة الاحتياطية</span>
<span class="metric-value">{{ $backupStatus['backup_size'] }}</span>
</div>
<div class="metric-row">
<span class="metric-label">موقع التخزين</span>
<span class="metric-value">{{ $backupStatus['backup_location'] }}</span>
</div>
<div class="metric-row">
<span class="metric-label">النسخة الاحتياطية القادمة</span>
<span class="metric-value">{{ $backupStatus['next_backup'] }}</span>
</div>
</div>
@else
<div class="system-card">
<h3><i class="fas fa-tasks"></i> مهام المراقبة</h3>
<div class="metric-row">
<span class="metric-label">مراقبة الأداء</span>
<span class="metric-value"><span class="status-badge status-operational">نشط</span></span>
</div>
<div class="metric-row">
<span class="metric-label">مراقبة قاعدة البيانات</span>
<span class="metric-value"><span class="status-badge status-operational">نشط</span></span>
</div>
<div class="metric-row">
<span class="metric-label">مراقبة الطلبات</span>
<span class="metric-value"><span class="status-badge status-operational">نشط</span></span>
</div>
<div class="metric-row">
<span class="metric-label">مراقبة الزيارات</span>
<span class="metric-value"><span class="status-badge status-operational">نشط</span></span>
</div>
</div>
@endif

<div class="system-card">
<h3><i class="fas fa-hdd"></i> استخدام التخزين</h3>
@foreach($storageUsage as $type => $size)
<div class="metric-row">
<span class="metric-label">{{ ucfirst($type) }}</span>
<span class="metric-value">{{ $size }}</span>
</div>
@endforeach
</div>
</div>

<!-- API Status & System Info -->
<div style="display:grid;grid-template-columns:1fr 1fr;gap:2rem;margin-bottom:2rem">
<div class="system-card">
<h3><i class="fas fa-plug"></i> حالة API</h3>
@foreach($apiStatus as $api)
<div class="metric-row">
<span class="metric-label"><code class="api-endpoint">{{ $api['endpoint'] }}</code></span>
<span class="metric-value">
<span class="status-badge status-operational">{{ $api['status'] }}</span>
<small style="color:#666;margin-right:0.5rem">{{ $api['response_time'] }}</small>
</span>
</div>
@endforeach
</div>

<div class="system-card">
<h3><i class="fas fa-info-circle"></i> معلومات النظام</h3>
<div class="metric-row">
<span class="metric-label">إصدار PHP</span>
<span class="metric-value">{{ $systemHealth['php_version'] }}</span>
</div>
<div class="metric-row">
<span class="metric-label">إصدار Laravel</span>
<span class="metric-value">{{ $systemHealth['laravel_version'] }}</span>
</div>
<div class="metric-row">
<span class="metric-label">البيئة</span>
<span class="metric-value">{{ app()->environment() }}</span>
</div>
<div class="metric-row">
<span class="metric-label">المنطقة الزمنية</span>
<span class="metric-value">{{ config('app.timezone') }}</span>
</div>
</div>
</div>

<!-- Top Pages -->
<div class="system-card">
<h3><i class="fas fa-file-alt"></i> أكثر الصفحات زيارة</h3>
<table>
<thead>
<tr>
<th>الصفحة</th>
<th>عدد المشاهدات</th>
<th>النسبة</th>
</tr>
</thead>
<tbody>
@php $totalViews = collect($topPages)->sum('views'); @endphp
@foreach($topPages as $page)
<tr>
<td><code class="api-endpoint">{{ $page['page'] }}</code></td>
<td><strong>{{ number_format($page['views']) }}</strong></td>
<td>
<div class="progress-bar" style="width:150px">
<div class="progress-fill" style="width:{{ ($page['views'] / $totalViews) * 100 }}%"></div>
</div>
</td>
</tr>
@endforeach
</tbody>
</table>
</div>

<!-- Activity & Logs -->
<div style="display:grid;grid-template-columns:1fr 1fr;gap:2rem;margin-bottom:2rem">
<div class="system-card">
<h3><i class="fas fa-history"></i> النشاط الأخير</h3>
@foreach($recentActivity as $activity)
<div class="activity-item">
<div class="activity-action"><i class="fas fa-circle" style="font-size:0.5rem;color:#2a7080;margin-left:0.5rem"></i>{{ $activity['action'] }}</div>
<div class="activity-meta">{{ $activity['user'] }} • {{ $activity['time'] }}</div>
</div>
@endforeach
</div>

<div class="system-card">
<h3><i class="fas fa-exclamation-triangle"></i> سجل الأحداث</h3>
@foreach($errorLogs as $log)
<div class="log-item">
<span class="log-level {{ $log['level'] }}">{{ strtoupper($log['level']) }}</span>
<span class="log-message">{{ $log['message'] }}</span>
<span class="log-time">{{ $log['time'] }}</span>
</div>
@endforeach
</div>
</div>

<!-- Business Metrics -->
<h2 class="section-title"><i class="fas fa-chart-pie"></i> مقاييس الأعمال</h2>
<div class="stats-grid">
<div class="stat-card">
<div class="stat-icon"><i class="fas fa-dollar-sign"></i></div>
<div class="stat-label">المبيعات اليوم</div>
<div class="stat-value">${{ number_format($salesToday, 2) }}</div>
</div>
<div class="stat-card">
<div class="stat-icon"><i class="fas fa-shopping-cart"></i></div>
<div class="stat-label">الطلبات اليوم</div>
<div class="stat-value">{{ $ordersToday }}</div>
</div>
<div class="stat-card">
<div class="stat-icon"><i class="fas fa-users"></i></div>
<div class="stat-label">العملاء الجدد</div>
<div class="stat-value">{{ $customersMonth }}</div>
</div>
<div class="stat-card">
<div class="stat-icon"><i class="fas fa-chart-bar"></i></div>
<div class="stat-label">متوسط قيمة الطلب</div>
<div class="stat-value">${{ number_format($avgOrderValue, 2) }}</div>
</div>
</div>

<!-- Charts -->
<div style="display:grid;grid-template-columns:2fr 1fr;gap:2rem;margin-bottom:2rem">
<div class="chart-card">
<h3 class="chart-title"><i class="fas fa-chart-area"></i> المبيعات - آخر 7 أيام</h3>
<canvas id="salesChart"></canvas>
</div>
<div class="chart-card">
<h3 class="chart-title"><i class="fas fa-chart-pie"></i> حالة الطلبات</h3>
<canvas id="ordersChart"></canvas>
</div>
</div>

<!-- Recent Orders -->
<div class="system-card">
<h3><i class="fas fa-shopping-bag"></i> أحدث الطلبات</h3>
<table>
<thead>
<tr>
<th>رقم الطلب</th>
<th>العميل</th>
<th>المبلغ</th>
<th>الحالة</th>
<th>التاريخ</th>
</tr>
</thead>
<tbody>
@foreach($recentOrders as $order)
<tr>
<td><strong>{{ $order->order_number }}</strong></td>
<td>{{ $order->recipient_name }}</td>
<td><strong style="color:#2a7080">${{ number_format($order->total, 2) }}</strong></td>
<td>
<span class="status-badge status-{{ $order->status == 'delivered' ? 'operational' : ($order->status == 'pending' ? 'warning' : 'info') }}">
{{ $order->status }}
</span>
</td>
<td>{{ $order->created_at->format('Y-m-d H:i') }}</td>
</tr>
@endforeach
</tbody>
</table>
</div>

</div>

<script>
// Quick Actions Function
function executeAction(action, button) {
    // Disable button and show loading
    const originalHTML = button.innerHTML;
    button.disabled = true;
    button.style.opacity = '0.6';
    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i><span>جاري التنفيذ...</span>';
    
    // Get CSRF token
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    // Map action to route
    const routes = {
        'clear-cache': '/it/clear-cache',
        'update-system': '/it/update-system',
        'check-database': '/it/check-database',
        'create-backup': '/it/create-backup',
        'optimize-performance': '/it/optimize-performance',
        'security-scan': '/it/security-scan'
    };
    
    // Execute action
    fetch(routes[action], {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': token,
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        // Re-enable button
        button.disabled = false;
        button.style.opacity = '1';
        button.innerHTML = originalHTML;
        
        // Show notification
        showNotification(data.success, data.message);
    })
    .catch(error => {
        // Re-enable button
        button.disabled = false;
        button.style.opacity = '1';
        button.innerHTML = originalHTML;
        
        // Show error notification
        showNotification(false, 'حدث خطأ في الاتصال بالخادم');
        console.error('Error:', error);
    });
}

// Notification function
function showNotification(success, message) {
    // Create notification element
    const notification = document.createElement('div');
    notification.style.cssText = `
        position: fixed;
        top: 100px;
        left: 50%;
        transform: translateX(-50%);
        background: ${success ? 'linear-gradient(135deg, #22c55e 0%, #16a34a 100%)' : 'linear-gradient(135deg, #ef4444 0%, #dc2626 100%)'};
        color: white;
        padding: 1.5rem 2.5rem;
        border-radius: 15px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.3);
        z-index: 10000;
        font-size: 1.1rem;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 1rem;
        animation: slideDown 0.3s ease-out;
    `;
    
    notification.innerHTML = `
        <i class="fas fa-${success ? 'check-circle' : 'exclamation-circle'}" style="font-size: 1.5rem;"></i>
        <span>${message}</span>
    `;
    
    // Add animation
    const style = document.createElement('style');
    style.textContent = `
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
        @keyframes slideUp {
            from {
                opacity: 1;
                transform: translateX(-50%) translateY(0);
            }
            to {
                opacity: 0;
                transform: translateX(-50%) translateY(-20px);
            }
        }
    `;
    document.head.appendChild(style);
    
    // Add to page
    document.body.appendChild(notification);
    
    // Remove after 4 seconds
    setTimeout(() => {
        notification.style.animation = 'slideUp 0.3s ease-out';
        setTimeout(() => {
            notification.remove();
        }, 300);
    }, 4000);
}

// Sales Chart
const salesCtx = document.getElementById('salesChart').getContext('2d');
new Chart(salesCtx, {
    type: 'line',
    data: {
        labels: {!! json_encode(array_column($salesChartData, 'date')) !!},
        datasets: [{
            label: 'المبيعات ($)',
            data: {!! json_encode(array_column($salesChartData, 'sales')) !!},
            borderColor: '#2a7080',
            backgroundColor: 'rgba(42, 112, 128, 0.1)',
            tension: 0.4,
            fill: true,
            borderWidth: 3,
            pointRadius: 5,
            pointHoverRadius: 7,
            pointBackgroundColor: '#2a7080'
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

// Orders Status Chart
const ordersCtx = document.getElementById('ordersChart').getContext('2d');
new Chart(ordersCtx, {
    type: 'doughnut',
    data: {
        labels: {!! json_encode($ordersByStatus->pluck('status')) !!},
        datasets: [{
            data: {!! json_encode($ordersByStatus->pluck('count')) !!},
            backgroundColor: ['#22c55e', '#fbbf24', '#3b82f6', '#ef4444', '#8b5cf6'],
            borderWidth: 3,
            borderColor: '#fff'
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'bottom'
            }
        }
    }
});

@if($isSupervisor)
// Errors Chart
const errorsChartEl = document.getElementById('errorsChart');
if (errorsChartEl) {
    const errorsCtx = errorsChartEl.getContext('2d');
    new Chart(errorsCtx, {
        type: 'pie',
        data: {
            labels: {!! json_encode(array_column($errorsByType, 'type')) !!},
            datasets: [{
                data: {!! json_encode(array_column($errorsByType, 'count')) !!},
                backgroundColor: ['#ef4444', '#f59e0b', '#3b82f6', '#8b5cf6'],
                borderWidth: 3,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        font: {
                            family: 'Cairo',
                            size: 11
                        },
                        padding: 10
                    }
                }
            }
        }
    });
}

// Auto-refresh metrics every 30 seconds
setInterval(() => {
    // In production, you'd fetch new data via AJAX
    console.log('Refreshing real-time metrics...');
}, 30000);

// Terminal Command Execution
function executeTerminalCommand() {
    const select = document.getElementById('commandSelect');
    const command = select.value;
    const output = document.getElementById('terminalOutput');
    
    if (!command) {
        showNotification(false, 'الرجاء اختيار أمر للتنفيذ');
        return;
    }
    
    // Add command to output
    output.innerHTML += `\n<div style="color:#3b82f6">$ ${command}</div>`;
    output.innerHTML += `<div style="color:#fbbf24">جاري التنفيذ...</div>`;
    output.scrollTop = output.scrollHeight;
    
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    fetch('/it/execute-command', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': token,
            'Accept': 'application/json'
        },
        body: JSON.stringify({ command: command })
    })
    .then(response => response.json())
    .then(data => {
        const color = data.success ? '#22c55e' : '#ef4444';
        output.innerHTML += `<div style="color:${color}">${data.output}</div>`;
        output.scrollTop = output.scrollHeight;
        
        if (data.success) {
            showNotification(true, 'تم تنفيذ الأمر بنجاح');
        }
    })
    .catch(error => {
        output.innerHTML += `<div style="color:#ef4444">خطأ في الاتصال بالخادم</div>`;
        output.scrollTop = output.scrollHeight;
        console.error('Error:', error);
    });
}

// Live Logs Functions
function refreshLogs() {
    const output = document.getElementById('liveLogsOutput');
    output.innerHTML = '<div style="color:#fbbf24">جاري تحميل السجلات...</div>';
    
    fetch('/it/live-logs', {
        method: 'GET',
        headers: {
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            output.textContent = data.logs;
            output.scrollTop = output.scrollHeight;
            showNotification(true, 'تم تحديث السجلات');
        } else {
            output.innerHTML = `<div style="color:#ef4444">${data.logs}</div>`;
        }
    })
    .catch(error => {
        output.innerHTML = '<div style="color:#ef4444">خطأ في تحميل السجلات</div>';
        console.error('Error:', error);
    });
}

function clearLogsDisplay() {
    document.getElementById('liveLogsOutput').innerHTML = '<div style="color:#666">تم مسح العرض</div>';
    showNotification(true, 'تم مسح عرض السجلات');
}

// Auto-load logs on page load
window.addEventListener('load', () => {
    const logsOutput = document.getElementById('liveLogsOutput');
    if (logsOutput) {
        refreshLogs();
    }
});
@endif

// Chat System Variables
let currentChatUserId = null;
let currentChatUserName = '';
let chatRefreshInterval = null;

// Open Chat Window
function openChatWindow(userId, userName, userRole) {
    currentChatUserId = userId;
    currentChatUserName = userName;
    
    // Update header
    document.getElementById('chatHeaderText').innerHTML = `
        <div style="display:flex;align-items:center;gap:1rem;flex:1">
            <div style="width:40px;height:40px;border-radius:50%;background:#fff;color:#2a7080;display:flex;align-items:center;justify-content:center;font-weight:700">
                ${userName.charAt(0).toUpperCase()}
            </div>
            <div>
                <div style="font-size:1.1rem">${userName}</div>
                <div style="font-size:0.85rem;opacity:0.9">${userRole}</div>
            </div>
        </div>
    `;
    
    // Show input area
    document.getElementById('chatInputArea').style.display = 'block';
    
    // Load messages
    loadChatMessages(userId);
    
    // Auto-refresh messages every 5 seconds
    if (chatRefreshInterval) {
        clearInterval(chatRefreshInterval);
    }
    chatRefreshInterval = setInterval(() => {
        loadChatMessages(userId, true);
    }, 5000);
}

// Load Chat Messages
function loadChatMessages(userId, silent = false) {
    const messagesContainer = document.getElementById('chatMessages');
    
    if (!silent) {
        messagesContainer.innerHTML = '<div style="text-align:center;color:#2a7080"><i class="fas fa-spinner fa-spin" style="font-size:2rem"></i></div>';
    }
    
    fetch(`/api/chat/messages/${userId}`, {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            displayMessages(data.messages);
        } else {
            messagesContainer.innerHTML = '<div style="text-align:center;color:#ef4444">خطأ في تحميل الرسائل</div>';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        if (!silent) {
            messagesContainer.innerHTML = '<div style="text-align:center;color:#ef4444">خطأ في الاتصال بالخادم</div>';
        }
    });
}

// Display Messages
function displayMessages(messages) {
    const messagesContainer = document.getElementById('chatMessages');
    const currentUserId = {{ auth()->id() }};
    
    if (messages.length === 0) {
        messagesContainer.innerHTML = `
            <div style="text-align:center;color:#a0aec0">
                <i class="fas fa-comment-slash" style="font-size:3rem;margin-bottom:1rem;display:block"></i>
                <div>لا توجد رسائل بعد. ابدأ المحادثة!</div>
            </div>
        `;
        messagesContainer.style.display = 'flex';
        messagesContainer.style.alignItems = 'center';
        messagesContainer.style.justifyContent = 'center';
        return;
    }
    
    messagesContainer.style.display = 'block';
    messagesContainer.style.alignItems = 'unset';
    messagesContainer.style.justifyContent = 'unset';
    
    let html = '';
    messages.forEach(message => {
        const isSent = message.sender_id === currentUserId;
        const messageClass = isSent ? 'sent' : 'received';
        const time = new Date(message.created_at).toLocaleTimeString('ar-EG', { hour: '2-digit', minute: '2-digit' });
        
        html += `
            <div class="chat-message ${messageClass}">
                <div class="chat-message-bubble">
                    <div>${message.message}</div>
                    <div class="chat-message-time">${time}</div>
                </div>
            </div>
        `;
    });
    
    messagesContainer.innerHTML = html;
    messagesContainer.scrollTop = messagesContainer.scrollHeight;
}

// Send Chat Message
function sendChatMessage() {
    if (!currentChatUserId) {
        showNotification(false, 'الرجاء اختيار مستخدم أولاً');
        return;
    }
    
    const input = document.getElementById('chatMessageInput');
    const message = input.value.trim();
    
    if (!message) {
        showNotification(false, 'الرجاء كتابة رسالة');
        return;
    }
    
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    fetch('/chat', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': token,
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            receiver_id: currentChatUserId,
            message: message
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            input.value = '';
            loadChatMessages(currentChatUserId, true);
        } else {
            showNotification(false, 'فشل إرسال الرسالة');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification(false, 'خطأ في الاتصال بالخادم');
    });
}

// Cleanup on page unload
window.addEventListener('beforeunload', () => {
    if (chatRefreshInterval) {
        clearInterval(chatRefreshInterval);
    }
});
</script>
</body>
</html>
