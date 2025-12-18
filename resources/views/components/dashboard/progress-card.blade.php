@props(['title', 'value', 'max', 'color' => 'primary', 'icon' => 'fas fa-chart-line', 'suffix' => ''])

@php
$percentage = $max > 0 ? min(($value / $max) * 100, 100) : 0;
$colors = [
    'primary' => 'from-primary-500 to-primary-600',
    'green' => 'from-emerald-500 to-emerald-600',
    'blue' => 'from-blue-500 to-blue-600',
    'orange' => 'from-orange-500 to-orange-600',
    'purple' => 'from-purple-500 to-purple-600',
];
$gradient = $colors[$color] ?? $colors['primary'];
@endphp

<div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-5">
    <div class="flex items-center justify-between mb-3">
        <div class="flex items-center gap-2">
            <i class="{{ $icon }} text-primary-500"></i>
            <span class="font-semibold text-gray-700 dark:text-gray-200 text-sm">{{ $title }}</span>
        </div>
        <span class="font-bold text-primary-600 dark:text-primary-400">{{ round($percentage, 1) }}%</span>
    </div>
    <div class="h-3 bg-gray-100 dark:bg-gray-700 rounded-full overflow-hidden">
        <div class="h-full bg-gradient-to-r {{ $gradient }} rounded-full transition-all duration-500" style="width: {{ $percentage }}%"></div>
    </div>
    <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
        {{ number_format($value) }}{{ $suffix }} من {{ number_format($max) }}{{ $suffix }}
    </p>
</div>
