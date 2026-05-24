<?php

declare(strict_types=1);

namespace Pajak\Ui\Table\Pagination;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Pajak\Ui\Table\Contracts\TablePaginator;

final readonly class EloquentPaginator implements TablePaginator
{
    /**
     * @param LengthAwarePaginator<int, mixed> $paginator
     */
    public function __construct(private LengthAwarePaginator $paginator)
    {
    }

    /**
     * @return array<int, mixed>
     */
    public function items(): array
    {
        return $this->paginator->items();
    }

    public function total(): int
    {
        return $this->paginator->total();
    }

    public function perPage(): int
    {
        return $this->paginator->perPage();
    }

    public function currentPage(): int
    {
        return $this->paginator->currentPage();
    }

    public function lastPage(): int
    {
        return $this->paginator->lastPage();
    }

    public function hasPages(): bool
    {
        return $this->paginator->hasPages();
    }

    public function onFirstPage(): bool
    {
        return $this->paginator->currentPage() <= 1;
    }

    public function onLastPage(): bool
    {
        return $this->paginator->currentPage() >= $this->paginator->lastPage();
    }
}
