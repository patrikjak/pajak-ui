<div class="pajak-table-filter-editor__inner">
    <p class="pajak-table-filter-editor__label">{{ $filter->getLabel() }}</p>
    <div class="pajak-table-filter-editor__options">
        @foreach($filter->getOptions() as $value => $optionLabel)
            <label class="pajak-checkbox">
                <input
                    type="checkbox"
                    class="pajak-checkbox__input"
                    data-pajak-filter-option
                    value="{{ $value }}"
                >
                <span class="pajak-checkbox__box" aria-hidden="true">
                    <svg class="pajak-checkbox__indet" width="11" height="2" viewBox="0 0 11 2">
                        <rect width="11" height="2" rx="1"/>
                    </svg>
                </span>

                <span class="pajak-checkbox__content">
                    <span class="pajak-checkbox__label">{{ $optionLabel }}</span>
                </span>
            </label>
        @endforeach
    </div>
    <button type="button" class="pajak-table-filter-editor__apply" data-pajak-filter-apply>
        {{ __('pajak::table.filter.apply') }}
    </button>
</div>
