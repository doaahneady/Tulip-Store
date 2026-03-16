@extends('dashboards.layouts.app')
@section('content')
@php $title = 'لوحة تقنية المعلومات'; $subtitle = 'مراقبة النظام والأداء والأمان'; @endphp

<div class="mb-6 flex flex-wrap items-center justify-between gap-3">
    <div class="flex flex-wrap items-center gap-2">
        <a href="{{ route('dashboard.it.system-health') }}" class="inline-flex items-center gap-2 bg-indigo-600 text-white px-4 py-2 rounded-xl hover:bg-indigo-700 transition">
            <i class="fas fa-heartbeat"></i>
            <span>System Health</span>
        </a>
        <a href="{{ route('dashboard.it.logs') }}" class="inline-flex items-center gap-2 bg-slate-700 text-white px-4 py-2 rounded-xl hover:bg-slate-800 transition">
            <i class="fas fa-file-alt"></i>
            <span>Logs</span>
        </a>
        <a href="{{ route('dashboard.it.api-errors') }}" class="inline-flex items-center gap-2 bg-amber-600 text-white px-4 py-2 rounded-xl hover:bg-amber-700 transition">
            <i class="fas fa-bug"></i>
            <span>API Errors</span>
        </a>
        <a href="{{ route('dashboard.it.database') }}" class="inline-flex items-center gap-2 bg-emerald-600 text-white px-4 py-2 rounded-xl hover:bg-emerald-700 transition">
            <i class="fas fa-database"></i>
            <span>Database</span>
        </a>
        <a href="{{ route('dashboard.it.backups') }}" class="inline-flex items-center gap-2 bg-blue-600 text-white px-4 py-2 rounded-xl hover:bg-blue-700 transition">
            <i class="fas fa-cloud-upload-alt"></i>
            <span>Backups</span>
        </a>
        <a href="{{ route('dashboard.administrative-approvals.index') }}" class="inline-flex items-center gap-2 bg-purple-600 text-white px-4 py-2 rounded-xl hover:bg-purple-700 transition">
            <i class="fas fa-clipboard-check"></i>
            <span>الموافقات الإدارية</span>
        </a>
        <a href="{{ route('dashboard.my-attendance.index') }}" class="inline-flex items-center gap-2 bg-teal-600 text-white px-4 py-2 rounded-xl hover:bg-teal-700 transition">
            <i class="fas fa-user-clock"></i>
            <span>حضوري</span>
        </a>
    </div>
</div>

<!-- System Metrics -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-4 mb-8">
    <x-dashboard.stat-card title="المستخدمين" :value="number_format($systemMetrics['total_users'])" icon="fas fa-users" color="blue" />
    <x-dashboard.stat-card title="الموظفين" :value="number_format($systemMetrics['total_employees'])" icon="fas fa-user-tie" color="purple" />
    <x-dashboard.stat-card title="المنتجات" :value="number_format($systemMetrics['total_products'])" icon="fas fa-box" color="green" />
    <x-dashboard.stat-card title="الطلبات" :value="number_format($systemMetrics['total_orders'])" icon="fas fa-shopping-cart" color="orange" />
    <x-dashboard.stat-card title="السائقين" :value="number_format($systemMetrics['total_drivers'])" icon="fas fa-truck" color="indigo" />
    <x-dashboard.stat-card title="حجم قاعدة البيانات" :value="$systemMetrics['database_size']" icon="fas fa-database" color="red" />
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 mb-8">
    <x-dashboard.stat-card title="مدة التشغيل" :value="$metrics['system_uptime'] ?? 'N/A'" icon="fas fa-clock" color="gray" />
    <x-dashboard.stat-card title="استخدام CPU" :value="isset($metrics['cpu_usage']) ? (number_format($metrics['cpu_usage'],1).'%' ) : 'N/A'" icon="fas fa-microchip" color="orange" />
    <x-dashboard.stat-card title="استخدام الذاكرة" :value="isset($metrics['memory_usage']) ? (number_format($metrics['memory_usage'],1).'%' ) : 'N/A'" icon="fas fa-memory" color="purple" />
    <x-dashboard.stat-card title="استخدام القرص" :value="isset($metrics['disk_usage']) ? (number_format($metrics['disk_usage'],1).'%' ) : 'N/A'" icon="fas fa-hdd" color="teal" />
    <x-dashboard.stat-card title="شبكة" :value="$metrics['network_throughput'] ?? 'N/A'" icon="fas fa-network-wired" color="blue" />
    </div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    <!-- User Activity Today -->
    <div class="bg-white rounded-2xl p-6 shadow-sm">
        <h3 class="text-lg font-bold text-gray-800 mb-4"><i class="fas fa-chart-line text-blue-500 ml-2"></i>نشاط اليوم</h3>
        <div class="space-y-4">
            <div class="flex items-center justify-between p-3 bg-blue-800 rounded-xl border border-gray-600">
                <span class="text-blue-100">تسجيلات جديدة</span>

                <span class="font-bold text-white">{{ $userActivity['new_registrations'] }}</span>
            </div>
            <div class="flex items-center justify-between p-3 bg-green-800 rounded-xl border border-gray-600">
                <span class="text-green-100">تسجيلات هذا الأسبوع</span>
                <span class="font-bold text-white">{{ $userActivity['new_this_week'] }}</span>
            </div>
            <div class="flex items-center justify-between p-3 bg-purple-800 rounded-xl border border-gray-600">
                <span class="text-purple-100">موظفين نشطين اليوم</span>
                <span class="font-bold text-white">{{ $userActivity['active_employees'] }}</span>
            </div>
            <div class="flex items-center justify-between p-3 bg-orange-700 rounded-xl border border-gray-600">
                <span class="text-orange-100">طلبات اليوم</span>
                <span class="font-bold text-white">{{ $userActivity['orders_today'] }}</span>
            </div>
        </div>
    </div>

    <!-- System Services -->
    <div class="bg-white rounded-2xl p-6 shadow-sm">
        <h3 class="text-lg font-bold text-gray-800 mb-4"><i class="fas fa-server text-green-500 ml-2"></i>حالة الخدمات</h3>
        <div class="space-y-3">
            @foreach($systemServices as $service)
            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl">
                <div class="flex items-center gap-3">
                    <div class="w-3 h-3 rounded-full {{ $service->status == 'running' ? 'bg-green-500' : ($service->status == 'stopped' ? 'bg-red-500' : 'bg-yellow-500') }}"></div>
                    <span class="text-gray-700">{{ $service->display_name ?? $service->name }}</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-xs text-gray-500">{{ $service->uptime ?? 'N/A' }}</span>
                    <span class="px-2 py-1 text-xs rounded-lg {{ $service->status == 'running' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                        {{ $service->status == 'running' ? 'يعمل' : 'متوقف' }}
                    </span>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Backup Status -->
    <div class="bg-white rounded-2xl p-6 shadow-sm">
        <h3 class="text-lg font-bold text-gray-800 mb-4"><i class="fas fa-cloud-upload-alt text-indigo-500 ml-2"></i>النسخ الاحتياطية</h3>
        <div class="space-y-4">
            <div class="grid grid-cols-3 gap-3 text-center">
                <div class="p-3 bg-blue-800 rounded-xl border border-gray-600">
                    <p class="text-2xl font-bold text-white">{{ $backupStats['total'] }}</p>
                    <p class="text-xs text-blue-200">الإجمالي</p>
                </div>
                <div class="p-3 bg-green-800 rounded-xl border border-gray-600">
                    <p class="text-2xl font-bold text-white">{{ $backupStats['completed'] }}</p>
                    <p class="text-xs text-green-200">مكتمل</p>
                </div>
                <div class="p-3 bg-red-800 rounded-xl border border-gray-600">
                    <p class="text-2xl font-bold text-white">{{ $backupStats['failed'] }}</p>
                    <p class="text-xs text-red-200">فشل</p>
                </div>
            </div>
            @if($backupStats['last_backup'])
            <div class="p-3 bg-gray-50 rounded-xl">
                <p class="text-xs text-gray-500">آخر نسخة احتياطية</p>
                <p class="font-semibold text-gray-800">{{ $backupStats['last_backup']->backup_name }}</p>
                <p class="text-xs text-gray-500">{{ $backupStats['last_backup']->completed_at?->diffForHumans() ?? 'N/A' }}</p>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Charts Row -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <!-- Daily Traffic Chart -->
    <div class="bg-white rounded-2xl p-6 shadow-sm">
        <h3 class="text-lg font-bold text-gray-800 mb-4"><i class="fas fa-chart-area text-blue-500 ml-2"></i>حركة المرور (آخر 7 أيام)</h3>
        <canvas id="trafficChart" height="200"></canvas>
    </div>

    <!-- Orders by Status -->
    <div class="bg-white rounded-2xl p-6 shadow-sm">
        <h3 class="text-lg font-bold text-gray-800 mb-4"><i class="fas fa-chart-pie text-purple-500 ml-2"></i>حالة الطلبات</h3>
        <canvas id="ordersChart" height="200"></canvas>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    <div class="bg-white rounded-2xl p-6 shadow-sm">
        <h3 class="text-lg font-bold text-gray-800 mb-4"><i class="fas fa-tachometer-alt text-orange-500 ml-2"></i>أداء واجهة API</h3>
        <div class="grid grid-cols-3 gap-3">
            <div class="p-3 bg-purple-800 rounded-xl text-center border border-gray-600">
                <p class="text-xl font-bold text-white">{{ number_format($apiStats['avg_response_time'] ?? 0, 0) }}ms</p>
                <p class="text-xs text-purple-200">متوسط الاستجابة</p>
            </div>
            <div class="p-3 bg-green-800 rounded-xl text-center border border-gray-600">
                @php $success = isset($metrics['error_rate_24h']) ? max(0, 100 - $metrics['error_rate_24h']) : null; @endphp
                <p class="text-xl font-bold text-white">{{ $success !== null ? (number_format($success, 1).'%') : 'N/A' }}</p>
                <p class="text-xs text-green-200">نسبة النجاح</p>
            </div>
            <div class="p-3 bg-red-800 rounded-xl text-center border border-gray-600">
                <p class="text-xl font-bold text-white">{{ $metrics['slow_queries_today'] ?? 0 }}</p>
                <p class="text-xs text-red-200">استعلامات بطيئة اليوم</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl p-6 shadow-sm">
        <h3 class="text-lg font-bold text-gray-800 mb-4"><i class="fas fa-bug text-red-500 ml-2"></i>مراقبة الأخطاء</h3>
        <div class="space-y-2 max-h-48 overflow-y-auto">
            @php $recentErrors = ($systemLogs ?? collect())->where('level','error')->take(10); @endphp
            @forelse($recentErrors as $log)
            <div class="flex items-start gap-3 p-3 bg-red-50 rounded-lg">
                <div class="w-2 h-2 mt-2 rounded-full bg-red-500"></div>
                <div class="flex-1">
                    <p class="text-sm text-gray-800">{{ Str::limit($log->message, 80) }}</p>
                    <p class="text-xs text-gray-500">{{ $log->created_at->diffForHumans() }} - {{ $log->channel ?? 'system' }}</p>
                </div>
            </div>
            @empty
            <p class="text-center text-gray-500 py-4">لا توجد أخطاء حديثة</p>
            @endforelse
        </div>
    </div>

    <div class="bg-white rounded-2xl p-6 shadow-sm">
        <h3 class="text-lg font-bold text-gray-800 mb-4"><i class="fas fa-code-branch text-indigo-500 ml-2"></i>النشر والإصدارات</h3>
        <div class="grid grid-cols-3 gap-3">
            <div class="p-3 bg-blue-800 rounded-xl text-center border border-gray-600">
                <p class="text-xl font-bold text-white">{{ $metrics['deployments_this_month'] ?? 0 }}</p>
                <p class="text-xs text-blue-200">نشـر هذا الشهر</p>
            </div>
            <div class="p-3 bg-green-800 rounded-xl text-center border border-gray-600">
                <p class="text-xl font-bold text-white">{{ isset($metrics['deployment_success_rate']) ? (number_format($metrics['deployment_success_rate'],1).'%') : 'N/A' }}</p>
                <p class="text-xs text-green-200">معدل النجاح</p>
            </div>
            <div class="p-3 bg-gray-800 rounded-xl text-center border border-gray-600">
                <p class="text-sm font-semibold text-white">{{ optional($metrics['last_deployment'])->diffForHumans() ?? 'N/A' }}</p>
                <p class="text-xs text-gray-200">آخر نشر</p>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <!-- Security Stats -->
    <div class="bg-white rounded-2xl p-6 shadow-sm">
        <h3 class="text-lg font-bold text-gray-800 mb-4"><i class="fas fa-shield-alt text-red-500 ml-2"></i>إحصائيات الأمان</h3>
        <div class="grid grid-cols-2 gap-4 mb-4">
            <div class="p-4 bg-blue-800 rounded-xl text-center border border-gray-600">
                <p class="text-2xl font-bold text-white">{{ number_format($securityStats['total_events']) }}</p>
                <p class="text-xs text-blue-200">إجمالي الأحداث</p>
            </div>
            <div class="p-4 bg-green-800 rounded-xl text-center border border-gray-600">
                <p class="text-2xl font-bold text-white">{{ $securityStats['today_events'] }}</p>
                <p class="text-xs text-green-200">أحداث اليوم</p>
            </div>
            <div class="p-4 bg-yellow-600 rounded-xl text-center border border-gray-600">
                <p class="text-2xl font-bold text-white">{{ $securityStats['failed_logins'] }}</p>
                <p class="text-xs text-yellow-100">محاولات فاشلة</p>
            </div>
            <div class="p-4 bg-red-800 rounded-xl text-center border border-gray-600">
                <p class="text-2xl font-bold text-white">{{ $securityStats['high_risk'] }}</p>
                <p class="text-xs text-red-200">مخاطر عالية</p>
            </div>
        </div>
    </div>

    <!-- Log Stats -->
    <div class="bg-white rounded-2xl p-6 shadow-sm">
        <h3 class="text-lg font-bold text-gray-800 mb-4"><i class="fas fa-file-alt text-orange-500 ml-2"></i>إحصائيات السجلات</h3>
        <div class="grid grid-cols-2 gap-4 mb-4">
            <div class="p-4 bg-blue-800 rounded-xl text-center border border-gray-600">
                <p class="text-2xl font-bold text-white">{{ number_format($logStats['total']) }}</p>
                <p class="text-xs text-blue-200">إجمالي السجلات</p>
            </div>
            <div class="p-4 bg-green-800 rounded-xl text-center border border-gray-600">
                <p class="text-2xl font-bold text-white">{{ $logStats['today'] }}</p>
                <p class="text-xs text-green-200">سجلات اليوم</p>
            </div>
            <div class="p-4 bg-yellow-600 rounded-xl text-center border border-gray-600">
                <p class="text-2xl font-bold text-white">{{ $logStats['warnings'] }}</p>
                <p class="text-xs text-yellow-100">تحذيرات</p>
            </div>
            <div class="p-4 bg-red-800 rounded-xl text-center border border-gray-600">
                <p class="text-2xl font-bold text-white">{{ $logStats['errors'] }}</p>
                <p class="text-xs text-red-200">أخطاء</p>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <!-- Recent Security Logs -->
    <div class="bg-white rounded-2xl p-6 shadow-sm">
        <h3 class="text-lg font-bold text-gray-800 mb-4"><i class="fas fa-lock text-indigo-500 ml-2"></i>سجلات الأمان الأخيرة</h3>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="text-right text-xs text-gray-500 border-b">
                        <th class="pb-3">الحدث</th>
                        <th class="pb-3">الحالة</th>
                        <th class="pb-3">IP</th>
                        <th class="pb-3">الوقت</th>
                    </tr>
                </thead>
                <tbody class="text-sm">
                    @forelse($securityLogs as $log)
                    <tr class="border-b border-gray-50">
                        <td class="py-3">{{ $log->event_type }}</td>
                        <td class="py-3">
                            <span class="px-2 py-1 text-xs rounded-lg {{ $log->status == 'success' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                {{ $log->status == 'success' ? 'نجاح' : 'فشل' }}
                            </span>
                        </td>
                        <td class="py-3 text-gray-500">{{ $log->ip_address ?? 'N/A' }}</td>
                        <td class="py-3 text-gray-500">{{ $log->created_at->diffForHumans() }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="py-4 text-center text-gray-500">لا توجد سجلات</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- System Logs -->
    <div class="bg-white rounded-2xl p-6 shadow-sm">
        <h3 class="text-lg font-bold text-gray-800 mb-4"><i class="fas fa-terminal text-gray-500 ml-2"></i>سجلات النظام</h3>
        <div class="space-y-2 max-h-80 overflow-y-auto">
            @forelse($systemLogs as $log)
            <div class="flex items-start gap-3 p-3 bg-gray-50 rounded-lg">
                <div class="w-2 h-2 mt-2 rounded-full {{ $log->level == 'error' ? 'bg-red-500' : ($log->level == 'warning' ? 'bg-yellow-500' : 'bg-green-500') }}"></div>
                <div class="flex-1">
                    <p class="text-sm text-gray-800">{{ Str::limit($log->message, 60) }}</p>
                    <p class="text-xs text-gray-500">{{ $log->created_at->diffForHumans() }} - {{ $log->action ?? 'system' }}</p>
                </div>
            </div>
            @empty
            <p class="text-center text-gray-500 py-4">لا توجد سجلات</p>
            @endforelse
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <!-- API Errors -->
    <div class="bg-gray-800 rounded-2xl p-6 shadow-sm border border-gray-700">
        <h3 class="text-lg font-bold text-white mb-4"><i class="fas fa-exclamation-triangle text-yellow-500 ml-2"></i>أخطاء API</h3>
        <div class="grid grid-cols-3 gap-3 mb-4">
            <div class="p-3 bg-blue-800 rounded-xl text-center border border-gray-600">
                <p class="text-xl font-bold text-white">{{ $apiStats['total_errors'] }}</p>
                <p class="text-xs text-blue-200">الإجمالي</p>
            </div>
            <div class="p-3 bg-orange-700 rounded-xl text-center border border-gray-600">
                <p class="text-xl font-bold text-white">{{ $apiStats['today_errors'] }}</p>
                <p class="text-xs text-orange-200">اليوم</p>
            </div>
            <div class="p-3 bg-purple-800 rounded-xl text-center border border-gray-600">
                <p class="text-xl font-bold text-white">{{ number_format($apiStats['avg_response_time'], 0) }}ms</p>
                <p class="text-xs text-purple-200">متوسط الاستجابة</p>
            </div>
        </div>
        <div class="space-y-2 max-h-48 overflow-y-auto">
            @forelse($apiErrors as $error)
            <div class="flex items-center justify-between p-2 bg-gray-700 rounded-lg text-sm">
                <span class="text-gray-200">{{ Str::limit($error->endpoint, 30) }}</span>
                <span class="px-2 py-1 text-xs rounded bg-red-200 text-red-800">{{ $error->status_code }}</span>
            </div>
            @empty
            <p class="text-center text-gray-500 py-4">لا توجد أخطاء</p>
            @endforelse
        </div>
    </div>

    <!-- Slow Queries -->
    <div class="bg-gray-800 rounded-2xl p-6 shadow-sm border border-gray-700">
        <h3 class="text-lg font-bold text-white mb-4"><i class="fas fa-clock text-red-500 ml-2"></i>الاستعلامات البطيئة</h3>
        <div class="grid grid-cols-3 gap-3 mb-4">
            <div class="p-3 bg-blue-800 rounded-xl text-center border border-gray-600">
                <p class="text-xl font-bold text-white">{{ $queryStats['total'] }}</p>
                <p class="text-xs text-blue-200">الإجمالي</p>
            </div>
            <div class="p-3 bg-yellow-600 rounded-xl text-center border border-gray-600">
                <p class="text-xl font-bold text-white">{{ $queryStats['unoptimized'] }}</p>
                <p class="text-xs text-yellow-100">غير محسّن</p>
            </div>
            <div class="p-3 bg-red-800 rounded-xl text-center border border-gray-600">
                <p class="text-xl font-bold text-white">{{ $queryStats['critical'] }}</p>
                <p class="text-xs text-red-200">حرج</p>
            </div>
        </div>
        <div class="space-y-2 max-h-48 overflow-y-auto">
            @forelse($slowQueries as $query)
            <div class="flex items-center justify-between p-2 bg-gray-700 rounded-lg text-sm">
                <span class="text-gray-200">{{ Str::limit($query->table_name ?? $query->query, 25) }}</span>
                <div class="flex items-center gap-2">
                    <span class="text-xs text-gray-400">{{ number_format($query->execution_time, 0) }}ms</span>
                    <span class="px-2 py-1 text-xs rounded {{ $query->is_optimized ? 'bg-green-200 text-green-800' : 'bg-yellow-200 text-yellow-800' }}">
                        {{ $query->is_optimized ? 'محسّن' : 'معلق' }}
                    </span>
                </div>
            </div>
            @empty
            <p class="text-center text-gray-500 py-4">لا توجد استعلامات بطيئة</p>
            @endforelse
        </div>
    </div>
</div>

<!-- Recent Logins -->
<div class="bg-white rounded-2xl p-6 shadow-sm mb-8">
    <h3 class="text-lg font-bold text-gray-800 mb-4"><i class="fas fa-sign-in-alt text-green-500 ml-2"></i>آخر تسجيلات الدخول</h3>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="text-right text-xs text-gray-500 border-b">
                    <th class="pb-3">الموظف</th>
                    <th class="pb-3">البريد الإلكتروني</th>
                    <th class="pb-3">القسم</th>
                    <th class="pb-3">آخر دخول</th>
                </tr>
            </thead>
            <tbody class="text-sm">
                @forelse($recentLogins as $employee)
                <tr class="border-b border-gray-50 hover:bg-gray-50">
                    <td class="py-3">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-gradient-to-br from-purple-500 to-indigo-600 rounded-full flex items-center justify-center text-white text-xs font-bold">
                                {{ substr($employee->full_name ?? 'U', 0, 1) }}
                            </div>
                            <span>{{ $employee->full_name ?? 'N/A' }}</span>
                        </div>
                    </td>
                    <td class="py-3 text-gray-500">{{ $employee->email }}</td>
                    <td class="py-3">
                        <span class="px-2 py-1 text-xs bg-blue-100 text-blue-700 rounded-lg">{{ $employee->department ?? 'N/A' }}</span>
                    </td>
                    <td class="py-3 text-gray-500">{{ $employee->last_login_at?->diffForHumans() ?? 'N/A' }}</td>
                </tr>
                @empty
                <tr><td colspan="4" class="py-4 text-center text-gray-500">لا توجد تسجيلات دخول</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Charts Scripts -->
<script>
// Traffic Chart
const trafficCtx = document.getElementById('trafficChart').getContext('2d');
new Chart(trafficCtx, {
    type: 'line',
    data: {
        labels: {!! json_encode(array_column($dailyTraffic, 'date')) !!},
        datasets: [{
            label: 'المستخدمين الجدد',
            data: {!! json_encode(array_column($dailyTraffic, 'users')) !!},
            borderColor: '#6366F1',
            backgroundColor: 'rgba(99, 102, 241, 0.1)',
            fill: true,
            tension: 0.4
        }, {
            label: 'الطلبات',
            data: {!! json_encode(array_column($dailyTraffic, 'orders')) !!},
            borderColor: '#10B981',
            backgroundColor: 'rgba(16, 185, 129, 0.1)',
            fill: true,
            tension: 0.4
        }, {
            label: 'تسجيلات الدخول',
            data: {!! json_encode(array_column($dailyTraffic, 'logins')) !!},
            borderColor: '#F59E0B',
            backgroundColor: 'rgba(245, 158, 11, 0.1)',
            fill: true,
            tension: 0.4
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { position: 'bottom' } },
        scales: { y: { beginAtZero: true } }
    }
});

// Orders Status Chart
const ordersCtx = document.getElementById('ordersChart').getContext('2d');
const statusLabels = {
    'pending': 'قيد الانتظار',
    'processing': 'قيد المعالجة',
    'shipped': 'تم الشحن',
    'delivered': 'تم التسليم',
    'cancelled': 'ملغي'
};
const statusColors = {
    'pending': '#F59E0B',
    'processing': '#3B82F6',
    'shipped': '#8B5CF6',
    'delivered': '#10B981',
    'cancelled': '#EF4444'
};
const orderData = @json($ordersByStatus);
new Chart(ordersCtx, {
    type: 'doughnut',
    data: {
        labels: orderData.map(item => statusLabels[item.status] || item.status),
        datasets: [{
            data: orderData.map(item => item.count),
            backgroundColor: orderData.map(item => statusColors[item.status] || '#6B7280'),
            borderWidth: 0
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { position: 'bottom' } }
    }
});
</script>
@endsection
