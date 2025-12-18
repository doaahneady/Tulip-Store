@props(['href', 'icon', 'label', 'color' => 'primary'])

@php
$colors = [
    'primary' => 'from-primary-500 to-primary-700 hover:from-primary-600 hover:to-primary-800',
    'green' => 'from-emerald-500 to-emerald-700 hover:from-emerald-600 hover:to-emerald-800',
    'blue' => 'from-blue-500 to-blue-700 hover:from-blue-600 hover:to-blue-800',
    'orange' => 'from-orange-500 to-orange-700 hover:from-orange-600 hover:to-orange-800',
    'purple' => 'from-purple-500 to-purple-700 hover:from-purple-600 hover:to-purple-800',
    'pink' => 'from-pink-500 to-pink-700 hover:from-pink-600 hover:to-pink-800',
    'teal' => 'from-teal-500 to-teal-700 hover:from-teal-600 hover:to-teal-800',
];
$gradient = $colors[$color] ?? $colors['primary'];
@endphp

<a href="{{ $href }}" class="flex flex-col items-center gap-3 p-5 rounded-2xl bg-gradient-to-br {{ $gradient }} text-white shadow-lg hover:shadow-xl transition-all hover:-translate-y-1 group">
    <div class="w-12 h-12 rounded-xl bg-white/20 flex items-center justify-center group-hover:scale-110 transition-transform">
        <i class="{{ $icon }} text-xl"></i>
    </div>
    <span class="font-semibold text-sm text-center">{{ $label }}</span>
</a>
