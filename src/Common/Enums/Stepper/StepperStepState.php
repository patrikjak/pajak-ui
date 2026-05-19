<?php

declare(strict_types=1);

namespace Pajak\Ui\Common\Enums\Stepper;

enum StepperStepState: string
{
    case Upcoming = 'upcoming';
    case Active = 'active';
    case Done = 'done';
}
