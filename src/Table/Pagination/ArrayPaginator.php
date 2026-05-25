<?php

declare(strict_types=1);

namespace Pajak\Ui\Table\Pagination;

use Illuminate\Support\Collection;
use Pajak\Ui\Table\Contracts\TablePaginator;

final class ArrayPaginator implements TablePaginator
{
    private int $totalItems;

    private int $lastPageNumber;

    /**
     * @var array<int, mixed>
     */
    private array $pageItems;

    /**
     * @param Collection<int, mixed> $items
     */
    public static function fromCollection(Collection $items, int $perPage = 15, int $page = 1): self
    {
        return new self($items, $perPage, $page);
    }

    /**
     * @param array<int, mixed> $items
     */
    public static function fromArray(array $items, int $perPage = 15, int $page = 1): self
    {
        return new self($items, $perPage, $page);
    }

    /**
     * @param Collection<int, mixed>|array<int, mixed> $items
     */
    public function __construct(
        Collection|array $items,
        private readonly int $perPageCount = 15,
        private readonly int $currentPageNumber = 1,
    ) {
        $collection = $items instanceof Collection ? $items : new Collection($items);
        $this->totalItems = $collection->count();
        $this->lastPageNumber = (int) ceil($this->totalItems / max(1, $this->perPageCount));
        $this->pageItems = $collection
            ->slice(($this->currentPageNumber - 1) * $this->perPageCount, $this->perPageCount)
            ->values()
            ->all();
    }

    /**
     * @return array<int, mixed>
     */
    public function items(): array
    {
        return $this->pageItems;
    }

    public function total(): int
    {
        return $this->totalItems;
    }

    public function perPage(): int
    {
        return $this->perPageCount;
    }

    public function currentPage(): int
    {
        return $this->currentPageNumber;
    }

    public function lastPage(): int
    {
        return max(1, $this->lastPageNumber);
    }

    public function hasPages(): bool
    {
        return $this->totalItems > $this->perPageCount;
    }

    public function onFirstPage(): bool
    {
        return $this->currentPageNumber === 1;
    }

    public function onLastPage(): bool
    {
        return $this->currentPageNumber >= $this->lastPage();
    }
}
