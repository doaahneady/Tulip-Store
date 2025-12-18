{{-- Role-Based Dashboard Sidebar --}}

@php
    $user = auth()->user();
    $currentRoute = request()->route()?->getName() ?? '';
    
    // Helper function to check if a route is active
    $isActive = function($routePattern) use ($currentRoute) {
        return str_starts_with($currentRoute, $routePattern);
    };
    
    // Helper function to safely get route URL
    $getRoute = function($routeName, $fallback = '#') {
        try {
            return route($routeName);
        } catch (\Exception $e) {
            return $fallback;
        }
    };
    
    // Define dashboard access based on user roles
    $dashboards = [];
    
    // Super Admin Dashboard
    if ($user->is_admin ?? false) {
        $dashboards[] = [
            'name' => 'Super Admin',
            'icon' => 'fa-crown',
            'route' => 'dashboard.admin.index',
            'pattern' => 'dashboard.admin',
            'color' => 'text-purple-600',
            'items' => [
                ['route' => 'dashboard.admin.index', 'pattern' => 'dashboard.admin.index', 'icon' => 'fa-tachometer-alt', 'label' => 'Overview'],
                ['route' => 'dashboard.admin.users', 'pattern' => 'dashboard.admin.users', 'icon' => 'fa-users', 'label' => 'User Management'],
                ['route' => 'dashboard.admin.roles', 'pattern' => 'dashboard.admin.roles', 'icon' => 'fa-user-shield', 'label' => 'Roles & Permissions'],
                ['route' => 'dashboard.admin.analytics', 'pattern' => 'dashboard.admin.analytics', 'icon' => 'fa-chart-line', 'label' => 'Global Analytics'],
                ['route' => 'dashboard.admin.audit-logs', 'pattern' => 'dashboard.admin.audit-logs', 'icon' => 'fa-history', 'label' => 'Audit Logs'],
                ['route' => 'dashboard.admin.settings', 'pattern' => 'dashboard.admin.settings', 'icon' => 'fa-building', 'label' => 'Settings'],
            ]
        ];
    }
    
    // IT/DevOps Dashboard
    if ($user->is_it ?? false || $user->is_it_super ?? false || $user->is_admin ?? false) {
        $dashboards[] = [
            'name' => 'IT/DevOps',
            'icon' => 'fa-server',
            'route' => 'dashboard.it.index',
            'pattern' => 'dashboard.it',
            'color' => 'text-blue-600',
            'items' => [
                ['route' => 'dashboard.it.index', 'pattern' => 'dashboard.it.index', 'icon' => 'fa-desktop', 'label' => 'System Status'],
                ['route' => 'dashboard.it.index', 'pattern' => 'dashboard.it.index', 'icon' => 'fa-cogs', 'label' => 'Services'],
                ['route' => 'dashboard.it.logs', 'pattern' => 'dashboard.it.logs', 'icon' => 'fa-file-alt', 'label' => 'System Logs'],
                ['route' => 'dashboard.it.database', 'pattern' => 'dashboard.it.database', 'icon' => 'fa-database', 'label' => 'Database'],
                ['route' => 'dashboard.it.deployments', 'pattern' => 'dashboard.it.deployments', 'icon' => 'fa-rocket', 'label' => 'Deployments'],
                ['route' => 'dashboard.it.integrations', 'pattern' => 'dashboard.it.integrations', 'icon' => 'fa-plug', 'label' => 'Integrations'],
            ]
        ];
    }
    
    // HR Dashboard
    if ($user->is_hr ?? false || $user->is_admin ?? false) {
        $dashboards[] = [
            'name' => 'Human Resources',
            'icon' => 'fa-users-cog',
            'route' => 'dashboard.hr.index',
            'pattern' => 'dashboard.hr',
            'color' => 'text-green-600',
            'items' => [
                ['route' => 'dashboard.hr.index', 'pattern' => 'dashboard.hr.index', 'icon' => 'fa-chart-pie', 'label' => 'Overview'],
                ['route' => 'dashboard.hr.employees', 'pattern' => 'dashboard.hr.employees', 'icon' => 'fa-id-badge', 'label' => 'Employees'],
                ['route' => 'dashboard.hr.shifts', 'pattern' => 'dashboard.hr.shifts', 'icon' => 'fa-calendar-alt', 'label' => 'Shift Scheduling'],
                ['route' => 'dashboard.hr.attendance', 'pattern' => 'dashboard.hr.attendance', 'icon' => 'fa-clock', 'label' => 'Attendance'],
                ['route' => 'dashboard.hr.payroll', 'pattern' => 'dashboard.hr.payroll', 'icon' => 'fa-money-check-alt', 'label' => 'Payroll'],
                ['route' => 'dashboard.hr.recruiting', 'pattern' => 'dashboard.hr.recruiting', 'icon' => 'fa-user-plus', 'label' => 'Recruiting'],
                ['route' => 'dashboard.hr.performance-reviews', 'pattern' => 'dashboard.hr.performance-reviews', 'icon' => 'fa-star', 'label' => 'Performance'],
            ]
        ];
    }
    
    // Driver Supervisor Dashboard
    if ($user->is_driver_supervisor ?? false || $user->is_admin ?? false) {
        $dashboards[] = [
            'name' => 'Driver Supervisor',
            'icon' => 'fa-route',
            'route' => 'dashboard.supervisor.index',
            'pattern' => 'dashboard.supervisor',
            'color' => 'text-orange-600',
            'items' => [
                ['route' => 'dashboard.supervisor.live-tracking', 'pattern' => 'dashboard.supervisor.live-tracking', 'icon' => 'fa-map', 'label' => 'Live Tracking'],
                ['route' => 'dashboard.supervisor.drivers', 'pattern' => 'dashboard.supervisor.drivers', 'icon' => 'fa-motorcycle', 'label' => 'Drivers'],
                ['route' => 'dashboard.supervisor.order-assignment', 'pattern' => 'dashboard.supervisor.order-assignment', 'icon' => 'fa-clipboard-list', 'label' => 'Assignments'],
                ['route' => 'dashboard.supervisor.vehicle-maintenance', 'pattern' => 'dashboard.supervisor.vehicle-maintenance', 'icon' => 'fa-truck', 'label' => 'Vehicles'],
                ['route' => 'dashboard.supervisor.vehicle-maintenance', 'pattern' => 'dashboard.supervisor.vehicle-maintenance', 'icon' => 'fa-wrench', 'label' => 'Maintenance'],
                ['route' => 'dashboard.supervisor.route-optimization', 'pattern' => 'dashboard.supervisor.route-optimization', 'icon' => 'fa-route', 'label' => 'Route Optimization'],
                ['route' => 'dashboard.supervisor.delivery-proof', 'pattern' => 'dashboard.supervisor.delivery-proof', 'icon' => 'fa-chart-bar', 'label' => 'Performance'],
            ]
        ];
    }
    
    // Finance Dashboard
    if ($user->is_finance ?? false || $user->is_accountant ?? false || $user->is_admin ?? false) {
        $dashboards[] = [
            'name' => 'Finance',
            'icon' => 'fa-chart-line',
            'route' => 'dashboard.finance.index',
            'pattern' => 'dashboard.finance',
            'color' => 'text-emerald-600',
            'items' => [
                ['route' => 'dashboard.finance.index', 'pattern' => 'dashboard.finance.index', 'icon' => 'fa-chart-pie', 'label' => 'Overview'],
                ['route' => 'dashboard.finance.transactions', 'pattern' => 'dashboard.finance.transactions', 'icon' => 'fa-exchange-alt', 'label' => 'Transactions'],
                ['route' => 'dashboard.finance.payouts', 'pattern' => 'dashboard.finance.payouts', 'icon' => 'fa-hand-holding-usd', 'label' => 'Payouts'],
                ['route' => 'dashboard.finance.revenue', 'pattern' => 'dashboard.finance.revenue', 'icon' => 'fa-dollar-sign', 'label' => 'Revenue'],
                ['route' => 'dashboard.finance.expenses', 'pattern' => 'dashboard.finance.expenses', 'icon' => 'fa-receipt', 'label' => 'Expenses'],
                ['route' => 'dashboard.finance.reports', 'pattern' => 'dashboard.finance.reports', 'icon' => 'fa-file-invoice-dollar', 'label' => 'Reports'],
                ['route' => 'dashboard.finance.tax', 'pattern' => 'dashboard.finance.tax', 'icon' => 'fa-calculator', 'label' => 'Tax Management'],
            ]
        ];
    }
    
    // Product Owner (Vendor) Dashboard
    if ($user->is_trader ?? false || $user->is_admin ?? false) {
        $dashboards[] = [
            'name' => 'My Store',
            'icon' => 'fa-store',
            'route' => 'dashboard.vendor.index',
            'pattern' => 'dashboard.vendor',
            'color' => 'text-indigo-600',
            'items' => [
                ['route' => 'dashboard.vendor.analytics', 'pattern' => 'dashboard.vendor.analytics', 'icon' => 'fa-chart-pie', 'label' => 'Analytics'],
                ['route' => 'dashboard.vendor.products', 'pattern' => 'dashboard.vendor.products', 'icon' => 'fa-box', 'label' => 'Products'],
                ['route' => 'dashboard.vendor.products', 'pattern' => 'dashboard.vendor.products', 'icon' => 'fa-warehouse', 'label' => 'Inventory'],
                ['route' => 'dashboard.vendor.orders', 'pattern' => 'dashboard.vendor.orders', 'icon' => 'fa-shopping-bag', 'label' => 'Orders'],
                ['route' => 'dashboard.vendor.orders', 'pattern' => 'dashboard.vendor.orders', 'icon' => 'fa-users', 'label' => 'Customers'],
                ['route' => 'dashboard.vendor.earnings', 'pattern' => 'dashboard.vendor.earnings', 'icon' => 'fa-wallet', 'label' => 'Earnings'],
                ['route' => 'dashboard.vendor.store-profile', 'pattern' => 'dashboard.vendor.store-profile', 'icon' => 'fa-cog', 'label' => 'Store Settings'],
            ]
        ];
    }
@endphp

<!-- Desktop sidebar -->
<div class="hidden lg:fixed lg:inset-y-0 lg:z-50 lg:flex lg:w-72 lg:flex-col">
    <div class="flex grow flex-col gap-y-5 overflow-y-auto bg-white px-6 pb-4 shadow-xl border-r border-gray-200">
        <!-- Logo -->
        <div class="flex h-16 shrink-0 items-center">
            <a href="{{ route('home') }}" class="flex items-center gap-3 text-decoration-none">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-blue-500 to-blue-600 text-white shadow-lg">
                    <i class="fas fa-store text-lg"></i>
                </div>
                <span class="text-xl font-bold text-gray-900">Tulip Store</span>
            </a>
        </div>

    <!-- Navigation -->
    <nav class="sidebar-nav" style="flex: 1; padding: var(--space-4); overflow-y: auto;">
        @if(count($dashboards) > 1)
            <!-- Multiple Dashboards - Show All -->
            <div style="margin-bottom: var(--space-6);">
                <div style="font-size: 0.6875rem; font-weight: 600; color: var(--gray-500); text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: var(--space-3); padding: 0 var(--space-3);">
                    Dashboards
                </div>
                @foreach($dashboards as $dashboard)
                    <div style="margin-bottom: var(--space-4);">
                        <a href="{{ $getRoute($dashboard['route']) }}" 
                           class="nav-item {{ $isActive($dashboard['pattern']) ? 'active' : '' }}"
                           style="display: flex; align-items: center; gap: var(--space-3); padding: var(--space-3); margin-bottom: var(--space-1); border-radius: var(--radius-lg); color: var(--gray-700); text-decoration: none; font-weight: 500; font-size: 0.875rem; transition: all 0.2s ease; {{ $isActive($dashboard['pattern']) ? 'background-color: var(--primary-50); color: var(--primary-700); font-weight: 600;' : '' }}">
                            <i class="fas {{ $dashboard['icon'] }} {{ $dashboard['color'] }}" style="width: 18px; height: 18px; flex-shrink: 0;"></i>
                            <span>{{ $dashboard['name'] }}</span>
                        </a>
                        
                        @if($isActive($dashboard['pattern']) && isset($dashboard['items']))
                            <div style="margin-left: var(--space-6); margin-top: var(--space-2);">
                                @foreach($dashboard['items'] as $item)
                                    <a href="{{ $getRoute($item['route']) }}" 
                                       class="nav-subitem {{ $isActive($item['pattern']) ? 'active' : '' }}"
                                       style="display: flex; align-items: center; gap: var(--space-2); padding: var(--space-2) var(--space-3); margin-bottom: var(--space-1); border-radius: var(--radius-md); color: var(--gray-600); text-decoration: none; font-size: 0.8125rem; transition: all 0.2s ease; {{ $isActive($item['pattern']) ? 'background-color: var(--primary-100); color: var(--primary-700); font-weight: 500;' : '' }}">
                                        <i class="fas {{ $item['icon'] }}" style="width: 14px; height: 14px; flex-shrink: 0;"></i>
                                        <span>{{ $item['label'] }}</span>
                                    </a>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @elseif(count($dashboards) === 1)
            <!-- Single Dashboard - Show Menu Items -->
            @php $dashboard = $dashboards[0]; @endphp
            <div style="margin-bottom: var(--space-6);">
                <div style="font-size: 0.6875rem; font-weight: 600; color: var(--gray-500); text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: var(--space-3); padding: 0 var(--space-3);">
                    {{ $dashboard['name'] }}
                </div>
                @foreach($dashboard['items'] as $item)
                    <a href="{{ $getRoute($item['route']) }}" 
                       class="nav-item {{ $isActive($item['pattern']) ? 'active' : '' }}"
                       style="display: flex; align-items: center; gap: var(--space-3); padding: var(--space-3); margin-bottom: var(--space-1); border-radius: var(--radius-lg); color: var(--gray-700); text-decoration: none; font-weight: 500; font-size: 0.875rem; transition: all 0.2s ease; {{ $isActive($item['pattern']) ? 'background-color: var(--primary-50); color: var(--primary-700); font-weight: 600;' : '' }}">
                        <i class="fas {{ $item['icon'] }}" style="width: 18px; height: 18px; flex-shrink: 0;"></i>
                        <span>{{ $item['label'] }}</span>
                    </a>
                @endforeach
            </div>
        @endif

        <!-- Quick Actions -->
        <div style="margin-bottom: var(--space-6);">
            <div style="font-size: 0.6875rem; font-weight: 600; color: var(--gray-500); text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: var(--space-3); padding: 0 var(--space-3);">
                Quick Actions
            </div>
            <a href="{{ route('home') }}" 
               class="nav-item"
               style="display: flex; align-items: center; gap: var(--space-3); padding: var(--space-3); margin-bottom: var(--space-1); border-radius: var(--radius-lg); color: var(--gray-700); text-decoration: none; font-weight: 500; font-size: 0.875rem; transition: all 0.2s ease;">
                <i class="fas fa-home" style="width: 18px; height: 18px; flex-shrink: 0;"></i>
                <span>Back to Store</span>
            </a>
            <a href="{{ route('notifications') }}" 
               class="nav-item"
               style="display: flex; align-items: center; gap: var(--space-3); padding: var(--space-3); margin-bottom: var(--space-1); border-radius: var(--radius-lg); color: var(--gray-700); text-decoration: none; font-weight: 500; font-size: 0.875rem; transition: all 0.2s ease;">
                <i class="fas fa-bell" style="width: 18px; height: 18px; flex-shrink: 0;"></i>
                <span>Notifications</span>
                @if(($unreadNotifications ?? 0) > 0)
                    <span class="badge badge-error" style="margin-left: auto;">{{ $unreadNotifications }}</span>
                @endif
            </a>
        </div>
    </nav>

    <!-- Sidebar Footer -->
    <div style="padding: var(--space-4); border-top: 1px solid var(--gray-200);">
        <div style="display: flex; align-items: center; gap: var(--space-3);">
            <div style="width: 40px; height: 40px; border-radius: var(--radius-xl); background: var(--primary-100); display: flex; align-items: center; justify-content: center; color: var(--primary-600); font-weight: 600; flex-shrink: 0;">
                {{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}
            </div>
            <div style="flex: 1; min-width: 0;">
                <div style="font-weight: 500; font-size: 0.875rem; color: var(--gray-900); white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                    {{ $user->name ?? 'User' }}
                </div>
                <div style="font-size: 0.75rem; color: var(--gray-500); white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                    {{ $user->email ?? '' }}
                </div>
            </div>
        </div>
    </div>
</aside>

<style>
.nav-item:hover:not(.active) {
    background-color: var(--gray-100);
}

.nav-subitem:hover:not(.active) {
    background-color: var(--gray-50);
}

.nav-item:focus,
.nav-subitem:focus {
    outline: 2px solid var(--primary-500);
    outline-offset: -2px;
}

.nav-item:focus:not(:focus-visible),
.nav-subitem:focus:not(:focus-visible) {
    outline: none;
}

.nav-item:focus-visible,
.nav-subitem:focus-visible {
    outline: 2px solid var(--primary-500);
    outline-offset: -2px;
}
</style>