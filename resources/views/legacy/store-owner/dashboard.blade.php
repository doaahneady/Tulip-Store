@extends('layouts.dashboard')

@section('title', 'لوحة المتجر')
@section('page-title', 'لوحة تحكم المتجر')
@section('role-gradient', 'from-purple-500 to-purple-700')
@section('role-icon', 'fas fa-store')
@section('role-label', 'صاحب المتجر')

@section('sidebar-menu')
    @include('components.dashboard.sidebar-item', ['href' => route('store.index'), 'icon' => 'fas fa-tachometer-alt', 'label' => 'لوحة التحكم', 'active' => true])
    @include('components.dashboard.sidebar-item', ['href' => route('store.products'), 'icon' => 'fas fa-box', 'label' => 'منتجاتي'])
    @include('components.dashboard.sidebar-item', ['href' => route('store.orders'), 'icon' => 'fas fa-shopping-bag', 'label' => 'الطلبات'])
    @include('components.dashboard.sidebar-item', ['href' => route('store.analytics'), 'icon' => 'fas fa-chart-line', 'label' => 'التحليلات'])
    @include('components.dashboard.sidebar-item', ['href' => route('store.earnings'), 'icon' => 'fas fa-star', 'label' => 'الأرباح'])
    
    <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
        <p class="px-3 text-xs font-semibold text-gray-400 uppercase mb-2">إجراءات سريعة</p>
        @include('components.dashboard.sidebar-item', ['href' => route('store.products.create'), 'icon' => 'fas fa-plus', 'label' => 'إضافة منتج'])
    </div>
@endsection

@section('content')
<!-- Welcome Banner -->
<div class="bg-gradient-to-br from-indigo-600 via-purple-600 to-pink-500 rounded-3xl p-8 mb-8 text-white relative overflow-hidden shadow-2xl">
    <!-- Decorative Elements -->
    <div class="absolute top-0 right-0 w-64 h-64 opacity-10">
        <div class="absolute top-8 right-8 w-32 h-32 bg-white rounded-full animate-pulse"></div>
        <div class="absolute top-20 right-32 w-16 h-16 bg-white rounded-full animate-pulse delay-75"></div>
        <div class="absolute top-32 right-16 w-8 h-8 bg-white rounded-full animate-pulse delay-150"></div>
    </div>
    
    <div class="relative z-10 flex items-center justify-between">
        <div class="flex-1">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center backdrop-blur-sm">
                    <i class="fas fa-store text-2xl"></i>
                </div>
                <div>
                    <h2 class="text-3xl font-bold">مرحباً بك في متجرك!</h2>
                    <p class="text-white/90 text-lg">{{ $store->name ?? 'متجرك' }}</p>
                </div>
            </div>
            <p class="text-white/80 text-lg">تابع مبيعاتك ومنتجاتك وحقق أهدافك التجارية</p>
            
            <!-- Quick Stats in Banner -->
            <div class="flex gap-6 mt-6">
                <div class="bg-white/10 backdrop-blur-sm rounded-2xl px-4 py-3">
                    <div class="text-2xl font-bold">{{ $totalProducts ?? 0 }}</div>
                    <div class="text-white/80 text-sm">منتج</div>
                </div>
                <div class="bg-white/10 backdrop-blur-sm rounded-2xl px-4 py-3">
                    <div class="text-2xl font-bold">{{ $ordersToday ?? 0 }}</div>
                    <div class="text-white/80 text-sm">طلب اليوم</div>
                </div>
                <div class="bg-white/10 backdrop-blur-sm rounded-2xl px-4 py-3">
                    <div class="text-2xl font-bold">${{ number_format($salesToday ?? 0, 0) }}</div>
                    <div class="text-white/80 text-sm">مبيعات اليوم</div>
                </div>
            </div>
        </div>
        
        <div class="hidden lg:flex flex-col gap-3">
            <a href="{{ route('store.products.create') }}" class="flex items-center gap-3 px-6 py-3 bg-white/20 backdrop-blur-sm rounded-2xl hover:bg-white/30 transition-all duration-300 hover:scale-105 hover:shadow-lg">
                <i class="fas fa-plus text-xl"></i>
                <span class="font-semibold">إضافة منتج جديد</span>
            </a>
            <a href="{{ route('store.orders') }}" class="flex items-center gap-3 px-6 py-3 bg-white/10 backdrop-blur-sm rounded-2xl hover:bg-white/20 transition-all duration-300">
                <i class="fas fa-shopping-bag"></i>
                <span>عرض الطلبات</span>
            </a>
        </div>
    </div>
</div>

<!-- Enhanced Stats Grid -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    @include('components.dashboard.stat-card', [
        'title' => 'إجمالي المبيعات',
        'value' => '$' . number_format($salesMonth ?? 0, 0),
        'icon' => 'fas fa-chart-line',
        'color' => 'green',
        'change' => '+12%',
        'changeType' => 'up'
    ])
    @include('components.dashboard.stat-card', [
        'title' => 'الطلبات الجديدة',
        'value' => $ordersToday ?? 0,
        'icon' => 'fas fa-shopping-cart',
        'color' => 'blue',
        'change' => '+5 اليوم',
        'changeType' => 'up'
    ])
    @include('components.dashboard.stat-card', [
        'title' => 'المنتجات النشطة',
        'value' => $activeProducts ?? 0,
        'icon' => 'fas fa-box-open',
        'color' => 'purple',
        'subtitle' => 'من ' . ($totalProducts ?? 0) . ' منتج'
    ])
    @include('components.dashboard.stat-card', [
        'title' => 'متوسط التقييم',
        'value' => number_format($avgRating ?? 4.5, 1),
        'icon' => 'fas fa-star',
        'color' => 'orange',
        'subtitle' => 'من 5 نجوم'
    ])
</div>

<!-- Enhanced Quick Actions -->
<div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-8">
    @include('components.dashboard.quick-action', ['href' => route('store.products.create'), 'icon' => 'fas fa-plus-circle', 'label' => 'إضافة منتج', 'color' => 'purple'])
    @include('components.dashboard.quick-action', ['href' => route('store.orders'), 'icon' => 'fas fa-shopping-bag', 'label' => 'إدارة الطلبات', 'color' => 'blue'])
    @include('components.dashboard.quick-action', ['href' => route('store.analytics'), 'icon' => 'fas fa-chart-bar', 'label' => 'التحليلات', 'color' => 'green'])
    @include('components.dashboard.quick-action', ['href' => route('store.earnings'), 'icon' => 'fas fa-wallet', 'label' => 'الأرباح', 'color' => 'orange'])
</div>

<!-- Enhanced Main Content -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
    <!-- Recent Orders -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-3xl shadow-lg border border-gray-100 overflow-hidden">
            <!-- Enhanced Header -->
            <div class="px-6 py-5 border-b border-gray-100 bg-gradient-to-r from-blue-50 to-indigo-50">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-blue-500 rounded-2xl flex items-center justify-center">
                            <i class="fas fa-clock text-white"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-800 text-lg">أحدث الطلبات</h3>
                            <p class="text-gray-500 text-sm">آخر الطلبات الواردة لمتجرك</p>
                        </div>
                    </div>
                    <a href="{{ route('store.orders') }}" class="flex items-center gap-2 px-4 py-2 bg-blue-500 text-white rounded-xl hover:bg-blue-600 transition-all duration-300 hover:scale-105">
                        <span class="font-medium">عرض الكل</span>
                        <i class="fas fa-arrow-left text-sm"></i>
                    </a>
                </div>
            </div>
            
            <!-- Enhanced Table -->
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-right text-xs font-semibold text-gray-500 uppercase">رقم الطلب</th>
                            <th class="px-6 py-4 text-right text-xs font-semibold text-gray-500 uppercase">العميل</th>
                            <th class="px-6 py-4 text-right text-xs font-semibold text-gray-500 uppercase">المنتجات</th>
                            <th class="px-6 py-4 text-right text-xs font-semibold text-gray-500 uppercase">المبلغ</th>
                            <th class="px-6 py-4 text-right text-xs font-semibold text-gray-500 uppercase">الحالة</th>
                            <th class="px-6 py-4 text-right text-xs font-semibold text-gray-500 uppercase">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($recentOrders ?? [] as $order)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="font-bold text-blue-600">#{{ $order->order_number ?? 'ORD-001' }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 bg-gray-200 rounded-full flex items-center justify-center">
                                            <i class="fas fa-user text-gray-500 text-sm"></i>
                                        </div>
                                        <span class="text-gray-700">{{ $order->recipient_name ?? 'عميل' }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-gray-600">{{ $order->items->count() ?? 1 }} منتج</td>
                                <td class="px-6 py-4">
                                    <span class="font-bold text-green-600">${{ number_format($order->items->sum('subtotal') ?? 150, 2) }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        $status = $order->status ?? 'pending';
                                        $statusColors = [
                                            'pending' => 'bg-yellow-100 text-yellow-800',
                                            'confirmed' => 'bg-blue-100 text-blue-800',
                                            'shipped' => 'bg-purple-100 text-purple-800',
                                            'delivered' => 'bg-green-100 text-green-800',
                                            'cancelled' => 'bg-red-100 text-red-800'
                                        ];
                                        $statusLabels = [
                                            'pending' => 'قيد الانتظار',
                                            'confirmed' => 'مؤكد',
                                            'shipped' => 'تم الشحن',
                                            'delivered' => 'تم التسليم',
                                            'cancelled' => 'ملغي'
                                        ];
                                    @endphp
                                    <span class="px-3 py-1 rounded-full text-xs font-medium {{ $statusColors[$status] ?? $statusColors['pending'] }}">
                                        {{ $statusLabels[$status] ?? 'قيد الانتظار' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <button onclick="viewOrder({{ $order->id ?? 1 }})" class="w-8 h-8 bg-blue-100 hover:bg-blue-200 text-blue-600 rounded-lg flex items-center justify-center transition-colors" title="عرض">
                                            <i class="fas fa-eye text-sm"></i>
                                        </button>
                                        <button onclick="editOrder({{ $order->id ?? 1 }})" class="w-8 h-8 bg-purple-100 hover:bg-purple-200 text-purple-600 rounded-lg flex items-center justify-center transition-colors" title="تعديل">
                                            <i class="fas fa-edit text-sm"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center gap-3">
                                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center">
                                            <i class="fas fa-shopping-bag text-gray-400 text-2xl"></i>
                                        </div>
                                        <div>
                                            <p class="text-gray-500 font-medium">لا توجد طلبات حتى الآن</p>
                                            <p class="text-gray-400 text-sm">ستظهر الطلبات الجديدة هنا</p>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Enhanced Sidebar -->
    <div class="space-y-6">
        <!-- Performance Card -->
        <div class="bg-gradient-to-br from-amber-400 to-orange-500 rounded-3xl p-6 text-white shadow-lg">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center backdrop-blur-sm">
                    <i class="fas fa-star text-2xl"></i>
                </div>
                <div class="text-right">
                    <div class="text-3xl font-bold">{{ number_format($avgRating ?? 4.8, 1) }}</div>
                    <div class="text-white/80 text-sm">من 5.0</div>
                </div>
            </div>
            <div class="flex justify-center gap-1 mb-3">
                @for($i = 1; $i <= 5; $i++)
                    <i class="fas fa-star {{ $i <= round($avgRating ?? 4.8) ? 'text-white' : 'text-white/40' }}"></i>
                @endfor
            </div>
            <p class="text-white/90 text-center font-medium">تقييم ممتاز من العملاء</p>
        </div>

        <!-- Low Stock Alert -->
        @if(count($lowStockProducts ?? []) > 0)
        <div class="bg-white rounded-3xl shadow-lg border border-gray-100 p-6">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 bg-red-100 rounded-2xl flex items-center justify-center">
                    <i class="fas fa-exclamation-triangle text-red-500"></i>
                </div>
                <div>
                    <h3 class="font-bold text-gray-800">تنبيه المخزون</h3>
                    <p class="text-gray-500 text-sm">منتجات تحتاج إعادة تعبئة</p>
                </div>
            </div>
            <div class="space-y-3">
                @foreach($lowStockProducts->take(3) as $product)
                    <div class="flex justify-between items-center p-3 bg-red-50 rounded-2xl">
                        <div>
                            <div class="font-medium text-gray-800">{{ Str::limit($product->name, 15) }}</div>
                            <div class="text-red-600 text-sm font-medium">{{ $product->stock }} متبقي</div>
                        </div>
                        <button class="px-3 py-1 bg-red-500 text-white rounded-lg text-sm hover:bg-red-600 transition-colors">
                            تعبئة
                        </button>
                    </div>
                @endforeach
            </div>
        </div>
        @else
        <!-- Quick Stats Card -->
        <div class="bg-white rounded-3xl shadow-lg border border-gray-100 p-6">
            <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
                <i class="fas fa-chart-pie text-blue-500"></i>
                إحصائيات سريعة
            </h3>
            <div class="space-y-4">
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">المنتجات النشطة</span>
                    <span class="font-bold text-blue-600">{{ $activeProducts ?? 25 }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">طلبات هذا الأسبوع</span>
                    <span class="font-bold text-green-600">{{ $weeklyOrders ?? 12 }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">متوسط قيمة الطلب</span>
                    <span class="font-bold text-purple-600">${{ number_format($avgOrderValue ?? 85, 0) }}</span>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Sales Chart -->
@component('components.dashboard.chart-card', ['title' => 'المبيعات الأسبوعية', 'icon' => 'fas fa-chart-area', 'chartId' => 'salesChart'])
@endcomponent
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Chart.js for sales visualization
const ctx = document.getElementById('salesChart')?.getContext('2d');
if (ctx) {
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['السبت', 'الأحد', 'الاثنين', 'الثلاثاء', 'الأربعاء', 'الخميس', 'الجمعة'],
            datasets: [{
                label: 'المبيعات',
                data: @json($weeklySales ?? [120, 190, 300, 500, 200, 300, 450]),
                borderColor: '#8b5cf6',
                backgroundColor: 'rgba(139, 92, 246, 0.1)',
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#8b5cf6',
                pointBorderColor: '#ffffff',
                pointBorderWidth: 2,
                pointRadius: 6
            }]
        },
        options: { 
            responsive: true, 
            maintainAspectRatio: false,
            plugins: { 
                legend: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0,0,0,0.1)'
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
}

// Enhanced button functionality
function viewOrder(orderId) {
    // Show loading state
    const button = event.target.closest('button');
    const originalContent = button.innerHTML;
    button.innerHTML = '<i class="fas fa-spinner fa-spin text-sm"></i>';
    button.disabled = true;
    
    // Simulate API call or redirect to order view
    setTimeout(() => {
        window.location.href = `{{ route('store.orders') }}?view=${orderId}`;
    }, 500);
}

function editOrder(orderId) {
    // Show loading state
    const button = event.target.closest('button');
    const originalContent = button.innerHTML;
    button.innerHTML = '<i class="fas fa-spinner fa-spin text-sm"></i>';
    button.disabled = true;
    
    // Simulate API call or redirect to order edit
    setTimeout(() => {
        window.location.href = `{{ route('store.orders') }}?edit=${orderId}`;
    }, 500);
}

// Add smooth animations
document.addEventListener('DOMContentLoaded', function() {
    // Animate stat cards on load
    const statCards = document.querySelectorAll('[class*="stat-card"], [class*="bg-white"]');
    statCards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        setTimeout(() => {
            card.style.transition = 'all 0.6s ease';
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 100);
    });
    
    // Add hover effects to action buttons
    const actionButtons = document.querySelectorAll('button[onclick*="Order"]');
    actionButtons.forEach(button => {
        button.addEventListener('mouseenter', function() {
            this.style.transform = 'scale(1.1)';
        });
        button.addEventListener('mouseleave', function() {
            this.style.transform = 'scale(1)';
        });
    });
});

// Real-time updates simulation
function updateDashboardStats() {
    // This would typically fetch real data from an API
    const statsElements = document.querySelectorAll('[class*="text-2xl"][class*="font-bold"]');
    statsElements.forEach(element => {
        if (element.textContent.includes('$')) {
            // Simulate small changes in sales figures
            const currentValue = parseFloat(element.textContent.replace(/[$,]/g, ''));
            const change = Math.random() * 10 - 5; // Random change between -5 and +5
            const newValue = Math.max(0, currentValue + change);
            element.textContent = '$' + newValue.toLocaleString();
        }
    });
}

// Update stats every 30 seconds (optional)
// setInterval(updateDashboardStats, 30000);
</script>
@endpush
