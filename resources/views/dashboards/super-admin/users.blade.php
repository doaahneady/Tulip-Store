@extends('dashboards.layouts.app', ['title' => 'User Management', 'subtitle' => 'Manage platform users and permissions'])

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-4 gap-6 mb-8">
    <!-- User Stats Cards -->
    <div class="card">
        <div class="card-body">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium mb-2">Total Users</p>
                    <h3 class="text-2xl font-semibold text-gray-900">{{ $users->total() ?? 0 }}</h3>
                </div>
                <div class="w-12 h-12 rounded-lg bg-primary-50 text-primary-600 flex items-center justify-center">
                    <i class="fas fa-users text-lg"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium mb-2">Active Users</p>
                    <h3 class="text-2xl font-semibold text-gray-900">{{ $users->where('status', 'active')->count() ?? 0 }}</h3>
                </div>
                <div class="w-12 h-12 rounded-lg bg-success-50 text-success-600 flex items-center justify-center">
                    <i class="fas fa-user-check text-lg"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium mb-2">Admins</p>
                    <h3 class="text-2xl font-semibold text-gray-900">{{ $users->where('is_admin', true)->count() ?? 0 }}</h3>
                </div>
                <div class="w-12 h-12 rounded-lg bg-warning-50 text-warning-600 flex items-center justify-center">
                    <i class="fas fa-user-shield text-lg"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium mb-2">New This Month</p>
                    <h3 class="text-2xl font-semibold text-gray-900">{{ $users->where('created_at', '>=', now()->startOfMonth())->count() ?? 0 }}</h3>
                </div>
                <div class="w-12 h-12 rounded-lg bg-info-50 text-info-600 flex items-center justify-center">
                    <i class="fas fa-user-plus text-lg"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Users Table -->
<div class="card">
    <div class="card-header">
        <div class="flex items-center justify-between">
            <h3 class="card-title">All Users</h3>
            <div class="flex items-center gap-3">
                <button class="btn btn-secondary btn-sm">
                    <i class="fas fa-download"></i>
                    Export
                </button>
                <button class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i>
                    Add User
                </button>
            </div>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Joined</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users ?? [] as $user)
                    <tr>
                        <td>
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-primary-100 rounded-full flex items-center justify-center text-primary-600 text-sm font-medium">
                                    {{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}
                                </div>
                                <div>
                                    <div class="font-medium text-gray-900">{{ $user->name ?? 'Unknown' }}</div>
                                    <div class="text-gray-500 text-sm">ID: {{ $user->id }}</div>
                                </div>
                            </div>
                        </td>
                        <td>{{ $user->email ?? 'No email' }}</td>
                        <td>
                            <span class="badge {{ $user->is_admin ? 'badge-warning' : 'badge-gray' }}">
                                {{ $user->is_admin ? 'Admin' : 'User' }}
                            </span>
                        </td>
                        <td>
                            <span class="badge {{ $user->status === 'active' ? 'badge-success' : 'badge-error' }}">
                                {{ ucfirst($user->status ?? 'inactive') }}
                            </span>
                        </td>
                        <td>{{ $user->created_at ? $user->created_at->format('M d, Y') : 'Unknown' }}</td>
                        <td>
                            <div class="flex items-center gap-2">
                                <button class="btn btn-ghost btn-sm">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-ghost btn-sm text-error-600">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-8 text-gray-500">
                            No users found
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection