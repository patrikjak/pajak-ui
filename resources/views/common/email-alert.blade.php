@use('Pajak\Ui\Common\Enums\AlertType')

<div {{ $attributes->merge(['class' => 'pajak-email-alert'])->class(["pajak-email-alert--$type->value"]) }}>
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
