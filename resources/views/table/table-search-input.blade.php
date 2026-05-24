<div class="pajak-table-search">
    <div class="pajak-table-search__wrap">
        <x-heroicon-o-magnifying-glass class="pajak-table-search__icon" width="16" height="16" aria-hidden="true" />
        <input
            type="text"
            class="pajak-table-search__input"
            placeholder="{{ __('pajak::table.search.placeholder') }}"
            aria-label="{{ __('pajak::table.search.label') }}"
            data-pajak-table-search
            autocomplete="off"
        >
        <button
            type="button"
            class="pajak-table-search__clear"
            data-pajak-table-search-clear
            hidden
            aria-label="{{ __('pajak::table.search.clear') }}"
        >
            <x-heroicon-m-x-mark width="12" height="12" aria-hidden="true" />
        </button>
    </div>
</div>
