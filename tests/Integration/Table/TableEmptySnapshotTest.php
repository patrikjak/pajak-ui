<?php

declare(strict_types=1);

namespace Pajak\Ui\Tests\Integration\Table;

use Pajak\Ui\Tests\Integration\TestCase;

final class TableEmptySnapshotTest extends TestCase
{
    public function testEmptyStateDefault(): void
    {
        $html = (string) $this->blade('<x-pajak-table::table-empty :columnCount="3" />');

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testEmptyStateWithActiveFilters(): void
    {
        $html = (string) $this->blade('<x-pajak-table::table-empty :columnCount="5" :hasActiveFilters="true" />');

        $this->assertMatchesHtmlSnapshot($html);
    }
}
