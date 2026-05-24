<div {{ $attributes->merge(['class' => 'pajak-email-step']) }}>
    <div @class(['pajak-email-step__num', 'pajak-email-step__num--done' => $done])>
        @if($done)
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
        @else
            {{ $number }}
        @endif
    </div>
    <div class="pajak-email-step__content">
        <div class="pajak-email-step__title">{{ $title }}</div>
        @isset($description)
            <div class="pajak-email-step__desc">{{ $description }}</div>
        @endisset
    </div>
</div>
