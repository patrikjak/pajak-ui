<?php

declare(strict_types=1);

namespace Pajak\Ui\Common\Enums\StatCard;

enum StatCardTrend: string
{
    case Up = 'up';
    case Down = 'down';
    case Warn = 'warn';
}
