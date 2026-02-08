@extends('layouts.dashboard')

@section('title', 'لوحات التحكم')
@section('page-title', 'مركز لوحات التحكم')
@section('role-gradient', 'from-gray-600 to-gray-800')
@section('role-icon', 'fas fa-th-large')
@section('role-label', 'المستخدم')

@section('sidebar-menu')
    @include('components.dashboard.sidebar-item', ['href' => '/dashboards', 'icon' => 'fas fa-th-large', 'label' => 'جميع اللوحات', 'active' => true])
@endsection

@section('content')
<div class="text-center mb-8">
    <h1 class="text-3xl font-bold text-gray-800 dark:text-white mb-2">مرحباً، {{ auth()->user()->name ?? 'مستخدم' }}!</h1>
    <p class="text-gray-500 dark:text-gray-400">اختر لوحة التحكم التي تريد الوصول إليها</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
    @php
    $user = auth()->user();
    $dashboards = [
        ['route' => 'admin.dashboard', 'icon' => 'fas fa-crown', 'label' => 'لوحة الإدارة', 'desc' => 'إدارة كاملة للنظام', 'color' => 'indigo', 'check' => $user->is_admin ?? false],
        ['route' => 'it.dashboard', 'icon' => 'fas fa-server', 'label' => 'تقنية المعلومات', 'desc' => 'مراقبة النظام والأداء', 'color' => 'emerald', 'check' => ($user->is_it ?? false) || ($user->is_it_super ?? false)],
        ['route' => 'hr.dashboard', 'icon' => 'fas fa-user-tie', 'label' => 'الموارد البشرية', 'desc' => 'إدارة الموظفين والحضور', 'color' => 'pink', 'check' => ($user->is_hr ?? false) || ($user->is_admin ?? false)],
        ['route' => 'cs.dashboard', 'icon' => 'fas fa-headset', 'label' => 'خدمة العملاء', 'desc' => 'التذاكر والدعم الفني', 'color' => 'amber', 'check' => ($user->is_cs ?? false) || ($user->is_admin ?? false)],
        ['route' => 'finance.dashboard', 'icon' => 'fas fa-coins', 'label' => 'الإدارة المالية', 'desc' => 'الإيرادات والمصروفات', 'color' => 'teal', 'check' => ($user->is_finance ?? false) || ($user->is_admin ?? false)],
        ['route' => 'accounting.dashboard', 'icon' => 'fas fa-calculator', 'label' => 'المحاسبة', 'desc' => 'القيود والتقارير المالية', 'color' => 'cyan', 'check' => ($user->is_accountant ?? false) || ($user->is_admin ?? false)],
        ['route' => 'delivery.supervisor.dashboard', 'icon' => 'fas fa-truck', 'label' => 'إدارة التوصيل', 'desc' => 'تتبع السائقين والطلبات', 'color' => 'orange', 'check' => ($user->is_driver_supervisor ?? false) || ($user->is_admin ?? false)],
    ];
    @endphp

    @foreach($dashboards as $dashboard)
        @if($dashboard['check'])
        <a href="{{ route($dashboard['route']) }}" class="group">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 hover:shadow-xl transition-all duration-300 hover:-translate-y-2">
                <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-{{ $dashboard['color'] }}-500 to-{{ $dashboard['color'] }}-700 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                    <i class="{{ $dashboard['icon'] }} text-white text-2xl"></i>
                </div>
                <h3 class="font-bold text-gray-800 dark:text-white text-lg mb-1">{{ $dashboard['label'] }}</h3>
                <p class="text-gray-500 dark:text-gray-400 text-sm">{{ $dashboard['desc'] }}</p>
            </div>
        </a>
        @endif
    @endforeach
</div>

@if(!($user->is_admin ?? false) && !($user->is_it ?? false) && !($user->is_hr ?? false) && !($user->is_cs ?? false) && !($user->is_finance ?? false) && !($user->is_accountant ?? false) && !($user->is_driver_supervisor ?? false))
<div class="text-center py-12">
    <div class="w-24 h-24 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center mx-auto mb-4">
        <i class="fas fa-lock text-gray-400 text-4xl"></i>
    </div>
    <h3 class="text-xl font-bold text-gray-800 dark:text-white mb-2">لا توجد لوحات تحكم متاحة</h3>
    <p class="text-gray-500 dark:text-gray-400">تواصل مع المسؤول للحصول على صلاحيات الوصول</p>
</div>
@endif
@endsection
