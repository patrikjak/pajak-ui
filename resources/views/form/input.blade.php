<div class="pajak-field">
    @isset($label)
        <label class="pajak-field__label" for="{{ $inputId() }}">{{ $labelText() }}</label>
    @endisset

    <div @class(['pajak-input-wrap', "pajak-input-wrap--$size->value", 'pajak-input-wrap--has-icon' => isset($icon) && $icon->isNotEmpty()])>
        @isset($icon)
            <span class="pajak-input-wrap__icon" aria-hidden="true">
                {{ $icon }}
            </span>
        @endisset

        <input
            @class(['pajak-input', "pajak-input--{$size->value}", "pajak-input--{$resolvedState()->value}"])
            type="{{ $type }}"
            name="{{ $name }}"
            id="{{ $inputId() }}"
            @isset($placeholder) placeholder="{{ $placeholder }}" @endisset
            @isset($value) value="{{ $value }}" @endisset
            @isset($autocomplete) autocomplete="{{ $autocomplete }}" @endisset
            @isset($pattern) pattern="{{ $pattern }}" @endisset
            @if($disabled) disabled @endif
            {{ $attributes->except(['class', 'type', 'name', 'id', 'placeholder', 'value', 'disabled', 'autocomplete', 'pattern']) }}
        >
    </div>

    @isset($error)
        <x-pajak-form::field-message>{{ $error }}</x-pajak-form::field-message>
    @endisset
</div>
