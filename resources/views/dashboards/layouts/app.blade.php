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
    $isAdmin = (bool) ($employee?->is_admin ?? false);
    $canAccess = function (string $routeName) use ($dashboardRoutes, $isAdmin): bool {
        return $isAdmin || in_array($routeName, $dashboardRoutes, true);
    };
@endphp

<!DOCTYPE html>
<html lang="{{ $dashboardLocale }}" dir="{{ $dashboardDir }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? ($dashboardLocale === 'ar' ? 'لوحة التحكم' : 'Dashboard') }} - Tulip Store</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=El+Messiri:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        * { font-family: 'El Messiri', sans-serif; }
        .sidebar-link.active { background: linear-gradient(90deg, #8B5CF6 0%, #6366F1 100%); color: white; }
        .sidebar-link:hover:not(.active) { background: #F3F4F6; }
        .card-gradient { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .stat-card { transition: all 0.3s ease; }
        .stat-card:hover { transform: translateY(-5px); box-shadow: 0 10px 40px rgba(0,0,0,0.1); }
    </style>
    @stack('styles')
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <div class="w-72 bg-white shadow-xl fixed h-full z-50">
            <!-- Logo -->
            <div class="p-6 border-b border-gray-100">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-indigo-600 rounded-xl flex items-center justify-center">
                        <i class="fas fa-store text-white text-xl"></i>
                    </div>
                    <div>
                        <h1 class="text-xl font-bold text-gray-800">Tulip Store</h1>
                        <p class="text-xs text-gray-500">لوحة التحكم الإدارية</p>
                    </div>
                </div>
            </div>
            
            <!-- Navigation -->
            <nav class="p-4 space-y-2">
                <p class="text-xs text-gray-400 uppercase tracking-wider mb-4 px-3">{{ $dashboardLocale === 'ar' ? 'القائمة الرئيسية' : 'Main Menu' }}</p>

                @if(! $isTraderSession && $canAccess('dashboard.admin.index'))
                    <a href="{{ route('dashboard.admin.index') }}" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl text-gray-700 {{ request()->routeIs('dashboard.admin.*') ? 'active' : '' }}">
                        <i class="fas fa-chart-pie w-5"></i>
                        <span>{{ $dashboardLocale === 'ar' ? 'لوحة الإدارة' : 'Admin' }}</span>
                    </a>
                @endif

                @if(! $isTraderSession && $canAccess('dashboard.cs.index'))
                    <a href="{{ route('dashboard.cs.index') }}" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl text-gray-700 {{ request()->routeIs('dashboard.cs.*') ? 'active' : '' }}">
                        <i class="fas fa-headset w-5"></i>
                        <span>{{ $dashboardLocale === 'ar' ? 'خدمة العملاء' : 'Support' }}</span>
                    </a>
                @endif

                @if(! $isTraderSession && $canAccess('dashboard.finance.index'))
                    <a href="{{ route('dashboard.finance.index') }}" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl text-gray-700 {{ request()->routeIs('dashboard.finance.*') ? 'active' : '' }}">
                        <i class="fas fa-coins w-5"></i>
                        <span>{{ $dashboardLocale === 'ar' ? 'المالية' : 'Finance' }}</span>
                    </a>
                @endif

                @if(! $isTraderSession && $canAccess('dashboard.hr.index'))
                    <a href="{{ route('dashboard.hr.index') }}" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl text-gray-700 {{ request()->routeIs('dashboard.hr.*') ? 'active' : '' }}">
                        <i class="fas fa-users-cog w-5"></i>
                        <span>{{ $dashboardLocale === 'ar' ? 'الموارد البشرية' : 'HR' }}</span>
                    </a>
                @endif

                @if(! $isTraderSession && $canAccess('dashboard.it.index'))
                    <a href="{{ route('dashboard.it.index') }}" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl text-gray-700 {{ request()->routeIs('dashboard.it.*') ? 'active' : '' }}">
                        <i class="fas fa-server w-5"></i>
                        <span>{{ $dashboardLocale === 'ar' ? 'تقنية المعلومات' : 'IT' }}</span>
                    </a>
                @endif

                @if(! $isTraderSession && $canAccess('dashboard.supervisor.index'))
                    <a href="{{ route('dashboard.supervisor.index') }}" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl text-gray-700 {{ request()->routeIs('dashboard.supervisor.*') ? 'active' : '' }}">
                        <i class="fas fa-truck w-5"></i>
                        <span>{{ $dashboardLocale === 'ar' ? 'إدارة السائقين' : 'Driver Supervisor' }}</span>
                    </a>
                @endif

            </nav>
            
            <!-- User Info -->
            <div class="absolute bottom-0 w-72 p-4 border-t border-gray-100 bg-gray-50">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-indigo-600 rounded-full flex items-center justify-center text-white font-bold">
                        {{ mb_substr($actorName ?? 'U', 0, 1) }}
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-semibold text-gray-800">{{ $actorName }}</p>
                        <p class="text-xs text-gray-500">{{ $actorEmail }}</p>
                    </div>
                    <form action="{{ $isTraderSession ? route('trader.logout') : route('employee.logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="w-8 h-8 bg-red-100 text-red-600 rounded-lg hover:bg-red-200 transition">
                            <i class="fas fa-sign-out-alt text-sm"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 mr-72">
            <!-- Top Bar -->
            <div class="bg-white shadow-sm px-8 py-4 flex items-center justify-between sticky top-0 z-40">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">{{ $title ?? 'لوحة التحكم' }}</h1>
                    <p class="text-sm text-gray-500">{{ $subtitle ?? 'مرحباً بك في لوحة التحكم' }}</p>
                </div>
                <div class="flex items-center gap-4">
                    <span class="text-sm text-gray-500">{{ now()->format('Y/m/d') }}</span>
                    <button class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center text-gray-600 hover:bg-gray-200">
                        <i class="fas fa-bell"></i>
                    </button>
                </div>
            </div>
            
            <!-- Page Content -->
            <div class="p-8">
                @if(session('success'))
                    <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl">
                        {{ session('success') }}
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-xl">
                        {{ session('error') }}
                    </div>
                @endif
                
                @yield('content')
            </div>
            
            <!-- Footer -->
            <div style="background: #F9FAFB; padding: 20px 32px; text-align: center; border-top: 1px solid #E5E7EB;">
                <p style="color: #6B7280; font-size: 14px;">© {{ date('Y') }} Tulip Store. جميع الحقوق محفوظة.</p>
            </div>
        </div>
    </div>
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
        }
    @endphp
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
