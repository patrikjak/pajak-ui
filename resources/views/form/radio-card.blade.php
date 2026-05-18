<div class="pajak-field">
    <label @class(['pajak-radio-card', 'pajak-radio-card--disabled' => $disabled, 'pajak-radio-card--error' => $error])>
        <input
            class="pajak-radio-card__input"
            type="radio"
            name="{{ $name }}"
            id="{{ $inputId() }}"
            value="{{ $value }}"
            @if($checked) checked @endif
            @if($disabled) disabled @endif
            {{ $attributes->except(['class', 'type', 'name', 'id', 'value', 'checked', 'disabled']) }}
        >
        <span class="pajak-radio-card__dot" aria-hidden="true"></span>

        @if($label || $hint || $slot->isNotEmpty())
            <span class="pajak-radio-card__content">
                @isset($label)
                    <span class="pajak-radio-card__label">{{ $label }}</span>
                @endisset
                @isset($hint)
                    <span class="pajak-radio-card__hint">{{ $hint }}</span>
                @endisset
                {{ $slot }}
            </span>
        @endif
    </label>

    @isset($error)
        <x-pajak-form::field-message>{{ $error }}</x-pajak-form::field-message>
    @endisset
</div>
