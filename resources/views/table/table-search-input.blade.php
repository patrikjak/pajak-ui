<div class="pajak-table-search">
    <div class="pajak-table-search__wrap">
        <svg class="pajak-table-search__icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
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
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
        </button>
    </div>
</div>
