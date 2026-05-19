@use('Pajak\Ui\Common\Enums\EmptyState\EmptyStateVariant')

<div {{ $attributes->merge(['class' => 'pajak-empty'])->class([
    'pajak-empty--dashed' => $variant === EmptyStateVariant::Dashed,
    'pajak-empty--compact' => $variant === EmptyStateVariant::Compact,
]) }}>
    @isset($icon)
        <div class="pajak-empty__art">{{ $icon }}</div>
    @endisset
    @isset($title)
        <div class="pajak-empty__title">{{ $title }}</div>
    @endisset
    @isset($message)
        <div class="pajak-empty__msg">{{ $message }}</div>
    @endisset
    @isset($actions)
        <div class="pajak-empty__actions">{{ $actions }}</div>
    @endisset
</div>
