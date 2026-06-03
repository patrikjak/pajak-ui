<div
    @class(['pajak-table-wrapper', 'is-restoring' => $table->isAsync()])
    id="pajak-table-{{ $table->name() }}"
    data-pajak-table
    data-table-name="{{ $table->name() }}"
    @if($table->url()) data-url="{{ $table->url() }}" @endif
    @if($table->isAsync()) data-pajak-table-async @endif
>
    <script>
        (function () {
            try {
                var key = 'pajak_table_{{ $table->name() }}';
                var raw = sessionStorage.getItem(key);
                if (raw) {
                    var s = JSON.parse(raw);
                    var hasState = s.search || s.sort || (s.filters && Object.keys(s.filters).length > 0) || s.page > 1 || s.perPage;
                    if (hasState) {
                        document.currentScript.parentElement.classList.add('is-restoring');
                    }
                }
            } catch (e) {}
        })();
    </script>
    {{-- Heading --}}
    @if($table->getHeading())
        <div class="pajak-table-head">
            <h2 class="pajak-table-head__title">{{ $table->getHeading() }}</h2>
        </div>
    @endif

    {{-- Toolbar --}}
    @if($table->isSearchable() || $table->hasFilters() || $table->hasColumnVisibility())
        <div class="pajak-table-toolbar">
            @if($table->isSearchable())
                <x-pajak-table::table-search-input />
            @endif

            <div class="pajak-table-toolbar__right">
                @if($table->hasFilters())
                    <button
                        type="button"
                        class="pajak-table-toolbar__btn"
                        data-pajak-table-filter-toggle
                    >
                        <x-heroicon-o-funnel width="16" height="16" aria-hidden="true" />
                        @lang('pajak::table.filter.add')
                    </button>
                @endif

                @if($table->hasColumnVisibility())
                    <button
                        type="button"
                        class="pajak-table-toolbar__btn"
                        data-pajak-table-columns-toggle
                    >
                        <x-heroicon-o-table-cells width="16" height="16" aria-hidden="true" />
                        @lang('pajak::table.columns.toggle')
                    </button>
                @endif
            </div>
        </div>
    @endif

    {{-- Active filter chips --}}
    @if($table->hasFilters())
        @include('pajak::table.partials.filter-chips', ['table' => $table])
    @endif

    {{-- Bulk bar --}}
    @if($table->hasBulkActions())
        <x-pajak-table::table-bulk-bar :table="$table" />
    @endif

    {{-- Table surface --}}
    <div class="pajak-table-scroll">
        <table class="pajak-table">
            <thead class="pajak-table__head">
                <tr class="pajak-table__row">
                    @if($table->isSelectable())
                        <th class="pajak-table__th pajak-table__th--check">
                            <input
                                type="checkbox"
                                class="pajak-table__select-all"
                                data-pajak-table-select-all
                                aria-label="{{ __('pajak::table.bulk.selected', ['count' => 'all']) }}"
                            >
                        </th>
                    @endif

                    @foreach($table->getColumns() as $column)
                        @if(!$column->isHidden())
                            <x-pajak-table::table-header :column="$column" :currentSort="null" />
                        @endif
                    @endforeach

                    @if($table->hasActions())
                        <th class="pajak-table__th pajak-table__th--actions"></th>
                    @endif
                </tr>
            </thead>

            <tbody class="pajak-table__body" data-pajak-table-body>
                @if($table->isAsync())
                    @foreach(range(1, $table->getSkeletonRows()) as $i)
                        @include('pajak::table.partials.table-skeleton-row', ['table' => $table])
                    @endforeach
                @else
                    @forelse($paginator->items() as $index => $row)
                        <x-pajak-table::table-row :row="$row" :table="$table" :index="$index" />
                    @empty
                        <x-pajak-table::table-empty :columnCount="$table->totalColumnCount()" :hasActiveFilters="false" />
                    @endforelse
                @endif
            </tbody>
        </table>
    </div>

    {{-- Restore loader (hidden in async mode via CSS — skeleton rows serve as the loading indicator) --}}
    <div class="pajak-table-restore-loader" aria-hidden="true">
        <svg class="pajak-spinner pajak-spinner--xl pajak-spinner--primary" viewBox="0 0 24 24" fill="none">
            <circle class="pajak-spinner__track" cx="12" cy="12" r="9" stroke-width="2.5" stroke-dasharray="56.5" stroke-dashoffset="0"/>
            <circle class="pajak-spinner__head" cx="12" cy="12" r="9" stroke-width="2.5" stroke-dasharray="56.5" stroke-dashoffset="38" stroke-linecap="round"/>
        </svg>
    </div>

    {{-- Pagination --}}
    @if($paginator->hasPages() || $table->hasPerPageOptions())
        <x-pajak-table::table-pagination :paginator="$paginator" :table="$table" />
    @endif

    {{-- Filter popovers --}}
    @if($table->hasFilters())
        @foreach($table->getFilters() as $filter)
            <div class="pajak-table-filter-editor" data-pajak-filter-key="{{ $filter->key() }}" hidden>
                @include($filter->editorPartial(), ['filter' => $filter])
            </div>
        @endforeach
    @endif

    {{-- Confirm dialogs --}}
    @include('pajak::table.partials.confirm-dialogs', ['table' => $table])
</div>
