<?php

declare(strict_types=1);

namespace Pajak\Ui\Table\Columns;

use Pajak\Ui\Table\Columns\Concerns\HasColorMap;

class BadgeColumn extends TextColumn
{
    use HasColorMap;

    public function cellView(): string
    {
        return 'pajak::table.cells.badge';
    }
}
