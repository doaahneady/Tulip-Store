<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>التقارير - Tulip Store</title>
<link rel="stylesheet" href="/css/store.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=El+Messiri:wght@400;500;600;700&display=swap" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<style>
*{font-family:'El Messiri',sans-serif;margin:0;padding:0;box-sizing:border-box}
body{background:linear-gradient(135deg,#0f0c29 0%,#302b63 50%,#24243e 100%);min-height:100vh}

.page-header{background:transparent;padding:2rem;margin-top:80px}
.header-content{max-width:1400px;margin:0 auto;display:flex;justify-content:space-between;align-items:center}
.header-title{color:#fff}
.header-title h1{font-size:2.8rem;font-weight:800;margin-bottom:0.5rem;background:linear-gradient(135deg,#667eea,#764ba2,#f093fb);-webkit-background-clip:text;-webkit-text-fill-color:transparent}
.header-title p{color:rgba(255,255,255,0.7);font-size:1.1rem}
.header-actions{display:flex;gap:1rem}
.btn-header{background:rgba(255,255,255,0.1);color:#fff;padding:0.9rem 1.8rem;border-radius:50px;text-decoration:none;font-weight:600;backdrop-filter:blur(20px);transition:all 0.4s;border:1px solid rgba(255,255,255,0.2);display:flex;align-items:center;gap:0.6rem}
.btn-header:hover{background:linear-gradient(135deg,#667eea,#764ba2);border-color:transparent;transform:scale(1.05)}

.container{max-width:1400px;margin:0 auto;padding:0 2rem 2rem}

.date-card{background:rgba(255,255,255,0.05);border-radius:24px;padding:2rem;backdrop-filter:blur(20px);border:1px solid rgba(255,255,255,0.1);margin-bottom:2rem}
.date-header{display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem}
.date-title{font-size:1.2rem;font-weight:700;color:#fff;display:flex;align-items:center;gap:0.8rem}
.date-title i{background:linear-gradient(135deg,#667eea,#764ba2);-webkit-background-clip:text;-webkit-text-fill-color:transparent}
.date-form{display:flex;gap:1rem;align-items:flex-end;flex-wrap:wrap}
.date-group{display:flex;flex-direction:column}
.date-group label{font-size:0.85rem;font-weight:600;color:rgba(255,255,255,0.6);margin-bottom:0.4rem}
.date-input{padding:0.8rem 1.2rem;background:rgba(255,255,255,0.08);border:1px solid rgba(255,255,255,0.15);border-radius:12px;font-size:0.95rem;color:#fff;transition:all 0.3s}
.date-input:focus{outline:none;border-color:#667eea;background:rgba(255,255,255,0.12)}
.btn-filter{background:linear-gradient(135deg,#667eea,#764ba2);color:#fff;padding:0.8rem 2rem;border:none;border-radius:12px;font-weight:600;cursor:pointer;transition:all 0.3s}
.btn-filter:hover{transform:translateY(-3px);box-shadow:0 10px 30px rgba(102,126,234,0.4)}
.quick-dates{display:flex;gap:0.6rem;margin-top:1.2rem;flex-wrap:wrap}
.quick-btn{background:rgba(255,255,255,0.08);color:rgba(255,255,255,0.8);padding:0.6rem 1.2rem;border:1px solid rgba(255,255,255,0.1);border-radius:25px;font-size:0.85rem;cursor:pointer;transition:all 0.3s}
.quick-btn:hover{background:linear-gradient(135deg,#667eea,#764ba2);color:#fff;border-color:transparent}

.stats-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:1.5rem;margin-bottom:2rem}
.stat-card{background:rgba(255,255,255,0.05);padding:2rem;border-radius:20px;backdrop-filter:blur(20px);border:1px solid rgba(255,255,255,0.1);position:relative;overflow:hidden;transition:all 0.4s}
.stat-card::before{content:'';position:absolute;top:0;left:0;right:0;height:3px;background:linear-gradient(90deg,#667eea,#764ba2,#f093fb)}
.stat-card:hover{transform:translateY(-8px);border-color:rgba(102,126,234,0.5);box-shadow:0 20px 50px rgba(102,126,234,0.2)}
.stat-icon{position:absolute;bottom:1rem;left:1rem;font-size:3.5rem;background:linear-gradient(135deg,#667eea,#764ba2);-webkit-background-clip:text;-webkit-text-fill-color:transparent;opacity:0.15}
.stat-label{font-size:0.9rem;color:rgba(255,255,255,0.6);margin-bottom:0.5rem}
.stat-value{font-size:2.8rem;font-weight:800;background:linear-gradient(135deg,#fff,#e0e0e0);-webkit-background-clip:text;-webkit-text-fill-color:transparent}
.stat-sub{font-size:0.85rem;color:rgba(255,255,255,0.5);margin-top:0.3rem}

.content-card{background:rgba(255,255,255,0.05);border-radius:24px;backdrop-filter:blur(20px);border:1px solid rgba(255,255,255,0.1);overflow:hidden;margin-bottom:2rem}
.card-header{background:linear-gradient(135deg,rgba(102,126,234,0.3),rgba(118,75,162,0.3));padding:1.5rem 2rem;font-size:1.2rem;font-weight:700;color:#fff;display:flex;align-items:center;gap:0.8rem}
.card-header i{background:linear-gradient(135deg,#667eea,#f093fb);-webkit-background-clip:text;-webkit-text-fill-color:transparent}
table{width:100%;border-collapse:collapse}
th{background:rgba(255,255,255,0.03);padding:1rem 1.5rem;text-align:right;font-weight:600;color:rgba(255,255,255,0.7);font-size:0.85rem;text-transform:uppercase;letter-spacing:1px}
td{padding:1.2rem 1.5rem;border-bottom:1px solid rgba(255,255,255,0.05);color:#fff}
tr:hover{background:rgba(255,255,255,0.03)}
.rank{width:40px;height:40px;background:linear-gradient(135deg,#667eea,#764ba2);color:#fff;border-radius:12px;display:inline-flex;align-items:center;justify-content:center;font-weight:700}
.amount{font-weight:700;background:linear-gradient(135deg,#667eea,#f093fb);-webkit-background-clip:text;-webkit-text-fill-color:transparent;font-size:1.1rem}

.chart-card{background:rgba(255,255,255,0.05);border-radius:24px;padding:2rem;backdrop-filter:blur(20px);border:1px solid rgba(255,255,255,0.1);margin-bottom:2rem}
.chart-title{font-size:1.2rem;font-weight:700;color:#fff;margin-bottom:1.5rem;display:flex;align-items:center;gap:0.8rem}
.chart-title i{background:linear-gradient(135deg,#667eea,#f093fb);-webkit-background-clip:text;-webkit-text-fill-color:transparent}

.two-cols{display:grid;grid-template-columns:1fr 1fr;gap:2rem}

@media(max-width:1024px){.stats-grid{grid-template-columns:repeat(2,1fr)}.two-cols{grid-template-columns:1fr}}
@media(max-width:768px){.stats-grid{grid-template-columns:1fr}.header-content{flex-direction:column;text-align:center;gap:1rem}.date-form{flex-direction:column;width:100%}}
</style>
</head>
<body>
@include('components.navbar')

<section class="page-header">
<div class="header-content">
<div class="header-title"><h1><i class="fas fa-chart-line"></i> التقارير والإحصائيات</h1><p>تحليل شامل لأداء متجرك</p></div>
<div class="header-actions">
<a href="{{ route('admin.reports.export') }}" class="btn-header"><i class="fas fa-file-pdf"></i> تحميل PDF</a>
<a href="{{ route('admin.dashboard') }}" class="btn-header"><i class="fas fa-arrow-right"></i> العودة</a>
</div>
</div>
</section>

<div class="container">
<div class="date-card">
<div class="date-header"><div class="date-title"><i class="fas fa-calendar-alt"></i> اختر الفترة الزمنية</div></div>
<form method="GET" class="date-form">
<div class="date-group"><label>من تاريخ</label><input type="date" name="start_date" class="date-input" value="{{ $startDate->format('Y-m-d') }}"></div>
<div class="date-group"><label>إلى تاريخ</label><input type="date" name="end_date" class="date-input" value="{{ $endDate->format('Y-m-d') }}"></div>
<button type="submit" class="btn-filter"><i class="fas fa-search"></i> عرض</button>
</form>
<div class="quick-dates">
<button type="button" class="quick-btn" onclick="setRange(7)">7 أيام</button>
<button type="button" class="quick-btn" onclick="setRange(30)">30 يوم</button>
<button type="button" class="quick-btn" onclick="setRange(90)">3 أشهر</button>
<button type="button" class="quick-btn" onclick="setRange(365)">سنة</button>
</div>
</div>

<div class="stats-grid">
<div class="stat-card"><i class="fas fa-dollar-sign stat-icon"></i><div class="stat-label">Total Sales</div><div class="stat-value">${{ number_format($customSales, 2) }}</div><div class="stat-sub">During selected period</div></div>
<div class="stat-card"><i class="fas fa-shopping-cart stat-icon"></i><div class="stat-label">Total Orders</div><div class="stat-value">{{ $customOrders }}</div><div class="stat-sub">Orders</div></div>
<div class="stat-card"><i class="fas fa-users stat-icon"></i><div class="stat-label">New Customers</div><div class="stat-value">{{ $customCustomers }}</div><div class="stat-sub">New customers</div></div>
<div class="stat-card"><i class="fas fa-chart-line stat-icon"></i><div class="stat-label">Average Order</div><div class="stat-value">${{ $customOrders > 0 ? number_format($customSales / $customOrders, 2) : '0' }}</div><div class="stat-sub">Per order</div></div>
</div>

<div class="chart-card">
<h3 class="chart-title"><i class="fas fa-chart-area"></i> المبيعات اليومية</h3>
<canvas id="dailyChart" height="100"></canvas>
</div>

<div class="two-cols">
<div class="content-card">
<div class="card-header"><i class="fas fa-star"></i> أفضل المنتجات</div>
<table><thead><tr><th>#</th><th>Product</th><th>Quantity</th><th>Revenue</th></tr></thead>
<tbody>@forelse($topProductsRange as $i => $p)<tr><td><span class="rank">{{ $i+1 }}</span></td><td><strong>{{ $p->name }}</strong></td><td>{{ $p->total_sold }}</td><td><span class="amount">${{ number_format($p->revenue, 2) }}</span></td></tr>@empty<tr><td colspan="4" style="text-align:center;padding:2rem;color:rgba(255,255,255,0.5)">لا توجد بيانات</td></tr>@endforelse</tbody>
</table>
</div>
<div class="content-card">
<div class="card-header"><i class="fas fa-crown"></i> أفضل العملاء</div>
<table><thead><tr><th>#</th><th>Customer</th><th>Orders</th><th>Spending</th></tr></thead>
<tbody>@foreach($topCustomers->take(5) as $i => $c)<tr><td><span class="rank">{{ $i+1 }}</span></td><td><strong>{{ $c->name }}</strong></td><td>{{ $c->orders_count }}</td><td><span class="amount">${{ number_format($c->total_spent, 2) }}</span></td></tr>@endforeach</tbody>
</table>
</div>
</div>

<div class="stats-grid">
<div class="stat-card"><div class="stat-label">Today's Sales</div><div class="stat-value">${{ number_format($salesReports['today'], 2) }}</div></div>
<div class="stat-card"><div class="stat-label">Weekly Sales</div><div class="stat-value">${{ number_format($salesReports['week'], 2) }}</div></div>
<div class="stat-card"><div class="stat-label">Monthly Sales</div><div class="stat-value">${{ number_format($salesReports['month'], 2) }}</div></div>
<div class="stat-card"><div class="stat-label">Total Sales</div><div class="stat-value">${{ number_format($salesReports['all_time'], 2) }}</div></div>
</div>
</div>

<script>
function setRange(days){const e=new Date(),s=new Date();s.setDate(s.getDate()-days);document.querySelector('input[name="start_date"]').value=s.toISOString().split('T')[0];document.querySelector('input[name="end_date"]').value=e.toISOString().split('T')[0]}
new Chart(document.getElementById('dailyChart').getContext('2d'),{type:'line',data:{labels:{!! json_encode(array_column($dailyBreakdown,'display_date')) !!},datasets:[{label:'Sales',data:{!! json_encode(array_column($dailyBreakdown,'sales')) !!},borderColor:'#667eea',backgroundColor:'rgba(102,126,234,0.1)',tension:0.4,fill:true,borderWidth:3,pointBackgroundColor:'#764ba2',pointBorderColor:'#fff',pointBorderWidth:2,pointRadius:4}]},options:{responsive:true,plugins:{legend:{display:false}},scales:{y:{beginAtZero:true,grid:{color:'rgba(255,255,255,0.05)'},ticks:{color:'rgba(255,255,255,0.6)'}},x:{grid:{color:'rgba(255,255,255,0.05)'},ticks:{color:'rgba(255,255,255,0.6)'}}}}});
</script>
</body>
</html>