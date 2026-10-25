@use('Pajak\Ui\Table\Columns\StatusColumn')
@php(assert($column instanceof StatusColumn))

@php($cellValue = $column->resolveValue($row))
@php($cellColor = $column->colorFor($cellValue))

<td class="pajak-table__td" data-column-key="{{ $column->key() }}">
    <span @class(['pajak-table-status', "pajak-table-status--$cellColor" => $cellColor !== null])>
        {{ $column->labelFor($cellValue, $row) }}
    </span>
</td>
