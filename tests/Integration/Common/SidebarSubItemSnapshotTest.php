<?php

declare(strict_types=1);

namespace Pajak\Ui\Tests\Integration\Common;

use Pajak\Ui\Tests\Integration\TestCase;

final class SidebarSubItemSnapshotTest extends TestCase
{
    public function testSidebarSubItem(): void
    {
        $html = (string) $this->blade(
            '<x-pajak::sidebar-sub-item href="/progress" label="In progress" :active="true" :count="8" />',
        );

        $this->assertMatchesHtmlSnapshot($html);
    }
}
