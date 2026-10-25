@use('Pajak\Ui\Table\Columns\BadgeListColumn')
@php(assert($column instanceof BadgeListColumn))

@php($items = $column->items($row))

<td class="pajak-table__td" data-column-key="{{ $column->key() }}">
    <div class="pajak-table-badge-list">
        @forelse($items as $item)
            @php($itemColor = $column->colorFor($item))
            <span @class(['pajak-table-badge', "pajak-table-badge--$itemColor" => $itemColor !== null])>
                {{ $column->labelFor($item, $row) }}
            </span>
        @empty
            @if($column->emptyText() !== '')
                <span class="pajak-table-badge-list__empty">{{ $column->emptyText() }}</span>
            @endif
        @endforelse
    </div>
</td>
