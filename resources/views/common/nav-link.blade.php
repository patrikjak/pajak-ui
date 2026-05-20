<a {{ $attributes->merge(['class' => 'pajak-nav-link', 'href' => $href])->class(['is-active' => $active]) }} @if($active) aria-current="page" @endif>
    {{ $label }}

    @if($dot)
        <span class="pajak-nav-link__dot" aria-hidden="true"></span>
    @endif

    @isset($count)
        <span class="pajak-nav-link__count">{{ $count }}</span>
    @endisset
</a>
