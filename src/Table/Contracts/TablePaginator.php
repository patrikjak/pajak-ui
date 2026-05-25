<?php

declare(strict_types=1);

namespace Pajak\Ui\Table\Contracts;

interface TablePaginator
{
    /**
     * @return array<int, mixed>
     */
    public function items(): array;

    public function total(): int;

    public function perPage(): int;

    public function currentPage(): int;

    public function lastPage(): int;

    public function hasPages(): bool;

    public function onFirstPage(): bool;

    public function onLastPage(): bool;
}
