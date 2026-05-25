<div class="pajak-field pajak-field--option">
    <label @class(['pajak-checkbox', 'pajak-checkbox--disabled' => $disabled, 'pajak-checkbox--with-desc' => $description, 'pajak-checkbox--error' => $error !== null])>
        <input
            class="pajak-checkbox__input"
            type="checkbox"
            name="{{ $name }}"
            id="{{ $inputId() }}"
            value="{{ $value }}"
            @if($checked) checked @endif
            @if($disabled) disabled @endif
            {{ $attributes->except(['class', 'type', 'name', 'id', 'value', 'checked', 'disabled']) }}
        >
        <span class="pajak-checkbox__box" aria-hidden="true">
            <svg class="pajak-checkbox__indet" width="11" height="2" viewBox="0 0 11 2">
                <rect width="11" height="2" rx="1"/>
            </svg>
        </span>

        <span class="pajak-checkbox__content">
            <span class="pajak-checkbox__label">{{ $label }}</span>
            @isset($description)
                <span class="pajak-checkbox__description">{{ $description }}</span>
            @endisset
        </span>

        {{ $slot }}
    </label>

    @isset($error)
        <x-pajak-form::field-message>{{ $error }}</x-pajak-form::field-message>
    @endisset
</div>
