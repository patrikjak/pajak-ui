<?php

declare(strict_types=1);

namespace Pajak\Ui\Tests\Unit\Table;

use Illuminate\Support\Collection;
use Pajak\Ui\Table\Pagination\ArrayPaginator;
use Pajak\Ui\Tests\Unit\TestCase;

final class PaginatorTest extends TestCase
{
    public function testArrayPaginatorFirstPage(): void
    {
        $items = range(1, 30);
        $paginator = new ArrayPaginator($items, 15, 1);

        $this->assertCount(15, $paginator->items());
        $this->assertSame(30, $paginator->total());
        $this->assertSame(1, $paginator->currentPage());
        $this->assertSame(2, $paginator->lastPage());
        $this->assertTrue($paginator->onFirstPage());
        $this->assertFalse($paginator->onLastPage());
        $this->assertTrue($paginator->hasPages());
    }

    public function testArrayPaginatorLastPage(): void
    {
        $items = range(1, 30);
        $paginator = new ArrayPaginator($items, 15, 2);

        $this->assertCount(15, $paginator->items());
        $this->assertSame(2, $paginator->currentPage());
        $this->assertFalse($paginator->onFirstPage());
        $this->assertTrue($paginator->onLastPage());
    }

    public function testArrayPaginatorWithFewerItemsThanPerPage(): void
    {
        $paginator = new ArrayPaginator([1, 2, 3], 15, 1);

        $this->assertCount(3, $paginator->items());
        $this->assertSame(1, $paginator->lastPage());
        $this->assertFalse($paginator->hasPages());
    }

    public function testArrayPaginatorWithCollection(): void
    {
        $items = new Collection(range(1, 10));
        $paginator = new ArrayPaginator($items, 5, 2);

        $this->assertCount(5, $paginator->items());
        $this->assertSame(6, $paginator->items()[0]);
    }

    public function testArrayPaginatorEmptyItems(): void
    {
        $paginator = new ArrayPaginator([], 15, 1);

        $this->assertCount(0, $paginator->items());
        $this->assertSame(0, $paginator->total());
        $this->assertSame(1, $paginator->lastPage());
    }
}
