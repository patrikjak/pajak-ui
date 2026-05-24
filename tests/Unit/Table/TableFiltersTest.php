<?php

declare(strict_types=1);

namespace Pajak\Ui\Tests\Unit\Table;

use Illuminate\Support\Collection;
use Pajak\Ui\Table\Dto\TableFilterValue;
use Pajak\Ui\Table\Enums\TextOperator;
use Pajak\Ui\Table\Services\TableFilters;
use Pajak\Ui\Tests\Unit\TestCase;

final class TableFiltersTest extends TestCase
{
    private TableFilters $filters;

    protected function setUp(): void
    {
        parent::setUp();
        $this->filters = new TableFilters();
    }

    /** @return Collection<int, array<string, mixed>> */
    private function items(): Collection
    {
        return new Collection([
            ['name' => 'Alice Smith', 'status' => 'active', 'amount' => 100],
            ['name' => 'Bob Jones', 'status' => 'inactive', 'amount' => 50],
            ['name' => 'Charlie Brown', 'status' => 'active', 'amount' => 200],
        ]);
    }

    public function testContainsFilter(): void
    {
        $result = $this->filters->apply($this->items(), [
            new TableFilterValue('name', 'alice', TextOperator::Contains),
        ]);

        $this->assertCount(1, $result);
        $this->assertSame('Alice Smith', $result->first()['name']);
    }

    public function testNotContainsFilter(): void
    {
        $result = $this->filters->apply($this->items(), [
            new TableFilterValue('name', 'jones', TextOperator::NotContains),
        ]);

        $this->assertCount(2, $result);
    }

    public function testEqualsFilter(): void
    {
        $result = $this->filters->apply($this->items(), [
            new TableFilterValue('status', 'active', TextOperator::Equals),
        ]);

        $this->assertCount(2, $result);
    }

    public function testNotEqualsFilter(): void
    {
        $result = $this->filters->apply($this->items(), [
            new TableFilterValue('status', 'active', TextOperator::NotEquals),
        ]);

        $this->assertCount(1, $result);
        $this->assertSame('Bob Jones', $result->first()['name']);
    }

    public function testArrayValueFilter(): void
    {
        $result = $this->filters->apply($this->items(), [
            new TableFilterValue('status', ['active']),
        ]);

        $this->assertCount(2, $result);
    }

    public function testRangeFilter(): void
    {
        $result = $this->filters->apply($this->items(), [
            new TableFilterValue('amount', ['from' => 75, 'to' => 150]),
        ]);

        $this->assertCount(1, $result);
        $this->assertSame(100, $result->first()['amount']);
    }

    public function testEmptyValueSkipsFilter(): void
    {
        $result = $this->filters->apply($this->items(), [
            new TableFilterValue('name', ''),
        ]);

        $this->assertCount(3, $result);
    }

    public function testMultipleFilters(): void
    {
        $result = $this->filters->apply($this->items(), [
            new TableFilterValue('status', 'active', TextOperator::Equals),
            new TableFilterValue('amount', ['from' => 150]),
        ]);

        $this->assertCount(1, $result);
        $this->assertSame('Charlie Brown', $result->first()['name']);
    }
}
