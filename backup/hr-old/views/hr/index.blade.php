@extends('layouts.dashboard')

@section('title', 'HR Dashboard - People, Time & Compensation')

@section('content')
<div class="p-6 space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">لوحة الموارد البشرية</h1>
            <p class="text-sm text-gray-500">إدارة شاملة للموظفين، الوقت، والتعويضات</p>
        </div>
        <div class="flex items-center gap-2">
            <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-sm">{{ $metrics['active_employees'] ?? 0 }} نشط</span>
            <span class="px-3 py-1 bg-red-100 text-red-700 rounded-full text-sm">{{ $metrics['total_employees'] - $metrics['active_employees'] ?? 0 }} غير نشط</span>
        </div>
    </div>

    <!-- Key Metrics -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">إجمالي الموظفين</p>
                    <p class="text-2xl font-bold text-gray-800">{{ number_format($metrics['total_employees'] ?? 0) }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-users text-blue-600"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">الحضور اليوم</p>
                    <p class="text-2xl font-bold text-gray-800">{{ number_format($metrics['today_attendance'] ?? 0) }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-clock text-green-600"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">طلبات الإجازة المعلقة</p>
                    <p class="text-2xl font-bold text-gray-800">{{ number_format($metrics['pending_leaves'] ?? 0) }}</p>
                </div>
                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-calendar-check text-yellow-600"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">الرواتب المعلقة</p>
                    <p class="text-2xl font-bold text-gray-800">{{ number_format($metrics['pending_payroll'] ?? 0) }}</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-money-bill-wave text-purple-600"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Quick Actions -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">إجراءات سريعة</h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <a href="{{ route('dashboard.hr.create-employee') }}" class="flex flex-col items-center p-4 bg-blue-50 rounded-lg hover:bg-blue-100 transition">
                        <i class="fas fa-user-plus text-blue-600 text-xl mb-2"></i>
                        <span class="text-sm text-blue-700">إضافة موظف</span>
                    </a>
                    <a href="{{ route('dashboard.hr.attendance') }}" class="flex flex-col items-center p-4 bg-green-50 rounded-lg hover:bg-green-100 transition">
                        <i class="fas fa-clock text-green-600 text-xl mb-2"></i>
                        <span class="text-sm text-green-700">الحضور</span>
                    </a>
                    <a href="{{ route('dashboard.hr.leaves') }}" class="flex flex-col items-center p-4 bg-yellow-50 rounded-lg hover:bg-yellow-100 transition">
                        <i class="fas fa-calendar-check text-yellow-600 text-xl mb-2"></i>
                        <span class="text-sm text-yellow-700">الإجازات</span>
                    </a>
                    <a href="{{ route('dashboard.hr.payroll') }}" class="flex flex-col items-center p-4 bg-purple-50 rounded-lg hover:bg-purple-100 transition">
                        <i class="fas fa-money-bill-wave text-purple-600 text-xl mb-2"></i>
                        <span class="text-sm text-purple-700">الرواتب</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Recent Activities -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">الأنشطة الأخيرة</h3>
            <div class="space-y-3 max-h-64 overflow-y-auto">
                @forelse($recent_activities as $activity)
                    <div class="flex items-start gap-3 p-3 bg-gray-50 rounded-lg">
                        <div class="w-8 h-8 bg-gray-200 rounded-full flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-user text-gray-600 text-sm"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm text-gray-800">{{ $activity->employee->user->name ?? 'نظام' }}</p>
                            <p class="text-xs text-gray-500">{{ $activity->details }}</p>
                            <p class="text-xs text-gray-400">{{ $activity->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-gray-500 text-center">لا توجد أنشطة حديثة</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Upcoming Leaves & Announcements -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Upcoming Leaves -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-800">الإجازات القادمة</h3>
                <a href="{{ route('dashboard.hr.leaves') }}" class="text-sm text-blue-600 hover:text-blue-800">عرض الكل</a>
            </div>
            <div class="space-y-3 max-h-64 overflow-y-auto">
                @forelse($upcoming_leaves as $leave)
                    <div class="flex items-center justify-between p-3 bg-yellow-50 rounded-lg">
                        <div>
                            <p class="text-sm font-medium text-gray-800">{{ $leave->employee->user->name }}</p>
                            <p class="text-xs text-gray-500">{{ $leave->leave_type }} - {{ $leave->days }} أيام</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-gray-700">{{ $leave->start_date->format('Y-m-d') }}</p>
                            <p class="text-xs text-gray-500">{{ $leave->start_date->diffForHumans() }}</p>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-gray-500 text-center">لا توجد إجازات قادمة</p>
                @endforelse
            </div>
        </div>

        <!-- Recent Announcements -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-800">الإعلانات</h3>
                <a href="{{ route('dashboard.hr.announcements') }}" class="text-sm text-blue-600 hover:text-blue-800">عرض الكل</a>
            </div>
            <div class="space-y-3 max-h-64 overflow-y-auto">
                @forelse(\App\Models\HRAnnouncement::where('is_active', true)->latest()->limit(5)->get() as $announcement)
                    <div class="p-3 bg-blue-50 rounded-lg">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-800">{{ $announcement->title }}</p>
                                <p class="text-xs text-gray-600 mt-1">{{ Str::limit($announcement->content, 100) }}</p>
                                <p class="text-xs text-gray-400 mt-2">{{ $announcement->created_at->diffForHumans() }}</p>
                            </div>
                            <span class="px-2 py-1 text-xs rounded-full {{ $announcement->type == 'urgent' ? 'bg-red-100 text-red-700' : ($announcement->type == 'policy' ? 'bg-yellow-100 text-yellow-700' : 'bg-blue-100 text-blue-700') }}">
                                {{ $announcement->type }}
                            </span>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-gray-500 text-center">لا توجد إعلانات</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Navigation Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition">
            <div class="flex items-center mb-4">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-users text-blue-600 text-xl"></i>
                </div>
                <div class="mr-4">
                    <h4 class="font-semibold text-gray-800">إدارة الموظفين</h4>
                    <p class="text-sm text-gray-500">ملفات الموظفين والعقود</p>
                </div>
            </div>
            <div class="space-y-2">
                <a href="{{ route('dashboard.hr.employees') }}" class="block text-sm text-blue-600 hover:text-blue-800">قائمة الموظفين</a>
                <a href="{{ route('dashboard.hr.create-employee') }}" class="block text-sm text-blue-600 hover:text-blue-800">إضافة موظف جديد</a>
                <a href="{{ route('dashboard.hr.performance-reviews') }}" class="block text-sm text-blue-600 hover:text-blue-800">تقييم الأداء</a>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition">
            <div class="flex items-center mb-4">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-clock text-green-600 text-xl"></i>
                </div>
                <div class="mr-4">
                    <h4 class="font-semibold text-gray-800">الحضور والجدولة</h4>
                    <p class="text-sm text-gray-500">تتبع الوقت وإدارة الورديات</p>
                </div>
            </div>
            <div class="space-y-2">
                <a href="{{ route('dashboard.hr.attendance') }}" class="block text-sm text-green-600 hover:text-green-800">سجل الحضور</a>
                <a href="{{ route('dashboard.hr.shifts') }}" class="block text-sm text-green-600 hover:text-green-800">إدارة الورديات</a>
                <a href="{{ route('dashboard.hr.reports', ['type' => 'attendance']) }}" class="block text-sm text-green-600 hover:text-green-800">تقارير الحضور</a>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition">
            <div class="flex items-center mb-4">
                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-calendar-check text-yellow-600 text-xl"></i>
                </div>
                <div class="mr-4">
                    <h4 class="font-semibold text-gray-800">إدارة الإجازات</h4>
                    <p class="text-sm text-gray-500">طلبات وموافقات الإجازات</p>
                </div>
            </div>
            <div class="space-y-2">
                <a href="{{ route('dashboard.hr.leaves') }}" class="block text-sm text-yellow-600 hover:text-yellow-800">طلبات الإجازة</a>
                <a href="{{ route('dashboard.hr.leaves', ['status' => 'pending']) }}" class="block text-sm text-yellow-600 hover:text-yellow-800">الإجازات المعلقة</a>
                <a href="{{ route('dashboard.hr.reports', ['type' => 'leave']) }}" class="block text-sm text-yellow-600 hover:text-yellow-800">تقارير الإجازات</a>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition">
            <div class="flex items-center mb-4">
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-money-bill-wave text-purple-600 text-xl"></i>
                </div>
                <div class="mr-4">
                    <h4 class="font-semibold text-gray-800">الرواتب والتعويضات</h4>
                    <p class="text-sm text-gray-500">إعداد الرواتب والحسابات</p>
                </div>
            </div>
            <div class="space-y-2">
                <a href="{{ route('dashboard.hr.payroll') }}" class="block text-sm text-purple-600 hover:text-purple-800">سجلات الرواتب</a>
                <a href="{{ route('dashboard.hr.payroll', ['status' => 'draft']) }}" class="block text-sm text-purple-600 hover:text-purple-800">الرواتب المعلقة</a>
                <a href="{{ route('dashboard.hr.reports', ['type' => 'payroll']) }}" class="block text-sm text-purple-600 hover:text-purple-800">تقارير الرواتب</a>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition">
            <div class="flex items-center mb-4">
                <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-bullhorn text-indigo-600 text-xl"></i>
                </div>
                <div class="mr-4">
                    <h4 class="font-semibold text-gray-800">الإعلانات والتواصل</h4>
                    <p class="text-sm text-gray-500">الإعلانات الداخلية</p>
                </div>
            </div>
            <div class="space-y-2">
                <a href="{{ route('dashboard.hr.announcements') }}" class="block text-sm text-indigo-600 hover:text-indigo-800">الإعلانات</a>
                <a href="{{ route('dashboard.hr.create-announcement') }}" class="block text-sm text-indigo-600 hover:text-indigo-800">إنشاء إعلان</a>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition">
            <div class="flex items-center mb-4">
                <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-history text-red-600 text-xl"></i>
                </div>
                <div class="mr-4">
                    <h4 class="font-semibold text-gray-800">المراقبة والتقارير</h4>
                    <p class="text-sm text-gray-500">سجلات التدقيق والتقارير</p>
                </div>
            </div>
            <div class="space-y-2">
                <a href="{{ route('dashboard.hr.audit-logs') }}" class="block text-sm text-red-600 hover:text-red-800">سجلات التدقيق</a>
                <a href="{{ route('dashboard.hr.reports') }}" class="block text-sm text-red-600 hover:text-red-800">التقارير والتحليلات</a>
            </div>
        </div>
    </div>
</div>
@endsection