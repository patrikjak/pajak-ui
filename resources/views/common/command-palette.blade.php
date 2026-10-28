<dialog {{ $attributes->merge(['class' => 'pajak-cmdk']) }} id="{{ $id }}" aria-label="{{ $placeholderText() }}" data-pajak-command-palette>
    <div class="pajak-cmdk__search">
        <x-heroicon-o-magnifying-glass class="pajak-cmdk__search-icon" width="18" height="18" aria-hidden="true" />
        <input
            class="pajak-cmdk__input"
            type="text"
            role="combobox"
            aria-expanded="true"
            aria-autocomplete="list"
            aria-controls="{{ $id }}-list"
            aria-label="{{ $placeholderText() }}"
            placeholder="{{ $placeholderText() }}"
            autocomplete="off"
            autocapitalize="off"
            spellcheck="false"
            data-pajak-command-palette-input
        >
    </div>

    <div class="pajak-cmdk__list" id="{{ $id }}-list" role="listbox" aria-label="{{ $placeholderText() }}">
        {{ $slot }}
    </div>

    <p class="pajak-cmdk__empty" role="status" hidden data-pajak-command-palette-empty>{{ $emptyText() }}</p>
</dialog>
