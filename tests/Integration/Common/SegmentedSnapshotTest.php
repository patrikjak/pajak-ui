<?php

declare(strict_types=1);

namespace Pajak\Ui\Tests\Integration\Common;

use Pajak\Ui\Common\Enums\Segmented\SegmentedVariant;
use Pajak\Ui\Common\Enums\Size;
use Pajak\Ui\Tests\Integration\TestCase;

final class SegmentedSnapshotTest extends TestCase
{
    public function testDefaultSegmented(): void
    {
        $html = (string) $this->blade(
            <<<'BLADE'
            <x-pajak::segmented>
                <x-pajak::segmented-option label="Day" :active="true" />
                <x-pajak::segmented-option label="Week" />
                <x-pajak::segmented-option label="Month" />
            </x-pajak::segmented>
            BLADE,
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testWithActiveOption(): void
    {
        $html = (string) $this->blade(
            <<<'BLADE'
            <x-pajak::segmented>
                <x-pajak::segmented-option label="All" />
                <x-pajak::segmented-option label="Active" :active="true" />
                <x-pajak::segmented-option label="Archived" />
            </x-pajak::segmented>
            BLADE,
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testDisabledOption(): void
    {
        $html = (string) $this->blade(
            <<<'BLADE'
            <x-pajak::segmented>
                <x-pajak::segmented-option label="Personal" :active="true" />
                <x-pajak::segmented-option label="Joint" />
                <x-pajak::segmented-option label="Business" :disabled="true" />
            </x-pajak::segmented>
            BLADE,
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testPillVariant(): void
    {
        $html = (string) $this->blade(
            <<<'BLADE'
            <x-pajak::segmented :variant="$variant">
                <x-pajak::segmented-option label="PLN" :active="true" />
                <x-pajak::segmented-option label="EUR" />
            </x-pajak::segmented>
            BLADE,
            ['variant' => SegmentedVariant::Pill],
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testBrandVariant(): void
    {
        $html = (string) $this->blade(
            <<<'BLADE'
            <x-pajak::segmented :variant="$variant">
                <x-pajak::segmented-option label="Refund" :active="true" />
                <x-pajak::segmented-option label="Owed" />
                <x-pajak::segmented-option label="Even" />
            </x-pajak::segmented>
            BLADE,
            ['variant' => SegmentedVariant::Brand],
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testBorderedVariant(): void
    {
        $html = (string) $this->blade(
            <<<'BLADE'
            <x-pajak::segmented :variant="$variant">
                <x-pajak::segmented-option label="All" />
                <x-pajak::segmented-option label="Active" :active="true" />
                <x-pajak::segmented-option label="Archived" />
            </x-pajak::segmented>
            BLADE,
            ['variant' => SegmentedVariant::Bordered],
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testSmallSize(): void
    {
        $html = (string) $this->blade(
            <<<'BLADE'
            <x-pajak::segmented :size="$size">
                <x-pajak::segmented-option label="Day" :active="true" />
                <x-pajak::segmented-option label="Week" />
                <x-pajak::segmented-option label="Month" />
            </x-pajak::segmented>
            BLADE,
            ['size' => Size::Sm],
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testLargeSize(): void
    {
        $html = (string) $this->blade(
            <<<'BLADE'
            <x-pajak::segmented :size="$size">
                <x-pajak::segmented-option label="Day" :active="true" />
                <x-pajak::segmented-option label="Week" />
                <x-pajak::segmented-option label="Month" />
            </x-pajak::segmented>
            BLADE,
            ['size' => Size::Lg],
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testFullWidth(): void
    {
        $html = (string) $this->blade(
            <<<'BLADE'
            <x-pajak::segmented :full="true" :variant="$variant">
                <x-pajak::segmented-option label="Refund" :active="true" />
                <x-pajak::segmented-option label="Owed" />
                <x-pajak::segmented-option label="Even" />
            </x-pajak::segmented>
            BLADE,
            ['variant' => SegmentedVariant::Brand],
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testWithIconSlot(): void
    {
        $html = (string) $this->blade(
            <<<'BLADE'
            <x-pajak::segmented>
                <x-pajak::segmented-option label="Cards" :active="true">
                    <x-slot:icon><svg width="13" height="13" aria-hidden="true"></svg></x-slot:icon>
                </x-pajak::segmented-option>
                <x-pajak::segmented-option label="List">
                    <x-slot:icon><svg width="13" height="13" aria-hidden="true"></svg></x-slot:icon>
                </x-pajak::segmented-option>
            </x-pajak::segmented>
            BLADE,
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testIconOnly(): void
    {
        $html = (string) $this->blade(
            <<<'BLADE'
            <x-pajak::segmented>
                <x-pajak::segmented-option :active="true">
                    <x-slot:icon><svg width="14" height="14" aria-label="Left" aria-hidden="true"></svg></x-slot:icon>
                </x-pajak::segmented-option>
                <x-pajak::segmented-option>
                    <x-slot:icon><svg width="14" height="14" aria-label="Center" aria-hidden="true"></svg></x-slot:icon>
                </x-pajak::segmented-option>
                <x-pajak::segmented-option>
                    <x-slot:icon><svg width="14" height="14" aria-label="Right" aria-hidden="true"></svg></x-slot:icon>
                </x-pajak::segmented-option>
            </x-pajak::segmented>
            BLADE,
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testWithValue(): void
    {
        $html = (string) $this->blade(
            <<<'BLADE'
            <x-pajak::segmented>
                <x-pajak::segmented-option label="Light" value="light" :active="true" />
                <x-pajak::segmented-option label="Dark" value="dark" />
                <x-pajak::segmented-option label="Auto" value="auto" />
            </x-pajak::segmented>
            BLADE,
        );

        $this->assertMatchesHtmlSnapshot($html);
    }
}
