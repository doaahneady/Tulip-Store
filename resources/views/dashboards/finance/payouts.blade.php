@extends('dashboards.layouts.app', ['title' => 'Payout Management', 'subtitle' => 'Manage vendor payouts and approvals'])

@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <h2 class="text-2xl font-semibold text-gray-900">Payout Management</h2>
        <p class="text-gray-600">Review and process vendor payout requests</p>
    </div>
    <button class="btn btn-primary">
        <i class="fas fa-download text-sm mr-2"></i>
        Export Report
    </button>
</div>

<!-- Payout Stats -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="card">
        <div class="card-body">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium mb-2">Pending Requests</p>
                    <h3 class="text-2xl font-semibold text-warning-600">{{ $metrics['payout_requests'] ?? 23 }}</h3>
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
                    <p class="text-gray-600 text-sm font-medium mb-2">Pending Amount</p>
                    <h3 class="text-2xl font-semibold text-warning-600">${{ number_format($metrics['pending_payouts'] ?? 125000) }}</h3>
                </div>
                <div class="w-12 h-12 rounded-lg bg-warning-50 text-warning-600 flex items-center justify-center">
                    <i class="fas fa-dollar-sign text-lg"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium mb-2">Approved Amount</p>
                    <h3 class="text-2xl font-semibold text-success-600">${{ number_format($metrics['approved_payouts'] ?? 85000) }}</h3>
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
                    <p class="text-gray-600 text-sm font-medium mb-2">Monthly Payouts</p>
                    <h3 class="text-2xl font-semibold text-gray-900">${{ number_format($metrics['monthly_payouts'] ?? 485000) }}</h3>
                </div>
                <div class="w-12 h-12 rounded-lg bg-gray-50 text-gray-600 flex items-center justify-center">
                    <i class="fas fa-chart-line text-lg"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Payout Requests -->
<div class="card">
    <div class="card-header">
        <div class="flex items-center justify-between">
            <h3 class="card-title">Payout Requests</h3>
            <div class="flex items-center gap-3">
                <select class="form-select">
                    <option>All Status</option>
                    <option>Pending</option>
                    <option>Approved</option>
                    <option>Processing</option>
                    <option>Completed</option>
                    <option>Rejected</option>
                </select>
                <select class="form-select">
                    <option>All Stores</option>
                    <option>Electronics Store</option>
                    <option>Fashion Boutique</option>
                    <option>Food Market</option>
                </select>
            </div>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>Payout ID</th>
                        <th>Store</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Requested</th>
                        <th>Payment Method</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $payouts = [
                            ['id' => 'PO-2024-001', 'store' => 'Electronics Store', 'amount' => 15000.00, 'status' => 'pending', 'requested' => '2024-12-18 09:30', 'method' => 'Bank Transfer'],
                            ['id' => 'PO-2024-002', 'store' => 'Fashion Boutique', 'amount' => 8500.00, 'status' => 'approved', 'requested' => '2024-12-18 08:15', 'method' => 'Bank Transfer'],
                            ['id' => 'PO-2024-003', 'store' => 'Food Market', 'amount' => 12000.00, 'status' => 'processing', 'requested' => '2024-12-17 16:45', 'method' => 'Bank Transfer'],
                            ['id' => 'PO-2024-004', 'store' => 'Home & Garden', 'amount' => 6750.00, 'status' => 'completed', 'requested' => '2024-12-17 14:20', 'method' => 'Bank Transfer'],
                            ['id' => 'PO-2024-005', 'store' => 'Sports Shop', 'amount' => 9200.00, 'status' => 'pending', 'requested' => '2024-12-17 11:10', 'method' => 'Bank Transfer'],
                        ];
                    @endphp
                    @foreach($payouts as $payout)
                    <tr>
                        <td class="font-mono text-sm">{{ $payout['id'] }}</td>
                        <td class="font-medium">{{ $payout['store'] }}</td>
                        <td class="font-semibold">${{ number_format($payout['amount'], 2) }}</td>
                        <td>
                            <span class="badge 
                                @if($payout['status'] === 'completed') badge-success
                                @elseif($payout['status'] === 'approved') badge-primary
                                @elseif($payout['status'] === 'processing') badge-warning
                                @elseif($payout['status'] === 'pending') badge-gray
                                @else badge-error
                                @endif">
                                {{ ucfirst($payout['status']) }}
                            </span>
                        </td>
                        <td class="text-gray-600">{{ date('M j, Y H:i', strtotime($payout['requested'])) }}</td>
                        <td class="text-gray-600">{{ $payout['method'] }}</td>
                        <td>
                            <div class="flex items-center gap-2">
                                <button class="btn btn-sm btn-ghost">
                                    <i class="fas fa-eye text-xs"></i>
                                </button>
                                @if($payout['status'] === 'pending')
                                <button class="btn btn-sm btn-success">
                                    <i class="fas fa-check text-xs"></i>
                                    Approve
                                </button>
                                <button class="btn btn-sm btn-error">
                                    <i class="fas fa-times text-xs"></i>
                                    Reject
                                </button>
                                @elseif($payout['status'] === 'approved')
                                <button class="btn btn-sm btn-primary">
                                    <i class="fas fa-paper-plane text-xs"></i>
                                    Process
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