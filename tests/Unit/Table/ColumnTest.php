<?php

declare(strict_types=1);

namespace Pajak\Ui\Tests\Unit\Table;

use Pajak\Ui\Table\Columns\AmountColumn;
use Pajak\Ui\Table\Columns\AvatarColumn;
use Pajak\Ui\Table\Columns\BadgeColumn;
use Pajak\Ui\Table\Columns\DateColumn;
use Pajak\Ui\Table\Columns\StatusColumn;
use Pajak\Ui\Table\Columns\TextColumn;
use Pajak\Ui\Table\Columns\TwoLineColumn;
use Pajak\Ui\Tests\Unit\TestCase;

final class ColumnTest extends TestCase
{
    public function testTextColumnDefaults(): void
    {
        $column = TextColumn::make('name');

        $this->assertSame('name', $column->key());
        $this->assertSame('name', $column->getLabel());
        $this->assertFalse($column->isSortable());
        $this->assertFalse($column->isHidden());
    }

    public function testTextColumnSortable(): void
    {
        $column = TextColumn::make('name')->sortable();

        $this->assertTrue($column->isSortable());
    }

    public function testTextColumnHidden(): void
    {
        $column = TextColumn::make('name')->hidden();

        $this->assertTrue($column->isHidden());
    }

    public function testTextColumnLabel(): void
    {
        $column = TextColumn::make('name')->label('Full Name');

        $this->assertSame('Full Name', $column->getLabel());
    }

    public function testStatusColumnColorMap(): void
    {
        $column = StatusColumn::make('status')->colors(['active' => 'green', 'inactive' => 'gray']);

        $this->assertSame('green', $column->colorFor('active'));
        $this->assertSame('gray', $column->colorFor('inactive'));
        $this->assertNull($column->colorFor('unknown'));
    }

    public function testTwoLineColumnSecondaryKey(): void
    {
        $column = TwoLineColumn::make('client')->secondary('nip');

        $this->assertSame('nip', $column->secondaryKey());
    }

    public function testBadgeColumnColorFor(): void
    {
        $column = BadgeColumn::make('type')->colors(['invoice' => 'blue']);

        $this->assertSame('blue', $column->colorFor('invoice'));
    }

    public function testAvatarColumnKeys(): void
    {
        $column = AvatarColumn::make('name')->subtitle('email')->image('avatar_url');

        $this->assertSame('email', $column->subtitleKey());
        $this->assertSame('avatar_url', $column->imageKey());
    }

    public function testDateColumnDefaultFormat(): void
    {
        $column = DateColumn::make('created_at');

        $this->assertSame('d.m.Y', $column->dateFormat());
    }

    public function testDateColumnCustomFormat(): void
    {
        $column = DateColumn::make('created_at')->format('Y-m-d');

        $this->assertSame('Y-m-d', $column->dateFormat());
    }

    public function testAmountColumnCellView(): void
    {
        $column = AmountColumn::make('amount');

        $this->assertSame('pajak::table.cells.amount', $column->cellView());
    }

    public function testAllColumnCellViews(): void
    {
        $this->assertSame('pajak::table.cells.text', TextColumn::make('x')->cellView());
        $this->assertSame('pajak::table.cells.status', StatusColumn::make('x')->cellView());
        $this->assertSame('pajak::table.cells.two-line', TwoLineColumn::make('x')->cellView());
        $this->assertSame('pajak::table.cells.badge', BadgeColumn::make('x')->cellView());
        $this->assertSame('pajak::table.cells.avatar', AvatarColumn::make('x')->cellView());
        $this->assertSame('pajak::table.cells.date', DateColumn::make('x')->cellView());
        $this->assertSame('pajak::table.cells.amount', AmountColumn::make('x')->cellView());
    }
}
