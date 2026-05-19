<?php

declare(strict_types=1);

namespace Pajak\Ui\Common\Enums\Tabs;

enum TabsVariant: string
{
    case Underline = 'underline';
    case Pill = 'pill';
    case Segmented = 'segmented';
    case Vertical = 'vertical';
}
