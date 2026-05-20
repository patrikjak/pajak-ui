<a {{ $attributes->merge(['class' => 'pajak-sb-item', 'href' => $href])->class([
    'is-active' => $active,
    'is-warn' => $warn,
]) }} @if($active) aria-current="page" @endif>
    @isset($icon)
        <span class="pajak-sb-item__icon" aria-hidden="true">{{ $icon }}</span>
    @endisset

    <span class="pajak-sb-item__label">{{ $label }}</span>

    @if($dot)
        <span class="pajak-sb-item__dot" aria-hidden="true"></span>
    @endif

    @isset($count)
        <span class="pajak-sb-item__count">{{ $count }}</span>
    @endisset

    <span class="pajak-sb-item__tooltip" aria-hidden="true">{{ $label }}</span>
</a>
