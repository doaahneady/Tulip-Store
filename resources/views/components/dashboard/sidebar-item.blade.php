@props(['href', 'icon', 'label', 'active' => false, 'badge' => null])

<a href="{{ $href }}" class="db4-nav-link {{ $active ? 'is-active' : '' }}">
    <span class="db4-nav-icon" aria-hidden="true"><i class="{{ $icon }}"></i></span>
    <span class="db4-nav-meta">
        <span class="db4-nav-label">{{ $label }}</span>
        @if($badge)
            <span class="db4-nav-hint">{{ $badge }}</span>
        @endif
    </span>
</a>
