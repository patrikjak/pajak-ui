@use('Pajak\Ui\Common\Enums\Navbar\NavbarVariant')

@if($variant === NavbarVariant::Stacked)
    <nav {{ $attributes->merge(['class' => 'pajak-navbar pajak-navbar--stacked']) }}>
        <div class="pajak-navbar__top">
            @isset($brand)
                <div class="pajak-navbar__brand">{{ $brand }}</div>
            @endisset

            @isset($links)
                <div class="pajak-navbar__links">{{ $links }}</div>
            @endisset

            @isset($actions)
                <div class="pajak-navbar__actions">{{ $actions }}</div>
            @endisset
        </div>

        @isset($subLinks)
            <div class="pajak-navbar__sub">{{ $subLinks }}</div>
        @endisset
    </nav>
@elseif($variant === NavbarVariant::Split)
    <nav {{ $attributes->merge(['class' => 'pajak-navbar pajak-navbar--split']) }}>
        @isset($brand)
            <div class="pajak-navbar__brand-area">{{ $brand }}</div>
        @endisset

        <div class="pajak-navbar__main">
            @isset($title)
                <div class="pajak-navbar__title">{{ $title }}</div>
            @endisset

            @isset($actions)
                <div class="pajak-navbar__actions">{{ $actions }}</div>
            @endisset
        </div>
    </nav>
@else
    <nav {{ $attributes->merge(['class' => 'pajak-navbar'])->class([
        "pajak-navbar--$variant->value" => $variant !== NavbarVariant::Standard,
    ]) }}>
        @isset($brand)
            <div class="pajak-navbar__brand">{{ $brand }}</div>
        @endisset

        @isset($links)
            <div class="pajak-navbar__links">{{ $links }}</div>
        @endisset

        @isset($actions)
            <div class="pajak-navbar__actions">{{ $actions }}</div>
        @endisset
    </nav>
@endif
