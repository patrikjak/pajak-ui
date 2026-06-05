<?php

declare(strict_types=1);

namespace Pajak\Ui\Tests\Integration\Common;

use Pajak\Ui\Tests\Integration\TestCase;

final class SidebarSectionSnapshotTest extends TestCase
{
    public function testSidebarSection(): void
    {
        $html = (string) $this->blade('<x-pajak::sidebar-section label="Account" />');

        $this->assertMatchesHtmlSnapshot($html);
    }
}
