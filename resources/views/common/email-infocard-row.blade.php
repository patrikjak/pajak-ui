@use('Pajak\Ui\Common\Enums\Email\EmailAccent')

@php
    $valueColor = match($accent) {
        EmailAccent::Blue => '#5386E4',
        EmailAccent::Green => '#27AE60',
        null => '#1A1F2E',
    };
@endphp

<div {{ $attributes->merge(['class' => 'pajak-email-infocard__row']) }} style="display: flex; justify-content: space-between; align-items: baseline; padding: 8px 0; border-bottom: 1px solid #EEF2FB; font-size: 14px;">
    <span class="pajak-email-infocard__label" style="color: #7F93B5; font-weight: 400;">{{ $label }}</span>
    <span @class(['pajak-email-infocard__value', 'pajak-email-infocard__value--blue' => $accent === EmailAccent::Blue, 'pajak-email-infocard__value--green' => $accent === EmailAccent::Green]) style="color: {{ $valueColor }}; font-weight: 600;">{{ $value }}</span>
</div>
