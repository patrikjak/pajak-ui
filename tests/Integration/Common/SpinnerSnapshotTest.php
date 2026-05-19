<?php

declare(strict_types=1);

namespace Pajak\Ui\Tests\Integration\Common;

use Pajak\Ui\Common\Enums\SpinnerColor;
use Pajak\Ui\Common\Enums\SpinnerSize;
use Pajak\Ui\Common\Enums\SpinnerType;
use Pajak\Ui\Tests\Integration\TestCase;

final class SpinnerSnapshotTest extends TestCase
{
    public function testDefaultArcSpinner(): void
    {
        $html = (string) $this->blade('<x-pajak::spinner />');

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testArcSpinnerSizes(): void
    {
        foreach (SpinnerSize::cases() as $size) {
            $html = (string) $this->blade(
                '<x-pajak::spinner :size="$size" />',
                ['size' => $size],
            );

            $this->assertMatchesHtmlSnapshot($html);
        }
    }

    public function testArcSpinnerColors(): void
    {
        foreach (SpinnerColor::cases() as $color) {
            $html = (string) $this->blade(
                '<x-pajak::spinner :color="$color" />',
                ['color' => $color],
            );

            $this->assertMatchesHtmlSnapshot($html);
        }
    }

    public function testDotsSpinner(): void
    {
        $html = (string) $this->blade(
            '<x-pajak::spinner :type="$type" />',
            ['type' => SpinnerType::Dots],
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testDotsSpinnerNeutralColor(): void
    {
        $html = (string) $this->blade(
            '<x-pajak::spinner :type="$type" :color="$color" />',
            ['type' => SpinnerType::Dots, 'color' => SpinnerColor::Neutral],
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testBarSpinner(): void
    {
        $html = (string) $this->blade(
            '<x-pajak::spinner :type="$type" />',
            ['type' => SpinnerType::Bar],
        );

        $this->assertMatchesHtmlSnapshot($html);
    }
}
