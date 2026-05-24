<tr class="pajak-table__row pajak-table__row--empty">
    <td class="pajak-table__td pajak-table__td--empty" colspan="{{ $columnCount }}">
        <div class="pajak-table-empty">
            @if($hasActiveFilters)
                <x-heroicon-o-magnifying-glass class="pajak-table-empty__icon" width="40" height="40" aria-hidden="true" />
                <p class="pajak-table-empty__title">{{ __('pajak::table.empty.filtered_title') }}</p>
                <p class="pajak-table-empty__description">{{ __('pajak::table.empty.filtered_description') }}</p>
            @else
                <x-heroicon-o-cube class="pajak-table-empty__icon" width="40" height="40" aria-hidden="true" />
                <p class="pajak-table-empty__title">{{ __('pajak::table.empty.title') }}</p>
                <p class="pajak-table-empty__description">{{ __('pajak::table.empty.description') }}</p>
            @endif
            {{ $slot ?? '' }}
        </div>
    </td>
</tr>
