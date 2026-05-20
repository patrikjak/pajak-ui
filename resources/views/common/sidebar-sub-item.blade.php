<a {{ $attributes->merge(['class' => 'pajak-sb-sub-item', 'href' => $href])->class(['is-active' => $active]) }} @if($active) aria-current="page" @endif>
    {{ $label }}

    @isset($count)
        <span class="pajak-sb-sub-item__count">{{ $count }}</span>
    @endisset
</a>
