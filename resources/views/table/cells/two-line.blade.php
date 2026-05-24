@use('Pajak\Ui\Table\Columns\TwoLineColumn')
@php(assert($column instanceof TwoLineColumn))

@php($primaryValue = is_array($row) ? ($row[$column->key()] ?? '') : ($row->{$column->key()} ?? ''))
@php($secondaryValue = $column->secondaryKey() ? (is_array($row) ? ($row[$column->secondaryKey()] ?? '') : ($row->{$column->secondaryKey()} ?? '')) : null)

<td class="pajak-table__td" data-column-key="{{ $column->key() }}">
    <div class="pajak-table-two-line">
        <span class="pajak-table-two-line__primary">{{ $primaryValue }}</span>
        @if($secondaryValue !== null && $secondaryValue !== '')
            <span class="pajak-table-two-line__secondary">{{ $secondaryValue }}</span>
        @endif
    </div>
</td>
