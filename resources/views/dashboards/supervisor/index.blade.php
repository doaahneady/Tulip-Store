@extends('dashboards.layouts.app', ['title' => 'Driver Supervisor Dashboard', 'subtitle' => 'Fleet Management & Live Delivery Tracking'])

@section('content')
<!-- Fleet Overview Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Active Drivers -->
    <div class="card">
        <div class="card-body">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium mb-2">Active Drivers</p>
                    <h3 class="text-2xl font-semibold text-gray-900">{{ $metrics['active_drivers'] ?? 38 }}/{{ $metrics['total_drivers'] ?? 45 }}</h3>
                    <div class="flex items-center gap-1 mt-1">
                        <span class="text-success-600 text-sm font-medium">
                            <i class="fas fa-circle text-xs"></i>
                            {{ $metrics['drivers_online'] ?? 32 }} online now
                        </span>
                    </div>
                </div>
                <div class="w-12 h-12 rounded-lg bg-success-50 text-success-600 flex items-center justify-center">
                    <i class="fas fa-motorcycle text-lg"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Pending Deliveries -->
    <div class="card">
        <div class="card-body">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium mb-2">Pending Deliveries</p>
                    <h3 class="text-2xl font-semibold text-gray-900">{{ number_format($metrics['pending_deliveries'] ?? 127) }}</h3>
                    <div class="flex items-center gap-1 mt-1">
                        <span class="text-warning-600 text-sm font-medium flex items-center gap-1">
                            <i class="fas fa-clock text-xs"></i>
                            {{ $metrics['urgent_deliveries'] ?? 15 }} urgent
                        </span>
                    </div>
                </div>
                <div class="w-12 h-12 rounded-lg bg-warning-50 text-warning-600 flex items-center justify-center">
                    <i class="fas fa-box text-lg"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Delivery Success Rate -->
    <div class="card">
        <div class="card-body">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium mb-2">Success Rate</p>
                    <h3 class="text-2xl font-semibold text-gray-900">{{ $metrics['success_rate'] ?? 96.8 }}%</h3>
                    <div class="flex items-center gap-1 mt-1">
                        <span class="text-success-600 text-sm font-medium flex items-center gap-1">
                            <i class="fas fa-arrow-up text-xs"></i>
                            +{{ $metrics['success_improvement'] ?? 1.2 }}%
                        </span>
                        <span class="text-gray-500 text-sm">this week</span>
                    </div>
                </div>
                <div class="w-12 h-12 rounded-lg bg-primary-50 text-primary-600 flex items-center justify-center">
                    <i class="fas fa-chart-line text-lg"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Average Delivery Time -->
    <div class="card">
        <div class="card-body">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium mb-2">Avg Delivery Time</p>
                    <h3 class="text-2xl font-semibold text-gray-900">{{ $metrics['avg_delivery_time'] ?? 28 }}m</h3>
                    <div class="flex items-center gap-1 mt-1">
                        <span class="text-success-600 text-sm font-medium flex items-center gap-1">
                            <i class="fas fa-arrow-down text-xs"></i>
                            -{{ $metrics['time_improvement'] ?? 3 }}m faster
                        </span>
                    </div>
                </div>
                <div class="w-12 h-12 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center">
                    <i class="fas fa-stopwatch text-lg"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Main Dashboard Grid -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
    <!-- Live Map -->
    <div class="lg:col-span-2">
        <div class="card">
            <div class="card-header">
                <div class="flex items-center justify-between">
                    <h3 class="card-title">Live Driver Tracking</h3>
                    <div class="flex items-center gap-2">
                        <button class="btn btn-ghost btn-sm" onclick="refreshMap()">
                            <i class="fas fa-sync-alt"></i>
                            Refresh
                        </button>
                        <button class="btn btn-ghost btn-sm" onclick="toggleMapView()">
                            <i class="fas fa-layer-group"></i>
                            View
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <div id="delivery-map" style="height: 400px; width: 100%;"></div>
            </div>
        </div>
    </div>

    <!-- Driver Status Panel -->
    <div>
        <div class="card">
            <div class="card-header">
                <div class="flex items-center justify-between">
                    <h3 class="card-title">Driver Status</h3>
                    <span class="text-sm text-gray-500">Live Updates</span>
                </div>
            </div>
            <div class="card-body p-0">
                @php
                    $drivers = [
                        ['name' => 'Ahmed Hassan', 'status' => 'delivering', 'location' => 'Downtown', 'eta' => '15 min', 'orders' => 3],
                        ['name' => 'Mike Chen', 'status' => 'available', 'location' => 'Mall Area', 'eta' => null, 'orders' => 0],
                        ['name' => 'Carlos Rodriguez', 'status' => 'delivering', 'location' => 'North District', 'eta' => '8 min', 'orders' => 2],
                        ['name' => 'David Kim', 'status' => 'returning', 'location' => 'Warehouse', 'eta' => '12 min', 'orders' => 0],
                        ['name' => 'Sarah Johnson', 'status' => 'offline', 'location' => 'Unknown', 'eta' => null, 'orders' => 0],
                        ['name' => 'Lisa Martinez', 'status' => 'delivering', 'location' => 'East Side', 'eta' => '22 min', 'orders' => 4],
                    ];
                @endphp
                @foreach($drivers as $driver)
                <div class="flex items-center justify-between p-4 border-b border-gray-100 last:border-b-0">
                    <div class="flex items-center gap-3">
                        <div class="relative">
                            <div class="w-8 h-8 bg-primary-100 rounded-full flex items-center justify-center text-primary-600 text-sm font-medium">
                                {{ strtoupper(substr($driver['name'], 0, 1)) }}
                            </div>
                            <div class="absolute -bottom-1 -right-1 w-3 h-3 rounded-full border-2 border-white
                                @if($driver['status'] === 'delivering') bg-success-500
                                @elseif($driver['status'] === 'available') bg-primary-500
                                @elseif($driver['status'] === 'returning') bg-warning-500
                                @else bg-gray-400
                                @endif"></div>
                        </div>
                        <div>
                            <div class="font-medium text-gray-900 text-sm">{{ $driver['name'] }}</div>
                            <div class="text-gray-600 text-xs">{{ $driver['location'] }}</div>
                        </div>
                    </div>
                    <div class="text-right">
                        <span class="badge 
                            @if($driver['status'] === 'delivering') badge-success
                            @elseif($driver['status'] === 'available') badge-info
                            @elseif($driver['status'] === 'returning') badge-warning
                            @else badge-gray
                            @endif text-xs">
                            {{ ucfirst($driver['status']) }}
                        </span>
                        @if($driver['eta'])
                            <div class="text-xs text-gray-500 mt-1">ETA: {{ $driver['eta'] }}</div>
                        @endif
                        @if($driver['orders'] > 0)
                            <div class="text-xs text-primary-600 mt-1">{{ $driver['orders'] }} orders</div>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<!-- Delivery Performance & Vehicle Status -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
    <!-- Delivery Performance Chart -->
    <div class="card">
        <div class="card-header">
            <div class="flex items-center justify-between">
                <h3 class="card-title">Delivery Performance</h3>
                <select class="form-select text-sm" id="performance-period">
                    <option value="today" selected>Today</option>
                    <option value="week">This Week</option>
                    <option value="month">This Month</option>
                </select>
            </div>
        </div>
        <div class="card-body">
            <canvas id="deliveryPerformanceChart" height="300"></canvas>
        </div>
    </div>

    <!-- Vehicle Status -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Vehicle Fleet Status</h3>
        </div>
        <div class="card-body">
            @php
                $vehicles = [
                    ['id' => 'VH-001', 'type' => 'Motorcycle', 'driver' => 'Ahmed Hassan', 'status' => 'active', 'fuel' => 85, 'mileage' => 12450],
                    ['id' => 'VH-002', 'type' => 'Motorcycle', 'driver' => 'Mike Chen', 'status' => 'active', 'fuel' => 92, 'mileage' => 8920],
                    ['id' => 'VH-003', 'type' => 'Van', 'driver' => 'Carlos Rodriguez', 'status' => 'maintenance', 'fuel' => 45, 'mileage' => 45200],
                    ['id' => 'VH-004', 'type' => 'Motorcycle', 'driver' => 'David Kim', 'status' => 'active', 'fuel' => 78, 'mileage' => 15600],
                    ['id' => 'VH-005', 'type' => 'Motorcycle', 'driver' => 'Lisa Martinez', 'status' => 'active', 'fuel' => 65, 'mileage' => 9800],
                ];
            @endphp
            <div class="space-y-3">
                @foreach($vehicles as $vehicle)
                <div class="p-3 border border-gray-200 rounded-lg">
                    <div class="flex items-center justify-between mb-2">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg 
                                @if($vehicle['status'] === 'active') bg-success-100 text-success-600
                                @elseif($vehicle['status'] === 'maintenance') bg-warning-100 text-warning-600
                                @else bg-gray-100 text-gray-600
                                @endif
                                flex items-center justify-center">
                                @if($vehicle['type'] === 'Motorcycle')
                                    <i class="fas fa-motorcycle text-sm"></i>
                                @else
                                    <i class="fas fa-truck text-sm"></i>
                                @endif
                            </div>
                            <div>
                                <div class="font-medium text-gray-900 text-sm">{{ $vehicle['id'] }}</div>
                                <div class="text-gray-600 text-xs">{{ $vehicle['driver'] }}</div>
                            </div>
                        </div>
                        <span class="badge 
                            @if($vehicle['status'] === 'active') badge-success
                            @elseif($vehicle['status'] === 'maintenance') badge-warning
                            @else badge-gray
                            @endif">
                            {{ ucfirst($vehicle['status']) }}
                        </span>
                    </div>
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="text-gray-500">Fuel Level:</span>
                            <div class="flex items-center gap-2 mt-1">
                                <div class="flex-1 bg-gray-200 rounded-full h-2">
                                    <div class="
                                        @if($vehicle['fuel'] > 50) bg-success-500
                                        @elseif($vehicle['fuel'] > 25) bg-warning-500
                                        @else bg-error-500
                                        @endif
                                        h-2 rounded-full" style="width: {{ $vehicle['fuel'] }}%"></div>
                                </div>
                                <span class="text-xs font-medium">{{ $vehicle['fuel'] }}%</span>
                            </div>
                        </div>
                        <div>
                            <span class="text-gray-500">Mileage:</span>
                            <div class="font-medium">{{ number_format($vehicle['mileage']) }} km</div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<!-- Recent Assignments & Route Optimization -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    <!-- Recent Assignments -->
    <div class="card">
        <div class="card-header">
            <div class="flex items-center justify-between">
                <h3 class="card-title">Recent Assignments</h3>
                <a href="{{ route('dashboard.supervisor.order-assignment') }}" class="btn btn-ghost btn-sm">
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
                            <th>Driver</th>
                            <th>Status</th>
                            <th>ETA</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $assignments = [
                                ['order' => '#ORD-1234', 'driver' => 'Ahmed Hassan', 'status' => 'in_transit', 'eta' => '15 min', 'priority' => 'high'],
                                ['order' => '#ORD-1235', 'driver' => 'Mike Chen', 'status' => 'picked_up', 'eta' => '25 min', 'priority' => 'normal'],
                                ['order' => '#ORD-1236', 'driver' => 'Carlos Rodriguez', 'status' => 'assigned', 'eta' => '45 min', 'priority' => 'normal'],
                                ['order' => '#ORD-1237', 'driver' => 'David Kim', 'status' => 'delivered', 'eta' => 'Completed', 'priority' => 'low'],
                                ['order' => '#ORD-1238', 'driver' => 'Lisa Martinez', 'status' => 'in_transit', 'eta' => '12 min', 'priority' => 'high'],
                            ];
                        @endphp
                        @foreach($assignments as $assignment)
                        <tr>
                            <td>
                                <div class="flex items-center gap-2">
                                    <span class="font-medium text-primary-600">{{ $assignment['order'] }}</span>
                                    @if($assignment['priority'] === 'high')
                                        <i class="fas fa-exclamation-circle text-error-500 text-xs"></i>
                                    @endif
                                </div>
                            </td>
                            <td class="text-gray-900">{{ $assignment['driver'] }}</td>
                            <td>
                                <span class="badge 
                                    @if($assignment['status'] === 'delivered') badge-success
                                    @elseif($assignment['status'] === 'in_transit') badge-info
                                    @elseif($assignment['status'] === 'picked_up') badge-warning
                                    @else badge-gray
                                    @endif">
                                    {{ ucfirst(str_replace('_', ' ', $assignment['status'])) }}
                                </span>
                            </td>
                            <td class="text-gray-600 text-sm">{{ $assignment['eta'] }}</td>
                            <td>
                                <div class="flex items-center gap-1">
                                    <button class="btn btn-ghost btn-sm" onclick="trackDelivery('{{ $assignment['order'] }}')" title="Track">
                                        <i class="fas fa-map-marker-alt text-xs"></i>
                                    </button>
                                    <button class="btn btn-ghost btn-sm" onclick="contactDriver('{{ $assignment['driver'] }}')" title="Contact">
                                        <i class="fas fa-phone text-xs"></i>
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

    <!-- Route Optimization -->
    <div class="card">
        <div class="card-header">
            <div class="flex items-center justify-between">
                <h3 class="card-title">Route Optimization</h3>
                <button class="btn btn-primary btn-sm" onclick="optimizeRoutes()">
                    <i class="fas fa-route"></i>
                    Optimize
                </button>
            </div>
        </div>
        <div class="card-body">
            <!-- Optimization Stats -->
            <div class="grid grid-cols-2 gap-4 mb-6">
                <div class="p-3 bg-gray-50 rounded-lg text-center">
                    <div class="text-2xl font-bold text-success-600">{{ $metrics['time_saved'] ?? 45 }}m</div>
                    <div class="text-sm text-gray-600">Time Saved Today</div>
                </div>
                <div class="p-3 bg-gray-50 rounded-lg text-center">
                    <div class="text-2xl font-bold text-primary-600">{{ $metrics['fuel_saved'] ?? 12 }}%</div>
                    <div class="text-sm text-gray-600">Fuel Efficiency</div>
                </div>
            </div>

            <!-- Pending Optimizations -->
            <div class="mb-4">
                <h4 class="font-medium text-gray-900 mb-3">Pending Optimizations</h4>
                <div class="space-y-2">
                    @php
                        $optimizations = [
                            ['area' => 'Downtown Route', 'orders' => 8, 'estimated_saving' => '15 min'],
                            ['area' => 'North District', 'orders' => 5, 'estimated_saving' => '12 min'],
                            ['area' => 'Mall Area', 'orders' => 12, 'estimated_saving' => '22 min'],
                        ];
                    @endphp
                    @foreach($optimizations as $opt)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div>
                            <div class="font-medium text-gray-900 text-sm">{{ $opt['area'] }}</div>
                            <div class="text-gray-600 text-xs">{{ $opt['orders'] }} orders</div>
                        </div>
                        <div class="text-right">
                            <div class="text-sm font-medium text-success-600">{{ $opt['estimated_saving'] }}</div>
                            <div class="text-xs text-gray-500">estimated saving</div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="space-y-2">
                <button class="btn btn-secondary w-full btn-sm" onclick="autoAssignOrders()">
                    <i class="fas fa-magic"></i>
                    Auto-Assign Orders
                </button>
                <button class="btn btn-secondary w-full btn-sm" onclick="balanceWorkload()">
                    <i class="fas fa-balance-scale"></i>
                    Balance Workload
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let deliveryMap;
let driverMarkers = [];

document.addEventListener('DOMContentLoaded', function() {
    // Initialize Map
    initializeMap();
    
    // Initialize Performance Chart
    initializePerformanceChart();
    
    // Start real-time updates
    startRealTimeUpdates();
});

function initializeMap() {
    // Initialize Leaflet map
    deliveryMap = L.map('delivery-map').setView([24.7136, 46.6753], 12); // Riyadh coordinates
    
    // Add tile layer
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(deliveryMap);
    
    // Add driver markers
    addDriverMarkers();
}

function addDriverMarkers() {
    const drivers = [
        {name: 'Ahmed Hassan', lat: 24.7136, lng: 46.6753, status: 'delivering'},
        {name: 'Mike Chen', lat: 24.7236, lng: 46.6853, status: 'available'},
        {name: 'Carlos Rodriguez', lat: 24.7036, lng: 46.6653, status: 'delivering'},
        {name: 'David Kim', lat: 24.7336, lng: 46.6953, status: 'returning'},
        {name: 'Lisa Martinez', lat: 24.6936, lng: 46.6553, status: 'delivering'},
    ];
    
    drivers.forEach(driver => {
        const color = driver.status === 'delivering' ? 'green' : 
                     driver.status === 'available' ? 'blue' : 
                     driver.status === 'returning' ? 'orange' : 'gray';
        
        const marker = L.circleMarker([driver.lat, driver.lng], {
            color: color,
            fillColor: color,
            fillOpacity: 0.7,
            radius: 8
        }).addTo(deliveryMap);
        
        marker.bindPopup(`
            <strong>${driver.name}</strong><br>
            Status: ${driver.status}<br>
            <button onclick="contactDriver('${driver.name}')" class="btn btn-sm btn-primary mt-1">Contact</button>
        `);
        
        driverMarkers.push(marker);
    });
}

function initializePerformanceChart() {
    const ctx = document.getElementById('deliveryPerformanceChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['00:00', '04:00', '08:00', '12:00', '16:00', '20:00'],
            datasets: [
                {
                    label: 'Completed',
                    data: [5, 8, 25, 45, 38, 22],
                    backgroundColor: 'rgba(34, 197, 94, 0.8)',
                    borderRadius: 4
                },
                {
                    label: 'Failed',
                    data: [1, 0, 2, 3, 2, 1],
                    backgroundColor: 'rgba(239, 68, 68, 0.8)',
                    borderRadius: 4
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Deliveries'
                    }
                }
            }
        }
    });
}

function startRealTimeUpdates() {
    // Update driver locations every 30 seconds
    setInterval(updateDriverLocations, 30000);
    
    // Update delivery status every 15 seconds
    setInterval(updateDeliveryStatus, 15000);
}

function updateDriverLocations() {
    // Simulate driver movement
    driverMarkers.forEach((marker, index) => {
        const currentLatLng = marker.getLatLng();
        const newLat = currentLatLng.lat + (Math.random() - 0.5) * 0.001;
        const newLng = currentLatLng.lng + (Math.random() - 0.5) * 0.001;
        marker.setLatLng([newLat, newLng]);
    });
}

function updateDeliveryStatus() {
    // Fetch latest delivery status from API
    fetch('/api/supervisor/delivery-status')
        .then(response => response.json())
        .then(data => {
            console.log('Updated delivery status:', data);
            // Update UI with new data
        })
        .catch(error => console.error('Error updating delivery status:', error));
}

function refreshMap() {
    console.log('Refreshing map...');
    // Clear existing markers and reload
    driverMarkers.forEach(marker => deliveryMap.removeLayer(marker));
    driverMarkers = [];
    addDriverMarkers();
}

function toggleMapView() {
    console.log('Toggling map view...');
    // Switch between satellite and street view
}

function trackDelivery(orderId) {
    console.log(`Tracking delivery for order: ${orderId}`);
    // Open detailed tracking view
}

function contactDriver(driverName) {
    console.log(`Contacting driver: ${driverName}`);
    // Open communication interface
}

function optimizeRoutes() {
    if (confirm('Optimize all pending routes? This will reassign some deliveries.')) {
        console.log('Optimizing routes...');
        // API call to optimize routes
    }
}

function autoAssignOrders() {
    console.log('Auto-assigning orders...');
    // API call to auto-assign orders
}

function balanceWorkload() {
    console.log('Balancing workload...');
    // API call to balance driver workload
}
</script>
@endpush