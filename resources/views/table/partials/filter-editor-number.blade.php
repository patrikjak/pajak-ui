<div class="pajak-table-filter-editor__inner">
    <p class="pajak-table-filter-editor__label">{{ $filter->getLabel() }}</p>
    <div class="pajak-table-filter-editor__range">
        <input
            type="number"
            class="pajak-input pajak-input--sm"
            data-pajak-filter-min
            placeholder="{{ __('pajak::table.filter.min') }}"
        >
        <span class="pajak-table-filter-editor__range-sep">–</span>
        <input
            type="number"
            class="pajak-input pajak-input--sm"
            data-pajak-filter-max
            placeholder="{{ __('pajak::table.filter.max') }}"
        >
    </div>
    <button type="button" class="pajak-table-filter-editor__apply" data-pajak-filter-apply>
        {{ __('pajak::table.filter.apply') }}
    </button>
</div>
