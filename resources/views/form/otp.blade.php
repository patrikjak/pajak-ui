<div
    {{ $attributes->class(['pajak-field'])->merge([
        'data-pajak-otp' => '',
        'data-numeric' => $numeric ? 'true' : 'false',
        'data-autosubmit' => $autosubmit ? 'true' : 'false',
    ]) }}
>
    <label class="pajak-field__label" id="{{ $labelId() }}" for="{{ $boxId(1) }}">{{ $labelText() }}</label>

    <div class="pajak-otp" role="group" aria-labelledby="{{ $labelId() }}">
        @foreach($positions() as $position)
            <input
                @class(['pajak-otp__box', "pajak-otp__box--{$size->value}", 'pajak-otp__box--error' => $hasError()])
                type="text"
                id="{{ $boxId($position) }}"
                autocomplete="{{ $position === 1 ? 'one-time-code' : 'off' }}"
                aria-label="{{ $boxLabel($position) }}"
                @if($numeric) inputmode="numeric" pattern="[0-9]*" @endif
                @if($autofocus && $position === 1) autofocus @endif
                @if($disabled) disabled @endif
            >
        @endforeach

        <input type="hidden" name="{{ $name }}" @if($disabled) disabled @endif>
    </div>

    @if($hasError())
        <x-pajak-form::field-message>{{ $error }}</x-pajak-form::field-message>
    @endif
</div>
