<button
    {{ $attributes->merge(['class' => 'pajak-seg__opt'])->class(['pajak-seg__opt--icon-only' => $label === null]) }}
    role="tab"
    aria-selected="{{ $active ? 'true' : 'false' }}"
    @if($disabled) disabled @endif
    @isset($value) data-value="{{ $value }}" @endisset
    type="button"
>
    @isset($icon)
        <span class="pajak-seg__icon" aria-hidden="true">{{ $icon }}</span>
    @endisset

    @isset($label)
        {{ $label }}
    @endisset
</button>
