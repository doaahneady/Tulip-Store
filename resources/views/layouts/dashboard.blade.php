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
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Styles -->
    <link href="{{ asset('resources/css/dashboard-system.css') }}" rel="stylesheet">
    @stack('styles')
    
    <style>
        /* RTL Adjustments */
        [dir="rtl"] .dashboard-sidebar {
            right: 0;
            left: auto;
            border-right: none;
            border-left: 1px solid var(--gray-200);
        }
        
        [dir="rtl"] .dashboard-main {
            margin-right: 280px;
            margin-left: 0;
        }
        
        [dir="rtl"] .topbar-search input {
            padding: var(--space-2) var(--space-10) var(--space-2) var(--space-3);
        }
        
        [dir="rtl"] .topbar-search-icon {
            right: var(--space-3);
            left: auto;
        }
        
        [dir="rtl"] .notification-badge {
            left: 0;
            right: auto;
        }
        
        @media (max-width: 1024px) {
            [dir="rtl"] .dashboard-sidebar {
                transform: translateX(100%);
            }
            
            [dir="rtl"] .dashboard-sidebar.open {
                transform: translateX(0);
            }
            
            [dir="rtl"] .dashboard-main {
                margin-right: 0;
            }
        }
    </style>
</head>
<body>
    <div class="dashboard-layout">
        <!-- Sidebar -->
        <aside class="dashboard-sidebar" id="sidebar">
            <div class="sidebar-header">
                <a href="{{ route('dashboard') }}" class="sidebar-logo">
                    <i class="fas fa-store text-primary-500"></i>
                    <span>{{ config('app.name') }}</span>
                </a>
            </div>
            
            <nav class="sidebar-nav">
                @yield('sidebar-menu')
            </nav>
        </aside>
        
        <!-- Main Content -->
        <main class="dashboard-main">
            <!-- Topbar -->
            <header class="dashboard-topbar">
                <div class="topbar-left">
                    <button class="lg:hidden p-2 rounded-md hover:bg-gray-100" onclick="toggleSidebar()">
                        <i class="fas fa-bars"></i>
                    </button>
                    <div>
                        <h1 class="topbar-title">@yield('page-title', 'لوحة التحكم')</h1>
                        @hasSection('breadcrumb')
                        <div class="topbar-breadcrumb">
                            @yield('breadcrumb')
                        </div>
                        @endif
                    </div>
                </div>
                
                <div class="topbar-right">
                    <!-- Search -->
                    <div class="topbar-search">
                        <i class="fas fa-search topbar-search-icon"></i>
                        <input type="text" placeholder="البحث...">
                    </div>
                    
                    <!-- Notifications -->
                    <div class="topbar-notifications">
                        <i class="fas fa-bell"></i>
                        <span class="notification-badge"></span>
                    </div>
                    
                    <!-- User Menu -->
                    <div class="topbar-user" onclick="toggleUserMenu()">
                        <div class="user-avatar">
                            {{ substr(auth()->user()->name, 0, 1) }}
                        </div>
                        <div class="user-info">
                            <div class="user-name">{{ auth()->user()->name }}</div>
                            <div class="user-role">@yield('user-role', 'مستخدم')</div>
                        </div>
                        <i class="fas fa-chevron-down text-xs"></i>
                    </div>
                </div>
            </header>
            
            <!-- Content -->
            <div class="dashboard-content">
                @if(session('success'))
                <div class="mb-6 p-4 bg-success-50 border border-success-200 rounded-lg text-success-700">
                    <i class="fas fa-check-circle mr-2"></i>
                    {{ session('success') }}
                </div>
                @endif
                
                @if(session('error'))
                <div class="mb-6 p-4 bg-error-50 border border-error-200 rounded-lg text-error-700">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    {{ session('error') }}
                </div>
                @endif
                
                @yield('content')
            </div>
        </main>
    </div>
    
    <!-- User Menu Dropdown -->
    <div id="userMenu" class="fixed top-16 right-6 bg-white rounded-lg shadow-xl border border-gray-200 py-2 min-w-48 z-50 hidden">
        <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
            <i class="fas fa-user"></i>
            الملف الشخصي
        </a>
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
            <i class="fas fa-th-large"></i>
            جميع اللوحات
        </a>
        <hr class="my-2">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full flex items-center gap-3 px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                <i class="fas fa-sign-out-alt"></i>
                تسجيل الخروج
            </button>
        </form>
    </div>
    
    <!-- Scripts -->
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('open');
        }
        
        function toggleUserMenu() {
            const menu = document.getElementById('userMenu');
            menu.classList.toggle('hidden');
        }
        
        // Close user menu when clicking outside
        document.addEventListener('click', function(event) {
            const menu = document.getElementById('userMenu');
            const userButton = document.querySelector('.topbar-user');
            
            if (!menu.contains(event.target) && !userButton.contains(event.target)) {
                menu.classList.add('hidden');
            }
        });
        
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