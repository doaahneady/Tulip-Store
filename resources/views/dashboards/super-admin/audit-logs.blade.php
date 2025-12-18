@extends('dashboards.layouts.app', ['title' => 'Audit Logs', 'subtitle' => 'Track all system activities and changes'])

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-4 gap-6 mb-8">
    <!-- Audit Stats Cards -->
    <div class="card">
        <div class="card-body">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium mb-2">Total Logs</p>
                    <h3 class="text-2xl font-semibold text-gray-900">{{ $logs->total() ?? 0 }}</h3>
                </div>
                <div class="w-12 h-12 rounded-lg bg-primary-50 text-primary-600 flex items-center justify-center">
                    <i class="fas fa-history text-lg"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium mb-2">Today's Activities</p>
                    <h3 class="text-2xl font-semibold text-gray-900">{{ $logs->where('created_at', '>=', today())->count() ?? 0 }}</h3>
                </div>
                <div class="w-12 h-12 rounded-lg bg-success-50 text-success-600 flex items-center justify-center">
                    <i class="fas fa-calendar-day text-lg"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium mb-2">Critical Actions</p>
                    <h3 class="text-2xl font-semibold text-gray-900">{{ $logs->whereIn('action', ['delete', 'force_delete'])->count() ?? 0 }}</h3>
                </div>
                <div class="w-12 h-12 rounded-lg bg-error-50 text-error-600 flex items-center justify-center">
                    <i class="fas fa-exclamation-triangle text-lg"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium mb-2">Unique Users</p>
                    <h3 class="text-2xl font-semibold text-gray-900">{{ $logs->pluck('user_id')->unique()->count() ?? 0 }}</h3>
                </div>
                <div class="w-12 h-12 rounded-lg bg-info-50 text-info-600 flex items-center justify-center">
                    <i class="fas fa-users text-lg"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="card mb-6">
    <div class="card-body">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Action</label>
                <select class="form-select">
                    <option value="">All Actions</option>
                    @foreach($actions ?? [] as $action)
                    <option value="{{ $action }}">{{ ucfirst($action) }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Model Type</label>
                <select class="form-select">
                    <option value="">All Models</option>
                    @foreach($modelTypes ?? [] as $modelType)
                    <option value="{{ $modelType }}">{{ $modelType }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Date From</label>
                <input type="date" class="form-input">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Date To</label>
                <input type="date" class="form-input">
            </div>
        </div>
        <div class="flex items-center gap-3 mt-4">
            <button class="btn btn-primary">Apply Filters</button>
            <button class="btn btn-secondary">Export Logs</button>
        </div>
    </div>
</div>

<!-- Audit Logs Table -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Audit Trail</h3>
    </div>
    <div class="card-body p-0">
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>Timestamp</th>
                        <th>User</th>
                        <th>Action</th>
                        <th>Model</th>
                        <th>Changes</th>
                        <th>IP Address</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs ?? [] as $log)
                    <tr>
                        <td>
                            <div class="text-sm text-gray-900">{{ $log->created_at ? $log->created_at->format('M d, Y') : 'Unknown' }}</div>
                            <div class="text-xs text-gray-500">{{ $log->created_at ? $log->created_at->format('H:i:s') : '' }}</div>
                        </td>
                        <td>
                            <div class="flex items-center gap-2">
                                <div class="w-6 h-6 bg-primary-100 rounded-full flex items-center justify-center text-primary-600 text-xs font-medium">
                                    {{ strtoupper(substr($log->user->name ?? 'U', 0, 1)) }}
                                </div>
                                <div>
                                    <div class="text-sm font-medium text-gray-900">{{ $log->user->name ?? 'System' }}</div>
                                    <div class="text-xs text-gray-500">{{ $log->user->email ?? 'system@app.com' }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge 
                                @if($log->action === 'create') badge-success
                                @elseif($log->action === 'update') badge-warning
                                @elseif($log->action === 'delete') badge-error
                                @else badge-gray
                                @endif">
                                {{ ucfirst($log->action ?? 'unknown') }}
                            </span>
                        </td>
                        <td>
                            <div class="text-sm text-gray-900">{{ $log->model_type ?? 'Unknown' }}</div>
                            <div class="text-xs text-gray-500">ID: {{ $log->model_id ?? 'N/A' }}</div>
                        </td>
                        <td>
                            <div class="text-sm text-gray-600 max-w-xs truncate">
                                {{ $log->description ?? 'No description' }}
                            </div>
                        </td>
                        <td class="text-sm text-gray-500">{{ $log->ip_address ?? 'Unknown' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-8 text-gray-500">
                            No audit logs found
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection