@props(['title', 'icon' => 'fas fa-chart-bar', 'chartId'])

<div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-5">
    <div class="flex items-center justify-between mb-4">
        <h3 class="font-bold text-gray-800 dark:text-white flex items-center gap-2">
            <i class="{{ $icon }} text-primary-500"></i>
            {{ $title }}
        </h3>
        {{ $actions ?? '' }}
    </div>
    <div class="relative" style="height: 300px;">
        <canvas id="{{ $chartId }}"></canvas>
    </div>
</div>
