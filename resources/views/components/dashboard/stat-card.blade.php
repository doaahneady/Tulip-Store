@props(['title', 'value', 'icon', 'color' => 'primary', 'change' => null, 'changeType' => 'up', 'subtitle' => null])

@php
$colors = [
    'primary' => 'from-primary-500 to-primary-700',
    'green' => 'from-emerald-500 to-emerald-700',
    'blue' => 'from-blue-500 to-blue-700',
    'orange' => 'from-orange-500 to-orange-700',
    'purple' => 'from-purple-500 to-purple-700',
    'pink' => 'from-pink-500 to-pink-700',
    'teal' => 'from-teal-500 to-teal-700',
    'red' => 'from-red-500 to-red-700',
];
$gradient = $colors[$color] ?? $colors['primary'];
$changeDisplay = null;
if ($change !== null) {
    if (is_array($change)) {
        $changeDisplay = $change['value'] ?? null;
        if (is_array($changeDisplay)) {
            $changeDisplay = null;
        }
    } else {
        $changeDisplay = $change;
    }
    if (is_string($changeDisplay) && trim($changeDisplay) === '0.0%') {
        $changeDisplay = null;
    }
}
@endphp

<div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm hover:shadow-lg transition-all duration-300 p-5 border border-gray-100 dark:border-gray-700 group">
    <div class="flex items-start justify-between">
        <div class="flex-1">
            <p class="text-gray-500 dark:text-gray-400 text-sm font-medium mb-1">{{ $title }}</p>
            <h3 class="text-2xl lg:text-3xl font-bold text-gray-800 dark:text-white">{{ $value }}</h3>
            @if($subtitle)
                <p class="text-gray-400 text-xs mt-1">{{ $subtitle }}</p>
            @endif
            @if($changeDisplay !== null && $changeDisplay !== '')
                <div class="flex items-center gap-1 mt-2">
                    @if($changeType === 'up')
                        <span class="text-emerald-500 text-sm font-medium flex items-center gap-1">
                            <i class="fas fa-arrow-up text-xs"></i> {{ $changeDisplay }}
                        </span>
                    @elseif($changeType === 'down')
                        <span class="text-red-500 text-sm font-medium flex items-center gap-1">
                            <i class="fas fa-arrow-down text-xs"></i> {{ $changeDisplay }}
                        </span>
                    @else
                        <span class="text-gray-400 text-sm font-medium">{{ $changeDisplay }}</span>
                    @endif
                </div>
            @endif
        </div>
        <div class="w-14 h-14 rounded-2xl bg-gradient-to-br {{ $gradient }} flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
            <i class="{{ $icon }} text-white text-xl"></i>
        </div>
    </div>
</div>
