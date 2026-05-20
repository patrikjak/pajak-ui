<?php

declare(strict_types=1);

namespace Pajak\Ui\Common\Enums\Segmented;

enum SegmentedVariant: string
{
    case Default = 'default';
    case Pill = 'pill';
    case Brand = 'brand';
    case Bordered = 'bordered';
}
