@use('Pajak\Ui\Table\Enums\ActionPosition')

<tr class="pajak-table__row" data-row-id="{{ $rowId() }}">
    @if($table->isSelectable())
        <td class="pajak-table__td pajak-table__td--check">
            <input
                type="checkbox"
                class="pajak-table__row-check"
                data-pajak-table-row-check
                value="{{ $rowId() }}"
                aria-label="{{ __('pajak::table.bulk.selected', ['count' => 1]) }}"
            >
        </td>
    @endif

    @foreach($table->getColumns() as $column)
        @if(!$column->isHidden())
            @include($column->cellView(), ['column' => $column, 'row' => $row])
        @endif
    @endforeach

    @if($table->hasActions())
        @include('pajak::table.partials.row-actions', ['table' => $table, 'row' => $row])
    @endif
</tr>
