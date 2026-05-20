<?php

declare(strict_types=1);

namespace Pajak\Ui\Common\Enums\Popover;

enum PopoverPlacement: string
{
    case Bottom = 'bottom';
    case BottomEnd = 'bottom-end';
    case Top = 'top';
    case Right = 'right';
    case Left = 'left';
}
