@use('Pajak\Ui\Common\Enums\BreadcrumbSeparator')
@use('Pajak\Ui\Common\Enums\BreadcrumbVariant')

@if($variant === BreadcrumbVariant::Pill)
    <nav {{ $attributes->merge(['class' => 'pajak-crumbs-pill']) }} aria-label="Breadcrumb">
        @foreach($items as $item)
            @if($item->href !== null)
                <a href="{{ $item->href }}">
                    @if($item->isHome)
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                    @endif
                    {{ $item->label }}
                </a>
            @else
                <span class="current" aria-current="page">{{ $item->label }}</span>
            @endif
            @if(!$loop->last)
                <span class="sep" aria-hidden="true">
                    <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
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
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
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
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
                    @endif
                </li>
            @endif
        @endforeach
    </ul>
@endif
