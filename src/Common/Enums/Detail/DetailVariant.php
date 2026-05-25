<?php

declare(strict_types=1);

namespace Pajak\Ui\Common\Enums\Detail;

enum DetailVariant: string
{
    case Default = 'default';
    case Compact = 'compact';
    case Grid2 = 'grid-2';
    case Flush = 'flush';
}
