@use('Pajak\Ui\Common\Enums\State')

<div class="pajak-field">
    @isset($label)
        <label class="pajak-field__label" for="{{ $inputId() }}">{{ $label }}</label>
    @endisset

    <label
        @class([
            'pajak-file',
            'pajak-file--error'  => $resolvedState() === State::Error,
            'pajak-file--disabled' => $disabled,
        ])
        for="{{ $inputId() }}"
        data-pajak-file
    >
        <span class="pajak-file__icon" aria-hidden="true">
            <x-heroicon-o-arrow-up-tray width="16" height="16" />
        </span>
        <span
            class="pajak-file__text"
            data-pajak-file-text
        >{{ $placeholder ?? __('pajak::ui.form.file.placeholder') }}</span>
        <input
            class="pajak-file__input"
            type="file"
            name="{{ $name }}"
            id="{{ $inputId() }}"
            @isset($accept) accept="{{ $accept }}" @endisset
            @if($disabled) disabled @endif
            data-placeholder="{{ $placeholder ?? __('pajak::ui.form.file.placeholder') }}"
            {{ $attributes->except(['class', 'type', 'name', 'id', 'accept', 'disabled', 'data-placeholder']) }}
        >
    </label>

    @isset($error)
        <x-pajak-form::field-message>{{ $error }}</x-pajak-form::field-message>
    @endisset
</div>
