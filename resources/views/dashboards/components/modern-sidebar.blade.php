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
                ['route' => 'dashboard.admin.users', 'pattern' => 'dashboard.admin.users', 'icon' => 'fa-users', 'label' => 'Users'],
                ['route' => 'dashboard.admin.roles', 'pattern' => 'dashboard.admin.roles', 'icon' => 'fa-user-shield', 'label' => 'Roles'],
                ['route' => 'dashboard.admin.analytics', 'pattern' => 'dashboard.admin.analytics', 'icon' => 'fa-chart-line', 'label' => 'Analytics'],
                ['route' => 'dashboard.admin.audit-logs', 'pattern' => 'dashboard.admin.audit-logs', 'icon' => 'fa-history', 'label' => 'Audit Logs'],
                ['route' => 'dashboard.admin.settings', 'pattern' => 'dashboard.admin.settings', 'icon' => 'fa-cog', 'label' => 'Settings'],
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
                ['route' => 'dashboard.it.system-health', 'pattern' => 'dashboard.it.system-health', 'icon' => 'fa-heartbeat', 'label' => 'Health Monitor'],
                ['route' => 'dashboard.it.logs', 'pattern' => 'dashboard.it.logs', 'icon' => 'fa-file-alt', 'label' => 'System Logs'],
                ['route' => 'dashboard.it.database', 'pattern' => 'dashboard.it.database', 'icon' => 'fa-database', 'label' => 'Database'],
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
                ['route' => 'dashboard.hr.shifts', 'pattern' => 'dashboard.hr.shifts', 'icon' => 'fa-calendar-alt', 'label' => 'Shifts'],
                ['route' => 'dashboard.hr.payroll', 'pattern' => 'dashboard.hr.payroll', 'icon' => 'fa-money-check-alt', 'label' => 'Payroll'],
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
                ['route' => 'dashboard.supervisor.index', 'pattern' => 'dashboard.supervisor.index', 'icon' => 'fa-tachometer-alt', 'label' => 'Overview'],
                ['route' => 'dashboard.supervisor.live-tracking', 'pattern' => 'dashboard.supervisor.live-tracking', 'icon' => 'fa-map', 'label' => 'Live Tracking'],
                ['route' => 'dashboard.supervisor.drivers', 'pattern' => 'dashboard.supervisor.drivers', 'icon' => 'fa-motorcycle', 'label' => 'Drivers'],
                ['route' => 'dashboard.supervisor.order-assignment', 'pattern' => 'dashboard.supervisor.order-assignment', 'icon' => 'fa-clipboard-list', 'label' => 'Assignments'],
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
                ['route' => 'dashboard.finance.reports', 'pattern' => 'dashboard.finance.reports', 'icon' => 'fa-file-invoice-dollar', 'label' => 'Reports'],
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
                ['route' => 'dashboard.vendor.index', 'pattern' => 'dashboard.vendor.index', 'icon' => 'fa-chart-pie', 'label' => 'Overview'],
                ['route' => 'dashboard.vendor.products', 'pattern' => 'dashboard.vendor.products', 'icon' => 'fa-box', 'label' => 'Products'],
                ['route' => 'dashboard.vendor.orders', 'pattern' => 'dashboard.vendor.orders', 'icon' => 'fa-shopping-bag', 'label' => 'Orders'],
                ['route' => 'dashboard.vendor.earnings', 'pattern' => 'dashboard.vendor.earnings', 'icon' => 'fa-wallet', 'label' => 'Earnings'],
            ]
        ];
    }
@endphp

<!-- Desktop sidebar -->
<div class="hidden lg:fixed lg:inset-y-0 lg:z-50 lg:flex lg:w-72 lg:flex-col">
    <div class="flex grow flex-col gap-y-5 overflow-y-auto bg-white px-6 pb-4 shadow-xl">
        <!-- Logo -->
        <div class="flex h-16 shrink-0 items-center">
            <a href="{{ route('home') }}" class="flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-primary-500 to-primary-600 text-white shadow-lg">
                    <i class="fas fa-store text-lg"></i>
                </div>
                <span class="text-xl font-bold text-gray-900">Tulip Store</span>
            </a>
        </div>

        <!-- Navigation -->
        <nav class="flex flex-1 flex-col">
            <ul role="list" class="flex flex-1 flex-col gap-y-7">
                <li>
                    <div class="text-xs font-semibold leading-6 text-gray-400 uppercase tracking-wider">Dashboards</div>
                    <ul role="list" class="-mx-2 mt-2 space-y-1">
                        @foreach($dashboards as $dashboard)
                            <li>
                                <a href="{{ $getRoute($dashboard['route']) }}" 
                                   class="sidebar-item group flex gap-x-3 rounded-md p-3 text-sm leading-6 font-semibold {{ $isActive($dashboard['pattern']) ? 'active text-white' : 'text-gray-700 hover:text-primary-600' }}">
                                    <i class="fas {{ $dashboard['icon'] }} h-5 w-5 shrink-0 {{ $isActive($dashboard['pattern']) ? 'text-white' : $dashboard['color'] }}"></i>
                                    {{ $dashboard['name'] }}
                                </a>
                                
                                @if($isActive($dashboard['pattern']) && isset($dashboard['items']))
                                    <ul class="mt-2 space-y-1 ml-6">
                                        @foreach($dashboard['items'] as $item)
                                            <a href="{{ $getRoute($item['route']) }}" 
                                               class="group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-medium {{ $isActive($item['pattern']) ? 'bg-primary-50 text-primary-700' : 'text-gray-600 hover:text-primary-600 hover:bg-gray-50' }}">
                                                <i class="fas {{ $item['icon'] }} h-4 w-4 shrink-0"></i>
                                                {{ $item['label'] }}
                                            </a>
                                        @endforeach
                                    </ul>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </li>

                <!-- Quick Actions -->
                <li>
                    <div class="text-xs font-semibold leading-6 text-gray-400 uppercase tracking-wider">Quick Actions</div>
                    <ul role="list" class="-mx-2 mt-2 space-y-1">
                        <li>
                            <a href="{{ route('home') }}" class="sidebar-item group flex gap-x-3 rounded-md p-3 text-sm leading-6 font-semibold text-gray-700 hover:text-primary-600">
                                <i class="fas fa-home h-5 w-5 shrink-0 text-gray-400 group-hover:text-primary-600"></i>
                                Back to Store
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('notifications') }}" class="sidebar-item group flex gap-x-3 rounded-md p-3 text-sm leading-6 font-semibold text-gray-700 hover:text-primary-600">
                                <i class="fas fa-bell h-5 w-5 shrink-0 text-gray-400 group-hover:text-primary-600"></i>
                                Notifications
                                @if(($unreadNotifications ?? 0) > 0)
                                    <span class="ml-auto inline-flex items-center rounded-full bg-red-100 px-2 py-1 text-xs font-medium text-red-700">
                                        {{ $unreadNotifications }}
                                    </span>
                                @endif
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- User Profile -->
                <li class="mt-auto">
                    <div class="flex items-center gap-x-4 px-3 py-3 text-sm font-semibold leading-6 text-gray-900 bg-gray-50 rounded-lg">
                        <div class="h-8 w-8 rounded-full bg-primary-100 flex items-center justify-center text-primary-600 font-semibold text-sm">
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

<!-- Mobile sidebar -->
<div class="lg:hidden">
    <div class="fixed inset-0 z-50 -translate-x-full transition-transform duration-300 ease-in-out" id="mobile-sidebar">
        <div class="relative flex w-full max-w-xs flex-col bg-white pb-4 shadow-xl">
            <div class="absolute right-0 top-0 -mr-12 pt-2">
                <button type="button" class="ml-1 flex h-10 w-10 items-center justify-center rounded-full focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white" onclick="toggleMobileSidebar()">
                    <span class="sr-only">Close sidebar</span>
                    <i class="fas fa-times h-6 w-6 text-white"></i>
                </button>
            </div>

            <!-- Mobile navigation content (same as desktop) -->
            <div class="flex grow flex-col gap-y-5 overflow-y-auto px-6 pb-4">
                <!-- Logo -->
                <div class="flex h-16 shrink-0 items-center">
                    <a href="{{ route('home') }}" class="flex items-center gap-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-primary-500 to-primary-600 text-white shadow-lg">
                            <i class="fas fa-store text-lg"></i>
                        </div>
                        <span class="text-xl font-bold text-gray-900">Tulip Store</span>
                    </a>
                </div>

                <!-- Mobile Navigation (same structure as desktop) -->
                <nav class="flex flex-1 flex-col">
                    <ul role="list" class="flex flex-1 flex-col gap-y-7">
                        <li>
                            <div class="text-xs font-semibold leading-6 text-gray-400 uppercase tracking-wider">Dashboards</div>
                            <ul role="list" class="-mx-2 mt-2 space-y-1">
                                @foreach($dashboards as $dashboard)
                                    <li>
                                        <a href="{{ $getRoute($dashboard['route']) }}" 
                                           class="sidebar-item group flex gap-x-3 rounded-md p-3 text-sm leading-6 font-semibold {{ $isActive($dashboard['pattern']) ? 'active text-white' : 'text-gray-700 hover:text-primary-600' }}"
                                           onclick="toggleMobileSidebar()">
                                            <i class="fas {{ $dashboard['icon'] }} h-5 w-5 shrink-0 {{ $isActive($dashboard['pattern']) ? 'text-white' : $dashboard['color'] }}"></i>
                                            {{ $dashboard['name'] }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</div>