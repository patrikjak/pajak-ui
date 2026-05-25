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
                <x-heroicon-o-link-slash width="32" height="32" />
            @elseif($variant === ErrorPageCode::ServerError)
                <x-heroicon-o-bolt width="32" height="32" />
            @elseif($variant === ErrorPageCode::Forbidden)
                <x-heroicon-o-lock-closed width="32" height="32" />
            @elseif($variant === ErrorPageCode::Unauthorized)
                <x-heroicon-o-shield-exclamation width="32" height="32" />
            @elseif($variant === ErrorPageCode::ServiceUnavailable)
                <x-heroicon-o-clock width="32" height="32" />
            @else
                <x-heroicon-o-exclamation-circle width="32" height="32" />
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
