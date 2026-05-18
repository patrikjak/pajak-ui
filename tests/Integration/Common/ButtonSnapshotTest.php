<?php

declare(strict_types=1);

namespace Pajak\Ui\Tests\Integration\Common;

use Pajak\Ui\Common\Enums\Size;
use Pajak\Ui\Common\Enums\Variant;
use Pajak\Ui\Tests\Integration\TestCase;

final class ButtonSnapshotTest extends TestCase
{
    public function testDefaultButton(): void
    {
        $html = (string) $this->blade('<x-pajak::button>Click me</x-pajak::button>');

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testDisabledButton(): void
    {
        $html = (string) $this->blade('<x-pajak::button :disabled="true">Click me</x-pajak::button>');

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testSmallButton(): void
    {
        $html = (string) $this->blade(
            '<x-pajak::button :size="$size">Click me</x-pajak::button>',
            ['size' => Size::Sm],
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testLargeButton(): void
    {
        $html = (string) $this->blade(
            '<x-pajak::button :size="$size">Click me</x-pajak::button>',
            ['size' => Size::Lg],
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testSecondaryButton(): void
    {
        $html = (string) $this->blade(
            '<x-pajak::button :variant="$variant">Save Draft</x-pajak::button>',
            ['variant' => Variant::Secondary],
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testOutlineButton(): void
    {
        $html = (string) $this->blade(
            '<x-pajak::button :variant="$variant">View Details</x-pajak::button>',
            ['variant' => Variant::Outline],
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testGhostButton(): void
    {
        $html = (string) $this->blade(
            '<x-pajak::button :variant="$variant">Cancel</x-pajak::button>',
            ['variant' => Variant::Ghost],
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testDangerButton(): void
    {
        $html = (string) $this->blade(
            '<x-pajak::button :variant="$variant">Delete</x-pajak::button>',
            ['variant' => Variant::Danger],
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testLoadingButton(): void
    {
        $html = (string) $this->blade('<x-pajak::button :loading="true">Saving</x-pajak::button>');

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testLoadingSmallButton(): void
    {
        $html = (string) $this->blade(
            '<x-pajak::button :size="$size" :loading="true">Saving</x-pajak::button>',
            ['size' => Size::Sm],
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testLoadingLargeButton(): void
    {
        $html = (string) $this->blade(
            '<x-pajak::button :size="$size" :loading="true">Submitting</x-pajak::button>',
            ['size' => Size::Lg],
        );

        $this->assertMatchesHtmlSnapshot($html);
    }
}
