<?php

declare(strict_types=1);

namespace Pajak\Ui\Common\View;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Pajak\Ui\Common\Enums\Stepper\StepperVariant;

final class Stepper extends Component
{
    public function __construct(
        public readonly StepperVariant $variant = StepperVariant::Horizontal,
        public readonly int $current = 1,
        public readonly int $total = 1,
        public readonly ?string $label = null,
    ) {
    }

    public function render(): View
    {
        return view('pajak::common.stepper');
    }
}
