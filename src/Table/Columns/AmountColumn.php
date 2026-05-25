<?php

declare(strict_types=1);

namespace Pajak\Ui\Table\Columns;

class AmountColumn extends TextColumn
{
    public function cellView(): string
    {
        return 'pajak::table.cells.amount';
    }

    public function thModifier(): string
    {
        return 'amount';
    }
}
