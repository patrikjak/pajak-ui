@use('Pajak\Ui\Common\Enums\State')

<div class="pajak-field">
    @isset($label)
        <span id="{{ $inputId() }}-label" class="pajak-field__label">{{ $label }}</span>
    @endisset

    <div
        @class([
            'pajak-image-grid',
            'pajak-image-grid--error' => $resolvedState() === State::Error,
            'pajak-image-grid--disabled' => $disabled,
        ])
        data-pajak-image-grid
        data-name="{{ $name }}"
        @if($disabled) data-disabled @endif
        @isset($label) aria-labelledby="{{ $inputId() }}-label" @endisset
    >
        <div class="pajak-image-grid__grid" data-pajak-grid-items>
            @foreach($images as $image)
                <div
                    class="pajak-image-grid__tile pajak-image-grid__tile--existing"
                    data-existing
                    data-file-id="{{ $image->id }}"
                >
                    <img
                        class="pajak-image-grid__thumb"
                        src="{{ $image->url }}"
                        alt="{{ $image->name }}"
                    >
                    <div class="pajak-image-grid__overlay"></div>
                    <input
                        type="hidden"
                        name="{{ $name }}_existing[]"
                        value="{{ $image->id }}"
                        data-existing-input
                    >
                    @if(!$disabled)
                        <button
                            type="button"
                            class="pajak-image-grid__delete"
                            aria-label="{{ __('pajak::ui.form.image_grid.remove') }}"
                        >
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                        </button>
                    @endif
                </div>
            @endforeach

            @if(!$disabled)
                <div
                    class="pajak-image-grid__tile pajak-image-grid__tile--add"
                    role="button"
                    tabindex="0"
                    data-pajak-grid-add
                    aria-label="{{ __('pajak::ui.form.image_grid.add') }}"
                >
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                    <span>{{ __('pajak::ui.form.image_grid.add') }}</span>
                </div>
            @endif
        </div>

        <input
            class="pajak-image-grid__input"
            type="file"
            name="{{ $name }}[]"
            id="{{ $inputId() }}"
            @isset($accept) accept="{{ $accept }}" @endisset
            multiple
            @if($disabled) disabled @endif
            tabindex="-1"
            aria-hidden="true"
            data-pajak-grid-input
        >

        <div style="display:none" aria-hidden="true" data-pajak-grid-deletes></div>
    </div>

    @isset($error)
        <x-pajak-form::field-message>{{ $error }}</x-pajak-form::field-message>
    @endisset
</div>
