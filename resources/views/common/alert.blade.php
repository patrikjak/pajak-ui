@use('Pajak\Ui\Common\Enums\AlertType')
@use('Pajak\Ui\Common\Enums\AlertVariant')

<div {{ $attributes->merge(['class' => 'pajak-alert'])->class([
    "pajak-alert--$type->value",
    'pajak-alert--outline' => $variant === AlertVariant::Outline,
    'pajak-alert--inline' => $variant === AlertVariant::Inline,
]) }} role="alert">
    <span class="pajak-alert__icon" aria-hidden="true">
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

    <div class="pajak-alert__body">
        @if($title && $variant !== AlertVariant::Inline)
            <div class="pajak-alert__title">{{ $title }}</div>
        @endif
        <div class="pajak-alert__message">{{ $slot }}</div>
    </div>

    @if($dismissible)
        <button class="pajak-alert__close" type="button" aria-label="{{ __('pajak::ui.common.dismiss') }}">
            <x-heroicon-m-x-mark width="14" height="14" />
        </button>
    @endif
</div>
