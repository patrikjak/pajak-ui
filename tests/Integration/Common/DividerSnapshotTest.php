<?php

declare(strict_types=1);

namespace Pajak\Ui\Tests\Integration\Common;

use Pajak\Ui\Common\Enums\Divider\DividerOrientation;
use Pajak\Ui\Common\Enums\Divider\DividerStrength;
use Pajak\Ui\Common\Enums\Divider\DividerStyle;
use Pajak\Ui\Tests\Integration\TestCase;

final class DividerSnapshotTest extends TestCase
{
    public function testDefaultDivider(): void
    {
        $html = (string) $this->blade('<x-pajak::divider />');

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testStrongDivider(): void
    {
        $html = (string) $this->blade(
            '<x-pajak::divider :strength="$strength" />',
            ['strength' => DividerStrength::Strong],
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testSubtleDivider(): void
    {
        $html = (string) $this->blade(
            '<x-pajak::divider :strength="$strength" />',
            ['strength' => DividerStrength::Subtle],
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testDashedDivider(): void
    {
        $html = (string) $this->blade(
            '<x-pajak::divider :style="$style" />',
            ['style' => DividerStyle::Dashed],
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testDottedDivider(): void
    {
        $html = (string) $this->blade(
            '<x-pajak::divider :style="$style" />',
            ['style' => DividerStyle::Dotted],
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testVerticalDivider(): void
    {
        $html = (string) $this->blade(
            '<x-pajak::divider :orientation="$orientation" />',
            ['orientation' => DividerOrientation::Vertical],
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testLabeledDivider(): void
    {
        $html = (string) $this->blade('<x-pajak::divider :labeled="true">or</x-pajak::divider>');

        $this->assertMatchesHtmlSnapshot($html);
    }
}
