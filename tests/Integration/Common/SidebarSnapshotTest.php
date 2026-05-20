<?php

declare(strict_types=1);

namespace Pajak\Ui\Tests\Integration\Common;

use Pajak\Ui\Common\Enums\Sidebar\SidebarVariant;
use Pajak\Ui\Tests\Integration\TestCase;

final class SidebarSnapshotTest extends TestCase
{
    public function testDefaultSidebar(): void
    {
        $html = (string) $this->blade('<x-pajak::sidebar />');

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testWithBrandAndFooter(): void
    {
        $html = (string) $this->blade(
            <<<'BLADE'
            <x-pajak::sidebar>
                <x-slot:brand>My App</x-slot:brand>
                <x-slot:footer>User card here</x-slot:footer>
            </x-pajak::sidebar>
            BLADE,
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testWithSectionAndItems(): void
    {
        $html = (string) $this->blade(
            <<<'BLADE'
            <x-pajak::sidebar>
                <x-slot:brand>App</x-slot:brand>
                <x-pajak::sidebar-section label="Workspace" />
                <x-pajak::sidebar-item href="/" label="Dashboard" :active="true" />
                <x-pajak::sidebar-item href="/returns" label="Returns" :count="12" />
                <x-pajak::sidebar-item href="/docs" label="Documents" :dot="true" />
                <x-pajak::sidebar-item href="/reports" label="Reports" :count="3" :warn="true" />
            </x-pajak::sidebar>
            BLADE,
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testRailVariant(): void
    {
        $html = (string) $this->blade(
            '<x-pajak::sidebar :variant="$variant" />',
            ['variant' => SidebarVariant::Rail],
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testDarkVariant(): void
    {
        $html = (string) $this->blade(
            '<x-pajak::sidebar :variant="$variant" />',
            ['variant' => SidebarVariant::Dark],
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testWorkspaceVariant(): void
    {
        $html = (string) $this->blade(
            <<<'BLADE'
            <x-pajak::sidebar :variant="$variant">
                <x-slot:brand>App</x-slot:brand>
                <x-slot:header>Workspace switcher</x-slot:header>
                <x-pajak::sidebar-item href="/" label="Overview" :active="true" />
            </x-pajak::sidebar>
            BLADE,
            ['variant' => SidebarVariant::Workspace],
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testWideVariant(): void
    {
        $html = (string) $this->blade(
            '<x-pajak::sidebar :variant="$variant" />',
            ['variant' => SidebarVariant::Wide],
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testFiltersVariant(): void
    {
        $html = (string) $this->blade(
            <<<'BLADE'
            <x-pajak::sidebar :variant="$variant">
                <x-slot:brand>Filter returns</x-slot:brand>
                <p>Filter content here.</p>
            </x-pajak::sidebar>
            BLADE,
            ['variant' => SidebarVariant::Filters],
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testWithSubNav(): void
    {
        $html = (string) $this->blade(
            <<<'BLADE'
            <x-pajak::sidebar :variant="$variant">
                <x-pajak::sidebar-item href="/returns" label="Returns" :active="true" />
                <div class="pajak-sb__sub">
                    <x-pajak::sidebar-sub-item href="/all" label="All returns" :count="24" />
                    <x-pajak::sidebar-sub-item href="/progress" label="In progress" :active="true" :count="8" />
                    <x-pajak::sidebar-sub-item href="/submitted" label="Submitted" />
                </div>
            </x-pajak::sidebar>
            BLADE,
            ['variant' => SidebarVariant::Wide],
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testSidebarSection(): void
    {
        $html = (string) $this->blade('<x-pajak::sidebar-section label="Account" />');

        $this->assertMatchesHtmlSnapshot($html);
    }

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

    public function testSidebarSubItem(): void
    {
        $html = (string) $this->blade(
            '<x-pajak::sidebar-sub-item href="/progress" label="In progress" :active="true" :count="8" />',
        );

        $this->assertMatchesHtmlSnapshot($html);
    }
}
