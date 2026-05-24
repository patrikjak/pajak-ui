<tr class="pajak-table__row pajak-table__row--empty">
    <td class="pajak-table__td pajak-table__td--empty" colspan="{{ $columnCount }}">
        <div class="pajak-table-empty">
            @if($hasActiveFilters)
                <svg class="pajak-table-empty__icon" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/><line x1="11" y1="8" x2="11" y2="14"/><line x1="8" y1="11" x2="14" y2="11"/></svg>
                <p class="pajak-table-empty__title">{{ __('pajak::table.empty.filtered_title') }}</p>
                <p class="pajak-table-empty__description">{{ __('pajak::table.empty.filtered_description') }}</p>
            @else
                <svg class="pajak-table-empty__icon" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/><polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/></svg>
                <p class="pajak-table-empty__title">{{ __('pajak::table.empty.title') }}</p>
                <p class="pajak-table-empty__description">{{ __('pajak::table.empty.description') }}</p>
            @endif
            {{ $slot ?? '' }}
        </div>
    </td>
</tr>
