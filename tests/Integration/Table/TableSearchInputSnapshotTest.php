<?php

declare(strict_types=1);

namespace Pajak\Ui\Tests\Integration\Table;

use Pajak\Ui\Tests\Integration\TestCase;

final class TableSearchInputSnapshotTest extends TestCase
{
    public function testSearchInput(): void
    {
        $html = (string) $this->blade('<x-pajak-table::table-search-input />');

        $this->assertMatchesHtmlSnapshot($html);
    }
}
