<div {{ $attributes->merge(['class' => 'pajak-tooltip-wrap'])->class(["pajak-tooltip-wrap--$placement->value"]) }}>
    {{ $slot }}
    <span class="pajak-tooltip" role="tooltip">{{ $text }}</span>
</div>
