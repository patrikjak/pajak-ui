<?php

declare(strict_types=1);

namespace Pajak\Ui\Tests\Integration\Common;

use Pajak\Ui\Tests\Integration\TestCase;

final class SidebarItemSnapshotTest extends TestCase
{
    public function testSidebarItemActive(): void
    {
        $html = (string) $this->blade(
            '<x-pajak::sidebar-item href="/dashboard" label="Dashboard" :active="true" />',
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testSidebarItemWithCount(): void
    {
        $html = (string) $this->blade(
            '<x-pajak::sidebar-item href="/returns" label="Returns" :count="12" />',
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testSidebarItemWarn(): void
    {
        $html = (string) $this->blade(
            '<x-pajak::sidebar-item href="/docs" label="Documents" :count="3" :warn="true" />',
        );

        $this->assertMatchesHtmlSnapshot($html);
    }
}
