<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<title>التقرير اليومي - Tulip Store</title>
<style>
@font-face {
    font-family: 'DejaVu Sans';
    src: url('{{ storage_path('fonts/DejaVuSans.ttf') }}') format('truetype');
    font-weight: normal;
    font-style: normal;
}
*{font-family:'DejaVu Sans', 'Arial', sans-serif;margin:0;padding:0;box-sizing:border-box}
body{font-size:12px;line-height:1.6;color:#333;direction:rtl}
.header{text-align:center;padding:20px;background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);color:#fff;margin-bottom:30px}
.header h1{font-size:28px;font-weight:700;margin-bottom:5px}
.header p{font-size:14px;opacity:0.9}
.section{margin-bottom:30px;page-break-inside:avoid}
.section-title{font-size:18px;font-weight:700;color:#667eea;border-bottom:3px solid #667eea;padding-bottom:8px;margin-bottom:15px}
.stats-grid{display:table;width:100%;margin-bottom:20px}
.stat-row{display:table-row}
.stat-cell{display:table-cell;padding:12px;border:1px solid #e0e0e0;background:#f8f9fa;text-align:center;width:16.66%}
.stat-label{font-size:10px;color:#666;margin-bottom:5px}
.stat-value{font-size:20px;font-weight:700;color:#2d3748}
table{width:100%;border-collapse:collapse;margin-top:10px}
th{background:#667eea;color:#fff;padding:10px;text-align:right;font-weight:700;font-size:11px}
td{padding:10px;border-bottom:1px solid #e0e0e0;font-size:11px}
tr:nth-child(even){background:#f8f9fa}
.footer{text-align:center;margin-top:40px;padding-top:20px;border-top:2px solid #e0e0e0;color:#666;font-size:10px}
.summary-box{background:#f8f9fa;padding:15px;border-radius:8px;border-right:4px solid #667eea;margin-bottom:15px}
.summary-item{display:inline-block;width:48%;margin-bottom:10px}
.summary-label{font-weight:600;color:#667eea}
.summary-value{font-weight:700;color:#2d3748;font-size:14px}
</style>
</head>
<body>

<div class="header">
<h1>التقرير اليومي الشامل</h1>
<p>Tulip Store - {{ \Carbon\Carbon::now()->format('Y-m-d') }}</p>
<p>تم إنشاء التقرير في: {{ \Carbon\Carbon::now()->format('Y-m-d H:i:s') }}</p>
</div>

<!-- Sales Summary -->
<div class="section">
<h2 class="section-title">📊 ملخص المبيعات</h2>
<div class="stats-grid">
<div class="stat-row">
<div class="stat-cell">
<div class="stat-label">اليوم</div>
<div class="stat-value">${{ number_format($salesReports['today'], 2) }}</div>
</div>
<div class="stat-cell">
<div class="stat-label">هذا الأسبوع</div>
<div class="stat-value">${{ number_format($salesReports['week'], 2) }}</div>
</div>
<div class="stat-cell">
<div class="stat-label">هذا الشهر</div>
<div class="stat-value">${{ number_format($salesReports['month'], 2) }}</div>
</div>
<div class="stat-cell">
<div class="stat-label">هذا العام</div>
<div class="stat-value">${{ number_format($salesReports['year'], 2) }}</div>
</div>
<div class="stat-cell">
<div class="stat-label">الشهر الماضي</div>
<div class="stat-value">${{ number_format($salesReports['last_month'], 2) }}</div>
</div>
<div class="stat-cell">
<div class="stat-label">إجمالي المبيعات</div>
<div class="stat-value">${{ number_format($salesReports['all_time'], 2) }}</div>
</div>
</div>
</div>
</div>

<!-- Orders Summary -->
<div class="section">
<h2 class="section-title">📦 ملخص الطلبات</h2>
<div class="stats-grid">
<div class="stat-row">
<div class="stat-cell">
<div class="stat-label">إجمالي الطلبات</div>
<div class="stat-value">{{ $orderReports['total'] }}</div>
</div>
<div class="stat-cell">
<div class="stat-label">قيد الانتظار</div>
<div class="stat-value">{{ $orderReports['pending'] }}</div>
</div>
<div class="stat-cell">
<div class="stat-label">تم التوصيل</div>
<div class="stat-value">{{ $orderReports['delivered'] }}</div>
</div>
<div class="stat-cell">
<div class="stat-label">ملغاة</div>
<div class="stat-value">{{ $orderReports['cancelled'] }}</div>
</div>
<div class="stat-cell">
<div class="stat-label">اليوم</div>
<div class="stat-value">{{ $orderReports['today'] }}</div>
</div>
<div class="stat-cell">
<div class="stat-label">هذا الشهر</div>
<div class="stat-value">{{ $orderReports['this_month'] }}</div>
</div>
</div>
</div>
</div>

<!-- Customers Summary -->
<div class="section">
<h2 class="section-title">👥 ملخص العملاء</h2>
<div class="stats-grid">
<div class="stat-row">
<div class="stat-cell">
<div class="stat-label">إجمالي العملاء</div>
<div class="stat-value">{{ $customerReports['total'] }}</div>
</div>
<div class="stat-cell">
<div class="stat-label">جدد اليوم</div>
<div class="stat-value">{{ $customerReports['new_today'] }}</div>
</div>
<div class="stat-cell">
<div class="stat-label">جدد هذا الأسبوع</div>
<div class="stat-value">{{ $customerReports['new_this_week'] }}</div>
</div>
<div class="stat-cell">
<div class="stat-label">جدد هذا الشهر</div>
<div class="stat-value">{{ $customerReports['new_this_month'] }}</div>
</div>
<div class="stat-cell">
<div class="stat-label">لديهم طلبات</div>
<div class="stat-value">{{ $customerReports['with_orders'] }}</div>
</div>
<div class="stat-cell">
<div class="stat-label">بدون طلبات</div>
<div class="stat-value">{{ $customerReports['without_orders'] }}</div>
</div>
</div>
</div>
</div>

<!-- Products Summary -->
<div class="section">
<h2 class="section-title">📦 ملخص المنتجات</h2>
<div class="stats-grid">
<div class="stat-row">
<div class="stat-cell">
<div class="stat-label">إجمالي المنتجات</div>
<div class="stat-value">{{ $productReports['total'] }}</div>
</div>
<div class="stat-cell">
<div class="stat-label">نشطة</div>
<div class="stat-value">{{ $productReports['active'] }}</div>
</div>
<div class="stat-cell">
<div class="stat-label">غير نشطة</div>
<div class="stat-value">{{ $productReports['inactive'] }}</div>
</div>
<div class="stat-cell">
<div class="stat-label">نفذت من المخزون</div>
<div class="stat-value">{{ $productReports['out_of_stock'] }}</div>
</div>
<div class="stat-cell">
<div class="stat-label">مخزون منخفض</div>
<div class="stat-value">{{ $productReports['low_stock'] }}</div>
</div>
</div>
</div>
</div>

<!-- Top Products -->
<div class="section">
<h2 class="section-title">⭐ أفضل 10 منتجات</h2>
<table>
<thead>
<tr>
<th style="width:10%">#</th>
<th style="width:50%">المنتج</th>
<th style="width:20%">الكمية المباعة</th>
<th style="width:20%">الإيرادات</th>
</tr>
</thead>
<tbody>
@foreach($topProducts as $index => $product)
<tr>
<td>{{ $index + 1 }}</td>
<td><strong>{{ $product->name }}</strong></td>
<td>{{ $product->total_sold }}</td>
<td>${{ number_format($product->revenue, 2) }}</td>
</tr>
@endforeach
</tbody>
</table>
</div>

<!-- Top Customers -->
<div class="section">
<h2 class="section-title">👑 أفضل 10 عملاء</h2>
<table>
<thead>
<tr>
<th style="width:10%">#</th>
<th style="width:50%">العميل</th>
<th style="width:20%">عدد الطلبات</th>
<th style="width:20%">إجمالي الإنفاق</th>
</tr>
</thead>
<tbody>
@foreach($topCustomers as $index => $customer)
<tr>
<td>{{ $index + 1 }}</td>
<td><strong>{{ $customer->name }}</strong></td>
<td>{{ $customer->orders_count }}</td>
<td>${{ number_format($customer->total_spent, 2) }}</td>
</tr>
@endforeach
</tbody>
</table>
</div>

<!-- Category Performance -->
<div class="section">
<h2 class="section-title">🏷️ أداء الفئات</h2>
<table>
<thead>
<tr>
<th style="width:10%">#</th>
<th style="width:50%">الفئة</th>
<th style="width:20%">الكمية المباعة</th>
<th style="width:20%">الإيرادات</th>
</tr>
</thead>
<tbody>
@foreach($categoryPerformance as $index => $category)
<tr>
<td>{{ $index + 1 }}</td>
<td><strong>{{ $category->name }}</strong></td>
<td>{{ $category->items_sold }}</td>
<td>${{ number_format($category->revenue, 2) }}</td>
</tr>
@endforeach
</tbody>
</table>
</div>

<!-- Key Insights -->
<div class="section">
<h2 class="section-title">💡 رؤى رئيسية</h2>
<div class="summary-box">
<div class="summary-item">
<span class="summary-label">متوسط قيمة الطلب:</span>
<span class="summary-value">${{ $orderReports['total'] > 0 ? number_format($salesReports['all_time'] / $orderReports['total'], 2) : '0.00' }}</span>
</div>
<div class="summary-item">
<span class="summary-label">معدل التحويل:</span>
<span class="summary-value">{{ $customerReports['total'] > 0 ? number_format(($customerReports['with_orders'] / $customerReports['total']) * 100, 1) : '0' }}%</span>
</div>
<div class="summary-item">
<span class="summary-label">معدل الإلغاء:</span>
<span class="summary-value">{{ $orderReports['total'] > 0 ? number_format(($orderReports['cancelled'] / $orderReports['total']) * 100, 1) : '0' }}%</span>
</div>
<div class="summary-item">
<span class="summary-label">معدل التوصيل:</span>
<span class="summary-value">{{ $orderReports['total'] > 0 ? number_format(($orderReports['delivered'] / $orderReports['total']) * 100, 1) : '0' }}%</span>
</div>
</div>
</div>

<div class="footer">
<p><strong>Tulip Store</strong> - نظام إدارة المتجر</p>
<p>هذا التقرير تم إنشاؤه تلقائياً بواسطة النظام</p>
<p>© {{ \Carbon\Carbon::now()->year }} جميع الحقوق محفوظة</p>
</div>

</body>
</html>
