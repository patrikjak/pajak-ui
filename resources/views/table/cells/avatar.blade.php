@use('Pajak\Ui\Table\Columns\AvatarColumn')
@php(assert($column instanceof AvatarColumn))

@php($nameValue = is_array($row) ? ($row[$column->key()] ?? '') : ($row->{$column->key()} ?? ''))
@php($subtitleValue = $column->subtitleKey() ? (is_array($row) ? ($row[$column->subtitleKey()] ?? null) : ($row->{$column->subtitleKey()} ?? null)) : null)
@php($imageValue = $column->imageKey() ? (is_array($row) ? ($row[$column->imageKey()] ?? null) : ($row->{$column->imageKey()} ?? null)) : null)

<td class="pajak-table__td" data-column-key="{{ $column->key() }}">
    <div class="pajak-table-avatar">
        <div class="pajak-table-avatar__circle">
            @if($imageValue)
                <img src="{{ $imageValue }}" alt="{{ $nameValue }}" class="pajak-table-avatar__img">
            @else
                <span class="pajak-table-avatar__initials">{{ mb_strtoupper(mb_substr((string) $nameValue, 0, 1)) }}</span>
            @endif
        </div>
        <div class="pajak-table-avatar__body">
            <span class="pajak-table-avatar__name">{{ $nameValue }}</span>
            @if($subtitleValue !== null && $subtitleValue !== '')
                <span class="pajak-table-avatar__subtitle">{{ $subtitleValue }}</span>
            @endif
        </div>
    </div>
</td>
