<div class="pajak-field">
    <label @class(['pajak-toggle', "pajak-toggle--{$size->value}", 'pajak-toggle--disabled' => $disabled, 'pajak-toggle--error' => $error])>
        <input
            class="pajak-toggle__input"
            type="checkbox"
            name="{{ $name }}"
            id="{{ $inputId() }}"
            role="switch"
            aria-checked="{{ $checked ? 'true' : 'false' }}"
            @if($checked) checked @endif
            @if($disabled) disabled @endif
            {{ $attributes->except(['class', 'type', 'name', 'id', 'role', 'aria-checked', 'checked', 'disabled']) }}
        >
        <span class="pajak-toggle__track" aria-hidden="true">
            <span class="pajak-toggle__thumb"></span>
        </span>

        @isset($label)
            <span class="pajak-toggle__label">{{ $label }}</span>
        @endisset

        {{ $slot }}
    </label>

    @isset($error)
        <x-pajak-form::field-message>{{ $error }}</x-pajak-form::field-message>
    @endisset
</div>
