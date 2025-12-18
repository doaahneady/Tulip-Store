@extends('dashboards.layouts.app', ['title' => 'Driver Management', 'subtitle' => 'Manage drivers and their assignments'])

@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <h2 class="text-2xl font-semibold text-gray-900">Driver Management</h2>
        <p class="text-gray-600">Monitor and manage all drivers</p>
    </div>
    <button class="btn btn-primary">
        <i class="fas fa-plus text-sm mr-2"></i>
        Add Driver
    </button>
</div>

<!-- Driver Stats -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="card">
        <div class="card-body">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium mb-2">Total Drivers</p>
                    <h3 class="text-2xl font-semibold text-gray-900">{{ $metrics['total_drivers'] ?? 45 }}</h3>
                </div>
                <div class="w-12 h-12 rounded-lg bg-primary-50 text-primary-600 flex items-center justify-center">
                    <i class="fas fa-users text-lg"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium mb-2">Active Drivers</p>
                    <h3 class="text-2xl font-semibold text-success-600">{{ $metrics['active_drivers'] ?? 32 }}</h3>
                </div>
                <div class="w-12 h-12 rounded-lg bg-success-50 text-success-600 flex items-center justify-center">
                    <i class="fas fa-car text-lg"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium mb-2">On Delivery</p>
                    <h3 class="text-2xl font-semibold text-warning-600">{{ $metrics['drivers_on_delivery'] ?? 18 }}</h3>
                </div>
                <div class="w-12 h-12 rounded-lg bg-warning-50 text-warning-600 flex items-center justify-center">
                    <i class="fas fa-shipping-fast text-lg"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium mb-2">Available</p>
                    <h3 class="text-2xl font-semibold text-primary-600">{{ $metrics['available_drivers'] ?? 14 }}</h3>
                </div>
                <div class="w-12 h-12 rounded-lg bg-primary-50 text-primary-600 flex items-center justify-center">
                    <i class="fas fa-check-circle text-lg"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Driver List -->
<div class="card">
    <div class="card-header">
        <div class="flex items-center justify-between">
            <h3 class="card-title">All Drivers</h3>
            <div class="flex items-center gap-3">
                <div class="relative">
                    <input type="text" placeholder="Search drivers..." class="form-input pl-10">
                    <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                </div>
                <select class="form-select">
                    <option>All Status</option>
                    <option>Available</option>
                    <option>On Delivery</option>
                    <option>Off Duty</option>
                </select>
            </div>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>Driver</th>
                        <th>Vehicle</th>
                        <th>Status</th>
                        <th>Current Location</th>
                        <th>Deliveries Today</th>
                        <th>Rating</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $drivers = [
                            ['name' => 'Ahmed Hassan', 'phone' => '+1-555-0123', 'vehicle' => 'Honda Civic - ABC123', 'status' => 'on_delivery', 'location' => 'Downtown Area', 'deliveries' => 8, 'rating' => 4.8],
                            ['name' => 'Maria Rodriguez', 'phone' => '+1-555-0124', 'vehicle' => 'Toyota Camry - XYZ789', 'status' => 'available', 'location' => 'North District', 'deliveries' => 12, 'rating' => 4.9],
                            ['name' => 'John Smith', 'phone' => '+1-555-0125', 'vehicle' => 'Ford Focus - DEF456', 'status' => 'on_delivery', 'location' => 'East Side', 'deliveries' => 6, 'rating' => 4.7],
                            ['name' => 'Lisa Chen', 'phone' => '+1-555-0126', 'vehicle' => 'Nissan Altima - GHI789', 'status' => 'off_duty', 'location' => 'Depot', 'deliveries' => 0, 'rating' => 4.6],
                            ['name' => 'David Wilson', 'phone' => '+1-555-0127', 'vehicle' => 'Hyundai Elantra - JKL012', 'status' => 'available', 'location' => 'South Area', 'deliveries' => 9, 'rating' => 4.8],
                        ];
                    @endphp
                    @foreach($drivers as $driver)
                    <tr>
                        <td>
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-primary-100 rounded-full flex items-center justify-center text-primary-600 text-sm font-medium">
                                    {{ strtoupper(substr($driver['name'], 0, 1)) }}
                                </div>
                                <div>
                                    <div class="font-medium text-gray-900">{{ $driver['name'] }}</div>
                                    <div class="text-gray-500 text-sm">{{ $driver['phone'] }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="text-sm">{{ $driver['vehicle'] }}</td>
                        <td>
                            <span class="badge 
                                @if($driver['status'] === 'available') badge-success
                                @elseif($driver['status'] === 'on_delivery') badge-warning
                                @else badge-gray
                                @endif">
                                {{ ucfirst(str_replace('_', ' ', $driver['status'])) }}
                            </span>
                        </td>
                        <td class="text-gray-600">{{ $driver['location'] }}</td>
                        <td class="font-medium">{{ $driver['deliveries'] }}</td>
                        <td>
                            <div class="flex items-center gap-1">
                                <i class="fas fa-star text-yellow-400 text-sm"></i>
                                <span class="font-medium">{{ $driver['rating'] }}</span>
                            </div>
                        </td>
                        <td>
                            <div class="flex items-center gap-2">
                                <button class="btn btn-sm btn-ghost">
                                    <i class="fas fa-map-marker-alt text-xs"></i>
                                </button>
                                <button class="btn btn-sm btn-ghost">
                                    <i class="fas fa-phone text-xs"></i>
                                </button>
                                <button class="btn btn-sm btn-ghost">
                                    <i class="fas fa-edit text-xs"></i>
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