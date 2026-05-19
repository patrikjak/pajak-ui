<div {{ $attributes->merge(['class' => 'pajak-acc__item']) }} @if($open) open @endif>
    <button class="pajak-acc__header" aria-expanded="{{ $open ? 'true' : 'false' }}" type="button">
        @isset($icon)
            <span class="pajak-acc__lead-icon">{{ $icon }}</span>
        @endisset
        <span class="pajak-acc__title-stack">
            <span>{{ $title }}</span>
            @isset($subtitle)
                <span class="pajak-acc__subtitle">{{ $subtitle }}</span>
            @endisset
        </span>
        @isset($badge)
            <span class="pajak-acc__badge">{{ $badge }}</span>
        @endisset
        <svg class="pajak-acc__chev" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="6 9 12 15 18 9"/></svg>
        <span class="pajak-acc__plus" aria-hidden="true"></span>
    </button>
    <div class="pajak-acc__panel">
        <div class="pajak-acc__panel-inner">
            {{ $slot }}
        </div>
    </div>
</div>
