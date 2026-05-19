@use('Pajak\Ui\Common\Enums\AlertType')
@use('Pajak\Ui\Common\Enums\AlertVariant')

<div {{ $attributes->merge(['class' => 'pajak-alert'])->class([
    "pajak-alert--$type->value",
    'pajak-alert--outline' => $variant === AlertVariant::Outline,
    'pajak-alert--inline' => $variant === AlertVariant::Inline,
]) }} role="alert">
    <span class="pajak-alert__icon" aria-hidden="true">
        @if($type === AlertType::Info)
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
        @elseif($type === AlertType::Success)
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
        @elseif($type === AlertType::Warning)
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
        @elseif($type === AlertType::Error)
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
        @endif
    </span>

    <div class="pajak-alert__body">
        @if($title && $variant !== AlertVariant::Inline)
            <div class="pajak-alert__title">{{ $title }}</div>
        @endif
        <div class="pajak-alert__message">{{ $slot }}</div>
    </div>

    @if($dismissible)
        <button class="pajak-alert__close" type="button" aria-label="{{ __('pajak::ui.alert.dismiss') }}">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
        </button>
    @endif
</div>
