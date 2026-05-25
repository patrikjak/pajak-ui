<?php

declare(strict_types=1);

namespace Pajak\Ui\Tests\Unit\Table;

use Pajak\Ui\Table\Actions\LinkAction;
use Pajak\Ui\Table\Columns\TextColumn;
use Pajak\Ui\Table\Filters\TextFilter;
use Pajak\Ui\Table\Table;
use Pajak\Ui\Tests\Unit\TestCase;

final class TableBuilderTest extends TestCase
{
    public function testMakeCreatesInstanceWithName(): void
    {
        $table = Table::make('invoices');

        $this->assertSame('invoices', $table->name());
    }

    public function testDefaultsAreEmpty(): void
    {
        $table = Table::make('test');

        $this->assertSame([], $table->getColumns());
        $this->assertSame([], $table->getFilters());
        $this->assertSame([], $table->getActions());
        $this->assertSame([], $table->getBulkActions());
        $this->assertNull($table->url());
        $this->assertFalse($table->isSearchable());
    }

    public function testChainingColumnsFiltersActions(): void
    {
        $column = TextColumn::make('name')->label('Name');
        $filter = TextFilter::make('name')->label('Name');
        $action = LinkAction::make('view')->label('View');

        $table = Table::make('test')
            ->columns([$column])
            ->filters([$filter])
            ->actions([$action])
            ->dataUrl('https://example.com/table')
            ->searchable();

        $this->assertCount(1, $table->getColumns());
        $this->assertCount(1, $table->getFilters());
        $this->assertCount(1, $table->getActions());
        $this->assertSame('https://example.com/table', $table->url());
        $this->assertTrue($table->isSearchable());
    }

    public function testSelectableViaBulkActions(): void
    {
        $action = LinkAction::make('delete')->label('Delete');
        $table = Table::make('test')->bulkActions([$action]);

        $this->assertTrue($table->isSelectable());
    }

    public function testSelectableViaExplicitCall(): void
    {
        $table = Table::make('test')->selectable();

        $this->assertTrue($table->isSelectable());
    }

    public function testHasFiltersReturnsFalseByDefault(): void
    {
        $table = Table::make('test');

        $this->assertFalse($table->hasFilters());
    }

    public function testHasFiltersTrueWhenFiltersSet(): void
    {
        $table = Table::make('test')->filters([TextFilter::make('x')]);

        $this->assertTrue($table->hasFilters());
    }

    public function testColumnVisibilityFalseByDefault(): void
    {
        $table = Table::make('test');

        $this->assertFalse($table->hasColumnVisibility());
    }

    public function testColumnVisibilityTrueWhenEnabled(): void
    {
        $table = Table::make('test')->columnVisibility();

        $this->assertTrue($table->hasColumnVisibility());
    }
}
