@use('Pajak\Ui\Table\Columns\BadgeColumn')
@php(assert($column instanceof BadgeColumn))

@php($cellValue = $column->resolveValue($row))
@php($cellColor = $column->colorFor($cellValue))

<td class="pajak-table__td" data-column-key="{{ $column->key() }}">
    <span @class(['pajak-table-badge', "pajak-table-badge--$cellColor" => $cellColor !== null])>
        {{ $column->labelFor($cellValue, $row) }}
    </span>
</td>
