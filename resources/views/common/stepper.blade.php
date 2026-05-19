@use('Pajak\Ui\Common\Enums\Stepper\StepperVariant')

@if($variant === StepperVariant::Bar)
    <div {{ $attributes->merge(['class' => 'pajak-stepper pajak-stepper--bar']) }}>
        <div class="pajak-stepper__head">
            @if($label)
                <span class="pajak-stepper__now">{{ $label }}</span>
            @endif
            <span class="pajak-stepper__sub">{{ __('pajak::ui.stepper.step_of', ['current' => $current, 'total' => $total]) }}</span>
        </div>
        <div class="pajak-stepper__bar" role="progressbar" aria-valuenow="{{ $current }}" aria-valuemin="1" aria-valuemax="{{ $total }}">
            <span style="width: {{ $total > 0 ? round(($current / $total) * 100) : 0 }}%"></span>
        </div>
        <span class="pajak-stepper__pct">{{ $total > 0 ? round(($current / $total) * 100) : 0 }}%</span>
    </div>
@elseif($variant === StepperVariant::Vertical)
    <ol {{ $attributes->merge(['class' => 'pajak-stepper pajak-stepper--vertical']) }}>
        {{ $slot }}
    </ol>
@elseif($variant === StepperVariant::Pill)
    <div {{ $attributes->merge(['class' => 'pajak-stepper pajak-stepper--pill']) }}>
        {{ $slot }}
    </div>
@else
    <ol {{ $attributes->merge(['class' => 'pajak-stepper pajak-stepper--horizontal']) }}>
        {{ $slot }}
    </ol>
@endif
