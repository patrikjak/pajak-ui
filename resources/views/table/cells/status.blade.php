@use('Pajak\Ui\Table\Columns\StatusColumn')
@php(assert($column instanceof StatusColumn))

@php($cellValue = is_array($row) ? ($row[$column->key()] ?? '') : ($row->{$column->key()} ?? ''))
@php($cellColor = $column->colorFor($cellValue))

<td class="pajak-table__td" data-column-key="{{ $column->key() }}">
    <span @class(['pajak-table-status', "pajak-table-status--$cellColor" => $cellColor !== null])>
        {{ $cellValue }}
    </span>
</td>
