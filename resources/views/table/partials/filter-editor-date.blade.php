<div class="pajak-table-filter-editor__inner">
    <p class="pajak-table-filter-editor__label">{{ $filter->getLabel() }}</p>
    <x-pajak-calendar::date-picker
        name="filter_{{ $filter->key() }}"
        :range="true"
        :placeholder="__('pajak::table.filter.from') . ' – ' . __('pajak::table.filter.to')"
        data-pajak-filter-date-range
    />
    <button type="button" class="pajak-table-filter-editor__apply" data-pajak-filter-apply>
        {{ __('pajak::ui.common.apply') }}
    </button>
</div>
