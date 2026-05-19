<?php

declare(strict_types=1);

namespace Pajak\Ui\Tests\Integration\Common;

use Pajak\Ui\Common\Enums\SkeletonShape;
use Pajak\Ui\Tests\Integration\TestCase;

final class SkeletonSnapshotTest extends TestCase
{
    public function testDefaultSkeleton(): void
    {
        $html = (string) $this->blade('<x-pajak::skeleton />');

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testAllShapes(): void
    {
        foreach (SkeletonShape::cases() as $shape) {
            $html = (string) $this->blade(
                '<x-pajak::skeleton :shape="$shape" />',
                ['shape' => $shape],
            );

            $this->assertMatchesHtmlSnapshot($html);
        }
    }

    public function testWithWidthAttribute(): void
    {
        $html = (string) $this->blade(
            '<x-pajak::skeleton style="width: 60%" />',
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testCircleWithExplicitSize(): void
    {
        $html = (string) $this->blade(
            '<x-pajak::skeleton :shape="$shape" style="width: 40px; height: 40px;" />',
            ['shape' => SkeletonShape::Circle],
        );

        $this->assertMatchesHtmlSnapshot($html);
    }
}
