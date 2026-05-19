<?php

declare(strict_types=1);

namespace Pajak\Ui\Common\Enums;

enum SpinnerColor: string
{
    case Primary = 'primary';
    case Neutral = 'neutral';
    case Muted = 'muted';
    case White = 'white';
    case Success = 'success';
    case Danger = 'danger';
}
