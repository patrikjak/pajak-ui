<?php

declare(strict_types=1);

namespace Pajak\Ui\Tests\Integration\Common;

use Pajak\Ui\Common\Enums\ActionTile\ActionTileColor;
use Pajak\Ui\Tests\Integration\TestCase;

final class ActionTileTest extends TestCase
{
    public function testRendersLinkWithTitleAndDescription(): void
    {
        $html = (string) $this->blade(
            '<x-pajak::action-tile href="/returns/new" title="New return" description="Start a filing" />',
        );

        $this->assertStringContainsString('<a class="pajak-action-tile" href="/returns/new"', $html);
        $this->assertStringContainsString('pajak-action-tile__title">New return', $html);
        $this->assertStringContainsString('pajak-action-tile__description">Start a filing', $html);
    }

    public function testIconSlotUsesColorVariant(): void
    {
        $html = (string) $this->blade(
            <<<'BLADE'
            <x-pajak::action-tile title="T" :color="$color">
                <x-slot:icon><svg></svg></x-slot:icon>
            </x-pajak::action-tile>
            BLADE,
            ['color' => ActionTileColor::Sand],
        );

        $this->assertStringContainsString('pajak-action-tile__icon pajak-action-tile__icon--sand', $html);
    }

    public function testNoIconOrDescriptionRendersOnlyTitle(): void
    {
        $html = (string) $this->blade('<x-pajak::action-tile title="T" />');

        $this->assertStringNotContainsString('pajak-action-tile__icon', $html);
        $this->assertStringNotContainsString('pajak-action-tile__description', $html);
    }
}
