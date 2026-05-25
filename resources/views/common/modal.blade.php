<dialog {{ $attributes->merge(['class' => 'pajak-modal'])->class(["pajak-modal--$size->value" => $size->value !== 'md', 'pajak-modal--sheet' => $sheet]) }} id="{{ $id }}" @if($open) open @endif data-pajak-modal>
    @if($sheet)
        <div class="pajak-modal__grabber"></div>
    @endif

    @if(isset($title) || isset($description) || isset($icon) || $dismissible)
        <div class="pajak-modal__head">
            @isset($icon)
                <div class="pajak-modal__icon">{{ $icon }}</div>
            @endisset

            @if(isset($title) || isset($description))
                <div class="pajak-modal__head-body">
                    @isset($title)
                        <h3 class="pajak-modal__title">{{ $title }}</h3>
                    @endisset
                    @isset($description)
                        <p class="pajak-modal__description">{{ $description }}</p>
                    @endisset
                </div>
            @endif

            @if($dismissible)
                <button class="pajak-modal__close" type="button" aria-label="Close" data-pajak-modal-close>
                    <x-heroicon-m-x-mark width="18" height="18" aria-hidden="true" />
                </button>
            @endif
        </div>
    @endif

    @if($slot->isNotEmpty())
        <div class="pajak-modal__body">
            {{ $slot }}
        </div>
    @endif

    @isset($footer)
        <div class="pajak-modal__footer">
            {{ $footer }}
        </div>
    @endisset
</dialog>
