<?php

declare(strict_types=1);

namespace Pajak\Ui\Table\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Pajak\Ui\Table\Contracts\TablePaginator;
use Pajak\Ui\Table\Table;

final class TableComponent extends Component
{
    public function __construct(
        public readonly Table $table,
        public readonly TablePaginator $paginator,
    ) {
    }

    public function render(): View
    {
        return view('pajak::table.table');
    }
}
