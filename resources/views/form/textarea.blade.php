<div class="pajak-field">
    @isset($label)
        <label class="pajak-field__label" for="{{ $inputId() }}">{{ $labelText() }}</label>
    @endisset

    <textarea
        @class(['pajak-textarea', "pajak-textarea--{$resolvedState()->value}"])
        name="{{ $name }}"
        id="{{ $inputId() }}"
        @isset($placeholder) placeholder="{{ $placeholder }}" @endisset
        rows="{{ $rows }}"
        @if($disabled) disabled @endif
        {{ $attributes->except(['class', 'name', 'id', 'placeholder', 'rows', 'disabled']) }}
    >{{ $value }}</textarea>

    @isset($error)
        <x-pajak-form::field-message>{{ $error }}</x-pajak-form::field-message>
    @endisset
</div>
