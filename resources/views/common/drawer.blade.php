@use('Pajak\Ui\Common\Enums\Drawer\DrawerSide')

<dialog {{ $attributes->merge(['class' => 'pajak-drawer'])->class(["pajak-drawer--$side->value"]) }} id="{{ $id }}" @if($open) open @endif data-pajak-drawer>
    @if($side === DrawerSide::Bottom)
        <div class="pajak-drawer__grabber"></div>
    @endif

    @if(isset($title) || isset($description) || $dismissible)
        <div class="pajak-drawer__head">
            <div class="pajak-drawer__head-body">
                @isset($title)
                    <h3 class="pajak-drawer__title">{{ $title }}</h3>
                @endisset
                @isset($description)
                    <p class="pajak-drawer__description">{{ $description }}</p>
                @endisset
            </div>

            @if($dismissible)
                <button class="pajak-drawer__close" type="button" aria-label="Close" data-pajak-drawer-close>
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                </button>
            @endif
        </div>
    @endif

    @if($slot->isNotEmpty())
        <div class="pajak-drawer__body">
            {{ $slot }}
        </div>
    @endif

    @isset($footer)
        <div class="pajak-drawer__footer">
            {{ $footer }}
        </div>
    @endisset
</dialog>
