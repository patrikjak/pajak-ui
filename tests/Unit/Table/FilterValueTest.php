<?php

declare(strict_types=1);

namespace Pajak\Ui\Tests\Unit\Table;

use Pajak\Ui\Table\Dto\TableFilterValue;
use Pajak\Ui\Table\Enums\TextOperator;
use Pajak\Ui\Table\Filters\DateFilter;
use Pajak\Ui\Table\Filters\NumberFilter;
use Pajak\Ui\Table\Filters\SelectFilter;
use Pajak\Ui\Table\Filters\TextFilter;
use Pajak\Ui\Tests\Unit\TestCase;

final class FilterValueTest extends TestCase
{
    public function testTableFilterValueStoresFields(): void
    {
        $value = new TableFilterValue('name', 'John', TextOperator::Contains);

        $this->assertSame('name', $value->key);
        $this->assertSame('John', $value->value);
        $this->assertSame(TextOperator::Contains, $value->operator);
    }

    public function testTableFilterValueOperatorCanBeNull(): void
    {
        $value = new TableFilterValue('status', ['active', 'inactive']);

        $this->assertNull($value->operator);
    }

    public function testTextOperatorLabelsAreStrings(): void
    {
        foreach (TextOperator::cases() as $operator) {
            $this->assertIsString($operator->label());
            $this->assertNotEmpty($operator->label());
        }
    }

    public function testTextFilterEditorPartial(): void
    {
        $filter = TextFilter::make('name');

        $this->assertSame('pajak::table.partials.filter-editor-text', $filter->editorPartial());
    }

    public function testSelectFilterEditorPartial(): void
    {
        $filter = SelectFilter::make('status');

        $this->assertSame('pajak::table.partials.filter-editor-select', $filter->editorPartial());
    }

    public function testDateFilterEditorPartial(): void
    {
        $filter = DateFilter::make('date');

        $this->assertSame('pajak::table.partials.filter-editor-date', $filter->editorPartial());
    }

    public function testNumberFilterEditorPartial(): void
    {
        $filter = NumberFilter::make('amount');

        $this->assertSame('pajak::table.partials.filter-editor-number', $filter->editorPartial());
    }

    public function testSelectFilterStoresOptions(): void
    {
        $options = ['active' => 'Active', 'inactive' => 'Inactive'];
        $filter = SelectFilter::make('status')->options($options);

        $this->assertSame($options, $filter->getOptions());
    }
}
