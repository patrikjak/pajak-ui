<?php

declare(strict_types=1);

namespace Pajak\Ui\Tests\Unit\Table;

use Pajak\Ui\Table\Columns\TextColumn;
use Pajak\Ui\Table\Dto\TableSortState;
use Pajak\Ui\Table\Enums\SortDirection;
use Pajak\Ui\Table\View\Components\TableHeader;
use Pajak\Ui\Tests\Unit\TestCase;

final class TableHeaderTest extends TestCase
{
    public function testIsActiveSortReturnsFalseWhenNoSort(): void
    {
        $column = TextColumn::make('name');
        $header = new TableHeader($column, null);

        $this->assertFalse($header->isActiveSort());
    }

    public function testIsActiveSortReturnsTrueWhenCurrentSortMatchesColumn(): void
    {
        $column = TextColumn::make('name');
        $sort = new TableSortState('name', SortDirection::Asc);
        $header = new TableHeader($column, $sort);

        $this->assertTrue($header->isActiveSort());
    }

    public function testIsActiveSortReturnsFalseWhenCurrentSortIsDifferentColumn(): void
    {
        $column = TextColumn::make('name');
        $sort = new TableSortState('email', SortDirection::Asc);
        $header = new TableHeader($column, $sort);

        $this->assertFalse($header->isActiveSort());
    }

    public function testIsActiveSortReturnsTrueForDescSort(): void
    {
        $column = TextColumn::make('created_at');
        $sort = new TableSortState('created_at', SortDirection::Desc);
        $header = new TableHeader($column, $sort);

        $this->assertTrue($header->isActiveSort());
    }
}
