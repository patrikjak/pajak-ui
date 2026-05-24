@use('Pajak\Ui\Common\Enums\Email\EmailAccent')

<div {{ $attributes->merge(['class' => 'pajak-email-infocard__row']) }}>
    <span class="pajak-email-infocard__label">{{ $label }}</span>
    <span @class(['pajak-email-infocard__value', 'pajak-email-infocard__value--blue' => $accent === EmailAccent::Blue, 'pajak-email-infocard__value--green' => $accent === EmailAccent::Green])>{{ $value }}</span>
</div>
