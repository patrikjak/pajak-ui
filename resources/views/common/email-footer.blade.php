c<div {{ $attributes->merge(['class' => 'pajak-email-footer']) }} style="background: #F7F9FF; border-top: 1px solid #EEF2FB; border-radius: 0 0 14px 14px; padding: 24px 40px; text-align: center;">
    @isset($logo)
        <div class="pajak-email-footer__logo" style="display: flex; justify-content: center; margin-bottom: 10px;">{{ $logo }}</div>
    @endisset

    @isset($links)
        <div class="pajak-email-footer__links" style="display: flex; justify-content: center; gap: 20px; margin-bottom: 14px; flex-wrap: wrap;">{{ $links }}</div>
    @endisset

    @if($slot->isNotEmpty())
        <div class="pajak-email-footer__legal" style="font-size: 11px; color: #A4B3D0; line-height: 1.6;">{{ $slot }}</div>
    @endif
</div>
