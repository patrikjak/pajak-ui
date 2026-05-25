@use('Pajak\Ui\Common\Enums\BannerType')

<div {{ $attributes->merge(['class' => 'pajak-banner'])->class([
    "pajak-banner--$type->value",
    'pajak-banner--top' => $top,
]) }} role="alert">
    <span class="pajak-banner__icon" aria-hidden="true">
        @if($type === BannerType::Info)
            <x-heroicon-o-information-circle width="20" height="20" />
        @elseif($type === BannerType::Success)
            <x-heroicon-o-check-circle width="20" height="20" />
        @elseif($type === BannerType::Warning)
            <x-heroicon-o-exclamation-triangle width="20" height="20" />
        @elseif($type === BannerType::Error)
            <x-heroicon-o-x-circle width="20" height="20" />
        @elseif($type === BannerType::Neutral)
            <x-heroicon-o-signal width="20" height="20" />
        @elseif($type === BannerType::Promo)
            <x-heroicon-o-star width="20" height="20" />
        @endif
    </span>

    <div class="pajak-banner__body">
        @if($title !== null)
            <div class="pajak-banner__title">{{ $title }}</div>
        @endif
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
        <button class="pajak-banner__close" type="button" aria-label="{{ __('pajak::ui.common.dismiss') }}" data-pajak-banner-close>
            <x-heroicon-m-x-mark width="16" height="16" />
        </button>
    @endif
</div>
