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
                    <x-heroicon-m-x-mark width="18" height="18" aria-hidden="true" />
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
