<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>إدارة الطلبات - Tulip Store</title>
<link rel="stylesheet" href="/css/store.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=El+Messiri:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
*{font-family:'El Messiri',sans-serif;margin:0;padding:0;box-sizing:border-box}
body{background:linear-gradient(135deg,#1e3c72 0%,#2a5298 50%,#1e3c72 100%);min-height:100vh}

.page-header{background:transparent;padding:2rem;margin-top:80px}
.header-content{max-width:1400px;margin:0 auto;display:flex;justify-content:space-between;align-items:center}
.header-title{color:#fff}
.header-title h1{font-size:2.8rem;font-weight:800;margin-bottom:0.5rem;background:linear-gradient(135deg,#ffd700,#ffaa00);-webkit-background-clip:text;-webkit-text-fill-color:transparent;display:flex;align-items:center;gap:1rem}
.header-title p{color:rgba(255,255,255,0.7);font-size:1.1rem}
.back-btn{background:rgba(255,255,255,0.1);color:#fff;padding:0.9rem 1.8rem;border-radius:50px;text-decoration:none;font-weight:600;backdrop-filter:blur(20px);transition:all 0.4s;border:1px solid rgba(255,255,255,0.2)}
.back-btn:hover{background:linear-gradient(135deg,#ffd700,#ffaa00);color:#1e3c72;border-color:transparent}

.container{max-width:1400px;margin:0 auto;padding:0 2rem 2rem}

.stats-row{display:grid;grid-template-columns:repeat(4,1fr);gap:1.5rem;margin-bottom:2rem}
.stat-box{background:rgba(255,255,255,0.08);padding:2rem;border-radius:20px;backdrop-filter:blur(20px);border:1px solid rgba(255,255,255,0.1);text-align:center;transition:all 0.4s;position:relative;overflow:hidden}
.stat-box::before{content:'';position:absolute;top:0;left:0;right:0;height:3px;background:linear-gradient(90deg,#ffd700,#ffaa00)}
.stat-box:hover{transform:translateY(-8px);border-color:rgba(255,215,0,0.4);box-shadow:0 20px 50px rgba(255,215,0,0.15)}
.stat-box i{font-size:2.5rem;margin-bottom:1rem;background:linear-gradient(135deg,#ffd700,#ffaa00);-webkit-background-clip:text;-webkit-text-fill-color:transparent}
.stat-value{font-size:2.5rem;font-weight:800;color:#fff}
.stat-label{color:rgba(255,255,255,0.6);font-size:0.95rem;margin-top:0.3rem}

.content-card{background:rgba(255,255,255,0.08);border-radius:24px;backdrop-filter:blur(20px);border:1px solid rgba(255,255,255,0.1);overflow:hidden;margin-bottom:2rem}
.card-header{padding:1.5rem 2rem;border-bottom:1px solid rgba(255,255,255,0.1);display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:1rem}
.card-title{font-size:1.3rem;font-weight:700;color:#fff;display:flex;align-items:center;gap:0.8rem}
.card-title i{background:linear-gradient(135deg,#ffd700,#ffaa00);-webkit-background-clip:text;-webkit-text-fill-color:transparent}

.filters{padding:1.5rem 2rem;background:rgba(0,0,0,0.15);display:flex;gap:1rem;align-items:flex-end;flex-wrap:wrap;border-bottom:1px solid rgba(255,255,255,0.05)}
.filter-group{display:flex;flex-direction:column;flex:1;min-width:150px}
.filter-group label{font-weight:600;color:rgba(255,255,255,0.7);margin-bottom:0.5rem;font-size:0.85rem}
.filter-group label i{margin-left:0.3rem;background:linear-gradient(135deg,#ffd700,#ffaa00);-webkit-background-clip:text;-webkit-text-fill-color:transparent}
.filter-input{padding:0.85rem 1.2rem;background:rgba(255,255,255,0.08);border:1px solid rgba(255,255,255,0.15);border-radius:12px;font-size:0.95rem;color:#fff;transition:all 0.3s;font-family:'El Messiri',sans-serif}
.filter-input::placeholder{color:rgba(255,255,255,0.4)}
.filter-input option{background:#1e3c72;color:#fff}
.filter-input:focus{outline:none;border-color:#ffd700;box-shadow:0 0 20px rgba(255,215,0,0.2)}
.btn-search{background:linear-gradient(135deg,#ffd700,#ffaa00);color:#1e3c72;padding:0.85rem 2rem;border:none;border-radius:12px;font-weight:700;cursor:pointer;transition:all 0.3s;white-space:nowrap}
.btn-search:hover{transform:translateY(-3px);box-shadow:0 10px 30px rgba(255,215,0,0.4)}

.table-wrapper{overflow-x:auto}
table{width:100%;border-collapse:collapse;min-width:900px}
th{background:rgba(0,0,0,0.2);padding:1.1rem 1.2rem;text-align:right;font-weight:600;color:rgba(255,255,255,0.8);font-size:0.85rem;text-transform:uppercase;letter-spacing:0.5px}
td{padding:1.1rem 1.2rem;border-bottom:1px solid rgba(255,255,255,0.05);color:#fff;font-size:0.9rem}
tr:hover{background:rgba(255,255,255,0.05)}

.order-number{font-weight:700;background:linear-gradient(135deg,#ffd700,#ffaa00);-webkit-background-clip:text;-webkit-text-fill-color:transparent}
.customer-name{font-weight:600;color:#fff}

.badge{display:inline-flex;align-items:center;gap:0.4rem;padding:0.5rem 1rem;border-radius:25px;font-size:0.8rem;font-weight:600}
.badge-pending{background:rgba(255,193,7,0.2);color:#ffc107;border:1px solid rgba(255,193,7,0.3)}
.badge-confirmed{background:rgba(23,162,184,0.2);color:#17a2b8;border:1px solid rgba(23,162,184,0.3)}
.badge-processing{background:rgba(0,123,255,0.2);color:#007bff;border:1px solid rgba(0,123,255,0.3)}
.badge-shipped{background:rgba(111,66,193,0.2);color:#9d7bff;border:1px solid rgba(111,66,193,0.3)}
.badge-delivered{background:rgba(40,167,69,0.2);color:#28a745;border:1px solid rgba(40,167,69,0.3)}
.badge-cancelled{background:rgba(220,53,69,0.2);color:#dc3545;border:1px solid rgba(220,53,69,0.3)}
.badge-paid{background:rgba(40,167,69,0.2);color:#28a745;border:1px solid rgba(40,167,69,0.3)}
.badge-failed{background:rgba(220,53,69,0.2);color:#dc3545;border:1px solid rgba(220,53,69,0.3)}

.payment-method{display:flex;align-items:center;gap:0.5rem;font-size:0.9rem;color:rgba(255,255,255,0.8)}
.payment-method i{background:linear-gradient(135deg,#ffd700,#ffaa00);-webkit-background-clip:text;-webkit-text-fill-color:transparent}

.btn-view{background:linear-gradient(135deg,#ffd700,#ffaa00);color:#1e3c72;padding:0.6rem 1.2rem;border-radius:10px;text-decoration:none;font-size:0.85rem;display:inline-flex;align-items:center;gap:0.4rem;font-weight:700;transition:all 0.3s}
.btn-view:hover{transform:translateY(-2px);box-shadow:0 8px 20px rgba(255,215,0,0.3)}

.empty-state{text-align:center;padding:4rem 2rem;color:rgba(255,255,255,0.4)}
.empty-state i{font-size:4rem;margin-bottom:1rem;opacity:0.3}

.pagination-wrapper{margin-top:1.5rem;display:flex;justify-content:center}
.pagination-wrapper nav{background:rgba(255,255,255,0.08);border-radius:16px;padding:0.5rem;backdrop-filter:blur(20px)}

@media(max-width:1024px){.stats-row{grid-template-columns:repeat(2,1fr)}}
@media(max-width:768px){.stats-row{grid-template-columns:1fr}.header-content{flex-direction:column;text-align:center;gap:1rem}.filters{flex-direction:column}}
</style>
</head>
<body>
@include('components.navbar')

@php
$totalOrders = $orders->total();
$pendingOrders = \App\Models\Order::where('status', 'pending')->count();
$deliveredOrders = \App\Models\Order::where('status', 'delivered')->count();
$totalRevenue = \App\Models\Order::where('payment_status', 'paid')->sum('total');
@endphp

<section class="page-header">
<div class="header-content">
<div class="header-title"><h1><i class="fas fa-shopping-bag"></i> إدارة الطلبات</h1><p>عرض وإدارة جميع طلبات المتجر</p></div>
<a href="{{ route('admin.dashboard') }}" class="back-btn"><i class="fas fa-arrow-right"></i> العودة</a>
</div>
</section>

<div class="container">
<div class="stats-row">
<div class="stat-box"><i class="fas fa-shopping-cart"></i><div class="stat-value">{{ $totalOrders }}</div><div class="stat-label">إجمالي الطلبات</div></div>
<div class="stat-box"><i class="fas fa-clock"></i><div class="stat-value">{{ $pendingOrders }}</div><div class="stat-label">قيد الانتظار</div></div>
<div class="stat-box"><i class="fas fa-check-circle"></i><div class="stat-value">{{ $deliveredOrders }}</div><div class="stat-label">تم التوصيل</div></div>
<div class="stat-box"><i class="fas fa-dollar-sign"></i><div class="stat-value">${{ number_format($totalRevenue, 0) }}</div><div class="stat-label">إجمالي الإيرادات</div></div>
</div>

<div class="content-card">
<div class="card-header"><h3 class="card-title"><i class="fas fa-list"></i> قائمة الطلبات</h3></div>

<form method="GET" class="filters">
<div class="filter-group" style="flex:2"><label><i class="fas fa-search"></i> بحث</label><input type="text" name="search" class="filter-input" placeholder="رقم الطلب، اسم العميل، رقم الهاتف" value="{{ request('search') }}"></div>
<div class="filter-group"><label><i class="fas fa-filter"></i> حالة الطلب</label>
<select name="status" class="filter-input"><option value="">الكل</option><option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>قيد الانتظار</option><option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>تم التأكيد</option><option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>قيد التجهيز</option><option value="shipped" {{ request('status') == 'shipped' ? 'selected' : '' }}>تم الشحن</option><option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>تم التوصيل</option><option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>ملغي</option></select></div>
<div class="filter-group"><label><i class="fas fa-credit-card"></i> حالة الدفع</label>
<select name="payment_status" class="filter-input"><option value="">الكل</option><option value="pending" {{ request('payment_status') == 'pending' ? 'selected' : '' }}>قيد الانتظار</option><option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>تم الدفع</option><option value="failed" {{ request('payment_status') == 'failed' ? 'selected' : '' }}>فشل</option></select></div>
<div class="filter-group"><label><i class="fas fa-wallet"></i> طريقة الدفع</label>
<select name="payment_method" class="filter-input"><option value="">الكل</option><option value="cash" {{ request('payment_method') == 'cash' ? 'selected' : '' }}>نقدي</option><option value="card" {{ request('payment_method') == 'card' ? 'selected' : '' }}>بطاقة</option><option value="syriatel" {{ request('payment_method') == 'syriatel' ? 'selected' : '' }}>Syriatel</option><option value="bank" {{ request('payment_method') == 'bank' ? 'selected' : '' }}>تحويل بنكي</option></select></div>
<button type="submit" class="btn-search"><i class="fas fa-search"></i> بحث</button>
</form>

<div class="table-wrapper">
<table>
<thead><tr><th>رقم الطلب</th><th>العميل</th><th>الهاتف</th><th>التاريخ</th><th>حالة الطلب</th><th>حالة الدفع</th><th>طريقة الدفع</th><th>المبلغ</th><th>الإجراءات</th></tr></thead>
<tbody>
@forelse($orders as $order)
<tr>
<td><span class="order-number">{{ $order->order_number }}</span></td>
<td><span class="customer-name">{{ $order->recipient_name }}</span></td>
<td>{{ $order->phone }}</td>
<td>{{ $order->created_at->format('Y-m-d H:i') }}</td>
<td><span class="badge badge-{{ $order->status }}">@switch($order->status)@case('pending')<i class="fas fa-clock"></i> قيد الانتظار @break @case('confirmed')<i class="fas fa-check"></i> تم التأكيد @break @case('processing')<i class="fas fa-cog"></i> قيد التجهيز @break @case('shipped')<i class="fas fa-truck"></i> تم الشحن @break @case('delivered')<i class="fas fa-check-double"></i> تم التوصيل @break @case('cancelled')<i class="fas fa-times"></i> ملغي @break @endswitch</span></td>
<td><span class="badge badge-{{ $order->payment_status }}">@switch($order->payment_status)@case('pending')<i class="fas fa-hourglass-half"></i> قيد الانتظار @break @case('paid')<i class="fas fa-check-circle"></i> تم الدفع @break @case('failed')<i class="fas fa-times-circle"></i> فشل @break @endswitch</span></td>
<td><span class="payment-method">@switch($order->payment_method)@case('cash')<i class="fas fa-money-bill-wave"></i> نقدي @break @case('card')<i class="fas fa-credit-card"></i> بطاقة @break @case('syriatel')<i class="fas fa-mobile-alt"></i> Syriatel @break @case('bank')<i class="fas fa-university"></i> تحويل @break @endswitch</span></td>
<td><strong style="color:#ffd700">${{ number_format($order->total, 2) }}</strong></td>
<td><a href="/admin/orders/{{ $order->id }}" class="btn-view"><i class="fas fa-eye"></i> عرض</a></td>
</tr>
@empty
<tr><td colspan="9"><div class="empty-state"><i class="fas fa-inbox"></i><p>لا توجد طلبات</p></div></td></tr>
@endforelse
</tbody>
</table>
</div>
</div>

<div class="pagination-wrapper">{{ $orders->appends(request()->query())->links() }}</div>
</div>
</body>
</html>