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
                @include('pajak::form.partials.checkbox-box')

                <span class="pajak-checkbox__content">
                    <span class="pajak-checkbox__label">{{ $optionLabel }}</span>
                </span>
            </label>
        @endforeach
    </div>
    <button type="button" class="pajak-table-filter-editor__apply" data-pajak-filter-apply>
        {{ __('pajak::ui.common.apply') }}
    </button>
</div>
