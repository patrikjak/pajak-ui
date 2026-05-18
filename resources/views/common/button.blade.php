@use('Pajak\Ui\Common\Enums\Size')
<button
    {{ $attributes->merge(['class' => 'pajak-btn', 'type' => $type])->class(["pajak-btn--$size->value", "pajak-btn--$variant->value", 'is-loading' => $loading]) }}
    @if($disabled) disabled @endif
>
    @if($loading)
        <svg class="pajak-btn__spinner" viewBox="0 0 24 24" fill="none" stroke-linecap="round"
             stroke-width="{{ match($size) { Size::Lg => '2.5', Size::Sm => '3', default => '2.75' } }}">
            <circle class="track" cx="12" cy="12" r="9"/>
            <circle class="head" cx="12" cy="12" r="9" stroke-dasharray="56.5" stroke-dashoffset="38"/>
        </svg>
    @endif
    <span class="pajak-btn__label">{{ $slot }}</span>
</button>
