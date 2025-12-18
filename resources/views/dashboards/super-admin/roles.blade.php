@extends('dashboards.layouts.app', ['title' => 'Roles & Permissions', 'subtitle' => 'Manage user roles and permissions'])

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-4 gap-6 mb-8">
    <!-- Role Stats Cards -->
    <div class="card">
        <div class="card-body">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium mb-2">Total Roles</p>
                    <h3 class="text-2xl font-semibold text-gray-900">{{ $roles->count() ?? 0 }}</h3>
                </div>
                <div class="w-12 h-12 rounded-lg bg-primary-50 text-primary-600 flex items-center justify-center">
                    <i class="fas fa-user-shield text-lg"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium mb-2">Active Roles</p>
                    <h3 class="text-2xl font-semibold text-gray-900">{{ $roles->where('is_active', true)->count() ?? 0 }}</h3>
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
                    <p class="text-gray-600 text-sm font-medium mb-2">Permissions</p>
                    <h3 class="text-2xl font-semibold text-gray-900">{{ $permissions->flatten()->count() ?? 0 }}</h3>
                </div>
                <div class="w-12 h-12 rounded-lg bg-warning-50 text-warning-600 flex items-center justify-center">
                    <i class="fas fa-key text-lg"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium mb-2">Categories</p>
                    <h3 class="text-2xl font-semibold text-gray-900">{{ $permissions->keys()->count() ?? 0 }}</h3>
                </div>
                <div class="w-12 h-12 rounded-lg bg-info-50 text-info-600 flex items-center justify-center">
                    <i class="fas fa-tags text-lg"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Roles Management -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    <!-- Roles List -->
    <div class="card">
        <div class="card-header">
            <div class="flex items-center justify-between">
                <h3 class="card-title">Roles</h3>
                <button class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i>
                    Add Role
                </button>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="space-y-3 p-4">
                @forelse($roles ?? [] as $role)
                <div class="flex items-center justify-between p-3 border border-gray-200 rounded-lg">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-primary-100 rounded-full flex items-center justify-center text-primary-600 text-sm font-medium">
                            <i class="fas fa-user-shield"></i>
                        </div>
                        <div>
                            <div class="font-medium text-gray-900">{{ $role->name ?? 'Unknown Role' }}</div>
                            <div class="text-gray-500 text-sm">{{ $role->description ?? 'No description' }}</div>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="badge {{ $role->is_active ? 'badge-success' : 'badge-gray' }}">
                            {{ $role->is_active ? 'Active' : 'Inactive' }}
                        </span>
                        <button class="btn btn-ghost btn-sm">
                            <i class="fas fa-edit"></i>
                        </button>
                    </div>
                </div>
                @empty
                <div class="text-center py-8 text-gray-500">
                    No roles found
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Permissions by Category -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Permissions by Category</h3>
        </div>
        <div class="card-body">
            @forelse($permissions ?? [] as $category => $categoryPermissions)
            <div class="mb-6">
                <h4 class="font-medium text-gray-900 mb-3 flex items-center gap-2">
                    <i class="fas fa-folder text-primary-600"></i>
                    {{ ucfirst($category) }}
                </h4>
                <div class="grid grid-cols-1 gap-2">
                    @foreach($categoryPermissions as $permission)
                    <div class="flex items-center justify-between p-2 bg-gray-50 rounded">
                        <span class="text-sm text-gray-700">{{ $permission->name ?? 'Unknown Permission' }}</span>
                        <span class="text-xs text-gray-500">{{ $permission->guard_name ?? 'web' }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
            @empty
            <div class="text-center py-8 text-gray-500">
                No permissions found
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection