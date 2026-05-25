<?php

declare(strict_types=1);

namespace Pajak\Ui\Common\Enums\EmptyState;

enum EmptyStateVariant: string
{
    case Default = 'default';
    case Dashed = 'dashed';
    case Compact = 'compact';
}
