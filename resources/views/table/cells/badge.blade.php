@use('Pajak\Ui\Table\Columns\BadgeColumn')
@php(assert($column instanceof BadgeColumn))

@php($cellValue = is_array($row) ? ($row[$column->key()] ?? '') : ($row->{$column->key()} ?? ''))
@php($cellColor = $column->colorFor($cellValue))

<td class="pajak-table__td" data-column-key="{{ $column->key() }}">
    <span @class(['pajak-table-badge', "pajak-table-badge--$cellColor" => $cellColor !== null])>
        {{ $cellValue }}
    </span>
</td>
