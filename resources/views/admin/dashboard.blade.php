<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>لوحة الإدارة - Tulip Store</title>
<link rel="stylesheet" href="/css/store.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=El+Messiri:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
*{font-family:'El Messiri',sans-serif;margin:0;padding:0;box-sizing:border-box}
body{background:linear-gradient(135deg,#f5f7fa 0%,#e4e8ec 100%);min-height:100vh}

/* Hero Section */
.hero{background:linear-gradient(135deg,#2a7080 0%,#1a5060 100%);padding:2.5rem 2rem;margin-top:80px;position:relative;overflow:hidden}
.hero::before{content:'';position:absolute;top:-50%;right:-20%;width:60%;height:200%;background:radial-gradient(circle,rgba(255,255,255,0.1) 0%,transparent 60%);pointer-events:none}
.hero-content{max-width:1400px;margin:0 auto;display:flex;justify-content:space-between;align-items:center;position:relative;z-index:1}
.hero h1{font-size:2.5rem;font-weight:800;color:#fff;margin-bottom:0.5rem}
.hero p{color:#e8f4f8;font-size:1.1rem}
.hero-date{background:rgba(255,255,255,0.15);padding:0.8rem 1.5rem;border-radius:12px;color:#fff;font-weight:600;backdrop-filter:blur(10px)}

/* Container */
.dashboard-container{max-width:1400px;margin:0 auto;padding:2rem}

/* Stats Grid */
.stats-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:1.5rem;margin-bottom:2rem}
.stat-card{background:#fff;padding:1.8rem;border-radius:16px;box-shadow:0 4px 20px rgba(0,0,0,0.06);transition:all 0.3s;position:relative;overflow:hidden}
.stat-card:hover{transform:translateY(-5px);box-shadow:0 8px 30px rgba(0,0,0,0.12)}
.stat-card::before{content:'';position:absolute;top:0;right:0;width:100%;height:4px}
.stat-card.sales::before{background:linear-gradient(90deg,#4caf50,#8bc34a)}
.stat-card.orders::before{background:linear-gradient(90deg,#2196f3,#03a9f4)}
.stat-card.customers::before{background:linear-gradient(90deg,#ff9800,#ffc107)}
.stat-card.products::before{background:linear-gradient(90deg,#9c27b0,#e91e63)}
.stat-header{display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:1rem}
.stat-icon{width:55px;height:55px;border-radius:14px;display:flex;align-items:center;justify-content:center;font-size:1.5rem}
.stat-card.sales .stat-icon{background:linear-gradient(135deg,#4caf50,#8bc34a);color:#fff}
.stat-card.orders .stat-icon{background:linear-gradient(135deg,#2196f3,#03a9f4);color:#fff}
.stat-card.customers .stat-icon{background:linear-gradient(135deg,#ff9800,#ffc107);color:#fff}
.stat-card.products .stat-icon{background:linear-gradient(135deg,#9c27b0,#e91e63);color:#fff}
.stat-value{font-size:2.2rem;font-weight:800;color:#1a1a1a;margin-bottom:0.3rem}
.stat-label{color:#666;font-size:0.95rem;font-weight:600}
.stat-change{display:inline-flex;align-items:center;gap:0.3rem;font-size:0.85rem;padding:0.3rem 0.8rem;border-radius:20px;margin-top:0.8rem}
.stat-change.up{background:#e8f5e9;color:#2e7d32}
.stat-change.down{background:#ffebee;color:#c62828}
.stat-change.neutral{background:#f5f5f5;color:#666}

/* Quick Actions */
.section-title{font-size:1.4rem;font-weight:700;color:#1a1a1a;margin-bottom:1.5rem;display:flex;align-items:center;gap:0.8rem}
.section-title i{color:#2a7080}
.quick-actions{display:grid;grid-template-columns:repeat(4,1fr);gap:1rem;margin-bottom:2rem}
.quick-action-btn{background:linear-gradient(135deg,#2a7080 0%,#1a5060 100%);color:#fff;padding:1.2rem;border-radius:14px;text-align:center;cursor:pointer;transition:all 0.3s;text-decoration:none;display:flex;flex-direction:column;align-items:center;gap:0.8rem}
.quick-action-btn:hover{transform:translateY(-3px);box-shadow:0 8px 25px rgba(42,112,128,0.35)}
.quick-action-btn i{font-size:1.8rem}
.quick-action-btn span{font-weight:600;font-size:0.95rem}

/* Two Column Layout */
.two-columns{display:grid;grid-template-columns:2fr 1fr;gap:1.5rem;margin-bottom:2rem}

/* Tables */
.table-card{background:#fff;border-radius:16px;box-shadow:0 4px 20px rgba(0,0,0,0.06);overflow:hidden}
.table-header{padding:1.5rem;border-bottom:2px solid #f0f0f0;display:flex;justify-content:space-between;align-items:center}
.table-header h3{font-size:1.2rem;font-weight:700;color:#1a1a1a;display:flex;align-items:center;gap:0.6rem}
.table-header h3 i{color:#2a7080}
.view-all-btn{color:#2a7080;text-decoration:none;font-weight:600;font-size:0.9rem;display:flex;align-items:center;gap:0.4rem;transition:all 0.3s}
.view-all-btn:hover{color:#1a5060}
table{width:100%;border-collapse:collapse}
th{background:#f8f9fa;padding:1rem 1.2rem;text-align:right;font-weight:700;color:#555;font-size:0.85rem;text-transform:uppercase}
td{padding:1rem 1.2rem;border-bottom:1px solid #f5f5f5;font-size:0.95rem}
tr:hover{background:#fafafa}
.badge{display:inline-block;padding:0.35rem 0.9rem;border-radius:20px;font-size:0.8rem;font-weight:700}
.badge-pending{background:#fff3cd;color:#856404}
.badge-confirmed{background:#d1ecf1;color:#0c5460}
.badge-processing{background:#cce5ff;color:#004085}
.badge-shipped{background:#d4edda;color:#155724}
.badge-delivered{background:#d4edda;color:#155724}
.badge-cancelled{background:#f8d7da;color:#721c24}
.badge-paid{background:#d4edda;color:#155724}
.badge-failed{background:#f8d7da;color:#721c24}

/* Alerts Section */
.alerts-card{background:#fff;border-radius:16px;box-shadow:0 4px 20px rgba(0,0,0,0.06);padding:1.5rem}
.alert-item{display:flex;align-items:center;gap:1rem;padding:1rem;border-radius:12px;margin-bottom:0.8rem;transition:all 0.3s}
.alert-item:last-child{margin-bottom:0}
.alert-item.warning{background:#fff8e1}
.alert-item.danger{background:#ffebee}
.alert-item.info{background:#e3f2fd}
.alert-icon{width:45px;height:45px;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:1.2rem}
.alert-item.warning .alert-icon{background:#ffc107;color:#fff}
.alert-item.danger .alert-icon{background:#f44336;color:#fff}
.alert-item.info .alert-icon{background:#2196f3;color:#fff}
.alert-content h4{font-size:0.95rem;font-weight:700;color:#1a1a1a;margin-bottom:0.2rem}
.alert-content p{font-size:0.85rem;color:#666}

/* Progress Cards */
.progress-card{background:#fff;border-radius:16px;box-shadow:0 4px 20px rgba(0,0,0,0.06);padding:1.5rem;margin-bottom:1.5rem}
.progress-header{display:flex;justify-content:space-between;align-items:center;margin-bottom:1rem}
.progress-title{font-weight:700;color:#1a1a1a}
.progress-value{font-weight:800;color:#2a7080}
.progress-bar{height:10px;background:#e0e0e0;border-radius:10px;overflow:hidden}
.progress-fill{height:100%;border-radius:10px;transition:width 0.5s ease}
.progress-fill.green{background:linear-gradient(90deg,#4caf50,#8bc34a)}
.progress-fill.blue{background:linear-gradient(90deg,#2196f3,#03a9f4)}
.progress-fill.orange{background:linear-gradient(90deg,#ff9800,#ffc107)}

/* Responsive */
@media(max-width:1200px){
.stats-grid{grid-template-columns:repeat(2,1fr)}
.quick-actions{grid-template-columns:repeat(2,1fr)}
.two-columns{grid-template-columns:1fr}
}
@media(max-width:768px){
.stats-grid{grid-template-columns:1fr}
.quick-actions{grid-template-columns:1fr}
.hero-content{flex-direction:column;text-align:center;gap:1rem}
}
</style>
</head>
<body>
@include('components.navbar')

<!-- Hero Section -->
<section class="hero">
<div class="hero-content">
<div>
<h1><i class="fas fa-tachometer-alt"></i> لوحة الإدارة</h1>
<p>مرحباً {{ auth()->user()->name }}، إليك نظرة سريعة على متجرك</p>
</div>
<div class="hero-date">
<i class="fas fa-calendar-alt"></i>
{{ \Carbon\Carbon::now()->locale('ar')->translatedFormat('l، d F Y') }}
</div>
</div>
</section>

<div class="dashboard-container">
<!-- Stats Grid -->
<div class="stats-grid">
<div class="stat-card sales">
<div class="stat-header">
<div>
<div class="stat-value">${{ number_format($salesToday, 2) }}</div>
<div class="stat-label">مبيعات اليوم</div>
</div>
<div class="stat-icon"><i class="fas fa-dollar-sign"></i></div>
</div>
<span class="stat-change {{ $salesGrowthDaily >= 0 ? 'up' : 'down' }}">
<i class="fas fa-arrow-{{ $salesGrowthDaily >= 0 ? 'up' : 'down' }}"></i>
{{ abs(round($salesGrowthDaily, 1)) }}% عن أمس
</span>
</div>

<div class="stat-card orders">
<div class="stat-header">
<div>
<div class="stat-value">{{ $ordersToday }}</div>
<div class="stat-label">طلبات اليوم</div>
</div>
<div class="stat-icon"><i class="fas fa-shopping-cart"></i></div>
</div>
<span class="stat-change {{ $ordersGrowthDaily >= 0 ? 'up' : 'down' }}">
<i class="fas fa-arrow-{{ $ordersGrowthDaily >= 0 ? 'up' : 'down' }}"></i>
{{ abs(round($ordersGrowthDaily, 1)) }}% عن أمس
</span>
</div>

<div class="stat-card customers">
<div class="stat-header">
<div>
<div class="stat-value">{{ $customersTotal }}</div>
<div class="stat-label">إجمالي العملاء</div>
</div>
<div class="stat-icon"><i class="fas fa-users"></i></div>
</div>
<span class="stat-change up">
<i class="fas fa-user-plus"></i>
+{{ $customersMonth }} هذا الشهر
</span>
</div>

<div class="stat-card products">
<div class="stat-header">
<div>
<div class="stat-value">{{ $totalProducts }}</div>
<div class="stat-label">المنتجات</div>
</div>
<div class="stat-icon"><i class="fas fa-box"></i></div>
</div>
<span class="stat-change {{ $lowStock > 0 ? 'down' : 'neutral' }}">
<i class="fas fa-exclamation-triangle"></i>
{{ $lowStock }} مخزون منخفض
</span>
</div>
</div>

<!-- Quick Actions -->
<h3 class="section-title"><i class="fas fa-bolt"></i> إجراءات سريعة</h3>
<div class="quick-actions">
<a href="{{ route('admin.analytics') }}" class="quick-action-btn">
<i class="fas fa-chart-line"></i>
<span>التحليلات والرسوم البيانية</span>
</a>
<a href="{{ route('admin.reports.index') }}" class="quick-action-btn">
<i class="fas fa-file-alt"></i>
<span>التقارير</span>
</a>
<a href="{{ route('chat.index') }}" class="quick-action-btn">
<i class="fas fa-comments"></i>
<span>المحادثات</span>
</a>
<a href="{{ route('admin.orders.index') }}" class="quick-action-btn">
<i class="fas fa-shopping-bag"></i>
<span>إدارة الطلبات</span>
</a>
<a href="{{ route('admin.products.create') }}" class="quick-action-btn">
<i class="fas fa-plus-circle"></i>
<span>إضافة منتج</span>
</a>
<a href="{{ route('admin.users.index') }}" class="quick-action-btn">
<i class="fas fa-user-cog"></i>
<span>إدارة المستخدمين</span>
</a>
<a href="{{ route('admin.categories.index') }}" class="quick-action-btn">
<i class="fas fa-tags"></i>
<span>إدارة الفئات</span>
</a>
<a href="{{ route('admin.homepage.manage') }}" class="quick-action-btn">
<i class="fas fa-home"></i>
<span>إدارة الصفحة الرئيسية</span>
</a>
</div>

<!-- Two Column Layout -->
<div class="two-columns">
<!-- Recent Orders -->
<div class="table-card">
<div class="table-header">
<h3><i class="fas fa-clock"></i> أحدث الطلبات</h3>
<a href="{{ route('admin.orders.index') }}" class="view-all-btn">
عرض الكل <i class="fas fa-arrow-left"></i>
</a>
</div>
<table>
<thead>
<tr>
<th>رقم الطلب</th>
<th>العميل</th>
<th>المبلغ</th>
<th>حالة الطلب</th>
<th>حالة الدفع</th>
<th>التاريخ</th>
</tr>
</thead>
<tbody>
@foreach($recentOrders as $order)
<tr>
<td><strong style="color:#2a7080">{{ $order->order_number }}</strong></td>
<td>{{ $order->recipient_name }}</td>
<td><strong>${{ number_format($order->total, 2) }}</strong></td>
<td>
<span class="badge badge-{{ $order->status }}">
@switch($order->status)
@case('pending') قيد الانتظار @break
@case('confirmed') تم التأكيد @break
@case('processing') قيد التجهيز @break
@case('shipped') تم الشحن @break
@case('delivered') تم التوصيل @break
@case('cancelled') ملغي @break
@default {{ $order->status }}
@endswitch
</span>
</td>
<td>
<span class="badge badge-{{ $order->payment_status }}">
@switch($order->payment_status)
@case('pending') قيد الانتظار @break
@case('paid') تم الدفع @break
@case('failed') فشل @break
@default {{ $order->payment_status }}
@endswitch
</span>
</td>
<td>{{ $order->created_at->format('m/d H:i') }}</td>
</tr>
@endforeach
</tbody>
</table>
</div>

<!-- Alerts & Progress -->
<div>
<!-- Alerts -->
<div class="alerts-card" style="margin-bottom:1.5rem">
<h3 class="section-title" style="margin-bottom:1rem"><i class="fas fa-bell"></i> تنبيهات</h3>
@if($ordersPending > 0)
<div class="alert-item warning">
<div class="alert-icon"><i class="fas fa-hourglass-half"></i></div>
<div class="alert-content">
<h4>طلبات قيد الانتظار</h4>
<p>{{ $ordersPending }} طلب بحاجة للمراجعة</p>
</div>
</div>
@endif
@if($lowStock > 0)
<div class="alert-item danger">
<div class="alert-icon"><i class="fas fa-box-open"></i></div>
<div class="alert-content">
<h4>مخزون منخفض</h4>
<p>{{ $lowStock }} منتج بحاجة لإعادة التخزين</p>
</div>
</div>
@endif
@if($outOfStock > 0)
<div class="alert-item danger">
<div class="alert-icon"><i class="fas fa-times-circle"></i></div>
<div class="alert-content">
<h4>نفاد المخزون</h4>
<p>{{ $outOfStock }} منتج غير متوفر</p>
</div>
</div>
@endif
@if($pendingPayments > 0)
<div class="alert-item info">
<div class="alert-icon"><i class="fas fa-credit-card"></i></div>
<div class="alert-content">
<h4>مدفوعات معلقة</h4>
<p>{{ $pendingPayments }} طلب بانتظار الدفع</p>
</div>
</div>
@endif
@if($ordersPending == 0 && $lowStock == 0 && $outOfStock == 0 && $pendingPayments == 0)
<div class="alert-item info">
<div class="alert-icon"><i class="fas fa-check-circle"></i></div>
<div class="alert-content">
<h4>كل شيء على ما يرام!</h4>
<p>لا توجد تنبيهات حالياً</p>
</div>
</div>
@endif
</div>

<!-- Monthly Progress -->
<div class="progress-card">
<div class="progress-header">
<span class="progress-title"><i class="fas fa-target"></i> هدف المبيعات الشهري</span>
<span class="progress-value">{{ round($monthlyProgress, 1) }}%</span>
</div>
<div class="progress-bar">
<div class="progress-fill green" style="width:{{ min($monthlyProgress, 100) }}%"></div>
</div>
<p style="font-size:0.85rem;color:#666;margin-top:0.8rem">
${{ number_format($salesMonth, 2) }} من ${{ number_format($monthlyGoal, 2) }}
</p>
</div>

<!-- Order Completion Rate -->
<div class="progress-card">
<div class="progress-header">
<span class="progress-title"><i class="fas fa-check-double"></i> معدل إتمام الطلبات</span>
<span class="progress-value">{{ $ordersTotal > 0 ? round(($ordersDelivered / $ordersTotal) * 100, 1) : 0 }}%</span>
</div>
<div class="progress-bar">
<div class="progress-fill blue" style="width:{{ $ordersTotal > 0 ? ($ordersDelivered / $ordersTotal) * 100 : 0 }}%"></div>
</div>
<p style="font-size:0.85rem;color:#666;margin-top:0.8rem">
{{ $ordersDelivered }} طلب مكتمل من {{ $ordersTotal }}
</p>
</div>
</div>
</div>

<!-- Bottom Tables -->
<div class="two-columns">
<!-- Top Products -->
<div class="table-card">
<div class="table-header">
<h3><i class="fas fa-star"></i> المنتجات الأكثر مبيعاً</h3>
<a href="{{ route('admin.products.index') }}" class="view-all-btn">
عرض الكل <i class="fas fa-arrow-left"></i>
</a>
</div>
<table>
<thead>
<tr>
<th>المنتج</th>
<th>المبيعات</th>
<th>الإيرادات</th>
</tr>
</thead>
<tbody>
@foreach($topProducts as $product)
<tr>
<td><strong>{{ $product->name }}</strong></td>
<td>{{ $product->total_sold }} قطعة</td>
<td><strong style="color:#4caf50">${{ number_format($product->revenue, 2) }}</strong></td>
</tr>
@endforeach
</tbody>
</table>
</div>

<!-- Low Stock Products -->
<div class="table-card">
<div class="table-header">
<h3><i class="fas fa-exclamation-triangle" style="color:#ff9800"></i> مخزون منخفض</h3>
<a href="{{ route('admin.products.index') }}" class="view-all-btn">
عرض الكل <i class="fas fa-arrow-left"></i>
</a>
</div>
<table>
<thead>
<tr>
<th>المنتج</th>
<th>الكمية</th>
<th>الحالة</th>
</tr>
</thead>
<tbody>
@forelse($lowStockProducts as $product)
<tr>
<td><strong>{{ $product->name }}</strong></td>
<td><strong style="color:#f44336">{{ $product->stock }}</strong></td>
<td><span class="badge badge-cancelled">منخفض</span></td>
</tr>
@empty
<tr>
<td colspan="3" style="text-align:center;padding:2rem;color:#999">
<i class="fas fa-check-circle" style="font-size:2rem;display:block;margin-bottom:0.5rem;color:#4caf50"></i>
جميع المنتجات متوفرة
</td>
</tr>
@endforelse
</tbody>
</table>
</div>
</div>
</div>
</body>
</html>