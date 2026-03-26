@php
    $dashboardLocale = session('dashboard_locale', 'ar');
    $dashboardDir = $dashboardLocale === 'ar' ? 'rtl' : 'ltr';
    $employee = auth('employee')->user();
    $traderUser = auth('trader')->user();
    $actor = $employee ?: $traderUser;
    $actorName = $employee?->full_name ?? $traderUser?->name ?? $actor?->user_full_name ?? 'مستخدم';
    $actorEmail = $employee?->email ?? $traderUser?->email ?? '';
    $isTraderSession = (bool) ($traderUser && ! $employee);
    $dashboardRoutes = $employee
        ? collect($employee?->available_dashboards ?? [])->pluck('route')->filter()->values()->all()
        : ['dashboard.vendor.index'];
    $routeToDashboardKey = [
        'dashboard.admin.index' => 'admin',
        'dashboard.admin.style-guide' => 'admin',
        'dashboard.admin.mart' => 'mart',
        'dashboard.admin.mart.index' => 'mart',
        'dashboard.cs.index' => 'cs',
        'dashboard.finance.index' => 'finance',
        'dashboard.hr.index' => 'hr',
        'dashboard.it.index' => 'it',
        'dashboard.supervisor.index' => 'supervisor',
        'dashboard.driver.index' => 'driver',
        'dashboard.vendor.index' => 'vendor',
    ];
    $resolvedSidebarPerms = [];
    if ($employee) {
        foreach (array_unique(array_values($routeToDashboardKey)) as $dk) {
            $resolvedSidebarPerms[$dk] = \App\Services\DashboardPermissionService::resolve($employee, $dk);
        }
    }
    $canAccess = function (string $routeName) use ($dashboardRoutes, $employee, $routeToDashboardKey, $resolvedSidebarPerms): bool {
        if (! $employee) {
            return in_array($routeName, $dashboardRoutes, true);
        }
        $dk = $routeToDashboardKey[$routeName] ?? null;
        if ($dk) {
            return (bool) ($resolvedSidebarPerms[$dk]['can_view'] ?? false);
        }
        return in_array($routeName, $dashboardRoutes, true);
    };
@endphp

<!DOCTYPE html>
<html lang="{{ $dashboardLocale }}" dir="{{ $dashboardDir }}">
<head>
       <!-- fav icon -->
        <link rel="icon" type="image/png" href="/images/fav_icon-v1.png">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? ($dashboardLocale === 'ar' ? 'لوحة التحكم' : 'Dashboard') }} - Tulip Store</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800&family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @vite(['resources/css/app.css', 'resources/css/dashboard-next.css'])
    <link rel="stylesheet" href="{{ asset('css/dashboard/tokens.css') }}">
    {{-- Removed stale links that caused 404s: utilities.css and components.css are merged into dashboard-next.css --}}
    @stack('styles')
</head>
<body class="db-next">
    <a href="#mainContent" class="db4-skip">{{ $dashboardLocale === 'ar' ? 'تخطي إلى المحتوى' : 'Skip to content' }}</a>
    <div id="sidebarOverlay" class="db4-overlay" hidden></div>

    <div class="db4-shell">
        <aside id="sidebar" class="db4-sidebar" aria-label="{{ $dashboardLocale === 'ar' ? 'القائمة' : 'Navigation' }}">
            <div class="flex items-start justify-between gap-3">
                <a href="{{ route('dashboard.main') }}" class="db4-brand">
                    <span class="db4-brand-mark" aria-hidden="true"><i class="fas fa-store"></i></span>
                    <div class="min-w-0">
                        <div class="db4-brand-title">Tulip Store</div>
                        <div class="db4-brand-subtitle">{{ $dashboardLocale === 'ar' ? 'لوحة التحكم' : 'Dashboard' }}</div>
                    </div>
                </a>
                <div class="db4-sidebar-actions lg:hidden">
                    <button type="button" class="db4-icon-btn" aria-label="{{ $dashboardLocale === 'ar' ? 'إغلاق القائمة' : 'Close navigation' }}" onclick="toggleSidebar(false)">
                        <i class="fas fa-xmark"></i>
                    </button>
                </div>
            </div>

            <nav class="db4-nav" aria-label="{{ $dashboardLocale === 'ar' ? 'التنقل' : 'Navigation' }}">
                <div class="db4-nav-section">
                    <div class="db4-nav-title">{{ $dashboardLocale === 'ar' ? 'لوحات التحكم' : 'Dashboards' }}</div>

                    @if(! $isTraderSession && $canAccess('dashboard.admin.index'))
                        <a href="{{ route('dashboard.admin.index') }}" class="db4-nav-link {{ request()->routeIs('dashboard.admin.index') ? 'is-active' : '' }}">
                            <span class="db4-nav-icon" aria-hidden="true"><i class="fas fa-chart-pie"></i></span>
                            <span class="db4-nav-meta">
                                <span class="db4-nav-label">{{ $dashboardLocale === 'ar' ? 'لوحة الإدارة' : 'Admin' }}</span>
                                <span class="db4-nav-hint">{{ $dashboardLocale === 'ar' ? 'نظرة عامة وإدارة المنصة' : 'Overview and platform controls' }}</span>
                            </span>
                        </a>
                    @endif

                    @if(! $isTraderSession && ($canAccess('dashboard.admin.mart') || $canAccess('dashboard.admin.mart.index')))
                        <a href="{{ route('dashboard.admin.mart.index') }}" class="db4-nav-link {{ request()->routeIs('dashboard.admin.mart.*') ? 'is-active' : '' }}">
                            <span class="db4-nav-icon" aria-hidden="true"><i class="fas fa-store"></i></span>
                            <span class="db4-nav-meta">
                                <span class="db4-nav-label">{{ $dashboardLocale === 'ar' ? 'توليب مارت' : 'Tulip Mart' }}</span>
                                <span class="db4-nav-hint">{{ $dashboardLocale === 'ar' ? 'إدارة المنتجات والتصنيفات' : 'Products and categories' }}</span>
                            </span>
                        </a>
                    @endif

                    @if(! $isTraderSession && $canAccess('dashboard.admin.index'))
                        <a href="{{ route('dashboard.admin.style-guide') }}" class="db4-nav-link {{ request()->routeIs('dashboard.admin.style-guide') ? 'is-active' : '' }}">
                            <span class="db4-nav-icon" aria-hidden="true"><i class="fas fa-palette"></i></span>
                            <span class="db4-nav-meta">
                                <span class="db4-nav-label">{{ $dashboardLocale === 'ar' ? 'دليل التصميم' : 'Style Guide' }}</span>
                                <span class="db4-nav-hint">{{ $dashboardLocale === 'ar' ? 'مكونات وأنماط موحدة' : 'Tokens and components' }}</span>
                            </span>
                        </a>
                    @endif

                    @if(! $isTraderSession && $canAccess('dashboard.cs.index'))
                        <a href="{{ route('dashboard.cs.index') }}" class="db4-nav-link {{ request()->routeIs('dashboard.cs.*') ? 'is-active' : '' }}">
                            <span class="db4-nav-icon" aria-hidden="true"><i class="fas fa-headset"></i></span>
                            <span class="db4-nav-meta">
                                <span class="db4-nav-label">{{ $dashboardLocale === 'ar' ? 'خدمة العملاء' : 'Support' }}</span>
                                <span class="db4-nav-hint">{{ $dashboardLocale === 'ar' ? 'تذاكر ودعم العملاء' : 'Tickets and customer care' }}</span>
                            </span>
                        </a>
                    @endif

                    @if(! $isTraderSession && $canAccess('dashboard.finance.index'))
                        <a href="{{ route('dashboard.finance.index') }}" class="db4-nav-link {{ request()->routeIs('dashboard.finance.*') ? 'is-active' : '' }}">
                            <span class="db4-nav-icon" aria-hidden="true"><i class="fas fa-coins"></i></span>
                            <span class="db4-nav-meta">
                                <span class="db4-nav-label">{{ $dashboardLocale === 'ar' ? 'المالية' : 'Finance' }}</span>
                                <span class="db4-nav-hint">{{ $dashboardLocale === 'ar' ? 'معاملات وتقارير' : 'Transactions and reporting' }}</span>
                            </span>
                        </a>
                    @endif

                    @if(! $isTraderSession && $canAccess('dashboard.hr.index'))
                        <a href="{{ route('dashboard.hr.index') }}" class="db4-nav-link {{ request()->routeIs('dashboard.hr.*') ? 'is-active' : '' }}">
                            <span class="db4-nav-icon" aria-hidden="true"><i class="fas fa-users-cog"></i></span>
                            <span class="db4-nav-meta">
                                <span class="db4-nav-label">{{ $dashboardLocale === 'ar' ? 'الموارد البشرية' : 'HR' }}</span>
                                <span class="db4-nav-hint">{{ $dashboardLocale === 'ar' ? 'حضور وموارد بشرية' : 'Attendance and people ops' }}</span>
                            </span>
                        </a>
                    @endif

                    @if(! $isTraderSession && $canAccess('dashboard.it.index'))
                        <a href="{{ route('dashboard.it.index') }}" class="db4-nav-link {{ request()->routeIs('dashboard.it.*') ? 'is-active' : '' }}">
                            <span class="db4-nav-icon" aria-hidden="true"><i class="fas fa-server"></i></span>
                            <span class="db4-nav-meta">
                                <span class="db4-nav-label">{{ $dashboardLocale === 'ar' ? 'تقنية المعلومات' : 'IT' }}</span>
                                <span class="db4-nav-hint">{{ $dashboardLocale === 'ar' ? 'المراقبة والتشغيل' : 'Operations and tooling' }}</span>
                            </span>
                        </a>
                    @endif

                    @if(! $isTraderSession && $canAccess('dashboard.supervisor.index'))
                        <a href="{{ route('dashboard.supervisor.index') }}" class="db4-nav-link {{ request()->routeIs('dashboard.supervisor.*') ? 'is-active' : '' }}">
                            <span class="db4-nav-icon" aria-hidden="true"><i class="fas fa-truck"></i></span>
                            <span class="db4-nav-meta">
                                <span class="db4-nav-label">{{ $dashboardLocale === 'ar' ? 'اللوجستيات' : 'Logistics' }}</span>
                                <span class="db4-nav-hint">{{ $dashboardLocale === 'ar' ? 'تتبع وتوزيع الطلبات' : 'Live tracking and dispatch' }}</span>
                            </span>
                        </a>
                    @endif

                    @if(! $isTraderSession && $canAccess('dashboard.driver.index'))
                        <a href="{{ route('dashboard.driver.index') }}" class="db4-nav-link {{ request()->routeIs('dashboard.driver.*') ? 'is-active' : '' }}">
                            <span class="db4-nav-icon" aria-hidden="true"><i class="fas fa-truck-fast"></i></span>
                            <span class="db4-nav-meta">
                                <span class="db4-nav-label">{{ $dashboardLocale === 'ar' ? 'السائق' : 'Driver' }}</span>
                                <span class="db4-nav-hint">{{ $dashboardLocale === 'ar' ? 'طلباتي وحالة التوصيل' : 'My deliveries and status' }}</span>
                            </span>
                        </a>
                    @endif

                    @if($isTraderSession && $canAccess('dashboard.vendor.index'))
                        <a href="{{ route('dashboard.vendor.index') }}" class="db4-nav-link {{ request()->routeIs('dashboard.vendor.*') ? 'is-active' : '' }}">
                            <span class="db4-nav-icon" aria-hidden="true"><i class="fas fa-store"></i></span>
                            <span class="db4-nav-meta">
                                <span class="db4-nav-label">{{ $dashboardLocale === 'ar' ? 'إدارة المتجر' : 'Store' }}</span>
                                <span class="db4-nav-hint">{{ $dashboardLocale === 'ar' ? 'منتجات وطلبات' : 'Products and orders' }}</span>
                            </span>
                        </a>
                    @endif
                </div>
            </nav>

            <div class="db4-user">
                <div class="db4-avatar" aria-hidden="true">{{ mb_substr($actorName ?? 'U', 0, 1) }}</div>
                <div class="min-w-0">
                    <div class="db4-user-name">{{ $actorName }}</div>
                    <div class="db4-user-email">{{ $actorEmail }}</div>
                </div>
                <form action="{{ $isTraderSession ? route('trader.logout') : route('employee.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="db4-icon-btn" aria-label="{{ $dashboardLocale === 'ar' ? 'تسجيل الخروج' : 'Logout' }}">
                        <i class="fas fa-sign-out-alt"></i>
                    </button>
                </form>
            </div>
        </aside>

        <main class="db4-main">
            <header class="db4-topbar">
                <div class="db4-topbar-inner">
                    <div class="min-w-0">
                        <div class="flex items-start gap-3">
                            <div class="min-w-0">
                                <h1 class="db4-title">{{ $title ?? ($dashboardLocale === 'ar' ? 'لوحة التحكم' : 'Dashboard') }}</h1>
                                <div class="db4-subtitle">{{ $subtitle ?? ($dashboardLocale === 'ar' ? 'مرحباً بك في لوحة التحكم' : 'Welcome') }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="db4-actions">
                        <button type="button" class="db4-action-btn" aria-label="{{ $dashboardLocale === 'ar' ? 'إظهار أو إخفاء القائمة' : 'Toggle sidebar' }}" onclick="toggleSidebarVisibility()">
                            <i class="fas fa-bars-staggered" aria-hidden="true"></i>
                            <span class="hidden md:inline">{{ $dashboardLocale === 'ar' ? 'القائمة' : 'Sidebar' }}</span>
                        </button>
                        <button type="button" class="db4-action-btn" aria-label="{{ $dashboardLocale === 'ar' ? 'قائمة المستخدم' : 'User menu' }}" onclick="toggleUserMenu()">
                            <span aria-hidden="true" class="inline-flex items-center justify-center w-8 h-8 rounded-xl" style="background: rgba(13, 70, 76, 0.10); color: var(--db4-primary); font-weight: 900;">
                                {{ mb_substr($actorName ?? 'U', 0, 1) }}
                            </span>
                            <span class="hidden md:inline">{{ $actorName }}</span>
                            <i class="fas fa-chevron-down" aria-hidden="true"></i>
                        </button>
                    </div>
                </div>
            </header>

            <div class="db4-container" id="mainContent" tabindex="-1">
                @if(session('success'))
                    <div class="db4-alert db4-alert--success mb-6">{{ session('success') }}</div>
                @endif

                @if(session('error'))
                    <div class="db4-alert db4-alert--error mb-6">{{ session('error') }}</div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>

    <div id="userMenu" class="db4-menu" hidden>
        <form method="POST" action="{{ $isTraderSession ? route('trader.logout') : route('employee.logout') }}">
            @csrf
            <button type="submit">
                <i class="fas fa-sign-out-alt w-5" aria-hidden="true"></i>
                {{ $dashboardLocale === 'ar' ? 'تسجيل الخروج' : 'Logout' }}
            </button>
        </form>
    </div>

    <script>
        function setSidebar(open) {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            if (!sidebar || !overlay) return;
            if (open) {
                sidebar.classList.add('is-open');
                overlay.hidden = false;
            } else {
                sidebar.classList.remove('is-open');
                overlay.hidden = true;
            }
        }

        function toggleSidebar(open) {
            const sidebar = document.getElementById('sidebar');
            if (!sidebar) return;
            const next = typeof open === 'boolean' ? open : !sidebar.classList.contains('is-open');
            setSidebar(next);
        }

        function isDesktopLayout() {
            return window.matchMedia('(min-width: 1024px)').matches;
        }

        function setSidebarCollapsed(collapsed) {
            document.body.classList.toggle('sidebar-collapsed', collapsed);
            try {
                localStorage.setItem('dashboard.sidebarCollapsed', collapsed ? '1' : '0');
            } catch (e) {}
        }

        function toggleSidebarVisibility() {
            if (isDesktopLayout()) {
                const collapsed = document.body.classList.contains('sidebar-collapsed');
                setSidebarCollapsed(!collapsed);
                setSidebar(false);
                return;
            }
            toggleSidebar();
        }

        function toggleUserMenu(open) {
            const menu = document.getElementById('userMenu');
            if (!menu) return;
            const next = typeof open === 'boolean' ? open : menu.hidden;
            menu.hidden = !next;
        }

        (function () {
            const overlay = document.getElementById('sidebarOverlay');
            overlay?.addEventListener('click', () => setSidebar(false));

            document.addEventListener('keydown', function (e) {
                if (e.key !== 'Escape') return;
                setSidebar(false);
                toggleUserMenu(false);
            });

            document.addEventListener('click', function (event) {
                const menu = document.getElementById('userMenu');
                const userButton = document.querySelector('[onclick="toggleUserMenu()"]');
                if (!menu || !userButton) return;
                if (!menu.contains(event.target) && !userButton.contains(event.target)) {
                    toggleUserMenu(false);
                }
            });

            try {
                const saved = localStorage.getItem('dashboard.sidebarCollapsed') === '1';
                if (saved && isDesktopLayout()) {
                    setSidebarCollapsed(true);
                }
            } catch (e) {}

            const mq = window.matchMedia('(min-width: 1024px)');
            const onChange = function (e) {
                if (e.matches) {
                    setSidebar(false);
                    try {
                        setSidebarCollapsed(localStorage.getItem('dashboard.sidebarCollapsed') === '1');
                    } catch (err) {}
                } else {
                    document.body.classList.remove('sidebar-collapsed');
                }
            };
            if (typeof mq.addEventListener === 'function') {
                mq.addEventListener('change', onChange);
            } else if (typeof mq.addListener === 'function') {
                mq.addListener(onChange);
            }
        })();

        // Global date input validation (ensure 4-digit year)
        document.addEventListener('DOMContentLoaded', function() {
            function enforceDateLimit(input) {
                if (!input.getAttribute('min')) input.setAttribute('min', '1000-01-01');
                if (!input.getAttribute('max')) input.setAttribute('max', '9999-12-31');
                
                input.addEventListener('input', function() {
                    if (this.value && this.value.length > 10) {
                        this.value = this.value.slice(0, 10);
                    }
                });
            }

            // Apply to existing inputs
            document.querySelectorAll('input[type="date"]').forEach(enforceDateLimit);

            // Watch for dynamically added inputs
            const observer = new MutationObserver(function(mutations) {
                mutations.forEach(function(mutation) {
                    mutation.addedNodes.forEach(function(node) {
                        if (node.nodeType === 1) {
                            if (node.tagName === 'INPUT' && node.type === 'date') {
                                enforceDateLimit(node);
                            }
                            node.querySelectorAll('input[type="date"]').forEach(enforceDateLimit);
                        }
                    });
                });
            });
            observer.observe(document.body, { childList: true, subtree: true });
        });
    </script>
    @stack('scripts')
    @php
        $broadcastDriver = config('broadcasting.default');
        $dashKey = null;
        if (request()->routeIs('dashboard.admin.*')) {
            $dashKey = 'admin';
        } elseif (request()->routeIs('dashboard.it.*')) {
            $dashKey = 'it';
        } elseif (request()->routeIs('dashboard.hr.*')) {
            $dashKey = 'hr';
        } elseif (request()->routeIs('dashboard.finance.*')) {
            $dashKey = 'finance';
        } elseif (request()->routeIs('dashboard.cs.*')) {
            $dashKey = 'cs';
        } elseif (request()->routeIs('dashboard.supervisor.*')) {
            $dashKey = 'supervisor';
        } elseif (request()->routeIs('dashboard.vendor.*')) {
            $dashKey = 'vendor';
        } elseif (request()->routeIs('dashboard.driver.*')) {
            $dashKey = 'driver';
        }
    @endphp
    @php
        $resolvedPerm = request()->attributes->get('resolved_dashboard_permissions', null);
    @endphp
    @if($dashKey)
        <script>
            window.DASHBOARD_KEY = @json($dashKey);
            window.DASHBOARD_PERMISSIONS = @json($resolvedPerm);
            window.canDashboardEdit = function () {
                return !!(window.DASHBOARD_PERMISSIONS && window.DASHBOARD_PERMISSIONS.can_edit === true);
            };
            window.canDashboardAction = function (action) {
                const actions = (window.DASHBOARD_PERMISSIONS && Array.isArray(window.DASHBOARD_PERMISSIONS.actions))
                    ? window.DASHBOARD_PERMISSIONS.actions : [];
                return actions.includes(String(action || ''));
            };
            window.canViewSensitive = function () {
                return !!(window.DASHBOARD_PERMISSIONS && window.DASHBOARD_PERMISSIONS.can_view_sensitive === true);
            };
        </script>
    @endif
    @if($broadcastDriver === 'pusher' && $dashKey)
        <script src="https://cdn.jsdelivr.net/npm/pusher-js@8.4.0/dist/web/pusher.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/laravel-echo@1.16.0/dist/echo.iife.js"></script>
        <script>
            (function () {
                const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
                window.Pusher = window.Pusher || Pusher;
                window.Echo = new Echo({
                    broadcaster: 'pusher',
                    key: @json(env('PUSHER_APP_KEY', '')),
                    cluster: @json(env('PUSHER_APP_CLUSTER', '')),
                    wsHost: @json(env('PUSHER_HOST', request()->getHost())),
                    wsPort: @json((int) env('PUSHER_PORT', 6001)),
                    wssPort: @json((int) env('PUSHER_PORT', 6001)),
                    forceTLS: @json(env('PUSHER_SCHEME', 'http') === 'https'),
                    encrypted: @json(env('PUSHER_SCHEME', 'http') === 'https'),
                    disableStats: true,
                    enabledTransports: ['ws', 'wss'],
                    authEndpoint: @json(url('/broadcasting/auth')),
                    auth: {
                        headers: {
                            'X-CSRF-TOKEN': csrf
                        }
                    }
                });

                const dashKey = @json($dashKey);
                window.Echo.private('dashboard.' + dashKey).listen('.dashboard.updated', function (e) {
                    window.dispatchEvent(new CustomEvent('dashboard.updated', { detail: e }));
                });
            })();
        </script>
    @endif
</body>
</html>
