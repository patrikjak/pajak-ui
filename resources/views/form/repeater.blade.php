<div
    class="pajak-repeater"
    data-pajak-repeater
    data-name="{{ $name }}"
    data-min="{{ $min }}"
    @isset($max) data-max="{{ $max }}" @endisset
>
    @isset($label)
        <div class="pajak-repeater__label">{{ $label }}</div>
    @endisset

    <div class="pajak-repeater__rows">
        @for($i = 0; $i < $count; $i++)
            <div class="pajak-repeater__row">
                {!! $replaceIndex((string) $slot, $i) !!}
                <button
                    type="button"
                    class="pajak-repeater__remove"
                    aria-label="{{ $resolvedRemoveLabel() }}"
                    @if($count <= $min) disabled aria-disabled="true" @endif
                >
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                    </svg>
                    <span class="pajak-repeater__remove-label">{{ $resolvedRemoveLabel() }}</span>
                </button>
            </div>
        @endfor
    </div>

    <template class="pajak-repeater__template">
        <div class="pajak-repeater__row">
            {!! $slot !!}
            <button
                type="button"
                class="pajak-repeater__remove"
                aria-label="{{ $resolvedRemoveLabel() }}"
            >
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
                <span class="pajak-repeater__remove-label">{{ $resolvedRemoveLabel() }}</span>
            </button>
        </div>
    </template>

    <button
        type="button"
        class="pajak-repeater__add"
        @if($max !== null && $count >= $max) hidden @endif
    >
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
        </svg>
        {{ $resolvedAddLabel() }}
    </button>
</div>
