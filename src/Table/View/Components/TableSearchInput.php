<?php

declare(strict_types=1);

namespace Pajak\Ui\Table\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

final class TableSearchInput extends Component
{
    public function render(): View
    {
        return view('pajak::table.table-search-input');
    }
}
