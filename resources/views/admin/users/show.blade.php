@extends('layouts.admin')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4">
        <div class="mb-6">
            <a href="{{ route('admin.users.index') }}" class="text-orange-500 hover:text-orange-600">
                <i class="fas fa-arrow-left mr-2"></i>Back to Users
            </a>
        </div>

        <!-- User Info -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <div class="flex justify-between items-start">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">{{ $user->name }}</h1>
                    <p class="text-gray-600 mt-1">{{ $user->email }}</p>
                    <p class="text-gray-600">{{ $user->phone ?? 'No phone' }}</p>
                    @if($user->role)
                        <span class="inline-block mt-2 px-3 py-1 text-sm rounded-full bg-purple-100 text-purple-800">
                            <i class="fas fa-user-shield mr-1"></i>
                            {{ $user->role->display_name }}
                        </span>
                    @else
                        <span class="inline-block mt-2 px-3 py-1 text-sm rounded-full bg-gray-100 text-gray-800">
                            <i class="fas fa-user mr-1"></i>
                            Customer
                        </span>
                    @endif
                </div>
                <div class="text-right">
                    <p class="text-sm text-gray-500">Member since</p>
                    <p class="text-lg font-semibold">{{ $user->created_at->format('M d, Y') }}</p>
                </div>
            </div>
        </div>

        <!-- Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm">Total Orders</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['total_orders'] }}</p>
                    </div>
                    <div class="bg-blue-100 p-3 rounded-full">
                        <i class="fas fa-shopping-cart text-blue-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm">Total Spent</p>
                        <p class="text-2xl font-bold text-gray-900">${{ number_format($stats['total_spent'], 2) }}</p>
                    </div>
                    <div class="bg-green-100 p-3 rounded-full">
                        <i class="fas fa-dollar-sign text-green-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm">Pending Orders</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['pending_orders'] }}</p>
                    </div>
                    <div class="bg-yellow-100 p-3 rounded-full">
                        <i class="fas fa-clock text-yellow-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm">Completed</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['completed_orders'] }}</p>
                    </div>
                    <div class="bg-purple-100 p-3 rounded-full">
                        <i class="fas fa-check-circle text-purple-600 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Role & Permissions -->
        @if($user->role)
        <div class="bg-white rounded-lg shadow mb-6">
            <div class="p-6 border-b">
                <h2 class="text-xl font-bold text-gray-900">
                    <i class="fas fa-shield-alt mr-2"></i>
                    Role & Permissions
                </h2>
            </div>
            <div class="p-6">
                <div class="mb-4">
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">Role: {{ $user->role->display_name }}</h3>
                    <p class="text-gray-600">{{ $user->role->description }}</p>
                </div>
                
                <h4 class="text-md font-semibold text-gray-800 mb-3">Permissions:</h4>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                    @foreach($user->role->permissions as $permission)
                        <div class="flex items-center bg-green-50 border border-green-200 rounded-lg p-3">
                            <i class="fas fa-check-circle text-green-600 mr-2"></i>
                            <span class="text-sm text-gray-700">{{ $permission->display_name }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        <!-- Recent Orders -->
        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b">
                <h2 class="text-xl font-bold text-gray-900">Recent Orders</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Order ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($user->orders as $order)
                            <tr>
                                <td class="px-6 py-4 text-sm font-medium text-gray-900">#{{ $order->id }}</td>
                                <td class="px-6 py-4 text-sm text-gray-500">{{ $order->created_at->format('M d, Y') }}</td>
                                <td class="px-6 py-4 text-sm text-gray-900">${{ number_format($order->total, 2) }}</td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 text-xs rounded-full 
                                        @if($order->status === 'pending') bg-yellow-100 text-yellow-800
                                        @elseif($order->status === 'processing') bg-blue-100 text-blue-800
                                        @elseif($order->status === 'shipped') bg-purple-100 text-purple-800
                                        @elseif($order->status === 'delivered') bg-green-100 text-green-800
                                        @else bg-red-100 text-red-800
                                        @endif">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    <a href="{{ route('admin.orders.show', $order) }}" class="text-blue-600 hover:text-blue-800">
                                        View Details
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                    No orders yet
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
