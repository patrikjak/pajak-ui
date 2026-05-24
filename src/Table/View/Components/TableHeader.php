<?php

declare(strict_types=1);

namespace Pajak\Ui\Table\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Pajak\Ui\Table\Contracts\TableColumn;
use Pajak\Ui\Table\Dto\TableSortState;

final class TableHeader extends Component
{
    public function __construct(
        public readonly TableColumn $column,
        public readonly ?TableSortState $currentSort,
    ) {
    }

    public function isActiveSort(): bool
    {
        return $this->currentSort !== null
            && $this->currentSort->column === $this->column->key();
    }

    public function render(): View
    {
        return view('pajak::table.table-header');
    }
}
