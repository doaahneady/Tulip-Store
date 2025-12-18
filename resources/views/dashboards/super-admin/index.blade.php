@extends('dashboards.layouts.app', ['title' => 'Super Admin Dashboard', 'subtitle' => 'God Mode - Complete Platform Overview'])

@section('content')
<!-- Platform Overview Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Total Users -->
    <div class="bg-white rounded-xl p-6 border border-gray-200 shadow-sm hover:shadow-lg hover:border-blue-200 hover:-translate-y-1 transition-all duration-300">
        <div class="flex items-center justify-between">
            <div class="flex-1">
                <div class="text-sm font-medium text-gray-600 uppercase tracking-wide">Total Users</div>
                <div class="text-3xl font-bold text-gray-900 mt-2">{{ number_format($metrics['total_users'] ?? 1247) }}</div>
                <div class="flex items-center gap-1 mt-2 text-green-600 text-sm font-medium">
                    <i class="fas fa-arrow-up"></i>
                    <span>+{{ $metrics['user_growth'] ?? 12 }}% this month</span>
                </div>
            </div>
            <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center">
                <i class="fas fa-users text-xl"></i>
            </div>
        </div>
    </div>

    <!-- Total Revenue -->
    <div class="bg-white rounded-xl p-6 border border-gray-200 shadow-sm hover:shadow-lg hover:border-green-200 hover:-translate-y-1 transition-all duration-300">
        <div class="flex items-center justify-between">
            <div class="flex-1">
                <div class="text-sm font-medium text-gray-600 uppercase tracking-wide">Platform Revenue</div>
                <div class="text-3xl font-bold text-green-600 mt-2">${{ number_format($metrics['total_revenue'] ?? 2847500, 0) }}</div>
                <div class="flex items-center gap-1 mt-2 text-green-600 text-sm font-medium">
                    <i class="fas fa-arrow-up"></i>
                    <span>+{{ $metrics['revenue_growth'] ?? 18 }}% vs last month</span>
                </div>
            </div>
            <div class="w-12 h-12 bg-green-50 text-green-600 rounded-xl flex items-center justify-center">
                <i class="fas fa-dollar-sign text-xl"></i>
            </div>
        </div>
    </div>

    <!-- Active Stores -->
    <div class="metric-card">
        <div class="flex items-center justify-between">
            <div class="flex-1">
                <div class="metric-label">Active Stores</div>
                <div class="metric-value text-warning-600">{{ number_format($metrics['active_stores'] ?? 156) }}</div>
                <div class="metric-change text-success-600">
                    <i class="fas fa-arrow-up"></i>
                    <span>+{{ $metrics['stores_growth'] ?? 8 }}% new this month</span>
                </div>
            </div>
            <div class="metric-icon bg-warning-50 text-warning-600">
                <i class="fas fa-store"></i>
            </div>
        </div>
    </div>

    <!-- System Health -->
    <div class="metric-card">
        <div class="flex items-center justify-between">
            <div class="flex-1">
                <div class="metric-label">System Health</div>
                <div class="metric-value text-success-600">{{ $metrics['system_health'] ?? 99.8 }}%</div>
                <div class="metric-change text-success-600">
                    <i class="fas fa-check-circle"></i>
                    <span>All systems operational</span>
                </div>
            </div>
            <div class="metric-icon bg-success-50 text-success-600">
                <i class="fas fa-heartbeat"></i>
            </div>
        </div>
    </div>
</div>

<!-- Main Dashboard Grid -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
    <!-- Platform Analytics Chart -->
    <div class="lg:col-span-2">
        <div class="card">
            <div class="card-header">
                <div class="flex items-center justify-between">
                    <h3 class="card-title">Platform Growth Analytics</h3>
                    <div class="flex items-center gap-2">
                        <select class="form-select text-sm" id="analytics-period">
                            <option value="7d">Last 7 days</option>
                            <option value="30d" selected>Last 30 days</option>
                            <option value="90d">Last 90 days</option>
                            <option value="1y">Last year</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <canvas id="platformAnalyticsChart" height="300"></canvas>
            </div>
        </div>
    </div>

    <!-- Recent Activities -->
    <div>
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Recent Admin Activities</h3>
            </div>
            <div class="card-body p-0">
                @php
                    $activities = [
                        ['user' => 'John Admin', 'action' => 'Created new user role', 'time' => '2 minutes ago', 'type' => 'user'],
                        ['user' => 'Sarah Manager', 'action' => 'Approved store verification', 'time' => '15 minutes ago', 'type' => 'store'],
                        ['user' => 'Mike IT', 'action' => 'Deployed system update', 'time' => '1 hour ago', 'type' => 'system'],
                        ['user' => 'Lisa Finance', 'action' => 'Processed bulk payouts', 'time' => '2 hours ago', 'type' => 'finance'],
                        ['user' => 'Tom Support', 'action' => 'Resolved critical ticket', 'time' => '3 hours ago', 'type' => 'support'],
                    ];
                @endphp
                @foreach($activities as $activity)
                <div class="flex items-center gap-3 p-4 border-b border-gray-100 last:border-b-0">
                    <div class="w-8 h-8 rounded-lg 
                        @if($activity['type'] === 'user') bg-primary-100 text-primary-600
                        @elseif($activity['type'] === 'store') bg-warning-100 text-warning-600
                        @elseif($activity['type'] === 'system') bg-success-100 text-success-600
                        @elseif($activity['type'] === 'finance') bg-emerald-100 text-emerald-600
                        @else bg-gray-100 text-gray-600
                        @endif
                        flex items-center justify-center">
                        @if($activity['type'] === 'user')
                            <i class="fas fa-user text-sm"></i>
                        @elseif($activity['type'] === 'store')
                            <i class="fas fa-store text-sm"></i>
                        @elseif($activity['type'] === 'system')
                            <i class="fas fa-cog text-sm"></i>
                        @elseif($activity['type'] === 'finance')
                            <i class="fas fa-dollar-sign text-sm"></i>
                        @else
                            <i class="fas fa-bell text-sm"></i>
                        @endif
                    </div>
                    <div class="flex-1">
                        <p class="font-medium text-gray-900 text-sm">{{ $activity['user'] }}</p>
                        <p class="text-gray-600 text-xs">{{ $activity['action'] }}</p>
                        <p class="text-gray-500 text-xs">{{ $activity['time'] }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<!-- System Status & Quick Actions -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
    <!-- System Services Status -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">System Services Status</h3>
        </div>
        <div class="card-body">
            @php
                $services = [
                    ['name' => 'Web Server', 'status' => 'online', 'response_time' => '45ms', 'uptime' => '99.9%'],
                    ['name' => 'Database', 'status' => 'online', 'response_time' => '12ms', 'uptime' => '99.8%'],
                    ['name' => 'Redis Cache', 'status' => 'online', 'response_time' => '3ms', 'uptime' => '99.9%'],
                    ['name' => 'Payment Gateway', 'status' => 'online', 'response_time' => '120ms', 'uptime' => '99.7%'],
                    ['name' => 'Email Service', 'status' => 'degraded', 'response_time' => '2.1s', 'uptime' => '98.5%'],
                    ['name' => 'File Storage', 'status' => 'online', 'response_time' => '89ms', 'uptime' => '99.6%'],
                ];
            @endphp
            <div class="space-y-3">
                @foreach($services as $service)
                <div class="flex items-center justify-between p-3 rounded-lg border border-gray-200">
                    <div class="flex items-center gap-3">
                        <div class="w-3 h-3 rounded-full 
                            @if($service['status'] === 'online') bg-success-500
                            @elseif($service['status'] === 'degraded') bg-warning-500
                            @else bg-error-500
                            @endif"></div>
                        <span class="font-medium text-gray-900">{{ $service['name'] }}</span>
                    </div>
                    <div class="text-right">
                        <div class="text-sm text-gray-600">{{ $service['response_time'] }}</div>
                        <div class="text-xs text-gray-500">{{ $service['uptime'] }} uptime</div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Emergency Actions -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Emergency Controls</h3>
        </div>
        <div class="card-body">
            <div class="space-y-4">
                <!-- Maintenance Mode -->
                <div class="p-4 border border-warning-200 rounded-lg bg-warning-50">
                    <div class="flex items-center justify-between mb-2">
                        <span class="font-medium text-warning-800">Maintenance Mode</span>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" class="sr-only peer" id="maintenance-mode">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-warning-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-warning-600"></div>
                        </label>
                    </div>
                    <p class="text-sm text-warning-700">Enable to put the platform in maintenance mode</p>
                </div>

                <!-- Force User Logout -->
                <div class="p-4 border border-error-200 rounded-lg bg-error-50">
                    <h4 class="font-medium text-error-800 mb-2">Force User Logout</h4>
                    <div class="flex gap-2">
                        <input type="text" placeholder="User ID or Email" class="form-input flex-1 text-sm">
                        <button class="btn btn-sm" style="background-color: var(--error-600); color: white; border-color: var(--error-600);">
                            Logout
                        </button>
                    </div>
                </div>

                <!-- System Alerts -->
                <div class="p-4 border border-primary-200 rounded-lg bg-primary-50">
                    <h4 class="font-medium text-primary-800 mb-2">Broadcast Alert</h4>
                    <textarea placeholder="System-wide announcement..." class="form-input w-full text-sm mb-2" rows="2"></textarea>
                    <button class="btn btn-sm btn-primary">Send Alert</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Users & Audit Logs -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    <!-- Recent Users -->
    <div class="card">
        <div class="card-header">
            <div class="flex items-center justify-between">
                <h3 class="card-title">Recent User Registrations</h3>
                <a href="{{ route('dashboard.admin.users') }}" class="btn-action btn-primary-action">
                    <span>View All</span>
                    <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-enhanced">
                <table class="table">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Joined</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $recentUsers = [
                                ['name' => 'Ahmed Hassan', 'email' => 'ahmed@example.com', 'role' => 'Store Owner', 'status' => 'active', 'joined' => '2 hours ago'],
                                ['name' => 'Sarah Johnson', 'email' => 'sarah@example.com', 'role' => 'Customer', 'status' => 'active', 'joined' => '4 hours ago'],
                                ['name' => 'Mike Chen', 'email' => 'mike@example.com', 'role' => 'Driver', 'status' => 'pending', 'joined' => '6 hours ago'],
                                ['name' => 'Lisa Rodriguez', 'email' => 'lisa@example.com', 'role' => 'Customer', 'status' => 'active', 'joined' => '8 hours ago'],
                                ['name' => 'David Kim', 'email' => 'david@example.com', 'role' => 'Store Owner', 'status' => 'pending', 'joined' => '1 day ago'],
                            ];
                        @endphp
                        @foreach($recentUsers as $user)
                        <tr>
                            <td>
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 bg-primary-100 rounded-full flex items-center justify-center text-primary-600 text-xs font-medium">
                                        {{ strtoupper(substr($user['name'], 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="font-medium text-gray-900 text-sm">{{ $user['name'] }}</div>
                                        <div class="text-gray-500 text-xs">{{ $user['email'] }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge badge-gray">{{ $user['role'] }}</span>
                            </td>
                            <td>
                                <span class="badge {{ $user['status'] === 'active' ? 'badge-success' : 'badge-warning' }}">
                                    {{ ucfirst($user['status']) }}
                                </span>
                            </td>
                            <td class="text-gray-500 text-sm">{{ $user['joined'] }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Critical Audit Logs -->
    <div class="card">
        <div class="card-header">
            <div class="flex items-center justify-between">
                <h3 class="card-title">Critical Audit Logs</h3>
                <a href="{{ route('dashboard.admin.audit-logs') }}" class="btn-action btn-primary-action">
                    <span>View All</span>
                    <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
        <div class="card-body p-0">
            @php
                $auditLogs = [
                    ['action' => 'User Role Changed', 'user' => 'admin@tulip.com', 'target' => 'john@store.com', 'time' => '10 minutes ago', 'severity' => 'high'],
                    ['action' => 'Financial Transaction', 'user' => 'finance@tulip.com', 'target' => '$15,000 payout', 'time' => '25 minutes ago', 'severity' => 'medium'],
                    ['action' => 'System Configuration', 'user' => 'it@tulip.com', 'target' => 'Payment Gateway', 'time' => '1 hour ago', 'severity' => 'high'],
                    ['action' => 'Store Suspension', 'user' => 'admin@tulip.com', 'target' => 'Store #156', 'time' => '2 hours ago', 'severity' => 'critical'],
                    ['action' => 'Bulk User Import', 'user' => 'hr@tulip.com', 'target' => '50 employees', 'time' => '3 hours ago', 'severity' => 'medium'],
                ];
            @endphp
            @foreach($auditLogs as $log)
            <div class="flex items-center gap-3 p-4 border-b border-gray-100 last:border-b-0">
                <div class="w-2 h-8 rounded-full 
                    @if($log['severity'] === 'critical') bg-error-500
                    @elseif($log['severity'] === 'high') bg-warning-500
                    @else bg-primary-500
                    @endif"></div>
                <div class="flex-1">
                    <div class="flex items-center justify-between">
                        <span class="font-medium text-gray-900 text-sm">{{ $log['action'] }}</span>
                        <span class="text-xs text-gray-500">{{ $log['time'] }}</span>
                    </div>
                    <div class="text-xs text-gray-600 mt-1">
                        <span class="font-medium">{{ $log['user'] }}</span> → {{ $log['target'] }}
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
    // Platform Analytics Chart
    const ctx = document.getElementById('platformAnalyticsChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            datasets: [
                {
                    label: 'Revenue ($)',
                    data: [120000, 135000, 148000, 162000, 175000, 189000, 203000, 218000, 235000, 252000, 268000, 285000],
                    borderColor: 'rgb(34, 197, 94)',
                    backgroundColor: 'rgba(34, 197, 94, 0.1)',
                    fill: true,
                    tension: 0.4
                },
                {
                    label: 'Users',
                    data: [850, 920, 1050, 1180, 1320, 1450, 1580, 1720, 1860, 2000, 2140, 2280],
                    borderColor: 'rgb(59, 130, 246)',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    fill: true,
                    tension: 0.4,
                    yAxisID: 'y1'
                },
                {
                    label: 'Orders',
                    data: [450, 520, 580, 640, 720, 800, 880, 960, 1040, 1120, 1200, 1280],
                    borderColor: 'rgb(168, 85, 247)',
                    backgroundColor: 'rgba(168, 85, 247, 0.1)',
                    fill: true,
                    tension: 0.4,
                    yAxisID: 'y1'
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
                    type: 'linear',
                    display: true,
                    position: 'left',
                    title: {
                        display: true,
                        text: 'Revenue ($)'
                    }
                },
                y1: {
                    type: 'linear',
                    display: true,
                    position: 'right',
                    title: {
                        display: true,
                        text: 'Count'
                    },
                    grid: {
                        drawOnChartArea: false,
                    },
                }
            }
        }
    });

    // Maintenance Mode Toggle
    document.getElementById('maintenance-mode').addEventListener('change', function() {
        if (this.checked) {
            if (confirm('Are you sure you want to enable maintenance mode? This will make the platform unavailable to users.')) {
                // API call to enable maintenance mode
                console.log('Enabling maintenance mode...');
            } else {
                this.checked = false;
            }
        } else {
            // API call to disable maintenance mode
            console.log('Disabling maintenance mode...');
        }
    });

    // Real-time updates
    setInterval(function() {
        // Update system metrics
        updateSystemMetrics();
    }, 30000); // Update every 30 seconds
});

function updateSystemMetrics() {
    // Fetch latest metrics from API
    fetch('/api/admin/metrics')
        .then(response => response.json())
        .then(data => {
            // Update dashboard with new data
            console.log('Updated metrics:', data);
        })
        .catch(error => console.error('Error updating metrics:', error));
}
</script>
@endpush