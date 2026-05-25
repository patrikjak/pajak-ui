<div {{ $attributes->merge(['class' => 'pajak-detail__row'])->class([
    'pajak-detail__row--copyable' => $copyable,
]) }}>
    <span class="pajak-detail__key">{{ $key }}</span>
    <span class="pajak-detail__val" @class([
        'pajak-detail__val--mono'  => $mono,
        'pajak-detail__val--muted' => $muted,
    ])>
        {{ $slot }}
    </span>
</div>
