@use('Pajak\Ui\Common\Enums\Popover\PopoverPlacement')

<div {{ $attributes->merge(['class' => 'pajak-pop']) }} id="{{ $id }}" data-pajak-popover data-placement="{{ $placement->value }}" aria-hidden="true" hidden>
    @if($title !== null || $dismissible)
        <div class="pajak-pop__head">
            @if($title !== null)
                <span class="pajak-pop__title">{{ $title }}</span>
            @endif

            @if($dismissible)
                <button class="pajak-pop__close" type="button" aria-label="Close" data-pajak-popover-close>
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
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
