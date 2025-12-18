@extends('dashboards.layouts.app', ['title' => 'System Settings', 'subtitle' => 'Configure platform settings and preferences'])

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- General Settings -->
    <div class="lg:col-span-2">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">General Settings</h3>
            </div>
            <div class="card-body">
                <form class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Site Name</label>
                            <input type="text" class="form-input" value="{{ $settings['site_name'] ?? 'Tulip Store' }}">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Site URL</label>
                            <input type="url" class="form-input" value="{{ $settings['site_url'] ?? url('/') }}">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Site Description</label>
                        <textarea class="form-input" rows="3">{{ $settings['site_description'] ?? 'Your trusted online marketplace' }}</textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Admin Email</label>
                            <input type="email" class="form-input" value="{{ $settings['admin_email'] ?? 'admin@tulipstore.com' }}">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Support Email</label>
                            <input type="email" class="form-input" value="{{ $settings['support_email'] ?? 'support@tulipstore.com' }}">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Default Currency</label>
                            <select class="form-select">
                                <option value="USD">USD - US Dollar</option>
                                <option value="EUR">EUR - Euro</option>
                                <option value="SYP" selected>SYP - Syrian Pound</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Default Language</label>
                            <select class="form-select">
                                <option value="en">English</option>
                                <option value="ar" selected>Arabic</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                        <div>
                            <h4 class="font-medium text-gray-900">Maintenance Mode</h4>
                            <p class="text-sm text-gray-600">Put the site in maintenance mode</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary-600"></div>
                        </label>
                    </div>

                    <div class="flex items-center gap-3">
                        <button type="submit" class="btn btn-primary">Save Settings</button>
                        <button type="button" class="btn btn-secondary">Reset to Defaults</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- System Information -->
    <div>
        <div class="card mb-6">
            <div class="card-header">
                <h3 class="card-title">System Information</h3>
            </div>
            <div class="card-body">
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Laravel Version</span>
                        <span class="text-sm font-medium text-gray-900">{{ app()->version() }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">PHP Version</span>
                        <span class="text-sm font-medium text-gray-900">{{ PHP_VERSION }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Environment</span>
                        <span class="badge {{ app()->environment('production') ? 'badge-success' : 'badge-warning' }}">
                            {{ ucfirst(app()->environment()) }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Debug Mode</span>
                        <span class="badge {{ config('app.debug') ? 'badge-error' : 'badge-success' }}">
                            {{ config('app.debug') ? 'Enabled' : 'Disabled' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Quick Actions</h3>
            </div>
            <div class="card-body">
                <div class="space-y-3">
                    <button class="btn btn-secondary w-full">
                        <i class="fas fa-sync-alt"></i>
                        Clear Cache
                    </button>
                    <button class="btn btn-secondary w-full">
                        <i class="fas fa-database"></i>
                        Optimize Database
                    </button>
                    <button class="btn btn-secondary w-full">
                        <i class="fas fa-download"></i>
                        Backup System
                    </button>
                    <button class="btn btn-warning w-full">
                        <i class="fas fa-exclamation-triangle"></i>
                        Run Diagnostics
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection