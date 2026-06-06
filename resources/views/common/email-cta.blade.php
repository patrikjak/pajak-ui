@php
    $ctaStyle = $secondary
        ? 'color: #5386E4; border: 1.5px solid #5386E4;'
        : sprintf('background: %s; color: #fff;', $color ?? '#5386E4');
@endphp

<div {{ $attributes->merge(['class' => 'pajak-email-cta']) }}>
    <a
        href="{{ $href }}"
        @class(['pajak-email-cta__btn', 'pajak-email-cta__btn--secondary' => $secondary])
        style="{{ $ctaStyle }}"
    >
        {{ $slot }}
    </a>
</div>
