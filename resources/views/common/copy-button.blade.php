<span {{ $attributes->merge(['class' => 'pajak-copy']) }} data-pajak-copy="{{ $value }}">
    @if($icon)
        <button type="button" class="pajak-copy__btn" aria-label="{{ __('pajak::ui.common.copy') }}">
            <x-heroicon-o-clipboard width="16" height="16" aria-hidden="true" />
        </button>
    @else
        {{ $slot }}
    @endif
</span>
