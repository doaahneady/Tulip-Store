@extends('dashboards.layouts.app', ['title' => 'My Store Dashboard', 'subtitle' => 'Store Analytics & Management'])

@section('content')
<!-- Store Performance Overview -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Total Sales -->
    <div class="card">
        <div class="card-body">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium mb-2">Total Sales</p>
                    <h3 class="text-2xl font-semibold text-gray-900">${{ number_format($metrics['total_sales'] ?? 125750) }}</h3>
                    <div class="flex items-center gap-1 mt-1">
                        <span class="text-success-600 text-sm font-medium flex items-center gap-1">
                            <i class="fas fa-arrow-up text-xs"></i>
                            +{{ $metrics['sales_growth'] ?? 24 }}%
                        </span>
                        <span class="text-gray-500 text-sm">vs last month</span>
                    </div>
                </div>
                <div class="w-12 h-12 rounded-lg bg-success-50 text-success-600 flex items-center justify-center">
                    <i class="fas fa-dollar-sign text-lg"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Orders -->
    <div class="card">
        <div class="card-body">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium mb-2">Total Orders</p>
                    <h3 class="text-2xl font-semibold text-gray-900">{{ number_format($metrics['total_orders'] ?? 847) }}</h3>
                    <div class="flex items-center gap-1 mt-1">
                        <span class="text-primary-600 text-sm font-medium">
                            <i class="fas fa-shopping-bag text-xs"></i>
                            {{ $metrics['avg_order_value'] ?? 148 }} avg value
                        </span>
                    </div>
                </div>
                <div class="w-12 h-12 rounded-lg bg-primary-50 text-primary-600 flex items-center justify-center">
                    <i class="fas fa-shopping-cart text-lg"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Products Listed -->
    <div class="card">
        <div class="card-body">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium mb-2">Products</p>
                    <h3 class="text-2xl font-semibold text-gray-900">{{ number_format($metrics['total_products'] ?? 156) }}</h3>
                    <div class="flex items-center gap-1 mt-1">
                        <span class="text-warning-600 text-sm font-medium">
                            <i class="fas fa-exclamation-triangle text-xs"></i>
                            {{ $metrics['low_stock_items'] ?? 8 }} low stock
                        </span>
                    </div>
                </div>
                <div class="w-12 h-12 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center">
                    <i class="fas fa-box text-lg"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Pending Earnings -->
    <div class="card">
        <div class="card-body">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium mb-2">Pending Earnings</p>
                    <h3 class="text-2xl font-semibold text-gray-900">${{ number_format($metrics['pending_earnings'] ?? 18750) }}</h3>
                    <div class="flex items-center gap-1 mt-1">
                        <span class="text-emerald-600 text-sm font-medium">
                            <i class="fas fa-clock text-xs"></i>
                            Ready for payout
                        </span>
                    </div>
                </div>
                <div class="w-12 h-12 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center">
                    <i class="fas fa-wallet text-lg"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Main Dashboard Grid -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
    <!-- Sales Analytics Chart -->
    <div class="lg:col-span-2">
        <div class="card">
            <div class="card-header">
                <div class="flex items-center justify-between">
                    <h3 class="card-title">Sales Analytics</h3>
                    <div class="flex items-center gap-2">
                        <select class="form-select text-sm" id="sales-metric">
                            <option value="revenue" selected>Revenue</option>
                            <option value="orders">Orders</option>
                            <option value="customers">Customers</option>
                        </select>
                        <select class="form-select text-sm" id="sales-period">
                            <option value="7d">Last 7 days</option>
                            <option value="30d" selected>Last 30 days</option>
                            <option value="90d">Last 90 days</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <canvas id="salesAnalyticsChart" height="300"></canvas>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div>
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Quick Actions</h3>
            </div>
            <div class="card-body">
                <div class="space-y-3">
                    <a href="{{ route('dashboard.vendor.products.create') }}" class="btn btn-primary w-full">
                        <i class="fas fa-plus"></i>
                        Add New Product
                    </a>
                    <a href="{{ route('dashboard.vendor.products') }}" class="btn btn-secondary w-full">
                        <i class="fas fa-warehouse"></i>
                        Manage Inventory
                    </a>
                    <a href="{{ route('dashboard.vendor.orders') }}" class="btn btn-secondary w-full">
                        <i class="fas fa-shopping-bag"></i>
                        View Orders
                    </a>
                    <button class="btn btn-secondary w-full" onclick="requestPayout()">
                        <i class="fas fa-hand-holding-usd"></i>
                        Request Payout
                    </button>
                </div>

                <!-- Store Status -->
                <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                    <div class="flex items-center justify-between mb-3">
                        <span class="font-medium text-gray-900">Store Status</span>
                        <span class="badge badge-success">Active</span>
                    </div>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Commission Rate</span>
                            <span class="font-medium">{{ $store['commission_rate'] ?? 15 }}%</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Store Rating</span>
                            <div class="flex items-center gap-1">
                                <span class="font-medium">{{ $store['rating'] ?? 4.8 }}</span>
                                <div class="flex text-yellow-400">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fas fa-star text-xs {{ $i <= ($store['rating'] ?? 4.8) ? '' : 'text-gray-300' }}"></i>
                                    @endfor
                                </div>
                            </div>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Total Reviews</span>
                            <span class="font-medium">{{ $store['total_reviews'] ?? 234 }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Orders & Top Products -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
    <!-- Recent Orders -->
    <div class="card">
        <div class="card-header">
            <div class="flex items-center justify-between">
                <h3 class="card-title">Recent Orders</h3>
                <a href="{{ route('dashboard.vendor.orders') }}" class="btn btn-ghost btn-sm">
                    View All
                    <i class="fas fa-arrow-right text-xs ml-1"></i>
                </a>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Order</th>
                            <th>Customer</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $recentOrders = [
                                ['id' => 'ORD-5847', 'customer' => 'John Smith', 'amount' => 285, 'status' => 'delivered', 'date' => '2 hours ago'],
                                ['id' => 'ORD-5846', 'customer' => 'Sarah Johnson', 'amount' => 156, 'status' => 'shipped', 'date' => '4 hours ago'],
                                ['id' => 'ORD-5845', 'customer' => 'Mike Wilson', 'amount' => 420, 'status' => 'processing', 'date' => '6 hours ago'],
                                ['id' => 'ORD-5844', 'customer' => 'Emma Davis', 'amount' => 89, 'status' => 'confirmed', 'date' => '8 hours ago'],
                                ['id' => 'ORD-5843', 'customer' => 'David Brown', 'amount' => 325, 'status' => 'delivered', 'date' => '1 day ago'],
                            ];
                        @endphp
                        @foreach($recentOrders as $order)
                        <tr>
                            <td>
                                <a href="{{ route('dashboard.vendor.orders') }}" class="font-medium text-primary-600 hover:text-primary-700">
                                    {{ $order['id'] }}
                                </a>
                            </td>
                            <td class="text-gray-900">{{ $order['customer'] }}</td>
                            <td class="font-medium text-gray-900">${{ number_format($order['amount']) }}</td>
                            <td>
                                <span class="badge 
                                    @if($order['status'] === 'delivered') badge-success
                                    @elseif($order['status'] === 'shipped') badge-info
                                    @elseif($order['status'] === 'processing') badge-warning
                                    @else badge-gray
                                    @endif">
                                    {{ ucfirst($order['status']) }}
                                </span>
                            </td>
                            <td class="text-gray-500 text-sm">{{ $order['date'] }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Top Selling Products -->
    <div class="card">
        <div class="card-header">
            <div class="flex items-center justify-between">
                <h3 class="card-title">Top Selling Products</h3>
                <a href="{{ route('dashboard.vendor.products') }}" class="btn btn-ghost btn-sm">
                    View All
                    <i class="fas fa-arrow-right text-xs ml-1"></i>
                </a>
            </div>
        </div>
        <div class="card-body p-0">
            @php
                $topProducts = [
                    ['name' => 'Wireless Bluetooth Headphones', 'sales' => 145, 'revenue' => 14500, 'stock' => 23],
                    ['name' => 'Smart Phone Case', 'sales' => 128, 'revenue' => 3840, 'stock' => 67],
                    ['name' => 'USB-C Charging Cable', 'sales' => 98, 'revenue' => 1960, 'stock' => 156],
                    ['name' => 'Portable Power Bank', 'sales' => 87, 'revenue' => 4350, 'stock' => 34],
                    ['name' => 'Wireless Mouse', 'sales' => 76, 'revenue' => 2280, 'stock' => 89],
                ];
            @endphp
            @foreach($topProducts as $product)
            <div class="flex items-center justify-between p-4 border-b border-gray-100 last:border-b-0">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center">
                        <i class="fas fa-box text-gray-600"></i>
                    </div>
                    <div>
                        <div class="font-medium text-gray-900 text-sm">{{ $product['name'] }}</div>
                        <div class="text-gray-600 text-xs">{{ $product['sales'] }} sold • ${{ number_format($product['revenue']) }} revenue</div>
                    </div>
                </div>
                <div class="text-right">
                    <div class="text-sm font-medium text-gray-900">{{ $product['stock'] }} in stock</div>
                    <div class="text-xs {{ $product['stock'] < 20 ? 'text-warning-600' : 'text-success-600' }}">
                        {{ $product['stock'] < 20 ? 'Low Stock' : 'In Stock' }}
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Inventory Alerts & Customer Insights -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    <!-- Inventory Alerts -->
    <div class="card">
        <div class="card-header">
            <div class="flex items-center justify-between">
                <h3 class="card-title">Inventory Alerts</h3>
                <span class="badge badge-warning">{{ $metrics['low_stock_items'] ?? 8 }}</span>
            </div>
        </div>
        <div class="card-body">
            @php
                $lowStockItems = [
                    ['name' => 'Premium Laptop Stand', 'current' => 3, 'threshold' => 10, 'status' => 'critical'],
                    ['name' => 'Wireless Keyboard', 'current' => 7, 'threshold' => 15, 'status' => 'low'],
                    ['name' => 'HD Webcam', 'current' => 12, 'threshold' => 20, 'status' => 'low'],
                    ['name' => 'Gaming Mouse Pad', 'current' => 5, 'threshold' => 10, 'status' => 'critical'],
                ];
            @endphp
            <div class="space-y-3">
                @foreach($lowStockItems as $item)
                <div class="flex items-center justify-between p-3 {{ $item['status'] === 'critical' ? 'bg-error-50' : 'bg-warning-50' }} rounded-lg">
                    <div>
                        <div class="font-medium text-gray-900 text-sm">{{ $item['name'] }}</div>
                        <div class="text-gray-600 text-xs">{{ $item['current'] }} remaining (threshold: {{ $item['threshold'] }})</div>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="badge {{ $item['status'] === 'critical' ? 'badge-error' : 'badge-warning' }}">
                            {{ ucfirst($item['status']) }}
                        </span>
                        <button class="btn btn-sm btn-primary" onclick="restockProduct('{{ $item['name'] }}')">
                            <i class="fas fa-plus text-xs"></i>
                        </button>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="mt-4 pt-4 border-t border-gray-200">
                <button class="btn btn-secondary w-full" onclick="manageInventory()">
                    <i class="fas fa-warehouse"></i>
                    Manage All Inventory
                </button>
            </div>
        </div>
    </div>

    <!-- Customer Insights -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Customer Insights</h3>
        </div>
        <div class="card-body">
            <!-- Customer Stats -->
            <div class="grid grid-cols-2 gap-4 mb-6">
                <div class="p-3 bg-gray-50 rounded-lg text-center">
                    <div class="text-2xl font-bold text-primary-600">{{ $metrics['total_customers'] ?? 1247 }}</div>
                    <div class="text-sm text-gray-600">Total Customers</div>
                </div>
                <div class="p-3 bg-gray-50 rounded-lg text-center">
                    <div class="text-2xl font-bold text-success-600">{{ $metrics['repeat_customers'] ?? 68 }}%</div>
                    <div class="text-sm text-gray-600">Repeat Customers</div>
                </div>
            </div>

            <!-- Top Customers -->
            <div class="mb-4">
                <h4 class="font-medium text-gray-900 mb-3">Top Customers</h4>
                <div class="space-y-3">
                    @php
                        $topCustomers = [
                            ['name' => 'Sarah Johnson', 'orders' => 12, 'spent' => 2450],
                            ['name' => 'Mike Wilson', 'orders' => 8, 'spent' => 1890],
                            ['name' => 'Emma Davis', 'orders' => 6, 'spent' => 1250],
                            ['name' => 'David Brown', 'orders' => 5, 'spent' => 980],
                        ];
                    @endphp
                    @foreach($topCustomers as $customer)
                    <div class="flex items-center justify-between p-2 bg-gray-50 rounded">
                        <div>
                            <div class="font-medium text-gray-900 text-sm">{{ $customer['name'] }}</div>
                            <div class="text-gray-600 text-xs">{{ $customer['orders'] }} orders</div>
                        </div>
                        <div class="text-right">
                            <div class="font-semibold text-success-600">${{ number_format($customer['spent']) }}</div>
                            <div class="text-xs text-gray-500">total spent</div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Customer Satisfaction -->
            <div class="p-3 bg-primary-50 rounded-lg">
                <div class="flex items-center justify-between mb-2">
                    <span class="font-medium text-primary-800">Customer Satisfaction</span>
                    <span class="text-2xl font-bold text-primary-600">{{ $metrics['satisfaction_score'] ?? 4.8 }}/5</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="flex text-yellow-400">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="fas fa-star text-sm {{ $i <= ($metrics['satisfaction_score'] ?? 4.8) ? '' : 'text-gray-300' }}"></i>
                        @endfor
                    </div>
                    <span class="text-sm text-primary-700">Based on {{ $metrics['total_reviews'] ?? 234 }} reviews</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Sales Analytics Chart
    initializeSalesChart();
});

function initializeSalesChart() {
    const ctx = document.getElementById('salesAnalyticsChart').getContext('2d');
    let salesChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4'],
            datasets: [{
                label: 'Revenue ($)',
                data: [28500, 32000, 29750, 35500],
                borderColor: 'rgb(59, 130, 246)',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
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
                    title: {
                        display: true,
                        text: 'Amount ($)'
                    },
                    ticks: {
                        callback: function(value) {
                            return '$' + value.toLocaleString();
                        }
                    }
                }
            }
        }
    });

    // Sales Metric Selector
    document.getElementById('sales-metric').addEventListener('change', function() {
        const metric = this.value;
        updateSalesChart(metric, salesChart);
    });
}

function updateSalesChart(metric, chart) {
    const datasets = {
        revenue: {
            label: 'Revenue ($)',
            data: [28500, 32000, 29750, 35500],
            borderColor: 'rgb(59, 130, 246)',
            backgroundColor: 'rgba(59, 130, 246, 0.1)'
        },
        orders: {
            label: 'Orders',
            data: [185, 220, 198, 244],
            borderColor: 'rgb(34, 197, 94)',
            backgroundColor: 'rgba(34, 197, 94, 0.1)'
        },
        customers: {
            label: 'New Customers',
            data: [45, 52, 48, 61],
            borderColor: 'rgb(168, 85, 247)',
            backgroundColor: 'rgba(168, 85, 247, 0.1)'
        }
    };

    chart.data.datasets[0] = {
        ...datasets[metric],
        fill: true,
        tension: 0.4
    };
    chart.update();
}

function requestPayout() {
    console.log('Opening payout request modal...');
    // Open payout request modal or redirect to payout page
}

function restockProduct(productName) {
    console.log(`Restocking ${productName}...`);
    // Open restock modal or redirect to inventory management
}

function manageInventory() {
    window.location.href = '{{ route("dashboard.vendor.products") }}';
}
</script>
@endpush