<?php

declare(strict_types=1);

namespace Pajak\Ui\Common\Enums\Sidebar;

enum SidebarVariant: string
{
    case Standard = 'standard';
    case Rail = 'rail';
    case Dark = 'dark';
    case Workspace = 'workspace';
    case Wide = 'wide';
    case Filters = 'filters';
}
