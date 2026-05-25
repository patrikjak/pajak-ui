@use('Pajak\Ui\Common\Enums\Card\CardVariant')

<div {{ $attributes->merge(['class' => 'pajak-card'])->class(['pajak-card--accent' => $variant === CardVariant::Accent]) }}>
    @isset($kicker)
        <div class="pajak-card__kicker">{{ $kicker }}</div>
    @endisset
    @isset($title)
        <div class="pajak-card__title">{{ $title }}</div>
    @endisset
    {{ $slot }}
    @isset($footer)
        <div class="pajak-card__footer">{{ $footer }}</div>
    @endisset
</div>
