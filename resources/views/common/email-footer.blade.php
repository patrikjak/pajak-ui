<div {{ $attributes->merge(['class' => 'pajak-email-footer']) }}>
    @isset($logo)
        <div class="pajak-email-footer__logo">{{ $logo }}</div>
    @endisset

    @isset($links)
        <div class="pajak-email-footer__links">{{ $links }}</div>
    @endisset

    @if($slot->isNotEmpty())
        <div class="pajak-email-footer__legal">{{ $slot }}</div>
    @endif
</div>
