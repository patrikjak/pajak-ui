<?php

declare(strict_types=1);

namespace Pajak\Ui\Tests\Integration\Common;

use Pajak\Ui\Common\Enums\Dialog\DialogType;
use Pajak\Ui\Tests\Integration\TestCase;

final class DialogSnapshotTest extends TestCase
{
    public function testDefaultInfoDialog(): void
    {
        $html = (string) $this->blade(
            '<x-pajak::dialog id="test-dialog" />',
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testSuccessType(): void
    {
        $html = (string) $this->blade(
            '<x-pajak::dialog id="test-dialog" :type="$type" />',
            ['type' => DialogType::Success],
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testWarningType(): void
    {
        $html = (string) $this->blade(
            '<x-pajak::dialog id="test-dialog" :type="$type" />',
            ['type' => DialogType::Warning],
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testDangerType(): void
    {
        $html = (string) $this->blade(
            '<x-pajak::dialog id="test-dialog" :type="$type" />',
            ['type' => DialogType::Danger],
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testWithTitleAndDescription(): void
    {
        $html = (string) $this->blade(
            <<<'BLADE'
            <x-pajak::dialog id="test-dialog" title="Your session has ended">
                <x-slot:description>For your security, you've been signed out.</x-slot:description>
            </x-pajak::dialog>
            BLADE,
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testWithActions(): void
    {
        $html = (string) $this->blade(
            <<<'BLADE'
            <x-pajak::dialog id="test-dialog" title="Discard unsaved changes?" :type="$type">
                <x-slot:description>Leaving now will lose your changes.</x-slot:description>
                <x-slot:actions>
                    <button>Keep editing</button>
                    <button>Discard</button>
                </x-slot:actions>
            </x-pajak::dialog>
            BLADE,
            ['type' => DialogType::Danger],
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testStackedActions(): void
    {
        $html = (string) $this->blade(
            <<<'BLADE'
            <x-pajak::dialog id="test-dialog" title="Missing required field" :stacked="true">
                <x-slot:actions>
                    <button>Add now</button>
                    <button>Continue anyway</button>
                </x-slot:actions>
            </x-pajak::dialog>
            BLADE,
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testWithCustomIcon(): void
    {
        $html = (string) $this->blade(
            <<<'BLADE'
            <x-pajak::dialog id="test-dialog" title="Return submitted">
                <x-slot:icon><svg width="24" height="24" aria-hidden="true"></svg></x-slot:icon>
            </x-pajak::dialog>
            BLADE,
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testOpenProp(): void
    {
        $html = (string) $this->blade(
            '<x-pajak::dialog id="test-dialog" :open="true" title="Session ended" />',
        );

        $this->assertMatchesHtmlSnapshot($html);
    }
}
