<?php

declare(strict_types=1);

namespace Pajak\Ui\Tests\Integration\Table;

use Pajak\Ui\Table\Columns\TextColumn;
use Pajak\Ui\Table\Dto\TableSortState;
use Pajak\Ui\Table\Enums\SortDirection;
use Pajak\Ui\Tests\Integration\TestCase;

final class TableHeaderSnapshotTest extends TestCase
{
    public function testNonSortableHeader(): void
    {
        $column = TextColumn::make('name')->label('Name');

        $html = (string) $this->blade(
            '<x-pajak-table::table-header :column="$column" :currentSort="null" />',
            ['column' => $column],
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testSortableHeaderInactive(): void
    {
        $column = TextColumn::make('name')->label('Name')->sortable();

        $html = (string) $this->blade(
            '<x-pajak-table::table-header :column="$column" :currentSort="null" />',
            ['column' => $column],
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testSortableHeaderActiveSortAsc(): void
    {
        $column = TextColumn::make('name')->label('Name')->sortable();
        $sort = new TableSortState('name', SortDirection::Asc);

        $html = (string) $this->blade(
            '<x-pajak-table::table-header :column="$column" :currentSort="$sort" />',
            ['column' => $column, 'sort' => $sort],
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testSortableHeaderActiveSortDesc(): void
    {
        $column = TextColumn::make('name')->label('Name')->sortable();
        $sort = new TableSortState('name', SortDirection::Desc);

        $html = (string) $this->blade(
            '<x-pajak-table::table-header :column="$column" :currentSort="$sort" />',
            ['column' => $column, 'sort' => $sort],
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testSortableHeaderSortOnDifferentColumn(): void
    {
        $column = TextColumn::make('name')->label('Name')->sortable();
        $sort = new TableSortState('email', SortDirection::Asc);

        $html = (string) $this->blade(
            '<x-pajak-table::table-header :column="$column" :currentSort="$sort" />',
            ['column' => $column, 'sort' => $sort],
        );

        $this->assertMatchesHtmlSnapshot($html);
    }
}
