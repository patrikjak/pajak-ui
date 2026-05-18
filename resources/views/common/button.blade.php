<button
    {{ $attributes->merge(['class' => 'pajak-btn', 'type' => $type])->class(["pajak-btn--$size->value"]) }}
    @if($disabled) disabled @endif
>
    {{ $slot }}
</button>
