<button
    {{ $attributes->merge(['class' => 'pajak-tab']) }}
    role="tab"
    aria-selected="{{ $active ? 'true' : 'false' }}"
    @if($disabled) disabled @endif
    type="button"
>
    @isset($icon)
        <span class="pajak-tab__icon" aria-hidden="true">{{ $icon }}</span>
    @endisset
    {{ $label }}
    @isset($count)
        <span class="pajak-tab__count">{{ $count }}</span>
    @endisset
</button>
