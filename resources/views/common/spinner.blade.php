@use('Pajak\Ui\Common\Enums\SpinnerType')

@if($type === SpinnerType::Arc)
    <svg {{ $attributes->merge(['class' => 'pajak-spinner'])->class(["pajak-spinner--$size->value", "pajak-spinner--$color->value"]) }}
         viewBox="0 0 24 24" fill="none" stroke-linecap="round"
         stroke-width="{{ $strokeWidth }}"
         aria-hidden="true">
        <circle class="pajak-spinner__track" cx="12" cy="12" r="9"/>
        <circle class="pajak-spinner__head" cx="12" cy="12" r="9" stroke-dasharray="56.5" stroke-dashoffset="38"/>
    </svg>
@elseif($type === SpinnerType::Dots)
    <span {{ $attributes->merge(['class' => 'pajak-spinner-dots'])->class(["pajak-spinner-dots--$color->value"]) }}
          aria-hidden="true">
        <span></span>
        <span></span>
        <span></span>
    </span>
@elseif($type === SpinnerType::Bar)
    <div {{ $attributes->merge(['class' => 'pajak-spinner-bar']) }} aria-hidden="true"></div>
@endif
