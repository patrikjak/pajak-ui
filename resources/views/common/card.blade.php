@use('Pajak\Ui\Common\Enums\Card\CardIconVariant')
@use('Pajak\Ui\Common\Enums\Card\CardVariant')

@php($iconVariant = isset($icon) ? (CardIconVariant::tryFrom((string) $icon->attributes->get('variant')) ?? CardIconVariant::Primary) : null)

<div {{ $attributes->merge(['class' => 'pajak-card'])->class([
    'pajak-card--accent' => $variant === CardVariant::Accent,
    'pajak-card--static' => !$interactive,
    'pajak-card--icon-header' => $iconVariant !== null,
]) }}>
    @if($iconVariant !== null)
        <div class="pajak-card__header">
            <div class="pajak-card__icon pajak-card__icon--{{ $iconVariant->value }}" aria-hidden="true">{{ $icon }}</div>
            <div class="pajak-card__heading">
                @isset($title)
                    <div class="pajak-card__title">{{ $title }}</div>
                @endisset
                @isset($subtitle)
                    <div class="pajak-card__subtitle">{{ $subtitle }}</div>
                @endisset
            </div>
        </div>
        <div class="pajak-card__body">{{ $slot }}</div>
    @else
        @isset($kicker)
            <div class="pajak-card__kicker">{{ $kicker }}</div>
        @endisset
        @isset($title)
            <div class="pajak-card__title">{{ $title }}</div>
        @endisset
        @isset($subtitle)
            <div class="pajak-card__subtitle">{{ $subtitle }}</div>
        @endisset
        {{ $slot }}
    @endif
    @isset($footer)
        <div class="pajak-card__footer">{{ $footer }}</div>
    @endisset
</div>
