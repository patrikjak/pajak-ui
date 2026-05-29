<div
    class="pajak-repeater"
    data-pajak-repeater
    data-name="{{ $name }}"
    data-min="{{ $min }}"
    @isset($max) data-max="{{ $max }}" @endisset
    data-add-announcement="{{ __('pajak::ui.form.repeater.row_added') }}"
    data-remove-announcement="{{ __('pajak::ui.form.repeater.row_removed') }}"
>
    @isset($label)
        <div class="pajak-repeater__label">{{ $label }}</div>
    @endisset

    <div class="pajak-repeater__rows">
        @for($i = 0; $i < $count; $i++)
            <div class="pajak-repeater__row">
                {!! $replaceIndex((string) $slot, $i) !!}
                <button
                    type="button"
                    class="pajak-repeater__remove"
                    aria-label="{{ $resolvedRemoveLabel() }}"
                    @if($count <= $min) disabled aria-disabled="true" @endif
                >
                    <x-heroicon-m-x-mark width="14" height="14" aria-hidden="true" />
                    <span class="pajak-repeater__remove-label">{{ $resolvedRemoveLabel() }}</span>
                </button>
            </div>
        @endfor
    </div>

    <template class="pajak-repeater__template">
        <div class="pajak-repeater__row">
            {!! $slot !!}
            <button
                type="button"
                class="pajak-repeater__remove"
                aria-label="{{ $resolvedRemoveLabel() }}"
            >
                <x-heroicon-m-x-mark width="14" height="14" aria-hidden="true" />
                <span class="pajak-repeater__remove-label">{{ $resolvedRemoveLabel() }}</span>
            </button>
        </div>
    </template>

    <button
        type="button"
        class="pajak-repeater__add"
        @if($max !== null && $count >= $max) hidden @endif
    >
        <x-heroicon-o-plus width="14" height="14" aria-hidden="true" />
        {{ $resolvedAddLabel() }}
    </button>
</div>
