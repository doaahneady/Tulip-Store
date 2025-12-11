<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>التحليلات والرسوم البيانية - Tulip Store</title>
<link rel="stylesheet" href="/css/store.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=El+Messiri:wght@400;500;600;700&display=swap" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<style>
*{font-family:'El Messiri',sans-serif;margin:0;padding:0;box-sizing:border-box}
body{background:#f8fafc;min-height:100vh}

.page-header{background:linear-gradient(135deg,#fa709a 0%,#fee140 100%);padding:3rem 2rem;margin-top:80px;position:relative;overflow:hidden}
.page-header::before{content:'';position:absolute;top:0;left:0;right:0;bottom:0;background:url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E")}
.header-content{max-width:1400px;margin:0 auto;display:flex;justify-content:space-between;align-items:center;position:relative;z-index:1}
.header-title{color:#fff}
.header-title h1{font-size:2.5rem;font-weight:800;margin-bottom:0.5rem;display:flex;align-items:center;gap:1rem;text-shadow:0 2px 10px rgba(0,0,0,0.1)}
.header-title p{opacity:0.95;font-size:1.1rem}
.back-btn{background:rgba(255,255,255,0.25);color:#fff;padding:0.8rem 1.5rem;border-radius:12px;text-decoration:none;font-weight:600;backdrop-filter:blur(10px);transition:all 0.3s;border:1px solid rgba(255,255,255,0.3)}
.back-btn:hover{background:rgba(255,255,255,0.35);transform:translateY(-2px)}

.container{max-width:1400px;margin:0 auto;padding:2rem}

.stats-row{display:grid;grid-template-columns:repeat(4,1fr);gap:1.5rem;margin-top:-3rem;margin-bottom:2rem;position:relative;z-index:10}
.stat-box{background:#fff;padding:1.5rem;border-radius:16px;box-shadow:0 10px 40px rgba(0,0,0,0.1);text-align:center;transition:all 0.3s}
.stat-box:hover{transform:translateY(-5px)}
.stat-box i{font-size:2rem;margin-bottom:0.8rem;background:linear-gradient(135deg,#fa709a,#fee140);-webkit-background-clip:text;-webkit-text-fill-color:transparent}
.stat-value{font-size:2rem;font-weight:800;color:#1a1a1a}
.stat-label{color:#666;font-size:0.9rem;margin-top:0.3rem}

.chart-grid{display:grid;grid-template-columns:repeat(2,1fr);gap:1.5rem;margin-bottom:2rem}
.chart-card{background:#fff;border-radius:20px;padding:1.5rem;box-shadow:0 4px 20px rgba(0,0,0,0.08);transition:all 0.3s}
.chart-card:hover{box-shadow:0 8px 30px rgba(0,0,0,0.12)}
.chart-card.full-width{grid-column:span 2}
.chart-header{display:flex;align-items:center;gap:0.8rem;margin-bottom:1.5rem;padding-bottom:1rem;border-bottom:2px solid #f5f5f5}
.chart-icon{width:45px;height:45px;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:1.2rem;color:#fff}
.chart-icon.pink{background:linear-gradient(135deg,#fa709a,#fee140)}
.chart-icon.blue{background:linear-gradient(135deg,#4facfe,#00f2fe)}
.chart-icon.green{background:linear-gradient(135deg,#11998e,#38ef7d)}
.chart-icon.purple{background:linear-gradient(135deg,#667eea,#764ba2)}
.chart-icon.orange{background:linear-gradient(135deg,#f093fb,#f5576c)}
.chart-title{font-size:1.15rem;font-weight:700;color:#1a1a1a}

@media(max-width:1024px){
.chart-grid{grid-template-columns:1fr}
.chart-card.full-width{grid-column:span 1}
.stats-row{grid-template-columns:repeat(2,1fr)}
}
@media(max-width:600px){
.stats-row{grid-template-columns:1fr}
.header-content{flex-direction:column;text-align:center;gap:1rem}
}
</style>
</head>
<body>
@include('components.navbar')

<section class="page-header">
<div class="header-content">
<div class="header-title">
<h1><i class="fas fa-chart-line"></i> التحليلات والرسوم البيانية</h1>
<p>تحليل مفصل لأداء متجرك</p>
</div>
<a href="{{ route('admin.dashboard') }}" class="back-btn"><i class="fas fa-arrow-right"></i> العودة</a>
</div>
</section>

<div class="container">
<div class="stats-row">
<div class="stat-box"><i class="fas fa-dollar-sign"></i><div class="stat-value">${{ number_format($salesMonth, 0) }}</div><div class="stat-label">مبيعات الشهر</div></div>
<div class="stat-box"><i class="fas fa-shopping-bag"></i><div class="stat-value">{{ $ordersMonth }}</div><div class="stat-label">طلبات الشهر</div></div>
<div class="stat-box"><i class="fas fa-receipt"></i><div class="stat-value">${{ number_format($avgOrderValue, 0) }}</div><div class="stat-label">متوسط قيمة الطلب</div></div>
<div class="stat-box"><i class="fas fa-redo"></i><div class="stat-value">{{ round($repeatCustomerRate, 1) }}%</div><div class="stat-label">العملاء العائدين</div></div>
</div>

<div class="chart-grid">
<!-- Sales Chart (30 days) - Full Width -->
<div class="chart-card full-width">
<div class="chart-header"><div class="chart-icon pink"><i class="fas fa-chart-area"></i></div><h3 class="chart-title">المبيعات - آخر 30 يوم</h3></div>
<canvas id="salesChart" height="80"></canvas>
</div>

<!-- Orders Status -->
<div class="chart-card">
<div class="chart-header"><div class="chart-icon blue"><i class="fas fa-chart-pie"></i></div><h3 class="chart-title">حالة الطلبات</h3></div>
<canvas id="ordersChart"></canvas>
</div>

<!-- Payment Methods -->
<div class="chart-card">
<div class="chart-header"><div class="chart-icon green"><i class="fas fa-credit-card"></i></div><h3 class="chart-title">طرق الدفع</h3></div>
<canvas id="paymentChart"></canvas>
</div>

<!-- Monthly Sales -->
<div class="chart-card">
<div class="chart-header"><div class="chart-icon purple"><i class="fas fa-calendar-alt"></i></div><h3 class="chart-title">المبيعات الشهرية - آخر 12 شهر</h3></div>
<canvas id="monthlyChart"></canvas>
</div>

<!-- Category Performance -->
<div class="chart-card">
<div class="chart-header"><div class="chart-icon orange"><i class="fas fa-tags"></i></div><h3 class="chart-title">أداء الفئات</h3></div>
<canvas id="categoryChart"></canvas>
</div>

<!-- Hourly Sales -->
<div class="chart-card">
<div class="chart-header"><div class="chart-icon pink"><i class="fas fa-clock"></i></div><h3 class="chart-title">المبيعات حسب الساعة (اليوم)</h3></div>
<canvas id="hourlyChart"></canvas>
</div>

<!-- Revenue by Payment -->
<div class="chart-card">
<div class="chart-header"><div class="chart-icon blue"><i class="fas fa-money-bill-wave"></i></div><h3 class="chart-title">الإيرادات حسب طريقة الدفع</h3></div>
<canvas id="revenuePaymentChart"></canvas>
</div>

<!-- Customer Segments -->
<div class="chart-card">
<div class="chart-header"><div class="chart-icon green"><i class="fas fa-users"></i></div><h3 class="chart-title">شرائح العملاء</h3></div>
<canvas id="customerSegmentChart"></canvas>
</div>

<!-- Weekly Hourly Sales -->
<div class="chart-card">
<div class="chart-header"><div class="chart-icon purple"><i class="fas fa-chart-bar"></i></div><h3 class="chart-title">متوسط المبيعات حسب الساعة (7 أيام)</h3></div>
<canvas id="weeklyHourlyChart"></canvas>
</div>

<!-- Top Days -->
<div class="chart-card">
<div class="chart-header"><div class="chart-icon orange"><i class="fas fa-trophy"></i></div><h3 class="chart-title">أفضل 5 أيام هذا الشهر</h3></div>
<canvas id="topDaysChart"></canvas>
</div>
</div>
</div>

<script>
Chart.defaults.font.family = "'El Messiri', sans-serif";

// 1. Sales Chart (30 days)
new Chart(document.getElementById('salesChart').getContext('2d'), {
    type: 'line',
    data: {
        labels: {!! json_encode(array_column($salesChartData, 'date')) !!},
        datasets: [{
            label: 'المبيعات ($)',
            data: {!! json_encode(array_column($salesChartData, 'sales')) !!},
            borderColor: '#fa709a',
            backgroundColor: 'rgba(250, 112, 154, 0.1)',
            tension: 0.4,
            fill: true,
            borderWidth: 3,
            pointRadius: 3,
            pointHoverRadius: 6
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: { legend: { display: false } },
        scales: {
            y: { beginAtZero: true, grid: { color: 'rgba(0,0,0,0.05)' } },
            x: { grid: { display: false } }
        }
    }
});

// 2. Orders Status Chart
new Chart(document.getElementById('ordersChart').getContext('2d'), {
    type: 'doughnut',
    data: {
        labels: {!! json_encode($ordersByStatus->pluck('status')->map(function($s) {
            return match($s) {
                'pending' => 'قيد الانتظار',
                'confirmed' => 'تم التأكيد',
                'processing' => 'قيد التجهيز',
                'shipped' => 'تم الشحن',
                'delivered' => 'تم التوصيل',
                'cancelled' => 'ملغي',
                default => $s
            };
        })) !!},
        datasets: [{
            data: {!! json_encode($ordersByStatus->pluck('count')) !!},
            backgroundColor: ['#ff9800', '#2196f3', '#03a9f4', '#4caf50', '#8bc34a', '#f44336']
        }]
    },
    options: { responsive: true, plugins: { legend: { position: 'bottom' } } }
});

// 3. Payment Methods Chart
new Chart(document.getElementById('paymentChart').getContext('2d'), {
    type: 'pie',
    data: {
        labels: {!! json_encode($ordersByPayment->pluck('payment_method')->map(function($p) {
            return match($p) {
                'cash' => 'نقدي',
                'card' => 'بطاقة',
                'syriatel' => 'سيرياتيل',
                'bank' => 'تحويل بنكي',
                default => $p
            };
        })) !!},
        datasets: [{
            data: {!! json_encode($ordersByPayment->pluck('count')) !!},
            backgroundColor: ['#4caf50', '#2196f3', '#ff9800', '#9c27b0', '#f44336']
        }]
    },
    options: { responsive: true, plugins: { legend: { position: 'bottom' } } }
});

// 4. Monthly Sales Chart
new Chart(document.getElementById('monthlyChart').getContext('2d'), {
    type: 'bar',
    data: {
        labels: {!! json_encode(array_column($monthlySalesData, 'month')) !!},
        datasets: [{
            label: 'المبيعات ($)',
            data: {!! json_encode(array_column($monthlySalesData, 'sales')) !!},
            backgroundColor: 'rgba(102, 126, 234, 0.7)',
            borderColor: '#667eea',
            borderWidth: 2,
            borderRadius: 8
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: { y: { beginAtZero: true, grid: { color: 'rgba(0,0,0,0.05)' } }, x: { grid: { display: false } } }
    }
});

// 5. Category Performance Chart
new Chart(document.getElementById('categoryChart').getContext('2d'), {
    type: 'bar',
    data: {
        labels: {!! json_encode($categoryPerformance->pluck('name')) !!},
        datasets: [{
            label: 'الإيرادات ($)',
            data: {!! json_encode($categoryPerformance->pluck('revenue')) !!},
            backgroundColor: 'rgba(17, 153, 142, 0.7)',
            borderColor: '#11998e',
            borderWidth: 2,
            borderRadius: 8
        }]
    },
    options: {
        responsive: true,
        indexAxis: 'y',
        plugins: { legend: { display: false } },
        scales: { x: { beginAtZero: true, grid: { color: 'rgba(0,0,0,0.05)' } }, y: { grid: { display: false } } }
    }
});

// 6. Hourly Sales Chart
new Chart(document.getElementById('hourlyChart').getContext('2d'), {
    type: 'line',
    data: {
        labels: {!! json_encode(array_column($hourlySales, 'hour')) !!},
        datasets: [{
            label: 'المبيعات ($)',
            data: {!! json_encode(array_column($hourlySales, 'sales')) !!},
            borderColor: '#fee140',
            backgroundColor: 'rgba(254, 225, 64, 0.1)',
            tension: 0.4,
            fill: true,
            borderWidth: 3
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: { y: { beginAtZero: true, grid: { color: 'rgba(0,0,0,0.05)' } }, x: { grid: { display: false } } }
    }
});

// 7. Revenue by Payment Method
new Chart(document.getElementById('revenuePaymentChart').getContext('2d'), {
    type: 'doughnut',
    data: {
        labels: {!! json_encode($revenueByPayment->pluck('payment_method')->map(function($p) {
            return match($p) {
                'cash' => 'نقدي',
                'card' => 'بطاقة',
                'syriatel' => 'سيرياتيل',
                'bank' => 'تحويل بنكي',
                default => $p
            };
        })) !!},
        datasets: [{
            data: {!! json_encode($revenueByPayment->pluck('revenue')) !!},
            backgroundColor: ['#4caf50', '#2196f3', '#ff9800', '#9c27b0', '#f44336']
        }]
    },
    options: { responsive: true, plugins: { legend: { position: 'bottom' } } }
});

// 8. Customer Segments
new Chart(document.getElementById('customerSegmentChart').getContext('2d'), {
    type: 'pie',
    data: {
        labels: ['عملاء جدد', 'عملاء عائدون'],
        datasets: [{
            data: [{{ $newCustomers }}, {{ $returningCustomers }}],
            backgroundColor: ['#4facfe', '#11998e']
        }]
    },
    options: { responsive: true, plugins: { legend: { position: 'bottom' } } }
});

// 9. Weekly Hourly Sales
new Chart(document.getElementById('weeklyHourlyChart').getContext('2d'), {
    type: 'bar',
    data: {
        labels: {!! json_encode(array_column($salesByHour, 'hour')) !!},
        datasets: [{
            label: 'متوسط المبيعات ($)',
            data: {!! json_encode(array_column($salesByHour, 'sales')) !!},
            backgroundColor: 'rgba(240, 147, 251, 0.7)',
            borderColor: '#f093fb',
            borderWidth: 2,
            borderRadius: 6
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: { y: { beginAtZero: true, grid: { color: 'rgba(0,0,0,0.05)' } }, x: { grid: { display: false } } }
    }
});

// 10. Top Days
new Chart(document.getElementById('topDaysChart').getContext('2d'), {
    type: 'bar',
    data: {
        labels: {!! json_encode($topDays->pluck('date')) !!},
        datasets: [{
            label: 'الإيرادات ($)',
            data: {!! json_encode($topDays->pluck('revenue')) !!},
            backgroundColor: 'rgba(245, 87, 108, 0.7)',
            borderColor: '#f5576c',
            borderWidth: 2,
            borderRadius: 8
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: { y: { beginAtZero: true, grid: { color: 'rgba(0,0,0,0.05)' } }, x: { grid: { display: false } } }
    }
});
</script>
</body>
</html>