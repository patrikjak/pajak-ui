<?php

declare(strict_types=1);

namespace Pajak\Ui\Tests\Integration\Common;

use Pajak\Ui\Common\Enums\Tabs\TabsVariant;
use Pajak\Ui\Tests\Integration\TestCase;

final class TabsSnapshotTest extends TestCase
{
    public function testDefaultUnderlineTabs(): void
    {
        $html = (string) $this->blade(
            <<<'BLADE'
            <x-pajak::tabs>
                <x-pajak::tab label="Overview" :active="true" />
                <x-pajak::tab label="Documents" :count="12" />
                <x-pajak::tab label="Activity" />
                <x-pajak::tab label="Archive" :disabled="true" />
            </x-pajak::tabs>
            BLADE,
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testPillVariant(): void
    {
        $html = (string) $this->blade(
            <<<'BLADE'
            <x-pajak::tabs :variant="$variant">
                <x-pajak::tab label="All" :active="true" />
                <x-pajak::tab label="In progress" />
                <x-pajak::tab label="Submitted" />
            </x-pajak::tabs>
            BLADE,
            ['variant' => TabsVariant::Pill],
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testSegmentedVariant(): void
    {
        $html = (string) $this->blade(
            <<<'BLADE'
            <x-pajak::tabs :variant="$variant">
                <x-pajak::tab label="Cards" :active="true" />
                <x-pajak::tab label="List" />
                <x-pajak::tab label="Table" />
            </x-pajak::tabs>
            BLADE,
            ['variant' => TabsVariant::Segmented],
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testVerticalVariant(): void
    {
        $html = (string) $this->blade(
            <<<'BLADE'
            <x-pajak::tabs :variant="$variant">
                <x-pajak::tab label="Profile" />
                <x-pajak::tab label="Settings" :active="true" :count="2" />
                <x-pajak::tab label="Security" />
            </x-pajak::tabs>
            BLADE,
            ['variant' => TabsVariant::Vertical],
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testTabWithCount(): void
    {
        $html = (string) $this->blade(
            '<x-pajak::tab label="Documents" :count="12" />',
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testDisabledTab(): void
    {
        $html = (string) $this->blade(
            '<x-pajak::tab label="Archive" :disabled="true" />',
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testActiveTab(): void
    {
        $html = (string) $this->blade(
            '<x-pajak::tab label="Overview" :active="true" />',
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testTabWithIconSlot(): void
    {
        $html = (string) $this->blade(
            <<<'BLADE'
            <x-pajak::tab label="Settings">
                <x-slot:icon>
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="3"/></svg>
                </x-slot:icon>
            </x-pajak::tab>
            BLADE,
        );

        $this->assertMatchesHtmlSnapshot($html);
    }
}
