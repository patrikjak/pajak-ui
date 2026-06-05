@use('Pajak\Ui\Common\Enums\Navbar\NavbarVariant')
@use('Pajak\Ui\Common\Enums\Sidebar\SidebarVariant')

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

            @isset($menu)
                <button class="pajak-navbar__menu-btn" type="button" aria-label="Menu" data-pajak-sidebar-trigger="{{ $menuDialogId }}">
                    <x-heroicon-o-bars-3 width="20" height="20" aria-hidden="true" />
                </button>
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

            @isset($menu)
                <button class="pajak-navbar__menu-btn" type="button" aria-label="Menu" data-pajak-sidebar-trigger="{{ $menuDialogId }}">
                    <x-heroicon-o-bars-3 width="20" height="20" aria-hidden="true" />
                </button>
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

        @isset($menu)
            <button class="pajak-navbar__menu-btn" type="button" aria-label="Menu" data-pajak-sidebar-trigger="{{ $menuDialogId }}">
                <x-heroicon-o-bars-3 width="20" height="20" aria-hidden="true" />
            </button>
        @endisset
    </nav>
@endif

@isset($menu)
    <dialog id="{{ $menuDialogId }}" data-pajak-sidebar>
        <x-pajak::sidebar :variant="$variant === NavbarVariant::Dark ? SidebarVariant::Dark : SidebarVariant::Standard">
            @if(isset($brand))
                <x-slot:brand>{{ $brand }}</x-slot:brand>
            @elseif(isset($title))
                <x-slot:brand>{{ $title }}</x-slot:brand>
            @endif
            @isset($menuFooter)
                <x-slot:footer>{{ $menuFooter }}</x-slot:footer>
            @endisset
            {{ $menu }}
        </x-pajak::sidebar>
    </dialog>
@endisset
