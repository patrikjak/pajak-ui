<?php

declare(strict_types=1);

namespace Pajak\Ui\Tests\Integration\Table;

use Pajak\Ui\Table\Actions\LinkAction;
use Pajak\Ui\Table\Table;
use Pajak\Ui\Tests\Integration\TestCase;

final class TableBulkBarSnapshotTest extends TestCase
{
    public function testBulkBarWithSingleAction(): void
    {
        $table = Table::make('users')
            ->bulkActions([
                LinkAction::make('delete')->label('Delete')->danger(),
            ]);

        $html = (string) $this->blade(
            '<x-pajak-table::table-bulk-bar :table="$table" />',
            ['table' => $table],
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testBulkBarWithMultipleActions(): void
    {
        $table = Table::make('users')
            ->bulkActions([
                LinkAction::make('export')->label('Export'),
                LinkAction::make('delete')->label('Delete')->danger(),
            ]);

        $html = (string) $this->blade(
            '<x-pajak-table::table-bulk-bar :table="$table" />',
            ['table' => $table],
        );

        $this->assertMatchesHtmlSnapshot($html);
    }
}
