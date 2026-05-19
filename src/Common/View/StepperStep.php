<?php

declare(strict_types=1);

namespace Pajak\Ui\Common\View;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Pajak\Ui\Common\Enums\Stepper\StepperStepState;
use Pajak\Ui\Common\Enums\Stepper\StepperVariant;

final class StepperStep extends Component
{
    public function __construct(
        public readonly string $title,
        public readonly int $step,
        public readonly StepperStepState $state = StepperStepState::Upcoming,
        public readonly StepperVariant $variant = StepperVariant::Horizontal,
        public readonly ?string $description = null,
        public readonly bool $last = false,
    ) {
    }

    public function render(): View
    {
        return view('pajak::common.stepper-step');
    }
}
