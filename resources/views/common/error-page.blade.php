@use('Pajak\Ui\Common\Enums\ErrorPage\ErrorPageCode')

<div {{ $attributes->merge(['class' => 'pajak-error-page'])->class([
    "pajak-error-page--$colorKey",
    'pajak-error-page--maintenance' => $code === 503,
]) }}>

    {{-- 503 maintenance stripe --}}
    @if($code === 503)
        <div class="pajak-error-page__stripe" aria-hidden="true"></div>
    @endif

    {{-- Watermark number --}}
    <div class="pajak-error-page__wm" aria-hidden="true">{{ $code }}</div>

    {{-- Brand corner --}}
    @isset($brand)
        <div class="pajak-error-page__brand">{{ $brand }}</div>
    @endisset

    {{-- Core content --}}
    <div class="pajak-error-page__content">

        {{-- Icon --}}
        <div class="pajak-error-page__icon" aria-hidden="true">
            @if($variant === ErrorPageCode::NotFound)
                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/>
                    <path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/>
                    <line x1="2" y1="2" x2="22" y2="22"/>
                </svg>
            @elseif($variant === ErrorPageCode::ServerError)
                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
                    <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/>
                </svg>
            @elseif($variant === ErrorPageCode::Forbidden)
                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                    <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                </svg>
            @elseif($variant === ErrorPageCode::Unauthorized)
                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                    <line x1="12" y1="8" x2="12" y2="12"/>
                    <line x1="12" y1="16" x2="12.01" y2="16"/>
                </svg>
            @elseif($variant === ErrorPageCode::ServiceUnavailable)
                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"/>
                    <polyline points="12 6 12 12 16 14"/>
                </svg>
            @else
                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"/>
                    <line x1="12" y1="8" x2="12" y2="12"/>
                    <line x1="12" y1="16" x2="12.01" y2="16"/>
                </svg>
            @endif
        </div>

        {{-- Status pill --}}
        <span class="pajak-error-page__pill">
            <span class="pajak-error-page__dot" aria-hidden="true"></span>
            {{ $pillLabel }}
        </span>

        {{-- Title and description --}}
        <h1 class="pajak-error-page__title">{{ $resolvedTitle }}</h1>
        <p class="pajak-error-page__desc">{{ $resolvedDescription }}</p>

        {{-- 503 progress bar --}}
        @if($code === 503)
            <div class="pajak-error-page__progress" role="progressbar" aria-label="{{ __('pajak::ui.error_page.maintenance_progress') }}" aria-valuemin="0" aria-valuemax="100" aria-valuenow="62">
                <div class="pajak-error-page__progress-fill"></div>
            </div>
        @endif

        {{-- Action buttons --}}
        @isset($actions)
            <div class="pajak-error-page__actions">{{ $actions }}</div>
        @endisset

    </div>

    {{-- Support footer --}}
    @isset($footer)
        <div class="pajak-error-page__footer">{{ $footer }}</div>
    @endisset

</div>
