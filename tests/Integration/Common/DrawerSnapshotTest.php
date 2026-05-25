<?php

declare(strict_types=1);

namespace Pajak\Ui\Tests\Integration\Common;

use Pajak\Ui\Common\Enums\Drawer\DrawerSide;
use Pajak\Ui\Tests\Integration\TestCase;

final class DrawerSnapshotTest extends TestCase
{
    public function testDefaultDrawer(): void
    {
        $html = (string) $this->blade('<x-pajak::drawer id="test-drawer" />');

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testLeftSide(): void
    {
        $html = (string) $this->blade(
            '<x-pajak::drawer id="test-drawer" :side="$side" />',
            ['side' => DrawerSide::Left],
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testBottomSide(): void
    {
        $html = (string) $this->blade(
            '<x-pajak::drawer id="test-drawer" :side="$side" />',
            ['side' => DrawerSide::Bottom],
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testTopSide(): void
    {
        $html = (string) $this->blade(
            '<x-pajak::drawer id="test-drawer" :side="$side" />',
            ['side' => DrawerSide::Top],
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testWithTitleAndDescription(): void
    {
        $html = (string) $this->blade(
            <<<'BLADE'
            <x-pajak::drawer id="test-drawer" title="Filter deductions">
                <x-slot:description>12 results across 4 categories</x-slot:description>
            </x-pajak::drawer>
            BLADE,
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testWithBody(): void
    {
        $html = (string) $this->blade(
            <<<'BLADE'
            <x-pajak::drawer id="test-drawer" title="Notifications">
                <p>Notification content here.</p>
            </x-pajak::drawer>
            BLADE,
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testWithFooter(): void
    {
        $html = (string) $this->blade(
            <<<'BLADE'
            <x-pajak::drawer id="test-drawer" title="Filter deductions">
                <x-slot:footer>
                    <button>Reset</button>
                    <button>Apply</button>
                </x-slot:footer>
            </x-pajak::drawer>
            BLADE,
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testNotDismissible(): void
    {
        $html = (string) $this->blade(
            '<x-pajak::drawer id="test-drawer" :dismissible="false" title="Loading…" />',
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testOpenProp(): void
    {
        $html = (string) $this->blade(
            '<x-pajak::drawer id="test-drawer" :open="true" title="Filter deductions" />',
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testFullDrawer(): void
    {
        $html = (string) $this->blade(
            <<<'BLADE'
            <x-pajak::drawer id="test-drawer" title="Filter deductions" :side="$side">
                <x-slot:description>12 results across 4 categories</x-slot:description>
                <p>Filter options here.</p>
                <x-slot:footer>
                    <button>Reset</button>
                    <button>Apply (3)</button>
                </x-slot:footer>
            </x-pajak::drawer>
            BLADE,
            ['side' => DrawerSide::Right],
        );

        $this->assertMatchesHtmlSnapshot($html);
    }
}
