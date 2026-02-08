@extends('dashboards.layouts.app', ['title' => 'Main Dashboard'])

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <x-dashboard.stat-card title="Total Users" :value="number_format($metrics['total_users'] ?? 0)" icon="fas fa-users" color="blue" />
    <x-dashboard.stat-card title="Total Orders" :value="number_format($metrics['total_orders'] ?? 0)" icon="fas fa-shopping-cart" color="orange" />
    <x-dashboard.stat-card title="Total Products" :value="number_format($metrics['total_products'] ?? 0)" icon="fas fa-box" color="indigo" />
    <x-dashboard.stat-card title="Revenue Today" :value="'$'.number_format($metrics['revenue_today'] ?? 0, 2)" icon="fas fa-dollar-sign" color="green" />
</div>

<div class="bg-white rounded-lg shadow">
    <div class="p-6 border-b border-gray-200">
        <h3 class="text-lg font-medium text-gray-900">Welcome, {{ $user->full_name }}!</h3>
        <p class="text-sm text-gray-600">Here's what's happening with your store today.</p>
    </div>
    <div class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h4 class="text-sm font-medium text-gray-900 mb-3">Today's Stats</h4>
                <ul class="space-y-2">
                    <li class="flex justify-between">
                        <span class="text-sm text-gray-600">Orders Today</span>
                        <span class="text-sm font-medium">{{ $metrics['orders_today'] }}</span>
                    </li>
                    <li class="flex justify-between">
                        <span class="text-sm text-gray-600">Revenue Today</span>
                        <span class="text-sm font-medium">${{ number_format($metrics['revenue_today'], 2) }}</span>
                    </li>
                    <li class="flex justify-between">
                        <span class="text-sm text-gray-600">Total Employees</span>
                        <span class="text-sm font-medium">{{ $metrics['total_employees'] }}</span>
                    </li>
                </ul>
            </div>
            <div>
                <h4 class="text-sm font-medium text-gray-900 mb-3">Quick Actions</h4>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                    <x-dashboard.quick-action href="{{ route('dashboard.orders') }}" icon="fas fa-clipboard-list" label="View All Orders" color="blue" />
                    <x-dashboard.quick-action href="{{ route('dashboard.users') }}" icon="fas fa-users-cog" label="Manage Users" color="indigo" />
                    <x-dashboard.quick-action href="{{ route('dashboard.products') }}" icon="fas fa-boxes" label="Manage Products" color="green" />
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
