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
                    <x-heroicon-o-chevron-up class="pajak-table__sort-icon is-active" width="11" height="11" />
                @elseif($isActiveSort && $currentSort?->direction === SortDirection::Desc)
                    <x-heroicon-o-chevron-down class="pajak-table__sort-icon is-active" width="11" height="11" />
                @else
                    <x-heroicon-o-chevron-up-down class="pajak-table__sort-icon" width="11" height="11" />
                @endif
            </span>
        </button>
    @else
        {{ $column->getLabel() }}
    @endif
</th>
