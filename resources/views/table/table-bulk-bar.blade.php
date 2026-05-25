<div class="pajak-table-bulk-bar" data-pajak-table-bulk-bar hidden>
    <div class="pajak-table-bulk-bar__info">
        <span
            class="pajak-table-bulk-bar__count"
            data-pajak-table-bulk-count
            data-template="{{ __('pajak::table.bulk.selected', ['count' => ':count']) }}"
        >
            {{ __('pajak::table.bulk.selected', ['count' => 0]) }}
        </span>
        <button type="button" class="pajak-table-bulk-bar__clear" data-pajak-table-bulk-clear>
            {{ __('pajak::table.bulk.clear') }}
        </button>
    </div>

    <div class="pajak-table-bulk-bar__actions">
        @foreach($table->getBulkActions() as $action)
            <button
                type="button"
                @class(['pajak-table-bulk-bar__action', 'pajak-table-bulk-bar__action--danger' => $action->isDanger()])
                data-pajak-table-bulk-action="{{ $action->key() }}"
            >
                {{ $action->getLabel() }}
            </button>
        @endforeach
    </div>
</div>
