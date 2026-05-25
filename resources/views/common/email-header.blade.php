<div {{ $attributes->merge(['class' => 'pajak-email-header']) }}>
    @isset($logo)
        <div class="pajak-email-header__logo">{{ $logo }}</div>
    @endisset

    @isset($tag)
        <span class="pajak-email-header__tag">{{ $tag }}</span>
    @endisset
</div>
