<?php

declare(strict_types=1);

namespace Pajak\Ui\Common\Enums;

enum AvatarStatus: string
{
    case Online = 'online';
    case Away = 'away';
    case Offline = 'offline';
}
