<?php

declare(strict_types=1);

namespace Pajak\Ui\Common\Enums;

enum BannerType: string
{
    case Info = 'info';
    case Success = 'success';
    case Warning = 'warning';
    case Error = 'error';
    case Neutral = 'neutral';
    case Promo = 'promo';
}
