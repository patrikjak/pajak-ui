<?php

declare(strict_types=1);

namespace Pajak\Ui\Tests\Integration\Common;

use Pajak\Ui\Common\Enums\Navbar\NavbarVariant;
use Pajak\Ui\Tests\Integration\TestCase;

final class NavbarSnapshotTest extends TestCase
{
    public function testDefaultNavbar(): void
    {
        $html = (string) $this->blade('<x-pajak::navbar />');

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testWithBrandAndLinks(): void
    {
        $html = (string) $this->blade(
            <<<'BLADE'
            <x-pajak::navbar>
                <x-slot:brand>My App</x-slot:brand>
                <x-slot:links>
                    <x-pajak::nav-link href="/" label="Dashboard" :active="true" />
                    <x-pajak::nav-link href="/returns" label="Returns" :count="3" />
                    <x-pajak::nav-link href="/docs" label="Documents" :dot="true" />
                </x-slot:links>
                <x-slot:actions><button>Help</button></x-slot:actions>
            </x-pajak::navbar>
            BLADE,
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testUnderlineVariant(): void
    {
        $html = (string) $this->blade(
            '<x-pajak::navbar :variant="$variant" />',
            ['variant' => NavbarVariant::Underline],
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testDarkVariant(): void
    {
        $html = (string) $this->blade(
            '<x-pajak::navbar :variant="$variant" />',
            ['variant' => NavbarVariant::Dark],
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testSplitVariant(): void
    {
        $html = (string) $this->blade(
            <<<'BLADE'
            <x-pajak::navbar :variant="$variant">
                <x-slot:brand>My App</x-slot:brand>
                <x-slot:title>Dashboard</x-slot:title>
                <x-slot:actions><button>Save</button></x-slot:actions>
            </x-pajak::navbar>
            BLADE,
            ['variant' => NavbarVariant::Split],
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testStackedVariant(): void
    {
        $html = (string) $this->blade(
            <<<'BLADE'
            <x-pajak::navbar :variant="$variant">
                <x-slot:brand>My App</x-slot:brand>
                <x-slot:links>
                    <x-pajak::nav-link href="/" label="Dashboard" />
                    <x-pajak::nav-link href="/returns" label="Returns" :active="true" />
                </x-slot:links>
                <x-slot:subLinks>
                    <x-pajak::nav-link href="/all" label="All returns" :active="true" />
                    <x-pajak::nav-link href="/progress" label="In progress" />
                </x-slot:subLinks>
            </x-pajak::navbar>
            BLADE,
            ['variant' => NavbarVariant::Stacked],
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testMobileVariant(): void
    {
        $html = (string) $this->blade(
            '<x-pajak::navbar :variant="$variant" />',
            ['variant' => NavbarVariant::Mobile],
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testNavLinkActive(): void
    {
        $html = (string) $this->blade(
            '<x-pajak::nav-link href="/dashboard" label="Dashboard" :active="true" />',
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testNavLinkWithCount(): void
    {
        $html = (string) $this->blade(
            '<x-pajak::nav-link href="/docs" label="Documents" :count="5" />',
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testNavLinkWithDot(): void
    {
        $html = (string) $this->blade(
            '<x-pajak::nav-link href="/inbox" label="Inbox" :dot="true" />',
        );

        $this->assertMatchesHtmlSnapshot($html);
    }
}
