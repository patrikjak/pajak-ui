<?php

declare(strict_types=1);

namespace Pajak\Ui\Table\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

final class TableEmpty extends Component
{
    public function __construct(
        public readonly int $columnCount,
        public readonly bool $hasActiveFilters = false,
    ) {
    }

    public function render(): View
    {
        return view('pajak::table.table-empty');
    }
}
