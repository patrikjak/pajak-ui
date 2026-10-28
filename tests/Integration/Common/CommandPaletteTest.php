<?php

declare(strict_types=1);

namespace Pajak\Ui\Tests\Integration\Common;

use Pajak\Ui\Common\Enums\Method;
use Pajak\Ui\Tests\Integration\TestCase;

final class CommandPaletteTest extends TestCase
{
    public function testRendersDialogWithComboboxAndListbox(): void
    {
        $html = (string) $this->blade('<x-pajak::command-palette id="palette" placeholder="Find" empty="Nothing" />');

        $this->assertStringContainsString('id="palette"', $html);
        $this->assertStringContainsString('data-pajak-command-palette', $html);
        $this->assertStringContainsString('role="combobox"', $html);
        $this->assertStringContainsString('aria-controls="palette-list"', $html);
        $this->assertStringContainsString('role="listbox"', $html);
        $this->assertStringContainsString('placeholder="Find"', $html);
        $this->assertStringContainsString('Nothing', $html);
    }

    public function testFallsBackToTranslatedTexts(): void
    {
        $html = (string) $this->blade('<x-pajak::command-palette />');

        $this->assertStringContainsString('id="pajak-command-palette"', $html);
        $this->assertStringContainsString(__('pajak::ui.command_palette.placeholder'), $html);
        $this->assertStringContainsString(__('pajak::ui.command_palette.empty'), $html);
    }

    public function testLinkItemRendersAnchorWithHintAndKeywords(): void
    {
        $html = (string) $this->blade(
            <<<'BLADE'
            <x-pajak::command-palette-group label="Pages">
                <x-pajak::command-palette-item label="Users" href="/users" hint="Admin" :keywords="['people', 'team']"
                    />
            </x-pajak::command-palette-group>
            BLADE,
        );

        $this->assertStringContainsString('aria-label="Pages"', $html);
        $this->assertStringContainsString('<a class="pajak-cmdk__option" href="/users" role="option"', $html);
        $this->assertStringContainsString('data-keywords="people team"', $html);
        $this->assertStringContainsString('pajak-cmdk__hint', $html);
        $this->assertStringContainsString('Admin', $html);
    }

    public function testActionItemRendersCsrfFormWithMethodSpoofing(): void
    {
        $html = (string) $this->blade(
            '<x-pajak::command-palette-item label="Log out" action="/logout" :method="$method" />',
            ['method' => Method::Delete],
        );

        $this->assertStringContainsString('<form action="/logout" method="POST"', $html);
        $this->assertStringContainsString('name="_token"', $html);
        $this->assertStringContainsString('name="_method" value="DELETE"', $html);
        $this->assertStringContainsString('type="submit"', $html);
    }

    public function testEventItemRendersButtonWithCommand(): void
    {
        $html = (string) $this->blade('<x-pajak::command-palette-item label="Dark theme" event="theme.dark" />');

        $this->assertStringContainsString('type="button"', $html);
        $this->assertStringContainsString('data-pajak-command="theme.dark"', $html);
        $this->assertStringNotContainsString('<form', $html);
    }

    public function testItemWithoutHintOrIconRendersOnlyLabel(): void
    {
        $html = (string) $this->blade('<x-pajak::command-palette-item label="Plain" href="/x" />');

        $this->assertStringNotContainsString('pajak-cmdk__hint', $html);
        $this->assertStringNotContainsString('pajak-cmdk__icon', $html);
    }

    public function testTriggerPointsAtPalette(): void
    {
        $html = (string) $this->blade('<x-pajak::command-palette-trigger for="palette" label="Search" />');

        $this->assertStringContainsString('data-pajak-command-palette-trigger="palette"', $html);
        $this->assertStringContainsString('data-pajak-command-palette-shortcut', $html);
        $this->assertStringContainsString('Search', $html);
    }
}
