@use('Pajak\Ui\Common\Enums\State')

<div class="pajak-field">
    @isset($label)
        <label class="pajak-field__label" for="{{ $inputId() }}">{{ $label }}</label>
    @endisset

    <div
        @class([
            'pajak-dropzone',
            'pajak-dropzone--error' => $resolvedState() === State::Error,
            'pajak-dropzone--disabled' => $disabled,
        ])
        data-pajak-dropzone
        data-name="{{ $name }}"
        @if($multiple) data-multiple @endif
        @if($disabled) data-disabled @endif
    >
        <div
            class="pajak-dropzone__zone"
            role="button"
            tabindex="{{ $disabled ? '-1' : '0' }}"
            aria-label="{{ __('pajak::ui.form.dropzone.aria_label') }}"
        >
            <span class="pajak-dropzone__icon" aria-hidden="true">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
            </span>
            <span class="pajak-dropzone__title">{{ __('pajak::ui.form.dropzone.title') }}</span>
            <span class="pajak-dropzone__sub">{{ __('pajak::ui.form.dropzone.sub') }}</span>
        </div>

        <input
            class="pajak-dropzone__input"
            type="file"
            id="{{ $inputId() }}"
            name="{{ $name }}[]"
            @if($multiple) multiple @endif
            @isset($accept) accept="{{ $accept }}" @endisset
            @if($disabled) disabled @endif
            tabindex="-1"
            aria-hidden="true"
        >

        <div class="pajak-dropzone__list" data-pajak-dropzone-list>
            @foreach($files as $file)
                <div
                    @class(['pajak-dropzone__item', 'pajak-dropzone__item--existing'])
                    data-existing
                    data-file-id="{{ $file->id }}"
                    @if($file->url !== null) data-image @endif
                >
                    <span class="pajak-dropzone__item-icon" aria-hidden="true">
                        @if($file->url !== null)
                            <img class="pajak-dropzone__item-thumb" src="{{ $file->url }}" alt="">
                        @else
                            {{ strtoupper(substr(pathinfo($file->name, PATHINFO_EXTENSION), 0, 4)) ?: 'FILE' }}
                        @endif
                    </span>
                    <span class="pajak-dropzone__item-info">
                        <span class="pajak-dropzone__item-name">{{ $file->name }}</span>
                        @isset($file->size)
                            <span class="pajak-dropzone__item-size">{{ $formatSize($file->size) }}</span>
                        @endisset
                    </span>
                    <input
                        type="hidden"
                        name="{{ $name }}_existing[]"
                        value="{{ $file->id }}"
                        data-existing-input
                    >
                    @if(!$disabled)
                        <button
                            type="button"
                            class="pajak-dropzone__item-remove"
                            aria-label="{{ __('pajak::ui.form.dropzone.remove') }}"
                        >
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                        </button>
                    @endif
                </div>
            @endforeach
        </div>

        <div style="display:none" aria-hidden="true" data-pajak-dropzone-inputs></div>
        <div style="display:none" aria-hidden="true" data-pajak-dropzone-deletes></div>
    </div>

    @isset($error)
        <x-pajak-form::field-message>{{ $error }}</x-pajak-form::field-message>
    @endisset
</div>
