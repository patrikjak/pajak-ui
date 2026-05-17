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
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
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
