@use('Pajak\Ui\Common\Enums\AlertType')

@php
$alertStyles = match($type) {
    AlertType::Info => 'background: #EBF0FB; color: #1E4A9E; border: 1px solid rgba(83,134,228,0.30);',
    AlertType::Success => 'background: #E8F7EF; color: #1A7A43; border: 1px solid rgba(39,174,96,0.25);',
    AlertType::Warning => 'background: #FFF8E8; color: #B87318; border: 1px solid rgba(245,200,106,0.25);',
    AlertType::Error => 'background: #FDEEED; color: #A01C1A; border: 1px solid rgba(229,57,53,0.25);',
};
@endphp

<div {{ $attributes->merge(['class' => 'pajak-email-alert'])->class(["pajak-email-alert--$type->value"]) }} style="{{ $alertStyles }}">
    <span class="pajak-email-alert__icon" aria-hidden="true">
        @if($type === AlertType::Info)
            <x-heroicon-o-information-circle width="18" height="18" />
        @elseif($type === AlertType::Success)
            <x-heroicon-o-check-circle width="18" height="18" />
        @elseif($type === AlertType::Warning)
            <x-heroicon-o-exclamation-triangle width="18" height="18" />
        @elseif($type === AlertType::Error)
            <x-heroicon-o-x-circle width="18" height="18" />
        @endif
    </span>

    <div class="pajak-email-alert__body">
        @isset($title)
            <div class="pajak-email-alert__title">{{ $title }}</div>
        @endisset
        <div class="pajak-email-alert__message">{{ $slot }}</div>
    </div>
</div>
