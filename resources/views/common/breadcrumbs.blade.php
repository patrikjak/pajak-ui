@use('Pajak\Ui\Common\Enums\BreadcrumbSeparator')
@use('Pajak\Ui\Common\Enums\BreadcrumbVariant')

@if($variant === BreadcrumbVariant::Pill)
    <nav {{ $attributes->merge(['class' => 'pajak-crumbs-pill']) }} aria-label="Breadcrumb">
        @foreach($items as $item)
            @if($item->href !== null)
                <a href="{{ $item->href }}">
                    @if($item->isHome)
                        <x-heroicon-o-home width="13" height="13" aria-hidden="true" />
                    @endif
                    {{ $item->label }}
                </a>
            @else
                <span class="current" aria-current="page">{{ $item->label }}</span>
            @endif
            @if(!$loop->last)
                <span class="sep" aria-hidden="true">
                    <x-heroicon-o-chevron-right width="11" height="11" />
                </span>
            @endif
        @endforeach
    </nav>
@else
    <ul {{ $attributes->merge(['class' => 'pajak-crumbs'])->class(['pajak-crumbs--compact' => $variant === BreadcrumbVariant::Compact, 'pajak-crumbs--slash' => $separator === BreadcrumbSeparator::Slash]) }} aria-label="Breadcrumb">
        @foreach($items as $item)
            <li>
                @if($item->href !== null)
                    <a href="{{ $item->href }}" @class(['pajak-crumbs__home-link' => $item->isHome])>
                        @if($item->isHome)
                            <x-heroicon-o-home width="15" height="15" aria-hidden="true" />
                            <span class="sr-only">{{ $item->label }}</span>
                        @else
                            {{ $item->label }}
                        @endif
                    </a>
                @else
                    <span class="current" aria-current="page">{{ $item->label }}</span>
                @endif
            </li>
            @if(!$loop->last)
                <li class="sep" aria-hidden="true">
                    @if($separator === BreadcrumbSeparator::Chevron)
                        <x-heroicon-o-chevron-right width="12" height="12" />
                    @endif
                </li>
            @endif
        @endforeach
    </ul>
@endif
