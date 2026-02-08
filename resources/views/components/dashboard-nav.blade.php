@php
$user = auth()->user();
$dashboards = [];

// Admin Dashboard
if ($user->is_admin ?? false) {
    $dashboards[] = ['route' => 'admin.dashboard', 'icon' => 'fas fa-crown', 'label' => 'الإدارة', 'color' => 'indigo'];
}

// Note: Store Owner (Trader) dashboard is now accessed via separate trader login at /trader/login
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
