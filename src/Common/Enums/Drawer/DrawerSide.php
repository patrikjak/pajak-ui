<?php

declare(strict_types=1);

namespace Pajak\Ui\Common\Enums\Drawer;

enum DrawerSide: string
{
    case Right = 'right';
    case Left = 'left';
    case Bottom = 'bottom';
    case Top = 'top';
}
