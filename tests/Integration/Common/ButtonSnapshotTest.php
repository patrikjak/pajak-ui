<?php

declare(strict_types=1);

namespace Pajak\Ui\Tests\Integration\Common;

use Pajak\Ui\Common\Enums\Size;
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
}
