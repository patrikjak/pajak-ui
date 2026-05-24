@use('Pajak\Ui\Common\ValueObject\Money')
@use('Pajak\Ui\Table\Contracts\TableColumn')
@php(assert($column instanceof TableColumn))

@php
    $cellValue = is_array($row) ? ($row[$column->key()] ?? null) : ($row->{$column->key()} ?? null);
    $isNegative = $cellValue instanceof Money && $cellValue->minorUnits < 0;

    $formatted = $cellValue instanceof Money ? (string) $cellValue : null;
@endphp

<td class="pajak-table__td pajak-table__td--amount" data-column-key="{{ $column->key() }}">
    @if($formatted !== null)
        <span @class(['pajak-table-amount', 'pajak-table-amount--negative' => $isNegative])>
            {{ $formatted }}
        </span>
    @endif
</td>
