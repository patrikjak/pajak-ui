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
                <svg class="pajak-select__chevron" width="16" height="16" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="6 9 12 15 18 9"/>
                </svg>
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
                <svg class="pajak-select__chevron" width="16" height="16" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="6 9 12 15 18 9"/>
                </svg>
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
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                         stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8"/>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"/>
                    </svg>
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
