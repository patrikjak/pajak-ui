@use('Pajak\Ui\Common\Enums\Popover\PopoverPlacement')

<div {{ $attributes->merge(['class' => 'pajak-pop']) }} id="{{ $id }}" data-pajak-popover data-placement="{{ $placement->value }}" aria-hidden="true" hidden>
    @if($title !== null || $dismissible)
        <div class="pajak-pop__head">
            @if($title !== null)
                <span class="pajak-pop__title">{{ $title }}</span>
            @endif

            @if($dismissible)
                <button class="pajak-pop__close" type="button" aria-label="Close" data-pajak-popover-close>
                    <x-heroicon-m-x-mark width="16" height="16" aria-hidden="true" />
                </button>
            @endif
        </div>
    @endif

    @if($slot->isNotEmpty())
        <div class="pajak-pop__body">
            {{ $slot }}
        </div>
    @endif

    @isset($footer)
        <div class="pajak-pop__foot">
            {{ $footer }}
        </div>
    @endisset
</div>
