<div class="pajak-field pajak-field--option">
    <label @class(['pajak-radio', 'pajak-radio--disabled' => $disabled, 'pajak-radio--with-desc' => $description, 'pajak-radio--error' => $error !== null])>
        <input
            class="pajak-radio__input"
            type="radio"
            name="{{ $name }}"
            id="{{ $inputId() }}"
            value="{{ $value }}"
            @if($checked) checked @endif
            @if($disabled) disabled @endif
            {{ $attributes->except(['class', 'type', 'name', 'id', 'value', 'checked', 'disabled']) }}
        >
        <span class="pajak-radio__dot" aria-hidden="true"></span>

        @isset($label)
            <span class="pajak-radio__content">
                <span class="pajak-radio__label">{{ $label }}</span>
                @isset($description)
                    <span class="pajak-radio__description">{{ $description }}</span>
                @endisset
            </span>
        @endisset

        {{ $slot }}
    </label>

    @isset($error)
        <x-pajak-form::field-message>{{ $error }}</x-pajak-form::field-message>
    @endisset
</div>
