@extends('dashboards.layouts.app', ['title' => 'System Health', 'subtitle' => 'Monitor system services and performance'])

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-4 gap-6 mb-8">
    <!-- Service Status Cards -->
    <div class="card">
        <div class="card-body">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium mb-2">Services Online</p>
                    <h3 class="text-2xl font-semibold text-success-600">{{ $metrics['services_online'] ?? 12 }}</h3>
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
                    <p class="text-gray-600 text-sm font-medium mb-2">Services Offline</p>
                    <h3 class="text-2xl font-semibold text-error-600">{{ $metrics['services_offline'] ?? 0 }}</h3>
                </div>
                <div class="w-12 h-12 rounded-lg bg-error-50 text-error-600 flex items-center justify-center">
                    <i class="fas fa-times-circle text-lg"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium mb-2">Degraded Services</p>
                    <h3 class="text-2xl font-semibold text-warning-600">{{ $metrics['services_degraded'] ?? 1 }}</h3>
                </div>
                <div class="w-12 h-12 rounded-lg bg-warning-50 text-warning-600 flex items-center justify-center">
                    <i class="fas fa-exclamation-triangle text-lg"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium mb-2">Avg Response Time</p>
                    <h3 class="text-2xl font-semibold text-gray-900">{{ $metrics['avg_response_time'] ?? 45 }}ms</h3>
                </div>
                <div class="w-12 h-12 rounded-lg bg-primary-50 text-primary-600 flex items-center justify-center">
                    <i class="fas fa-tachometer-alt text-lg"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- System Services Status -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">System Services Status</h3>
    </div>
    <div class="card-body">
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>Service</th>
                        <th>Status</th>
                        <th>Response Time</th>
                        <th>Uptime</th>
                        <th>Last Check</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $services = [
                            ['name' => 'Web Server', 'status' => 'online', 'response_time' => '45ms', 'uptime' => '99.9%', 'last_check' => '1 min ago'],
                            ['name' => 'Database', 'status' => 'online', 'response_time' => '12ms', 'uptime' => '99.8%', 'last_check' => '1 min ago'],
                            ['name' => 'Redis Cache', 'status' => 'online', 'response_time' => '3ms', 'uptime' => '99.9%', 'last_check' => '1 min ago'],
                            ['name' => 'Payment Gateway', 'status' => 'online', 'response_time' => '120ms', 'uptime' => '99.7%', 'last_check' => '2 min ago'],
                            ['name' => 'Email Service', 'status' => 'degraded', 'response_time' => '2.1s', 'uptime' => '98.5%', 'last_check' => '1 min ago'],
                            ['name' => 'File Storage', 'status' => 'online', 'response_time' => '89ms', 'uptime' => '99.6%', 'last_check' => '1 min ago'],
                        ];
                    @endphp
                    @foreach($services as $service)
                    <tr>
                        <td class="font-medium">{{ $service['name'] }}</td>
                        <td>
                            <span class="badge 
                                @if($service['status'] === 'online') badge-success
                                @elseif($service['status'] === 'degraded') badge-warning
                                @else badge-error
                                @endif">
                                {{ ucfirst($service['status']) }}
                            </span>
                        </td>
                        <td>{{ $service['response_time'] }}</td>
                        <td>{{ $service['uptime'] }}</td>
                        <td class="text-gray-500">{{ $service['last_check'] }}</td>
                        <td>
                            <button class="btn btn-sm btn-ghost">
                                <i class="fas fa-sync text-xs"></i>
                                Test
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection