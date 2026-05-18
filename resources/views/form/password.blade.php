<div class="pajak-password">
    <div class="pajak-field">
        <label class="pajak-field__label" for="{{ $inputId() }}">
            {{ $labelText() }}
        </label>
        <div @class(['pajak-input-wrap', sprintf('pajak-input-wrap--%s', $size->value), 'pajak-input-wrap--has-icon' => isset($icon) && $icon->isNotEmpty()])>
            @isset($icon)
                <span class="pajak-input-wrap__icon" aria-hidden="true">
                    {{ $icon }}
                </span>
            @endisset

            <input
                @class(['pajak-input', sprintf('pajak-input--%s', $size->value), sprintf('pajak-input--%s', $resolvedState()->value)])
                type="password"
                name="{{ $name }}"
                id="{{ $inputId() }}"
                @isset($placeholder) placeholder="{{ $placeholder }}" @endisset
                @isset($value) value="{{ $value }}" @endisset
                autocomplete="{{ $autocomplete }}"
                @if($disabled) disabled @endif
                {{ $attributes->except(['class', 'type', 'name', 'id', 'placeholder', 'value', 'disabled', 'autocomplete']) }}
            >
        </div>

        @isset($error)
            <x-pajak-form::field-message>{{ $error }}</x-pajak-form::field-message>
        @endisset
    </div>

    @if($confirmation)
        <div class="pajak-field">
            <label class="pajak-field__label" for="{{ $confirmationId() }}">
                {{ $confirmationLabelText() }}
            </label>
            <div @class(['pajak-input-wrap', sprintf('pajak-input-wrap--%s', $size->value)])>
                <input
                    @class(['pajak-input', sprintf('pajak-input--%s', $size->value), sprintf('pajak-input--%s', $confirmationState()->value)])
                    type="password"
                    name="{{ $name }}_confirmation"
                    id="{{ $confirmationId() }}"
                    @isset($confirmationPlaceholder) placeholder="{{ $confirmationPlaceholder }}" @endisset
                    autocomplete="{{ $confirmationAutocomplete }}"
                    @if($disabled) disabled @endif
                >
            </div>

            @isset($confirmationError)
                <x-pajak-form::field-message>{{ $confirmationError }}</x-pajak-form::field-message>
            @endisset
        </div>
    @endif
</div>
