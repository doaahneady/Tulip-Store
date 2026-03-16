@extends('dashboards.layouts.app')
@section('content')
@php $title = 'لوحة الإدارة'; $subtitle = 'نظرة عامة على أداء المتجر'; $it = $it ?? []; @endphp

<!-- مركز التحكم الإداري -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 mb-8">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-bold text-gray-800">مركز التحكم الإداري</h3>
        <div class="text-sm text-gray-500">الوصول السريع لجميع وظائف الإدارة</div>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
        <a href="{{ route('dashboard.admin.index') }}" class="qa-card qa-indigo">
            <i class="fas fa-globe"></i>
            <p>نظرة عامة للنظام</p>
        </a>
        <a href="{{ route('dashboard.admin.employees') }}" class="qa-card qa-green">
            <i class="fas fa-id-badge"></i>
            <p>قائمة الموظفين</p>
        </a>
        <a href="{{ route('dashboard.admin.users') }}" class="qa-card qa-blue">
            <i class="fas fa-users-cog"></i>
            <p>إدارة المستخدمين</p>
        </a>
        <a href="{{ route('dashboard.admin.roles') }}" class="qa-card qa-purple">
            <i class="fas fa-shield-alt"></i>
            <p>الأدوار والصلاحيات (RBAC)</p>
        </a>
        <a href="{{ route('dashboard.admin.orders') }}" class="qa-card qa-green">
            <i class="fas fa-clipboard-list"></i>
            <p>الطلبات والفلاتر والتجاوز</p>
        </a>
        <a href="{{ route('dashboard.admin.gifts') }}" class="qa-card qa-pink">
            <i class="fas fa-gift"></i>
            <p>إدارة الهدايا (Gifts)</p>
        </a>
        <a href="{{ route('dashboard.admin.mart') }}" class="qa-card qa-indigo">
            <i class="fas fa-store"></i>
            <p>إدارة المارت (Mart)</p>
        </a>
        <a href="{{ route('dashboard.admin.cross-department-kpis') }}" class="qa-card qa-emerald">
            <i class="fas fa-chart-bar"></i>
            <p>مؤشرات الأقسام (HR/Finance/CS/Drivers)</p>
        </a>
        <a href="{{ route('dashboard.admin.audit-logs') }}" class="qa-card qa-gray">
            <i class="fas fa-history"></i>
            <p>سجل النشاط والتدقيق</p>
        </a>
        <a href="{{ route('dashboard.admin.approvals') }}" class="qa-card qa-teal">
            <i class="fas fa-check-double"></i>
            <p>الموافقات الإدارية</p>
        </a>
        <a href="{{ route('dashboard.admin.alerts') }}" class="qa-card qa-red">
            <i class="fas fa-exclamation-triangle"></i>
            <p>تنبيهات الأخطاء والفشل (IT)</p>
        </a>
        <a href="{{ route('dashboard.admin.reassignment') }}" class="qa-card qa-orange">
            <i class="fas fa-exchange-alt"></i>
            <p>إعادة تعيين الطلبات/السائقين/التذاكر</p>
        </a>
        <a href="{{ route('dashboard.admin.features') }}" class="qa-card qa-yellow">
            <i class="fas fa-toggle-on"></i>
            <p>التبديل بين الميزات</p>
        </a>
        <a href="{{ route('dashboard.admin.settings') }}" class="qa-card qa-indigo">
            <i class="fas fa-cogs"></i>
            <p>إعدادات النظام</p>
        </a>
        <a href="{{ route('dashboard.admin.database-health') }}" class="qa-card qa-cyan">
            <i class="fas fa-database"></i>
            <p>صحة قاعدة البيانات والاستخدام</p>
        </a>
        <a href="{{ route('dashboard.admin.announcements') }}" class="qa-card qa-pink">
            <i class="fas fa-bullhorn"></i>
            <p>الإشعارات والإعلانات</p>
        </a>
        <a href="{{ route('dashboard.admin.export.system-report') }}" class="qa-card qa-gray">
            <i class="fas fa-file-export"></i>
            <p>التقارير والتصدير (CSV/PDF)</p>
        </a>
        <form method="POST" action="{{ route('dashboard.admin.emergency.maintenance-mode') }}" class="qa-card qa-red">
            @csrf
            <input type="hidden" name="maintenance_mode" value="true">
            <button type="submit" class="w-full text-right">
                <i class="fas fa-tools"></i>
                <p>إجراءات الطوارئ</p>
            </button>
        </form>
    </div>
</div>

<!-- IT System Overview -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 mb-8">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-bold text-gray-800"><i class="fas fa-server text-indigo-600 ml-2"></i>نظرة تقنية المعلومات</h3>
        <div class="text-sm text-gray-500">مؤشرات الصحة والأخطاء والأداء</div>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-4">
        <x-dashboard.stat-card title="حالة النظام" :value="$it['uptime'] ?? 'N/A'" icon="fas fa-heartbeat" color="green" />
        <x-dashboard.stat-card title="أخطاء اليوم" :value="number_format($it['errors_today'] ?? 0)" icon="fas fa-exclamation-triangle" color="red" />
        <x-dashboard.stat-card title="زمن استجابة API" :value="(($it['avg_response_ms'] ?? 0).'ms')" icon="fas fa-tachometer-alt" color="orange" />
        <x-dashboard.stat-card title="نسبة نجاح API" :value="(number_format($it['success_rate'] ?? 0).'%')" icon="fas fa-check-circle" color="teal" />
        <x-dashboard.stat-card title="مستخدمون نشطون الآن" :value="number_format($it['active_users_now'] ?? 0)" icon="fas fa-users" color="blue" />
        <x-dashboard.stat-card title="جلسات متزامنة" :value="number_format($it['concurrent_sessions'] ?? 0)" icon="fas fa-user-clock" color="purple" />
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 mb-8">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-bold text-gray-800"><i class="fas fa-sign-in-alt text-indigo-600 ml-2"></i>سجل تسجيل الدخول</h3>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead>
                <tr class="bg-gray-50 text-gray-600">
                    <th class="px-4 py-2 text-right">الوقت</th>
                    <th class="px-4 py-2 text-right">من</th>
                    <th class="px-4 py-2 text-right">IP</th>
                    <th class="px-4 py-2 text-right">الحالة</th>
                    <th class="px-4 py-2 text-right">الوصف</th>
                </tr>
            </thead>
            <tbody>
                @forelse(($loginLogs ?? []) as $l)
                    @php $who = $l->user?->email ?? $l->user?->name ?? (data_get($l->metadata, 'identifier') ?? '-'); @endphp
                    <tr class="border-t">
                        <td class="px-4 py-2 text-gray-600">{{ $l->created_at?->format('Y-m-d H:i:s') ?? '-' }}</td>
                        <td class="px-4 py-2 text-gray-900">{{ $who }}</td>
                        <td class="px-4 py-2 text-gray-600">{{ $l->ip_address ?? '-' }}</td>
                        <td class="px-4 py-2 text-gray-600">{{ $l->status ?? '-' }}</td>
                        <td class="px-4 py-2 text-gray-700">{{ $l->description ?? '-' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-4 py-6 text-center text-gray-500">لا توجد سجلات</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="stat-card bg-white rounded-2xl p-6 shadow-sm">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm">إجمالي المستخدمين</p>
                <h3 class="text-3xl font-bold text-gray-800 mt-1">{{ number_format($metrics['total_users']) }}</h3>
                <p class="text-green-500 text-sm mt-2"><i class="fas fa-arrow-up"></i> +{{ $metrics['new_users_today'] }} اليوم</p>
            </div>
            <div class="w-14 h-14 bg-blue-100 rounded-2xl flex items-center justify-center">
                <i class="fas fa-users text-blue-600 text-xl"></i>
            </div>
        </div>
    </div>
    
    <div class="stat-card bg-white rounded-2xl p-6 shadow-sm">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm">إجمالي الطلبات</p>
                <h3 class="text-3xl font-bold text-gray-800 mt-1">{{ number_format($metrics['total_orders']) }}</h3>
                <p class="text-orange-500 text-sm mt-2"><i class="fas fa-clock"></i> {{ $metrics['pending_orders'] }} قيد الانتظار</p>
            </div>
            <div class="w-14 h-14 bg-green-100 rounded-2xl flex items-center justify-center">
                <i class="fas fa-shopping-cart text-green-600 text-xl"></i>
            </div>
        </div>
    </div>
    
    <div class="stat-card bg-white rounded-2xl p-6 shadow-sm">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm">إجمالي المنتجات</p>
                <h3 class="text-3xl font-bold text-gray-800 mt-1">{{ number_format($metrics['total_products']) }}</h3>
                <p class="text-red-500 text-sm mt-2"><i class="fas fa-exclamation-triangle"></i> {{ $metrics['low_stock_products'] }} مخزون منخفض</p>
            </div>
            <div class="w-14 h-14 bg-purple-100 rounded-2xl flex items-center justify-center">
                <i class="fas fa-box text-purple-600 text-xl"></i>
            </div>
        </div>
    </div>
    
    <div class="stat-card bg-white rounded-2xl p-6 shadow-sm">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm">السائقين النشطين</p>
                <h3 class="text-3xl font-bold text-gray-800 mt-1">{{ $metrics['active_drivers'] }}</h3>
                <p class="text-gray-500 text-sm mt-2">من {{ $metrics['total_drivers'] }} سائق</p>
            </div>
            <div class="w-14 h-14 bg-yellow-100 rounded-2xl flex items-center justify-center">
                <i class="fas fa-truck text-yellow-600 text-xl"></i>
            </div>
        </div>
    </div>
</div>

<!-- Revenue Cards -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-gradient-to-br from-purple-500 to-indigo-600 rounded-2xl p-6 text-white">
        <p class="text-purple-200 text-sm">إيرادات اليوم</p>
        <h3 class="text-3xl font-bold mt-2">{{ number_format($revenue['today'], 2) }}ل.س</h3>
        <div class="mt-4 flex items-center gap-2">
            <span class="bg-white/20 px-2 py-1 rounded text-xs">{{ $metrics['orders_today'] }} طلب</span>
        </div>
    </div>
    
    <div class="bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl p-6 text-white">
        <p class="text-green-200 text-sm">إيرادات هذا الأسبوع</p>
        <h3 class="text-3xl font-bold mt-2">{{ number_format($revenue['this_week'], 2) }} ل.س</h3>
        <div class="mt-4 flex items-center gap-2">
            <span class="bg-white/20 px-2 py-1 rounded text-xs">{{ $metrics['new_users_week'] }} مستخدم جديد</span>
        </div>
    </div>
    
    <div class="bg-gradient-to-br from-orange-500 to-red-500 rounded-2xl p-6 text-white">
        <p class="text-orange-200 text-sm">إيرادات هذا الشهر</p>
        <h3 class="text-3xl font-bold mt-2">{{ number_format($revenue['this_month'], 2) }} ل.س</h3>
        <div class="mt-4 flex items-center gap-2">
            @php $growth = $revenue['last_month'] > 0 ? round((($revenue['this_month'] - $revenue['last_month']) / $revenue['last_month']) * 100, 1) : 0; @endphp
            <span class="bg-white/20 px-2 py-1 rounded text-xs">{{ $growth > 0 ? '+' : '' }}{{ $growth }}% من الشهر الماضي</span>
        </div>
    </div>
</div>

<!-- Charts Row -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <!-- Revenue Chart -->
    <div class="bg-white rounded-2xl p-6 shadow-sm">
        <h3 class="text-lg font-bold text-gray-800 mb-4">الإيرادات والطلبات (آخر 7 أيام)</h3>
        <canvas id="revenueChart" height="200"></canvas>
    </div>
    
    <!-- Order Status Chart -->
    <div class="bg-white rounded-2xl p-6 shadow-sm">
        <h3 class="text-lg font-bold text-gray-800 mb-4">حالة الطلبات</h3>
        <canvas id="orderStatusChart" height="200"></canvas>
    </div>
</div>

<!-- Geographic Sales Map -->
<div class="bg-white rounded-2xl p-6 shadow-sm mb-8">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-bold text-gray-800"><i class="fas fa-map-marked-alt text-indigo-600 ml-2"></i>خريطة المبيعات الجغرافية</h3>
        <div class="text-sm text-gray-500">تركيز الطلبات حسب مواقع التوصيل (آخر 30 يوم)</div>
    </div>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <div id="salesMap" class="w-full rounded-2xl border border-gray-200" style="height: 500px;"></div>
</div>

<!-- Tables Row -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Recent Orders -->
    <div class="bg-gray-800 rounded-2xl shadow-sm overflow-hidden border border-gray-700">
        <div class="p-6 border-b border-gray-700">
            <h3 class="text-lg font-bold text-white">أحدث الطلبات</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-300">رقم الطلب</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-300">العميل</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-300">المبلغ</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-300">الحالة</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-700">
                    @forelse($recentOrders as $order)
                    <tr class="hover:bg-gray-700">
                        <td class="px-6 py-4 text-sm font-medium text-white">#{{ $order->id }}</td>
                        <td class="px-6 py-4 text-sm text-gray-300">{{ $order->user->name ?? 'غير معروف' }}</td>
                        <td class="px-6 py-4 text-sm font-semibold text-white">{{ number_format($order->total, 2) }} ل.س</td>
                        <td class="px-6 py-4">
                            @php
                                $statusColors = ['pending' => 'yellow', 'confirmed' => 'blue', 'processing' => 'indigo', 'shipped' => 'purple', 'delivered' => 'green', 'cancelled' => 'red'];
                                $statusNames = ['pending' => 'قيد الانتظار', 'confirmed' => 'مؤكد', 'processing' => 'قيد التجهيز', 'shipped' => 'تم الشحن', 'delivered' => 'تم التوصيل', 'cancelled' => 'ملغي'];
                            @endphp
                            <span class="px-2 py-1 text-xs rounded-full bg-{{ $statusColors[$order->status] ?? 'gray' }}-200 text-{{ $statusColors[$order->status] ?? 'gray' }}-800">
                                {{ $statusNames[$order->status] ?? $order->status }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="px-6 py-4 text-center text-gray-400">لا توجد طلبات</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    <!-- Top Products -->
    <div class="bg-gray-800 rounded-2xl shadow-sm overflow-hidden border border-gray-700">
        <div class="p-6 border-b border-gray-700">
            <h3 class="text-lg font-bold text-white">المنتجات الأكثر مبيعاً</h3>
        </div>
        <div class="p-6 space-y-4">
            @forelse($topProducts as $index => $product)
            <div class="flex items-center gap-4">
                <div class="w-8 h-8 bg-purple-200 rounded-full flex items-center justify-center text-purple-800 font-bold text-sm">
                    {{ $index + 1 }}
                </div>
                <div class="flex-1">
                    <p class="font-semibold text-white">{{ $product->name }}</p>
                    <p class="text-sm text-gray-400">{{ $product->order_items_count ?? 0 }} مبيعات</p>
                </div>
                <p class="font-bold text-white">{{ number_format($product->price, 2) }}ل.س</p>
            </div>
            @empty
            <p class="text-center text-gray-400">لا توجد منتجات</p>
            @endforelse
        </div>
    </div>
</div>

<script>
// Revenue Chart
const revenueCtx = document.getElementById('revenueChart').getContext('2d');
new Chart(revenueCtx, {
    type: 'line',
    data: {
        labels: {!! json_encode(array_column($chartData, 'date')) !!},
        datasets: [{
            label: 'الإيرادات',
            data: {!! json_encode(array_column($chartData, 'revenue')) !!},
            borderColor: '#8B5CF6',
            backgroundColor: 'rgba(139, 92, 246, 0.1)',
            fill: true,
            tension: 0.4
        }, {
            label: 'الطلبات',
            data: {!! json_encode(array_column($chartData, 'orders')) !!},
            borderColor: '#10B981',
            backgroundColor: 'transparent',
            tension: 0.4
        }]
    },
    options: { responsive: true, plugins: { legend: { position: 'bottom' } } }
});

// Order Status Chart
const statusCtx = document.getElementById('orderStatusChart').getContext('2d');
new Chart(statusCtx, {
    type: 'doughnut',
    data: {
        labels: ['قيد الانتظار', 'مؤكد', 'قيد التجهيز', 'تم الشحن', 'تم التوصيل', 'ملغي'],
        datasets: [{
            data: [
                {{ $orderStatus['pending'] ?? 0 }},
                {{ $orderStatus['confirmed'] ?? 0 }},
                {{ $orderStatus['processing'] ?? 0 }},
                {{ $orderStatus['shipped'] ?? 0 }},
                {{ $orderStatus['delivered'] ?? 0 }},
                {{ $orderStatus['cancelled'] ?? 0 }}
            ],
            backgroundColor: ['#FCD34D', '#60A5FA', '#818CF8', '#A78BFA', '#34D399', '#F87171']
        }]
    },
    options: { responsive: true, plugins: { legend: { position: 'bottom' } } }
});
</script>
@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet.heat/dist/leaflet-heat.js"></script>
<script>
const salesMapEl = document.getElementById('salesMap');
if (salesMapEl) {
    const points = {!! json_encode($orderLocations->map(function($o){ return [(float)$o->delivery_lat, (float)$o->delivery_lng]; })->values()) !!};
    const hasPoints = Array.isArray(points) && points.length > 0;
    const defaultCenter = [23.8859, 45.0792];
    const map = L.map('salesMap').setView(hasPoints ? [points[0][0], points[0][1]] : defaultCenter, hasPoints ? 6 : 5);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 18,
        attribution: '&copy; OpenStreetMap'
    }).addTo(map);
    if (hasPoints) {
        const heat = L.heatLayer(points, {radius: 25, blur: 15, maxZoom: 12}).addTo(map);
        const bounds = L.latLngBounds(points.map(p => L.latLng(p[0], p[1])));
        map.fitBounds(bounds.pad(0.1));
    }
}
</script>
@endpush
@endsection
