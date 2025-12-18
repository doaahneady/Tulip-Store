<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-gray-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Dashboard' }} - {{ config('app.name', 'Tulip Store') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                    colors: {
                        primary: {
                            50: '#eff6ff',
                            100: '#dbeafe',
                            200: '#bfdbfe',
                            300: '#93c5fd',
                            400: '#60a5fa',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                            800: '#1e40af',
                            900: '#1e3a8a',
                        },
                        success: {
                            50: '#f0fdf4',
                            100: '#dcfce7',
                            200: '#bbf7d0',
                            300: '#86efac',
                            400: '#4ade80',
                            500: '#22c55e',
                            600: '#16a34a',
                            700: '#15803d',
                            800: '#166534',
                            900: '#14532d',
                        },
                        warning: {
                            50: '#fffbeb',
                            100: '#fef3c7',
                            200: '#fde68a',
                            300: '#fcd34d',
                            400: '#fbbf24',
                            500: '#f59e0b',
                            600: '#d97706',
                            700: '#b45309',
                            800: '#92400e',
                            900: '#78350f',
                        },
                        error: {
                            50: '#fef2f2',
                            100: '#fee2e2',
                            200: '#fecaca',
                            300: '#fca5a5',
                            400: '#f87171',
                            500: '#ef4444',
                            600: '#dc2626',
                            700: '#b91c1c',
                            800: '#991b1b',
                            900: '#7f1d1d',
                        },
                        emerald: {
                            50: '#ecfdf5',
                            100: '#d1fae5',
                            200: '#a7f3d0',
                            300: '#6ee7b7',
                            400: '#34d399',
                            500: '#10b981',
                            600: '#059669',
                            700: '#047857',
                            800: '#065f46',
                            900: '#064e3b',
                        }
                    }
                }
            }
        }
    </script>
    
    <style>
        /* Custom Dashboard Styles */
        .metric-card {
            @apply bg-white rounded-xl p-6 border border-gray-200 shadow-sm hover:shadow-lg hover:border-blue-200 hover:-translate-y-1 transition-all duration-300;
        }
        
        .metric-label {
            @apply text-sm font-medium text-gray-600 uppercase tracking-wide;
        }
        
        .metric-value {
            @apply text-3xl font-bold mt-2;
        }
        
        .metric-change {
            @apply flex items-center gap-1 mt-2 text-sm font-medium;
        }
        
        .metric-icon {
            @apply w-12 h-12 rounded-xl flex items-center justify-center text-xl;
        }
        
        .card {
            @apply bg-white rounded-xl border border-gray-200 shadow-sm;
        }
        
        .card-header {
            @apply px-6 py-4 border-b border-gray-200;
        }
        
        .card-body {
            @apply p-6;
        }
        
        .card-title {
            @apply text-lg font-semibold text-gray-900;
        }
        
        .page-header {
            @apply mb-8;
        }
        
        .page-title {
            @apply text-3xl font-bold text-gray-900;
        }
        
        .page-subtitle {
            @apply text-lg text-gray-600 mt-2;
        }
        
        .btn {
            @apply inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg shadow-sm transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2;
        }
        
        .btn-primary {
            @apply text-white bg-blue-600 hover:bg-blue-700 focus:ring-blue-500;
        }
        
        .btn-secondary {
            @apply text-gray-700 bg-white border-gray-300 hover:bg-gray-50 focus:ring-blue-500;
        }
        
        .btn-success {
            @apply text-white bg-green-600 hover:bg-green-700 focus:ring-green-500;
        }
        
        .btn-warning {
            @apply text-white bg-yellow-600 hover:bg-yellow-700 focus:ring-yellow-500;
        }
        
        .btn-error {
            @apply text-white bg-red-600 hover:bg-red-700 focus:ring-red-500;
        }
        
        .btn-ghost {
            @apply text-gray-500 bg-transparent hover:text-gray-700 hover:bg-gray-100 border-0 shadow-none;
        }
        
        .btn-sm {
            @apply px-3 py-1.5 text-xs;
        }
        
        .btn-action {
            @apply inline-flex items-center gap-2 px-4 py-2 text-sm font-medium rounded-lg transition-colors;
        }
        
        .btn-primary-action {
            @apply text-blue-600 bg-blue-50 hover:bg-blue-100 hover:text-blue-700;
        }
        
        .form-input {
            @apply block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm;
        }
        
        .form-select {
            @apply block rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm;
        }
        
        .table {
            @apply min-w-full divide-y divide-gray-200;
        }
        
        .table thead th {
            @apply px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider bg-gray-50;
        }
        
        .table tbody td {
            @apply px-6 py-4 whitespace-nowrap text-sm text-gray-900 border-b border-gray-200;
        }
        
        .table tbody tr:hover {
            @apply bg-gray-50;
        }
        
        .table-container {
            @apply overflow-x-auto;
        }
        
        .table-enhanced {
            @apply overflow-hidden;
        }
        
        .badge {
            @apply inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium;
        }
        
        .badge-primary {
            @apply bg-blue-100 text-blue-800;
        }
        
        .badge-success {
            @apply bg-green-100 text-green-800;
        }
        
        .badge-warning {
            @apply bg-yellow-100 text-yellow-800;
        }
        
        .badge-error {
            @apply bg-red-100 text-red-800;
        }
        
        .badge-gray {
            @apply bg-gray-100 text-gray-800;
        }
        
        /* Color utilities */
        .text-primary-600 { color: #2563eb; }
        .text-success-600 { color: #16a34a; }
        .text-warning-600 { color: #d97706; }
        .text-error-600 { color: #dc2626; }
        .text-emerald-600 { color: #059669; }
        
        .bg-primary-50 { background-color: #eff6ff; }
        .bg-primary-100 { background-color: #dbeafe; }
        .bg-success-50 { background-color: #f0fdf4; }
        .bg-success-100 { background-color: #dcfce7; }
        .bg-warning-50 { background-color: #fffbeb; }
        .bg-warning-100 { background-color: #fef3c7; }
        .bg-error-50 { background-color: #fef2f2; }
        .bg-error-100 { background-color: #fee2e2; }
        .bg-emerald-50 { background-color: #ecfdf5; }
        .bg-emerald-100 { background-color: #d1fae5; }
        
        /* Custom variables for dynamic colors */
        :root {
            --primary-600: #2563eb;
            --success-600: #16a34a;
            --warning-600: #d97706;
            --error-600: #dc2626;
            --emerald-600: #059669;
        }
    </style>
    
    @stack('styles')
</head>
<body class="h-full font-sans antialiased bg-gray-50">
    <div class="min-h-full">
        <!-- Sidebar -->
        <div class="hidden lg:fixed lg:inset-y-0 lg:z-50 lg:flex lg:w-72 lg:flex-col">
            <div class="flex grow flex-col gap-y-5 overflow-y-auto bg-white px-6 pb-4 shadow-xl border-r border-gray-200">
                <!-- Logo -->
                <div class="flex h-16 shrink-0 items-center">
                    <a href="{{ route('home') }}" class="flex items-center gap-3 no-underline">
                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-blue-500 to-blue-600 text-white shadow-lg">
                            <i class="fas fa-store text-lg"></i>
                        </div>
                        <span class="text-xl font-bold text-gray-900">Tulip Store</span>
                    </a>
                </div>

                <!-- Navigation -->
                <nav class="flex flex-1 flex-col">
                    <ul role="list" class="flex flex-1 flex-col gap-y-7">
                        @php
                            $user = auth()->user();
                            $currentRoute = request()->route()?->getName() ?? '';
                            
                            $isActive = function($routePattern) use ($currentRoute) {
                                return str_starts_with($currentRoute, $routePattern);
                            };
                            
                            $getRoute = function($routeName, $fallback = '#') {
                                try {
                                    return route($routeName);
                                } catch (\Exception $e) {
                                    return $fallback;
                                }
                            };
                            
                            // Determine current dashboard and show its sections
                            $currentDashboard = null;
                            $dashboardSections = [];
                            
                            if (str_starts_with($currentRoute, 'dashboard.admin')) {
                                $currentDashboard = 'Super Admin';
                                $dashboardSections = [
                                    ['name' => 'Overview', 'icon' => 'fa-tachometer-alt', 'route' => 'dashboard.admin.index'],
                                    ['name' => 'User Management', 'icon' => 'fa-users', 'route' => 'dashboard.admin.users'],
                                    ['name' => 'Roles & Permissions', 'icon' => 'fa-shield-alt', 'route' => 'dashboard.admin.roles'],
                                    ['name' => 'Platform Analytics', 'icon' => 'fa-chart-bar', 'route' => 'dashboard.admin.analytics'],
                                    ['name' => 'Audit Logs', 'icon' => 'fa-clipboard-list', 'route' => 'dashboard.admin.audit-logs'],
                                    ['name' => 'System Settings', 'icon' => 'fa-cogs', 'route' => 'dashboard.admin.settings'],
                                ];
                            } elseif (str_starts_with($currentRoute, 'dashboard.it')) {
                                $currentDashboard = 'IT/DevOps';
                                $dashboardSections = [
                                    ['name' => 'Overview', 'icon' => 'fa-tachometer-alt', 'route' => 'dashboard.it.index'],
                                    ['name' => 'System Health', 'icon' => 'fa-heartbeat', 'route' => 'dashboard.it.system-health'],
                                    ['name' => 'System Logs', 'icon' => 'fa-file-alt', 'route' => 'dashboard.it.logs'],
                                    ['name' => 'API Errors', 'icon' => 'fa-exclamation-triangle', 'route' => 'dashboard.it.api-errors'],
                                    ['name' => 'Database', 'icon' => 'fa-database', 'route' => 'dashboard.it.database'],
                                    ['name' => 'Backups', 'icon' => 'fa-save', 'route' => 'dashboard.it.backups'],
                                    ['name' => 'Deployments', 'icon' => 'fa-rocket', 'route' => 'dashboard.it.deployments'],
                                    ['name' => 'System Alerts', 'icon' => 'fa-bell', 'route' => 'dashboard.it.alerts'],
                                ];
                            } elseif (str_starts_with($currentRoute, 'dashboard.hr')) {
                                $currentDashboard = 'Human Resources';
                                $dashboardSections = [
                                    ['name' => 'Overview', 'icon' => 'fa-tachometer-alt', 'route' => 'dashboard.hr.index'],
                                    ['name' => 'Employee Management', 'icon' => 'fa-users', 'route' => 'dashboard.hr.employees'],
                                    ['name' => 'Shift Management', 'icon' => 'fa-calendar-alt', 'route' => 'dashboard.hr.shifts'],
                                    ['name' => 'Driver Shifts', 'icon' => 'fa-truck', 'route' => 'dashboard.hr.driver-shifts'],
                                    ['name' => 'Payroll', 'icon' => 'fa-money-check-alt', 'route' => 'dashboard.hr.payroll'],
                                    ['name' => 'Performance Reviews', 'icon' => 'fa-star', 'route' => 'dashboard.hr.performance-reviews'],
                                    ['name' => 'Recruiting', 'icon' => 'fa-user-plus', 'route' => 'dashboard.hr.recruiting'],
                                    ['name' => 'Announcements', 'icon' => 'fa-bullhorn', 'route' => 'dashboard.hr.announcements'],
                                ];
                            } elseif (str_starts_with($currentRoute, 'dashboard.supervisor')) {
                                $currentDashboard = 'Driver Supervisor';
                                $dashboardSections = [
                                    ['name' => 'Overview', 'icon' => 'fa-tachometer-alt', 'route' => 'dashboard.supervisor.index'],
                                    ['name' => 'Live Tracking', 'icon' => 'fa-map-marked-alt', 'route' => 'dashboard.supervisor.live-tracking'],
                                    ['name' => 'Driver Management', 'icon' => 'fa-users', 'route' => 'dashboard.supervisor.drivers'],
                                    ['name' => 'Order Assignment', 'icon' => 'fa-clipboard-list', 'route' => 'dashboard.supervisor.order-assignment'],
                                    ['name' => 'Route Optimization', 'icon' => 'fa-route', 'route' => 'dashboard.supervisor.route-optimization'],
                                    ['name' => 'Vehicle Maintenance', 'icon' => 'fa-wrench', 'route' => 'dashboard.supervisor.vehicle-maintenance'],
                                    ['name' => 'Delivery Proof', 'icon' => 'fa-check-circle', 'route' => 'dashboard.supervisor.delivery-proof'],
                                ];
                            } elseif (str_starts_with($currentRoute, 'dashboard.finance')) {
                                $currentDashboard = 'Finance';
                                $dashboardSections = [
                                    ['name' => 'Overview', 'icon' => 'fa-tachometer-alt', 'route' => 'dashboard.finance.index'],
                                    ['name' => 'Transactions', 'icon' => 'fa-exchange-alt', 'route' => 'dashboard.finance.transactions'],
                                    ['name' => 'Payouts', 'icon' => 'fa-hand-holding-usd', 'route' => 'dashboard.finance.payouts'],
                                    ['name' => 'Revenue Analytics', 'icon' => 'fa-chart-line', 'route' => 'dashboard.finance.revenue'],
                                    ['name' => 'Expense Management', 'icon' => 'fa-receipt', 'route' => 'dashboard.finance.expenses'],
                                    ['name' => 'Financial Reports', 'icon' => 'fa-file-invoice-dollar', 'route' => 'dashboard.finance.reports'],
                                    ['name' => 'Tax Management', 'icon' => 'fa-calculator', 'route' => 'dashboard.finance.tax'],
                                    ['name' => 'Payroll Processing', 'icon' => 'fa-money-check-alt', 'route' => 'dashboard.finance.payroll'],
                                ];
                            } elseif (str_starts_with($currentRoute, 'dashboard.vendor')) {
                                $currentDashboard = 'My Store';
                                $dashboardSections = [
                                    ['name' => 'Overview', 'icon' => 'fa-tachometer-alt', 'route' => 'dashboard.vendor.index'],
                                    ['name' => 'Product Management', 'icon' => 'fa-box', 'route' => 'dashboard.vendor.products'],
                                    ['name' => 'Order Management', 'icon' => 'fa-shopping-cart', 'route' => 'dashboard.vendor.orders'],
                                    ['name' => 'Sales Analytics', 'icon' => 'fa-chart-bar', 'route' => 'dashboard.vendor.analytics'],
                                    ['name' => 'Earnings', 'icon' => 'fa-dollar-sign', 'route' => 'dashboard.vendor.earnings'],
                                    ['name' => 'Store Profile', 'icon' => 'fa-store', 'route' => 'dashboard.vendor.store-profile'],
                                ];
                            }
                        @endphp
                        
                        @if($currentDashboard && count($dashboardSections) > 0)
                        <li>
                            <div class="text-xs font-semibold leading-6 text-gray-400 uppercase tracking-wider">{{ $currentDashboard }}</div>
                            <ul role="list" class="-mx-2 mt-2 space-y-1">
                                @foreach($dashboardSections as $section)
                                    <li>
                                        <a href="{{ $getRoute($section['route']) }}" 
                                           class="group flex gap-x-3 rounded-md p-3 text-sm leading-6 font-semibold transition-colors {{ $isActive($section['route']) ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:text-blue-600 hover:bg-gray-50' }}">
                                            <i class="fas {{ $section['icon'] }} h-5 w-5 shrink-0 {{ $isActive($section['route']) ? 'text-blue-600' : 'text-gray-400' }}"></i>
                                            {{ $section['name'] }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </li>
                        @endif

                        <!-- Dashboard Switcher -->
                        @php
                            $availableDashboards = [];
                            
                            if ($user->is_admin ?? false) {
                                $availableDashboards[] = [
                                    'name' => 'Super Admin',
                                    'icon' => 'fa-crown',
                                    'route' => 'dashboard.admin.index',
                                    'pattern' => 'dashboard.admin',
                                    'color' => 'text-purple-600',
                                ];
                            }
                            
                            if ($user->is_it ?? false || $user->is_it_super ?? false || $user->is_admin ?? false) {
                                $availableDashboards[] = [
                                    'name' => 'IT/DevOps',
                                    'icon' => 'fa-server',
                                    'route' => 'dashboard.it.index',
                                    'pattern' => 'dashboard.it',
                                    'color' => 'text-blue-600',
                                ];
                            }
                            
                            if ($user->is_hr ?? false || $user->is_admin ?? false) {
                                $availableDashboards[] = [
                                    'name' => 'Human Resources',
                                    'icon' => 'fa-users-cog',
                                    'route' => 'dashboard.hr.index',
                                    'pattern' => 'dashboard.hr',
                                    'color' => 'text-green-600',
                                ];
                            }
                            
                            if ($user->is_driver_supervisor ?? false || $user->is_admin ?? false) {
                                $availableDashboards[] = [
                                    'name' => 'Driver Supervisor',
                                    'icon' => 'fa-route',
                                    'route' => 'dashboard.supervisor.index',
                                    'pattern' => 'dashboard.supervisor',
                                    'color' => 'text-orange-600',
                                ];
                            }
                            
                            if ($user->is_finance ?? false || $user->is_accountant ?? false || $user->is_admin ?? false) {
                                $availableDashboards[] = [
                                    'name' => 'Finance',
                                    'icon' => 'fa-chart-line',
                                    'route' => 'dashboard.finance.index',
                                    'pattern' => 'dashboard.finance',
                                    'color' => 'text-emerald-600',
                                ];
                            }
                            
                            if ($user->is_trader ?? false || $user->is_admin ?? false) {
                                $availableDashboards[] = [
                                    'name' => 'My Store',
                                    'icon' => 'fa-store',
                                    'route' => 'dashboard.vendor.index',
                                    'pattern' => 'dashboard.vendor',
                                    'color' => 'text-indigo-600',
                                ];
                            }
                        @endphp
                        
                        @if(count($availableDashboards) > 1)
                        <li>
                            <div class="text-xs font-semibold leading-6 text-gray-400 uppercase tracking-wider">Switch Dashboard</div>
                            <ul role="list" class="-mx-2 mt-2 space-y-1">
                                @foreach($availableDashboards as $dashboard)
                                    @if(!$isActive($dashboard['pattern']))
                                        <li>
                                            <a href="{{ $getRoute($dashboard['route']) }}" 
                                               class="group flex gap-x-3 rounded-md p-3 text-sm leading-6 font-semibold text-gray-700 hover:text-blue-600 hover:bg-gray-50 transition-colors">
                                                <i class="fas {{ $dashboard['icon'] }} h-5 w-5 shrink-0 {{ $dashboard['color'] }} group-hover:text-blue-600"></i>
                                                {{ $dashboard['name'] }}
                                            </a>
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                        </li>
                        @endif

                        <!-- Quick Actions -->
                        <li>
                            <div class="text-xs font-semibold leading-6 text-gray-400 uppercase tracking-wider">Quick Actions</div>
                            <ul role="list" class="-mx-2 mt-2 space-y-1">
                                <li>
                                    <a href="{{ route('home') }}" class="group flex gap-x-3 rounded-md p-3 text-sm leading-6 font-semibold text-gray-700 hover:text-blue-600 hover:bg-gray-50 transition-colors">
                                        <i class="fas fa-home h-5 w-5 shrink-0 text-gray-400 group-hover:text-blue-600"></i>
                                        Back to Store
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <!-- User Profile -->
                        <li class="mt-auto">
                            <div class="flex items-center gap-x-4 px-3 py-3 text-sm font-semibold leading-6 text-gray-900 bg-gray-50 rounded-lg">
                                <div class="h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-semibold text-sm">
                                    {{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="truncate text-sm font-medium text-gray-900">{{ $user->name ?? 'User' }}</div>
                                    <div class="truncate text-xs text-gray-500">{{ $user->email ?? '' }}</div>
                                </div>
                            </div>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>

        <!-- Main content -->
        <div class="lg:pl-72">
            <!-- Top bar -->
            <div class="sticky top-0 z-40 flex h-16 shrink-0 items-center gap-x-4 border-b border-gray-200 bg-white px-4 shadow-sm sm:gap-x-6 sm:px-6 lg:px-8">
                <!-- Mobile menu button -->
                <button type="button" class="-m-2.5 p-2.5 text-gray-700 lg:hidden" onclick="toggleMobileSidebar()">
                    <span class="sr-only">Open sidebar</span>
                    <i class="fas fa-bars h-6 w-6"></i>
                </button>

                <!-- Separator -->
                <div class="h-6 w-px bg-gray-200 lg:hidden"></div>

                <div class="flex flex-1 gap-x-4 self-stretch lg:gap-x-6">
                    <!-- Search -->
                    <form class="relative flex flex-1 max-w-md" action="#" method="GET">
                        <label for="search-field" class="sr-only">Search</label>
                        <i class="fas fa-search pointer-events-none absolute inset-y-0 left-0 h-full w-5 text-gray-400 pl-3 flex items-center"></i>
                        <input id="search-field" class="block h-full w-full border-0 py-0 pl-10 pr-0 text-gray-900 placeholder:text-gray-400 focus:ring-0 sm:text-sm bg-transparent" placeholder="Search..." type="search" name="search">
                    </form>

                    <div class="flex items-center gap-x-4 lg:gap-x-6">
                        <!-- Real-time status -->
                        <div class="flex items-center gap-x-2 rounded-full bg-green-50 px-3 py-1 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20">
                            <div class="h-2 w-2 rounded-full bg-green-500 animate-pulse"></div>
                            Live
                        </div>

                        <!-- Profile dropdown -->
                        <div class="relative">
                            <button type="button" class="-m-1.5 flex items-center p-1.5 hover:bg-gray-50 rounded-lg transition-colors">
                                <span class="sr-only">Open user menu</span>
                                <div class="h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-semibold text-sm">
                                    {{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}
                                </div>
                                <span class="hidden lg:flex lg:items-center">
                                    <span class="ml-4 text-sm font-semibold leading-6 text-gray-900">{{ $user->name ?? 'User' }}</span>
                                    <i class="fas fa-chevron-down ml-2 h-3 w-3 text-gray-400"></i>
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Page content -->
            <main class="py-8">
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <!-- Flash Messages -->
                    @if(session('success'))
                        <div class="mb-6 rounded-lg bg-green-50 p-4 border border-green-200">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-check-circle text-green-400"></i>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                                </div>
                                <div class="ml-auto pl-3">
                                    <button type="button" class="inline-flex rounded-md bg-green-50 p-1.5 text-green-500 hover:bg-green-100 transition-colors" onclick="this.parentElement.parentElement.parentElement.remove()">
                                        <i class="fas fa-times text-sm"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="mb-6 rounded-lg bg-red-50 p-4 border border-red-200">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-exclamation-circle text-red-400"></i>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                                </div>
                                <div class="ml-auto pl-3">
                                    <button type="button" class="inline-flex rounded-md bg-red-50 p-1.5 text-red-500 hover:bg-red-100 transition-colors" onclick="this.parentElement.parentElement.parentElement.remove()">
                                        <i class="fas fa-times text-sm"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Page Header -->
                    <div class="mb-8">
                        <div class="md:flex md:items-center md:justify-between">
                            <div class="min-w-0 flex-1">
                                <h1 class="text-3xl font-bold leading-tight tracking-tight text-gray-900 sm:text-4xl">
                                    {{ $title ?? 'Dashboard' }}
                                </h1>
                                @if(isset($subtitle))
                                    <p class="mt-2 text-lg text-gray-600">{{ $subtitle }}</p>
                                @endif
                            </div>
                            <div class="mt-4 flex md:ml-4 md:mt-0">
                                @stack('header-actions')
                            </div>
                        </div>
                    </div>

                    <!-- Main Content -->
                    <div class="space-y-8">
                        {{ $slot ?? '' }}
                        @yield('content')
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script>
        // Mobile sidebar toggle
        function toggleMobileSidebar() {
            // Add mobile sidebar functionality here
            console.log('Toggle mobile sidebar');
        }

        // Auto-dismiss flash messages
        document.addEventListener('DOMContentLoaded', function() {
            const flashMessages = document.querySelectorAll('.bg-green-50, .bg-red-50');
            flashMessages.forEach(function(message) {
                setTimeout(function() {
                    message.style.transition = 'opacity 300ms ease-out';
                    message.style.opacity = '0';
                    setTimeout(function() {
                        message.remove();
                    }, 300);
                }, 5000);
            });
        });
    </script>

    @stack('scripts')
</body>
</html>