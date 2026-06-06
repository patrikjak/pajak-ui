<div {{ $attributes->merge(['class' => 'pajak-stat-card']) }}>
    <div class="pajak-stat-card__header">
        <div class="pajak-stat-card__meta">
            <span class="pajak-stat-card__label">{{ $label }}</span>
            <span class="pajak-stat-card__value">{{ $value }}</span>
            @isset($trend)
                <span @class(['pajak-stat-card__trend', 'pajak-stat-card__trend--' . $trendDirection?->value => $trendDirection !== null])>
                    {{ $trend }}
                </span>
            @endisset
            @isset($sub)
                <span class="pajak-stat-card__sub">{{ $sub }}</span>
            @endisset
        </div>
        @isset($icon)
            <div class="pajak-stat-card__icon pajak-stat-card__icon--{{ $color->value }}">{{ $icon }}</div>
        @endisset
    </div>
</div>
