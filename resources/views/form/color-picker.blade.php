<div
    @class(['pajak-field', 'pajak-color-picker', 'pajak-color-picker--inline' => $inline])
    data-pajak-color-picker
    @if($swatchColor() !== null) style="--pajak-color: {{ $swatchColor() }}" @endif
>
    @isset($label)
        <label class="pajak-field__label" for="{{ $inputId() }}">{{ $label }}</label>
    @endisset

    <div @class(['pajak-input-wrap', 'pajak-input-wrap--has-icon', "pajak-input-wrap--$size->value"])>
        @unless($inline)
            <button
                type="button"
                @class(['pajak-color-picker__swatch', 'is-empty' => !$hasValidValue()])
                aria-label="{{ __('pajak::ui.color_picker.choose') }}"
                aria-haspopup="dialog"
                aria-expanded="false"
                data-pajak-color-swatch
                @if($disabled) disabled @endif
            ></button>
        @endunless

        <input
            @class(['pajak-input', "pajak-input--{$size->value}", "pajak-input--{$resolvedState()->value}", 'pajak-color-picker__text'])
            type="text"
            name="{{ $name }}"
            id="{{ $inputId() }}"
            maxlength="7"
            pattern="{{ $hexPattern() }}"
            autocomplete="off"
            spellcheck="false"
            @isset($placeholder) placeholder="{{ $placeholder }}" @endisset
            @isset($value) value="{{ $value }}" @endisset
            @if($disabled) disabled @endif
            data-pajak-color-text
            {{ $attributes->except(['class', 'type', 'name', 'id', 'placeholder', 'value', 'disabled', 'pattern', 'maxlength']) }}
        >

        @unless($inline)
            @include('pajak::form.partials.color-picker-panel')
        @endunless
    </div>

    @if($inline)
        @include('pajak::form.partials.color-picker-panel')
    @endif

    @if(filled($error))
        <x-pajak-form::field-message>{{ $error }}</x-pajak-form::field-message>
    @endif
</div>
