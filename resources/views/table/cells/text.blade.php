@use('Pajak\Ui\Table\Contracts\TableColumn')
@php(assert($column instanceof TableColumn))

<td class="pajak-table__td" data-column-key="{{ $column->key() }}">
    {{ is_array($row) ? ($row[$column->key()] ?? '') : ($row->{$column->key()} ?? '') }}
</td>
