<?php

declare(strict_types=1);

namespace Pajak\Ui\Tests\Unit\Table;

use Pajak\Ui\Table\Table;
use Pajak\Ui\Table\View\Components\TableRow;
use Pajak\Ui\Tests\Unit\TestCase;

final class TableRowTest extends TestCase
{
    public function testRowIdFromArrayWithId(): void
    {
        $table = Table::make('users');
        $row = new TableRow(['id' => 42, 'name' => 'Alice'], $table, 0);

        $this->assertSame('42', $row->rowId());
    }

    public function testRowIdFromObjectWithId(): void
    {
        $table = Table::make('users');
        $obj = new \stdClass();
        $obj->id = 99;
        $row = new TableRow($obj, $table, 0);

        $this->assertSame('99', $row->rowId());
    }

    public function testRowIdFallsBackToIndexForArrayWithoutId(): void
    {
        $table = Table::make('users');
        $row = new TableRow(['name' => 'Alice'], $table, 5);

        $this->assertSame('5', $row->rowId());
    }

    public function testRowIdFallsBackToIndexForObjectWithoutId(): void
    {
        $table = Table::make('users');
        $obj = new \stdClass();
        $obj->name = 'Alice';
        $row = new TableRow($obj, $table, 3);

        $this->assertSame('3', $row->rowId());
    }

    public function testRowIdFallsBackToIndexForScalarRow(): void
    {
        $table = Table::make('users');
        $row = new TableRow('plain-value', $table, 7);

        $this->assertSame('7', $row->rowId());
    }
}
