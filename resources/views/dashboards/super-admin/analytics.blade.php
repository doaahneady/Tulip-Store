@extends('dashboards.layouts.app', ['title' => 'Platform Analytics', 'subtitle' => 'Comprehensive platform performance metrics'])

@section('content')
<!-- Analytics Overview Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Total Revenue -->
    <div class="metric-card">
        <div class="flex items-center justify-between">
            <div class="flex-1">
                <div class="metric-label">Total Revenue</div>
                <div class="metric-value text-success-600">${{ number_format($metrics['total_revenue'] ?? 2847500, 0) }}</div>
                <div class="metric-change text-success-600">
                    <i class="fas fa-arrow-up"></i>
                    <span>+{{ $metrics['revenue_growth'] ?? 18 }}% this month</span>
                </div>
            </div>
            <div class="metric-icon bg-success-50 text-success-600">
                <i class="fas fa-dollar-sign"></i>
            </div>
        </div>
    </div>

    <!-- Total Orders -->
    <div class="metric-card">
        <div class="flex items-center justify-between">
            <div class="flex-1">
                <div class="metric-label">Total Orders</div>
                <div class="metric-value text-primary-600">{{ number_format($metrics['total_orders'] ?? 15420, 0) }}</div>
                <div class="metric-change text-success-600">
                    <i class="fas fa-arrow-up"></i>
                    <span>+{{ $metrics['orders_growth'] ?? 12 }}% this month</span>
                </div>
            </div>
            <div class="metric-icon bg-primary-50 text-primary-600">
                <i class="fas fa-shopping-cart"></i>
            </div>
        </div>
    </div>

    <!-- Total Users -->
    <div class="metric-card">
        <div class="flex items-center justify-between">
            <div class="flex-1">
                <div class="metric-label">Total Users</div>
                <div class="metric-value text-warning-600">{{ number_format($metrics['total_users'] ?? 8750, 0) }}</div>
                <div class="metric-change text-success-600">
                    <i class="fas fa-arrow-up"></i>
                    <span>+{{ $metrics['users_growth'] ?? 8 }}% this month</span>
                </div>
            </div>
            <div class="metric-icon bg-warning-50 text-warning-600">
                <i class="fas fa-users"></i>
            </div>
        </div>
    </div>

    <!-- Conversion Rate -->
    <div class="metric-card">
        <div class="flex items-center justify-between">
            <div class="flex-1">
                <div class="metric-label">Conversion Rate</div>
                <div class="metric-value text-emerald-600">{{ $metrics['conversion_rate'] ?? 3.2 }}%</div>
                <div class="metric-change text-success-600">
                    <i class="fas fa-arrow-up"></i>
                    <span>+{{ $metrics['conversion_growth'] ?? 0.5 }}% this month</span>
                </div>
            </div>
            <div class="metric-icon bg-emerald-50 text-emerald-600">
                <i class="fas fa-chart-line"></i>
            </div>
        </div>
    </div>
</div>

<!-- Analytics Charts -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
    <!-- Revenue Chart -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Revenue Analytics</h3>
        </div>
        <div class="card-body">
            <canvas id="revenueChart" height="300"></canvas>
        </div>
    </div>

    <!-- Orders Chart -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Orders Analytics</h3>
        </div>
        <div class="card-body">
            <canvas id="ordersChart" height="300"></canvas>
        </div>
    </div>
</div>

<!-- Detailed Analytics -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Top Products -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Top Performing Products</h3>
        </div>
        <div class="card-body p-0">
            @php
                $topProducts = [
                    ['name' => 'Wireless Headphones', 'sales' => 1250, 'revenue' => 125000],
                    ['name' => 'Smart Watch', 'sales' => 980, 'revenue' => 98000],
                    ['name' => 'Phone Case', 'sales' => 750, 'revenue' => 37500],
                    ['name' => 'Bluetooth Speaker', 'sales' => 650, 'revenue' => 65000],
                    ['name' => 'Power Bank', 'sales' => 580, 'revenue' => 29000],
                ];
            @endphp
            @foreach($topProducts as $product)
            <div class="flex items-center justify-between p-4 border-b border-gray-100 last:border-b-0">
                <div>
                    <div class="font-medium text-gray-900">{{ $product['name'] }}</div>
                    <div class="text-sm text-gray-500">{{ $product['sales'] }} sales</div>
                </div>
                <div class="text-right">
                    <div class="font-semibold text-gray-900">${{ number_format($product['revenue']) }}</div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- User Growth -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">User Growth Trends</h3>
        </div>
        <div class="card-body">
            <canvas id="userGrowthChart" height="200"></canvas>
        </div>
    </div>

    <!-- Performance Metrics -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Performance Metrics</h3>
        </div>
        <div class="card-body">
            @php
                $performanceMetrics = [
                    ['label' => 'Average Order Value', 'value' => '$' . number_format($metrics['avg_order_value'] ?? 185, 0), 'change' => '+5.2%', 'positive' => true],
                    ['label' => 'Customer Lifetime Value', 'value' => '$' . number_format($metrics['customer_ltv'] ?? 850, 0), 'change' => '+12.8%', 'positive' => true],
                    ['label' => 'Cart Abandonment Rate', 'value' => ($metrics['cart_abandonment'] ?? 68.5) . '%', 'change' => '-3.1%', 'positive' => true],
                    ['label' => 'Return Rate', 'value' => ($metrics['return_rate'] ?? 2.8) . '%', 'change' => '-0.5%', 'positive' => true],
                ];
            @endphp
            <div class="space-y-4">
                @foreach($performanceMetrics as $metric)
                <div class="flex items-center justify-between">
                    <div>
                        <div class="font-medium text-gray-900">{{ $metric['label'] }}</div>
                        <div class="text-2xl font-bold text-gray-900 mt-1">{{ $metric['value'] }}</div>
                    </div>
                    <div class="text-right">
                        <span class="text-sm font-medium {{ $metric['positive'] ? 'text-success-600' : 'text-error-600' }}">
                            {{ $metric['change'] }}
                        </span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Revenue Chart
    const revenueCtx = document.getElementById('revenueChart').getContext('2d');
    new Chart(revenueCtx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            datasets: [{
                label: 'Revenue ($)',
                data: [180000, 195000, 210000, 225000, 240000, 255000, 270000, 285000, 300000, 315000, 330000, 345000],
                borderColor: 'rgb(34, 197, 94)',
                backgroundColor: 'rgba(34, 197, 94, 0.1)',
                fill: true,
                tension: 0.4
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
                    ticks: {
                        callback: function(value) {
                            return '$' + value.toLocaleString();
                        }
                    }
                }
            }
        }
    });

    // Orders Chart
    const ordersCtx = document.getElementById('ordersChart').getContext('2d');
    new Chart(ordersCtx, {
        type: 'bar',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            datasets: [{
                label: 'Orders',
                data: [850, 920, 1050, 1180, 1320, 1450, 1580, 1720, 1860, 2000, 2140, 2280],
                backgroundColor: 'rgba(59, 130, 246, 0.8)',
                borderColor: 'rgb(59, 130, 246)',
                borderWidth: 1
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
                    beginAtZero: true
                }
            }
        }
    });

    // User Growth Chart
    const userGrowthCtx = document.getElementById('userGrowthChart').getContext('2d');
    new Chart(userGrowthCtx, {
        type: 'doughnut',
        data: {
            labels: ['New Users', 'Returning Users', 'Inactive Users'],
            datasets: [{
                data: [45, 35, 20],
                backgroundColor: [
                    'rgba(34, 197, 94, 0.8)',
                    'rgba(59, 130, 246, 0.8)',
                    'rgba(156, 163, 175, 0.8)'
                ],
                borderColor: [
                    'rgb(34, 197, 94)',
                    'rgb(59, 130, 246)',
                    'rgb(156, 163, 175)'
                ],
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
});
</script>
@endpush