@extends('dashboards.layouts.app', ['title' => 'Products', 'subtitle' => 'Manage your product inventory'])

@section('content')
<!-- Page Header -->
<div class="page-header">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="page-title">Product Management</h1>
            <p class="page-subtitle">Manage your store's product inventory</p>
        </div>
        <button class="btn btn-primary">
            <i class="fas fa-plus mr-2"></i>
            <span>Add Product</span>
        </button>
    </div>
</div>

<!-- Product Stats -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="metric-card">
        <div class="flex items-center justify-between">
            <div class="flex-1">
                <div class="metric-label">Total Products</div>
                <div class="metric-value text-gray-900">{{ $metrics['total_products'] ?? 156 }}</div>
            </div>
            <div class="metric-icon bg-primary-50 text-primary-600">
                <i class="fas fa-box"></i>
            </div>
        </div>
    </div>

    <div class="metric-card">
        <div class="flex items-center justify-between">
            <div class="flex-1">
                <div class="metric-label">Active Products</div>
                <div class="metric-value text-success-600">{{ $metrics['active_products'] ?? 142 }}</div>
            </div>
            <div class="metric-icon bg-success-50 text-success-600">
                <i class="fas fa-check-circle"></i>
            </div>
        </div>
    </div>

    <div class="metric-card">
        <div class="flex items-center justify-between">
            <div class="flex-1">
                <div class="metric-label">Low Stock</div>
                <div class="metric-value text-warning-600">{{ $metrics['low_stock'] ?? 8 }}</div>
            </div>
            <div class="metric-icon bg-warning-50 text-warning-600">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
        </div>
    </div>

    <div class="metric-card">
        <div class="flex items-center justify-between">
            <div class="flex-1">
                <div class="metric-label">Out of Stock</div>
                <div class="metric-value text-error-600">{{ $metrics['out_of_stock'] ?? 6 }}</div>
            </div>
            <div class="metric-icon bg-error-50 text-error-600">
                <i class="fas fa-times-circle"></i>
            </div>
        </div>
    </div>
</div>

<!-- Product List -->
<div class="card">
    <div class="card-header">
        <div class="flex items-center justify-between">
            <h3 class="card-title">All Products</h3>
            <div class="flex items-center gap-3">
                <div class="relative">
                    <input type="text" placeholder="Search products..." class="form-input pl-10">
                    <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                </div>
                <select class="form-select">
                    <option>All Categories</option>
                    <option>Electronics</option>
                    <option>Clothing</option>
                    <option>Food & Beverage</option>
                    <option>Home & Garden</option>
                </select>
            </div>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-enhanced">
            <table class="table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Stock</th>
                        <th>Status</th>
                        <th>Sales</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $products = [
                            ['name' => 'Wireless Headphones', 'sku' => 'WH-001', 'category' => 'Electronics', 'price' => 89.99, 'stock' => 45, 'status' => 'active', 'sales' => 156],
                            ['name' => 'Cotton T-Shirt', 'sku' => 'CT-002', 'category' => 'Clothing', 'price' => 24.99, 'stock' => 8, 'status' => 'active', 'sales' => 89],
                            ['name' => 'Coffee Beans (1kg)', 'sku' => 'CB-003', 'category' => 'Food & Beverage', 'price' => 15.99, 'stock' => 0, 'status' => 'inactive', 'sales' => 234],
                            ['name' => 'Garden Hose', 'sku' => 'GH-004', 'category' => 'Home & Garden', 'price' => 34.99, 'stock' => 23, 'status' => 'active', 'sales' => 67],
                            ['name' => 'Smartphone Case', 'sku' => 'SC-005', 'category' => 'Electronics', 'price' => 12.99, 'stock' => 156, 'status' => 'active', 'sales' => 345],
                        ];
                    @endphp
                    @foreach($products as $product)
                    <tr>
                        <td>
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-box text-gray-400"></i>
                                </div>
                                <div>
                                    <div class="font-medium text-gray-900">{{ $product['name'] }}</div>
                                    <div class="text-gray-500 text-sm">SKU: {{ $product['sku'] }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge badge-gray">{{ $product['category'] }}</span>
                        </td>
                        <td class="font-semibold">${{ number_format($product['price'], 2) }}</td>
                        <td>
                            <span class="
                                @if($product['stock'] === 0) text-error-600
                                @elseif($product['stock'] < 10) text-warning-600
                                @else text-success-600
                                @endif font-medium">
                                {{ $product['stock'] }}
                            </span>
                        </td>
                        <td>
                            <span class="badge {{ $product['status'] === 'active' ? 'badge-success' : 'badge-gray' }}">
                                {{ ucfirst($product['status']) }}
                            </span>
                        </td>
                        <td class="text-gray-600">{{ $product['sales'] }} sold</td>
                        <td>
                            <div class="flex items-center gap-2">
                                <button class="btn btn-sm btn-ghost">
                                    <i class="fas fa-eye text-xs"></i>
                                </button>
                                <button class="btn btn-sm btn-ghost">
                                    <i class="fas fa-edit text-xs"></i>
                                </button>
                                <button class="btn btn-sm btn-ghost">
                                    <i class="fas fa-plus text-xs"></i>
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