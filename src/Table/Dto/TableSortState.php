<?php

declare(strict_types=1);

namespace Pajak\Ui\Table\Dto;

use Pajak\Ui\Table\Enums\SortDirection;

final readonly class TableSortState
{
    public function __construct(
        public string $column,
        public SortDirection $direction,
    ) {
    }
}
