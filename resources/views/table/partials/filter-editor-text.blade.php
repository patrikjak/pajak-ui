@use('Pajak\Ui\Table\Enums\TextOperator')

<div class="pajak-table-filter-editor__inner">
    <p class="pajak-table-filter-editor__label">{{ $filter->getLabel() }}</p>
    <div class="pajak-select-wrap pajak-select-wrap--default" data-pajak-select data-pajak-filter-operator-select>
        <select class="pajak-select__native" data-pajak-filter-operator>
            @foreach(TextOperator::cases() as $operator)
                <option value="{{ $operator->value }}">{{ $operator->label() }}</option>
            @endforeach
        </select>
        <div class="pajak-select__trigger" aria-hidden="true">
            <span class="pajak-select__value"></span>
            <svg class="pajak-select__chevron" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
        </div>
        <div class="pajak-select__dropdown" role="listbox" hidden></div>
    </div>
    <input
        type="text"
        class="pajak-input pajak-input--sm"
        data-pajak-filter-value
        placeholder="{{ $filter->getLabel() }}"
    >
    <button type="button" class="pajak-table-filter-editor__apply" data-pajak-filter-apply>
        {{ __('pajak::table.filter.apply') }}
    </button>
</div>
