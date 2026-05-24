<div
    class="pajak-table-wrapper"
    id="pajak-table-{{ $table->name() }}"
    data-pajak-table
    data-table-name="{{ $table->name() }}"
    @if($table->url()) data-url="{{ $table->url() }}" @endif
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
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/></svg>
                        {{ __('pajak::table.filter.add') }}
                    </button>
                @endif

                @if($table->hasColumnVisibility())
                    <button
                        type="button"
                        class="pajak-table-toolbar__btn"
                        data-pajak-table-columns-toggle
                    >
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="3" width="18" height="18" rx="2"/><line x1="9" y1="3" x2="9" y2="21"/><line x1="15" y1="3" x2="15" y2="21"/></svg>
                        {{ __('pajak::table.columns.toggle') }}
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
                @forelse($paginator->items() as $index => $row)
                    <x-pajak-table::table-row :row="$row" :table="$table" :index="$index" />
                @empty
                    @php
                        $req = request()->all();
                        $hasActiveFilters = !empty($req['search']) || !empty($req['filters']);
                    @endphp
                    <x-pajak-table::table-empty :columnCount="$table->totalColumnCount()" :hasActiveFilters="$hasActiveFilters" />
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Restore loader --}}
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
