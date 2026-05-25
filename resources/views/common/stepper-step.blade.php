@use('Pajak\Ui\Common\Enums\Stepper\StepperStepState')
@use('Pajak\Ui\Common\Enums\Stepper\StepperVariant')

@if($variant === StepperVariant::Pill)
    <span {{ $attributes->merge(['class' => 'pajak-stepper__step'])->class([
        'pajak-stepper__step--done' => $state === StepperStepState::Done,
        'pajak-stepper__step--active' => $state === StepperStepState::Active,
    ]) }}>
        <span class="pajak-stepper__num">
            @if($state === StepperStepState::Done)
                <x-heroicon-o-check width="10" height="10" />
            @else
                {{ $step }}
            @endif
        </span>
        {{ $title }}
    </span>
    @if(!$last)
        <span @class(['pajak-stepper__connector', 'pajak-stepper__connector--done' => $state === StepperStepState::Done])></span>
    @endif
@else
    <li {{ $attributes->merge(['class' => 'pajak-stepper__step'])->class([
        'pajak-stepper__step--done' => $state === StepperStepState::Done,
        'pajak-stepper__step--active' => $state === StepperStepState::Active,
        'pajak-stepper__step--upcoming' => $state === StepperStepState::Upcoming,
    ]) }}>
        <span class="pajak-stepper__col-ind">
            <span class="pajak-stepper__indicator">
                @if($state === StepperStepState::Done)
                    <x-heroicon-o-check width="14" height="14" />
                @else
                    {{ $step }}
                @endif
            </span>
        </span>
        <div class="pajak-stepper__label-block">
            <span class="pajak-stepper__title">{{ $title }}</span>
            @if($description)
                <span class="pajak-stepper__desc">{{ $description }}</span>
            @endif
            @if($slot->isNotEmpty())
                <div class="pajak-stepper__content">{{ $slot }}</div>
            @endif
        </div>
    </li>
@endif
