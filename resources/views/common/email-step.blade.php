@php $stepNumColor = $done ? '#27AE60' : '#5386E4'; @endphp

<div {{ $attributes->merge(['class' => 'pajak-email-step']) }} style="display: flex; gap: 16px; align-items: flex-start; padding: 16px 0; border-bottom: 1px solid #EEF2FB;">
    <div @class(['pajak-email-step__num', 'pajak-email-step__num--done' => $done]) style="width: 28px; height: 28px; border-radius: 50%; background: {{ $stepNumColor }}; color: #fff; font-size: 13px; font-weight: 700; display: flex; align-items: center; justify-content: center; flex-shrink: 0; margin-top: 1px;">
        @if($done)
            <x-heroicon-o-check width="12" height="12" />
        @else
            {{ $number }}
        @endif
    </div>
    <div class="pajak-email-step__content" style="flex: 1;">
        <div class="pajak-email-step__title" style="font-size: 14px; font-weight: 600; color: #1A1F2E; margin-bottom: 2px;">{{ $title }}</div>
        @isset($description)
            <div class="pajak-email-step__desc" style="font-size: 13px; color: #7F93B5; line-height: 1.5;">{{ $description }}</div>
        @endisset
    </div>
</div>
