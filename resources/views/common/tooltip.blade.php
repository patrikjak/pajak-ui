<div {{ $attributes->merge(['class' => 'pajak-tooltip-wrap'])->class([
    "pajak-tooltip-wrap--$placement->value",
    'pajak-tooltip-wrap--disabled' => $disabled,
]) }}>
    {{ $slot }}
    <span class="pajak-tooltip" role="tooltip">{{ $text }}</span>
</div>
