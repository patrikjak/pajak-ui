@use('Pajak\Ui\Common\Enums\State')
<div class="pajak-field">
    @isset($label)
        <span id="{{ $inputId() }}-label" class="pajak-field__label">{{ $label }}</span>
    @endisset

    <div
        @class([
            'pajak-avatar-upload',
            'pajak-avatar-upload--error' => $resolvedState() === State::Error,
            'pajak-avatar-upload--disabled' => $disabled,
            'pajak-avatar-upload--has-image' => $hasImage(),
        ])
        data-pajak-avatar
        data-name="{{ $name }}"
        @if($disabled) data-disabled @endif
        @isset($label) aria-labelledby="{{ $inputId() }}-label" @endisset
    >
        <div class="pajak-avatar-upload__circle">
            @if($hasImage())
                <img
                    class="pajak-avatar-upload__image"
                    src="{{ $src }}"
                    alt=""
                    data-avatar-img
                >
            @endif

            @isset($initials)
                <span class="pajak-avatar-upload__initials" data-avatar-initials>{{ $initials }}</span>
            @endisset

            @if(!$disabled)
                <div class="pajak-avatar-upload__overlay" aria-hidden="true">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/><circle cx="12" cy="13" r="4"/></svg>
                </div>
            @endif

            <input
                class="pajak-avatar-upload__input"
                type="file"
                name="{{ $name }}"
                id="{{ $inputId() }}"
                accept="{{ $resolvedAccept() }}"
                @if($disabled) disabled @endif
                tabindex="-1"
                data-avatar-input
            >
        </div>

        @if(!$disabled)
            <div class="pajak-avatar-upload__actions">
                <button
                    type="button"
                    class="pajak-avatar-upload__btn pajak-avatar-upload__btn--upload"
                    data-avatar-upload-btn
                >
                    {{ __('pajak::ui.form.avatar.upload') }}
                </button>

                <button
                    type="button"
                    class="pajak-avatar-upload__btn pajak-avatar-upload__btn--remove"
                    data-avatar-remove-btn
                    @if(!$hasImage()) hidden @endif
                >
                    {{ __('pajak::ui.form.avatar.remove') }}
                </button>
            </div>
        @endif

        @if($hasImage())
            <input
                type="hidden"
                name="{{ $name }}_existing"
                value="1"
                data-avatar-existing-input
            >
        @endif

        <div style="display:none" aria-hidden="true" data-avatar-deletes></div>
    </div>

    @isset($error)
        <x-pajak-form::field-message>{{ $error }}</x-pajak-form::field-message>
    @endisset
</div>
