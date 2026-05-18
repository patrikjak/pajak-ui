@use('Pajak\Ui\Common\Enums\Method')

@php(assert($method instanceof Method))

<form
    {{ $attributes->merge(['class' => 'pajak-form pajak-form--' . $layout->value])->except(['action', 'method']) }}
    action="{{ $action }}"
    method="{{ $method->htmlMethod() }}"
    data-pajak-form
    @isset($redirect) data-redirect="{{ $redirect }}" @endisset
>
    @csrf

    @if($method->needsSpoofing())
        @method($method->value)
    @endif

    {{ $slot }}

    @if(!$hideSubmit)
        <div class="pajak-form__actions">
            <x-pajak::button type="submit" :size="$submitSize">
                {{ $resolvedSubmitText() }}
            </x-pajak::button>
        </div>
    @endif
</form>
