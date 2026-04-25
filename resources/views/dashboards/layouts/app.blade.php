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
        <link rel="icon" type="image/png" sizes="48x48" href="/images/fav_icon-v1.png">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="اكتشف Tulip Store، منصة تسوق إلكتروني متكاملة تتيح لك الشراء أو إنشاء متجرك الخاص والربح بسهولة، مع توصيل سريع وطرق دفع آمنة وتجربة استخدام مريحة.">

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

                        <!-- Mart orders notifications (shows only on /dashboard/admin/mart pages) -->
                        <div id="martBellNavWrap" style="position:relative; display:none;">
                            <button type="button" id="martBellNavBtn" class="db4-action-btn" aria-label="إشعارات الطلبات" style="position:relative;">
                                <i class="fas fa-bell" aria-hidden="true"></i>
                                <span id="martBellNavBadge" style="display:none; position:absolute; top:-6px; right:-6px; min-width:20px; height:20px; padding:0 6px; border-radius:999px; background:#dc2626; color:#fff; font-size:11px; line-height:20px; text-align:center; font-weight:800;"></span>
                            </button>

                            <div id="martBellNavDropdown" style="display:none; position:fixed; top:0; left:0; width:820px; max-width:calc(100vw - 24px); border-radius:16px; border:1px solid rgba(255,255,255,0.12); background:rgba(15,23,42,0.96); color:rgba(255,255,255,0.92); box-shadow:0 22px 70px rgba(0,0,0,0.55); z-index:2147483647; pointer-events:auto;">
                                <div style="padding:12px 14px; border-bottom:1px solid rgba(255,255,255,0.10); display:flex; align-items:center; justify-content:space-between;">
                                    <div style="font-weight:800; font-size:13px;">إشعارات الطلبات</div>
                                    <button type="button" id="martBellNavReadAll" style="font-size:12px; color:rgba(255,255,255,0.85); text-decoration:underline; background:transparent; border:0; cursor:pointer;">قراءة الكل</button>
                                </div>
                                <div id="martBellNavList" style="max-height:460px; overflow:auto; padding:8px;">
                                    <div style="padding:10px 12px; font-size:13px; color:rgba(255,255,255,0.70);">جارٍ التحميل...</div>
                                </div>
                            </div>
                        </div>
                        <button type="button" class="db4-action-btn" aria-label="{{ $dashboardLocale === 'ar' ? 'قائمة المستخدم' : 'User menu' }}" onclick="toggleUserMenu()">
                            <span aria-hidden="true" class="inline-flex items-center justify-center w-8 h-8 rounded-xl" style="background: rgba(13, 70, 76, 0.10); color: var(--db4-primary); font-weight: 900;">
                                {{ mb_substr($actorName ?? 'U', 0, 1) }}
                            </span>
                            <span class="hidden md:inline">{{ $actorName }}</span>
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
            if (overlay) {
                overlay.addEventListener('click', () => setSidebar(false));
            }

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

        // Mart notifications in topbar (only on /dashboard/admin/mart pages)
        (function () {
            const wrap = document.getElementById('martBellNavWrap');
            const btn = document.getElementById('martBellNavBtn');
            const badge = document.getElementById('martBellNavBadge');
            const dd = document.getElementById('martBellNavDropdown');
            const list = document.getElementById('martBellNavList');
            const readAll = document.getElementById('martBellNavReadAll');
            if (!wrap || !btn || !badge || !dd || !list || !readAll) return;

            const path = (window.location && window.location.pathname) ? window.location.pathname : '';
            const isMart = path.indexOf('/dashboard/admin/mart') === 0;
            if (!isMart) return;
            wrap.style.display = 'block';

            // Move dropdown to <body> so it can't be clipped/stacked under dashboard containers
            try {
                if (dd.parentElement !== document.body) {
                    document.body.appendChild(dd);
                }
            } catch (e) {}

            const csrfMeta = document.querySelector('meta[name="csrf-token"]');
            const csrf = csrfMeta ? (csrfMeta.getAttribute('content') || '') : '';
            const notificationsUrl = @json(route('dashboard.admin.mart.orders.notifications'));
            const readUrl = @json(route('dashboard.admin.mart.orders.notifications.read'));

            function setBadge(n) {
                const num = Number(n || 0);
                if (num > 0) {
                    badge.textContent = String(num);
                    badge.style.display = 'inline-block';
                } else {
                    badge.textContent = '';
                    badge.style.display = 'none';
                }
            }

            function render(items) {
                if (!Array.isArray(items) || items.length === 0) {
                    list.innerHTML = '<div style="padding:10px 12px; font-size:13px; color:rgba(255,255,255,0.70);">لا توجد إشعارات جديدة</div>';
                    return;
                }

                list.innerHTML = items.map((o) => {
                    const number = o.order_number || ('#' + o.id);
                    const customer = o.customer_name || '';
                    const status = o.status || '-';
                    const total = (typeof o.total === 'number') ? o.total.toFixed(2) : (o.total || '0');
                    const created = o.created_at ? new Date(o.created_at).toLocaleString('ar-SA') : '';
                    const url = o.show_url || '#';
                    return `
                        <div style="padding:6px;">
                            <div style="border:1px solid rgba(255,255,255,0.12); background:rgba(255,255,255,0.05); border-radius:14px; padding:10px 12px;">
                                <div style="display:flex; align-items:flex-start; justify-content:space-between; gap:10px;">
                                    <a href="${url}" style="color:rgba(255,255,255,0.95); font-weight:850; font-size:13px; text-decoration:none;">${number}</a>
                                    <button type="button" class="mart-nav-read-btn" data-id="${o.id}" style="font-size:12px; color:rgba(255,255,255,0.85); text-decoration:underline; background:transparent; border:0; cursor:pointer;">قراءة</button>
                                </div>
                                <div style="margin-top:6px; font-size:12px; color:rgba(255,255,255,0.80);">${customer}</div>
                                <div style="margin-top:6px; font-size:12px; color:rgba(255,255,255,0.70);">الحالة: ${status} • الإجمالي: ${total}</div>
                                <div style="margin-top:6px; font-size:11px; color:rgba(255,255,255,0.55);">${created}</div>
                            </div>
                        </div>
                    `;
                }).join('');

                const buttons = list.querySelectorAll('.mart-nav-read-btn');
                for (let i = 0; i < buttons.length; i++) {
                    buttons[i].addEventListener('click', async function () {
                        const id = Number(this.getAttribute('data-id') || 0);
                        if (!id) return;
                        try {
                            await fetch(readUrl, {
                                method: 'POST',
                                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrf },
                                body: JSON.stringify({ order_id: id }),
                            });
                        } catch (e) {}
                        await refresh();
                    });
                }
            }

            async function refresh() {
                try {
                    const r = await fetch(notificationsUrl, { headers: { 'Accept': 'application/json' } });
                    const data = await r.json();
                    setBadge(data && data.unread_count ? data.unread_count : 0);
                    render(data && data.orders ? data.orders : []);
                } catch (e) {}
            }

            function positionDropdown() {
                const rect = btn.getBoundingClientRect();
                const maxW = Math.max(240, window.innerWidth - 24);
                const desiredW = 820;
                const w = Math.min(desiredW, maxW);
                dd.style.width = w + 'px';
                dd.style.maxWidth = (window.innerWidth - 24) + 'px';

                const top = rect.bottom + 10;
                dd.style.top = top + 'px';

                // Align dropdown to the button, but keep inside viewport
                let left = rect.right - w;
                if (left < 12) left = 12;
                const maxLeft = window.innerWidth - w - 12;
                if (left > maxLeft) left = Math.max(12, maxLeft);
                dd.style.left = left + 'px';
            }

            function toggleDropdown(open) {
                const show = (typeof open === 'boolean') ? open : (dd.style.display === 'none' || dd.style.display === '');
                if (show) {
                    positionDropdown();
                    dd.style.display = 'block';
                    refresh();
                } else {
                    dd.style.display = 'none';
                }
            }

            btn.addEventListener('click', function (e) {
                e.stopPropagation();
                toggleDropdown();
            });
            document.addEventListener('click', function (e) {
                if (dd.style.display !== 'block') return;
                if (dd.contains(e.target) || btn.contains(e.target)) return;
                dd.style.display = 'none';
            });
            window.addEventListener('resize', function () {
                if (dd.style.display === 'block') {
                    positionDropdown();
                }
            });
            window.addEventListener('scroll', function () {
                if (dd.style.display === 'block') {
                    positionDropdown();
                }
            }, true);
            readAll.addEventListener('click', async function () {
                try {
                    await fetch(readUrl, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrf },
                        body: JSON.stringify({ all: true }),
                    });
                } catch (e) {}
                await refresh();
            });

            refresh();
            setInterval(refresh, 15000);
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
                const csrfMeta = document.querySelector('meta[name="csrf-token"]');
                const csrf = csrfMeta ? (csrfMeta.getAttribute('content') || '') : '';
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
