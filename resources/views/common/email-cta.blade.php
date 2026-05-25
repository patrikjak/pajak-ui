<div {{ $attributes->merge(['class' => 'pajak-email-cta']) }}>
    <a
        href="{{ $href }}"
        @class(['pajak-email-cta__btn', 'pajak-email-cta__btn--secondary' => $secondary])
        @if(!$secondary && $color) style="background: {{ $color }}" @endif
    >
        {{ $slot }}
    </a>
</div>
