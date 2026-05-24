<div {{ $attributes->merge(['class' => 'pajak-email-hero']) }} style="background: {{ $color }}">
    @isset($eyebrow)
        <div class="pajak-email-hero__eyebrow">{{ $eyebrow }}</div>
    @endisset

    @isset($title)
        <div class="pajak-email-hero__title">{{ $title }}</div>
    @endisset

    @if($slot->isNotEmpty())
        <div class="pajak-email-hero__sub">{{ $slot }}</div>
    @endif
</div>
