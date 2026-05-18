<?php

declare(strict_types=1);

namespace Pajak\Ui\Tests\Integration\Common;

use Pajak\Ui\Common\Enums\BadgeColor;
use Pajak\Ui\Common\Enums\Size;
use Pajak\Ui\Tests\Integration\TestCase;

final class BadgeSnapshotTest extends TestCase
{
    public function testDefaultBadge(): void
    {
        $html = (string) $this->blade('<x-pajak::badge>New</x-pajak::badge>');

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testSuccessBadge(): void
    {
        $html = (string) $this->blade(
            '<x-pajak::badge :color="$color">Complete</x-pajak::badge>',
            ['color' => BadgeColor::Success],
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testWarningBadge(): void
    {
        $html = (string) $this->blade(
            '<x-pajak::badge :color="$color">In Progress</x-pajak::badge>',
            ['color' => BadgeColor::Warning],
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testErrorBadge(): void
    {
        $html = (string) $this->blade(
            '<x-pajak::badge :color="$color">Overdue</x-pajak::badge>',
            ['color' => BadgeColor::Error],
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testInfoBadge(): void
    {
        $html = (string) $this->blade(
            '<x-pajak::badge :color="$color">Info</x-pajak::badge>',
            ['color' => BadgeColor::Info],
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testNeutralBadge(): void
    {
        $html = (string) $this->blade(
            '<x-pajak::badge :color="$color">Draft</x-pajak::badge>',
            ['color' => BadgeColor::Neutral],
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testSmallBadge(): void
    {
        $html = (string) $this->blade(
            '<x-pajak::badge :size="$size">Small</x-pajak::badge>',
            ['size' => Size::Sm],
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testLargeBadge(): void
    {
        $html = (string) $this->blade(
            '<x-pajak::badge :size="$size">Large</x-pajak::badge>',
            ['size' => Size::Lg],
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testOutlineBadge(): void
    {
        $html = (string) $this->blade('<x-pajak::badge :outline="true">Outline</x-pajak::badge>');

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testOutlineSuccessBadge(): void
    {
        $html = (string) $this->blade(
            '<x-pajak::badge :color="$color" :outline="true">Complete</x-pajak::badge>',
            ['color' => BadgeColor::Success],
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testBadgeWithDot(): void
    {
        $html = (string) $this->blade('<x-pajak::badge :dot="true">Active</x-pajak::badge>');

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testSuccessBadgeWithDot(): void
    {
        $html = (string) $this->blade(
            '<x-pajak::badge :color="$color" :dot="true">Complete</x-pajak::badge>',
            ['color' => BadgeColor::Success],
        );

        $this->assertMatchesHtmlSnapshot($html);
    }
}
