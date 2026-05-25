<div {{ $attributes->merge(['class' => 'pajak-email-step']) }}>
    <div @class(['pajak-email-step__num', 'pajak-email-step__num--done' => $done])>
        @if($done)
            <x-heroicon-o-check width="12" height="12" />
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
