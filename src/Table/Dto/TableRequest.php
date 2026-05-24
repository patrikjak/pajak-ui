<?php

declare(strict_types=1);

namespace Pajak\Ui\Table\Dto;

final readonly class TableRequest
{
    /**
     * @param array<int, TableFilterValue> $filters
     * @param array<int, string> $visibleColumns
     */
    public function __construct(
        public ?string $search,
        public ?TableSortState $sort,
        public array $filters,
        public int $page,
        public int $perPage,
        public array $visibleColumns,
    ) {
    }
}
