@use('Pajak\Ui\Common\Enums\Detail\DetailVariant')

<div {{ $attributes->merge(['class' => 'pajak-detail'])->class([
    'pajak-detail--compact' => $variant === DetailVariant::Compact,
    'pajak-detail--grid-2'  => $variant === DetailVariant::Grid2,
    'pajak-detail--flush'   => $variant === DetailVariant::Flush,
]) }}>
    @if($title || $actionLabel)
        <div class="pajak-detail__header">
            <span class="pajak-detail__header-title">{{ $title }}</span>
            @if($actionLabel)
                <a class="pajak-detail__header-action" href="{{ $actionHref ?? '#' }}">{{ $actionLabel }}</a>
            @endif
        </div>
    @endif
    @if($variant === DetailVariant::Grid2)
        <div class="pajak-detail__body">
            {{ $body ?? $slot }}
        </div>
    @else
        {{ $slot }}
    @endif
</div>
