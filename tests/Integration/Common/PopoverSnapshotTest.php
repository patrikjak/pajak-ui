<?php

declare(strict_types=1);

namespace Pajak\Ui\Tests\Integration\Common;

use Pajak\Ui\Common\Enums\Popover\PopoverPlacement;
use Pajak\Ui\Tests\Integration\TestCase;

final class PopoverSnapshotTest extends TestCase
{
    public function testDefaultPopover(): void
    {
        $html = (string) $this->blade('<x-pajak::popover id="test-pop" />');

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testWithTitle(): void
    {
        $html = (string) $this->blade('<x-pajak::popover id="test-pop" title="Tax bracket" />');

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testNotDismissible(): void
    {
        $html = (string) $this->blade(
            '<x-pajak::popover id="test-pop" title="Info" :dismissible="false" />',
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testNeitherTitleNorDismissible(): void
    {
        $html = (string) $this->blade(
            '<x-pajak::popover id="test-pop" :dismissible="false" />',
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testWithBody(): void
    {
        $html = (string) $this->blade(
            <<<'BLADE'
            <x-pajak::popover id="test-pop" title="Details">
                <p>Some body content.</p>
            </x-pajak::popover>
            BLADE,
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testWithFooter(): void
    {
        $html = (string) $this->blade(
            <<<'BLADE'
            <x-pajak::popover id="test-pop" title="Confirm">
                Are you sure?
                <x-slot:footer>
                    <button>Cancel</button>
                    <button>Confirm</button>
                </x-slot:footer>
            </x-pajak::popover>
            BLADE,
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testPlacementBottomEnd(): void
    {
        $html = (string) $this->blade(
            '<x-pajak::popover id="test-pop" :placement="$placement" />',
            ['placement' => PopoverPlacement::BottomEnd],
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testPlacementTop(): void
    {
        $html = (string) $this->blade(
            '<x-pajak::popover id="test-pop" :placement="$placement" />',
            ['placement' => PopoverPlacement::Top],
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testPlacementRight(): void
    {
        $html = (string) $this->blade(
            '<x-pajak::popover id="test-pop" :placement="$placement" />',
            ['placement' => PopoverPlacement::Right],
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testPlacementLeft(): void
    {
        $html = (string) $this->blade(
            '<x-pajak::popover id="test-pop" :placement="$placement" />',
            ['placement' => PopoverPlacement::Left],
        );

        $this->assertMatchesHtmlSnapshot($html);
    }
}
