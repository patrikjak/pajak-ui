@props(['confirmationIcon' => null])

<div class="pajak-password">
    <div class="pajak-field">
        <label class="pajak-field__label" for="{{ $inputId() }}">
            {{ $labelText() }}
        </label>
        <div @class(['pajak-input-wrap', sprintf('pajak-input-wrap--%s', $size->value), 'pajak-input-wrap--has-icon' => isset($icon) && $icon->isNotEmpty(), 'pajak-input-wrap--has-toggle' => $withToggle])>
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

            @if($withToggle)
                <button
                    type="button"
                    class="pajak-input-wrap__toggle"
                    data-pajak-password-toggle="{{ $inputId() }}"
                    aria-label="{{ __('pajak::ui.form.password.show') }}"
                    data-hide-label="{{ __('pajak::ui.form.password.hide') }}"
                    aria-pressed="false"
                >
                    <x-heroicon-o-eye class="pajak-input-wrap__toggle-icon pajak-input-wrap__toggle-icon--show" />
                    <x-heroicon-o-eye-slash class="pajak-input-wrap__toggle-icon pajak-input-wrap__toggle-icon--hide" />
                </button>
            @endif
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
            <div @class(['pajak-input-wrap', sprintf('pajak-input-wrap--%s', $size->value), 'pajak-input-wrap--has-icon' => $confirmationIcon && $confirmationIcon->isNotEmpty(), 'pajak-input-wrap--has-toggle' => $withToggle])>
                @if($confirmationIcon && $confirmationIcon->isNotEmpty())
                    <span class="pajak-input-wrap__icon" aria-hidden="true">
                        {{ $confirmationIcon }}
                    </span>
                @endif

                <input
                    @class(['pajak-input', sprintf('pajak-input--%s', $size->value), sprintf('pajak-input--%s', $confirmationState()->value)])
                    type="password"
                    name="{{ $name }}_confirmation"
                    id="{{ $confirmationId() }}"
                    @isset($confirmationPlaceholder) placeholder="{{ $confirmationPlaceholder }}" @endisset
                    autocomplete="{{ $confirmationAutocomplete }}"
                    @if($disabled) disabled @endif
                >

                @if($withToggle)
                    <button
                        type="button"
                        class="pajak-input-wrap__toggle"
                        data-pajak-password-toggle="{{ $confirmationId() }}"
                        aria-label="{{ __('pajak::ui.form.password.show') }}"
                        data-hide-label="{{ __('pajak::ui.form.password.hide') }}"
                        aria-pressed="false"
                    >
                        <x-heroicon-o-eye class="pajak-input-wrap__toggle-icon pajak-input-wrap__toggle-icon--show" />
                        <x-heroicon-o-eye-slash class="pajak-input-wrap__toggle-icon pajak-input-wrap__toggle-icon--hide" />
                    </button>
                @endif
            </div>

            @isset($confirmationError)
                <x-pajak-form::field-message>{{ $confirmationError }}</x-pajak-form::field-message>
            @endisset
        </div>
    @endif
</div>
