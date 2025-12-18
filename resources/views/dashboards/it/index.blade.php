@extends('dashboards.layouts.app', ['title' => 'IT/DevOps Dashboard', 'subtitle' => 'System Health & Infrastructure Monitoring'])

@section('content')
<!-- System Health Overview -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Overall System Health -->
    <div class="card">
        <div class="card-body">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium mb-2">System Health</p>
                    <h3 class="text-2xl font-semibold text-gray-900">{{ $metrics['system_health'] ?? 99.8 }}%</h3>
                    <div class="flex items-center gap-1 mt-1">
                        <span class="text-success-600 text-sm font-medium">
                            <i class="fas fa-check-circle text-xs"></i>
                            All systems operational
                        </span>
                    </div>
                </div>
                <div class="w-12 h-12 rounded-lg bg-success-50 text-success-600 flex items-center justify-center">
                    <i class="fas fa-heartbeat text-lg"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Server Uptime -->
    <div class="card">
        <div class="card-body">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium mb-2">Server Uptime</p>
                    <h3 class="text-2xl font-semibold text-gray-900">{{ $metrics['uptime_days'] ?? 47 }}d</h3>
                    <div class="flex items-center gap-1 mt-1">
                        <span class="text-success-600 text-sm font-medium">
                            99.9% availability
                        </span>
                    </div>
                </div>
                <div class="w-12 h-12 rounded-lg bg-primary-50 text-primary-600 flex items-center justify-center">
                    <i class="fas fa-server text-lg"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Active Alerts -->
    <div class="card">
        <div class="card-body">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium mb-2">Active Alerts</p>
                    <h3 class="text-2xl font-semibold text-gray-900">{{ $metrics['active_alerts'] ?? 2 }}</h3>
                    <div class="flex items-center gap-1 mt-1">
                        <span class="text-warning-600 text-sm font-medium">
                            <i class="fas fa-exclamation-triangle text-xs"></i>
                            1 critical, 1 warning
                        </span>
                    </div>
                </div>
                <div class="w-12 h-12 rounded-lg bg-warning-50 text-warning-600 flex items-center justify-center">
                    <i class="fas fa-bell text-lg"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Response Time -->
    <div class="card">
        <div class="card-body">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium mb-2">Avg Response Time</p>
                    <h3 class="text-2xl font-semibold text-gray-900">{{ $metrics['response_time'] ?? 145 }}ms</h3>
                    <div class="flex items-center gap-1 mt-1">
                        <span class="text-success-600 text-sm font-medium flex items-center gap-1">
                            <i class="fas fa-arrow-down text-xs"></i>
                            -12ms from yesterday
                        </span>
                    </div>
                </div>
                <div class="w-12 h-12 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center">
                    <i class="fas fa-tachometer-alt text-lg"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Main Dashboard Grid -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
    <!-- System Performance Chart -->
    <div class="lg:col-span-2">
        <div class="card">
            <div class="card-header">
                <div class="flex items-center justify-between">
                    <h3 class="card-title">System Performance Metrics</h3>
                    <div class="flex items-center gap-2">
                        <select class="form-select text-sm" id="performance-metric">
                            <option value="cpu">CPU Usage</option>
                            <option value="memory">Memory Usage</option>
                            <option value="disk">Disk I/O</option>
                            <option value="network">Network Traffic</option>
                        </select>
                        <select class="form-select text-sm" id="performance-period">
                            <option value="1h">Last Hour</option>
                            <option value="24h" selected>Last 24 Hours</option>
                            <option value="7d">Last 7 Days</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <canvas id="performanceChart" height="300"></canvas>
            </div>
        </div>
    </div>

    <!-- System Alerts -->
    <div>
        <div class="card">
            <div class="card-header">
                <div class="flex items-center justify-between">
                    <h3 class="card-title">System Alerts</h3>
                    <button class="btn btn-ghost btn-sm" onclick="refreshAlerts()">
                        <i class="fas fa-sync-alt"></i>
                    </button>
                </div>
            </div>
            <div class="card-body p-0">
                @php
                    $alerts = [
                        ['level' => 'critical', 'service' => 'Email Service', 'message' => 'High response time detected', 'time' => '5 minutes ago'],
                        ['level' => 'warning', 'service' => 'Database', 'message' => 'Connection pool 80% full', 'time' => '12 minutes ago'],
                        ['level' => 'info', 'service' => 'Cache', 'message' => 'Memory usage normal', 'time' => '1 hour ago'],
                        ['level' => 'warning', 'service' => 'File Storage', 'message' => 'Disk usage at 75%', 'time' => '2 hours ago'],
                    ];
                @endphp
                @foreach($alerts as $alert)
                <div class="flex items-center gap-3 p-4 border-b border-gray-100 last:border-b-0">
                    <div class="w-3 h-3 rounded-full 
                        @if($alert['level'] === 'critical') bg-error-500
                        @elseif($alert['level'] === 'warning') bg-warning-500
                        @else bg-success-500
                        @endif"></div>
                    <div class="flex-1">
                        <div class="flex items-center justify-between">
                            <span class="font-medium text-gray-900 text-sm">{{ $alert['service'] }}</span>
                            <span class="text-xs text-gray-500">{{ $alert['time'] }}</span>
                        </div>
                        <p class="text-gray-600 text-xs mt-1">{{ $alert['message'] }}</p>
                        <span class="badge 
                            @if($alert['level'] === 'critical') badge-error
                            @elseif($alert['level'] === 'warning') badge-warning
                            @else badge-success
                            @endif text-xs mt-1">
                            {{ ucfirst($alert['level']) }}
                        </span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<!-- Services Status & Database Health -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
    <!-- Services Status -->
    <div class="card">
        <div class="card-header">
            <div class="flex items-center justify-between">
                <h3 class="card-title">Service Status</h3>
                <div class="flex items-center gap-2">
                    <span class="text-xs text-gray-500">Last updated: {{ now()->format('H:i:s') }}</span>
                    <button class="btn btn-ghost btn-sm" onclick="refreshServices()">
                        <i class="fas fa-sync-alt"></i>
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body">
            @php
                $services = [
                    ['name' => 'Web Server (Nginx)', 'status' => 'online', 'response_time' => '45ms', 'uptime' => '99.9%', 'cpu' => '12%', 'memory' => '2.1GB'],
                    ['name' => 'Application Server', 'status' => 'online', 'response_time' => '89ms', 'uptime' => '99.8%', 'cpu' => '35%', 'memory' => '4.2GB'],
                    ['name' => 'Database (PostgreSQL)', 'status' => 'online', 'response_time' => '12ms', 'uptime' => '99.9%', 'cpu' => '18%', 'memory' => '8.1GB'],
                    ['name' => 'Redis Cache', 'status' => 'online', 'response_time' => '3ms', 'uptime' => '99.9%', 'cpu' => '5%', 'memory' => '512MB'],
                    ['name' => 'Payment Gateway', 'status' => 'online', 'response_time' => '120ms', 'uptime' => '99.7%', 'cpu' => 'N/A', 'memory' => 'N/A'],
                    ['name' => 'Email Service', 'status' => 'degraded', 'response_time' => '2.1s', 'uptime' => '98.5%', 'cpu' => 'N/A', 'memory' => 'N/A'],
                ];
            @endphp
            <div class="space-y-3">
                @foreach($services as $service)
                <div class="p-3 rounded-lg border border-gray-200 hover:border-gray-300 transition-colors">
                    <div class="flex items-center justify-between mb-2">
                        <div class="flex items-center gap-3">
                            <div class="w-3 h-3 rounded-full 
                                @if($service['status'] === 'online') bg-success-500
                                @elseif($service['status'] === 'degraded') bg-warning-500
                                @else bg-error-500
                                @endif"></div>
                            <span class="font-medium text-gray-900">{{ $service['name'] }}</span>
                        </div>
                        <span class="badge 
                            @if($service['status'] === 'online') badge-success
                            @elseif($service['status'] === 'degraded') badge-warning
                            @else badge-error
                            @endif">
                            {{ ucfirst($service['status']) }}
                        </span>
                    </div>
                    <div class="grid grid-cols-4 gap-4 text-sm">
                        <div>
                            <span class="text-gray-500">Response:</span>
                            <div class="font-medium">{{ $service['response_time'] }}</div>
                        </div>
                        <div>
                            <span class="text-gray-500">Uptime:</span>
                            <div class="font-medium">{{ $service['uptime'] }}</div>
                        </div>
                        <div>
                            <span class="text-gray-500">CPU:</span>
                            <div class="font-medium">{{ $service['cpu'] }}</div>
                        </div>
                        <div>
                            <span class="text-gray-500">Memory:</span>
                            <div class="font-medium">{{ $service['memory'] }}</div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Database Health -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Database Health</h3>
        </div>
        <div class="card-body">
            <!-- Database Metrics -->
            <div class="grid grid-cols-2 gap-4 mb-6">
                <div class="p-3 bg-gray-50 rounded-lg">
                    <div class="text-sm text-gray-600">Active Connections</div>
                    <div class="text-xl font-semibold text-gray-900">{{ $metrics['db_connections'] ?? 45 }}/100</div>
                </div>
                <div class="p-3 bg-gray-50 rounded-lg">
                    <div class="text-sm text-gray-600">Query Performance</div>
                    <div class="text-xl font-semibold text-gray-900">{{ $metrics['avg_query_time'] ?? 12 }}ms</div>
                </div>
                <div class="p-3 bg-gray-50 rounded-lg">
                    <div class="text-sm text-gray-600">Cache Hit Ratio</div>
                    <div class="text-xl font-semibold text-gray-900">{{ $metrics['cache_hit_ratio'] ?? 98.5 }}%</div>
                </div>
                <div class="p-3 bg-gray-50 rounded-lg">
                    <div class="text-sm text-gray-600">Database Size</div>
                    <div class="text-xl font-semibold text-gray-900">{{ $metrics['db_size'] ?? '2.4' }}GB</div>
                </div>
            </div>

            <!-- Recent Backups -->
            <div class="mb-4">
                <h4 class="font-medium text-gray-900 mb-3">Recent Backups</h4>
                <div class="space-y-2">
                    @php
                        $backups = [
                            ['type' => 'Full Backup', 'size' => '2.4GB', 'time' => '2 hours ago', 'status' => 'success'],
                            ['type' => 'Incremental', 'size' => '45MB', 'time' => '6 hours ago', 'status' => 'success'],
                            ['type' => 'Full Backup', 'size' => '2.3GB', 'time' => '1 day ago', 'status' => 'success'],
                        ];
                    @endphp
                    @foreach($backups as $backup)
                    <div class="flex items-center justify-between p-2 bg-gray-50 rounded">
                        <div class="flex items-center gap-2">
                            <i class="fas fa-database text-primary-600 text-sm"></i>
                            <span class="text-sm font-medium">{{ $backup['type'] }}</span>
                        </div>
                        <div class="flex items-center gap-3 text-sm text-gray-600">
                            <span>{{ $backup['size'] }}</span>
                            <span>{{ $backup['time'] }}</span>
                            <i class="fas fa-check-circle text-success-600"></i>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="flex gap-2">
                <button class="btn btn-sm btn-primary" onclick="createBackup()">
                    <i class="fas fa-save"></i>
                    Create Backup
                </button>
                <button class="btn btn-sm btn-secondary" onclick="optimizeDatabase()">
                    <i class="fas fa-cog"></i>
                    Optimize
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Recent Deployments & Error Logs -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    <!-- Recent Deployments -->
    <div class="card">
        <div class="card-header">
            <div class="flex items-center justify-between">
                <h3 class="card-title">Recent Deployments</h3>
                <a href="{{ route('dashboard.it.deployments') }}" class="btn btn-ghost btn-sm">
                    View All
                    <i class="fas fa-arrow-right text-xs ml-1"></i>
                </a>
            </div>
        </div>
        <div class="card-body p-0">
            @php
                $deployments = [
                    ['version' => 'v2.4.1', 'environment' => 'Production', 'status' => 'completed', 'deployed_by' => 'Mike IT', 'time' => '2 hours ago'],
                    ['version' => 'v2.4.0', 'environment' => 'Staging', 'status' => 'completed', 'deployed_by' => 'Sarah Dev', 'time' => '1 day ago'],
                    ['version' => 'v2.3.9', 'environment' => 'Production', 'status' => 'rolled_back', 'deployed_by' => 'Tom Lead', 'time' => '3 days ago'],
                    ['version' => 'v2.3.8', 'environment' => 'Production', 'status' => 'completed', 'deployed_by' => 'Mike IT', 'time' => '1 week ago'],
                ];
            @endphp
            @foreach($deployments as $deployment)
            <div class="flex items-center justify-between p-4 border-b border-gray-100 last:border-b-0">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg 
                        @if($deployment['status'] === 'completed') bg-success-100 text-success-600
                        @elseif($deployment['status'] === 'rolled_back') bg-error-100 text-error-600
                        @else bg-warning-100 text-warning-600
                        @endif
                        flex items-center justify-center">
                        @if($deployment['status'] === 'completed')
                            <i class="fas fa-check text-sm"></i>
                        @elseif($deployment['status'] === 'rolled_back')
                            <i class="fas fa-undo text-sm"></i>
                        @else
                            <i class="fas fa-clock text-sm"></i>
                        @endif
                    </div>
                    <div>
                        <div class="font-medium text-gray-900">{{ $deployment['version'] }}</div>
                        <div class="text-sm text-gray-600">{{ $deployment['environment'] }} • {{ $deployment['deployed_by'] }}</div>
                    </div>
                </div>
                <div class="text-right">
                    <span class="badge 
                        @if($deployment['status'] === 'completed') badge-success
                        @elseif($deployment['status'] === 'rolled_back') badge-error
                        @else badge-warning
                        @endif">
                        {{ ucfirst(str_replace('_', ' ', $deployment['status'])) }}
                    </span>
                    <div class="text-xs text-gray-500 mt-1">{{ $deployment['time'] }}</div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Error Logs -->
    <div class="card">
        <div class="card-header">
            <div class="flex items-center justify-between">
                <h3 class="card-title">Recent Error Logs</h3>
                <a href="{{ route('dashboard.it.logs') }}" class="btn btn-ghost btn-sm">
                    View All
                    <i class="fas fa-arrow-right text-xs ml-1"></i>
                </a>
            </div>
        </div>
        <div class="card-body p-0">
            @php
                $errorLogs = [
                    ['level' => 'error', 'message' => 'Payment gateway timeout', 'file' => 'PaymentService.php:142', 'time' => '5 minutes ago'],
                    ['level' => 'warning', 'message' => 'High memory usage detected', 'file' => 'MemoryMonitor.php:89', 'time' => '15 minutes ago'],
                    ['level' => 'error', 'message' => 'Database connection failed', 'file' => 'DatabaseManager.php:67', 'time' => '1 hour ago'],
                    ['level' => 'critical', 'message' => 'Email service unavailable', 'file' => 'EmailService.php:234', 'time' => '2 hours ago'],
                    ['level' => 'warning', 'message' => 'Slow query detected', 'file' => 'QueryLogger.php:45', 'time' => '3 hours ago'],
                ];
            @endphp
            @foreach($errorLogs as $log)
            <div class="p-4 border-b border-gray-100 last:border-b-0">
                <div class="flex items-start gap-3">
                    <div class="w-2 h-2 rounded-full mt-2 
                        @if($log['level'] === 'critical') bg-error-500
                        @elseif($log['level'] === 'error') bg-error-400
                        @elseif($log['level'] === 'warning') bg-warning-500
                        @else bg-gray-400
                        @endif"></div>
                    <div class="flex-1">
                        <div class="flex items-center justify-between">
                            <span class="badge 
                                @if($log['level'] === 'critical') badge-error
                                @elseif($log['level'] === 'error') badge-error
                                @elseif($log['level'] === 'warning') badge-warning
                                @else badge-gray
                                @endif text-xs">
                                {{ ucfirst($log['level']) }}
                            </span>
                            <span class="text-xs text-gray-500">{{ $log['time'] }}</span>
                        </div>
                        <p class="font-medium text-gray-900 text-sm mt-1">{{ $log['message'] }}</p>
                        <p class="text-xs text-gray-600 mt-1">{{ $log['file'] }}</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Performance Chart
    const ctx = document.getElementById('performanceChart').getContext('2d');
    let performanceChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: Array.from({length: 24}, (_, i) => `${i}:00`),
            datasets: [{
                label: 'CPU Usage (%)',
                data: [12, 15, 18, 22, 25, 28, 32, 35, 38, 42, 45, 48, 52, 55, 58, 62, 65, 68, 72, 75, 78, 72, 68, 65],
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
                    max: 100,
                    title: {
                        display: true,
                        text: 'Usage (%)'
                    }
                }
            }
        }
    });

    // Performance Metric Selector
    document.getElementById('performance-metric').addEventListener('change', function() {
        const metric = this.value;
        updatePerformanceChart(metric);
    });

    // Real-time updates
    setInterval(function() {
        updateSystemMetrics();
    }, 30000); // Update every 30 seconds
});

function updatePerformanceChart(metric) {
    const datasets = {
        cpu: {
            label: 'CPU Usage (%)',
            data: [12, 15, 18, 22, 25, 28, 32, 35, 38, 42, 45, 48, 52, 55, 58, 62, 65, 68, 72, 75, 78, 72, 68, 65],
            borderColor: 'rgb(59, 130, 246)',
            backgroundColor: 'rgba(59, 130, 246, 0.1)'
        },
        memory: {
            label: 'Memory Usage (%)',
            data: [45, 48, 52, 55, 58, 62, 65, 68, 72, 75, 78, 82, 85, 88, 92, 95, 98, 95, 92, 88, 85, 82, 78, 75],
            borderColor: 'rgb(34, 197, 94)',
            backgroundColor: 'rgba(34, 197, 94, 0.1)'
        },
        disk: {
            label: 'Disk I/O (MB/s)',
            data: [2.1, 2.5, 3.2, 4.1, 5.5, 6.8, 8.2, 9.5, 11.2, 12.8, 14.5, 16.2, 18.5, 20.1, 22.8, 25.5, 28.2, 25.8, 22.5, 18.2, 14.8, 11.5, 8.2, 5.5],
            borderColor: 'rgb(168, 85, 247)',
            backgroundColor: 'rgba(168, 85, 247, 0.1)'
        },
        network: {
            label: 'Network Traffic (Mbps)',
            data: [15.2, 18.5, 22.1, 25.8, 29.5, 32.1, 35.8, 39.2, 42.5, 45.8, 49.2, 52.5, 55.8, 59.2, 62.5, 65.8, 69.2, 65.5, 61.8, 58.2, 54.5, 50.8, 47.2, 43.5],
            borderColor: 'rgb(234, 179, 8)',
            backgroundColor: 'rgba(234, 179, 8, 0.1)'
        }
    };

    // Update chart with new dataset
    const chart = Chart.getChart('performanceChart');
    chart.data.datasets[0] = {
        ...datasets[metric],
        fill: true,
        tension: 0.4
    };
    chart.update();
}

function refreshServices() {
    // Simulate service refresh
    console.log('Refreshing services...');
    // In real implementation, make API call to refresh service status
}

function refreshAlerts() {
    // Simulate alerts refresh
    console.log('Refreshing alerts...');
    // In real implementation, make API call to refresh alerts
}

function createBackup() {
    if (confirm('Create a new database backup? This may take several minutes.')) {
        console.log('Creating backup...');
        // In real implementation, make API call to create backup
    }
}

function optimizeDatabase() {
    if (confirm('Optimize database? This may temporarily affect performance.')) {
        console.log('Optimizing database...');
        // In real implementation, make API call to optimize database
    }
}

function updateSystemMetrics() {
    // Fetch latest system metrics
    fetch('/api/it/metrics')
        .then(response => response.json())
        .then(data => {
            // Update dashboard with new data
            console.log('Updated system metrics:', data);
        })
        .catch(error => console.error('Error updating metrics:', error));
}
</script>
@endpush