@extends('dashboards.layouts.app', ['title' => 'Live Driver Tracking', 'subtitle' => 'Real-time driver location monitoring'])

@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <h2 class="text-2xl font-semibold text-gray-900">Live Driver Tracking</h2>
        <p class="text-gray-600">Monitor driver locations and delivery status in real-time</p>
    </div>
    <div class="flex items-center gap-3">
        <button class="btn btn-ghost">
            <i class="fas fa-sync text-sm mr-2"></i>
            Refresh
        </button>
        <button class="btn btn-primary">
            <i class="fas fa-route text-sm mr-2"></i>
            Optimize Routes
        </button>
    </div>
</div>

<!-- Driver Status Overview -->
<div class="grid grid-cols-1 md:grid-cols-5 gap-6 mb-8">
    <div class="card">
        <div class="card-body">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium mb-2">Available</p>
                    <h3 class="text-2xl font-semibold text-success-600">{{ $metrics['available_drivers'] ?? 14 }}</h3>
                </div>
                <div class="w-3 h-3 rounded-full bg-success-500"></div>
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
                <div class="w-3 h-3 rounded-full bg-warning-500"></div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium mb-2">Busy</p>
                    <h3 class="text-2xl font-semibold text-primary-600">{{ $metrics['busy_drivers'] ?? 8 }}</h3>
                </div>
                <div class="w-3 h-3 rounded-full bg-primary-500"></div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium mb-2">Offline</p>
                    <h3 class="text-2xl font-semibold text-gray-600">{{ $metrics['offline_drivers'] ?? 5 }}</h3>
                </div>
                <div class="w-3 h-3 rounded-full bg-gray-500"></div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium mb-2">Total</p>
                    <h3 class="text-2xl font-semibold text-gray-900">{{ $metrics['total_drivers'] ?? 45 }}</h3>
                </div>
                <div class="w-12 h-12 rounded-lg bg-primary-50 text-primary-600 flex items-center justify-center">
                    <i class="fas fa-users text-lg"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Map and Driver List -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Live Map -->
    <div class="lg:col-span-2">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Live Driver Map</h3>
            </div>
            <div class="card-body p-0">
                <div id="driver-map" class="w-full h-96 bg-gray-100 rounded-lg flex items-center justify-center">
                    <div class="text-center">
                        <i class="fas fa-map-marked-alt text-4xl text-gray-400 mb-4"></i>
                        <p class="text-gray-600">Interactive map will be loaded here</p>
                        <p class="text-gray-500 text-sm">Showing real-time driver locations</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Active Drivers List -->
    <div>
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Active Drivers</h3>
            </div>
            <div class="card-body p-0">
                @php
                    $activeDrivers = [
                        ['name' => 'Ahmed Hassan', 'status' => 'on_delivery', 'location' => 'Downtown Area', 'eta' => '15 min', 'orders' => 3],
                        ['name' => 'Maria Rodriguez', 'status' => 'available', 'location' => 'North District', 'eta' => '-', 'orders' => 0],
                        ['name' => 'John Smith', 'status' => 'on_delivery', 'location' => 'East Side', 'eta' => '8 min', 'orders' => 2],
                        ['name' => 'Lisa Chen', 'status' => 'busy', 'location' => 'South Area', 'eta' => '25 min', 'orders' => 4],
                        ['name' => 'David Wilson', 'status' => 'available', 'location' => 'West End', 'eta' => '-', 'orders' => 0],
                    ];
                @endphp
                @foreach($activeDrivers as $driver)
                <div class="flex items-center gap-3 p-4 border-b border-gray-100 last:border-b-0">
                    <div class="w-3 h-3 rounded-full 
                        @if($driver['status'] === 'available') bg-success-500
                        @elseif($driver['status'] === 'on_delivery') bg-warning-500
                        @elseif($driver['status'] === 'busy') bg-primary-500
                        @else bg-gray-500
                        @endif"></div>
                    <div class="flex-1">
                        <div class="font-medium text-gray-900 text-sm">{{ $driver['name'] }}</div>
                        <div class="text-gray-500 text-xs">{{ $driver['location'] }}</div>
                        @if($driver['orders'] > 0)
                        <div class="text-primary-600 text-xs">{{ $driver['orders'] }} orders • ETA: {{ $driver['eta'] }}</div>
                        @endif
                    </div>
                    <div class="flex items-center gap-1">
                        <button class="btn btn-sm btn-ghost">
                            <i class="fas fa-phone text-xs"></i>
                        </button>
                        <button class="btn btn-sm btn-ghost">
                            <i class="fas fa-map-marker-alt text-xs"></i>
                        </button>
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
    // Initialize map (placeholder for actual map integration)
    console.log('Live tracking map initialized');
    
    // Auto-refresh driver locations every 30 seconds
    setInterval(function() {
        updateDriverLocations();
    }, 30000);
});

function updateDriverLocations() {
    // Fetch latest driver locations from API
    fetch('/dashboard/api/supervisor/driver-locations')
        .then(response => response.json())
        .then(data => {
            // Update map markers and driver list
            console.log('Updated driver locations:', data);
        })
        .catch(error => console.error('Error updating locations:', error));
}
</script>
@endpush