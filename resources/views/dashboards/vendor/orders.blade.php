@extends('dashboards.layouts.app', ['title' => 'Orders', 'subtitle' => 'Manage your store orders'])

@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <h2 class="text-2xl font-semibold text-gray-900">Order Management</h2>
        <p class="text-gray-600">View and manage all orders for your store</p>
    </div>
    <button class="btn btn-primary">
        <i class="fas fa-download text-sm mr-2"></i>
        Export Orders
    </button>
</div>

<!-- Order Stats -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="card">
        <div class="card-body">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium mb-2">Total Orders</p>
                    <h3 class="text-2xl font-semibold text-gray-900">{{ $metrics['total_orders'] ?? 156 }}</h3>
                </div>
                <div class="w-12 h-12 rounded-lg bg-primary-50 text-primary-600 flex items-center justify-center">
                    <i class="fas fa-shopping-cart text-lg"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium mb-2">Pending Orders</p>
                    <h3 class="text-2xl font-semibold text-warning-600">{{ $metrics['pending_orders'] ?? 8 }}</h3>
                </div>
                <div class="w-12 h-12 rounded-lg bg-warning-50 text-warning-600 flex items-center justify-center">
                    <i class="fas fa-clock text-lg"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium mb-2">Completed Orders</p>
                    <h3 class="text-2xl font-semibold text-success-600">{{ $metrics['completed_orders'] ?? 142 }}</h3>
                </div>
                <div class="w-12 h-12 rounded-lg bg-success-50 text-success-600 flex items-center justify-center">
                    <i class="fas fa-check-circle text-lg"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium mb-2">Monthly Revenue</p>
                    <h3 class="text-2xl font-semibold text-gray-900">${{ number_format($metrics['monthly_revenue'] ?? 8500) }}</h3>
                </div>
                <div class="w-12 h-12 rounded-lg bg-gray-50 text-gray-600 flex items-center justify-center">
                    <i class="fas fa-dollar-sign text-lg"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Order List -->
<div class="card">
    <div class="card-header">
        <div class="flex items-center justify-between">
            <h3 class="card-title">Recent Orders</h3>
            <div class="flex items-center gap-3">
                <div class="relative">
                    <input type="text" placeholder="Search orders..." class="form-input pl-10">
                    <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                </div>
                <select class="form-select">
                    <option>All Status</option>
                    <option>Pending</option>
                    <option>Processing</option>
                    <option>Shipped</option>
                    <option>Delivered</option>
                    <option>Cancelled</option>
                </select>
            </div>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Customer</th>
                        <th>Items</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Payment</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $orders = [
                            ['id' => 'ORD-2024-001', 'customer' => 'Ahmed Hassan', 'items' => 3, 'total' => 125.50, 'status' => 'pending', 'payment' => 'paid', 'date' => '2024-12-18 10:30'],
                            ['id' => 'ORD-2024-002', 'customer' => 'Sarah Johnson', 'items' => 1, 'total' => 89.99, 'status' => 'processing', 'payment' => 'paid', 'date' => '2024-12-18 09:15'],
                            ['id' => 'ORD-2024-003', 'customer' => 'Mike Chen', 'items' => 5, 'total' => 234.75, 'status' => 'shipped', 'payment' => 'paid', 'date' => '2024-12-18 08:45'],
                            ['id' => 'ORD-2024-004', 'customer' => 'Lisa Rodriguez', 'items' => 2, 'total' => 156.00, 'status' => 'delivered', 'payment' => 'paid', 'date' => '2024-12-17 18:20'],
                            ['id' => 'ORD-2024-005', 'customer' => 'David Kim', 'items' => 4, 'total' => 198.50, 'status' => 'pending', 'payment' => 'pending', 'date' => '2024-12-17 16:10'],
                        ];
                    @endphp
                    @foreach($orders as $order)
                    <tr>
                        <td class="font-mono text-sm">{{ $order['id'] }}</td>
                        <td class="font-medium">{{ $order['customer'] }}</td>
                        <td class="text-gray-600">{{ $order['items'] }} items</td>
                        <td class="font-semibold">${{ number_format($order['total'], 2) }}</td>
                        <td>
                            <span class="badge 
                                @if($order['status'] === 'delivered') badge-success
                                @elseif($order['status'] === 'shipped') badge-primary
                                @elseif($order['status'] === 'processing') badge-warning
                                @elseif($order['status'] === 'pending') badge-gray
                                @else badge-error
                                @endif">
                                {{ ucfirst($order['status']) }}
                            </span>
                        </td>
                        <td>
                            <span class="badge {{ $order['payment'] === 'paid' ? 'badge-success' : 'badge-warning' }}">
                                {{ ucfirst($order['payment']) }}
                            </span>
                        </td>
                        <td class="text-gray-600">{{ date('M j, Y H:i', strtotime($order['date'])) }}</td>
                        <td>
                            <div class="flex items-center gap-2">
                                <button class="btn btn-sm btn-ghost">
                                    <i class="fas fa-eye text-xs"></i>
                                </button>
                                @if($order['status'] === 'pending')
                                <button class="btn btn-sm btn-primary">
                                    <i class="fas fa-check text-xs"></i>
                                    Process
                                </button>
                                @endif
                                <button class="btn btn-sm btn-ghost">
                                    <i class="fas fa-print text-xs"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection