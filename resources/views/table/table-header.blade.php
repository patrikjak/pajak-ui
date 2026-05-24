@use('Pajak\Ui\Table\Enums\SortDirection')
@use('Pajak\Ui\Table\Contracts\TableColumn')
@use('Pajak\Ui\Table\Dto\TableSortState')
@php(assert($column instanceof TableColumn))
@php
    /** @var ?TableSortState $currentSort */
    /** @var bool $isActiveSort */
@endphp

<th
    @class(['pajak-table__th', 'pajak-table__th--' . $column->thModifier() => $column->thModifier() !== ''])
    data-column-key="{{ $column->key() }}"
    data-pajak-table-column
>
    @if($column->isSortable())
        <button type="button" class="pajak-table__sort-btn" data-pajak-table-sort="{{ $column->key() }}">
            {{ $column->getLabel() }}
            <span class="pajak-table__sort-icons" aria-hidden="true">
                @if($isActiveSort && $currentSort?->direction === SortDirection::Asc)
                    <svg class="pajak-table__sort-icon is-active" width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="18 15 12 9 6 15"/></svg>
                @elseif($isActiveSort && $currentSort?->direction === SortDirection::Desc)
                    <svg class="pajak-table__sort-icon is-active" width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
                @else
                    <svg class="pajak-table__sort-icon" width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><polyline points="8 9 12 5 16 9"/><polyline points="8 15 12 19 16 15"/></svg>
                @endif
            </span>
        </button>
    @else
        {{ $column->getLabel() }}
    @endif
</th>
