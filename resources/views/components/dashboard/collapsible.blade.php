@props(['title', 'icon' => null, 'open' => false, 'subtitle' => null])

<details class="db4-collapse" @if($open) open @endif>
    <summary class="db4-collapse-summary">
        <div class="db4-collapse-title">
            @if($icon)
                <span class="db4-collapse-icon" aria-hidden="true"><i class="{{ $icon }}"></i></span>
            @endif
            <div class="min-w-0">
                <div class="db4-collapse-heading">{{ $title }}</div>
                @if($subtitle)
                    <div class="db4-collapse-subtitle">{{ $subtitle }}</div>
                @endif
            </div>
        </div>
        <span class="db4-collapse-chevron" aria-hidden="true"><i class="fas fa-chevron-down"></i></span>
    </summary>
    <div class="db4-collapse-body">
        {{ $slot }}
    </div>
</details>

