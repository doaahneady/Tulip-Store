@php
$user = auth()->user();
$dashboards = [];

// Admin Dashboard
if ($user->is_admin ?? false) {
    $dashboards[] = ['route' => 'admin.dashboard', 'icon' => 'fas fa-crown', 'label' => 'الإدارة', 'color' => 'indigo'];
}

// IT Dashboard
if (($user->is_it ?? false) || ($user->is_it_super ?? false)) {
    $dashboards[] = ['route' => 'it.dashboard', 'icon' => 'fas fa-server', 'label' => 'تقنية المعلومات', 'color' => 'emerald'];
}

// HR Dashboard
if ($user->is_hr ?? false || $user->is_admin ?? false) {
    $dashboards[] = ['route' => 'hr.dashboard', 'icon' => 'fas fa-user-tie', 'label' => 'الموارد البشرية', 'color' => 'pink'];
}

// Customer Service Dashboard
if ($user->is_cs ?? false || $user->is_admin ?? false) {
    $dashboards[] = ['route' => 'cs.dashboard', 'icon' => 'fas fa-headset', 'label' => 'خدمة العملاء', 'color' => 'amber'];
}

// Finance Dashboard
if ($user->is_finance ?? false || $user->is_accountant ?? false || $user->is_admin ?? false) {
    $dashboards[] = ['route' => 'finance.dashboard', 'icon' => 'fas fa-coins', 'label' => 'المالية', 'color' => 'teal'];
}

// Accounting Dashboard
if ($user->is_accountant ?? false || $user->is_admin ?? false) {
    $dashboards[] = ['route' => 'accounting.dashboard', 'icon' => 'fas fa-calculator', 'label' => 'المحاسبة', 'color' => 'cyan'];
}

// Delivery Supervisor Dashboard
if ($user->is_driver_supervisor ?? false || $user->is_admin ?? false) {
    $dashboards[] = ['route' => 'delivery.supervisor.dashboard', 'icon' => 'fas fa-truck', 'label' => 'التوصيل', 'color' => 'orange'];
}

// Store Owner Dashboard
if ($user->is_trader ?? false) {
    $dashboards[] = ['route' => 'store-owner.dashboard', 'icon' => 'fas fa-store', 'label' => 'متجري', 'color' => 'purple'];
}
@endphp

@if(count($dashboards) > 1)
<div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-4 mb-6">
    <p class="text-xs font-semibold text-gray-400 uppercase mb-3">لوحات التحكم المتاحة</p>
    <div class="flex flex-wrap gap-2">
        @foreach($dashboards as $dashboard)
            <a href="{{ route($dashboard['route']) }}" 
               class="flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-medium transition
                      {{ request()->routeIs($dashboard['route'].'*') 
                         ? 'bg-'.$dashboard['color'].'-500 text-white' 
                         : 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600' }}">
                <i class="{{ $dashboard['icon'] }}"></i>
                {{ $dashboard['label'] }}
            </a>
        @endforeach
    </div>
</div>
@endif
