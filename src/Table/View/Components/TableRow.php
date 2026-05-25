<?php

declare(strict_types=1);

namespace Pajak\Ui\Table\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Pajak\Ui\Table\Table;

final class TableRow extends Component
{
    public function __construct(
        public readonly mixed $row,
        public readonly Table $table,
        public readonly int $index,
    ) {
    }

    public function rowId(): string
    {
        if (is_array($this->row) && isset($this->row['id'])) {
            return (string) $this->row['id'];
        }

        if (is_object($this->row) && isset($this->row->id)) {
            return (string) $this->row->id;
        }

        return (string) $this->index;
    }

    public function render(): View
    {
        return view('pajak::table.table-row');
    }
}
