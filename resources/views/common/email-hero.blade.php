<div {{ $attributes->merge(['class' => 'pajak-email-hero']) }} style="background: {{ $color }}; color: #fff; padding: 32px 40px 36px;">
    @isset($eyebrow)
        <div class="pajak-email-hero__eyebrow" style="font-size: 11px; font-weight: 600; letter-spacing: 0.1em; text-transform: uppercase; color: #CCDAF7; margin-bottom: 8px;">{{ $eyebrow }}</div>
    @endisset

    @isset($title)
        <div class="pajak-email-hero__title" style="font-size: 26px; font-weight: 700; line-height: 1.25; letter-spacing: -0.02em; color: #fff; margin-bottom: 10px;">{{ $title }}</div>
    @endisset

    @if($slot->isNotEmpty())
        <div class="pajak-email-hero__sub" style="font-size: 15px; line-height: 1.55; color: #D9E5FA; max-width: 420px;">{{ $slot }}</div>
    @endif
</div>
