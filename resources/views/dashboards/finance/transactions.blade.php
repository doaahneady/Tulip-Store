@extends('dashboards.layouts.app', ['title' => 'Transactions', 'subtitle' => 'Manage financial transactions'])

@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <h2 class="text-2xl font-semibold text-gray-900">Financial Transactions</h2>
        <p class="text-gray-600">View and manage all financial transactions</p>
    </div>
    <button class="btn btn-primary">
        <i class="fas fa-download text-sm mr-2"></i>
        Export Report
    </button>
</div>

<!-- Transaction Stats -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="card">
        <div class="card-body">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium mb-2">Total Transactions</p>
                    <h3 class="text-2xl font-semibold text-gray-900">{{ number_format($metrics['transactions'] ?? 1247) }}</h3>
                </div>
                <div class="w-12 h-12 rounded-lg bg-primary-50 text-primary-600 flex items-center justify-center">
                    <i class="fas fa-exchange-alt text-lg"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium mb-2">Pending Approval</p>
                    <h3 class="text-2xl font-semibold text-warning-600">{{ number_format($metrics['pending_transactions'] ?? 23) }}</h3>
                </div>
                <div class="w-12 h-12 rounded-lg bg-warning-50 text-warning-600 flex items-center justify-center">
                    <i class="fas fa-clock text-lg"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium mb-2">Total Volume</p>
                    <h3 class="text-2xl font-semibold text-success-600">${{ number_format($metrics['total_volume'] ?? 2847500) }}</h3>
                </div>
                <div class="w-12 h-12 rounded-lg bg-success-50 text-success-600 flex items-center justify-center">
                    <i class="fas fa-dollar-sign text-lg"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium mb-2">Avg Transaction</p>
                    <h3 class="text-2xl font-semibold text-gray-900">${{ number_format($metrics['avg_transaction'] ?? 2283) }}</h3>
                </div>
                <div class="w-12 h-12 rounded-lg bg-gray-50 text-gray-600 flex items-center justify-center">
                    <i class="fas fa-chart-line text-lg"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Transaction List -->
<div class="card">
    <div class="card-header">
        <div class="flex items-center justify-between">
            <h3 class="card-title">Recent Transactions</h3>
            <div class="flex items-center gap-3">
                <select class="form-select">
                    <option>All Types</option>
                    <option>Payment</option>
                    <option>Refund</option>
                    <option>Payout</option>
                    <option>Commission</option>
                </select>
                <select class="form-select">
                    <option>All Status</option>
                    <option>Pending</option>
                    <option>Completed</option>
                    <option>Failed</option>
                </select>
            </div>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>Transaction ID</th>
                        <th>Type</th>
                        <th>Amount</th>
                        <th>From/To</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $transactions = [
                            ['id' => 'TXN-2024-001', 'type' => 'payment', 'amount' => 1250.00, 'party' => 'Store #156', 'status' => 'completed', 'date' => '2024-12-18 10:30'],
                            ['id' => 'TXN-2024-002', 'type' => 'payout', 'amount' => 5000.00, 'party' => 'Vendor #45', 'status' => 'pending', 'date' => '2024-12-18 09:15'],
                            ['id' => 'TXN-2024-003', 'type' => 'refund', 'amount' => 350.00, 'party' => 'Customer #789', 'status' => 'completed', 'date' => '2024-12-18 08:45'],
                            ['id' => 'TXN-2024-004', 'type' => 'commission', 'amount' => 125.00, 'party' => 'Platform Fee', 'status' => 'completed', 'date' => '2024-12-18 07:20'],
                            ['id' => 'TXN-2024-005', 'type' => 'payment', 'amount' => 2800.00, 'party' => 'Store #89', 'status' => 'failed', 'date' => '2024-12-17 18:30'],
                        ];
                    @endphp
                    @foreach($transactions as $transaction)
                    <tr>
                        <td class="font-mono text-sm">{{ $transaction['id'] }}</td>
                        <td>
                            <span class="badge badge-gray">{{ ucfirst($transaction['type']) }}</span>
                        </td>
                        <td class="font-semibold">${{ number_format($transaction['amount'], 2) }}</td>
                        <td>{{ $transaction['party'] }}</td>
                        <td>
                            <span class="badge 
                                @if($transaction['status'] === 'completed') badge-success
                                @elseif($transaction['status'] === 'pending') badge-warning
                                @else badge-error
                                @endif">
                                {{ ucfirst($transaction['status']) }}
                            </span>
                        </td>
                        <td class="text-gray-600">{{ date('M j, Y H:i', strtotime($transaction['date'])) }}</td>
                        <td>
                            <div class="flex items-center gap-2">
                                <button class="btn btn-sm btn-ghost">
                                    <i class="fas fa-eye text-xs"></i>
                                </button>
                                @if($transaction['status'] === 'pending')
                                <button class="btn btn-sm btn-success">
                                    <i class="fas fa-check text-xs"></i>
                                </button>
                                <button class="btn btn-sm btn-error">
                                    <i class="fas fa-times text-xs"></i>
                                </button>
                                @endif
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