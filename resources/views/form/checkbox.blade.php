<div class="pajak-field pajak-field--option">
    <label @class(['pajak-checkbox', 'pajak-checkbox--disabled' => $disabled, 'pajak-checkbox--with-desc' => $description, 'pajak-checkbox--error' => $error !== null])>
        <input
            class="pajak-checkbox__input"
            type="checkbox"
            name="{{ $name }}"
            id="{{ $inputId() }}"
            value="{{ $value }}"
            @if($checked) checked @endif
            @if($disabled) disabled @endif
            {{ $attributes->except(['class', 'type', 'name', 'id', 'value', 'checked', 'disabled']) }}
        >
        @include('pajak::form.partials.checkbox-box')

        <span class="pajak-checkbox__content">
            <span class="pajak-checkbox__label">{!! $label !!}</span>
            @isset($description)
                <span class="pajak-checkbox__description">{{ $description }}</span>
            @endisset
        </span>

        {{ $slot }}
    </label>

    @if(filled($error))
        <x-pajak-form::field-message>{{ $error }}</x-pajak-form::field-message>
    @endif
</div>
