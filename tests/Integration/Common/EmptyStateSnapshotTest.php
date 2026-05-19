<?php

declare(strict_types=1);

namespace Pajak\Ui\Tests\Integration\Common;

use Pajak\Ui\Common\Enums\EmptyState\EmptyStateVariant;
use Pajak\Ui\Tests\Integration\TestCase;

final class EmptyStateSnapshotTest extends TestCase
{
    public function testDefaultWithAllSlots(): void
    {
        $html = (string) $this->blade(
            <<<'BLADE'
            <x-pajak::empty-state>
                <x-slot:icon>
                    <svg width="26" height="26" viewBox="0 0 24 24" fill="none"
                        stroke="#5386E4" stroke-width="1.75"><circle cx="12" cy="12" r="10"/></svg>
                </x-slot:icon>
                <x-slot:title>No documents uploaded yet</x-slot:title>
                <x-slot:message>Drop your files here to get started.</x-slot:message>
                <x-slot:actions>
                    <button type="button">Upload</button>
                </x-slot:actions>
            </x-pajak::empty-state>
            BLADE,
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testDashedVariant(): void
    {
        $html = (string) $this->blade(
            <<<'BLADE'
            <x-pajak::empty-state :variant="$variant">
                <x-slot:icon>
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none"
                        stroke="#5386E4" stroke-width="1.75"><line x1="12" y1="5" x2="12" y2="19"/></svg>
                </x-slot:icon>
                <x-slot:title>Drop documents here</x-slot:title>
                <x-slot:message>PDF, JPG, PNG — up to 10 MB each</x-slot:message>
            </x-pajak::empty-state>
            BLADE,
            ['variant' => EmptyStateVariant::Dashed],
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testCompactVariant(): void
    {
        $html = (string) $this->blade(
            <<<'BLADE'
            <x-pajak::empty-state :variant="$variant">
                <x-slot:title>All caught up</x-slot:title>
                <x-slot:message>No outstanding tasks for tax year 2025.</x-slot:message>
            </x-pajak::empty-state>
            BLADE,
            ['variant' => EmptyStateVariant::Compact],
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testWithoutActions(): void
    {
        $html = (string) $this->blade(
            <<<'BLADE'
            <x-pajak::empty-state>
                <x-slot:title>No matches found</x-slot:title>
                <x-slot:message>Try a different keyword.</x-slot:message>
            </x-pajak::empty-state>
            BLADE,
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testWithoutIcon(): void
    {
        $html = (string) $this->blade(
            <<<'BLADE'
            <x-pajak::empty-state>
                <x-slot:title>Nothing here yet</x-slot:title>
            </x-pajak::empty-state>
            BLADE,
        );

        $this->assertMatchesHtmlSnapshot($html);
    }
}
