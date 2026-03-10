<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'لوحة التحكم') - {{ config('app.name') }}</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800&family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/css/dashboard-next.css'])
    @stack('styles')
</head>
<body class="db-next">
    <a href="#mainContent" class="db4-skip">تخطي إلى المحتوى</a>
    <div id="sidebarOverlay" class="db4-overlay" hidden></div>

    <div class="db4-shell">
        <aside id="sidebar" class="db4-sidebar" aria-label="القائمة">
            <div class="flex items-start justify-between gap-3">
                <a href="{{ route('dashboard') }}" class="db4-brand">
                    <span class="db4-brand-mark" aria-hidden="true"><i class="fas fa-store"></i></span>
                    <div class="min-w-0">
                        <div class="db4-brand-title">{{ config('app.name') }}</div>
                        <div class="db4-brand-subtitle">Dashboard</div>
                    </div>
                </a>
                <div class="db4-sidebar-actions lg:hidden">
                    <button type="button" class="db4-icon-btn" aria-label="إغلاق القائمة" onclick="toggleSidebar(false)">
                        <i class="fas fa-xmark"></i>
                    </button>
                </div>
            </div>

            <nav class="db4-nav" aria-label="التنقل">
                @yield('sidebar-menu')
            </nav>

            <div class="db4-user">
                @php
                    $userName = is_array(data_get(auth()->user(),'name')) ? json_encode(data_get(auth()->user(),'name')) : (data_get(auth()->user(),'name') ?? '');
                @endphp
                <div class="db4-avatar" aria-hidden="true">{{ mb_substr($userName ?: 'U', 0, 1) }}</div>
                <div class="min-w-0">
                    <div class="db4-user-name">{{ $userName }}</div>
                    <div class="db4-user-email">@yield('user-role', 'مستخدم')</div>
                </div>
                <button type="button" class="db4-icon-btn" aria-label="قائمة المستخدم" onclick="toggleUserMenu()">
                    <i class="fas fa-ellipsis-vertical" aria-hidden="true"></i>
                </button>
            </div>
        </aside>
        
        <main class="db4-main">
            <header class="db4-topbar">
                <div class="db4-topbar-inner">
                    <div class="min-w-0">
                        <div class="flex items-start gap-3">
                            <div class="min-w-0">
                                <h1 class="db4-title">@yield('page-title', 'لوحة التحكم')</h1>
                                @hasSection('breadcrumb')
                                    <div class="db4-subtitle">@yield('breadcrumb')</div>
                                @else
                                    <div class="db4-subtitle">@yield('page-subtitle', ' ') </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="db4-actions">
                        <button type="button" class="db4-action-btn" aria-label="إظهار أو إخفاء القائمة" onclick="toggleSidebarVisibility()">
                            <i class="fas fa-bars-staggered" aria-hidden="true"></i>
                            <span class="hidden md:inline">القائمة</span>
                        </button>
                        <button type="button" class="db4-action-btn" aria-label="قائمة المستخدم" onclick="toggleUserMenu()">
                            <i class="fas fa-user" aria-hidden="true"></i>
                            <span class="hidden md:inline">الحساب</span>
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
    
    <!-- User Menu Dropdown -->
    <div id="userMenu" class="db4-menu" hidden>
        <a href="{{ route('profile.edit') }}">
            <i class="fas fa-user"></i>
            الملف الشخصي
        </a>
        <a href="{{ route('dashboard') }}">
            <i class="fas fa-th-large"></i>
            جميع اللوحات
        </a>
        <hr class="my-2" style="border-color: rgba(15, 23, 42, 0.10);">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit">
                <i class="fas fa-sign-out-alt"></i>
                تسجيل الخروج
            </button>
        </form>
    </div>
    
    <!-- Scripts -->
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

            document.addEventListener('click', function(event) {
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
        
        // Auto-hide alerts
        setTimeout(() => {
            const alerts = document.querySelectorAll('[class*="bg-success-"], [class*="bg-error-"]');
            alerts.forEach(alert => {
                alert.style.transition = 'opacity 0.5s ease';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 500);
            });
        }, 5000);
    </script>
    
    @stack('scripts')
</body>
</html>
