<?php
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
    $isAdmin = (bool) ($employee?->is_admin ?? false);
    $canAccess = function (string $routeName) use ($dashboardRoutes, $isAdmin): bool {
        return $isAdmin || in_array($routeName, $dashboardRoutes, true);
    };
?>

<!DOCTYPE html>
<html lang="<?php echo e($dashboardLocale); ?>" dir="<?php echo e($dashboardDir); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo e($title ?? ($dashboardLocale === 'ar' ? 'لوحة التحكم' : 'Dashboard')); ?> - Tulip Store</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800&family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/css/dashboard-next.css']); ?>
    <link rel="stylesheet" href="<?php echo e(asset('css/dashboard/tokens.css')); ?>">
    
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body class="db-next">
    <a href="#mainContent" class="db4-skip"><?php echo e($dashboardLocale === 'ar' ? 'تخطي إلى المحتوى' : 'Skip to content'); ?></a>
    <div id="sidebarOverlay" class="db4-overlay" hidden></div>

    <div class="db4-shell">
        <aside id="sidebar" class="db4-sidebar" aria-label="<?php echo e($dashboardLocale === 'ar' ? 'القائمة' : 'Navigation'); ?>">
            <div class="flex items-start justify-between gap-3">
                <a href="<?php echo e(route('dashboard.main')); ?>" class="db4-brand">
                    <span class="db4-brand-mark" aria-hidden="true"><i class="fas fa-store"></i></span>
                    <div class="min-w-0">
                        <div class="db4-brand-title">Tulip Store</div>
                        <div class="db4-brand-subtitle"><?php echo e($dashboardLocale === 'ar' ? 'لوحة التحكم' : 'Dashboard'); ?></div>
                    </div>
                </a>
                <div class="db4-sidebar-actions lg:hidden">
                    <button type="button" class="db4-icon-btn" aria-label="<?php echo e($dashboardLocale === 'ar' ? 'إغلاق القائمة' : 'Close navigation'); ?>" onclick="toggleSidebar(false)">
                        <i class="fas fa-xmark"></i>
                    </button>
                </div>
            </div>

            <nav class="db4-nav" aria-label="<?php echo e($dashboardLocale === 'ar' ? 'التنقل' : 'Navigation'); ?>">
                <div class="db4-nav-section">
                    <div class="db4-nav-title"><?php echo e($dashboardLocale === 'ar' ? 'لوحات التحكم' : 'Dashboards'); ?></div>

                    <?php if(! $isTraderSession && $canAccess('dashboard.admin.index')): ?>
                        <a href="<?php echo e(route('dashboard.admin.index')); ?>" class="db4-nav-link <?php echo e(request()->routeIs('dashboard.admin.*') ? 'is-active' : ''); ?>">
                            <span class="db4-nav-icon" aria-hidden="true"><i class="fas fa-chart-pie"></i></span>
                            <span class="db4-nav-meta">
                                <span class="db4-nav-label"><?php echo e($dashboardLocale === 'ar' ? 'لوحة الإدارة' : 'Admin'); ?></span>
                                <span class="db4-nav-hint"><?php echo e($dashboardLocale === 'ar' ? 'نظرة عامة وإدارة المنصة' : 'Overview and platform controls'); ?></span>
                            </span>
                        </a>
                        <a href="<?php echo e(route('dashboard.admin.style-guide')); ?>" class="db4-nav-link <?php echo e(request()->routeIs('dashboard.admin.style-guide') ? 'is-active' : ''); ?>">
                            <span class="db4-nav-icon" aria-hidden="true"><i class="fas fa-palette"></i></span>
                            <span class="db4-nav-meta">
                                <span class="db4-nav-label"><?php echo e($dashboardLocale === 'ar' ? 'دليل التصميم' : 'Style Guide'); ?></span>
                                <span class="db4-nav-hint"><?php echo e($dashboardLocale === 'ar' ? 'مكونات وأنماط موحدة' : 'Tokens and components'); ?></span>
                            </span>
                        </a>
                    <?php endif; ?>

                    <?php if(! $isTraderSession && $canAccess('dashboard.cs.index')): ?>
                        <a href="<?php echo e(route('dashboard.cs.index')); ?>" class="db4-nav-link <?php echo e(request()->routeIs('dashboard.cs.*') ? 'is-active' : ''); ?>">
                            <span class="db4-nav-icon" aria-hidden="true"><i class="fas fa-headset"></i></span>
                            <span class="db4-nav-meta">
                                <span class="db4-nav-label"><?php echo e($dashboardLocale === 'ar' ? 'خدمة العملاء' : 'Support'); ?></span>
                                <span class="db4-nav-hint"><?php echo e($dashboardLocale === 'ar' ? 'تذاكر ودعم العملاء' : 'Tickets and customer care'); ?></span>
                            </span>
                        </a>
                    <?php endif; ?>

                    <?php if(! $isTraderSession && $canAccess('dashboard.finance.index')): ?>
                        <a href="<?php echo e(route('dashboard.finance.index')); ?>" class="db4-nav-link <?php echo e(request()->routeIs('dashboard.finance.*') ? 'is-active' : ''); ?>">
                            <span class="db4-nav-icon" aria-hidden="true"><i class="fas fa-coins"></i></span>
                            <span class="db4-nav-meta">
                                <span class="db4-nav-label"><?php echo e($dashboardLocale === 'ar' ? 'المالية' : 'Finance'); ?></span>
                                <span class="db4-nav-hint"><?php echo e($dashboardLocale === 'ar' ? 'معاملات وتقارير' : 'Transactions and reporting'); ?></span>
                            </span>
                        </a>
                    <?php endif; ?>

                    <?php if(! $isTraderSession && $canAccess('dashboard.hr.index')): ?>
                        <a href="<?php echo e(route('dashboard.hr.index')); ?>" class="db4-nav-link <?php echo e(request()->routeIs('dashboard.hr.*') ? 'is-active' : ''); ?>">
                            <span class="db4-nav-icon" aria-hidden="true"><i class="fas fa-users-cog"></i></span>
                            <span class="db4-nav-meta">
                                <span class="db4-nav-label"><?php echo e($dashboardLocale === 'ar' ? 'الموارد البشرية' : 'HR'); ?></span>
                                <span class="db4-nav-hint"><?php echo e($dashboardLocale === 'ar' ? 'حضور وموارد بشرية' : 'Attendance and people ops'); ?></span>
                            </span>
                        </a>
                    <?php endif; ?>

                    <?php if(! $isTraderSession && $canAccess('dashboard.it.index')): ?>
                        <a href="<?php echo e(route('dashboard.it.index')); ?>" class="db4-nav-link <?php echo e(request()->routeIs('dashboard.it.*') ? 'is-active' : ''); ?>">
                            <span class="db4-nav-icon" aria-hidden="true"><i class="fas fa-server"></i></span>
                            <span class="db4-nav-meta">
                                <span class="db4-nav-label"><?php echo e($dashboardLocale === 'ar' ? 'تقنية المعلومات' : 'IT'); ?></span>
                                <span class="db4-nav-hint"><?php echo e($dashboardLocale === 'ar' ? 'المراقبة والتشغيل' : 'Operations and tooling'); ?></span>
                            </span>
                        </a>
                    <?php endif; ?>

                    <?php if(! $isTraderSession && $canAccess('dashboard.supervisor.index')): ?>
                        <a href="<?php echo e(route('dashboard.supervisor.index')); ?>" class="db4-nav-link <?php echo e(request()->routeIs('dashboard.supervisor.*') ? 'is-active' : ''); ?>">
                            <span class="db4-nav-icon" aria-hidden="true"><i class="fas fa-truck"></i></span>
                            <span class="db4-nav-meta">
                                <span class="db4-nav-label"><?php echo e($dashboardLocale === 'ar' ? 'إدارة السائقين' : 'Drivers'); ?></span>
                                <span class="db4-nav-hint"><?php echo e($dashboardLocale === 'ar' ? 'تتبع وتوزيع الطلبات' : 'Live tracking and dispatch'); ?></span>
                            </span>
                        </a>
                    <?php endif; ?>

                    <?php if($isTraderSession && $canAccess('dashboard.vendor.index')): ?>
                        <a href="<?php echo e(route('dashboard.vendor.index')); ?>" class="db4-nav-link <?php echo e(request()->routeIs('dashboard.vendor.*') ? 'is-active' : ''); ?>">
                            <span class="db4-nav-icon" aria-hidden="true"><i class="fas fa-store"></i></span>
                            <span class="db4-nav-meta">
                                <span class="db4-nav-label"><?php echo e($dashboardLocale === 'ar' ? 'إدارة المتجر' : 'Store'); ?></span>
                                <span class="db4-nav-hint"><?php echo e($dashboardLocale === 'ar' ? 'منتجات وطلبات' : 'Products and orders'); ?></span>
                            </span>
                        </a>
                    <?php endif; ?>
                </div>
            </nav>

            <div class="db4-user">
                <div class="db4-avatar" aria-hidden="true"><?php echo e(mb_substr($actorName ?? 'U', 0, 1)); ?></div>
                <div class="min-w-0">
                    <div class="db4-user-name"><?php echo e($actorName); ?></div>
                    <div class="db4-user-email"><?php echo e($actorEmail); ?></div>
                </div>
                <form action="<?php echo e($isTraderSession ? route('trader.logout') : route('employee.logout')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="db4-icon-btn" aria-label="<?php echo e($dashboardLocale === 'ar' ? 'تسجيل الخروج' : 'Logout'); ?>">
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
                                <h1 class="db4-title"><?php echo e($title ?? ($dashboardLocale === 'ar' ? 'لوحة التحكم' : 'Dashboard')); ?></h1>
                                <div class="db4-subtitle"><?php echo e($subtitle ?? ($dashboardLocale === 'ar' ? 'مرحباً بك في لوحة التحكم' : 'Welcome')); ?></div>
                            </div>
                        </div>
                    </div>

                    <div class="db4-actions">
                        <button type="button" class="db4-action-btn" aria-label="<?php echo e($dashboardLocale === 'ar' ? 'إظهار أو إخفاء القائمة' : 'Toggle sidebar'); ?>" onclick="toggleSidebarVisibility()">
                            <i class="fas fa-bars-staggered" aria-hidden="true"></i>
                            <span class="hidden md:inline"><?php echo e($dashboardLocale === 'ar' ? 'القائمة' : 'Sidebar'); ?></span>
                        </button>
                        <button type="button" class="db4-action-btn" aria-label="<?php echo e($dashboardLocale === 'ar' ? 'قائمة المستخدم' : 'User menu'); ?>" onclick="toggleUserMenu()">
                            <span aria-hidden="true" class="inline-flex items-center justify-center w-8 h-8 rounded-xl" style="background: rgba(13, 70, 76, 0.10); color: var(--db4-primary); font-weight: 900;">
                                <?php echo e(mb_substr($actorName ?? 'U', 0, 1)); ?>

                            </span>
                            <span class="hidden md:inline"><?php echo e($actorName); ?></span>
                            <i class="fas fa-chevron-down" aria-hidden="true"></i>
                        </button>
                    </div>
                </div>
            </header>

            <div class="db4-container" id="mainContent" tabindex="-1">
                <?php if(session('success')): ?>
                    <div class="db4-alert db4-alert--success mb-6"><?php echo e(session('success')); ?></div>
                <?php endif; ?>

                <?php if(session('error')): ?>
                    <div class="db4-alert db4-alert--error mb-6"><?php echo e(session('error')); ?></div>
                <?php endif; ?>

                <?php echo $__env->yieldContent('content'); ?>
            </div>
        </main>
    </div>

    <div id="userMenu" class="db4-menu" hidden>
        <form method="POST" action="<?php echo e($isTraderSession ? route('trader.logout') : route('employee.logout')); ?>">
            <?php echo csrf_field(); ?>
            <button type="submit">
                <i class="fas fa-sign-out-alt w-5" aria-hidden="true"></i>
                <?php echo e($dashboardLocale === 'ar' ? 'تسجيل الخروج' : 'Logout'); ?>

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
    </script>
    <?php echo $__env->yieldPushContent('scripts'); ?>
    <?php
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
        }
    ?>
    <?php if($broadcastDriver === 'pusher' && $dashKey): ?>
        <script src="https://cdn.jsdelivr.net/npm/pusher-js@8.4.0/dist/web/pusher.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/laravel-echo@1.16.0/dist/echo.iife.js"></script>
        <script>
            (function () {
                const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
                window.Pusher = window.Pusher || Pusher;
                window.Echo = new Echo({
                    broadcaster: 'pusher',
                    key: <?php echo json_encode(env('PUSHER_APP_KEY', ''), 512) ?>,
                    cluster: <?php echo json_encode(env('PUSHER_APP_CLUSTER', ''), 512) ?>,
                    wsHost: <?php echo json_encode(env('PUSHER_HOST', request()->getHost()), 512) ?>,
                    wsPort: <?php echo json_encode((int) env('PUSHER_PORT', 6001), 512) ?>,
                    wssPort: <?php echo json_encode((int) env('PUSHER_PORT', 6001), 512) ?>,
                    forceTLS: <?php echo json_encode(env('PUSHER_SCHEME', 'http') === 'https', 512) ?>,
                    encrypted: <?php echo json_encode(env('PUSHER_SCHEME', 'http') === 'https', 512) ?>,
                    disableStats: true,
                    enabledTransports: ['ws', 'wss'],
                    authEndpoint: <?php echo json_encode(url('/broadcasting/auth'), 15, 512) ?>,
                    auth: {
                        headers: {
                            'X-CSRF-TOKEN': csrf
                        }
                    }
                });

                const dashKey = <?php echo json_encode($dashKey, 15, 512) ?>;
                window.Echo.private('dashboard.' + dashKey).listen('.dashboard.updated', function (e) {
                    window.dispatchEvent(new CustomEvent('dashboard.updated', { detail: e }));
                });
            })();
        </script>
    <?php endif; ?>
</body>
</html>
<?php /**PATH E:\Tulip-Store\resources\views/dashboards/layouts/app.blade.php ENDPATH**/ ?>