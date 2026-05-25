<?php

declare(strict_types=1);

namespace Pajak\Ui\Common\Enums;

enum Variant: string
{
    case Primary = 'primary';
    case Secondary = 'secondary';
    case Outline = 'outline';
    case Ghost = 'ghost';
    case Danger = 'danger';
}
