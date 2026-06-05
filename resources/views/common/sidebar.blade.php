@use('Pajak\Ui\Common\Enums\Sidebar\SidebarVariant')

<aside {{ $attributes->merge(['class' => 'pajak-sb'])->class([
    "pajak-sb--$variant->value" => $variant !== SidebarVariant::Standard,
]) }}>
    @if(isset($brand))
        <div class="pajak-sb__brand">
            {{ $brand }}
            <button class="pajak-sb-toggle pajak-sb-toggle--close" data-pajak-sidebar-close aria-label="Close navigation">
                <span class="pajak-sb-toggle__icon">
                    <span></span>
                    <span></span>
                    <span></span>
                </span>
            </button>
        </div>
    @else
        <button class="pajak-sb-toggle pajak-sb-toggle--close" data-pajak-sidebar-close aria-label="Close navigation">
            <span class="pajak-sb-toggle__icon">
                <span></span>
                <span></span>
                <span></span>
            </span>
        </button>
    @endif

    @isset($header)
        <div class="pajak-sb__header">{{ $header }}</div>
    @endisset

    @if($slot->isNotEmpty())
        <div class="pajak-sb__scroll">{{ $slot }}</div>
    @endif

    @isset($footer)
        <div class="pajak-sb__footer">{{ $footer }}</div>
    @endisset
</aside>
