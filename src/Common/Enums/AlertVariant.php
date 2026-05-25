<?php

declare(strict_types=1);

namespace Pajak\Ui\Common\Enums;

enum AlertVariant: string
{
    case Banner = 'banner';
    case Outline = 'outline';
    case Inline = 'inline';
}
