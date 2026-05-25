<?php

declare(strict_types=1);

namespace Pajak\Ui\Table\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Pajak\Ui\Table\Contracts\TablePaginator;
use Pajak\Ui\Table\Table;

final class TablePagination extends Component
{
    public function __construct(public readonly TablePaginator $paginator, public readonly Table $table)
    {
    }

    public function render(): View
    {
        return view('pajak::table.table-pagination');
    }
}
