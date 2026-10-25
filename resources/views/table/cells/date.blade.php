@use('Carbon\Carbon')
@use('Pajak\Ui\Table\Columns\DateColumn')
@php(assert($column instanceof DateColumn))

@php($rawValue = is_array($row) ? ($row[$column->key()] ?? null) : ($row->{$column->key()} ?? null))
@php($displayTimezone = $column->resolveTimezone())
@php($parsed = $rawValue ? Carbon::parse($rawValue) : null)
@php($formatted = $parsed ? ($displayTimezone ? $parsed->setTimezone($displayTimezone) : $parsed)->format($column->dateFormat()) : '')

<td class="pajak-table__td" data-column-key="{{ $column->key() }}">
    {{ $formatted }}
</td>
