@use('Pajak\Ui\Common\Enums\BannerType')

<div {{ $attributes->merge(['class' => 'pajak-banner'])->class([
    "pajak-banner--$type->value",
    'pajak-banner--top' => $top,
]) }} role="alert">
    <span class="pajak-banner__icon" aria-hidden="true">
        @if($type === BannerType::Info)
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
        @elseif($type === BannerType::Success)
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
        @elseif($type === BannerType::Warning)
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
        @elseif($type === BannerType::Error)
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
        @elseif($type === BannerType::Neutral)
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
        @elseif($type === BannerType::Promo)
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
        @endif
    </span>

    <div class="pajak-banner__body">
        @isset($title)
            <div class="pajak-banner__title">{{ $title }}</div>
        @endisset
        <div class="pajak-banner__text">{{ $slot }}</div>
        @isset($progress)
            <div class="pajak-banner__progress" role="progressbar" aria-valuenow="{{ $progress }}" aria-valuemin="0" aria-valuemax="100">
                <span style="width: {{ $progress }}%"></span>
            </div>
        @endisset
    </div>

    @isset($actions)
        <div class="pajak-banner__actions">{{ $actions }}</div>
    @endisset

    @if($dismissible)
        <button class="pajak-banner__close" type="button" aria-label="{{ __('pajak::ui.banner.dismiss') }}" data-pajak-banner-close>
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
        </button>
    @endif
</div>
