<div class="pajak-field">
    @isset($label)
        <label class="pajak-field__label" for="{{ $inputId() }}">{{ $labelText() }}</label>
    @endisset

    <div
        @class([
            'pajak-select-wrap',
            sprintf('pajak-select-wrap--%s', $resolvedState()->value),
            'pajak-select-wrap--disabled' => $disabled,
            'pajak-select-wrap--multiple' => $multiple,
        ])
        data-pajak-select
        @if($searchable) data-searchable @endif
        @if($multiple) data-multiple @endif
        data-placeholder="{{ $resolvedPlaceholder() }}"
        data-search-placeholder="{{ $resolvedSearchPlaceholder() }}"
    >
        <select
            class="pajak-select__native"
            name="{{ $name }}{{ $multiple ? '[]' : '' }}"
            id="{{ $inputId() }}"
            @if($disabled) disabled @endif
            @if($multiple) multiple @endif
            {{ $attributes->except(['class', 'name', 'id', 'disabled', 'multiple']) }}
        >
            @if(!$multiple)
                <option value="" disabled @if($value === null) selected @endif>{{ $resolvedPlaceholder() }}</option>
            @endif

            @foreach($options as $groupOrValue => $labelOrGroup)
                @if(is_iterable($labelOrGroup))
                    <optgroup label="{{ $groupOrValue }}">
                        @foreach($labelOrGroup as $optValue => $optLabel)
                            <option
                                value="{{ $optValue }}"
                                @if($multiple && is_array($value) && in_array((string) $optValue, array_map('strval', $value))) selected
                                @elseif(!$multiple && (string) $optValue === (string) $value) selected
                                @endif
                            >{{ $optLabel }}</option>
                        @endforeach
                    </optgroup>
                @else
                    <option
                        value="{{ $groupOrValue }}"
                        @if($multiple && is_array($value) && in_array((string) $groupOrValue, array_map('strval', $value))) selected
                        @elseif(!$multiple && (string) $groupOrValue === (string) $value) selected
                        @endif
                    >{{ $labelOrGroup }}</option>
                @endif
            @endforeach
            {{ $slot }}
        </select>

        {{-- Single-select trigger (hidden in multi mode) --}}
        @if(!$multiple)
            <div class="pajak-select__trigger" aria-hidden="true">
                <span class="pajak-select__value">{{ $resolvedPlaceholder() }}</span>
                <x-heroicon-o-chevron-down class="pajak-select__chevron" width="16" height="16" />
            </div>
        @endif

        {{-- Multi-select trigger — chip container --}}
        @if($multiple)
            <div class="pajak-select__trigger pajak-select__trigger--multi" aria-hidden="true">
                <div class="pajak-select__chips"></div>
                <input
                    class="pajak-select__chip-input"
                    type="text"
                    placeholder="{{ $resolvedPlaceholder() }}"
                    autocomplete="off"
                    aria-autocomplete="list"
                    aria-label="{{ $labelText() ?? $resolvedPlaceholder() }}"
                >
                <x-heroicon-o-chevron-down class="pajak-select__chevron" width="16" height="16" />
            </div>
        @endif

        <div
            class="pajak-select__dropdown"
            role="listbox"
            hidden
            @if($multiple) aria-multiselectable="true" @endif
            @isset($label) aria-label="{{ $labelText() }}" @endisset
        >
            @if($searchable && !$multiple)
                <div class="pajak-select__search">
                    <x-heroicon-o-magnifying-glass width="14" height="14" />
                    <input
                        class="pajak-select__search-input"
                        type="text"
                        placeholder="{{ $resolvedSearchPlaceholder() }}"
                        autocomplete="off"
                        aria-label="{{ $resolvedSearchPlaceholder() }}"
                    >
                </div>
            @endif
            {{-- Options populated by JS --}}
        </div>
    </div>

    @isset($error)
        <x-pajak-form::field-message>{{ $error }}</x-pajak-form::field-message>
    @endisset
</div>
