@props(['type' => 'info', 'title', 'message', 'icon' => null])

@php
$styles = [
    'info' => ['bg' => 'bg-blue-50 dark:bg-blue-900/20', 'icon-bg' => 'bg-blue-500', 'text' => 'text-blue-800 dark:text-blue-200', 'default-icon' => 'fas fa-info-circle'],
    'success' => ['bg' => 'bg-emerald-50 dark:bg-emerald-900/20', 'icon-bg' => 'bg-emerald-500', 'text' => 'text-emerald-800 dark:text-emerald-200', 'default-icon' => 'fas fa-check-circle'],
    'warning' => ['bg' => 'bg-amber-50 dark:bg-amber-900/20', 'icon-bg' => 'bg-amber-500', 'text' => 'text-amber-800 dark:text-amber-200', 'default-icon' => 'fas fa-exclamation-triangle'],
    'danger' => ['bg' => 'bg-red-50 dark:bg-red-900/20', 'icon-bg' => 'bg-red-500', 'text' => 'text-red-800 dark:text-red-200', 'default-icon' => 'fas fa-times-circle'],
];
$style = $styles[$type] ?? $styles['info'];
$iconClass = $icon ?? $style['default-icon'];
@endphp

<div class="flex items-center gap-4 p-4 rounded-xl {{ $style['bg'] }} transition-all hover:scale-[1.02]">
    <div class="w-11 h-11 rounded-xl {{ $style['icon-bg'] }} flex items-center justify-center flex-shrink-0">
        <i class="{{ $iconClass }} text-white"></i>
    </div>
    <div class="flex-1 min-w-0">
        <h4 class="font-bold {{ $style['text'] }} text-sm">{{ $title }}</h4>
        <p class="text-sm {{ $style['text'] }} opacity-80 truncate">{{ $message }}</p>
    </div>
</div>
