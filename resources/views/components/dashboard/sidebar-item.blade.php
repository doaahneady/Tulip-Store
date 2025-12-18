@props(['href', 'icon', 'label', 'active' => false, 'badge' => null])

<a href="{{ $href }}" class="flex items-center justify-between px-3 py-2.5 rounded-xl transition-all {{ $active ? 'bg-primary-50 dark:bg-primary-900/30 text-primary-600 dark:text-primary-400 font-semibold' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
    <div class="flex items-center gap-3">
        <i class="{{ $icon }} w-5 {{ $active ? 'text-primary-500' : '' }}"></i>
        <span class="text-sm">{{ $label }}</span>
    </div>
    @if($badge)
        <span class="px-2 py-0.5 text-xs font-bold rounded-full bg-red-500 text-white">{{ $badge }}</span>
    @endif
</a>
