@use('Carbon\Carbon')
@use('Pajak\Ui\Table\Columns\DateColumn')
@php(assert($column instanceof DateColumn))

@php($rawValue = is_array($row) ? ($row[$column->key()] ?? null) : ($row->{$column->key()} ?? null))
@php($formatted = $rawValue ? Carbon::parse($rawValue)->format($column->dateFormat()) : '')

<td class="pajak-table__td" data-column-key="{{ $column->key() }}">
    {{ $formatted }}
</td>
