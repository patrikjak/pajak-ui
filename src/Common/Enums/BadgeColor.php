<?php

declare(strict_types=1);

namespace Pajak\Ui\Common\Enums;

enum BadgeColor: string
{
    case Primary = 'primary';
    case Success = 'success';
    case Warning = 'warning';
    case Error = 'error';
    case Info = 'info';
    case Neutral = 'neutral';
}
