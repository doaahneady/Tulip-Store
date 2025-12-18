@extends('dashboards.layouts.app', ['title' => 'Finance Dashboard', 'subtitle' => 'Financial Operations & Transaction Management'])

@section('content')
<!-- Finance Overview Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Total Revenue -->
    <div class="card">
        <div class="card-body">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium mb-2">Total Revenue</p>
                    <h3 class="text-2xl font-semibold text-gray-900">${{ number_format($metrics['total_revenue'] ?? 2847500) }}</h3>
                    <div class="flex items-center gap-1 mt-1">
                        <span class="text-success-600 text-sm font-medium flex items-center gap-1">
                            <i class="fas fa-arrow-up text-xs"></i>
                            +{{ $metrics['revenue_growth'] ?? 18 }}%
                        </span>
                        <span class="text-gray-500 text-sm">vs last month</span>
                    </div>
                </div>
                <div class="w-12 h-12 rounded-lg bg-success-50 text-success-600 flex items-center justify-center">
                    <i class="fas fa-dollar-sign text-lg"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Pending Payouts -->
    <div class="card">
        <div class="card-body">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium mb-2">Pending Payouts</p>
                    <h3 class="text-2xl font-semibold text-gray-900">${{ number_format($metrics['pending_payouts'] ?? 145750) }}</h3>
                    <div class="flex items-center gap-1 mt-1">
                        <span class="text-warning-600 text-sm font-medium">
                            <i class="fas fa-clock text-xs"></i>
                            {{ $metrics['payout_requests'] ?? 23 }} requests
                        </span>
                    </div>
                </div>
                <div class="w-12 h-12 rounded-lg bg-warning-50 text-warning-600 flex items-center justify-center">
                    <i class="fas fa-hand-holding-usd text-lg"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Monthly Profit -->
    <div class="card">
        <div class="card-body">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium mb-2">Monthly Profit</p>
                    <h3 class="text-2xl font-semibold text-gray-900">${{ number_format($metrics['monthly_profit'] ?? 425800) }}</h3>
                    <div class="flex items-center gap-1 mt-1">
                        <span class="text-success-600 text-sm font-medium flex items-center gap-1">
                            <i class="fas fa-arrow-up text-xs"></i>
                            +{{ $metrics['profit_margin'] ?? 15.2 }}%
                        </span>
                        <span class="text-gray-500 text-sm">profit margin</span>
                    </div>
                </div>
                <div class="w-12 h-12 rounded-lg bg-primary-50 text-primary-600 flex items-center justify-center">
                    <i class="fas fa-chart-line text-lg"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Transaction Volume -->
    <div class="card">
        <div class="card-body">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium mb-2">Transactions</p>
                    <h3 class="text-2xl font-semibold text-gray-900">{{ number_format($metrics['transactions'] ?? 15847) }}</h3>
                    <div class="flex items-center gap-1 mt-1">
                        <span class="text-primary-600 text-sm font-medium">
                            <i class="fas fa-exchange-alt text-xs"></i>
                            {{ $metrics['avg_transaction'] ?? 179 }} avg value
                        </span>
                    </div>
                </div>
                <div class="w-12 h-12 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center">
                    <i class="fas fa-receipt text-lg"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Main Dashboard Grid -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
    <!-- Revenue Analytics Chart -->
    <div class="lg:col-span-2">
        <div class="card">
            <div class="card-header">
                <div class="flex items-center justify-between">
                    <h3 class="card-title">Revenue & Profit Analytics</h3>
                    <div class="flex items-center gap-2">
                        <select class="form-select text-sm" id="revenue-metric">
                            <option value="revenue">Revenue</option>
                            <option value="profit">Profit</option>
                            <option value="commission">Commission</option>
                            <option value="expenses">Expenses</option>
                        </select>
                        <select class="form-select text-sm" id="revenue-period">
                            <option value="30d" selected>Last 30 days</option>
                            <option value="90d">Last 90 days</option>
                            <option value="1y">Last year</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <canvas id="revenueAnalyticsChart" height="300"></canvas>
            </div>
        </div>
    </div>

    <!-- Pending Approvals -->
    <div>
        <div class="card">
            <div class="card-header">
                <div class="flex items-center justify-between">
                    <h3 class="card-title">Pending Approvals</h3>
                    <span class="badge badge-warning">{{ $metrics['pending_approvals'] ?? 8 }}</span>
                </div>
            </div>
            <div class="card-body p-0">
                @php
                    $approvals = [
                        ['type' => 'Payout Request', 'amount' => 15000, 'store' => 'TechStore Plus', 'time' => '2 hours ago', 'priority' => 'high'],
                        ['type' => 'Expense Claim', 'amount' => 850, 'store' => 'HR Department', 'time' => '4 hours ago', 'priority' => 'normal'],
                        ['type' => 'Payout Request', 'amount' => 8500, 'store' => 'Fashion Hub', 'time' => '6 hours ago', 'priority' => 'normal'],
                        ['type' => 'Refund Request', 'amount' => 250, 'store' => 'Customer #1234', 'time' => '8 hours ago', 'priority' => 'low'],
                        ['type' => 'Payout Request', 'amount' => 22000, 'store' => 'Electronics World', 'time' => '1 day ago', 'priority' => 'high'],
                    ];
                @endphp
                @foreach($approvals as $approval)
                <div class="flex items-center justify-between p-4 border-b border-gray-100 last:border-b-0">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg 
                            @if($approval['priority'] === 'high') bg-error-100 text-error-600
                            @elseif($approval['priority'] === 'normal') bg-warning-100 text-warning-600
                            @else bg-gray-100 text-gray-600
                            @endif
                            flex items-center justify-center">
                            @if($approval['type'] === 'Payout Request')
                                <i class="fas fa-hand-holding-usd text-sm"></i>
                            @elseif($approval['type'] === 'Expense Claim')
                                <i class="fas fa-receipt text-sm"></i>
                            @else
                                <i class="fas fa-undo text-sm"></i>
                            @endif
                        </div>
                        <div>
                            <div class="font-medium text-gray-900 text-sm">{{ $approval['type'] }}</div>
                            <div class="text-gray-600 text-xs">{{ $approval['store'] }}</div>
                            <div class="text-gray-500 text-xs">{{ $approval['time'] }}</div>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="font-semibold text-gray-900">${{ number_format($approval['amount']) }}</div>
                        <div class="flex gap-1 mt-1">
                            <button class="btn btn-sm" style="background-color: var(--success-600); color: white; padding: 0.25rem 0.5rem;" onclick="approveTransaction('{{ $approval['type'] }}', {{ $approval['amount'] }})">
                                <i class="fas fa-check text-xs"></i>
                            </button>
                            <button class="btn btn-sm" style="background-color: var(--error-600); color: white; padding: 0.25rem 0.5rem;" onclick="rejectTransaction('{{ $approval['type'] }}', {{ $approval['amount'] }})">
                                <i class="fas fa-times text-xs"></i>
                            </button>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<!-- Transaction Overview & Tax Summary -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
    <!-- Recent Transactions -->
    <div class="card">
        <div class="card-header">
            <div class="flex items-center justify-between">
                <h3 class="card-title">Recent Transactions</h3>
                <a href="{{ route('dashboard.finance.transactions') }}" class="btn btn-ghost btn-sm">
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
                            <th>Transaction</th>
                            <th>Type</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $transactions = [
                                ['id' => 'TXN-5847', 'type' => 'Order Payment', 'amount' => 1250, 'status' => 'completed', 'date' => '2 hours ago'],
                                ['id' => 'TXN-5846', 'type' => 'Payout', 'amount' => -8500, 'status' => 'processing', 'date' => '4 hours ago'],
                                ['id' => 'TXN-5845', 'type' => 'Commission', 'amount' => 125, 'status' => 'completed', 'date' => '6 hours ago'],
                                ['id' => 'TXN-5844', 'type' => 'Refund', 'amount' => -350, 'status' => 'completed', 'date' => '8 hours ago'],
                                ['id' => 'TXN-5843', 'type' => 'Order Payment', 'amount' => 2100, 'status' => 'completed', 'date' => '1 day ago'],
                            ];
                        @endphp
                        @foreach($transactions as $transaction)
                        <tr>
                            <td>
                                <span class="font-medium text-primary-600">{{ $transaction['id'] }}</span>
                            </td>
                            <td>
                                <span class="badge badge-gray">{{ $transaction['type'] }}</span>
                            </td>
                            <td>
                                <span class="font-medium {{ $transaction['amount'] > 0 ? 'text-success-600' : 'text-error-600' }}">
                                    {{ $transaction['amount'] > 0 ? '+' : '' }}${{ number_format(abs($transaction['amount'])) }}
                                </span>
                            </td>
                            <td>
                                <span class="badge 
                                    @if($transaction['status'] === 'completed') badge-success
                                    @elseif($transaction['status'] === 'processing') badge-warning
                                    @else badge-gray
                                    @endif">
                                    {{ ucfirst($transaction['status']) }}
                                </span>
                            </td>
                            <td class="text-gray-500 text-sm">{{ $transaction['date'] }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Tax & Compliance Summary -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Tax & Compliance</h3>
        </div>
        <div class="card-body">
            <!-- Tax Overview -->
            <div class="grid grid-cols-2 gap-4 mb-6">
                <div class="p-3 bg-gray-50 rounded-lg">
                    <div class="text-sm text-gray-600">VAT Collected</div>
                    <div class="text-xl font-semibold text-gray-900">${{ number_format($metrics['vat_collected'] ?? 42750) }}</div>
                </div>
                <div class="p-3 bg-gray-50 rounded-lg">
                    <div class="text-sm text-gray-600">Tax Liability</div>
                    <div class="text-xl font-semibold text-gray-900">${{ number_format($metrics['tax_liability'] ?? 38250) }}</div>
                </div>
            </div>

            <!-- Tax Breakdown -->
            <div class="mb-6">
                <h4 class="font-medium text-gray-900 mb-3">Monthly Tax Breakdown</h4>
                <div class="space-y-3">
                    @php
                        $taxBreakdown = [
                            ['type' => 'Sales Tax (15%)', 'amount' => 28500, 'percentage' => 65],
                            ['type' => 'Service Tax (5%)', 'amount' => 9500, 'percentage' => 22],
                            ['type' => 'Platform Fee Tax', 'amount' => 4750, 'percentage' => 13],
                        ];
                    @endphp
                    @foreach($taxBreakdown as $tax)
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <div class="flex items-center justify-between mb-1">
                                <span class="text-sm font-medium text-gray-900">{{ $tax['type'] }}</span>
                                <span class="text-sm text-gray-600">${{ number_format($tax['amount']) }}</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-primary-600 h-2 rounded-full" style="width: {{ $tax['percentage'] }}%"></div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Compliance Status -->
            <div class="space-y-2">
                <div class="flex items-center justify-between p-2 bg-success-50 rounded">
                    <span class="text-sm text-success-800">Monthly VAT Filing</span>
                    <i class="fas fa-check-circle text-success-600"></i>
                </div>
                <div class="flex items-center justify-between p-2 bg-success-50 rounded">
                    <span class="text-sm text-success-800">Quarterly Report</span>
                    <i class="fas fa-check-circle text-success-600"></i>
                </div>
                <div class="flex items-center justify-between p-2 bg-warning-50 rounded">
                    <span class="text-sm text-warning-800">Annual Audit</span>
                    <span class="text-xs text-warning-600">Due in 45 days</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Financial Reports & Commission Tracking -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    <!-- P&L Summary -->
    <div class="card">
        <div class="card-header">
            <div class="flex items-center justify-between">
                <h3 class="card-title">P&L Summary</h3>
                <a href="{{ route('dashboard.finance.reports') }}" class="btn btn-ghost btn-sm">
                    Full Report
                    <i class="fas fa-arrow-right text-xs ml-1"></i>
                </a>
            </div>
        </div>
        <div class="card-body">
            <!-- P&L Chart -->
            <div class="mb-6">
                <canvas id="profitLossChart" height="200"></canvas>
            </div>

            <!-- P&L Details -->
            <div class="space-y-3">
                @php
                    $plItems = [
                        ['item' => 'Total Revenue', 'amount' => 2847500, 'type' => 'revenue'],
                        ['item' => 'Platform Commissions', 'amount' => 427125, 'type' => 'revenue'],
                        ['item' => 'Payment Processing', 'amount' => -85500, 'type' => 'expense'],
                        ['item' => 'Staff Salaries', 'amount' => -285000, 'type' => 'expense'],
                        ['item' => 'Infrastructure Costs', 'amount' => -45000, 'type' => 'expense'],
                        ['item' => 'Marketing Expenses', 'amount' => -125000, 'type' => 'expense'],
                    ];
                @endphp
                @foreach($plItems as $item)
                <div class="flex items-center justify-between p-2 border-b border-gray-100 last:border-b-0">
                    <span class="text-sm text-gray-700">{{ $item['item'] }}</span>
                    <span class="font-medium {{ $item['type'] === 'revenue' ? 'text-success-600' : 'text-error-600' }}">
                        {{ $item['amount'] > 0 ? '+' : '' }}${{ number_format($item['amount']) }}
                    </span>
                </div>
                @endforeach
                <div class="flex items-center justify-between p-3 bg-primary-50 rounded-lg mt-3">
                    <span class="font-semibold text-primary-800">Net Profit</span>
                    <span class="font-bold text-primary-800">${{ number_format(array_sum(array_column($plItems, 'amount'))) }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Commission Tracking -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Commission Tracking</h3>
        </div>
        <div class="card-body">
            <!-- Commission Overview -->
            <div class="grid grid-cols-2 gap-4 mb-6">
                <div class="p-3 bg-gray-50 rounded-lg text-center">
                    <div class="text-2xl font-bold text-primary-600">{{ $metrics['commission_rate'] ?? 15 }}%</div>
                    <div class="text-sm text-gray-600">Avg Commission Rate</div>
                </div>
                <div class="p-3 bg-gray-50 rounded-lg text-center">
                    <div class="text-2xl font-bold text-success-600">${{ number_format($metrics['monthly_commission'] ?? 427125) }}</div>
                    <div class="text-sm text-gray-600">This Month</div>
                </div>
            </div>

            <!-- Top Earning Stores -->
            <div class="mb-4">
                <h4 class="font-medium text-gray-900 mb-3">Top Commission Generators</h4>
                <div class="space-y-3">
                    @php
                        $topStores = [
                            ['store' => 'TechStore Plus', 'commission' => 45000, 'rate' => 15],
                            ['store' => 'Fashion Hub', 'commission' => 38500, 'rate' => 12],
                            ['store' => 'Electronics World', 'commission' => 32000, 'rate' => 18],
                            ['store' => 'Home & Garden', 'commission' => 28750, 'rate' => 14],
                        ];
                    @endphp
                    @foreach($topStores as $store)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div>
                            <div class="font-medium text-gray-900 text-sm">{{ $store['store'] }}</div>
                            <div class="text-gray-600 text-xs">{{ $store['rate'] }}% commission rate</div>
                        </div>
                        <div class="text-right">
                            <div class="font-semibold text-success-600">${{ number_format($store['commission']) }}</div>
                            <div class="text-xs text-gray-500">this month</div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="flex gap-2">
                <button class="btn btn-sm btn-primary" onclick="generateCommissionReport()">
                    <i class="fas fa-file-alt"></i>
                    Generate Report
                </button>
                <button class="btn btn-sm btn-secondary" onclick="adjustCommissionRates()">
                    <i class="fas fa-percentage"></i>
                    Adjust Rates
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Revenue Analytics Chart
    initializeRevenueChart();
    
    // Initialize P&L Chart
    initializeProfitLossChart();
});

function initializeRevenueChart() {
    const ctx = document.getElementById('revenueAnalyticsChart').getContext('2d');
    let revenueChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4'],
            datasets: [{
                label: 'Revenue ($)',
                data: [650000, 720000, 680000, 797500],
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
                    title: {
                        display: true,
                        text: 'Amount ($)'
                    },
                    ticks: {
                        callback: function(value) {
                            return '$' + value.toLocaleString();
                        }
                    }
                }
            }
        }
    });

    // Revenue Metric Selector
    document.getElementById('revenue-metric').addEventListener('change', function() {
        const metric = this.value;
        updateRevenueChart(metric, revenueChart);
    });
}

function updateRevenueChart(metric, chart) {
    const datasets = {
        revenue: {
            label: 'Revenue ($)',
            data: [650000, 720000, 680000, 797500],
            borderColor: 'rgb(34, 197, 94)',
            backgroundColor: 'rgba(34, 197, 94, 0.1)'
        },
        profit: {
            label: 'Profit ($)',
            data: [97500, 108000, 102000, 119625],
            borderColor: 'rgb(59, 130, 246)',
            backgroundColor: 'rgba(59, 130, 246, 0.1)'
        },
        commission: {
            label: 'Commission ($)',
            data: [97500, 108000, 102000, 119625],
            borderColor: 'rgb(168, 85, 247)',
            backgroundColor: 'rgba(168, 85, 247, 0.1)'
        },
        expenses: {
            label: 'Expenses ($)',
            data: [552500, 612000, 578000, 677875],
            borderColor: 'rgb(239, 68, 68)',
            backgroundColor: 'rgba(239, 68, 68, 0.1)'
        }
    };

    chart.data.datasets[0] = {
        ...datasets[metric],
        fill: true,
        tension: 0.4
    };
    chart.update();
}

function initializeProfitLossChart() {
    const ctx = document.getElementById('profitLossChart').getContext('2d');
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Revenue', 'Expenses'],
            datasets: [{
                data: [3274625, 540500],
                backgroundColor: [
                    'rgba(34, 197, 94, 0.8)',
                    'rgba(239, 68, 68, 0.8)'
                ],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                }
            }
        }
    });
}

function approveTransaction(type, amount) {
    if (confirm(`Approve ${type} for $${amount.toLocaleString()}?`)) {
        console.log(`Approving ${type} for $${amount}`);
        // API call to approve transaction
    }
}

function rejectTransaction(type, amount) {
    if (confirm(`Reject ${type} for $${amount.toLocaleString()}?`)) {
        console.log(`Rejecting ${type} for $${amount}`);
        // API call to reject transaction
    }
}

function generateCommissionReport() {
    console.log('Generating commission report...');
    // API call to generate and download commission report
}

function adjustCommissionRates() {
    console.log('Opening commission rate adjustment...');
    // Open commission rate management interface
}
</script>
@endpush