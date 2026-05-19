<?php

declare(strict_types=1);

namespace Pajak\Ui\Tests\Integration\Common;

use Pajak\Ui\Common\Enums\Card\CardVariant;
use Pajak\Ui\Tests\Integration\TestCase;

final class CardSnapshotTest extends TestCase
{
    public function testDefaultCard(): void
    {
        $html = (string) $this->blade('<x-pajak::card>Content here.</x-pajak::card>');

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testCardWithKicker(): void
    {
        $html = (string) $this->blade(
            <<<'BLADE'
            <x-pajak::card>
                <x-slot:kicker>Refund</x-slot:kicker>
                PLN 1,240
            </x-pajak::card>
            BLADE,
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testCardWithTitle(): void
    {
        $html = (string) $this->blade(
            <<<'BLADE'
            <x-pajak::card>
                <x-slot:title>Home Office</x-slot:title>
                You may qualify for up to PLN 3,600 annually.
            </x-pajak::card>
            BLADE,
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testCardWithFooter(): void
    {
        $html = (string) $this->blade(
            <<<'BLADE'
            <x-pajak::card>
                <x-slot:title>Filing Status</x-slot:title>
                Individual taxpayer · 2025
                <x-slot:footer>On track</x-slot:footer>
            </x-pajak::card>
            BLADE,
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testAccentVariant(): void
    {
        $html = (string) $this->blade(
            '<x-pajak::card :variant="$variant">Content.</x-pajak::card>',
            ['variant' => CardVariant::Accent],
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testAllSlots(): void
    {
        $html = (string) $this->blade(
            <<<'BLADE'
            <x-pajak::card>
                <x-slot:kicker>Deductions</x-slot:kicker>
                <x-slot:title>Home Office</x-slot:title>
                You may qualify for up to PLN 3,600 annually.
                <x-slot:footer><span>New</span></x-slot:footer>
            </x-pajak::card>
            BLADE,
        );

        $this->assertMatchesHtmlSnapshot($html);
    }
}
