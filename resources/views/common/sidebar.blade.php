@use('Pajak\Ui\Common\Enums\Sidebar\SidebarVariant')

<aside {{ $attributes->merge(['class' => 'pajak-sb'])->class([
    "pajak-sb--$variant->value" => $variant !== SidebarVariant::Standard,
]) }}>
    @isset($brand)
        <div class="pajak-sb__brand">{{ $brand }}</div>
    @endisset

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
