<?php

declare(strict_types=1);

namespace Pajak\Ui\Common\Enums\Stepper;

enum StepperVariant: string
{
    case Horizontal = 'horizontal';
    case Pill = 'pill';
    case Vertical = 'vertical';
    case Bar = 'bar';
}
