<?php

declare(strict_types=1);

namespace Pajak\Ui\Tests\Integration\Common;

use Pajak\Ui\Common\Enums\ProgressColor;
use Pajak\Ui\Common\Enums\ProgressSize;
use Pajak\Ui\Tests\Integration\TestCase;

final class ProgressSnapshotTest extends TestCase
{
    public function testDefaultProgress(): void
    {
        $html = (string) $this->blade(
            '<x-pajak::progress :value="$value" />',
            ['value' => 65],
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testSizes(): void
    {
        foreach (ProgressSize::cases() as $size) {
            $html = (string) $this->blade(
                '<x-pajak::progress :value="40" :size="$size" />',
                ['size' => $size],
            );

            $this->assertMatchesHtmlSnapshot($html);
        }
    }

    public function testColors(): void
    {
        foreach (ProgressColor::cases() as $color) {
            $html = (string) $this->blade(
                '<x-pajak::progress :value="75" :color="$color" />',
                ['color' => $color],
            );

            $this->assertMatchesHtmlSnapshot($html);
        }
    }

    public function testLabeledWithValue(): void
    {
        $html = (string) $this->blade(
            '<x-pajak::progress :value="75" label="Income" :show-value="true" />',
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testLabeledSuccess(): void
    {
        $html = (string) $this->blade(
            '<x-pajak::progress :value="100" :color="$color" label="Personal details" :show-value="true" />',
            ['color' => ProgressColor::Success],
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testZeroValue(): void
    {
        $html = (string) $this->blade(
            '<x-pajak::progress :value="0" label="Review &amp; submit" :show-value="true" />',
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testCustomMax(): void
    {
        $html = (string) $this->blade(
            '<x-pajak::progress :value="7" :max="10" :show-value="true" />',
        );

        $this->assertMatchesHtmlSnapshot($html);
    }
}
