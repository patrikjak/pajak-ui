<span {{ $attributes->merge(['class' => 'pajak-badge'])->class(["pajak-badge--$color->value", "pajak-badge--$size->value", 'pajak-badge--outline' => $outline]) }}>
    @if($dot)
        <span class="pajak-badge__dot"></span>
    @endif
    {{ $slot }}
</span>
