<?php

declare(strict_types=1);

namespace Pajak\Ui\Table\Columns;

use Pajak\Ui\Table\Columns\Concerns\HasColorMap;

class StatusColumn extends TextColumn
{
    use HasColorMap;

    /**
     * @return array<string, string>
     */
    public function colorMap(): array
    {
        return $this->colorMap;
    }

    public function cellView(): string
    {
        return 'pajak::table.cells.status';
    }
}
