@use('Illuminate\Support\Collection')

@php
    $current = $paginator->currentPage();
    $last = $paginator->lastPage();

    $pages = new Collection(range(1, $last))
        ->filter(fn(int $p) => $p === 1 || $p === $last || abs($p - $current) <= 1)
        ->values();

    $slots = [];
    foreach ($pages as $i => $p) {
        if ($i > 0 && $p - $pages[$i - 1] > 1) {
            $slots[] = null;
        }
        $slots[] = $p;
    }
@endphp
<div class="pajak-table-pagination">
    <span class="pajak-table-pagination__info">
        {{ __('pajak::table.pagination.page') }} {{ $current }} {{ __('pajak::table.pagination.of') }} {{ $last }}
        &nbsp;&mdash;&nbsp;
        {{ $paginator->total() }} {{ __('pajak::table.pagination.results') }}
    </span>

    <div class="pajak-table-pagination__controls">
        @if($table->hasPerPageOptions())
            @php
                $perPageOptions = new Collection($table->getPerPageOptions())
                    ->mapWithKeys(fn(int $opt) => [$opt => (string) $opt])
                    ->all();
            @endphp
            <x-pajak-form::select
                name="per_page"
                :value="$paginator->perPage()"
                :options="$perPageOptions"
                data-pajak-table-per-page
                aria-label="{{ __('pajak::table.pagination.per_page') }}"
            />
        @endif

        <div class="pajak-table-pagination__pager">
            <button
                type="button"
                class="pajak-table-pagination__btn pajak-table-pagination__btn--icon"
                data-pajak-table-page="{{ $current - 1 }}"
                @disabled($paginator->onFirstPage())
                aria-label="{{ __('pajak::table.pagination.previous') }}"
            >
                <x-heroicon-o-chevron-left width="13" height="13" aria-hidden="true" />
            </button>

            @foreach($slots as $slot)
                @if($slot === null)
                    <span class="pajak-table-pagination__ellipsis" aria-hidden="true">…</span>
                @else
                    <button
                        type="button"
                        class="pajak-table-pagination__btn pajak-table-pagination__btn--page @if($slot === $current) is-current @endif"
                        data-pajak-table-page="{{ $slot }}"
                        @disabled($slot === $current)
                        aria-label="{{ __('pajak::table.pagination.page') }} {{ $slot }}"
                        @if($slot === $current) aria-current="page" @endif
                    >{{ $slot }}</button>
                @endif
            @endforeach

            <button
                type="button"
                class="pajak-table-pagination__btn pajak-table-pagination__btn--icon"
                data-pajak-table-page="{{ $current + 1 }}"
                @disabled($paginator->onLastPage())
                aria-label="{{ __('pajak::table.pagination.next') }}"
            >
                <x-heroicon-o-chevron-right width="13" height="13" aria-hidden="true" />
            </button>
        </div>
    </div>
</div>
