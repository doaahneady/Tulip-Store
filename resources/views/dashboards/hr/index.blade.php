@extends('dashboards.layouts.app', ['title' => 'HR Dashboard', 'subtitle' => 'Human Resources Management & Employee Analytics'])

@section('content')
<!-- HR Overview Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Total Employees -->
    <div class="card">
        <div class="card-body">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium mb-2">Total Employees</p>
                    <h3 class="text-2xl font-semibold text-gray-900">{{ number_format($metrics['total_employees'] ?? 247) }}</h3>
                    <div class="flex items-center gap-1 mt-1">
                        <span class="text-success-600 text-sm font-medium flex items-center gap-1">
                            <i class="fas fa-arrow-up text-xs"></i>
                            +{{ $metrics['employees_growth'] ?? 8 }}%
                        </span>
                        <span class="text-gray-500 text-sm">this quarter</span>
                    </div>
                </div>
                <div class="w-12 h-12 rounded-lg bg-primary-50 text-primary-600 flex items-center justify-center">
                    <i class="fas fa-users text-lg"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Active Drivers -->
    <div class="card">
        <div class="card-body">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium mb-2">Active Drivers</p>
                    <h3 class="text-2xl font-semibold text-gray-900">{{ number_format($metrics['active_drivers'] ?? 45) }}</h3>
                    <div class="flex items-center gap-1 mt-1">
                        <span class="text-success-600 text-sm font-medium">
                            <i class="fas fa-check-circle text-xs"></i>
                            {{ $metrics['drivers_online'] ?? 38 }} online now
                        </span>
                    </div>
                </div>
                <div class="w-12 h-12 rounded-lg bg-success-50 text-success-600 flex items-center justify-center">
                    <i class="fas fa-motorcycle text-lg"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Attendance Rate -->
    <div class="card">
        <div class="card-body">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium mb-2">Attendance Rate</p>
                    <h3 class="text-2xl font-semibold text-gray-900">{{ $metrics['attendance_rate'] ?? 94.5 }}%</h3>
                    <div class="flex items-center gap-1 mt-1">
                        <span class="text-success-600 text-sm font-medium flex items-center gap-1">
                            <i class="fas fa-arrow-up text-xs"></i>
                            +{{ $metrics['attendance_improvement'] ?? 2.1 }}%
                        </span>
                        <span class="text-gray-500 text-sm">vs last month</span>
                    </div>
                </div>
                <div class="w-12 h-12 rounded-lg bg-warning-50 text-warning-600 flex items-center justify-center">
                    <i class="fas fa-calendar-check text-lg"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Pending Payroll -->
    <div class="card">
        <div class="card-body">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium mb-2">Pending Payroll</p>
                    <h3 class="text-2xl font-semibold text-gray-900">${{ number_format($metrics['pending_payroll'] ?? 125750) }}</h3>
                    <div class="flex items-center gap-1 mt-1">
                        <span class="text-warning-600 text-sm font-medium">
                            <i class="fas fa-clock text-xs"></i>
                            {{ $metrics['pending_employees'] ?? 15 }} employees
                        </span>
                    </div>
                </div>
                <div class="w-12 h-12 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center">
                    <i class="fas fa-money-check-alt text-lg"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Main Dashboard Grid -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
    <!-- Employee Analytics Chart -->
    <div class="lg:col-span-2">
        <div class="card">
            <div class="card-header">
                <div class="flex items-center justify-between">
                    <h3 class="card-title">Employee Analytics</h3>
                    <div class="flex items-center gap-2">
                        <select class="form-select text-sm" id="analytics-type">
                            <option value="attendance">Attendance Trends</option>
                            <option value="performance">Performance Metrics</option>
                            <option value="turnover">Turnover Rate</option>
                            <option value="hiring">Hiring Pipeline</option>
                        </select>
                        <select class="form-select text-sm" id="analytics-period">
                            <option value="30d" selected>Last 30 days</option>
                            <option value="90d">Last 90 days</option>
                            <option value="1y">Last year</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <canvas id="employeeAnalyticsChart" height="300"></canvas>
            </div>
        </div>
    </div>

    <!-- Today's Schedule -->
    <div>
        <div class="card">
            <div class="card-header">
                <div class="flex items-center justify-between">
                    <h3 class="card-title">Today's Schedule</h3>
                    <span class="text-sm text-gray-500">{{ now()->format('M d, Y') }}</span>
                </div>
            </div>
            <div class="card-body p-0">
                @php
                    $todaySchedule = [
                        ['employee' => 'Ahmed Hassan', 'role' => 'Driver', 'shift' => '08:00 - 16:00', 'status' => 'present'],
                        ['employee' => 'Sarah Johnson', 'role' => 'CS Agent', 'shift' => '09:00 - 17:00', 'status' => 'present'],
                        ['employee' => 'Mike Chen', 'role' => 'Driver', 'shift' => '10:00 - 18:00', 'status' => 'late'],
                        ['employee' => 'Lisa Rodriguez', 'role' => 'Finance', 'shift' => '08:30 - 16:30', 'status' => 'present'],
                        ['employee' => 'David Kim', 'role' => 'Driver', 'shift' => '14:00 - 22:00', 'status' => 'scheduled'],
                        ['employee' => 'Emma Wilson', 'role' => 'HR Assistant', 'shift' => '09:00 - 17:00', 'status' => 'absent'],
                    ];
                @endphp
                @foreach($todaySchedule as $schedule)
                <div class="flex items-center justify-between p-4 border-b border-gray-100 last:border-b-0">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg 
                            @if($schedule['status'] === 'present') bg-success-100 text-success-600
                            @elseif($schedule['status'] === 'late') bg-warning-100 text-warning-600
                            @elseif($schedule['status'] === 'absent') bg-error-100 text-error-600
                            @else bg-gray-100 text-gray-600
                            @endif
                            flex items-center justify-center">
                            @if($schedule['status'] === 'present')
                                <i class="fas fa-check text-sm"></i>
                            @elseif($schedule['status'] === 'late')
                                <i class="fas fa-clock text-sm"></i>
                            @elseif($schedule['status'] === 'absent')
                                <i class="fas fa-times text-sm"></i>
                            @else
                                <i class="fas fa-calendar text-sm"></i>
                            @endif
                        </div>
                        <div>
                            <div class="font-medium text-gray-900 text-sm">{{ $schedule['employee'] }}</div>
                            <div class="text-gray-600 text-xs">{{ $schedule['role'] }}</div>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-sm text-gray-900">{{ $schedule['shift'] }}</div>
                        <span class="badge 
                            @if($schedule['status'] === 'present') badge-success
                            @elseif($schedule['status'] === 'late') badge-warning
                            @elseif($schedule['status'] === 'absent') badge-error
                            @else badge-gray
                            @endif text-xs">
                            {{ ucfirst($schedule['status']) }}
                        </span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<!-- Department Overview & Recruitment Pipeline -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
    <!-- Department Overview -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Department Overview</h3>
        </div>
        <div class="card-body">
            @php
                $departments = [
                    ['name' => 'Delivery & Logistics', 'employees' => 45, 'budget' => 125000, 'performance' => 92, 'color' => 'bg-blue-500'],
                    ['name' => 'Customer Support', 'employees' => 28, 'budget' => 85000, 'performance' => 88, 'color' => 'bg-green-500'],
                    ['name' => 'Finance & Accounting', 'employees' => 12, 'budget' => 95000, 'performance' => 95, 'color' => 'bg-emerald-500'],
                    ['name' => 'IT & Development', 'employees' => 18, 'budget' => 145000, 'performance' => 90, 'color' => 'bg-purple-500'],
                    ['name' => 'Human Resources', 'employees' => 8, 'budget' => 65000, 'performance' => 87, 'color' => 'bg-orange-500'],
                    ['name' => 'Administration', 'employees' => 15, 'budget' => 75000, 'performance' => 85, 'color' => 'bg-gray-500'],
                ];
            @endphp
            <div class="space-y-4">
                @foreach($departments as $dept)
                <div class="p-4 border border-gray-200 rounded-lg">
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center gap-3">
                            <div class="w-3 h-3 rounded-full {{ $dept['color'] }}"></div>
                            <span class="font-medium text-gray-900">{{ $dept['name'] }}</span>
                        </div>
                        <span class="text-sm text-gray-600">{{ $dept['employees'] }} employees</span>
                    </div>
                    <div class="grid grid-cols-3 gap-4 text-sm">
                        <div>
                            <span class="text-gray-500">Budget:</span>
                            <div class="font-medium">${{ number_format($dept['budget']) }}</div>
                        </div>
                        <div>
                            <span class="text-gray-500">Performance:</span>
                            <div class="font-medium">{{ $dept['performance'] }}%</div>
                        </div>
                        <div>
                            <span class="text-gray-500">Avg Salary:</span>
                            <div class="font-medium">${{ number_format($dept['budget'] / $dept['employees']) }}</div>
                        </div>
                    </div>
                    <!-- Performance Bar -->
                    <div class="mt-3">
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="{{ $dept['color'] }} h-2 rounded-full" style="width: {{ $dept['performance'] }}%"></div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Recruitment Pipeline -->
    <div class="card">
        <div class="card-header">
            <div class="flex items-center justify-between">
                <h3 class="card-title">Recruitment Pipeline</h3>
                <a href="{{ route('dashboard.hr.recruiting') }}" class="btn btn-ghost btn-sm">
                    View All
                    <i class="fas fa-arrow-right text-xs ml-1"></i>
                </a>
            </div>
        </div>
        <div class="card-body">
            <!-- Pipeline Stages -->
            <div class="grid grid-cols-4 gap-4 mb-6">
                <div class="text-center">
                    <div class="w-12 h-12 bg-primary-100 text-primary-600 rounded-lg flex items-center justify-center mx-auto mb-2">
                        <span class="font-bold">{{ $metrics['applications'] ?? 24 }}</span>
                    </div>
                    <div class="text-xs text-gray-600">Applications</div>
                </div>
                <div class="text-center">
                    <div class="w-12 h-12 bg-warning-100 text-warning-600 rounded-lg flex items-center justify-center mx-auto mb-2">
                        <span class="font-bold">{{ $metrics['screening'] ?? 12 }}</span>
                    </div>
                    <div class="text-xs text-gray-600">Screening</div>
                </div>
                <div class="text-center">
                    <div class="w-12 h-12 bg-success-100 text-success-600 rounded-lg flex items-center justify-center mx-auto mb-2">
                        <span class="font-bold">{{ $metrics['interviews'] ?? 6 }}</span>
                    </div>
                    <div class="text-xs text-gray-600">Interviews</div>
                </div>
                <div class="text-center">
                    <div class="w-12 h-12 bg-emerald-100 text-emerald-600 rounded-lg flex items-center justify-center mx-auto mb-2">
                        <span class="font-bold">{{ $metrics['offers'] ?? 3 }}</span>
                    </div>
                    <div class="text-xs text-gray-600">Offers</div>
                </div>
            </div>

            <!-- Recent Candidates -->
            <div>
                <h4 class="font-medium text-gray-900 mb-3">Recent Candidates</h4>
                <div class="space-y-3">
                    @php
                        $candidates = [
                            ['name' => 'John Smith', 'position' => 'Senior Driver', 'stage' => 'Interview', 'score' => 85],
                            ['name' => 'Maria Garcia', 'position' => 'CS Agent', 'stage' => 'Screening', 'score' => 78],
                            ['name' => 'Robert Chen', 'position' => 'IT Specialist', 'stage' => 'Offer', 'score' => 92],
                            ['name' => 'Anna Johnson', 'position' => 'Finance Analyst', 'stage' => 'Interview', 'score' => 88],
                        ];
                    @endphp
                    @foreach($candidates as $candidate)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-primary-100 rounded-full flex items-center justify-center text-primary-600 text-sm font-medium">
                                {{ strtoupper(substr($candidate['name'], 0, 1)) }}
                            </div>
                            <div>
                                <div class="font-medium text-gray-900 text-sm">{{ $candidate['name'] }}</div>
                                <div class="text-gray-600 text-xs">{{ $candidate['position'] }}</div>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="badge 
                                @if($candidate['stage'] === 'Offer') badge-success
                                @elseif($candidate['stage'] === 'Interview') badge-warning
                                @else badge-info
                                @endif text-xs">
                                {{ $candidate['stage'] }}
                            </span>
                            <div class="text-xs text-gray-500 mt-1">Score: {{ $candidate['score'] }}%</div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Payroll Summary & Leave Requests -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    <!-- Payroll Summary -->
    <div class="card">
        <div class="card-header">
            <div class="flex items-center justify-between">
                <h3 class="card-title">Payroll Summary</h3>
                <a href="{{ route('dashboard.hr.payroll') }}" class="btn btn-ghost btn-sm">
                    Process Payroll
                    <i class="fas fa-arrow-right text-xs ml-1"></i>
                </a>
            </div>
        </div>
        <div class="card-body">
            <!-- Payroll Overview -->
            <div class="grid grid-cols-2 gap-4 mb-6">
                <div class="p-3 bg-gray-50 rounded-lg">
                    <div class="text-sm text-gray-600">This Month</div>
                    <div class="text-xl font-semibold text-gray-900">${{ number_format($metrics['monthly_payroll'] ?? 285750) }}</div>
                </div>
                <div class="p-3 bg-gray-50 rounded-lg">
                    <div class="text-sm text-gray-600">Pending Approval</div>
                    <div class="text-xl font-semibold text-gray-900">${{ number_format($metrics['pending_payroll'] ?? 125750) }}</div>
                </div>
            </div>

            <!-- Payroll Breakdown -->
            <div class="space-y-3">
                @php
                    $payrollBreakdown = [
                        ['department' => 'Delivery & Logistics', 'amount' => 95000, 'employees' => 45, 'status' => 'approved'],
                        ['department' => 'IT & Development', 'amount' => 85000, 'employees' => 18, 'status' => 'pending'],
                        ['department' => 'Customer Support', 'amount' => 55000, 'employees' => 28, 'status' => 'approved'],
                        ['department' => 'Finance & Accounting', 'amount' => 45000, 'employees' => 12, 'status' => 'pending'],
                    ];
                @endphp
                @foreach($payrollBreakdown as $payroll)
                <div class="flex items-center justify-between p-3 border border-gray-200 rounded-lg">
                    <div>
                        <div class="font-medium text-gray-900 text-sm">{{ $payroll['department'] }}</div>
                        <div class="text-gray-600 text-xs">{{ $payroll['employees'] }} employees</div>
                    </div>
                    <div class="text-right">
                        <div class="font-semibold text-gray-900">${{ number_format($payroll['amount']) }}</div>
                        <span class="badge {{ $payroll['status'] === 'approved' ? 'badge-success' : 'badge-warning' }} text-xs">
                            {{ ucfirst($payroll['status']) }}
                        </span>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Quick Actions -->
            <div class="flex gap-2 mt-4">
                <button class="btn btn-sm btn-primary" onclick="processPayroll()">
                    <i class="fas fa-play"></i>
                    Process All
                </button>
                <button class="btn btn-sm btn-secondary" onclick="exportPayroll()">
                    <i class="fas fa-download"></i>
                    Export
                </button>
            </div>
        </div>
    </div>

    <!-- Leave Requests -->
    <div class="card">
        <div class="card-header">
            <div class="flex items-center justify-between">
                <h3 class="card-title">Leave Requests</h3>
                <span class="badge badge-warning">{{ $metrics['pending_leaves'] ?? 5 }} pending</span>
            </div>
        </div>
        <div class="card-body p-0">
            @php
                $leaveRequests = [
                    ['employee' => 'Ahmed Hassan', 'type' => 'Vacation', 'dates' => 'Dec 20-25', 'days' => 5, 'status' => 'pending'],
                    ['employee' => 'Sarah Johnson', 'type' => 'Sick Leave', 'dates' => 'Dec 18', 'days' => 1, 'status' => 'approved'],
                    ['employee' => 'Mike Chen', 'type' => 'Personal', 'dates' => 'Dec 22-23', 'days' => 2, 'status' => 'pending'],
                    ['employee' => 'Lisa Rodriguez', 'type' => 'Vacation', 'dates' => 'Jan 2-5', 'days' => 4, 'status' => 'pending'],
                    ['employee' => 'David Kim', 'type' => 'Sick Leave', 'dates' => 'Dec 17', 'days' => 1, 'status' => 'rejected'],
                ];
            @endphp
            @foreach($leaveRequests as $request)
            <div class="flex items-center justify-between p-4 border-b border-gray-100 last:border-b-0">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-primary-100 rounded-full flex items-center justify-center text-primary-600 text-sm font-medium">
                        {{ strtoupper(substr($request['employee'], 0, 1)) }}
                    </div>
                    <div>
                        <div class="font-medium text-gray-900 text-sm">{{ $request['employee'] }}</div>
                        <div class="text-gray-600 text-xs">{{ $request['type'] }} • {{ $request['dates'] }} ({{ $request['days'] }} days)</div>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <span class="badge 
                        @if($request['status'] === 'approved') badge-success
                        @elseif($request['status'] === 'rejected') badge-error
                        @else badge-warning
                        @endif text-xs">
                        {{ ucfirst($request['status']) }}
                    </span>
                    @if($request['status'] === 'pending')
                        <div class="flex gap-1">
                            <button class="btn btn-sm" style="background-color: var(--success-600); color: white; padding: 0.25rem 0.5rem;" onclick="approveLeave('{{ $request['employee'] }}')">
                                <i class="fas fa-check text-xs"></i>
                            </button>
                            <button class="btn btn-sm" style="background-color: var(--error-600); color: white; padding: 0.25rem 0.5rem;" onclick="rejectLeave('{{ $request['employee'] }}')">
                                <i class="fas fa-times text-xs"></i>
                            </button>
                        </div>
                    @endif
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
    // Employee Analytics Chart
    const ctx = document.getElementById('employeeAnalyticsChart').getContext('2d');
    let analyticsChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4'],
            datasets: [{
                label: 'Attendance Rate (%)',
                data: [92, 94, 91, 95],
                borderColor: 'rgb(34, 197, 94)',
                backgroundColor: 'rgba(34, 197, 94, 0.1)',
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
                        text: 'Percentage (%)'
                    }
                }
            }
        }
    });

    // Analytics Type Selector
    document.getElementById('analytics-type').addEventListener('change', function() {
        const type = this.value;
        updateAnalyticsChart(type);
    });
});

function updateAnalyticsChart(type) {
    const datasets = {
        attendance: {
            label: 'Attendance Rate (%)',
            data: [92, 94, 91, 95],
            borderColor: 'rgb(34, 197, 94)',
            backgroundColor: 'rgba(34, 197, 94, 0.1)'
        },
        performance: {
            label: 'Performance Score',
            data: [85, 88, 87, 90],
            borderColor: 'rgb(59, 130, 246)',
            backgroundColor: 'rgba(59, 130, 246, 0.1)'
        },
        turnover: {
            label: 'Turnover Rate (%)',
            data: [5.2, 4.8, 5.5, 4.2],
            borderColor: 'rgb(239, 68, 68)',
            backgroundColor: 'rgba(239, 68, 68, 0.1)'
        },
        hiring: {
            label: 'New Hires',
            data: [8, 12, 6, 15],
            borderColor: 'rgb(168, 85, 247)',
            backgroundColor: 'rgba(168, 85, 247, 0.1)'
        }
    };

    const chart = Chart.getChart('employeeAnalyticsChart');
    chart.data.datasets[0] = {
        ...datasets[type],
        fill: true,
        tension: 0.4
    };
    chart.update();
}

function processPayroll() {
    if (confirm('Process payroll for all pending departments? This action cannot be undone.')) {
        console.log('Processing payroll...');
        // In real implementation, make API call to process payroll
    }
}

function exportPayroll() {
    console.log('Exporting payroll data...');
    // In real implementation, generate and download payroll report
}

function approveLeave(employee) {
    if (confirm(`Approve leave request for ${employee}?`)) {
        console.log(`Approving leave for ${employee}`);
        // In real implementation, make API call to approve leave
    }
}

function rejectLeave(employee) {
    if (confirm(`Reject leave request for ${employee}?`)) {
        console.log(`Rejecting leave for ${employee}`);
        // In real implementation, make API call to reject leave
    }
}
</script>
@endpush